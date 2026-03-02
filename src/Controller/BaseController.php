<?php

namespace Dysback\Ogo\Controller;

/**
 * Base class for all controllers. UNUSED FOR NOW.
 * @package Dysback\Ogo\Controller
 */
abstract class BaseController implements IController
{
    //public abstract function __construct(array $data = []);

    public function __construct(array $data = [])
    {
        // $this->data = $data;
    }
    //public abstract function processRequest(array $data = []): array;
}
