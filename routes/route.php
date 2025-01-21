<?php

class Router {
    private $routes = [];

    public function get($uri, $controllerAction) {
        $this->routes['GET'][$uri] = $controllerAction;
    }

    public function post($uri, $controllerAction) {
        $this->routes['POST'][$uri] = $controllerAction;
    }

    public function put($uri, $controllerAction) {
        $this->routes['PUT'][$uri] = $controllerAction;
    }

    public function delete($uri, $controllerAction) {
        $this->routes['DELETE'][$uri] = $controllerAction;
    }

    public function resolve() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        // Loại bỏ phần "public" trong URL nếu đang sử dụng Apache
        $uri = str_replace('/DungVuongStore/public', '', $uri);

        // Duyệt qua các route và kiểm tra khớp
        foreach ($this->routes[$method] as $route => $controllerAction) {
            // Sử dụng regular expression để kiểm tra các route động
            $routePattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
            if (preg_match("~^$routePattern$~", $uri, $matches)) {
                // Tách controller và action
                $controllerActionParts = explode('@', $controllerAction);
                $controller = $controllerActionParts[0];
                $action = $controllerActionParts[1];

                // Lấy tham số động từ URI và truyền cho controller
                $parameters = array_slice($matches, 1);
                
                // Khởi tạo controller và gọi action
                $controllerInstance = new $controller();
                call_user_func_array([$controllerInstance, $action], $parameters);
                return;
            }
        }

        // Nếu không tìm thấy route
        echo "404 Not Found ok e";
    }
}
?>