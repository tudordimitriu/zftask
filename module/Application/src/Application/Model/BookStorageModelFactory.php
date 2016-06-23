<?php
/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 20/06/16
 * Time: 10:54
 */

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\Factory;

class BookStorageModelFactory implements FactoryInterface
{
    /**
     * Interpret context and instantiate the book search storage model
     * @param ServiceLocatorInterface $serviceLocator
     * @return RdbBookSearch
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var Adapter $dbAdapter
         */
        $dbAdapter = $serviceLocator->get('dbadapter');
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

        return new RdbBookSearch($dbAdapter, $inputFilter);
    }

}