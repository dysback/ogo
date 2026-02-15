<?php

namespace Dysback\Ogo\Response;

interface IResponse
{
    public function getStatusCode(): StatusCode;
    public function getHeaders(): array;
    public function getBody(): mixed;
    public function send(): void;
}
