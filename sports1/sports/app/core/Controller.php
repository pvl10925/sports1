<?php
class Controller 
{
    protected function loadModel(string $model)
    {
        $file = __DIR__ . '/../models/' . $model . '.php';
        if (file_exists($file)) {
            require_once $file;
            return new $model();
        }
        return null;
    }

    protected function render(string $view, array $params = [], string $layout = 'main')
    {
        // FIX: Thay thế extract($params)
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $$key = $value;
            }
        }

        $viewFile   = __DIR__ . '/../views/' . $view . '.php';
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';

        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "View not found: $viewFile";
        }
        $content = ob_get_clean();

        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            echo "Layout not found: $layoutFile";
        }
    }

    protected function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['account']) && is_array($_SESSION['account']);
    }

    protected function requireAdmin(): void
    {
        if (!$this->isLoggedIn() || ($_SESSION['account']['role'] ?? '') !== 'ADMIN') {
            $this->redirect('index.php?c=auth&a=login');
        }
    }
}