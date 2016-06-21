<?php
date_default_timezone_set('Europe/London');
$I = new ApiTester($scenario);
$I->wantTo('check that I can get a book by valid isbn 13');
$I->sendGET('/application-rest/isbn/9782221109830');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson();
$I->seeResponseContains('{"data":');
$I->seeResponseContains('"ISBN13":"9782221109830"');
