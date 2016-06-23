<?php
date_default_timezone_set('Europe/London');
$I = new ApiTester($scenario);
$I->wantTo('Check that I can get a book by its title');
$I->sendGET('/application-rest/title/Rewards And Fairies');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson();
$I->seeResponseContains('"Rewards And Fairies"');
