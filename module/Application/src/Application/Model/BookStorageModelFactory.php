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
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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
        
        return new RdbBookSearch($dbAdapter);
    }

}