<?php

/**
 * @class
 * Interface for pages management
 */
interface CultureFeed_Pages {

  /**
   * Get the detail of a page.
   * @param string $id
   *   Page id to load.
   */
  public function getPage($id);

  /**
   * Get the list from all users for a given page id.
   * @param string $id
   *   Page id to query.
   * @param array $roles
   *   Roles to filter on. (ADMIN / MEMBER / FOLLOWER)
   * @return CultureFeed_ResultSet
   */
  public function getUserList($id, $roles = array());

  /**
   * Add a member to the given page.
   * @param string $id
   *   Id of the page.
   * @param string $userId
   *   User ID of the user to add.
   * @param string $relation
   *   Relation to add
   * @param bool $activityPrivate
   *   Log an activity or not.
   */
  public function addMember($id, $userId, $relation = CultureFeed_Pages_Membership::MEMBERSHIP_ROLE_MEMBER, $activityPrivate = TRUE);

}
