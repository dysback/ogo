<?php

namespace Dysback\Ogo\Router;

interface IRouter
{
    public function route(string $path): void;
    public function getPath(): string;
    public function getPathPieces(): array;
    public function getModule(): string;
    //public function getView(): string;
    public function getParams(): array;
    public function getParam($key): string;
    public function getRouteType(): string;
}
