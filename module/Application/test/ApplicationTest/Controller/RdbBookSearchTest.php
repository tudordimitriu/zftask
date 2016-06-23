<?php

/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 21/06/16
 * Time: 10:47
 */
use ApplicationTest\Bootstrap;
use Application\Model\RdbBookSearch;
use Zend\InputFilter\Factory;

class RdbBookSearchTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Application\Model\RdbBookSearch $rdbBookSearch
     */
    protected $rdbBooksearch;
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();

        $factory = new Factory();

        $inputFilter = $factory->createInputFilter(array(
            'author_first_name' => array(
                'name'       => 'author_first_name',
                'required'   => false,
                'filters' => array(
                    array(
                        'name' => 'string_to_lower',
                    ),
                    array(
                        'name' => 'Word/SeparatorToSeparator',
                        'options' => array(
                            'search_separator' => ' ',
                            'replacement_separator' => '%',
                        ),
                    ),
                ),
            ),
            'author_last_name' => array(
                'name'       => 'author_first_name',
                'required'   => false,
                'filters' => array(
                    array(
                        'name' => 'string_to_lower',
                    ),
                    array(
                        'name' => 'Word/SeparatorToSeparator',
                        'options' => array(
                            'search_separator' => ' ',
                            'replacement_separator' => '%',
                        ),
                    ),
                ),
            ),
            'title' => array(
                'name'       => 'title',
                'required'   => false,
                'filters' => array(
                    array(
                        'name' => 'string_to_lower',
                    ),
                    array(
                        'name' => 'Word/SeparatorToSeparator',
                        'options' => array(
                            'search_separator' => ' ',
                            'replacement_separator' => '%',
                        ),
                    ),
                ),
            ),
            'published_before' => array(
                'name' => 'published_before',
                'required' => false,
            ),
            'published_after' => array(
                'name' => 'published_after',
                'required' => false,
            ),
            'minimum_rating' => array(
                'name' => 'minrating',
                'required' => false,
            ),
            'isbn10' => array(
                'name' => 'isbn10',
                'required' => false,
            ),
            'isbn13' => array(
                'name' => 'isbn13',
                'required' => false,
            )

        ));

        $dbAdapter = $this->serviceManager->get('dbadapter');
        $this->rdbBooksearch = new RdbBookSearch($dbAdapter, $inputFilter);
    }

    public function testGetAllRecords()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array());

        $this->assertCount(4, $results);
    }

    public function testGetByIsbn10()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('isbn10' => '1853261599'));

        $this->assertCount(1, $results);

    }

    public function testGetByIsbn13()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('isbn13' => '9789731931784'));

        $this->assertCount(1, $results);

    }

    public function testGetByMinRating()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('minimum_rating' => '5'));

        $this->assertCount(2, $results);
    }

    public function testGetPublishedAfter()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('published_after' => '2000-01-01'));

        $this->assertCount(2, $results);
    }

    public function testGetPublishedBefore()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('published_after' => '2000-01-01'));

        $this->assertCount(2, $results);
    }

    public function testGetByAuthorLast()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('author_last_name' => 'kipling'));

        $this->assertCount(2, $results);
    }

    public function testGetByAuthorFirst()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('author_first_name' => 'rudyard'));

        $this->assertCount(2, $results);
    }

    public function testGetByTitle()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('title' => 'rewards and fairies'));

        $this->assertCount(1, $results);
    }
}