<?php

class CultureFeed_Uitpas_Event_CultureEvent extends CultureFeed_Uitpas_ValueObject {

  /**
   * @deprecated Use the CultureFeed_Uitpas_Event_CheckinConstraintReason
   *   constants instead.
   */
  const CHECKIN_CONSTRAINT_REASON_MAXIMUM_REACHED = 'MAXIMUM_REACHED';

  /**
   * @deprecated Use the CultureFeed_Uitpas_Event_CheckinConstraintReason
   *   constants instead.
   */
  const CHECKIN_CONSTRAINT_REASON_INVALID_DATE_TIME = 'INVALID_DATE_TIME';

  /**
   * @deprecated Use the CultureFeed_Uitpas_Event_BuyConstraintReason
   *   constants instead.
   */
  const BUY_CONSTRAINT_REASON_MAXIMUM_REACHED = 'MAXIMUM_REACHED';

  /**
   * The identification of the event
   *
   * @var string
   */
  public $cdbid;

  /**
   * The ID of the location of the event
   *
   * @var string
   */
  public $locationId;

  /**
   * The name of the location of the event
   *
   * @var string
   */
  public $locationName;

  /**
   * The organiserId cdbid van de inrichter
   *
   * @var string
   */
   public $organiserId;

   /**
   * The organiserId cdbid van de inrichter
   * the API has an error and it needs actorId in order to register an event
   *
   * @var string
   */
   public $actorId;

  /**
   * The distribution key id(s) of the event.
   *
   * Historically the API docs used to indicate that this property should always
   * be a single value, while in reality it was always allowed to be an array.
   *
   * When parsing from xml this property will always be a value of
   * DistributionKey objects, containing both an id and name.
   *
   * When POSTing it can be a single string, specifically the id of a single
   * distribution key. This is to maintain backwards compatibility with existing
   * code. Alternatively it can be an array of DistributionKey objects. In that
   * case only the id property of the DistributionKey object is required.
   *
   * @var \CultureFeed_Uitpas_DistributionKey[]|string
   */
  public $distributionKey;

   /**
   * The volume constraint added for registering an event
   *
   * @var integer
   */
   public $volumeConstraint;

   /**
   * date format yyyy-mm-dd added for registering an event
   *
   * @var string
   */
   public $timeConstraintFrom;

   /**
   * date format yyyy-mm-dd added for registering an event
   *
   * @var string
   */
   public $timeConstraintTo;

   /**
   * added for registering an event
   *
   * @var string
   */
   public $periodConstraintVolume;


   /**
    * added for registering an event
    *
    * One of DAY, WEEK, MONTH, QUARTER or YEAR.
    *
    * @var string
    */
   public $periodConstraintType;

   /**
   * added for registering an event
   *
   * From API:
   * True, indien periodConstraint degressief is.
   * Dit is enkel mogelijk bij periodConstraintType YEAR.
   *
   * @var boolean
   */
   public $degressive;

   /**
   * added for registering an event
    *
    * One of DAY, WEEK, MONTH, QUARTER or YEAR.
   *
   * @var string
   */
   public $checkinPeriodConstraintType;


   /**
   * The checkin constraint added for registering an event
   *
   * @var integer
   */
   public $checkinPeriodConstraintVolume;


   /**
   * The organiserName van de inrichter
   *
   * @var string
   */
   public $organiserName;

   /**
   * The city
   *
   * @var string
   */
   public $city;

  /**
   * True if a given passholder can checkin on the event
   *
   * @var boolean
   */
  public $checkinAllowed;

  /**
   * The checkin constraint of the event
   *
   * @var CultureFeed_Uitpas_Event_CheckinConstraint
   */
  public $checkinConstraint;

  /**
   * The reason the passholder cannot check in on the event
   *
   * @var string
   */
  public $checkinConstraintReason;

  /**
   * The checkin start date.
   *
   * @var int
   */
  public $checkinStartDate;

  /**
   * The checkin end date.
   *
   * @var int
   */
  public $checkinEndDate;

  /**
   * The reason the passholder cannot buy tickets for the event
   *
   * @var string
   */
  public $buyConstraintReason;

  /**
   * The price of the event
   *
   * @var float
   */
  public $price;

  /**
   * The price names of the event
   *
   * @var string[]
   */
  public $postPriceNames;

  /**
   * The price values of the event
   *
   * @var float[]
   */
  public $postPriceValues;

  /**
   * The tariff of the event for a given passholder
   *
   * @var float
   */
  public $tariff;

  /**
   * The title of the event
   *
   * @var string
   */
  public $title;

  /**
   * The calendar description of the event
   *
   * @var CultureFeed_Uitpas_Calendar
   */
  public $calendar;

   /**
   * The number of points of the event
   *
   * @var int
   */
  public $numberOfPoints;

  /**
   * The number of months grace period for buy tickets.
   */
  public $gracePeriodMonths;

  /**
   * The cardsystems.
   *
   * @var CultureFeed_Uitpas_CardSystem[]
   */

  public $cardSystems;

  /**
   * The description of the event
   *
   * @var string
   */
  public $description;

  /**
   * The ticket sales.
   *
   * @var CultureFeed_Uitpas_Event_TicketSale_Opportunity[]
   */
  public $ticketSales;

  public function __construct() {
    $this->ticketSales = array();
    $this->postPriceNames = array();
    $this->postPriceValues = array();
  }

  /**
   * Modify an array of data for posting.
   */
  protected function manipulatePostData(&$data) {
    // Set the actor ID.
    $data['actorId'] = $data['organiserId'];

    // These are allowed params for registering an event.
    $allowed = array();

    $allowed[] = "cdbid";
    $allowed[] = "locationId";
    $allowed[] = "actorId";
    $allowed[] = "distributionKey";
    $allowed[] = "volumeConstraint";
    $allowed[] = "timeConstraintFrom";
    $allowed[] = "timeConstraintTo";
    $allowed[] = "periodConstraintVolume";
    $allowed[] = "periodConstraintType";
    $allowed[] = "degressive";
    $allowed[] = "checkinPeriodConstraintType";
    $allowed[] = "checkinPeriodConstraintVolume";
    $allowed[] = "price";
    $allowed[] = "numberOfPoints";
    $allowed[] = "gracePeriodMonths";
    $allowed[] = "gracePeriod";

    foreach ($data as $key => $value) {
      if (!in_array($key, $allowed)) {
        unset($data[$key]);
      }
    }

    $priceNameIndex = 0;
    foreach ($this->postPriceNames as $priceName) {
      $priceNameIndex++;
      $data['price.name.' . $priceNameIndex] = $priceName;
    }

    $priceValueIndex = 0;
    foreach ($this->postPriceValues as $priceValue) {
      $priceValueIndex++;
      $data['price.value.' . $priceValueIndex] = $priceValue;
    }

    // If distributionKey is an array we should convert the containing keys to
    // strings as we should only POST the distribution key id.
    if (is_array($data['distributionKey'])) {
      $data['distributionKey'] = array_map(
        function (\CultureFeed_Uitpas_DistributionKey $key) {
          return (string) $key->id;
        },
        $data['distributionKey']
      );
    }
  }




  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {

    $event = new CultureFeed_Uitpas_Event_CultureEvent();
    $event->cdbid = $object->xpath_str('cdbid');
    $event->locationId = $object->xpath_str('locationId');
    $event->locationName = $object->xpath_str('locationName');
    $event->organiserId = $object->xpath_str('organiserId');
    $event->organiserName = $object->xpath_str('organiserName');
    $event->city = $object->xpath_str('city');
    $event->checkinAllowed = $object->xpath_bool('checkinAllowed');
    $event->checkinConstraint = CultureFeed_Uitpas_Event_CheckinConstraint::createFromXML($object->xpath('checkinConstraint', false));
    $event->checkinConstraintReason = $object->xpath_str('checkinConstraintReason');
    $event->checkinStartDate = $object->xpath_time('checkinStartDate');
    $event->checkinEndDate = $object->xpath_time('checkinEndDate');
    $event->buyConstraintReason = $object->xpath_str('buyConstraintReason');
    $event->price = $object->xpath_float('price');
    $event->tariff = $object->xpath_float('tariff');
    $event->title = $object->xpath_str('title');
    $event->description = $object->xpath_str('shortDescription');

    $object->registerXPathNamespace('cdb', CultureFeed_Cdb_Default::CDB_SCHEME_URL);

    $calendar_xml = $object->xpath('cdb:calendar', false);
    if ($calendar_xml !== FALSE && !is_array($calendar_xml)) {
      $event->calendar = CultureFeed_Uitpas_Calendar::createFromXML($calendar_xml);
    }
    $event->numberOfPoints = $object->xpath_int('numberOfPoints');
    $event->gracePeriodMonths = $object->xpath_int('gracePeriodMonths');

    $event->cardSystems = array();
    foreach ($object->xpath('cardSystems/cardSystem') as $cardSystem) {
      $event->cardSystems[] = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystem);
    }

    $event->ticketSales = array();
    foreach ($object->xpath('ticketSales/ticketSale') as $ticketSale) {
      $event->ticketSales[] = CultureFeed_Uitpas_Event_TicketSale_Opportunity::createFromXml($ticketSale);
    }

    $event->distributionKey = array();
    foreach ($object->xpath('distributionKeys/distributionKey') as $distributionKey) {
      $event->distributionKey[] = CultureFeed_Uitpas_DistributionKey::createFromXML($distributionKey);
    }

    return $event;
  }

}
