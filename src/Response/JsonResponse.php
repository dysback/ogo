<?php

namespace Dysback\Ogo\Response;

class JsonResponse extends BaseResponse implements IResponse
{
    public function __construct(mixed $body, array $headers = [], StatusCode $statusCode = StatusCode::OK)
    {
        parent::__construct($statusCode, [...$headers, 'Content-Type' => 'application/json'], $body);
    }
    public function send(): void
    {
        parent::send();
        echo json_encode($this->body);
    }
}
