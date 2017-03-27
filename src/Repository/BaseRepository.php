<?php
/**
 * Clarifai Concepts
 *
 * @category Repository
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

use DarrynTen\Clarifai\Request\RequestHandler;

/**
 * Class BaseRepository for the same methods collection
 *
 * @package DarrynTen\Clarifai\Repository
 */
abstract class BaseRepository
{
    /**
     * @var RequestHandler
     */
    private $request;

    public function __construct(RequestHandler $request)
    {
        $this->request = $request;
    }

    /**
     * @return RequestHandler
     */
    protected function getRequest()
    {
        return $this->request;
    }
}
