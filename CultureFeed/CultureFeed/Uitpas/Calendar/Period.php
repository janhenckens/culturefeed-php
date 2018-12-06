<?php


class CultureFeed_Uitpas_Calendar_Period implements \JsonSerializable {

  /**
   * The start date of the period
   *
   * @var integer
   */
  public $datefrom;

  /**
   * The end date of the period
   *
   * @var integer
   */
  public $dateto;

  /**
   * {@inheritdoc}
   */
  public function jsonSerialize()
  {
    return [
      'dateFrom' => $this->datefrom,
      'dateTo' => $this->dateto,
    ];
  }
}