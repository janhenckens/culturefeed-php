<?php

class CultureFeed_Uitpas_Event_CardSystemsTest extends PHPUnit_Framework_TestCase {
  const EVENTCDBID = "47B6FA21-ACB1-EA8F-2C231182C7DD0A19";

  public function testGetCardSystemsForEvent() {
    /** @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject $oauth_client_stub */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $get_xml = file_get_contents(dirname(__FILE__) . '/../data/cultureevent/getCardSystems.xml');

    $oauth_client_stub->expects($this->once())
      ->method('consumerGetAsXML')
      ->will($this->returnValue($get_xml));

    $cf = new CultureFeed($oauth_client_stub);

    $data = $cf->uitpas()->getCardSystemsForEvent(self::EVENTCDBID);

    $this->assertInstanceOf('CultureFeed_ResultSet', $data);

    $this->assertContainsOnly('CultureFeed_Uitpas_CardSystem', $data->objects);
    $this->assertCount(1, $data->objects);
    $this->assertEquals(1, $data->total);

    /* @var \CultureFeed_Uitpas_CardSystem $cardSystem */
    $cardSystem = reset($data->objects);

    $this->assertEquals(1, $cardSystem->id);
    $this->assertEquals("UiTPAS Dender", $cardSystem->name);

    $this->assertContainsOnly('CultureFeed_Uitpas_DistributionKey', $cardSystem->distributionKeys);
    $this->assertCount(1, $cardSystem->distributionKeys);

    $this->assertEquals(27, $cardSystem->distributionKeys[0]->id);
    $this->assertEquals('Speelplein HA', $cardSystem->distributionKeys[0]->name);
  }

  /**
   * Test the registering of an event.
   */
  public function testAddCardSystemToEvent() {

    $response = <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<response>
    <code>ACTION_SUCCEEDED</code>
    <cardSystems>
        <cardSystem>
            <id>1</id>
            <name>UiTPAS Dender</name>
            <distributionKeys>
                <distributionKey>
                    <id>27</id>
                    <name>Speelplein HA</name>
                    <conditions>
                        <condition>
                            <definition>KANSARM</definition>
                            <operator>IN</operator>
                            <value>MY_CARDSYSTEM</value>
                        </condition>
                    </conditions>
                    <tariff>3.0</tariff>
                    <priceClasses>
                        <priceClass>
                            <name>Basistarief</name>
                            <price>10.0</price>
                            <tariff>3.0</tariff>
                        </priceClass>
                    </priceClasses>
                    <automatic>false</automatic>
                </distributionKey>
            </distributionKeys>
        </cardSystem>
    </cardSystems>
</response>
XML;

    /** @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject $oauth_client_stub */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');
    $oauth_client_stub
      ->expects($this->once())
      ->method('consumerPostAsXml')
      ->with($this->equalTo('uitpas/cultureevent/' . self::EVENTCDBID . '/cardsystems'))
      ->will($this->returnValue($response));

    $cf = new CultureFeed($oauth_client_stub);

    $response = $cf->uitpas()->addCardSystemToEvent(self::EVENTCDBID, 1, 27);

    $this->assertInstanceOf('\CultureFeed_Uitpas_Response', $response);

    $this->assertEquals('ACTION_SUCCEEDED', $response->code);
    $this->assertNull($response->message);
  }

  public function testDeleteCardSystemFromEvent() {

    $response = <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<response>
    <code>ACTION_SUCCEEDED</code>
    <cardSystems/>
</response>
XML;

    $cardSystemId = 1;

    /** @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject $oauth_client_stub */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');
    $oauth_client_stub
      ->expects($this->once())
      ->method('request')
      ->with(
        'uitpas/cultureevent/' . self::EVENTCDBID . '/cardsystems/' . $cardSystemId,
        [],
        'DELETE',
        FALSE
      )
      ->willReturn($response);

    $cf = new CultureFeed($oauth_client_stub);

    $response = $cf->uitpas()->deleteCardSystemFromEvent(self::EVENTCDBID, $cardSystemId);

    $this->assertInstanceOf('\CultureFeed_Uitpas_Response', $response);

    $this->assertEquals('ACTION_SUCCEEDED', $response->code);
    $this->assertNull($response->message);
  }
}
