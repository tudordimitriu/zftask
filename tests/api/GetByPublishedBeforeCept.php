<?php
date_default_timezone_set('Europe/London');
$I = new ApiTester($scenario);
$I->wantTo('check that i can get a book published before a date');
$I->sendGET('/application-rest/published-before/2001-01-01');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson();
$I->seeResponseContains('{"data":');
