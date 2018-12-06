<?php

/**
 * @class
 * Representation of a permanent / permanentOpeningTimes element in the cdb xml.
 */
class CultureFeed_Uitpas_Calendar_Permanent extends CultureFeed_Cdb_Data_Calendar_Permanent
{
  public static function parseFromCdbXml(SimpleXMLElement $xmlElement)
  {
    $calendar = new CultureFeed_Cdb_Data_Calendar_Permanent();

    // The calendar is namespaced with ns2.
    $ns = $xmlElement->getNamespaces();
    $children = $xmlElement->children($ns['ns2']);

    $permanentXml = $children->permanentopeningtimes->permanent;

    if (!empty($permanentXml->weekscheme)) {
      $calendar->setWeekScheme(
        CultureFeed_Cdb_Data_Calendar_Weekscheme::parseFromCdbXml(
          $permanentXml->weekscheme
        )
      );
    }

    if (!empty($xmlElement->exceptions)) {
      $calendar->setExceptions(
        CultureFeed_Cdb_Data_Calendar_Exceptions::parseFromCdbXml(
          $permanentXml->exceptions
        )
      );
    }

    return $calendar;
  }
}