<?php


class CultureFeed_Uitpas_Calendar_Timestamp implements \JsonSerializable {

  /**
   * The date
   *
   * @var integer
   */
  public $date;

  /**
   * The start time
   *
   * @var integer
   */
  public $timestart;
  
  /**
   * The end time
   *
   * @var integer
   */
  public $timeend;

  /**
   * {@inheritdoc}
   */
  public function jsonSerialize()
  {
    return [
      'date' => $this->date,
      'timeStart' => $this->timestart,
      'timeEnd' => $this->timeend,
    ];
  }
}