<?php

namespace Dysback\Ogo\View;

abstract class BaseView implements IView
{
    abstract public function render(): void;
}
