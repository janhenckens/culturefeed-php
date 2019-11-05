<?php

class CultureFeed_Uitpas_Event_TicketSale_Coupon extends CultureFeed_Uitpas_ValueObject {
  /**
   * @var string
   */
  public $id;

  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $description;

  /**
   * @var string
   */
  public $validFrom;

  /**
   * @var string
   */
  public $validTo;

  /**
   * @var CultureFeed_Uitpas_PeriodConstraint
   */
  public $exchangeConstraint;

  /**
   * @var CultureFeed_Uitpas_PeriodConstraint
   */
  public $buyConstraint;

  /**
   * @var CultureFeed_Uitpas_PeriodConstraint
   */
  public $remainingTotal;

  /**
   * @var CultureFeed_Uitpas_CardSystem
   */
  public $cardSystem;

  /**
   * @param CultureFeed_SimpleXMLElement $object
   * @return CultureFeed_Uitpas_Event_TicketSale_Coupon
   */
  public static function createFromXml(CultureFeed_SimpleXMLElement $object)
  {
    $coupon = new CultureFeed_Uitpas_Event_TicketSale_Coupon();

    $coupon->id = $object->xpath_str('id', FALSE);
    $coupon->name = $object->xpath_str('name', FALSE);
    $coupon->description = $object->xpath_str('description', FALSE);
    $coupon->validFrom = $object->xpath_time('validFrom');
    $coupon->validTo = $object->xpath_time('validTo');

    $exchangeConstraintElement = $object->xpath('exchangeConstraint', FALSE);
    if ($exchangeConstraintElement instanceof CultureFeed_SimpleXMLElement) {
      $coupon->exchangeConstraint = CultureFeed_Uitpas_PeriodConstraint::createFromXML($exchangeConstraintElement);
    }

    $buyConstraintElement = $object->xpath('buyConstraint', FALSE);
    if ($buyConstraintElement instanceof CultureFeed_SimpleXMLElement) {
      $coupon->buyConstraint = CultureFeed_Uitpas_PeriodConstraint::createFromXML($buyConstraintElement);
    }

    $remainingTotalElement = $object->xpath('remainingTotal', FALSE);
    if ($remainingTotalElement instanceof CultureFeed_SimpleXMLElement) {
      $coupon->remainingTotal = CultureFeed_Uitpas_PeriodConstraint::createFromXML($remainingTotalElement);
    }

    $cardSystem = $object->xpath('cardSystem', FALSE);
    if ($cardSystem instanceof CultureFeed_SimpleXMLElement) {
      $coupon->cardSystem = CultureFeed_Uitpas_CardSystem::createFromXML($cardSystem);
    }

    return $coupon;
  }
}
