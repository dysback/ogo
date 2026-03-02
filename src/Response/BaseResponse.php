<?php

namespace Dysback\Ogo\Response;

/**
 * Base class for all responses.
 * @package Dysback\Ogo\Response
 */
abstract class BaseResponse implements IResponse
{
    protected StatusCode $statusCode;
    protected array $headers;
    protected mixed $body;

    public function __construct(mixed $body, array $headers = [], StatusCode $statusCode = StatusCode::OK)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    /**
     * Send the response to the client, including the headers and status code.
     * @return void
     */
    public function send(): void
    {
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
        http_response_code($this->statusCode->value);
    }
}
