<?php

/**
 *
 */
interface CultureFeed_Uitpas {

  const CONSUMER_REQUEST = 'ConsumerRequest';
  const USER_ACCESS_TOKEN = 'UserAccessToken';

  /**
   * @param $uitpas_number
   * @param string $consumer_key_counter
   * @param integer $max
   * @param integer $start
   *
   * @return CultureFeed_ResultSet
   */
  public function getCouponsForPassholder($uitpas_number, $consumer_key_counter = NULL, $max = NULL, $start = NULL);

  /**
   * Get the associations.
   *
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   * @param boolean $readPermission Filter associations with read permission
   * @param boolean $registerPermission Filter associations with register permission
   *
   * @return CultureFeed_ResultSet
   */
  public function getAssociations($consumer_key_counter = NULL, $readPermission = NULL, $registerPermission = NULL);

  /**
   * Register a set of distribution keys for an organizer. The entire set (including existing)
   * of distribution keys must be provided.
   *
   * @param string $cdbid The CDBID of the organizer
   * @param array $distribution_keys The identification of the distribution key
   */
  public function registerDistributionKeysForOrganizer($cdbid, $distribution_keys);

  /**
   * Get the distribution keys for a given organizer.
   *
   * @param string $cdbid The CDBID of the given organizer
   * @return CultureFeed_ResultSet The set of distribution keys
   */
  public function getDistributionKeysForOrganizer($cdbid);

  /**
   * Get the card systems for a given organizer.
   *
   * @param string $cdbid The CDBID of the given organizer
   * @return CultureFeed_ResultSet The set of card systems
   */
  public function getCardSystemsForOrganizer($cdbid);

  /**
   * Get the price of the UitPas.
   *
   * @return CultureFeed_ResultSet
   */
  public function getPrice($consumer_key_counter = NULL);

  /**
   * @param string $uitpas_number
   * @param string $reason
   * @param int $date_of_birth
   * @param string $postal_code
   * @param string $voucher_number
   * @param string $consumer_key_counter
   *
   * @return CultureFeed_Uitpas_Passholder_UitpasPrice
   *
   * @throws CultureFeed_ParseException
   *   When the response XML could not be parsed.
   *
   * @throws LogicException
   *   When the response contains no uitpasPrice object.
   */
  public function getPriceByUitpas($uitpas_number, $reason, $date_of_birth = null, $postal_code = null, $voucher_number = null, $consumer_key_counter = NULL);

  /**
   * @param string $card_system_id
   * @param int $date_of_birth
   * @param string|null $postal_code
   * @param string|null $voucher_number
   * @param string|null $consumer_key_counter
   *
   * @return CultureFeed_Uitpas_Passholder_UitpasPrice
   *
   * @throws CultureFeed_ParseException
   *   When the response XML could not be parsed.
   *
   * @throws LogicException
   *   When the response contains no uitpasPrice object.
   */
  public function getPriceForUpgrade($card_system_id, $date_of_birth, $postal_code = null, $voucher_number = null, $consumer_key_counter = null);

  /**
   * Create a new UitPas passholder.
   *
   * @param CultureFeed_Uitpas_Passholder $passholder The new passholder
   *
   * @return string uuid
   *   The uuid for the new passholder.
   */
  public function createPassholder(CultureFeed_Uitpas_Passholder $passholder);

  /**
   * Create a new membership for a UitPas passholder.
   *
   * @param string $id The user ID of the passholder
   * @param string $organization The name of the organization
   * @param DateTime $end_date The membership's organization end date
   */
  public function createMembershipForPassholder(CultureFeed_Uitpas_Passholder_Membership $membership);

  /**
   * Resend the activation e-mail for a passholder
   *
   * @param string $uitpas_number The UitPas number
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function resendActivationEmail($uitpas_number, $consumer_key_counter = NULL);

  /**
   * Get a passholder based on the UitPas number.
   *
   * @param string $uitpas_number The UitPas number
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   *
   * @return CultureFeed_Uitpas_Passholder
   */
  public function getPassholderByUitpasNumber($uitpas_number, $consumer_key_counter = NULL);

  /**
   * Get a card, with optionally a passholder, or a group pass based on a identification number.
   *
   * @param string $identification_number
   *   The identification number. This can be either an UiTPAS number, chip-number, INSZ-number, or INSZ-barcode.
   * @param string $consumer_key_counter
   *   The consumer key of the counter from where the request originates
   * @return CultureFeed_Uitpas_Identity
   */
  public function identify($identification_number, $consumer_key_counter = NULL);

  /**
   * Get a passholder based on the user ID
   *
   * @param string $user_id The user ID
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   *
   * @return CultureFeed_Uitpas_Passholder
   */
  public function getPassholderByUser($user_id, $consumer_key_counter = NULL);

  /**
   * Search for passholders.
   *
   * @param CultureFeed_Uitpas_Passholder_Query_SearchPassholdersOptions $query The query
   * @param string $method The request method
   *
   * @return CultureFeed_Uitpas_Passholder_ResultSet
   */
  public function searchPassholders(CultureFeed_Uitpas_Passholder_Query_SearchPassholdersOptions $query, $method = CultureFeed_Uitpas::CONSUMER_REQUEST);

  /**
   * Get the welcome advantages for a passholder.
   *
   * @param CultureFeed_Uitpas_Passholder_Query_WelcomeAdvantagesOptions $query The query
   * @param string $uitpas_number The UitPas number
   */
  public function getWelcomeAdvantagesForPassholder(CultureFeed_Uitpas_Passholder_Query_WelcomeAdvantagesOptions $query);

  /**
   * Check in a passholder.
   *
   * Provide either a UitPas number or chip number. You cannot provide both.
   *
   * @param CultureFeed_Uitpas_Passholder_Query_CheckInPassholderOptions $event The event data object
   */
  public function checkinPassholder(CultureFeed_Uitpas_Passholder_Query_CheckInPassholderOptions $event);

  /**
   * Cash in a welcome advantage.
   *
   * @param string $uitpas_number The UitPas number
   * @param int $welcome_advantage_id Identification welcome advantage
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function cashInWelcomeAdvantage($uitpas_number, $welcome_advantage_id, $consumer_key_counter = NULL);

  /**
   * Get the redeem options
   *
   * @param CultureFeed_Uitpas_Passholder_Query_SearchPromotionPointsOptions $query The query
   *
   * @return CultureFeed_ResultSet
   */
  public function getPromotionPoints(CultureFeed_Uitpas_Passholder_Query_SearchPromotionPointsOptions $query);

  public function getCashedInPromotionPoints(CultureFeed_Uitpas_Passholder_Query_SearchCashedInPromotionPointsOptions $query);

  /**
   * Cash in promotion points for a UitPas.
   *
   * @param string $uitpas_number The UitPas number
   * @param int $points_promotion_id The identification of the redeem option
   * @param string $counter The name of the UitPas counter
   */
  public function cashInPromotionPoints($uitpas_number, $points_promotion_id, $consumer_key_counter = NULL);

  /**
   * Upload a picture for a given passholder.
   *
   * @param string $id The user ID of the passholder
   * @param string $file_data The binary data of the picture
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function uploadPicture($id, $file_data, $consumer_key_counter = NULL);

  /**
   * Update a passholder.
   *
   * @param CultureFeed_Uitpas_Passholder $passholder The passholder to update.
   * 		The passholder is identified by ID. Only fields that are set will be updated.
   */
  public function updatePassholder(CultureFeed_Uitpas_Passholder $passholder);

  /**
   * Update a passholder's card system preferences.
   *
   * @param CultureFeed_Uitpas_Passholder_CardSystemPreferences $preferences The passholder's card preferences to update.
   *        The card system preferences are identified by user id and card system id. Only fields that are set will be updated.
   */
  public function updatePassholderCardSystemPreferences(CultureFeed_Uitpas_Passholder_CardSystemPreferences $preferences);

  /**
   * Update a passholder's opt-in preferences.
   *
   * @param string $id The user ID of the passholder
   * @param CultureFeed_Uitpas_Passholder_OptInPreferences $preferences The passholder's opt-in preferences to update.
   *        The opt-in preferences are identified by user id. Only fields that are set will be updated.
   */
  public function updatePassholderOptInPreferences($id, CultureFeed_Uitpas_Passholder_OptInPreferences $preferences);

  /**
   * Block a UitPas.
   *
   * @param string $uitpas_number The UitPas number
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function blockUitpas($uitpas_number, $consumer_key_counter = NULL);

  /**
   * Search for welcome advantages.
   *
   * @param CultureFeed_Uitpas_Promotion_Query_WelcomeAdvantagesOptions $query The query
   * @param string $method The request method
   */
  public function searchWelcomeAdvantages(CultureFeed_Uitpas_Promotion_Query_WelcomeAdvantagesOptions $query, $method = CultureFeed_Uitpas::CONSUMER_REQUEST);

  /**
   * Get info regarding a UiTPAS card based on chipNumber of uitpasNumber.
   *
   * @param CultureFeed_Uitpas_CardInfoQuery $card_query
   * @return CultureFeed_Uitpas_CardInfo
   */
  public function getCard(CultureFeed_Uitpas_CardInfoQuery $card_query);

  /**
   * Get the activitation link for a passholder which is not activated online yet.
   *
   * @param CultureFeed_Uitpas_Passholder_Query_ActivationData $activation_data
   * @param mixed $destination_callback
   *
   * @return string
   */
  public function getPassholderActivationLink(CultureFeed_Uitpas_Passholder_Query_ActivationData $activation_data, $destination_callback = NULL);

  /**
   * Constructs an activation link,
   *
   * @param string $uid
   * @param string $activation_code
   * @param string $destination_
   */
  public function constructPassHolderActivationLink($uid, $activation_code, $destination = NULL);

  /**
   * Get the activitation link for a passholder which is not activated online yet,
   * chained with an authorization.
   *
   * @param string $uitpas_number
   * @param DateTime $date_of_birth
   * @param string $callback_url
   */
  public function getPassholderActivationLinkChainedWithAuthorization($uitpas_number, DateTime $date_of_birth, $callback_url);

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
   */
  public function registerTicketSale($uitpas_number, $cdbid, $consumer_key_counter = NULL, $price_class = NULL, $ticket_sale_coupon_id = NULL, $amount_of_tickets = NULL);

  /**
   * @param CultureFeed_Uitpas_Event_Query_SearchTicketSalesOptions $query
   * @return CultureFeed_ResultSet
   */
  public function searchTicketSales(CultureFeed_Uitpas_Event_Query_SearchTicketSalesOptions $query);

  /**
   * Register a new Uitpas
   *
   * @param CultureFeed_Uitpas_Passholder_Query_RegisterUitpasOptions $query The query
   */
  public function registerUitpas(CultureFeed_Uitpas_Passholder_Query_RegisterUitpasOptions $query);

  /**
   * Registers an existing passholder in a new cardsystem.
   *
   * @param string $passholderId
   * @param CultureFeed_Uitpas_Passholder_Query_RegisterInCardSystemOptions $query
   * @return CultureFeed_Uitpas_Passholder
   */
  public function registerPassholderInCardSystem($passholderId, CultureFeed_Uitpas_Passholder_Query_RegisterInCardSystemOptions $query);

  /**
   * Cancel a ticket sale for a passholder
   *
   * @param string $uitpas_number The UitPas number
   * @param string $cdbid The event CDBID
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function cancelTicketSale($uitpas_number, $cdbid, $consumer_key_counter = NULL);

  /**
   * Cancel a ticket sale for a passholder by ticket id.
   *
   * @param string $ticketId The ticket id
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function cancelTicketSaleById($ticketId, $consumer_key_counter = NULL);

  /**
   * Search for checkins
   *
   * @param CultureFeed_Uitpas_Event_Query_SearchCheckinsOptions $query The query
   * @param string $consumer_key_counter Optional consumer key of the counter.
   * @param string $method The OAuth request method, either consumer request or
   *   user request.
   *
   * @return CultureFeed_ResultSet
   */
  public function searchCheckins(CultureFeed_Uitpas_Event_Query_SearchCheckinsOptions $query, $consumer_key_counter = NULL, $method = CultureFeed_Uitpas::USER_ACCESS_TOKEN);

  /**
   * Search for Uitpas events
   *
   * @param CultureFeed_Uitpas_Event_Query_SearchEventsOptions $query The query
   *
   * @return CultureFeed_ResultSet
   */
  public function searchEvents(CultureFeed_Uitpas_Event_Query_SearchEventsOptions $query);

  /**
   * Search for point of sales
   *
   * @param CultureFeed_Uitpas_Counter_Query_SearchPointsOfSaleOptions $query The query
   * @param string $method The request method
   */
  public function searchPointOfSales(CultureFeed_Uitpas_Counter_Query_SearchPointsOfSaleOptions $query, $method = CultureFeed_Uitpas::CONSUMER_REQUEST);

  public function searchCounters(CultureFeed_Uitpas_Counter_Query_SearchCounterOptions $query, $method = CultureFeed_Uitpas::CONSUMER_REQUEST);

  /**
   * Add a member to a counter.
   *
   * @param string $uid The Culturefeed user ID
   * @param string $consumer_key_counter The consumer key of the counter from where the request originates
   */
  public function addMemberToCounter($uid, $consumer_key_counter = NULL);

  public function removeMemberFromCounter($uid, $consumer_key_counter = NULL);

  public function getMembersForCounter($consumer_key_counter = NULL);

  /**
   * @param string|null $consumer_key_counter
   * @return CultureFeed_Uitpas_Counter_CardCounter[]
   */
  public function getCardCounters($consumer_key_counter = NULL);

  /**
   * Search for counters for a given member
   *
   * @param string $uid The Culturefeed user ID
   *
   * @return CultureFeed_ResultSet
   */
  public function searchCountersForMember($uid);

  /**
   * @param null $consumer_key_counter
   * @param bool $show_event
   * @return CultureFeed_Uitpas_Counter_Device[]
   */
  public function getDevices($consumer_key_counter = NULL, $show_event = FALSE);

  public function getEventsForDevice($consumer_key_device, $consumer_key_counter = NULL);

  public function connectDeviceWithEvent($device_id, $cdbid, $consumer_key_counter = NULL);

  /**
   *
   * @param integer $id
   * @param CultureFeed_Uitpas_Promotion_PassholderParameter $passholder
   */
  public function getWelcomeAdvantage($id, CultureFeed_Uitpas_Promotion_PassholderParameter $passholder = NULL);

  /**
   *
   * @param integer $id
   * @param CultureFeed_Uitpas_Promotion_PassholderParameter $passholder
   */
  public function getPointsPromotion($id, CultureFeed_Uitpas_Promotion_PassholderParameter $passholder = NULL);

  /**
   * Register an event.
   *
   * @param CultureFeed_Uitpas_Event_CultureEvent $event The event data that needs to be sent over.
   * @return CultureFeed_Uitpas_Response
   */
  public function registerEvent(CultureFeed_Uitpas_Event_CultureEvent $event);

  /**
   * Update an event.
   *
   * @param CultureFeed_Uitpas_Event_CultureEvent $event The event data that needs to be sent over.
   * @return CultureFeed_Uitpas_Response
   */
  public function updateEvent(CultureFeed_Uitpas_Event_CultureEvent $event);

  /**
   * Get the details of an event.
   *
   * @param string $id
   *   Id of the event.
   * @return CultureFeed_Uitpas_Event_CultureEvent
   *   Details of the event.
   */
  public function getEvent($id);

  /**
   * Get the card systems for a given event.
   *
   * @param string $cdbid The CDBID of the given event
   * @return CultureFeed_ResultSet The set of card systems
   */
  public function getCardSystemsForEvent($cdbid);

  /**
   * @param string $cdbid
   * @return bool
   */
  public function eventHasTicketSales($cdbid);

  /**
   * Add a card system to the event.
   *
   * @param string $cdbid
   * @param string $cardSystemId
   * @param string|null $distributionKey
   *   Only required for manual distribution keys.
   *
   * @return CultureFeed_Uitpas_Response
   */
  public function addCardSystemToEvent($cdbid, $cardSystemId, $distributionKey = null);

  /**
   * Delete a card system from the event.
   *
   * @param string $cdbid
   * @param string $cardSystemId
   *
   * @return CultureFeed_Uitpas_Response
   */
  public function deleteCardSystemFromEvent($cdbid, $cardSystemId);

  /**
	 * @param string $permanent if permanent only permanent card systems need to be sent over.
   * @return CultureFeed_Uitpas_CardSystem[]
   */
  public function getCardSystems($permanent);

  /**
   * @param DateTime $start_date
   * @param DateTime $end_date
   * @param string|null $consumer_key_counter
   *
   * @return string reportId
   */
  public function generateFinancialOverviewReport(DateTime $start_date, DateTime $end_date, $consumer_key_counter = NULL);

  /**
   * @param string $report_id
   * @param string|null $consumer_key_counter
   *
   * @return CultureFeed_ReportStatus
   */
  public function financialOverviewReportStatus(
    $report_id,
    $consumer_key_counter = NULL
  );

  /**
   * @param string $report_id
   * @param string|null $consumer_key_counter
   *
   * @return mixed
   */
  public function downloadFinancialOverviewReport(
    $report_id,
    $consumer_key_counter = NULL
  );

  /**
   * @param string $uid
   * @param string $assocationId
   * @param string|null $consumer_key_counter
   * @return CultureFeed_Uitpas_Response
   */
  public function deleteMembership(
    $uid,
    $assocationId,
    $consumer_key_counter = NULL);

  public function getPassholderEventActions(CultureFeed_Uitpas_Passholder_Query_EventActions $query);

  /**
   * @param CultureFeed_Uitpas_Passholder_Query_ExecuteEventActions $eventActions
   * @return CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult
   */
  public function postPassholderEventActions(CultureFeed_Uitpas_Passholder_Query_ExecuteEventActions $eventActions);

  /**
   * Returns a CultureFeed_Uitpas_GroupPass.
   *
   * @param $id
   * @return CultureFeed_Uitpas_GroupPass
   */
  public function getGroupPass($id);
}
