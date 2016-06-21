<?php
/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 20/06/16
 * Time: 14:56
 */

use ApplicationTest\Bootstrap;
use Application\Controller\BookSearchController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;

class BookSearchControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var BookSearchController $controller;
     */
    protected $controller;
    /**
     * @var Request $request
     */
    protected $request;
    /**
     * @var \Zend\Http\PhpEnvironment\Response $response
     */
    protected $response;
    /**
     * @var RouteMatch $routeMatch
     */
    protected $routeMatch;
    /**
     * @var MvcEvent $event
     */
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new BookSearchController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'BookSearch'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    public function testGetListCanBeAccessed()
    {
        /**
         * @var \Zend\Http\PhpEnvironment\Response $response
         */
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        echo get_class($response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetCanBeAccessed()
    {
        /**
         * @var \Zend\Http\PhpEnvironment\Response $response
         */
        $this->routeMatch->setParam('id', '1');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
    
}