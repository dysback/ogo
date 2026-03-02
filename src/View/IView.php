<?php

namespace Dysback\Ogo\View;

interface IView
{
    public function render(): void;

    /*
        public function getTemplate(): string;
        public function setTemplate(string $template): void;
        public function setData(array $data): void;
        public function getData(): array;
        public function setLayout(string $layout): void;
        public function getLayout(): string;
        public function setHeader(string $header): void;
        public function getHeader(): string;
        public function setFooter(string $footer): void;
        public function getFooter(): string;
        */
}
