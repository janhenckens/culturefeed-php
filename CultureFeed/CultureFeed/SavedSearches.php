<?php

/**
 * @class
 * Interface for savedSearches implementations.
 */
interface CultureFeed_SavedSearches {

  /**
   * Subscribe a new saved search.
   *
   * @param CultureFeed_SavedSearches_SavedSearch $savedSearch
   *   SavedSearch to subscribe.
   * @return CultureFeed_SavedSearches_SavedSearch
   *   The saved search object.
   * @throws CultureFeed_ParseException
   *   If the result could not be parsed.
   */
  public function subscribe(CultureFeed_SavedSearches_SavedSearch $savedSearch);

  /**
   * Unsubscribe a user from a saved search.
   *
   * @param int $savedSearchId
   *   Saved search id to unscribe from.
   * @param string $userId
   *   UserId to unsubscribe.
   */
  public function unsubscribe($savedSearchId, $userId);

  /**
   * Change the frequency of a saved search.
   *
   * @param int $savedSearchId
   *   Saved search id to change.
   * @param string $frequency
   *   New frequency to set.
   */
  public function changeFrequency($savedSearchId, $frequency);

  /**
   * Get a list of all savedSearches for current user.
   *
   * @param bool $allConsumers
   *   Give a list of savedsearches in all consumers, or only on current consumer.
   * @return array
   *   List of savedSearches.
   * @throws CultureFeed_ParseException
   *   If the result could not be parsed.
   */
  public function getList($allConsumers = FALSE);

  /**
   * Load a saved search by id.
   * @param int $savedSearchId
   * @return CultureFeed_SavedSearches_SavedSearch
   *   The requested saved search object.
   * @throws CultureFeed_ParseException
   *   If the result could not be parsed.
   */
  public function getSavedSearch($savedSearchId);

}
