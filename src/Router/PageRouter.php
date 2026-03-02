<?php

namespace Dysback\Ogo\Router;

use Dysback\Ogo\App;
use Dysback\Ogo\Logger\LogLevel;
use Dysback\Ogo\Response\JsonResponse;
use Dysback\Ogo\Response\StatusCode;
use Dysback\Ogo\View\HtmlView;
use Dysback\Ogo\View\GeneralView;
use Dysback\Ogo\View\MustacheView;
use Dysback\Ogo\View\IView;

/**
 * Page router class. Renders a view template and returns the response.
 * @package Dysback\Ogo\Router
 */
class PageRouter extends BaseRouter
{
    public private(set) IView $view;
    protected string $view_class_name;
    protected string $operation;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->namespace = $app->config->get('APP_NAMESPACE');
    }

    public function route(string $path): void
    {
        $this->path = $path;
        $this->path_pieces = explode('/', $path);
        $module = $this->path_pieces[1];
        $view_name = $this->path_pieces[2];
        $operation = $this->path_pieces[3] ?? '';
        $params = array_slice($this->path_pieces, 4);
        $logger = $this->app->logger;

        if (count($this->path_pieces) < 3) {
            $logger->log("Path is too short: " . $this->path, LogLevel::DEBUG, 'router');
            $response = new JsonResponse([
                'error' => 'Path is too short',
                'path' => $this->path,
                'path_pieces' => $this->path_pieces,
                'module' => $module,
                'view' => $view_name,
                'operation' => $operation,
                'params' => $params,
            ], [], StatusCode::NOT_FOUND);
        }
        $logger->log(
            [
                "Path: " . $this->path,
                "Path Pieces: " . implode(', ', $this->path_pieces),
                "Module: " . $module,
                "View: " . $view_name,
                "Params: " . implode(', ', $params),
                "Route Type: " . $this->route_type
            ],
            LogLevel::DEBUG,
            'router'
        );

        $this->view_class_name = "{$this->namespace}\\{$module}\\{$view_name}";
        if (!class_exists($this->view_class_name)) {
            http_response_code(404);
            die("Page not found: " . $this->path);
        }
        //$logger->log("View class name: " . $this->view_class_name, LogLevel::DEBUG, 'router');
        $this->view = new $this->view_class_name();
        //$this->view->operation = $operation;
        $this->view->{$operation}(...$params);

        if (is_subclass_of($this->view, HtmlView::class)) {
            $this->view->render();

            $pieces = explode('\\', $this->view_class_name);
            $template = $pieces[count($pieces) - 2] . '/' . $pieces[count($pieces) - 1];
            $template_path = App::getInstance()->config->get('VIEWS_FOLDER') . $template . '.tmpl.php';    
            if (file_exists($template_path)) {
                require $template_path;
            } else {
                echo "Template not found: " . $template_path . print_r($pieces, true);
            }
        } elseif (is_subclass_of($this->view, GeneralView::class)) {
            $this->view->render();
        } elseif (is_subclass_of($this->view, MustacheView::class)) {
            $this->view->render();
        }
    }
}



