<?php

namespace Dysback\Ogo\Router;

/**
 * Enum for request types.
 * @package Dysback\Ogo\Router
 */
enum RequestType: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}
