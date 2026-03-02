<?php

namespace Dysback\Ogo\Response;

/**
 * Interface for all responses.
 * @package Dysback\Ogo\Response
 */
interface IResponse
{
    /**
     * Get the status code of the response. Example: StatusCode::OK, StatusCode::NOT_FOUND, INTERNAL_SERVER_ERROR, etc.
     * @return StatusCode The status code of the response.
     */
    //public function getStatusCode(): StatusCode;

    /**
     * Get the headers of the response. Example: ['Content-Type' => 'application/json', 'X-Frame-Options' => 'DENY']
     * @return array The headers of the response.
     */
    //public function getHeaders(): array;

    /**
     * Get the body of the response.
     * @return mixed The body of the response.
     */
    //public function getBody(): mixed;

    /**
     * Send the response to the client.
     * @return void
     */
    public function send(): void;
}
