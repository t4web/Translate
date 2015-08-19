<?php
// This is global bootstrap for autoloading

if (file_exists('init_autoloader.php')) {
    require 'init_autoloader.php';
    Zend\Mvc\Application::init(require 'config/application.config.php');
} else {
    throw new \RuntimeException('cannot load vendor autoload');
}