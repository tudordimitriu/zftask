<?php
/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 18/06/16
 * Time: 16:04
 */

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class RdbBookSearch
 * Implements the searchBooks method for the provided MySQL storage sample. Could be replaced by another class working with
 * a different storage but implementing the same BookStorageModelInterface.
 * @package Application\Model
 */
class RdbBookSearch implements BookStorageModelInterface
{
    /**
     * @var AdapterInterface
     */
    protected $dbAdapter;
    protected $inputFilter;

    /**
     * RdbBookSearch constructor.
     * Used by the BookStorageModelFactory to inject the Database Adapter
     * @param AdapterInterface $dbAdapter
     */
    public function __construct(AdapterInterface $dbAdapter, InputFilterInterface $inputFilter)
    {
        $this->dbAdapter = $dbAdapter;
        $this->inputFilter = $inputFilter;
    }

    /**
     * Parses and array of filters and builds the db query to extract the book records. Gets the results and returns them
     * to the book search service.
     * @param array $filters
     * @return array
     * @throws \Exception
     */
    public function searchBooks(array $filters)
    {
        $this->inputFilter->setData($filters);
        if(!$valid = $this->inputFilter->isValid()) {
            throw new \Exception('Filters are not valid');
        }
        $filters = $this->inputFilter->getValues();
        $where = array();

        if(isset($filters['title'])) {
            $where[] = new Expression('LOWER(Title) LIKE ?', array('%'.$filters['title'].'%'));
        }
        if(isset($filters['isbn10'])) {
            $where['ISBN10'] = $filters['isbn10'];
        }
        if(isset($filters['isbn13'])) {
            $where['ISBN13'] = $filters['isbn13'];
        }
        if(isset($filters['published_after'])) {
            $whereObject = new Where();
            $whereObject->greaterThanOrEqualTo('Date', $filters['published_after']);
            $where[] = $whereObject;
        }
        if(isset($filters['published_before'])) {
            $whereObject = new Where();
            $whereObject->lessThanOrEqualTo('Date', $filters['published_before']);
            $where[] = $whereObject;
        }
        if(isset($filters['minimum_rating'])) {
            $whereObject = new Where();
            $whereObject->greaterThanOrEqualTo('Rating', $filters['minimum_rating']);
            $where[] = $whereObject;
        }
        $authorCondition = array();
        $authorParams = array();
        if(isset($filters['author_last_name'])) {
            $authorCondition[] = 'LOWER( WhereAuthor.LastName ) LIKE ? ';
            $authorParams[] = '%'.$filters['author_last_name'].'%';
        }

        if(isset($filters['author_first_name'])) {
            $authorCondition[] = 'LOWER( WhereAuthor.FirstName ) LIKE ? ';
            $authorParams[] = '%'.$filters['author_first_name'].'%';
        }

        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('Edition');
        $select->where($where);
        $select->join(
            'Title',
            'Edition.IdTitle = Title.IdTitle',
            '*',
            'inner'
        );
        if(count($authorCondition)) {
            $select->join('TitleAuthor',
                'Title.IdTitle = TitleAuthor.IdTitle',
                null,
                'inner'
            );
            $select->join(
                array('WhereAuthor' => 'Author'),
                new Expression('TitleAuthor.IdAuthor = WhereAuthor.IdAuthor AND '.implode(' AND ', $authorCondition), $authorParams),
                null,
                'inner'
            );
        }
        $select->join(
            array('ta' => 'TitleAuthor'),
            'Title.IdTitle = ta.IdTitle',
            array(),
            'left'
        );
        $select->join(
            'Author',
            'ta.IdAuthor = Author.IdAuthor',
            '*',
            'inner'
        );
        $select->join(
            'Publisher',
            'Edition.IdPublisher = Publisher.IdPublisher',
            array('PublisherName' => 'Name'),
            'inner'
        );
        $select->order('Title.Title');

        $statement = $sql->prepareStatementForSqlObject($select);

        $results = $statement->execute();
        $return = array();
        $oldId = null;
        $bookData = null;
        foreach($results as $value) {
            $authorName = $value['LastName'].', '.$value['FirstName'];
            if($value['IdTitle'] != $oldId) {
                if (!is_null($oldId)) {
                    $return[] = $bookData;
                }
                $bookData = array_filter($value);
                unset($bookData['FirstName']);
                unset($bookData['LastName']);
                unset($bookData['Name']);
                unset($bookData['IdPublisher']);
                unset($bookData['IdAuthor']);
                unset($bookData['IdEdition']);
                $oldId = $bookData['IdTitle'];
                unset($bookData['IdTitle']);
            }
            $bookData['Author'][] = $authorName;
        }
        $return[] = $bookData;

        return $return;
    }
}