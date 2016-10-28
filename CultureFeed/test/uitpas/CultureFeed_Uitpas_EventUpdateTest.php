<?php
/**
 * @file
 * PHPUnit Testing the registering of an event.
 */

class CultureFeed_Uitpas_EventUpdateTest extends PHPUnit_Framework_TestCase {

  /**
   * Test the update of an event.
   */
  public function testUpdateEvent() {

    $response = <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<response>
  <code>1</code>
  <message>foo</message>
</response>
XML;

    /* @var $oauth_client_stub PHPUnit_Framework_MockObject_MockObject|CultureFeed_OAuthClient */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');
    $oauth_client_stub
      ->expects($this->once())
      ->method('consumerPostAsXml')
      ->with($this->equalTo('/uitpas/cultureevent/update'), array())
      ->will($this->returnValue($response));

    $event = new CultureFeed_Uitpas_Event_CultureEvent();
    $event->cdbid = 'da4cf0be-b28b-4b1d-b66f-50adc5638594';
    $event->organiserId = 'b101b61b-1d91-4216-908e-2c0ac16bc490';
    $event->locationId = 'abd76139-5b0d-42b1-ba5b-a40172e27fba';

    $distributionKey200 = new CultureFeed_Uitpas_DistributionKey();
    $distributionKey200->id = '200';
    $distributionKey200->name = 'Distribution key 200';

    $distributionKey201 = new CultureFeed_Uitpas_DistributionKey();
    $distributionKey201->id = '201';
    $distributionKey201->name = 'Distribution key 201';

    $event->distributionKey = array(
      $distributionKey200,
      $distributionKey201,
    );

    $cf = new CultureFeed($oauth_client_stub);

    $response = $cf->uitpas()->updateEvent($event);

    $this->assertInstanceOf('\CultureFeed_Uitpas_Response', $response);

    $this->assertEquals(1, $response->code);
    $this->assertEquals('foo', $response->message);
  }

}
