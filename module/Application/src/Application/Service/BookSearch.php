<?php
/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 18/06/16
 * Time: 12:56
 */

namespace Application\Service;


use Application\Model\BookStorageModelInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * The actual BookService model
 * Note: This class could have extended Zend\Stdlib\ArrayObject but it woluld have complicated the factory structure
 * (dependencies would have had to be passed via setters in order to stick to Liskov's substitution principle).
 *
 * Class BookSearch
 * @package Application\Service
 */
class BookSearch
{
    protected $ISBN10;
    protected $ISBN13;
    protected $earliestPublishingDate;
    protected $latestPublishingDate;
    protected $minimumRating;
    protected $authorFirstName;
    protected $authorLastName;
    protected $title;
    protected $storageModel;
    protected $inputFilter;

    /**
     * BookSearch constructor.
     * @param BookStorageModelInterface $storageModel
     * @param InputFilterInterface $inputFilter
     */
    public function __construct(BookStorageModelInterface $storageModel, InputFilterInterface $inputFilter)
    {
        $this->storageModel = $storageModel;
        $this->inputFilter = $inputFilter;
    }

    /**
     * Used to hydrate the model. Maps a parameters array to the actual internal representation of the BookSearch service filters.
     * Identifies ISBN type (10 or 13 digits) and splits author first name from authro last name when appropriate.
     * @param $data
     */
    public function exchangeArray($data)
    {
        if(isset($data['isbn'])) {
            if(strlen($data['isbn']) == 10) {
                $this->ISBN10 = $data['isbn'];
                $this->ISBN13 = null;
            } else {
                $this->ISBN13 = $data['isbn'];
                $this->ISBN10 = null;
            }
        }
        if(isset($data['author'])) {
            $bits = explode(',', $data['author']);
            if(count($bits) == 2) {
                $this->authorFirstName = trim($bits[1], ' ');
                $this->authorLastName = trim($bits[0], ' ');
            } else {
                $this->authorLastName = $data['author'];
            }
        }
        if(isset($data['published_before'])) {
            $this->latestPublishingDate = $data['published_before'];
        }
        if(isset($data['published_after'])) {
            $this->earliestPublishingDate = $data['published_after'];
        }
        if(isset($data['minrating'])) {
            $this->minimumRating = $data['minrating'];
        }
        if(isset($data['title'])) {
            $this->title = $data['title'];
        }
    }

    /**
     * Validates filters parameters and passes them over to the storage model for actual data extraction.
     * @return array
     */
    public function searchBooks()
    {
        $filters = $this->getFilters();
        $this->getInputFilter()->setData($filters);

        if(!$this->getInputFilter()->isValid()) {
            return array('error' => $this->getInputFilter()->getMessages());
        }
        //calling the storage model's search method in a try block in order to avoid potential db errors direclty to the
        // user.
        try {
            return array('data' => $this->getStorageModel()->searchBooks($filters));
        } catch (\Exception $exception) {
            return array('error' => 'Something went wront in the storage layer. Please contact an admin :).');
            //@todo add code to handle logging error details for later
        }
    }

    /**
     * Prepares the filters to be passed to the storage models. Removes all undefined filters.
     * @return array
     */
    protected function getFilters()
    {
        $filters = array();
        $filters['isbn10'] = $this->ISBN10;
        $filters['isbn13'] = $this->ISBN13;
        $filters['published_before'] = $this->latestPublishingDate;
        $filters['published_after'] = $this->earliestPublishingDate;
        $filters['title'] = $this->title;
        $filters['minimum_rating'] = $this->minimumRating;
        $filters['author_first_name'] = $this->authorFirstName;
        $filters['author_last_name'] = $this->authorLastName;

        $filters = array_filter($filters);

        return $filters;
    }

    /**
     * @return BookStorageModelInterface
     */
    protected function getStorageModel()
    {
        return $this->storageModel;
    }

    /**
     * @return InputFilter
     */
    protected function getInputFilter()
    {
        return $this->inputFilter;
    }

    /**
     * Allows changing the storage model at runtime. Also used in tests to replace it with a mock.
     * @param BookStorageModelInterface $newStorage
     * @return bool
     */
    public function setStorageModel(BookStorageModelInterface $newStorage)
    {
        $this->storageModel = $newStorage;
        return true;
    }

}