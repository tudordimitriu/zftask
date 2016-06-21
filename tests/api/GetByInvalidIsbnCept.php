<?php
date_default_timezone_set('Europe/London');
$I = new ApiTester($scenario);
$I->wantTo('check that I get an error when providing an invalid isbn');
$I->sendGET('/application-rest/isbn/9782221');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson();
$I->seeResponseContains('{"error":');
