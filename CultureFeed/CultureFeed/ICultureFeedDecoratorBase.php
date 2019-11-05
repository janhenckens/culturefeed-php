<?php
/**
 * @file
 */

/**
 * Base class for ICultureFeed decorators.
 */
abstract class CultureFeed_ICultureFeedDecoratorBase implements ICultureFeed
{
    protected $realCultureFeed;

    public function __construct(ICultureFeed $decoratee)
    {
        $this->realCultureFeed = $decoratee;
    }

    public function cashInPromotion(
        $userId,
        array $promotionId,
        array $promotionCount
    ) {
        return $this->realCultureFeed->cashInPromotion(
            $userId,
            $promotionId,
            $promotionCount
        );
    }

    public function createActivity(CultureFeed_Activity $activity)
    {
        return $this->realCultureFeed->createActivity($activity);
    }

    public function createMailing(CultureFeed_Mailing $mailing)
    {
        return $this->realCultureFeed->createMailing($mailing);
    }

    public function createServiceConsumer(CultureFeed_Consumer $consumer)
    {
        return $this->realCultureFeed->createServiceConsumer($consumer);
    }

    public function createTemplate(CultureFeed_Template $template) {
        return $this->realCultureFeed->createTemplate($template);
    }

    public function createUser(CultureFeed_User $user)
    {
        return $this->realCultureFeed->createUser($user);
    }

    public function deleteActivity($id)
    {
        return $this->realCultureFeed->deleteActivity($id);
    }

    public function deleteMailing($id)
    {
        return $this->realCultureFeed->deleteMailing($id);
    }

    public function deleteTemplate($id) {
        return $this->realCultureFeed->deleteTemplate($id);
    }

    public function deleteUser($id)
    {
        return $this->realCultureFeed->deleteUser($id);
    }

    public function deleteUserOnlineAccount($id, $account_type, $account_name)
    {
        return $this->realCultureFeed->deleteUserOnlineAccount(
            $id,
            $account_type,
            $account_name
        );
    }

    public function disableMailing($id)
    {
        return $this->realCultureFeed->disableMailing($id);
    }

    public function evaluateRecommendation($id, $evaluation)
    {
        return $this->realCultureFeed->evaluateRecommendation($id, $evaluation);
    }

    public function followNode($contentType, $nodeId, $userId)
    {
        return $this->realCultureFeed->followNode(
            $contentType,
            $nodeId,
            $userId
        );
    }

    public function getAccessToken($oauth_verifier)
    {
        return $this->realCultureFeed->getAccessToken($oauth_verifier);
    }

    public function getActivityPointsPromotion($promotionId)
    {
        return $this->realCultureFeed->getActivityPointsPromotion($promotionId);
    }

    public function getActivityPointsPromotions($params = array())
    {
        return $this->realCultureFeed->getActivityPointsPromotions($params);
    }

    public function getActivityPointsTimeline($userId)
    {
        return $this->realCultureFeed->getActivityPointsTimeline($userId);
    }

    public function getClient()
    {
        return $this->realCultureFeed->getClient();
    }

    public function getConsumer()
    {
        return $this->realCultureFeed->getConsumer();
    }

    public function getMailing($id)
    {
        return $this->realCultureFeed->getMailing($id);
    }

    public function getMailingList(CultureFeed_SearchMailingsQuery $query)
    {
        return $this->realCultureFeed->getMailingList($query);
    }

    public function getMailingSubscriptions($user_id, $use_auth = TRUE)
    {
        return $this->realCultureFeed->getMailingSubscriptions($user_id, $use_auth);
    }

    public function getNodeStatus($contentType, $nodeId, $userId)
    {
        return $this->realCultureFeed->getNodeStatus(
            $contentType,
            $nodeId,
            $userId
        );
    }

    public function getNotifications($userId, $params = array())
    {
        return $this->realCultureFeed->getNotifications($userId, $params);
    }

    public function getNotificationsCount($userId, $dateFrom = null)
    {
        return $this->realCultureFeed->getNotificationsCount(
            $userId,
            $dateFrom
        );
    }

    public function getRecommendationsForEvent(
        $id,
        CultureFeed_RecommendationsQuery $query = null
    ) {
        return $this->realCultureFeed->getRecommendationsForEvent($id, $query);
    }

    public function getRecommendationsForUser(
        $id,
        CultureFeed_RecommendationsQuery $query = null
    ) {
        return $this->realCultureFeed->getRecommendationsForUser($id, $query);
    }

    public function getRequestToken($callback = '')
    {
        return $this->realCultureFeed->getRequestToken($callback);
    }

    public function getServiceConsumer($consumerKey) {
        return $this->realCultureFeed->getServiceConsumer($consumerKey);
    }

    public function getServiceConsumerByApiKey($apiKey, $includePermissions = TRUE) {
      return $this->realCultureFeed->getServiceConsumerByApiKey($apiKey, $includePermissions);
    }

    public function getServiceConsumers($start = 0, $max = null, $filters = array())
    {
        return $this->realCultureFeed->getServiceConsumers($start, $max, $filters);
    }

    public function getSimilarUsers($id)
    {
        return $this->realCultureFeed->getSimilarUsers($id);
    }

    public function getTemplate($id) {
        return $this->realCultureFeed->getTemplate($id);
    }

    public function getTemplateList() {
        return $this->realCultureFeed->getTemplateList();
    }

    public function getToken()
    {
        return $this->realCultureFeed->getToken();
    }

    public function getTopEvents($type, $max = 5)
    {
        return $this->realCultureFeed->getTopEvents($type, $max);
    }

    public function getTotalActivities(
        $userId,
        $type_contentType,
        $private = false
    ) {
        return $this->realCultureFeed->getTotalActivities(
            $userId,
            $type_contentType,
            $private
        );
    }

    public function getTotalPageActivities(
        $pageId,
        $type_contentType,
        $private = false
    ) {
        return $this->realCultureFeed->getTotalPageActivities(
            $pageId,
            $type_contentType,
            $private
        );
    }

    public function getUrlAddSocialNetwork($network, $destination = '')
    {
        return $this->realCultureFeed->getUrlAddSocialNetwork(
            $network,
            $destination
        );
    }

    public function getUrlAuthorize(
        $token,
        $callback = '',
        $type = CultureFeed::AUTHORIZE_TYPE_REGULAR,
        $skip_confirmation = false,
        $skip_authorization = false,
        $via = '',
        $language = '',
        $consumerKey = ''
    ) {
        return $this->realCultureFeed->getUrlAuthorize(
            $token,
            $callback,
            $type,
            $skip_confirmation,
            $skip_authorization,
            $via,
            $language,
            $consumerKey
        );
    }

    public function getUrlChangePassword($id, $destination = '')
    {
        return $this->realCultureFeed->getUrlChangePassword(
            $id,
            $destination
        );
    }

    public function getUrlLogout($destination = '')
    {
        return $this->realCultureFeed->getUrlLogout($destination);
    }

    public function getUser($id, $private = false, $use_auth = true)
    {
        return $this->realCultureFeed->getUser(
            $id,
            $private,
            $use_auth
        );
    }

    public function getUserLightId($email, $home_zip = '')
    {
        return $this->realCultureFeed->getUserLightId($email, $home_zip);
    }

    public function getUserPreferences($uid)
    {
        return $this->realCultureFeed->getUserPreferences($uid);
    }

    public function getUserServiceConsumers($id)
    {
        return $this->realCultureFeed->getUserServiceConsumers($id);
    }

    public function addServiceConsumerAdmin($consumerKey, $uid)
    {
        return $this->realCultureFeed->addServiceConsumerAdmin($consumerKey, $uid);
    }

    public function removeUserDepiction($id)
    {
        return $this->realCultureFeed->removeUserDepiction($id);
    }

    public function resendMboxConfirmationForUser($id)
    {
        return $this->realCultureFeed->resendMboxConfirmationForUser($id);
    }

    public function revokeUserServiceConsumer($user_id, $consumer_id)
    {
        return $this->realCultureFeed->revokeUserServiceConsumer(
            $user_id,
            $consumer_id
        );
    }

    public function searchActivities(CultureFeed_SearchActivitiesQuery $query)
    {
        return $this->realCultureFeed->searchActivities($query);
    }

    public function searchActivityUsers(
        $nodeId,
        $type,
        $contentType,
        $start = null,
        $max = null
    ) {
        return $this->realCultureFeed->searchActivityUsers(
            $nodeId,
            $type,
            $contentType,
            $start,
            $max
        );
    }

    public function searchMailings(CultureFeed_SearchMailingsQuery $query)
    {
        return $this->realCultureFeed->searchMailings($query);
    }

    public function searchUsers(CultureFeed_SearchUsersQuery $query)
    {
        return $this->realCultureFeed->searchUsers($query);
    }

    public function sendMailing($id)
    {
        return $this->realCultureFeed->sendMailing($id);
    }

    public function sendTestMailing($user_id, $mailing_id)
    {
        return $this->realCultureFeed->sendTestMailing($user_id, $mailing_id);
    }

    public function setUserPreferences(
        $uid,
        CultureFeed_Preferences $preferences
    ) {
        return $this->realCultureFeed->setUserPreferences(
            $uid,
            $preferences
        );
    }

    public function subscribeToMailing($user_id, $mailing_id, $use_auth = TRUE)
    {
        return $this->realCultureFeed->subscribeToMailing($user_id, $mailing_id, $use_auth);
    }

    public function unFollowNode($contentType, $nodeId, $userId)
    {
        return $this->realCultureFeed->unFollowNode(
            $contentType,
            $nodeId,
            $userId
        );
    }

    /**
     * @return CultureFeed_Uitpas
     */
    public function uitpas()
    {
        return $this->realCultureFeed->uitpas();
    }

    public function unsubscribeFromMailing($user_id, $mailing_id, $use_auth = TRUE)
    {
        return $this->realCultureFeed->unsubscribeFromMailing($user_id, $mailing_id, $use_auth);
    }

    public function updateActivity($id, $private)
    {
        return $this->realCultureFeed->updateActivity($id, $private);
    }

    public function updateMailing(
        CultureFeed_Mailing $mailing,
        $fields = array()
    ) {
        return $this->realCultureFeed->updateMailing($mailing, $fields);
    }

    public function updateServiceConsumer(CultureFeed_Consumer $consumer)
    {
        return $this->realCultureFeed->updateServiceConsumer($consumer);
    }

    public function updateTemplate(CultureFeed_Template $template, $fields = array()) {
        return $this->realCultureFeed->updateTemplate($template, $fields);
    }

    public function updateUser(CultureFeed_User $user, $fields = array())
    {
        return $this->realCultureFeed->updateUser($user, $fields);
    }

    public function updateUserOnlineAccount(
        $id,
        CultureFeed_OnlineAccount $account
    ) {
        return $this->realCultureFeed->updateUserOnlineAccount($id, $account);
    }

    public function updateUserPrivacy(
        $id,
        CultureFeed_UserPrivacyConfig $privacy_config
    ) {
        return $this->realCultureFeed->updateUserPrivacy($id, $privacy_config);
    }

    public function uploadUserDepiction($id, $file_data)
    {
        return $this->realCultureFeed->uploadUserDepiction($id, $file_data);
    }

    public function addUitpasPermission(CultureFeed_Consumer $consumer, $permissionGroup)
    {
        return $this->realCultureFeed->addUitpasPermission($consumer, $permissionGroup);
    }

    /**
     * {@inheritdoc}
     */
    public function messages()
    {
        return $this->realCultureFeed->messages();
    }

    /**
     * {@inheritdoc}
     */
    public function pages()
    {
        return $this->realCultureFeed->pages();
    }

    /**
     * {@inheritdoc}
     */
    public function postToSocial(
        $id,
        $account_name,
        $account_type,
        $message,
        $image = null,
        $link = null
    ) {
        return $this->realCultureFeed->postToSocial(
            $id,
            $account_name,
            $account_type,
            $message,
            $image,
            $link
        );
    }

    /**
     * {@inheritdoc}
     */
    public function savedSearches()
    {
        return $this->realCultureFeed->savedSearches();
    }

}
