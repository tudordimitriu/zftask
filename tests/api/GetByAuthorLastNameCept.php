<?php
date_default_timezone_set('Europe/London');
$I = new ApiTester($scenario);
$I->wantTo('Check that I can get a book by its author last name');
$I->sendGET('/application-rest/author/smith');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson();
$I->seeResponseContains('Wealth of Nations');
