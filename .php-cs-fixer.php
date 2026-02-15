<?php


declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;


$finder = (new Finder())
    ->in(__DIR__)
;

return (new Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PER-CS' => true,
        //'@PSR12' => true,
        //'@PER-CS3.0' => true,
        //'@PHP82Migration' => true,
        //'@PhpCsFixer' => true,
        //'phpdoc_to_comment' => false,
    ])
    ->setFinder($finder)
;



