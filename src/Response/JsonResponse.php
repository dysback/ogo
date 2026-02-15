<?php

namespace Dysback\Ogo\Response;

class JsonResponse extends BaseResponse implements IResponse
{
    public function __construct(mixed $body, array $headers = [], StatusCode $statusCode = StatusCode::OK)
    {
        parent::__construct($body, array_merge($headers, ['Content-Type' => 'application/json']), $statusCode);
    }
    public function send(): void
    {
        parent::send();
        echo json_encode($this->body, JSON_PRETTY_PRINT);
    }
}
