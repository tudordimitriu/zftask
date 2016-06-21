<?php
/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 18/06/16
 * Time: 13:49
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class BookSearch
 * Using JsonModels to return JSON. Using different controllers with the same BookSearchSErvice could easily yield xml
 * output or otherwise
 * @package Application\Controller
 */
class BookSearchController extends AbstractRestfulController
{
    /**
     * Used for plain listing and for all searches that rely on a combination of criteria rather than ISBN alone.
     * The parameter structure is as follows:
     * [/author/:author][/title/:title][/rating/:minrating][/published-after/:published_after]
     * [/published-before/:published_before]
     *
     * @return JsonModel
     */
    public function getList()
    {
        /**
         * @var \Application\Service\BookSearch $bookSearch
         */
        $bookSearch = $this->getServiceLocator()->get('booksearch');

        $parameters = $this->params()->fromRoute();

        $bookSearch->exchangeArray($parameters);

        $model = new JsonModel($bookSearch->searchBooks());
        return $model;

    }

    /**
     * This action will be triggered if the id parameter is present. We use this for searching by ISBN, as an
     * ISBN should point to one book only.
     * @param mixed $id
     * @return JsonModel
     */
    public function get($id)
    {
        /**
         * @var \Application\Service\BookSearch $bookSearch
         */
        $bookSearch = $this->getServiceLocator()->get('booksearch');
        $bookSearch->exchangeArray(array('isbn' => $id));
        $model = new JsonModel($bookSearch->searchBooks());
        return $model;
    }
}