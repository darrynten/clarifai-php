<?php
/**
 * Clarifai API Exception
 *
 * @category Exception
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Exception;

use Exception;

/**
 * Custom exception for Clarifai
 *
 * @package Clarifai
 */
class ApiException extends Exception
{

    /**
     * @inheritdoc
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        // Construct message from JSON if required.
        if (preg_match('/^[\[\{]\"/', $message)) {
            $messageObject = json_decode($message);
            $message = sprintf(
                '%s: %s - %s',
                $messageObject->status,
                $messageObject->title,
                $messageObject->detail
            );
            if (!empty($messageObject->errors)) {
                $message .= ' ' . serialize($messageObject->errors);
            }
        }

        parent::__construct($message, $code, $previous);
    }
}
