<?php
date_default_timezone_set('Europe/London');
$I = new ApiTester($scenario);
$I->wantTo('check that I can get a book by valid isbn');
$I->sendGET('/application-rest/isbn/1853261599');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson();
$I->seeResponseContains('{"data":');
$I->seeResponseContains('"ISBN10":"1853261599"');
