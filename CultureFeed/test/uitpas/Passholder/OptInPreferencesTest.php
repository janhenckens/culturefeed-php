<?php

/**
 * Provides tests for OptinPreferences
 */
class CultureFeed_Uitpas_Passholder_OptinPreferencesTest extends PHPUnit_Framework_TestCase {

  public function testToPostData() {

    $data = [
      'optInServiceMails' => 'true',
      'optInInfoMails' => 'false',
      'optInMilestoneMails' => 'false',
      'optInSms' => 'true',
      'optInPost' => 'false',
    ];

    $optinPreferences = new CultureFeed_Uitpas_Passholder_OptInPreferences();
    $optinPreferences->optInServiceMails = true;
    $optinPreferences->optInInfoMails = false;
    $optinPreferences->optInMilestoneMails = false;
    $optinPreferences->optInSms = true;
    $optinPreferences->optInPost = false;
    $this->assertEquals($data, $optinPreferences->toPostData());

    $data = [
      'optInServiceMails' => 'false',
      'optInInfoMails' => 'true',
      'optInMilestoneMails' => 'true',
      'optInSms' => 'false',
      'optInPost' => 'true',
    ];

    $optinPreferences = new CultureFeed_Uitpas_Passholder_OptInPreferences();
    $optinPreferences->optInServiceMails = false;
    $optinPreferences->optInInfoMails = true;
    $optinPreferences->optInMilestoneMails = true;
    $optinPreferences->optInSms = false;
    $optinPreferences->optInPost = true;
    $this->assertEquals($data, $optinPreferences->toPostData());

  }

}
