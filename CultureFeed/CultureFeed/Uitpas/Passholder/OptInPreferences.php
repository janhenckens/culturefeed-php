<?php

class CultureFeed_Uitpas_Passholder_OptInPreferences extends CultureFeed_Uitpas_ValueObject {

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


  public static function createFromXML(CultureFeed_SimpleXMLElement $object) {
    $preferences = new CultureFeed_Uitpas_Passholder_OptInPreferences();

    $preferences->optInServiceMails = $object->xpath_bool('optInServiceMails');
    $preferences->optInMilestoneMails = $object->xpath_bool('optInMilestoneMails');
    $preferences->optInInfoMails = $object->xpath_bool('optInInfoMails');
    $preferences->optInSms = $object->xpath_bool('optInSms');
    $preferences->optInPost = $object->xpath_bool('optInPost');

    return $preferences;
  }

  /**
   * {@inheritdoc}
   */
  public function toPostData() {

    $data = get_object_vars($this);
    foreach ($data as $key => $value) {
      $data[$key] = $value ? 'true' : 'false';
    }

    return $data;
  }

}
