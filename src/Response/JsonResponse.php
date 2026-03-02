<?php

namespace Dysback\Ogo\Response;

class JsonResponse extends BaseResponse implements IResponse
{
    public function __construct(mixed $body, array $headers = [], StatusCode $statusCode = StatusCode::OK)
    {
        parent::__construct($body, array_merge($headers, ['Content-Type' => 'application/json']), $statusCode);
    }

    /**
     * Send the response to the client as JSON, including the headers and status code.
     * @return void
     */
    public function send(): void
    {
        parent::send();
        echo json_encode($this->body, JSON_PRETTY_PRINT);
    }
}
