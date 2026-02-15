<?php

namespace Dysback\Ogo\Response;

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

    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): mixed
    {
        return $this->body;
    }

    public function send(): void
    {
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
        http_response_code($this->statusCode->value);
    }
}
