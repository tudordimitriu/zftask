<?php
date_default_timezone_set('Europe/London');
$I = new ApiTester($scenario);
$I->wantTo('check that i get an errorif a provide a missformed published before date');
$I->sendGET('/application-rest/published-before/2001-01-01-foo');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson();
$I->seeResponseContains('{"error":');
