<?php

namespace Dysback\Ogo\Router;

use Dysback\Ogo\App;
use Dysback\Ogo\Logger\LogLevel;
use Dysback\Ogo\Controller\IController;
use Dysback\Ogo\Response\JsonResponse;
use Dysback\Ogo\Response\StatusCode;

class ApiRouter extends BaseRouter
{
    private IController $controller;
    private string $service_class_name;
    private string $service;
    private string $method;
    private RequestType $request_type;

    private string $namespace;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }
    public function __xconstruct(string $path)
    {
        $this->path = $path;
        $this->path_pieces = explode('/', $path);
        $this->module = $this->path_pieces[1];
        $this->service = $this->path_pieces[2];
        $this->method = $this->path_pieces[3];

        $this->request_type = $this->geReqestType();
        switch ($this->request_type) {
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
        //echo "**** file_get_contents('php://input'):: " . print_r(file_get_contents('php://input'), true) . ":: ++++";
        //echo "**** params: " . print_r($this->params, true);


        App::getInstance()->logger->log(['Path' => $this->path,
            'Path Pieces' => implode(', ', $this->path_pieces),
            'Module' => $this->module,
            'Service' => $this->service,
            'Method' => $this->method,
            'Params' => print_r($this->params, true),
            'Route Type' => $this->route_type], LogLevel::DEBUG, 'router');
    }
    public function getService(): string
    {
        return $this->service;
    }
    public function getMethod(): string
    {
        return $this->method;
    }

    public function route(string $path): void
    {
        try {
            $this->path = $path;
            $this->path_pieces = explode('/', $path);
            $module = $this->path_pieces[1];
            $service = $this->path_pieces[2];
            $method = $this->path_pieces[3];

            $this->service_class_name = "{$this->namespace}\\{$module}\\{$service}";
            $this->controller = new $this->service_class_name();

            $params = $this->getParams() ?? [];
            $response = new JsonResponse(
                $this->controller->{$method}(...$params)
            );
            $response->send();
        } catch (\Exception $e) {
            $response = new JsonResponse(
                [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                [],
                StatusCode::INTERNAL_SERVER_ERROR,
            );
            $response->send();
        }
    }
}
