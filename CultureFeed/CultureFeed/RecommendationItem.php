<?php

/**
 * Class to represent a recommendation item (event).
 */
class CultureFeed_RecommendationItem {

  /**
   * ID of the recommendation item (CDBID of the event).
   *
   * @var string
   */
  public $id;

  /**
   * Permalink of the recommendation item (event).
   *
   * @var string
   */
  public $permalink;

  /**
   * Title of the recommendation item (event).
   *
   * @var string
   */
  public $title;

  /**
   * Description of the recommendation item (event).
   *
   * @var string
   */
  public $description;

  /**
   * Short description of the recommendation item (event).
   *
   * @var string
   */
  public $description_short;

  /**
   * Start date of the recommendation item (event) as a UNIX timestamp.
   *
   * @var integer
   */
  public $from;

  /**
   * End dat of the recommendation item (event) as a UNIX timestamp.
   *
   * @var integer
   */
  public $to;

  /**
   * Address of the recommendation item (event).
   *
   * @var string
   */
  public $location_simple;

  /**
   * Segment (zip) of the recommendation item (event).
   *
   * @var string
   */
  public $segment;

  /**
   * City of the recommendation item (event).
   *
   * @var string
   */
  public $location_city;

  /**
   * Coordinates of the recommendation item (event).
   *
   * @var CultureFeed_Location
   */
  public $location_latlong;

  /**
   * Thumbnail of the recommendation item (event).
   *
   * @var string
   */
  public $thumbnail;

}

