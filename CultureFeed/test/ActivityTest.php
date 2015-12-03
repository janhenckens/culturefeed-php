<?php

class CultureFeed_ActivityTest extends PHPUnit_Framework_TestCase {

  public function testToPostData() {

    $activity = new CultureFeed_Activity();
    $activity->priority = CultureFeed_Activity::PRIORITY_NORMAL;
    
    $result = $activity->toPostData();

    $this->assertTRUE(array_key_exists('priority', $result));
  }
}

