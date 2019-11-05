<?php

class CultureFeed_Uitpas_Passholder extends CultureFeed_Uitpas_ValueObject {

  const EMAIL_NO_MAILS = 'NO_MAILS';
  const EMAIL_NOTIFICATION_MAILS = 'NOTIFICATION_MAILS';
  const EMAIL_ALL_MAILS = 'ALL_MAILS';

  const SMS_NO_SMS = 'NO_SMS';
  const SMS_ALL_SMS = 'ALL_SMS';
  const SMS_NOTIFICATION_SMS = 'NOTIFICATION_SMS';

  /**
   * The name of the passholder. (Required)
   *
   * @var string
   */
  public $name;

  /**
   * The first name of the passholder. (Required)
   *
   * @var string
   */
  public $firstName;

  /**
   * The second name of the passholder.
   *
   * @var string
   */
  public $secondName;

  /**
   * The e-mail of the passholder.
   *
   * @var string
   */
  public $email;

  /**
   * The e-mail preference
   *
   * @var string
   */
  public $emailPreference;

  /**
   * The SMS preference
   *
   * @var string
   */
  public $smsPreference;

  /**
   * The INSZ number of the passholder. (Required)
   *
   * @var string
   */
  public $inszNumber;

  /**
   * The date of birth of the passholder. (Required)
   *
   * @var integer
   */
  public $dateOfBirth;

  /**
   * The gender of the passholder.
   *
   * @var string
   */
  public $gender;

  /**
   * The street of the passholder.
   *
   * @var string
   */
  public $street;

  /**
   * The number of the passholder.
   *
   * @var string
   */
  public $number;

  /**
   * The post box of the passholder.
   *
   * @var string
   */
  public $box;

  /**
   * The postal code of the passholder. (Required)
   *
   * @var string
   */
  public $postalCode;

  /**
   * The city of the passholder. (Required)
   *
   * @var string
   */
  public $city;

  /**
   * The telephone number of the passholder.
   *
   * @var string
   */
  public $telephone;

  /**
   * The GSM number of the passholder.
   *
   * @var string
   */
  public $gsm;

  /**
   * The nationality of the passholder.
   *
   * @var string
   */
  public $nationality;

  /**
   * The place of birth of the passholder.
   *
   * @var string
   */
  public $placeOfBirth;

  /**
   * The price the passholder pays for his UitPas.
   *
   * @var string
   */
  public $price;

  /**
   * True if the passholder has a kansenstatuut.
   *
   * @var boolean
   */
  public $kansenStatuut;

  /**
   * End date kansenstatuut.
   *
   * @var integer
   */
  public $kansenStatuutEndDate;

  /**
   * If the kansenstatuus has expired
   *
   * @var boolean
   */
  public $kansenStatuutExpired;
  
  /**
   * If the kansenstatuus has expired, but is still
   * in its grace period.
   *
   * @var boolean
   */
  public $kansenStatuutInGracePeriod;  

  /**
   * The user coupled with the passholder
   *
   * @var CultureFeed_Uitpas_Passholder_UitIdUser
   */
  public $uitIdUser;

  /**
   * True if the uitpas has been blocked
   *
   * @var boolean
   */
  public $blocked;

  /**
   * True if the data of the passholder was fetched using eID.
   *
   * @var boolean
   */
  public $verified;

  /**
   * The memberships if the passholder
   *
   * @var CultureFeed_Uitpas_Passholder_Membership[]
   */
  public $memberships = array();

  /**
   * The consumer key of the counter where the passholder has been registered
   *
   * @var string
   */
  public $registrationBalieConsumerKey;

  /**
   * The number of points of the passholder
   *
   * @var integer
   */
  public $points;

  public $moreInfo;

  public $schoolConsumerKey;

  public $balieConsumerKey;

  public $picture;

  /**
   * Card system specific information of the passholder.
   *
   * @var CultureFeed_Uitpas_Passholder_CardSystemSpecific[]
   */
  public $cardSystemSpecific;

  /**
   * Empty properties to keep when converting to POST data.
   *
   * @var array
   */
  protected $postDataEmptyPropertiesToKeep = array();

  /**
   * How many times the passholder checked in somewhere.
   *
   * @var integer
   */
  public $numberOfCheckins;

  /**
   * Hash of the INSZ number.
   *
   * @var string
   */
  public $inszNumberHash;

  /**
   * UiTPAS number.
   *
   * @var string
   */
  public $uitpasNumber;

  /**
   * @var string
   */
  public $voucherNumber;

  /**
   * True if the passholder has opted in to receive service mails (only used for registration).
   *
   * @var bool
   */
  public $optInServiceMails;

  /**
   * True if the passholder has opted in to receive milestone mails (only used for registration).
   *
   * @var bool
   */
  public $optInMilestoneMails;

  /**
   * True if the passholder has opted in to receive info mails (only used for registration).
   *
   * @var bool
   */
  public $optInInfoMails;

  /**
   * True if the passholder has opted in to receive SMS messages (only used for registration).
   *
   * @var bool
   */
  public $optInSms;

  /**
   * True if the passholder has opted in to receive info via post (only used for registration).
   *
   * @var bool
   */
  public $optInPost;

  /**
   * True if the passholder has received the legal terms and conditions on paper (only used for registration).
   *
   * @var bool
   */
  public $legalTermsPaper;

  /**
   * True if the passholder has received the legal terms and conditions through email (only used for registration).
   *
   * @var bool
   */
  public $legalTermsDigital;

  /**
   * True if the passholder is a minor and has parental consent (only used for registration).
   *
   * @var bool
   */
  public $parentalConsent;

  /**
   * {@inheritdoc}
   */
  protected function manipulatePostData(&$data) {
    if (isset($data['dateOfBirth'])) {
      $data['dateOfBirth'] = date('Y-m-d', $data['dateOfBirth']);
    }

    if (isset($data['kansenStatuut'])) {
      $data['kansenStatuut'] = $data['kansenStatuut'] ? 'true' : 'false';
    }

    if (isset($data['kansenStatuutEndDate'])) {
      $data['kansenStatuutEndDate'] = date('Y-m-d', $data['kansenStatuutEndDate']);
    }

    if (isset($data['inszNumberHash'])) {
      $data['inszNumber'] = $data['inszNumberHash'];
    }

    $readOnlyProperties = array(
      'cardSystemSpecific',
      'uitIdUser',
      'postDataEmptyPropertiesToKeep',
      'numberOfCheckins',
    );

    if (isset($this->uitIdUser)) {
      $readOnlyProperties[] = 'uitpasNumber';
    }

    foreach ($readOnlyProperties as $readOnlyProperty) {
      if (isset($data[$readOnlyProperty])) {
        unset($data[$readOnlyProperty]);
      }
    }

    if (isset($data['verified'])) {
      $data['verified'] = $data['verified'] ? 'true' : 'false';
    }
  }

  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {
    $passholder = new CultureFeed_Uitpas_Passholder();
    $passholder->name = $object->xpath_str('name');
    $passholder->firstName = $object->xpath_str('firstName');
    $passholder->secondName = $object->xpath_str('secondName');
    $passholder->email = $object->xpath_str('email');
    $passholder->emailPreference = $object->xpath_str('emailPreference');
    $passholder->smsPreference = $object->xpath_str('smsPreference');
    $passholder->inszNumberHash = $object->xpath_str('inszNumberHash');
    $passholder->inszNumber = $object->xpath_str('inszNumber');
    $passholder->dateOfBirth = $object->xpath_time('dateOfBirth');
    $passholder->gender = $object->xpath_str('gender');
    $passholder->street = $object->xpath_str('street');
    $passholder->number = $object->xpath_str('number');
    $passholder->box = $object->xpath_str('box');
    $passholder->postalCode = $object->xpath_str('postalCode');
    $passholder->city = $object->xpath_str('city');
    $passholder->telephone = $object->xpath_str('telephone');
    $passholder->gsm = $object->xpath_str('gsm');
    $passholder->nationality = $object->xpath_str('nationality');
    $passholder->placeOfBirth = $object->xpath_str('placeOfBirth');
    $passholder->price = $object->xpath_float('price');
    $passholder->kansenStatuut = $object->xpath_bool('kansenStatuut');
    $passholder->kansenStatuutEndDate = $object->xpath_time('kansenStatuutEndDate');
    $passholder->kansenStatuutExpired = $object->xpath_bool('kansenStatuutExpired');
    $passholder->kansenStatuutInGracePeriod = $object->xpath_bool('kansenStatuutInGracePeriod');

    foreach ($object->xpath('cardSystemSpecific') as $cardSystemSpecific) {
      $cardSystemId = $cardSystemSpecific->xpath_int('cardSystem/id', FALSE);
      $passholder->cardSystemSpecific[$cardSystemId] = CultureFeed_Uitpas_Passholder_CardSystemSpecific::createFromXML($cardSystemSpecific);
    }

    if ($object->xpath('uitIdUser', false) instanceof SimpleXMLElement) {
      $passholder->uitIdUser = CultureFeed_Uitpas_Passholder_UitIdUser::createFromXML($object->xpath('uitIdUser', false));
    }

    $passholder->blocked = $object->xpath_bool('blocked');
    $passholder->verified = $object->xpath_bool('verified');
    $passholder->registrationBalieConsumerKey = $object->xpath_str('registrationBalieConsumerKey');
    $passholder->points = $object->xpath_int('points');
    $passholder->moreInfo = $object->xpath_str('moreInfo');
    $passholder->schoolConsumerKey = $object->xpath_str('schoolConsumerKey');
    $passholder->picture = $object->xpath_str('picture');

    // Temporary workaround for wrongly structured output.
    foreach ($object->xpath('memberships/association/..') as $membership) {
      $passholder->memberships[] = CultureFeed_Uitpas_Passholder_Membership::createFromXML($membership);
    }

    foreach ($object->xpath('memberships/membership') as $membership) {
      $passholder->memberships[] = CultureFeed_Uitpas_Passholder_Membership::createFromXML($membership);
    }

    $passholder->numberOfCheckins = $object->xpath_int('numberOfCheckins');

    return $passholder;
  }

  /**
   * Variation of CultureFeed_Uitpas_ValueObject::toPostData() which
   * supports posting certain empty properties.
   *
   * @return array
   */
  public function toPostData() {
    $data = get_object_vars($this);
    $this->manipulatePostData($data);

    foreach ($data as $key => $value) {
      $keep_empty = in_array($key, $this->postDataEmptyPropertiesToKeep, TRUE);

      $empty = (FALSE === (bool) $value);

      if (!$keep_empty && $empty) {
        unset($data[$key]);
      }
    }

    return $data;
  }

  /**
   * Specify if a empty schoolConsumerKey property needs to be kept when
   * converting to POST data.
   *
   * @param bool $keep Whether to keep an empty-valued schoolConsumerKey property or not.
   *
   * @return $this
   */
  public function toPostDataKeepEmptySchoolConsumerKey($keep = TRUE) {
    $this->toPostDataKeepEmptyProperty('schoolConsumerKey', $keep);

    return $this;
  }

  /**
   * Specify if an empty secondName property needs to be kept when
   * converting to POST data.
   *
   * @param bool $keep Wether to keep an empty-valued secondName property or not.
   *
   * @return $this
   */
  public function toPostDataKeepEmptySecondName($keep = TRUE) {
    $this->toPostDataKeepEmptyProperty('secondName', $keep);

    return $this;
  }

  /**
   * Specify if an empty email property needs to be kept when
   * converting to POST data.
   *
   * @param bool $keep Whether to keep an empty-valued email property or not.
   *
   * @return $this
   */
  public function toPostDataKeepEmptyEmail($keep = TRUE) {
    $this->toPostDataKeepEmptyProperty('email', $keep);

    return $this;
  }

  /**
   * Specify if an empty 'more info' property needs to be kept when
   * converting to POST data.
   *
   * @param bool $keep Whether to keep an empty-valued 'more info' property or not.
   *
   * @return $this
   */
  public function toPostDataKeepEmptyMoreInfo($keep = TRUE) {
    $this->toPostDataKeepEmptyProperty('moreInfo', $keep);

    return $this;
  }

  /**
   * Specify if an empty 'telephone' property needs to be kept when
   * converting to POST data.
   *
   * @param bool $keep Whether to keep an empty-valued 'telephone' property or not.
   *
   * @return $this
   */
  public function toPostDataKeepEmptyTelephone($keep = TRUE) {
    $this->toPostDataKeepEmptyProperty('telephone', $keep);

    return $this;
  }

  /**
   * Specify if an empty 'gsm' property needs to be kept when
   * converting to POST data.
   *
   * @param bool $keep Whether to keep an empty-valued 'gsm' property or not.
   *
   * @return $this
   */
  public function toPostDataKeepEmptyGSM($keep = TRUE) {
    $this->toPostDataKeepEmptyProperty('gsm', $keep);

    return $this;
  }

  /**
   * Specify if a certain property needs ot be kept when
   * converting to POST data.
   *
   * @param string $name The name of the property
   * @param bool $keep Wether to keep an empty-valued property or not.
   */
  protected function toPostDataKeepEmptyProperty($name, $keep) {
    if ($keep) {
      $this->postDataEmptyPropertiesToKeep[] = $name;
    }
    else {
      if(($key = array_search($name, $this->postDataEmptyPropertiesToKeep)) !== false) {
        unset($this->postDataEmptyPropertiesToKeep[$key]);
      }
    }
  }

  /**
   * @param integer $id
   *
   * @return bool
   */
  public function inCardSystem($id) {
    return array_key_exists($id, $this->cardSystemSpecific);
  }
}
