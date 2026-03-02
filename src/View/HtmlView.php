<?php

namespace Dysback\Ogo\View;

use Dysback\Ogo\App;

abstract class HtmlView extends BaseView
{
    private string $master_template = '';
    private string $title = '';
    private array $styles = [];
    private array $scripts = [];
    public function render(): void
    {
        $pieces = explode('\\', static::class);
        $template = $pieces[count($pieces) - 2] . '/' . $pieces[count($pieces) - 1];
        $views_folder = App::getInstance()->config->get('VIEWS_FOLDER');
        if (file_exists($views_folder . $template . '.tmpl.html')) {
            require $views_folder . $template . '.tmpl.html';
        }
    }

    public function getMasterTemplate(): string
    {
        return $this->master_template;
    }

    public function setMasterTemplate(string $master_template): void
    {
        $this->master_template = $master_template;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getStyles(): array
    {
        return $this->styles;
    }
    public function setStyles(array $styles): void
    {
        $this->styles = $styles;
    }

    public function getScripts(): array
    {
        return $this->scripts;
    }

    public function setScripts(array $scripts): void
    {
        $this->scripts = $scripts;
    }
}
