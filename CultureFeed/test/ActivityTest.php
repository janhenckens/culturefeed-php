<?php

class CultureFeed_ActivityTest extends PHPUnit_Framework_TestCase {

  public function testPriorityToPostData() {

    $activity = new CultureFeed_Activity();

    $result = $activity->toPostData();
    $this->assertFALSE(array_key_exists('priority', $result));
    
    $activity->priority = CultureFeed_Activity::PRIORITY_NORMAL;
    $result = $activity->toPostData();
    $this->assertTRUE(array_key_exists('priority', $result));
  }
}

