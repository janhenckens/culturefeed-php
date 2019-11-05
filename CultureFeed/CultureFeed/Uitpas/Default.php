<?php

/**
 *
 */
class CultureFeed_Uitpas_Default implements CultureFeed_Uitpas {

  use Culturefeed_ValidationTrait;
  
  /**
   *
   * CultureFeed object to make CultureFeed core requests.
   * @var ICultureFeed
   */
  protected $culturefeed;

  /**
   * OAuth request object to do the request.
   *
   * @var CultureFeed_OAuthClient
   */
  protected $oauth_client;

  /**
   *
   * Constructor for a new UitPas_Default instance
   * @param ICultureFeed $culturefeed
   */
  public function __construct(ICultureFeed $culturefeed) {
    $this->culturefeed = $culturefeed;
    $this->oauth_client = $culturefeed->getClient();
  }

  /**
   * {@inheritdoc}
   */
  public function getCouponsForPassholder($uitpas_number, $consumer_key_counter = NULL, $max = NULL, $start = NULL) {
    $data = array();
    $path = 'uitpas/passholder/' . $uitpas_number . '/coupons';

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    if ($max) {
      $data['max'] = $max;
    }

    if ($start) {
      $data['start'] = $start;
    }

    $result = $this->oauth_client->authenticatedGetAsXML($path, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $coupons = array();
    $objects = $xml->xpath('/ticketSaleCoupons/ticketSaleCoupon');
    $total = count($objects);

    foreach ($objects as $object) {
      $coupons[] = CultureFeed_Uitpas_Event_TicketSale_Coupon::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $coupons);
  }

  /**
   * {@inheritdoc}
   */
  public function getAssociations($consumer_key_counter = NULL, $readPermission = NULL, $registerPermission = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    // The parameters reflect the existing UiTPAS API.
    // You have to leave out permissions completely if you don't want to
    // filter at all.
    // Filter values should be strings, because booleans would be casted to 0
    // or 1 and the API would not be able to parse those apparently.
    if (!is_null($readPermission)) {
      $data['readPermission'] = $readPermission ? 'true' : 'false';
    }
    if (!is_null($registerPermission)) {
      $data['registerPermission'] = $registerPermission ? 'true' : 'false';
    }

    $result = $this->oauth_client->authenticatedGetAsXML('uitpas/association/list', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $associations = array();
    $objects = $xml->xpath('/response/associations/association');
    $total = count($objects);

    foreach ($objects as $object) {
      $associations[] = CultureFeed_Uitpas_Association::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $associations);
  }

  /**
   * {@inheritdoc}
   */
  public function getDistributionKeysForOrganizer($cdbid) {
    $result = $this->oauth_client->consumerGetAsXML('uitpas/distributionkey/organiser/' . $cdbid, array());
    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $distribution_keys = array();

    foreach ($xml->xpath('/response/cardSystems/cardSystem') as $cardSystemXml) {
      $cardSystem = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);

      $objects = $cardSystemXml->xpath('distributionKeys/distributionKey');

      foreach ($objects as $object) {
        $distributionKey = CultureFeed_Uitpas_DistributionKey::createFromXML($object);
        $distributionKey->cardSystem = $cardSystem;
        $distribution_keys[] = $distributionKey;
      }
    }

    $total = count($distribution_keys);
    return new CultureFeed_ResultSet($total, $distribution_keys);
  }

  /**
   * {@inheritdoc}
   */
  public function getCardSystemsForOrganizer($cdbid) {
    $result = $this->oauth_client->consumerGetAsXML('uitpas/distributionkey/organiser/' . $cdbid, []);
    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    } catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $cardSystems = [];
    foreach ($xml->xpath('/response/cardSystems/cardSystem') as $cardSystemXml) {
      $cardSystems[] = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);
    }

    $total = count($cardSystems);
    return new CultureFeed_ResultSet($total, $cardSystems);
  }

  /**
   * Register a set of distribution keys for an organizer. The entire set (including existing)
   * of distribution keys must be provided.
   *
   * @param string $cdbid The CDBID of the organizer
   * @param array $distribution_keys The identification of the distribution key
   */
  public function registerDistributionKeysForOrganizer($cdbid, $distribution_keys) {
    $this->oauth_client->consumerPostAsXml('uitpas/distributionkey/organiser/' . $cdbid, $distribution_keys);
  }

  /**
   * {@inheritdoc}
   */
  public function getPrice($consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/uitpasPrice', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $prices = array();

    foreach ($xml->xpath('uitpasPrices/uitpasPrice') as $price_xml) {
      $prices[] = CultureFeed_Uitpas_Passholder_UitpasPrice::createFromXML($price_xml);
    }

    $total = count($prices);

    return new CultureFeed_ResultSet($total, $prices);
  }

  /**
   * @inheritdoc
   */
  public function getPriceByUitpas($uitpas_number, $reason, $date_of_birth = null, $postal_code = null, $voucher_number = null, $consumer_key_counter = NULL) {
    $data = array(
      'reason' => $reason,
      'uitpasNumber' => $uitpas_number,
    );

    return $this->requestPrice($data, $date_of_birth, $postal_code, $voucher_number, $consumer_key_counter);
  }

  /**
   * @inheritdoc
   */
  public function getPriceForUpgrade($card_system_id, $date_of_birth, $postal_code = null, $voucher_number = null, $consumer_key_counter = null) {
    $reason = CultureFeed_Uitpas_Passholder_UitpasPrice::REASON_CARD_UPGRADE;

    $data = array(
      'reason' => $reason,
      'cardSystemId' => $card_system_id
    );

    return $this->requestPrice($data, $date_of_birth, $postal_code, $voucher_number, $consumer_key_counter);
  }

  /**
   * @param $data
   */
  private function requestPrice($data, $date_of_birth = null, $postal_code = null, $voucher_number = null, $consumer_key_counter = null) {
    if (!is_null($date_of_birth)) {
      $data['dateOfBirth'] = date('Y-m-d', $date_of_birth);
    }
    if (!is_null($postal_code)) {
      $data['postalCode'] = $postal_code;
    }
    if (!is_null($voucher_number)) {
      $data['voucherNumber'] = $voucher_number;
    }
    if (!is_null($consumer_key_counter)) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/price', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $price_xml = $xml->xpath('uitpasPrice', FALSE);
    if (!($price_xml instanceof CultureFeed_SimpleXMLElement)) {
      throw new LogicException('Could not find expected uitpasPrice tag in response XML.');
    }

    return CultureFeed_Uitpas_Passholder_UitpasPrice::createFromXML($price_xml);
  }


  /**
   * Create a new UitPas passholder.
   *
   * @param CultureFeed_Uitpas_Passholder $passholder The new passholder
   * @param null $consumer_key_counter
   * @return Passholder user ID
   * @throws \CultureFeed_ParseException
   * @throws \CultureFeed_Uitpas_PassholderException
   */
  public function createPassholder(CultureFeed_Uitpas_Passholder $passholder, $consumer_key_counter = NULL) {
    $data = $passholder->toPostData();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/register', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $code = $xml->xpath_str('/response/code');
    if ($code == 'INSZ_ALREADY_USED') {

      $exception = CultureFeed_Uitpas_PassholderException::createFromXML($code, $xml);
      throw $exception;

    }

    return $xml->xpath_str('/response/message');
  }

  /**
   * Create a new membership for a UitPas passholder.
   *
   * @param CultureFeed_Uitpas_Membership $membership The membership object of the UitPas passholder
   */
  public function createMembershipForPassholder(CultureFeed_Uitpas_Passholder_Membership $membership) {
    $data = $membership->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/membership', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  /**
   * Register an event.
   *
   * @param CultureFeed_Uitpas_Event_CultureEvent $event The event data that needs to be sent over.
   * @return CultureFeed_Uitpas_Response
   */
  public function registerEvent(CultureFeed_Uitpas_Event_CultureEvent $event) {
    return $this->consumerPostWithSimpleResponse(
      'uitpas/cultureevent/register',
      $event
    );
  }

  /**
   * @inheritdoc
   */
  public function getEvent($id) {
    $result = $this->oauth_client->consumerGetAsXml('uitpas/cultureevent/' . $id);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return CultureFeed_Uitpas_Event_CultureEvent::createFromXML($xml);
  }

  /**
   * {@inheritdoc}
   */
  public function getCardSystemsForEvent($cdbid) {
    $result = $this->oauth_client->consumerGetAsXML('uitpas/cultureevent/' . $cdbid . '/cardsystems', []);
    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    } catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $cardSystems = [];
    foreach ($xml->xpath('/response/cardSystems/cardSystem') as $cardSystemXml) {
      $cardSystems[] = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);
    }

    $total = count($cardSystems);
    return new CultureFeed_ResultSet($total, $cardSystems);
  }

  /**
   * {@inheritdoc}
   */
  public function eventHasTicketSales($cdbid) {
    $result = $this->oauth_client->consumerGetAsXML('uitpas/cultureevent/' . $cdbid . '/hasticketsales');

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    } catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $responseTag = $xml->xpath('/response', false);
    $code = $responseTag->xpath_str('code');
    $hasTicketSales = $responseTag->xpath_bool('hasTicketSales');

    if ($code === 'ACTION_SUCCEEDED') {
      return (bool) $hasTicketSales;
    } elseif ($code === 'UNKNOWN_EVENT_CDBID') {
      throw new CultureFeed_HttpException($result, 404);
    } else {
      throw new CultureFeed_Cdb_ParseException('Got unknown response code ' . $code);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addCardSystemToEvent($cdbid, $cardSystemId, $distributionKey = NULL) {
    $postData = array_filter(
      [
        'cardSystemId' => $cardSystemId,
        'distributionKey' => $distributionKey,
      ]
    );

    return $this->consumerPostWithSimpleResponse('uitpas/cultureevent/' . $cdbid . '/cardsystems', $postData);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteCardSystemFromEvent($cdbid, $cardSystemId) {
    $result = $this->oauth_client->request(
      'uitpas/cultureevent/' . $cdbid . '/cardsystems/' . $cardSystemId,
      [],
      'DELETE',
      FALSE
    );

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));

    return $response;
  }

  /**
   * Performs a consumer authenticated POST request expecting a simple response.
   *
   * @param string $path
   *   Path to post to.
   * @param CultureFeed_Uitpas_ValueObject|array $data
   *   Post data.
   * @return \CultureFeed_Uitpas_Response
   *   The simple response.
   * @throws \CultureFeed_ParseException
   *   When the returned payload is not valid XML.
   */
  private function consumerPostWithSimpleResponse($path, $data) {
    if (is_object($data) && $data instanceof CultureFeed_Uitpas_ValueObject) {
      $data = $data->toPostData();
    }

    $result = $this->oauth_client->consumerPostAsXml($path, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));

    return $response;
  }

  /**
   * @inheritdoc
   */
  public function updateEvent(CultureFeed_Uitpas_Event_CultureEvent $event) {
    return $this->consumerPostWithSimpleResponse(
      'uitpas/cultureevent/update',
      $event
    );
  }

  /**
   * Resend the activation e-mail for a passholder
   *
   * @param string $uitpas_number The UitPas number
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function resendActivationEmail($uitpas_number, $consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $uitpas_number . '/resend_activation_mail', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  /**
   * Get a passholder based on the UitPas number.
   *
   * @param string $uitpas_number The UitPas number
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function getPassholderByUitpasNumber($uitpas_number, $consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/' . $uitpas_number, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $object = $xml->xpath('/passHolder', false);

    return CultureFeed_Uitpas_Passholder::createFromXml($object);
  }

  /**
   * Get a card, with optionally a passholder, or a group pass based on a identification number.
   *
   * @param string $identification_number
   *   The identification number. This can be either an UiTPAS number, chip-number, INSZ-number, or INSZ-barcode.
   * @param string $consumer_key_counter
   *   The consumer key of the counter from where the request originates
   * @return CultureFeed_Uitpas_Identity
   *
   * @throws CultureFeed_ParseException
   *   When the response XML could not be parsed.
   */
  public function identify($identification_number, $consumer_key_counter = NULL) {
    $data = array(
      'identification' => $identification_number,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/retrieve', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $object = $xml->xpath('/response', false);

    return CultureFeed_Uitpas_Identity::createFromXml($object);
  }

  /**
   * Get a passholder based on the user ID
   *
   * @param string $user_id The user ID
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function getPassholderByUser($user_id, $consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/uid/' . $user_id, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return CultureFeed_Uitpas_Passholder::createFromXml($xml);
  }

  /**
   * {@inheritdoc}
   */
  public function searchPassholders(CultureFeed_Uitpas_Passholder_Query_SearchPassholdersOptions $query, $method = CultureFeed_Uitpas::CONSUMER_REQUEST) {
    $data = $query->toPostData();

    if ($method == CultureFeed_Uitpas::CONSUMER_REQUEST) {
      $result = $this->oauth_client->consumerGetAsXml('uitpas/passholder/search', $data);
    }
    else {
      $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/search', $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $passholders = array();
    $objects = $xml->xpath('/response/passholders/passholder');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $passholders[] = CultureFeed_Uitpas_Passholder::createFromXML($object);
    }

    $invalidUitpasNumbers = $xml->xpath_str('/response/invalidUitpasNumbers/invalidUitpasNumber', TRUE);

    return new CultureFeed_Uitpas_Passholder_ResultSet($total, $passholders, $invalidUitpasNumbers);
  }

  /**
   * Get the welcome advantages for a passholder.
   *
   * @param CultureFeed_Uitpas_Passholder_Query_WelcomeAdvantagesOptions $query The query
   *
   * @return CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet
   */
  public function getWelcomeAdvantagesForPassholder(CultureFeed_Uitpas_Passholder_Query_WelcomeAdvantagesOptions $query) {
    $data = $query->toPostData();
    unset($data['uitpas_number']);
    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/' . $query->uitpas_number . '/welcomeadvantages', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    // Can not use CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet::createfromXML() here
    // because the response format is not consistent.
    // It lacks a 'total' element for example.
    $promotion_elements = $xml->xpath('promotion');
    $promotions = array();
    foreach ($promotion_elements as $promotion_element) {
      $promotions[] = CultureFeed_Uitpas_Passholder_WelcomeAdvantage::createFromXML($promotion_element);
    }
    $total = count($promotions);

    $advantages = new CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet($total, $promotions);
    return $advantages;
  }

  /**
   * Check in a passholder.
   *
   * Provide either a UitPas number or chip number. You cannot provide both.
   *
   * @param CultureFeed_Uitpas_Passholder_Query_CheckInPassholderOptions $query The event data object
   * @return The total amount of points of the user
   */
  public function checkinPassholder(CultureFeed_Uitpas_Passholder_Query_CheckInPassholderOptions $query) {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/checkin', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $points = $xml->xpath_int('/response/points');
    return $points;
  }

  /**
   * Cash in a welcome advantage.
   *
   * @param string $uitpas_number The UitPas number
   * @param int $welcome_advantage_id Identification welcome advantage
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function cashInWelcomeAdvantage($uitpas_number, $welcome_advantage_id, $consumer_key_counter = NULL) {
     $data = array(
       'welcomeAdvantageId' => $welcome_advantage_id,
     );

     if ($consumer_key_counter) {
       $data['balieConsumerKey'] = $consumer_key_counter;
     }

     $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $uitpas_number . '/cashInWelcomeAdvantage', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotion = CultureFeed_Uitpas_Passholder_WelcomeAdvantage::createFromXML($xml->xpath('/promotionTO', false));
    return $promotion;
  }

  /**
   * Get the redeem options
   *
   * @param CultureFeed_Uitpas_Passholder_Query_SearchPromotionPointsOptions $query The query
   */
  public function getPromotionPoints(CultureFeed_Uitpas_Passholder_Query_SearchPromotionPointsOptions $query) {
    $data = $query->toPostData();
    $result = $this->oauth_client->consumerGetAsXml('uitpas/passholder/pointsPromotions', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotions = CultureFeed_Uitpas_Passholder_PointsPromotionResultSet::createFromXML($xml->xpath('/response', false));

    return $promotions;
  }

  public function getCashedInPromotionPoints(CultureFeed_Uitpas_Passholder_Query_SearchCashedInPromotionPointsOptions $query) {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/' . $query->uitpasNumber . '/cashedPointsPromotions', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotions = array();
    $objects = $xml->xpath('/response/cashedPromotions/cashedPromotion');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $promotions[] = CultureFeed_Uitpas_Passholder_CashedInPointsPromotion::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $promotions);
  }

  /**
   * Cash in promotion points for a UitPas.
   *
   * @param string $uitpas_number The UitPas number
   * @param int $points_promotion_id The identification of the redeem option
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function cashInPromotionPoints($uitpas_number, $points_promotion_id, $consumer_key_counter = NULL) {
    $data = array(
      'pointsPromotionId' => $points_promotion_id,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $uitpas_number . '/cashInPointsPromotion', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotion = CultureFeed_Uitpas_Passholder_PointsPromotion::createFromXML($xml->xpath('/promotionTO', false));
    return $promotion;
  }

  public function getPassholderEventActions(CultureFeed_Uitpas_Passholder_Query_EventActions $query) {
    $data = $query->toPostData();

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/passholder/eventActions', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $eventActions = CultureFeed_Uitpas_Passholder_EventActions::createFromXML($xml);
    return $eventActions;
  }

  public function postPassholderEventActions(CultureFeed_Uitpas_Passholder_Query_ExecuteEventActions $eventActions) {
    $data = $eventActions->toPostData();

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/eventActions', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $eventActions = CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult::createFromXML($xml);
    return $eventActions;
  }

  /**
   * Upload a picture for a given passholder.
   *
   * @param string $id The user ID of the passholder
   * @param string $file_data The binary data of the picture
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function uploadPicture($id, $file_data, $consumer_key_counter = NULL) {
    $data = array(
      'picture' => $file_data,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $id . '/uploadPicture', $data, TRUE, TRUE);
  }

    /**
     * Update a passholder.
     *
     * @param CultureFeed_Uitpas_Passholder $passholder The passholder to update.
     *     The passholder is identified by ID. Only fields that are set will be updated.
     * @param null $consumer_key_counter
     * @return \CultureFeed_Uitpas_Response
     * @throws \CultureFeed_ParseException
     */
  public function updatePassholder(CultureFeed_Uitpas_Passholder $passholder, $consumer_key_counter = NULL) {
    $data = $passholder->toPostData();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $passholder->uitpasNumber, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function updatePassholderCardSystemPreferences(CultureFeed_Uitpas_Passholder_CardSystemPreferences $preferences) {

    $data = $preferences->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $preferences->id . '/' . $preferences->cardSystemId, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

    /**
     * {@inheritdoc}
     */
    public function updatePassholderOptInPreferences($id, CultureFeed_Uitpas_Passholder_OptInPreferences $preferences, $consumer_key_counter = NULL) {

        $data = $preferences->toPostData();

        if ($consumer_key_counter) {
            $data['balieConsumerKey'] = $consumer_key_counter;
        }

        $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/' . $id . '/optinpreferences', $data);

        try {
            $xml = new CultureFeed_SimpleXMLElement($result);
        }
        catch (Exception $e) {
            throw new CultureFeed_ParseException($result);
        }

        $response = CultureFeed_Uitpas_Passholder_OptInPreferences::createFromXML($xml->xpath('optInPreferences', false));

        return $response;
    }

  /**
   * Block a UitPas.
   *
   * @param string $uitpas_number The UitPas number
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function blockUitpas($uitpas_number, $consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/block/' . $uitpas_number, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  /**
   * Search for welcome advantages.
   *
   * @param CultureFeed_Uitpas_Promotion_Query_WelcomeAdvantagesOptions $query The query
   * @param string $method The request method
   */
  public function searchWelcomeAdvantages(CultureFeed_Uitpas_Promotion_Query_WelcomeAdvantagesOptions $query, $method = CultureFeed_Uitpas::CONSUMER_REQUEST) {
    $path = 'uitpas/promotion/welcomeAdvantages';

    $data = $query->toPostData();

    if ($method == CultureFeed_Uitpas::CONSUMER_REQUEST) {
      $result = $this->oauth_client->consumerGetAsXml($path, $data);
    }
    else {
      $result = $this->oauth_client->authenticatedGetAsXml($path, $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotions = CultureFeed_Uitpas_Passholder_WelcomeAdvantageResultSet::createFromXML($xml->xpath('/response', false));
    return $promotions;
  }

  /**
   * {@inheritdoc}
   */
  public function getCard(CultureFeed_Uitpas_CardInfoQuery $card_query) {
    $data = $card_query->toPostData();

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/card', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $card = CultureFeed_Uitpas_CardInfo::createFromXML($xml->xpath('/response', FALSE));
    return $card;
  }

  /**
   * {@inheritdoc}
   */
  public function getPassholderActivationLink(CultureFeed_Uitpas_Passholder_Query_ActivationData $activation_data, $destination_callback = NULL) {
    $path = "uitpas/passholder/{$activation_data->uitpasNumber}/activation";

    $params = array(
      'dob' => $activation_data->dob->format('Y-m-d'),
    );

    $result = $this->oauth_client->consumerGetAsXml($path, $params);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $link = $xml->xpath_str('/response/activationLink');

    $query = array();

    if ($destination_callback) {
      $query['destination'] = call_user_func($destination_callback);
    }

    if (!empty($query)) {
      $link .= '?' . http_build_query($query);
    }

    return $link;
  }

  /**
   * {@inheritdoc}
   */
  public function constructPassHolderActivationLink($uid, $activation_code, $destination = NULL) {
    $path = "uitpas/activate/{$uid}/{$activation_code}";

    $query = array();

    if ($destination) {
      $query['destination'] = $destination;
    }

    $link = $this->oauth_client->getUrl($path, $query);

    return $link;
  }

  public function getPassholderActivationLinkChainedWithAuthorization($uitpas_number, DateTime $date_of_birth, $callback_url) {
    $c = $this->culturefeed;

    $link = $this->getPassholderActivationLink($uitpas_number, $date_of_birth, function () use ($c, $callback_url) {
      $token = $c->getRequestToken($callback_url);

      $auth_url = $c->getUrlAuthorize($token, $callback_url, CultureFeed::AUTHORIZE_TYPE_REGULAR, TRUE);

      return $auth_url;
    });

    return $link;
  }

  /**
   * Register a new Uitpas
   *
   * @param CultureFeed_Uitpas_Passholder_Query_RegisterUitpasOptions $query The query
   */
  public function registerUitpas(CultureFeed_Uitpas_Passholder_Query_RegisterUitpasOptions $query) {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/newCard', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return $xml->xpath_str('/response/message');
  }

  /**
   * {@inheritdoc}
   */
  public function registerPassholderInCardSystem(
    $passholderId,
    CultureFeed_Uitpas_Passholder_Query_RegisterInCardSystemOptions $query
  ) {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedPostAsXml("uitpas/passholder/{$passholderId}/register", $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $object = $xml->xpath('/passHolder', false);

    return CultureFeed_Uitpas_Passholder::createFromXml($object);
  }


  /**
   * Register a ticket sale for a passholder
   *
   * @param string $uitpas_number The UitPas number
   * @param string $cdbid The event CDBID
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   * @param string $price_class Price class used for the ticket sale.
   * @param string $ticket_sale_coupon_id The coupon id of the ticket sale.
   * @param int $amount_of_tickets The amount of ticket sales to register.
   *
   * @return CultureFeed_Uitpas_Event_TicketSale
   *
   * @throws CultureFeed_ParseException
   *   When the response could not be parsed.
   *
   * @throws CultureFeed_Exception
   *   When the response was an error message instead of a TicketSale entity.
   */
  public function registerTicketSale($uitpas_number, $cdbid, $consumer_key_counter = NULL, $price_class = NULL, $ticket_sale_coupon_id = NULL, $amount_of_tickets = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }
    if ($ticket_sale_coupon_id) {
      $data['ticketSaleCouponId'] = $ticket_sale_coupon_id;
    }
    if ($price_class) {
      $data['priceClass'] = $price_class;
    }
    if ($amount_of_tickets) {
      $data['amountOfTickets'] = (int) $amount_of_tickets;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/cultureevent/' . $cdbid . '/buy/' . $uitpas_number, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = $xml->xpath('/response', false);
    if ($response instanceof CultureFeed_SimpleXMLElement) {
      $response = CultureFeed_Response::createFromResponseBody($response);
      throw new CultureFeed_Exception($response->getMessage(), $response->getCode());
    }

    $ticket_sale = CultureFeed_Uitpas_Event_TicketSale::createFromXML($xml->xpath('/ticketSale', false));
    return $ticket_sale;
  }

  /**
   * Cancel a ticket sale for a passholder
   *
   * @param string $uitpas_number The UitPas number
   * @param string $cdbid The event CDBID
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function cancelTicketSale($uitpas_number, $cdbid, $consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    try {
      $this->oauth_client->authenticatedPostAsXml('uitpas/cultureevent/' . $cdbid . '/cancel/' . $uitpas_number, $data);
      return true;
    }
    catch (Exception $e) {
      return false;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function cancelTicketSaleById($ticketId, $consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $this->oauth_client->authenticatedPostAsXml('uitpas/cultureevent/cancel/' . $ticketId, $data);
  }

  /**
   * @inheritdoc
   */
  public function getPassholderForTicketSale( CultureFeed_Uitpas_Event_TicketSale $ts, $consumer_key_counter = NULL ) {
    $user_id = $ts->userId;
    return $this->getPassholderByUser($user_id, $consumer_key_counter);
  }

  /**
   * {@inheritdoc}
   */
  public function searchCheckins(CultureFeed_Uitpas_Event_Query_SearchCheckinsOptions $query, $consumer_key_counter = NULL, $method = CultureFeed_Uitpas::USER_ACCESS_TOKEN) {
    $data = $query->toPostData();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $path = 'uitpas/cultureevent/searchCheckins';

    if ($method == CultureFeed_Uitpas::USER_ACCESS_TOKEN) {
      $result = $this->oauth_client->authenticatedGetAsXml($path, $data);
    }
    else {
      $result = $this->oauth_client->consumerGetAsXml($path, $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $checkins = array();
    $objects = $xml->xpath('/response/checkinActivities/checkinActivity');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $checkins[] = CultureFeed_Uitpas_Event_CheckinActivity::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $checkins);
  }

  /**
   * Search for checkins
   *
   * @param CultureFeed_Uitpas_Passholder_Query_SearchCheckinsOptions $query The query
   */
  public function searchPassholderCheckins(CultureFeed_Uitpas_Passholder_Query_SearchCheckinsOptions $query) {
    $data = $query->toPostData();
    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/cultureevent/searchCheckins', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $checkins = array();
    $objects = $xml->xpath('/response/checkinActivities/checkinActivity');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $checkins[] = CultureFeed_Uitpas_Event_CheckinActivity::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $checkins);
  }

  /**
   * {@inheritdoc}
   */
  public function searchEvents(CultureFeed_Uitpas_Event_Query_SearchEventsOptions $query) {
    $data = $query->toPostData();

    $result = $this->oauth_client->consumerGetAsXml('uitpas/cultureevent/search', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $events = array();
    $objects = $xml->xpath('/cultureEvents/event');
    $total = $xml->xpath_int('/cultureEvents/total');

    foreach ($objects as $object) {
      $events[] = CultureFeed_Uitpas_Event_CultureEvent::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $events);
  }

  public function searchCounters(CultureFeed_Uitpas_Counter_Query_SearchCounterOptions $query, $method = CultureFeed_Uitpas::CONSUMER_REQUEST) {
    $data = $query->toPostData();

    if ($method == CultureFeed_Uitpas::CONSUMER_REQUEST) {
      $result = $this->oauth_client->consumerGetAsXml('uitpas/balie/search', $data);
    }
    else {
      $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/search', $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $counters = array();
    $objects = $xml->xpath('/response/balies/balie');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $counters[] = CultureFeed_Uitpas_Counter::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $counters);
  }

  /**
   * Search for point of sales
   *
   * @param CultureFeed_Uitpas_Counter_Query_SearchPointsOfSaleOptions $query The query
   * @param string $method The request method
   */
  public function searchPointOfSales(CultureFeed_Uitpas_Counter_Query_SearchPointsOfSaleOptions $query, $method = CultureFeed_Uitpas::CONSUMER_REQUEST) {
    $data = $query->toPostData();

    if ($method == CultureFeed_Uitpas::CONSUMER_REQUEST) {
      $result = $this->oauth_client->consumerGetAsXml('uitpas/balie/pos', $data);
    }
    else {
      $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/pos', $data);
    }

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $pos = array();
    $objects = $xml->xpath('/response/balies/balie');
    $total = $xml->xpath_int('/response/total');

    foreach ($objects as $object) {
      $pos[] = CultureFeed_Uitpas_Counter::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $pos);
  }

  public function searchTicketSales(CultureFeed_Uitpas_Event_Query_SearchTicketSalesOptions $query) {
    $data = $query->toPostData();


    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/cultureevent/searchTicketsales', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $total = $xml->xpath_int('/response/total');
    $objects = $xml->xpath('/response/ticketSales/ticketSale');
    $ticket_sales = array();

    foreach ($objects as $object) {
      $ticket_sales[] = CultureFeed_Uitpas_Event_TicketSale::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $ticket_sales);
  }

  /**
   * Add a member to a counter.
   *
   * @param string $uid The Culturefeed user ID
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function addMemberToCounter($uid, $consumer_key_counter = NULL) {
    $data = array(
      'uid' => $uid,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $this->oauth_client->authenticatedPost('uitpas/balie/member', $data);
  }

  public function removeMemberFromCounter($uid, $consumer_key_counter = NULL) {
    $data = array(
      'uid' => $uid,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $this->oauth_client->authenticatedPost('uitpas/balie/removeMember', $data);
  }

  /**
   * {@inheritdoc}
   */
  public function getCardCounters($consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/countCards', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $counters = array();
    $objects = $xml->xpath('/response/counters/counter');

    foreach ($objects as $object) {
      $counters[] = CultureFeed_Uitpas_Counter_CardCounter::createFromXML($object);
    }

    return $counters;

  }

  public function getMembersForCounter($consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/listEmployees', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $data = array();

    foreach ($xml->xpath('/response/admins/admin') as $object) {
      $data['admins'][] = CultureFeed_Uitpas_Counter_Member::createFromXML($object);
    }

    foreach ($xml->xpath('/response/members/member') as $object) {
      $data['members'][] = CultureFeed_Uitpas_Counter_Member::createFromXML($object);
    }

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function searchCountersForMember($uid) {
    $data = array(
      'uid' => $uid,
    );

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/balie/list', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $counters = array();
    $objects = $xml->xpath('balies/balie');
    $total = count($objects);

    foreach ($objects as $object) {
      $counters[] = CultureFeed_Uitpas_Counter_Employee::createFromXML($object);
    }

    return new CultureFeed_ResultSet($total, $counters);
  }

  /**
   * @inheritdoc
   */
  public function getDevices($consumer_key_counter = NULL, $show_event = FALSE) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    if ($show_event) {
      $data['showEvent'] = 'true';
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/cid/list', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $devices = array();
    $objects = $xml->xpath('/response/cids/cid');

    foreach ($objects as $object) {
      $devices[] = CultureFeed_Uitpas_Counter_Device::createFromXML($object);
    }

    return $devices;
  }

  public function getEventsForDevice($consumer_key_device, $consumer_key_counter = NULL) {
    $data = array();

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedGetAsXml('uitpas/cid/' . $consumer_key_device, $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return CultureFeed_Uitpas_Counter_Device::createFromXml($xml->xpath('/response', FALSE));
  }

  public function connectDeviceWithEvent($consumer_key_device, $cdbid, $consumer_key_counter = NULL) {
    $data = array(
      'cdbid' => $cdbid,
      'cidConsumerKey' => $consumer_key_device,
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/cid/connect', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    return CultureFeed_Uitpas_Counter_Device::createFromXml($xml->xpath('/response', FALSE));
  }

  /**
   * (non-PHPdoc)
   * @see CultureFeed_Uitpas::getWelcomeAdvantage()
   */
  public function getWelcomeAdvantage($id, CultureFeed_Uitpas_Promotion_PassholderParameter $passholder = NULL) {
    $path = 'uitpas/promotion/welcomeAdvantage/' . $id;

    $params = array();

    if ($passholder) {
      $params += $passholder->params();
    }

    $result = $this->oauth_client->consumerGetAsXml($path, $params);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $advantage = CultureFeed_Uitpas_Passholder_WelcomeAdvantage::createFromXML($xml);

    return $advantage;
  }

  public function getPointsPromotion($id, CultureFeed_Uitpas_Promotion_PassholderParameter $passholder = NULL) {
    $path = 'uitpas/promotion/pointsPromotion/' . $id;

    $params = array();

    if ($passholder) {
      $params += $passholder->params();
    }

    $result = $this->oauth_client->consumerGetAsXml($path, $params);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $promotion = CultureFeed_Uitpas_Passholder_PointsPromotion::createFromXML($xml);

    return $promotion;
  }

  public function getCardSystems($permanent = NULL) {
    if ($permanent == 'permanent') {
			$result = $this->oauth_client->consumerGetAsXml('uitpas/cardsystem?permanent=true');
		}
		else {
			$result = $this->oauth_client->consumerGetAsXml('uitpas/cardsystem');
		}

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $cardsystems = array();

    foreach ($xml->cardSystems->cardSystem as $cardSystemXml) {
      $cardsystems[] = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);
    }

    return $cardsystems;
  }

  /**
   * {@inheritdoc}
   */
  public function generateFinancialOverviewReport(
    DateTime $start_date,
    DateTime $end_date,
    $consumer_key_counter = NULL
  ) {
    $data = array(
      'startDate' => $start_date->format(DateTime::W3C),
      'endDate' => $end_date->format(DateTime::W3C),
    );

    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }

    $result = $this->oauth_client->authenticatedPost(
      'uitpas/report/financialoverview/organiser',
      $data
    );

    $response = CultureFeed_Response::createFromResponseBody($result);

    if ($response->getCode() !== 'ACTION_SUCCEEDED') {
      throw new RuntimeException('Expected response code ACTION_SUCCEEDED, got ' . $response->getCode());
    }

    // Extract the reportId from the relative URL we get back.
    // Example:
    // /uitpas/report/financialoverview/organiser/19/status?balieConsumerKey=31413BDF-DFC7-7A9F-10403618C2816E44
    if (1 === preg_match('@organiser/([^/]+)/status@', $response->getResource(), $matches)) {
      $reportId = $matches[1];
    }
    else {
      throw new RuntimeException('Unable to extract report ID from response');
    }

    return $reportId;
  }

  /**
   * {@inheritdoc}
   */
  public function financialOverviewReportStatus(
    $report_id,
    $consumer_key_counter = NULL
  ) {
    $params = array();

    if ($consumer_key_counter) {
      $params['balieConsumerKey'] = $consumer_key_counter;
    }

    $response_xml = $this->oauth_client->authenticatedGetAsXml(
      "uitpas/report/financialoverview/organiser/{$report_id}/status",
      $params
    );

    $response = CultureFeed_Response::createFromResponseBody($response_xml);

    return CultureFeed_ReportStatus::createFromResponse($response);
  }

  /**
   * @param string $report_id
   * @param string|null $consumer_key_counter
   *
   * @return mixed
   */
  public function downloadFinancialOverviewReport(
    $report_id,
    $consumer_key_counter = NULL
  ) {
    $params = array();

    if ($consumer_key_counter) {
      $params['balieConsumerKey'] = $consumer_key_counter;
    }

    $response = $this->oauth_client->authenticatedGet(
      "uitpas/report/financialoverview/organiser/{$report_id}/download",
      $params
    );

    return $response;
  }

  public function deleteMembership($uid, $assocationId, $consumer_key_counter = NULL) {
    $data = array(
      'uid' => $uid,
      'associationId' => $assocationId
    );
    if ($consumer_key_counter) {
      $data['balieConsumerKey'] = $consumer_key_counter;
    }
    $result = $this->oauth_client->authenticatedPostAsXml('uitpas/passholder/membership/delete', $data);

    try {
      $xml = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $response = CultureFeed_Uitpas_Response::createFromXML($xml->xpath('/response', false));
    return $response;
  }

  /**
   * Returns a CultureFeed_Uitpas_GroupPass object.
   *
   * @param $id
   * @return CultureFeed_Uitpas_GroupPass
   * @throws \CultureFeed_ParseException
   */
  public function getGroupPass($id) {

    $result = $this->oauth_client->consumerGetAsXml('uitpas/grouppass/' . $id);
    $xml = $this->validateResult($result, '');

    return CultureFeed_Uitpas_GroupPass::createFromXML($xml);
  }

}
