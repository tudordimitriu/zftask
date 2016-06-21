<?php
/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 20/06/16
 * Time: 10:16
 */

namespace Application\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

/**
 * Class DbAdapterFactory
 *
 * Creates the Database Adapter Service instance. Currently works with mysql on 127.0.0.1
 *
 * @package Application\Model
 */
class DbAdapterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = new Adapter(array(
            'driver' => 'Mysqli',
            'database' => 'bookdb',
            'username' => 'root',
            'password' => 'root',
            'hostname' => '127.0.0.1',
        ));
        return $adapter;
    }

}