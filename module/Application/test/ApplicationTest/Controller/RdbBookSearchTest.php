<?php

/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 21/06/16
 * Time: 10:47
 */
use ApplicationTest\Bootstrap;
use Application\Model\RdbBookSearch;

class RdbBookSearchTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Application\Model\RdbBookSearch $rdbBookSearch
     */
    protected $rdbBooksearch;
    protected $serviceManager;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();

        $dbAdapter = $this->serviceManager->get('dbadapter');
        $this->rdbBooksearch = new RdbBookSearch($dbAdapter);
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
        $results = $rdbBooksearch->searchBooks(array('author_last_name' => 'Kipling'));

        $this->assertCount(2, $results);
    }

    public function testGetByAuthorFirst()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('author_first_name' => 'Rudyard'));

        $this->assertCount(2, $results);
    }

    public function testGetByTitle()
    {
        $rdbBooksearch = clone $this->rdbBooksearch;
        $results = $rdbBooksearch->searchBooks(array('title' => 'Rewards And Fairies'));

        $this->assertCount(1, $results);
    }
}