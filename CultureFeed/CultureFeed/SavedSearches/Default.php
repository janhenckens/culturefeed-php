<?php

/**
 * @class
 * Contains all methods for saved searches.
 */
class CultureFeed_SavedSearches_Default implements CultureFeed_SavedSearches {

  /**
   * CultureFeed object to make CultureFeed core requests.
   * @var ICultureFeed
   */
  protected $culturefeed;

  /**
   * OAuth request object to do the request.
   *
   * @var CultureFeed_OAuthClient
   */
  protected $oauth_client;

  public function __construct(ICultureFeed $culturefeed) {
    $this->culturefeed = $culturefeed;
    $this->oauth_client = $culturefeed->getClient();
  }

  /**
   * {@inheritdoc}
   */
  public function subscribe(CultureFeed_SavedSearches_SavedSearch $savedSearch) {
    $result = $this->oauth_client->authenticatedPostAsXml('savedSearch/subscribe', $savedSearch->toPostData());

    try {
      $xml_element = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $search_element = $xml_element->xpath('/response/savedSearch');
    if (empty($search_element)) {
      throw new CultureFeed_ParseException($result);
    }
    return $this->parseSavedSearch($search_element[0]);
  }

  /**
   * {@inheritdoc}
   */
  public function unsubscribe($savedSearchId, $userId) {
    $this->oauth_client->authenticatedPostAsXml('savedSearch/' . $savedSearchId . '/unsubscribe', array('userId' => $userId));
  }

  /**
   * {@inheritdoc}
   */
  public function changeFrequency($savedSearchId, $frequency) {
    $this->oauth_client->authenticatedPostAsXml('savedSearch/' . $savedSearchId . '/frequency', array('frequency' => $frequency));
  }

  /**
   * {@inheritdoc}
   */
  public function getSavedSearch($savedSearchId) {

    $result = $this->oauth_client->authenticatedGetAsXml('savedSearch/' . $savedSearchId);
    try {
      $xmlElement = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $searchElement = $xmlElement->xpath('savedSearch');
    if (empty($searchElement)) {
      throw new CultureFeed_ParseException($result);
    }
    return $this->parseSavedSearch($searchElement[0]);

  }

  /**
   * {@inheritdoc}
   */
  public function getList($allConsumers = FALSE) {

    $result = $this->oauth_client->authenticatedGetAsXml('savedSearch/list', array('all' => $allConsumers ? 'true' : 'false'));
    try {
      $xmlElement = new CultureFeed_SimpleXMLElement($result);
    }
    catch (Exception $e) {
      throw new CultureFeed_ParseException($result);
    }

    $savedSearches = array();

    $searchElements = $xmlElement->xpath('/response/savedSearches/savedSearch');
    if (empty($searchElements)) {
      throw new CultureFeed_ParseException($result);
    }
    foreach ($searchElements as $searchElement) {
      $search = $this->parseSavedSearch($searchElement);
      $savedSearches[$search->id] = $search;
    }

    return $savedSearches;

  }

  /**
   * Parse a saved search.
   * @param CultureFeed_SimpleXMLElement $xmlElement
   */
  private function parseSavedSearch($xmlElement) {

    $search = new CultureFeed_SavedSearches_SavedSearch();
    $search->id = $xmlElement->xpath_int('id');
    $search->frequency = $xmlElement->xpath_str('frequency');
    $search->name = $xmlElement->xpath_str('name');
    $search->query = $xmlElement->xpath_str('query');

    return $search;

  }

}
