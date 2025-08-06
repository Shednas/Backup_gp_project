<?php
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/';
    $class = ltrim($class, '\\');
    $file = '';
    if ($lastNsPos = strrpos($class, '\\')) {
        $namespace = substr($class, 0, $lastNsPos);
        $className = substr($class, $lastNsPos + 1);
        $file = $base_dir . str_replace('\\', '/', $namespace) . '/' . $className . '.php';
    } else {
        $file = $base_dir . $class . '.php';
    }
    if (file_exists($file)) {
        require_once $file;
    }
});
?>