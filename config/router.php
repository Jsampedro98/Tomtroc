<?php

/**
 * Classe Router - Gestion du routing de l'application
 */
class Router
{
    private array $routes = [];

    /**
     * Ajouter une route GET
     *
     * @param string $path Chemin de la route
     * @param string $controller Nom du contrôleur
     * @param string $method Nom de la méthode
     */
    public function get(string $path, string $controller, string $method): void
    {
        $this->addRoute('GET', $path, $controller, $method);
    }

    /**
     * Ajouter une route POST
     *
     * @param string $path Chemin de la route
     * @param string $controller Nom du contrôleur
     * @param string $method Nom de la méthode
     */
    public function post(string $path, string $controller, string $method): void
    {
        $this->addRoute('POST', $path, $controller, $method);
    }

    /**
     * Ajouter une route
     *
     * @param string $httpMethod Méthode HTTP
     * @param string $path Chemin de la route
     * @param string $controller Nom du contrôleur
     * @param string $method Nom de la méthode
     */
    private function addRoute(string $httpMethod, string $path, string $controller, string $method): void
    {
        $this->routes[] = [
            'http_method' => $httpMethod,
            'path' => $path,
            'controller' => $controller,
            'method' => $method
        ];
    }

    /**
     * Exécuter le routing
     */
    public function run(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Retirer le préfixe /tomtroc/public de l'URI si présent
        $basePath = '/tomtroc/public';
        if (strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }

        // Si l'URI est vide, on met /
        if (empty($requestUri) || $requestUri === '') {
            $requestUri = '/';
        }

        foreach ($this->routes as $route) {
            // Convertir le pattern en regex pour gérer les paramètres
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['http_method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                // Extraire les paramètres
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Instancier le contrôleur
                $controllerClass = $route['controller'];
                $controller = new $controllerClass();

                // Appeler la méthode
                $method = $route['method'];
                $controller->$method(...array_values($params));
                return;
            }
        }

        // Aucune route trouvée - 404
        http_response_code(404);
        require_once VIEWS_PATH . '/errors/404.php';
    }
}
