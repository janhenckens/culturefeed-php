<?php

class CultureFeed_Uitpas_Event_TicketSalesTest extends PHPUnit_Framework_TestCase {
  const EVENTCDBID = "47B6FA21-ACB1-EA8F-2C231182C7DD0A19";

  public function testEventHasTicketSales() {
    /** @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject $oauth_client_stub */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $get_xml = file_get_contents(dirname(__FILE__) . '/../data/ticketsales/hasticketsales-true.xml');

    $oauth_client_stub->expects($this->once())
      ->method('consumerGetAsXML')
      ->will($this->returnValue($get_xml));

    $cf = new CultureFeed($oauth_client_stub);

    $hasTicketSales = $cf->uitpas()->eventHasTicketSales(self::EVENTCDBID);

    $this->assertTrue($hasTicketSales);
  }

  public function testEventHasNoTicketSales() {
    /** @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject $oauth_client_stub */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $get_xml = file_get_contents(dirname(__FILE__) . '/../data/ticketsales/hasticketsales-false.xml');

    $oauth_client_stub->expects($this->once())
      ->method('consumerGetAsXML')
      ->will($this->returnValue($get_xml));

    $cf = new CultureFeed($oauth_client_stub);

    $hasTicketSales = $cf->uitpas()->eventHasTicketSales(self::EVENTCDBID);

    $this->assertFalse($hasTicketSales);
  }

  public function testEventNotFound() {
    /** @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject $oauth_client_stub */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $get_xml = file_get_contents(dirname(__FILE__) . '/../data/ticketsales/hasticketsales-unknown-cdbid.xml');

    $oauth_client_stub->expects($this->once())
      ->method('consumerGetAsXML')
      ->will($this->returnValue($get_xml));

    $cf = new CultureFeed($oauth_client_stub);

    $this->setExpectedException('\CultureFeed_HttpException');
    $cf->uitpas()->eventHasTicketSales(self::EVENTCDBID);
  }

  public function testUnknownResponseCode() {
    /** @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject $oauth_client_stub */
    $oauth_client_stub = $this->getMock('CultureFeed_OAuthClient');

    $get_xml = file_get_contents(dirname(__FILE__) . '/../data/ticketsales/hasticketsales-unknown-code.xml');

    $oauth_client_stub->expects($this->once())
      ->method('consumerGetAsXML')
      ->will($this->returnValue($get_xml));

    $cf = new CultureFeed($oauth_client_stub);

    $this->setExpectedException('\CultureFeed_Cdb_ParseException');
    $cf->uitpas()->eventHasTicketSales(self::EVENTCDBID);
  }
}
