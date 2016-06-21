<?php
/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 20/06/16
 * Time: 11:05
 */

namespace Application\Model;

use Application\Service\BookSearch;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\Factory;

/**
 * Class BookSearchFactory
 *
 * Instantiates the BookSearch service and injects its dependencies
 *
 * @package Application\Model
 */
class BookSearchFactory implements FactoryInterface
{
    /**
     * Creates the BookSearch service and injects its dependencies (book storage model and input filter)
     * @param ServiceLocatorInterface $serviceLocator
     * @return BookSearch
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var BookStorageModelInterface $bookStorageModel
         */
        $factory = new Factory();
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
        $bookStorageModel = $serviceLocator->get('bookstoragemodel');
        return new BookSearch($bookStorageModel, $inputFilter);
    }

}