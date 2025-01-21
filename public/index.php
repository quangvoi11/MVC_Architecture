<?php
header("Access-Control-Allow-Origin: *"); // Cho phép mọi domain
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Các phương thức HTTP cho phép
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Các header cho phép

require_once '../routes/route.php';
require_once '../app/controllers/ProductController.php';
require_once '../app/controllers/CartController.php';
require_once '../app/controllers/UserController.php';
require_once '../config/db.php';
require_once '../app/models/Product.php';
require_once '../app/models/Cart.php';
require_once '../app/models/User.php';


// Khởi tạo router
$router = new Router();

$router->get('/api/products', 'ProductController@apiGetAll');
$router->get('/api/products/{id}', 'ProductController@apiGetById');
$router->post('/api/products', 'ProductController@apiCreate');
$router->put('/api/products/{id}', 'ProductController@apiUpdate');
$router->delete('/api/products/{id}', 'ProductController@apiDelete');
$router->get('/api/products/search', 'ProductController@apiSearch');

$router->get('/api/cart', 'CartController@apiGetCart');
$router->post('/api/cart', 'CartController@apiAddToCart');
$router->put('/api/cart/{productId}', 'CartController@apiUpdateCart');
$router->delete('/api/cart/{productId}', 'CartController@apiRemoveFromCart');

$router->get('/api/users', 'UserController@apiGetAll'); 
$router->get('/api/users/{id}', 'UserController@apiGetById'); 
$router->put('/api/users/{id}', 'UserController@apiUpdate');  
$router->delete('/api/users/{id}', 'UserController@apiDelete'); 

$router->post('/api/register', 'UserController@apiRegister');
$router->post('/api/login', 'UserController@apiLogin');
$router->resolve();

