<?php
/**
 * Created by PhpStorm.
 * User: tudormihaiioandimitriu
 * Date: 20/06/16
 * Time: 11:21
 */

namespace Application\Model;

/**
 * Interface BookStorageModelInterface
 *
 * Defines an interface that all book storage models must comply with.
 * @package Application\Model
 */
interface BookStorageModelInterface
{
    public function searchBooks(array $filters);
}