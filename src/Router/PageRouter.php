<?php

namespace Dysback\Ogo\Router;

use Dysback\Ogo\App;
use Dysback\Ogo\Logger\LogLevel;

class PageRouter extends BaseRouter
{
    protected $view;
    protected $view_class_name;
    protected $operation;
    protected $params = [];

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->path_pieces = explode('/', $path);
        if (count($this->path_pieces) < 3) {
            http_response_code(404);
            die("Path is too short: " . $this->path);
        }
        $this->module = $this->path_pieces[1];
        $this->view = $this->path_pieces[2];
        $this->operation = $this->path_pieces[3] ?? '';
        $this->params = array_slice($this->path_pieces, 4);

        App::getInstance()->Logger->log("Path: " . $this->path, LogLevel::DEBUG, 'router');
        App::getInstance()->Logger->log("Path Pieces: " . implode(', ', $this->path_pieces), LogLevel::DEBUG, 'router');
        App::getInstance()->Logger->log("Module: " . $this->module, LogLevel::DEBUG, 'router');
        App::getInstance()->Logger->log("View: " . $this->view, LogLevel::DEBUG, 'router');
        App::getInstance()->Logger->log("Params: " . implode(', ', $this->params), LogLevel::DEBUG, 'router');
        App::getInstance()->Logger->log("Route Type: " . $this->route_type, LogLevel::DEBUG, 'router');
    }



    public function route(): void
    {
        $this->view_class_name = "Mct\Live\View\\{$this->module}\\{$this->view}";
        if (!class_exists($this->view_class_name)) {
            http_response_code(404);
            die("Page not found: " . $this->path);
        }
        $this->view = new $this->view_class_name($this->getParams() ?? []);
        if ($this->operation) {
            $this->view->{$this->operation}(... $this->params);
        }
        App::getInstance()->$view = $this->view;
        require BASE_PATH . 'src/views/templates/master-templates/' . $this->view->getMasterTemplate();
    }
}
