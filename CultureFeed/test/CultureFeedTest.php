<?php

/**
 * @file
 * Testing methods for the culturefeed class.
 */

class CultureFeed_CultureFeedTest extends PHPUnit_Framework_TestCase {

  /**
   * @var Culturefeed
   */
  protected $cultureFeed;

  /**
   * @var CultureFeed_OAuthClient|PHPUnit_Framework_MockObject_MockObject
   */
  protected $oauthClient;

  public function setUp() {
    parent::setUp();

    $this->oauthClient = $this->getMock('CultureFeed_OAuthClient');
    $this->cultureFeed = new CultureFeed($this->oauthClient);
  }

  /**
   * @dataProvider getConsumerMethodDataProvider
   *
   * @param string $method
   * @param string $identifier
   * @param string $expectedPath
   */
  public function testGetConsumerWithGroups(
    $method,
    $identifier,
    $expectedPath
  ) {
    $xml = file_get_contents(__DIR__ . '/data/consumer_with_api_key_sapi3.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with($expectedPath)
      ->willReturn($xml);

    $consumer = $this->cultureFeed->{$method}($identifier);

    $this->assertEquals([744, 22074], $consumer->group);
  }

  /**
   * @dataProvider getConsumerMethodDataProvider
   *
   * @param string $method
   * @param string $identifier
   * @param string $expectedPath
   */
  public function testGetConsumerWithoutSapi3Properties(
    $method,
    $identifier,
    $expectedPath
  ) {
    $xml = file_get_contents(__DIR__ . '/data/consumer_without_sapi3_properties.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with($expectedPath)
      ->willReturn($xml);

    $consumer = $this->cultureFeed->{$method}($identifier);

    $this->assertNull($consumer->apiKeySapi3);
    $this->assertNull($consumer->searchPrefixSapi3);
  }

  /**
   * @dataProvider getConsumerMethodDataProvider
   *
   * @param string $method
   * @param string $identifier
   * @param string $expectedPath
   */
  public function testGetConsumerWithEmptySapi3Properties(
    $method,
    $identifier,
    $expectedPath
  ) {
    $xml = file_get_contents(__DIR__ . '/data/consumer_with_empty_sapi3_properties.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with($expectedPath)
      ->willReturn($xml);

    $consumer = $this->cultureFeed->{$method}($identifier);

    $this->assertEquals('', $consumer->apiKeySapi3);
    $this->assertEquals('', $consumer->searchPrefixSapi3);
  }

  /**
   * @dataProvider getConsumerMethodDataProvider
   *
   * @param string $method
   * @param string $identifier
   * @param string $expectedPath
   */
  public function testGetConsumerWithApiKeySapi3(
    $method,
    $identifier,
    $expectedPath
  ) {
    $xml = file_get_contents(__DIR__ . '/data/consumer_with_api_key_sapi3.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with($expectedPath)
      ->willReturn($xml);

    $consumer = $this->cultureFeed->{$method}($identifier);

    $this->assertEquals('c2436351-f314-4b83-916d-2e0a37502358', $consumer->apiKeySapi3);
    $this->assertEquals('', $consumer->searchPrefixSapi3);
  }

  /**
   * @dataProvider getConsumerMethodDataProvider
   *
   * @param string $method
   * @param string $identifier
   * @param string $expectedPath
   */
  public function testGetConsumerWithSearchPrefixSapi3(
    $method,
    $identifier,
    $expectedPath
  ) {
    $xml = file_get_contents(__DIR__ . '/data/consumer_with_search_prefix_sapi3.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with($expectedPath)
      ->willReturn($xml);

    $consumer = $this->cultureFeed->{$method}($identifier);

    $this->assertEquals('c2436351-f314-4b83-916d-2e0a37502358', $consumer->apiKeySapi3);
    $this->assertEquals('labels:foo AND regions:gem-leuven', $consumer->searchPrefixSapi3);
  }

  /**
   * @return array
   */
  public function getConsumerMethodDataProvider()
  {
    return [
        [
          'method' => 'getServiceConsumer',
          'identifier' => '5720d908-03cf-46da-8f5a-c43db621df5c',
          'expectedPath' => 'serviceconsumer/5720d908-03cf-46da-8f5a-c43db621df5c',
        ],
      [
        'method' => 'getServiceConsumerByApiKey',
        'identifier' => 'c2436351-f314-4b83-916d-2e0a37502358',
        'expectedPath' => 'serviceconsumer/apikey/c2436351-f314-4b83-916d-2e0a37502358',
      ],
    ];
  }

  /**
   * Test the handling of a succesfull user light call.
   */
  public function testGetUserLightId() {

    $success_xml = file_get_contents(dirname(__FILE__) . '/data/user_light_success.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with(
        'user/light',
        array('email' => 'test@test.be', 'homeZip' => '')
      )
      ->will($this->returnValue($success_xml));

    $uid = $this->cultureFeed->getUserLightId('test@test.be', '');
    $this->assertEquals('400118cc-d251-4eed-a36b-8fc5c2689f12', $uid);

  }

  /**
   * Test the handling of an empty xml when calling user light.
   * @expectedException Culturefeed_ParseException
   */
  public function testGetUserLightIdEmptyXmlParseException() {

    $without_uid_xml = file_get_contents(dirname(__FILE__) . '/data/user_light_without_uid.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with(
        'user/light',
        array('email' => 'test@test.be', 'homeZip' => '')
      )
      ->will($this->returnValue($without_uid_xml));

    $this->cultureFeed->getUserLightId('test@test.be', '');

  }

  /**
   * Test the handling of an invalid xml when calling user light.
   * @expectedException Culturefeed_ParseException
   */
  public function testGetUserLightInvalidXmlParseException() {

    $invalid_xml = file_get_contents(dirname(__FILE__) . '/data/user_light_invalid_xml.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with(
        'user/light',
        array('email' => 'test@test.be', 'homeZip' => '')
      )
      ->will($this->returnValue($invalid_xml));

    $this->cultureFeed->getUserLightId('test@test.be', '');

  }

  /**
   * Test the subscribing to mailing when authenticated
   */
  public function testSubscribeToMailingAuthenticated() {

    $subscribe_to_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe_to_mailing_success.xml');

    $this->oauthClient->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'mailing/v2/3/subscribe',
        array('userId' => 1)
      )
    ->will($this->returnValue($subscribe_to_mailing_xml));

    $this->cultureFeed->subscribeToMailing(1, 3);

  }

  /**
   * Test the subscribing to mailing as anonymous user.
   */
  public function testSubscribeToMailingConsumer() {

    $subscribe_to_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe_to_mailing_success.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerPostAsXml')
      ->with(
        'mailing/v2/3/subscribe',
        array('userId' => 1)
      )
    ->will($this->returnValue($subscribe_to_mailing_xml));

    $this->cultureFeed->subscribeToMailing(1, 3, FALSE);
  }

  /**
   * Test the error code handling
   */
  public function testSubscribeToMailingErrorCodeHandling() {

    $subscribe_to_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe_to_mailing_error.xml');

    $this->oauthClient->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'mailing/v2/3/subscribe',
        array('userId' => 1)
      )
    ->will($this->returnValue($subscribe_to_mailing_xml));

    $this->setExpectedException('CultureFeed_InvalidCodeException', 'errormessage');
    $this->cultureFeed->subscribeToMailing(1, 3);

  }

  /**
   * Test the unsubscribing of a mailing list as authenticated user.
   */
  public function testUnSubscribeFromMailingAuthenticated() {

    $unsubscribe_from_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/unsubscribe_from_mailing_success.xml');

    $this->oauthClient->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'mailing/v2/3/unsubscribe',
        array('userId' => 1)
      )
      ->will($this->returnValue($unsubscribe_from_mailing_xml));

    $this->cultureFeed->unSubscribeFromMailing(1, 3);
  }

  /**
   * Test the unsubscribing of a mailing list as anonymous user.
   */
  public function testUnSubscribeFromMailingConsumer() {

    $unsubscribe_from_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/unsubscribe_from_mailing_success.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerPostAsXml')
      ->with(
        'mailing/v2/3/unsubscribe',
        array('userId' => 1)
      )
      ->will($this->returnValue($unsubscribe_from_mailing_xml));

    $this->cultureFeed->unSubscribeFromMailing(1, 3, FALSE);

  }

  /**
   * Test the error code handling
   */
  public function testUnSubscribeFromMailingErrorCodeHandling() {

    $subscribe_to_mailing_xml = file_get_contents(dirname(__FILE__) . '/data/subscribe_to_mailing_error.xml');

    $this->oauthClient->expects($this->once())
      ->method('authenticatedPostAsXml')
      ->with(
        'mailing/v2/3/unsubscribe',
        array('userId' => 1)
      )
    ->will($this->returnValue($subscribe_to_mailing_xml));

    $this->setExpectedException('CultureFeed_InvalidCodeException', 'errormessage');
    $this->cultureFeed->unsubscribeFromMailing(1, 3);

  }

  /**
   * Test searching users.
   */
  public function testSearchUsers() {
    $query = new CultureFeed_SearchUsersQuery();
    $query->name = 'john';
    $query->mboxIncludePrivate = true;

    $search_users_xml = file_get_contents(dirname(__FILE__) . '/data/search_users.xml');

    $this->oauthClient->expects($this->once())
      ->method('consumerGetAsXml')
      ->with(
        'user/search',
        array(
            'name' => 'john',
            'mboxIncludePrivate' => 'true'
        )
      )
      ->will($this->returnValue($search_users_xml));

    $johnDoe = new CultureFeed_SearchUser();
    $johnDoe->id = 'A5912755-8060-4CB7-B0D1-51717725A46A';
    $johnDoe->nick = 'john.doe';
    $johnDoe->mbox = 'john.doe@example.com';
    $johnDoe->depiction = '//media.uitid.be/fis/rest/download/ce126667652776f0e9e55160f12f5463/uiv/default.png';
    $johnDoe->sortValue = null;

    $johnySmith = new CultureFeed_SearchUser();
    $johnySmith->id = '0AC1C30A-4821-4D82-98AD-ADC02FBC0059';
    $johnySmith->nick = 'johny.smith';
    $johnySmith->mbox = 'johny@acme.com';
    $johnySmith->depiction = '//media.uitid.be/fis/rest/download/ce126667652776f0e9e55160f12f5325/uiv/picture-5015.jpg';
    $johnySmith->sortValue = null;

    $expectedUsers = array(
      $johnDoe,
      $johnySmith
    );

    $expectedResults = new CultureFeed_ResultSet(2, $expectedUsers);

    $actualResults = $this->cultureFeed->searchUsers($query);

    $this->assertEquals($expectedResults, $actualResults);
  }

}
