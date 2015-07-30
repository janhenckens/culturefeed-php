<?php
/**
 * @file
 */ 

class CultureFeed_Uitpas_CardInfo {

  /**
   * @var CultureFeed_Uitpas_CardSystem
   */
  public $cardSystem;

  /**
   * @var string
   */
  public $uitpasNumber;

  /**
   * @var string
   */
  public $status;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   *
   * @return self
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object) {
    $instance = new static();

    $cardSystemXml = $object->xpath('cardSystem', FALSE);
    if ($cardSystemXml instanceof CultureFeed_SimpleXMLElement) {
      $instance->cardSystem = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystemXml);
    }

    $instance->uitpasNumber = $object->xpath_str('uitpasNumber');
    if (empty($instance->uitpasNumber)) {
      $instance->uitpasNumber = $object->xpath_str('uitpasNumber/uitpasNumber');
    }

    $instance->status = $object->xpath_str('status');

    return $instance;
  }

  /**
   * @return bool
   */
  public function kansenStatuut() {
    return '1' == substr($this->uitpasNumber, -2, 1);
  }
}
