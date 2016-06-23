<?php

/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 20/06/16
 * Time: 20:31
 */

use ApplicationTest\Bootstrap;

class BookSearchServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Application\Service\BookSearch $bookSearchService
     */
    protected $bookSearchService;
    protected $serviceManager;
    protected $storage;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $factory = new \Zend\InputFilter\Factory();
        $inputFilter = $factory->createInputFilter(array(
            'author_first_name' => array(
                'name'       => 'author_first_name',
                'required'   => false,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'alnum',
                        'options' => array(
                            'allowWhiteSpace' => true
                        ),
                    ),
                ),
            ),
            'author_last_name' => array(
                'name'       => 'author_first_name',
                'required'   => false,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'alnum',
                        'options' => array(
                            'allowWhiteSpace' => true
                        ),
                    ),
                ),
            ),
            'title' => array(
                'name'       => 'title',
                'required'   => false,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'alnum',
                        'options' => array(
                            'allowWhiteSpace' => true
                        ),
                    ),
                ),
            ),
            'published_before' => array(
                'name' => 'published_before',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'date',
                    ),
                ),
            ),
            'published_after' => array(
                'name' => 'published_after',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'date',
                    ),
                ),
            ),
            'minimum_rating' => array(
                'name' => 'minrating',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'is_int',
                    ),
                    array(
                        'name' => 'between',
                        'options' => array(
                            'min' => 0,
                            'max' => 5,
                        ),
                    )
                ),
            ),
            'isbn10' => array(
                'name' => 'isbn10',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'isbn',
                    ),
                ),
            ),
            'isbn13' => array(
                'name' => 'isbn13',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                    ),
                    array(
                        'name' => 'isbn',
                    ),
                )
            )

        ));
        $this->storage = $this->getMock('Application\Model\RdbBookSearch', array('searchBooks'), array(), '', false);

        $this->bookSearchService = new \Application\Service\BookSearch($this->storage, $inputFilter);

    }

    public function testExchangeArrayExceptIsbnAndAuthor()
    {
        $params = array(
            'title' => 'Rewards And Fairies',
            'published_before' => '2002-01-01',
            'published_after' => '2001-01-01',
            'minrating' => '4',
        );

        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);

        $this->assertAttributeEquals('Rewards And Fairies', 'title', $bookSearchService);
        $this->assertAttributeEquals('2002-01-01', 'latestPublishingDate', $bookSearchService);
        $this->assertAttributeEquals('2001-01-01', 'earliestPublishingDate', $bookSearchService);
        $this->assertAttributeEquals('4', 'minimumRating', $bookSearchService);
    }
    public function testExchangeArrayIsbn10Mapping()
    {
        $params = array(
            'title' => 'Rewards And Fairies',
            'published_before' => '2002-01-01',
            'published_after' => '2001-01-01',
            'minrating' => '4',
            'isbn' => '1234567890',
        );
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $this->assertAttributeEquals('1234567890', 'ISBN10', $bookSearchService);
    }

    public function testExchangeArrayIsbn13Mapping()
    {
        $params = array(
            'title' => 'Rewards And Fairies',
            'published_before' => '2002-01-01',
            'published_after' => '2001-01-01',
            'minrating' => '4',
            'isbn' => '1234',
        );
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $this->assertAttributeEquals('1234', 'ISBN13', $bookSearchService);

    }

    public function testExchangeArrayAuthorLastNameMapping()
    {
        $params = array(
            'title' => 'Rewards And Fairies',
            'published_before' => '2002-01-01',
            'published_after' => '2001-01-01',
            'minrating' => '4',
            'isbn' => '1234',
            'author' => 'Gibbon',
        );
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $this->assertAttributeEquals(null, 'authorFirstName', $bookSearchService);
        $this->assertAttributeEquals('Gibbon', 'authorLastName', $bookSearchService);
    }

    public function testExchangeArrayAuthorFullNameMapping()
    {
        $params = array(
            'title' => 'Rewards And Fairies',
            'published_before' => '2002-01-01',
            'published_after' => '2001-01-01',
            'minrating' => '4',
            'isbn' => '1234',
            'author' => 'Gibbon, Edward',
        );
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $this->assertAttributeEquals('Edward', 'authorFirstName', $bookSearchService);
        $this->assertAttributeEquals('Gibbon', 'authorLastName', $bookSearchService);

    }

    public function testSearchFilter()
    {
        $params = array('isbn' => '1234');
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);

        $this->assertArrayHasKey('error', $bookSearchService->searchBooks());
    }

    public function testSearchBooksValidInput()
    {
        $params = array('isbn' => '1853261386');
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $bookSearchService->setStorageModel($this->storage);

        $this->assertArrayHasKey('data', $bookSearchService->searchBooks());
    }

    public function testSearchIsbnValidationIsPresent()
    {
        $params = array('isbn' => '1853');
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $bookSearchService->setStorageModel($this->storage);

        $this->assertArrayHasKey('isbn13', $bookSearchService->searchBooks()['error']);
    }

    public function testSearchAuthorValidationIsPresent()
    {
        $params = array('author' => '12..3, 45..6');
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $bookSearchService->setStorageModel($this->storage);

        $this->assertArrayHasKey('author_last_name', $bookSearchService->searchBooks()['error']);
    }

    public function testSearchAuthorLastNameValidationIsPresent()
    {
        $params = array('author' => '45..6');
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $bookSearchService->setStorageModel($this->storage);

        $this->assertArrayHasKey('author_last_name', $bookSearchService->searchBooks()['error']);
    }

    public function testSearchTitleValidationIsPresent()
    {
        $params = array('title' => '45..6');
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $bookSearchService->setStorageModel($this->storage);

        $this->assertArrayHasKey('title', $bookSearchService->searchBooks()['error']);
    }

    public function testMinimumDateValidationIsPresent()
    {
        $params = array('published_before' => '10.10.2001');
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $bookSearchService->setStorageModel($this->storage);

        $this->assertArrayHasKey('published_before', $bookSearchService->searchBooks()['error']);

    }

    public function testMaximumDateValidationIsPresent()
    {
        $params = array('published_after' => '10.10.2001');
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $bookSearchService->setStorageModel($this->storage);

        $this->assertArrayHasKey('published_after', $bookSearchService->searchBooks()['error']);

    }

    public function testMinimumRatingValidationIsPresent()
    {
        $params = array('minrating' => '3.5');
        $bookSearchService = clone $this->bookSearchService;
        $bookSearchService->exchangeArray($params);
        $bookSearchService->setStorageModel($this->storage);

        $this->assertArrayHasKey('minimum_rating', $bookSearchService->searchBooks()['error']);
    }

    public function testExceptionHandling()
    {
        $params = array('minrating' => '3.5');
        $bookSearchService = clone $this->bookSearchService;

        $storageModel = clone $this->storage;
        $storageModel->expects($this->once())
            ->method('searchBooks')
            ->will($this->throwException(new \Exception));

        $bookSearchService->exchangeArray($params);
        $bookSearchService->setStorageModel($this->storage);

        $this->assertArrayHasKey('error', $bookSearchService->searchBooks());

    }
}