<?php
date_default_timezone_set('Europe/London');
$I = new ApiTester($scenario);
$I->wantTo('check that I can get a book by its full author name');
$I->sendGET('/application-rest/author/Smith, Adam');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson();
$I->seeResponseContains('Wealth of Nations');
