<?php
/**
 * @file
 */

class CultureFeed_Uitpas_Passholder_ExecuteEventActionsResultTest extends PHPUnit_Framework_TestCase {

  protected $dataDir;

  protected function setUp() {
    $this->dataDir = dirname(__FILE__) . '/data/eventactions';
  }

  /**
   * @param string $file
   * @return CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult
   */
  protected function loadSample($file) {
    $xml = file_get_contents($this->dataDir. '/' . $file);
    $xml_element = new CultureFeed_SimpleXMLElement($xml);
    return CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult::createFromXML($xml_element);
  }

  public function testCreateFromXML() {
    $result = $this->loadSample('post.xml');

    $expectedPassholder = new CultureFeed_Uitpas_Passholder();
    $expectedPassholder->registrationBalieConsumerKey = '47B6FA21-ACB1-EA8F-2C231182C7DD0A19';
    $expectedPassholder->name = 'ryopl';
    $expectedPassholder->firstName = 'fatru';
    $expectedPassholder->nationality = 'ituliexhul';
    $expectedPassholder->dateOfBirth = 515721600;
    $expectedPassholder->gender = 'MALE';
    $expectedPassholder->street = 'umrop 8';
    $expectedPassholder->verified = false;
    $expectedPassholder->points = 10;
    $expectedPassholder->numberOfCheckins = 4;
    $expectedPassholder->inszNumberHash = 'f29f5e1a8289e6453fdfa6a51a01778a71adf70e';
    $expectedPassholder->gsm = '0485/55.62.24';
    $expectedPassholder->city = 'Aalst';
    $expectedPassholder->postalCode = '9300';

    $expectedPassholder->uitIdUser = new CultureFeed_Uitpas_Passholder_UitIdUser();
    $expectedPassholder->uitIdUser->id = 'eb0cc9fa-bf36-4898-8fce-a20ad040f3df';
    $expectedPassholder->uitIdUser->optInPreferences = new CultureFeed_Uitpas_Passholder_OptInPreferences();
    $expectedPassholder->uitIdUser->optInPreferences->optInServiceMails = true;
    $expectedPassholder->uitIdUser->optInPreferences->optInMilestoneMails = false;
    $expectedPassholder->uitIdUser->optInPreferences->optInInfoMails = true;
    $expectedPassholder->uitIdUser->optInPreferences->optInSms = false;
    $expectedPassholder->uitIdUser->optInPreferences->optInPost = true;

    $passholderCardSystemData = new CultureFeed_Uitpas_Passholder_CardSystemSpecific();
    $passholderCardSystemData->cardSystem = new CultureFeed_Uitpas_CardSystem();
    $passholderCardSystemData->cardSystem->id = '1';
    $passholderCardSystemData->cardSystem->name = 'UiTPAS Regio Aalst';

    $passholderCardSystemData->currentCard = new CultureFeed_Uitpas_Passholder_Card();
    $passholderCardSystemData->currentCard->kansenpas = false;
    $passholderCardSystemData->currentCard->status = CultureFeed_Uitpas_Passholder_Card::STATUS_ACTIVE;
    $passholderCardSystemData->currentCard->uitpasNumber = '0942000000885';

    $passholderCardSystemData->emailPreference = CultureFeed_Uitpas_Passholder_CardSystemPreferences::EMAIL_ALL_MAILS;
    $passholderCardSystemData->kansenStatuut = false;
    $passholderCardSystemData->kansenStatuutExpired = false;
    $passholderCardSystemData->kansenStatuutInGracePeriod = false;
    $passholderCardSystemData->smsPreference = CultureFeed_Uitpas_Passholder_CardSystemPreferences::SMS_NO_SMS;
    $passholderCardSystemData->status = 'ACTIVE';

    $expectedPassholder->cardSystemSpecific = array(
      $passholderCardSystemData->cardSystem->id => $passholderCardSystemData,
    );

    $this->assertEquals($expectedPassholder, $result->passholder);

    $expectedActions = array();

    $expectedBuyTicketResultAction =
      new CultureFeed_Uitpas_Passholder_ExecuteEventActionsResultAction();
    $expectedBuyTicketResultAction->actionType =
      $expectedBuyTicketResultAction::TYPE_BUYTICKET;
    $expectedBuyTicketResultAction->buyTicketResponse =
      new CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_BuyTicketResponse();
    $expectedBuyTicketResultAction->buyTicketResponse->code =
      'ACTION_SUCCEEDED';
    $expectedBuyTicketResultAction->buyTicketResponse->price = 10.0;
    $expectedBuyTicketResultAction->buyTicketResponse->tariff = 1.5;
    $expectedBuyTicketResultAction->buyTicketResponse->id = '16978';
    $expectedActions[] = $expectedBuyTicketResultAction;

    $expectedWelcomeAdvantageResultAction = new Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction();
    $expectedWelcomeAdvantageResultAction->actionType = Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction::TYPE_CASHIN_WELCOMEADVANTAGE;
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse = new CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_WelcomeAdvantageResponse();
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->code = 'ACTION_SUCCEEDED';
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->promotion = new CultureFeed_Uitpas_Passholder_WelcomeAdvantage();
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->promotion->id = 109;
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->promotion->title = 'poster Sterkendries';
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->promotion->points = 0;
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->promotion->cashedIn = true;
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->promotion->cashingDate = 1426161429;
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->promotion->owningCardSystem = new CultureFeed_Uitpas_CardSystem(1, 'UiTPAS Regio Aalst');
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->promotion->applicableCardSystems = array(
      new CultureFeed_Uitpas_CardSystem(1, 'UiTPAS Regio Aalst')
    );
    $expectedWelcomeAdvantageResultAction->welcomeAdvantageResponse->promotion->counters = array(
      new CultureFeed_Uitpas_Passholder_Counter('31413BDF-DFC7-7A9F-10403618C2816E44', 'CC De Werf'),
      new CultureFeed_Uitpas_Passholder_Counter('7B3D9697-FD79-7DE9-5FA4D6EB9EA1726D', 'Openbare Bibliotheek Lede'),
      new CultureFeed_Uitpas_Passholder_Counter('47B6FA21-ACB1-EA8F-2C231182C7DD0A19', 'CultuurNet Vlaanderen'),
      new CultureFeed_Uitpas_Passholder_Counter('71969d26e70b309de51addba97c2f064', 'Gemeentehuis Lede'),
    );

    $expectedActions[] = $expectedWelcomeAdvantageResultAction;

    $expectedPointsPromotionResultAction = new Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction();
    $expectedPointsPromotionResultAction->actionType = Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction::TYPE_CASHIN_POINTSPROMOTION;
    $expectedPointsPromotionResultAction->pointsPromotionsResponse = new CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_PointsPromotionsResponse();
    $expectedPointsPromotionResultAction->pointsPromotionsResponse->code = 'ACTION_SUCCEEDED';
    $expectedPointsPromotionResultAction->pointsPromotionsResponse->promotion = new CultureFeed_Uitpas_Passholder_PointsPromotion(
      480,
      'gratis koffie bij statik',
      1
    );

    $expectedPointsPromotionResultAction->pointsPromotionsResponse->promotion->cashedIn = TRUE;
    $expectedPointsPromotionResultAction->pointsPromotionsResponse->promotion->counters = array(
      new CultureFeed_Uitpas_Passholder_Counter('31413BDF-DFC7-7A9F-10403618C2816E44', 'CC De Werf'),
    );

    $expectedActions[] = $expectedPointsPromotionResultAction;

    $expectedCheckinResponseResultAction = new Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction();
    $expectedCheckinResponseResultAction->actionType = Culturefeed_Uitpas_Passholder_ExecuteEventActionsResultAction::TYPE_CHECKIN;

    $expectedCheckinResponseResultAction->checkinResponse = new CultureFeed_Uitpas_Passholder_ExecuteEventActionsResult_CheckinResponse();
    $expectedCheckinResponseResultAction->checkinResponse->code = 'ACTION_SUCCEEDED';
    $expectedCheckinResponseResultAction->checkinResponse->points = 10;

    $expectedActions[] = $expectedCheckinResponseResultAction;

    $this->assertEquals($expectedActions, $result->actions);
  }
}
