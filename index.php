<?php
/**
 * @param $path    array of the path parts
 * @param $params  array of parameter entries
 */
function route($path, $params) {
  // Implement your own routing rules
  // ...
  if (empty($path)) {
    return new Request('index', $params);
  }
  
  if ($path[0] == 'article' && !empty($path[1])) {
      
    $params['id'] = (int)$path[1];
    return new Request('article', $params);
  }
  
  if ($path[0] == 'author' && !empty($path[1])) {
      
    $params['authorId'] = (int)$path[1];
    return new Request('index', $params);
  }
  
  if ($path[0] == 'programming') {
      
    $params['categoryId'] = 1;
    return new Request('index', $params);
  }
  if ($path[0] == 'miscellaneous') {
      
    $params['categoryId'] = 2;
    return new Request('index', $params);
  }
  
  http_response_code(404);
}

// /////////////////////////////////////////////////////////////////////////////

header("Content-Type: text/html; charset=UTF-8");

require_once __DIR__ . '/config/services.config.php';

class Request {
  
  public $controller;
  public $params;
  
  public function __construct($controller = 'index', $params = array()) {                                           
    $this->controller = $controller;
    $this->params = $params;
  }
}     

class Dispatcher {

  public function dispatch($_path, $_params) {
    $path = $this->parsePath($_path);
    $params = $this->parseParams($_params);
    
    $request = route($path, $params);
    
    $controller = $this->buildController($request->controller);      
    $model = $controller->index($request->params);
    $view = new View($request->controller, $model);
    
    $view->show();
  }
  
  private function buildController($controllerName) {
    $controllerClassname = ucfirst($controllerName) . 'Controller';
    require_once "./application/{$controllerClassname}.php";
    
    switch ($controllerName) {
      case 'index':
        require_once "./infrastructure/BlogRepoREST.php";
        require_once "./infrastructure/ArticleRepoREST.php";
        
        $controllerClassname = "blog\\{$controllerClassname}";        
        return new $controllerClassname(new blog\BlogRepoREST(ENDPOINT_BLOG), new blog\articles\ArticleRepoREST(ENDPOINT_ARTICLES));    
    }
        
    throw new Exception("Unknown controller: {$controllerName}");    
  }
  
  private function parsePath($path) {
    $path = !empty($path) && $path[strlen($path) - 1] == '/' ? substr($path, 0, strlen($path) - 1) : $path;
    $path = substr($path, 4);// TODO remove
    if (empty($path)) {
      return array();
    }
    $queryPos = strpos($path, '?');
    if ($queryPos !== FALSE) {
      $path = substr($path, 0, $queryPos);
    }
    return explode('/', $path[0] == '/' ? substr($path, 1) : $path);
  }
  
  private function parseParams($params) {
    return $params;
  }
}

class View {

  private $viewName;
  private $model;
  
  public function __construct($viewName, $model = array()) {                                           
    $this->viewName = $viewName;
    $this->model = $model;
  }
  
  public function show() {
    $view = $this->buildView($this->viewName);
    $view->render();
  }
  
  private function buildView($viewName) {
    $viewClassname = ucfirst($viewName) . 'View';
    require_once "./application/{$viewClassname}.php";
    
    switch ($viewName) {
      case 'index':
        $viewClassname = "blog\\{$viewClassname}";
        return new $viewClassname($this->model);    
    }
        
    throw new Exception("Unknown view: {$viewName}");    
  }
}

$dispatcher = new Dispatcher();
$dispatcher->dispatch($_SERVER['REQUEST_URI'], $_REQUEST);