<?php 
date_default_timezone_set('Europe/London');
$I = new ApiTester($scenario);
$I->wantTo('Check that I get an error if I provide a missformed published after date');
$I->sendGET('/application-rest/published-before/2001-01-01-foo');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson();
$I->seeResponseContains('{"error":');
