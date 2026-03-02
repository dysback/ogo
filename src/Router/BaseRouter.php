<?php

namespace Dysback\Ogo\Router;

use Dysback\Ogo\App;

/**
 * Abstract base class for all routers.
 */
abstract class BaseRouter implements IRouter
{
    protected App $app;
    protected $path;
    protected $path_pieces;
    protected $module;
    protected $params = [];
    protected $route_type;
    protected string $namespace;


    /**
     * Route the request to the appropriate service and method.
     * @param string $path The path of the request.
     * @return void
     */
    abstract public function route(string $path): void;

    /**
     * Get the parameters from the request.
     * @return array The parameters from the request.
     */
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


    /**
     * Create a router based on the path. NOT USED AND NOT IMPLEMENTED FOR NOW.
     * @param string $path The path of the request.
     * @return IRouter The router.
     */
    public static function createRouter(string $path): IRouter
    {
        $path_pieces = explode('/', $path);
        $route_type = $path_pieces[0];
        switch ($route_type) {
            case 'app':
                $router = new PageRouter(App::getInstance());
                $router->route_type = 'page';
                break;
            case 'api':
                $router = new ApiRouter(App::getInstance());
                $router->route_type = 'api';
                break;
            default:
                throw new \Exception("Invalid route type: " . $route_type);
        }
        return $router;
    }

    /**
     * Get the request type from the request.
     * @return RequestType The request type.
     */
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
