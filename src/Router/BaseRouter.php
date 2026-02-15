<?php

namespace Dysback\Ogo\Router;

abstract class BaseRouter implements IRouter
{
    protected $path;
    protected $path_pieces;
    protected $module;
    protected $params = [];
    protected $route_type;

    abstract public function route(string $path): void;
    /*
        public function __construct(string $path) {
            $this->path = $path;
            $this->path_pieces = explode('/', $path);
            $this->module = $this->path_pieces[1];
            $this->view = $this->path_pieces[2];
            $this->params = array_slice($this->path_pieces, 3);
            AppFileLogger::log("Path: " . $this->path, LogLevel::Debug, 'router');
            AppFileLogger::log("Path Pieces: " . implode(', ', $this->path_pieces), LogLevel::Debug, 'router');
            AppFileLogger::log("Module: " . $this->module, LogLevel::Debug, 'router');
            AppFileLogger::log("View: " . $this->view, LogLevel::Debug, 'router');
            AppFileLogger::log("Params: " . implode(', ', $this->params), LogLevel::Debug, 'router');
            AppFileLogger::log("Route Type: " . $this->route_type, LogLevel::Debug, 'router');
        }
    */
    public function getPath(): string
    {
        return $this->path;
    }

    public function getPathPieces(): array
    {
        return $this->path_pieces;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function getParams(): array
    {
        $request_type = $this->geReqestType();
        switch ($request_type) {
            case RequestType::GET:
                $this->params = array_slice($this->path_pieces, 4);
                break;
            case RequestType::POST:
                if (str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
                    $this->params = json_decode(file_get_contents('php://input'), true) ?? [];
                } else {
                    $this->params = $_POST;
                }
                break;
            case RequestType::PUT:
                //$this->params = $_PUT;
                //break;
            case RequestType::DELETE:
                //$this->params = $_DELETE;
                //break;
            default:
                $this->params = [];
                break;
        }
        return $this->params;
    }

    public function getParam($key): string
    {
        return $this->params[$key] ?? null;
    }

    public function getRouteType(): string
    {
        return $this->route_type;
    }


    public static function createRouter(string $path): IRouter
    {
        $path_pieces = explode('/', $path);
        $route_type = $path_pieces[0];
        switch ($route_type) {
            case 'app':
                $router = new PageRouter($path);
                $router->route_type = 'page';
                break;
            case 'api':
                $router = new ApiRouter($path);
                $router->route_type = 'api';
                break;
            default:
                throw new \Exception("Invalid route type: " . $route_type);
        }
        return $router;
    }

    public function geReqestType(): RequestType
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return RequestType::GET;
            case 'POST':
                return RequestType::POST;
            case 'PUT':
                return RequestType::PUT;
            case 'DELETE':
                return RequestType::DELETE;
            default:
                return RequestType::GET;
        }
    }
}
