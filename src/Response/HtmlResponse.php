<?php

namespace Dysback\Ogo\Response;

class HtmlResponse extends BaseResponse implements IResponse
{
    public function __construct(mixed $body, array $headers = [], StatusCode $statusCode = StatusCode::OK)
    {
        parent::__construct($body, [...$headers, 'Content-Type' => 'text/html'], $statusCode);
    }
    public function send(): void
    {
        parent::send();
        echo $this->body;
    }
}
