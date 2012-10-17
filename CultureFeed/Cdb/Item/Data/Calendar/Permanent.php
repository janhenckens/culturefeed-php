<?php

/**
 * @class
 * Representation of a permanent / permanentOpeningTimes element in the cdb xml.
 */
class CultureFeed_Cdb_Calendar_Permanent extends CultureFeed_Cdb_Calendar implements ICultureFeed_Cdb_Element {

  /**
   * Exceptions for the opening times.
   * @var CultureFeed_Cdb_Calendar_Exceptions
   */
  protected $exceptions;

  /**
   * Week scheme for the permanent opening times.
   * @var CultureFeed_Cdb_Calendar_WeekScheme
   */
  protected $weekScheme;

  /**
   * Set the exceptions for the opening times.
   * @param CultureFeed_Cdb_Calendar_Exceptions $exceptions
   *   Exceptions to set.
   */
  public function setExceptions(CultureFeed_Cdb_Calendar_Exceptions $exceptions) {
    $this->exceptions = $exceptions;
  }

  /**
   * Set the week scheme for the opening times.
   * @param CultureFeed_Cdb_Calendar_WeekScheme $scheme
   *   Weekscheme to set.
   */
  public function setWeekScheme(CultureFeed_Cdb_Calendar_WeekScheme $scheme) {
    $this->weekScheme = $scheme;
  }

  /**
   * Get the weekscheme from the permanent calendar.
   */
  public function getWeekScheme() {
    return $this->weekScheme;
  }

  /**
   * Get the exceptions from the permanent calendar.
   */
  public function getExceptions() {
    return $this->exceptions;
  }

  /**
   * @see ICultureFeed_Cdb_Element::appendToDOM()
   */
  public function appendToDOM(DOMELement $element) {

    $dom = $element->ownerDocument;

    $calendarElement = $dom->createElement('calendar');
    $openingTimesElement = $dom->createElement('permanentopeningtimes');
    $permanentElement = $dom->createElement('permanent');

    if ($this->exceptions) {
      $this->exceptions->appendToDOM($permanentElement);
    }

    if ($this->weekScheme) {
      $this->weekScheme->appendToDom($permanentElement);
    }

    $openingTimesElement->appendChild($permanentElement);
    $calendarElement->appendChild($openingTimesElement);
    $element->appendChild($calendarElement);

  }

    /**
   * @see ICultureFeed_Cdb_Element::parseFromCdbXml($xmlElement)
   * @return CultureFeed_Cdb_Calendar_Permanent
   */
  public static function parseFromCdbXml($xmlElement) {

    $permanentXml = $xmlElement->permanentopeningtimes->permanent;
    $calendar = new CultureFeed_Cdb_Calendar_Permanent();

    if (!empty($permanentXml->weekscheme)) {
      $calendar->setWeekScheme(CultureFeed_Cdb_Calendar_Weekscheme::parseFromCdbXml($permanentXml->weekscheme));
    }

    if (!empty($permanentXml->exceptions)) {
      $calendar->setExceptions(CultureFeed_Cdb_Calendar_Exceptions::parseFromCdbXml($permanentXml->exceptions));
    }

    return $calendar;

  }

}
