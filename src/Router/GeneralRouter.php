<?php

namespace Dysback\Ogo\Router;

use Dysback\Ogo\App;
use Dysback\Ogo\Response\StatusCode;
use Dysback\Ogo\Response\HtmlResponse;

/**
 * General router class. Lets user method return the response directly.
 * @package Dysback\Ogo\Router
 */
class GeneralRouter extends BaseRouter
{
    private string $service_class_name;
    private RequestType $request_type;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->namespace = $app->config->get('API_NAMESPACE');
    }

    /**
     * Route the request to the appropriate service and method.
     * @param string $path The path of the request.
     * @return void
     */
    public function route(string $path): void
    {
        try {
            $this->path = $path;
            $this->path_pieces = explode('/', $path);
            $module = $this->path_pieces[1];
            $service = $this->path_pieces[2];
            $method = $this->path_pieces[3];

            $this->service_class_name = "{$this->namespace}\\{$module}\\{$service}";
            $controller = new $this->service_class_name();

            $params = $this->getParams() ?? [];
            $controller->{$method}(...$params);
        } catch (\Exception $e) {
            $response = new HtmlResponse(
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
