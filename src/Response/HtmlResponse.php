<?php

namespace Dysback\Ogo\Response;

/**
 * Html response class.
 * @package Dysback\Ogo\Response
 */
class HtmlResponse extends BaseResponse implements IResponse
{
    public function __construct(mixed $body, array $headers = [], StatusCode $statusCode = StatusCode::OK)
    {
        parent::__construct($body, [...$headers, 'Content-Type' => 'text/html'], $statusCode);
    }
    /**
     * Send the response to the client as HTML, including the headers and status code.
     * @return void
     */
    public function send(): void
    {
        parent::send();
        echo $this->body;
    }
}
