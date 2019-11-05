<?php

class CultureFeed_Uitpas_CardSystemsTest extends PHPUnit_Framework_TestCase {

  const ORGANIZERCDBID = "47B6FA21-ACB1-EA8F-2C231182C7DD0A19";

  public function testGetCardSystemsForOrganizer() {
    /** @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject $oauth_client_stub */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $get_xml = file_get_contents(dirname(__FILE__) . '/data/cardsystems/get.xml');

    $oauth_client_stub->expects($this->once())
      ->method('consumerGetAsXML')
      ->will($this->returnValue($get_xml));

    $cf = new CultureFeed($oauth_client_stub);

    $data = $cf->uitpas()->getCardSystemsForOrganizer(self::ORGANIZERCDBID);

    $this->assertInstanceOf('CultureFeed_ResultSet', $data);

    $this->assertContainsOnly('CultureFeed_Uitpas_CardSystem', $data->objects);
    $this->assertCount(2, $data->objects);
    $this->assertEquals(2, $data->total);

    /* @var \CultureFeed_Uitpas_CardSystem $cardSystem */
    $cardSystem = reset($data->objects);

    $this->assertEquals(1, $cardSystem->id);
    $this->assertEquals("UiTPAS Dender", $cardSystem->name);

    $this->assertContainsOnly('CultureFeed_Uitpas_DistributionKey', $cardSystem->distributionKeys);
    $this->assertCount(3, $cardSystem->distributionKeys);

    $this->assertEquals(25, $cardSystem->distributionKeys[0]->id);
    $this->assertEquals('25% meerdaags  (regio)', $cardSystem->distributionKeys[0]->name);

    $this->assertEquals(23, $cardSystem->distributionKeys[1]->id);
    $this->assertEquals('€1,5 dag (regio)', $cardSystem->distributionKeys[1]->name);

    $this->assertEquals(24, $cardSystem->distributionKeys[2]->id);
    $this->assertEquals('€3  hele dag (regio)', $cardSystem->distributionKeys[2]->name);

    $cardSystem = next($data->objects);

    $this->assertEquals(15, $cardSystem->id);
    $this->assertEquals("UiTPAS", $cardSystem->name);
    $this->assertEmpty($cardSystem->distributionKeys);
  }
}
