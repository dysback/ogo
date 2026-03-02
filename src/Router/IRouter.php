<?php

namespace Dysback\Ogo\Router;

/**
 * Interface for all routers.
 * @package Dysback\Ogo\Router
 */
interface IRouter
{
    /**
     * Route the request to the appropriate service and method.
     * @param string $path The path of the request.
     * @return void
     */
    public function route(string $path): void;
    //public function getPath(): string;
    //public function getPathPieces(): array;
    //public function getModule(): string;
    //public function getView(): string;

    /**
     * Get the parameters from the request.
     * @return array The parameters from the request.
     */
    public function getParams(): array;
    //public function getParam($key): string;
    //public function getRouteType(): string;
}
