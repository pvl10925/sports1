<?php
class Router
{
    public static function dispatch(array $routes, string $defaultController, string $defaultAction)
    {
        $controllerKey = $_GET['c'] ?? $defaultController;
        $actionName    = $_GET['a'] ?? $defaultAction;

        if (!isset($routes[$controllerKey])) {
            http_response_code(404);
            echo "Controller not found";
            exit;
        }

        $controllerFile = __DIR__ . '/../controllers/' . $routes[$controllerKey] . '.php';
        if (!file_exists($controllerFile)) {
            http_response_code(500);
            echo "Controller file missing";
            exit;
        }
        require_once $controllerFile;
        if (!class_exists(basename($routes[$controllerKey]))) {
            // class name = file name w/o path
            $className = pathinfo($routes[$controllerKey], PATHINFO_FILENAME);
        } else {
            $className = basename($routes[$controllerKey]);
        }

        $controller = new $className();

        if (!method_exists($controller, $actionName)) {
            http_response_code(404);
            echo "Action not found";
            exit;
        }

        $controller->$actionName();
    }
}
