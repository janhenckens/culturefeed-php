<?php

interface ICultureFeed {

  public function getConsumer();

  public function getToken();

  public function getRequestToken($callback = '');

  public function getUrlAuthorize($token, $callback = '', $type = CultureFeed::AUTHORIZE_TYPE_REGULAR, $skip_confirmation = FALSE, $skip_authorization = FALSE, $via = '', $language = '', $consumerKey = '');

  public function getAccessToken($oauth_verifier);

  public function createUser(CultureFeed_User $user);

  public function getUserPreferences($uid);

  public function setUserPreferences($uid, CultureFeed_Preferences $preferences);

  public function updateUser(CultureFeed_User $user, $fields = array());

  public function deleteUser($id);

  public function getUser($id, $private = FALSE, $use_auth = TRUE);

  public function getUserLightId($email, $home_zip = '');

  public function searchUsers(CultureFeed_SearchUsersQuery $query);

  public function getSimilarUsers($id);

  public function uploadUserDepiction($id, $file_data);

  public function removeUserDepiction($id);

  public function resendMboxConfirmationForUser($id);

  public function updateUserPrivacy($id, CultureFeed_UserPrivacyConfig $privacy_config);

  public function getUserServiceConsumers($id);

  public function revokeUserServiceConsumer($user_id, $consumer_id);

  public function updateUserOnlineAccount($id, CultureFeed_OnlineAccount $account);

  public function deleteUserOnlineAccount($id, $account_type, $account_name);

  public function createActivity(CultureFeed_Activity $activity);

  public function updateActivity($id, $private);

  public function deleteActivity($id);

  public function searchActivities(CultureFeed_SearchActivitiesQuery $query);

  public function searchActivityUsers($nodeId, $type, $contentType, $start = NULL, $max = NULL);

  public function getTotalActivities($userId, $type_contentType, $private = FALSE);

  public function getTotalPageActivities($pageId, $type_contentType, $private = FALSE);

  public function getActivityPointsTimeline($userId);

  public function getActivityPointsPromotion($promotionId);

  public function getActivityPointsPromotions($params = array());

  public function cashInPromotion($userId, array $promotionId, array $promotionCount);

  public function getTemplate($id);

  public function createTemplate(CultureFeed_Template $template);

  public function updateTemplate(CultureFeed_Template $template, $fields = array());

  public function getTemplateList();

  public function deleteTemplate($id);

  public function getMailing($id);

  public function createMailing(CultureFeed_Mailing $mailing);

  public function updateMailing(CultureFeed_Mailing $mailing, $fields = array());

  public function disableMailing($id);

  public function deleteMailing($id);

  public function getMailingList(CultureFeed_SearchMailingsQuery $query);

  public function sendTestMailing($user_id, $mailing_id);

  public function sendMailing($id);

  public function searchMailings(CultureFeed_SearchMailingsQuery $query);

  public function subscribeToMailing($user_id, $mailing_id, $use_auth = TRUE);

  public function unsubscribeFromMailing($user_id, $mailing_id, $use_auth = TRUE);

  public function getMailingSubscriptions($user_id, $use_auth = TRUE);

  public function getTopEvents($type, $max = 5);

  public function getRecommendationsForUser($id, CultureFeed_RecommendationsQuery $query = NULL);

  public function getRecommendationsForEvent($id, CultureFeed_RecommendationsQuery $query = NULL);

  public function evaluateRecommendation($id, $evaluation);

  public function getNotificationsCount($userId, $dateFrom = NULL);

  public function getNotifications($userId, $params = array());

  public function getNodeStatus($contentType, $nodeId, $userId);

  public function followNode($contentType, $nodeId, $userId);

  public function unFollowNode($contentType, $nodeId, $userId);

  public function getUrlAddSocialNetwork($network, $destination = '');

  public function getUrlChangePassword($id, $destination = '');

  public function getUrlLogout($destination = '');

  /**
   * @param int $start
   *   Row number to start with
   * @param int $max
   *   Total resuls to return
   * @param array $filters
   *   Array of extra filters to send.
   * @return CultureFeed_ResultSet
   */
  public function getServiceConsumers($start = 0, $max = NULL, $filters = array());

  public function createServiceConsumer(CultureFeed_Consumer $consumer);

  public function updateServiceConsumer(CultureFeed_Consumer $consumer);

  public function addUitpasPermission(CultureFeed_Consumer $consumer, $permissionGroup);

  /**
   * Add a user as service consumer admin.
   * @param $consumerKey
   * @param $uid
   */
  public function addServiceConsumerAdmin($consumerKey, $uid);

  public function getServiceConsumer($consumerKey);

  public function getServiceConsumerByApiKey($apiKey, $includePermissions = TRUE);

  /**
   * @return CultureFeed_Uitpas
   */
  public function uitpas();

  /**
   * @return CultureFeed_Pages
   */
  public function pages();

  /**
   * @return CultureFeed_Messages
   */
  public function messages();

  /**
   * @return CultureFeed_SavedSearches
   */
  public function savedSearches();

  public function getClient();

  /**
   * Post a message to social network.
   *
   * The object should be initialized with the consumer token and user access token of the user who is acted upon.
   *
   * @param int $id
   *   ID of the user to push to his social account.
   * @param string $accountName
   *   ID of the social account
   * @param string $accountType
   *   type of social account (twitter/facebook)
   * @param string $message
   *   message to post to social account
   * @param string $image
   *   URL of an image (twitter ignore this parameter)
   * @param string $link
   *   Url to post
   */
  public function postToSocial($id, $account_name, $account_type, $message, $image = NULL, $link = NULL);

}
