<?php
/**                                       
 * @param $method  method of the request
 * @param $path    array of the path parts
 * @param $params  array of parameter entries
 */
function route($method, $path, $params) {
  // Implement your own routing rules
  // ...
  if (!empty($path)) {
    
    if ($path[0] === 'privacypolicy') {
      return new Request('static', 'index', array('content' => 'privacypolicy'));
    }
    
    if (preg_match('/[a-z0-9-]+-[0-9]+/', $path[0]) && !isset($path[1])) {        
      $params['id'] = (int)substr($path[0], strrpos($path[0], '-') + 1);        
      return new Request('article', 'index', $params);
    }    
  }
  
  http_response_code(404);
    
  return new Request('index', 'index', $params);
}

// /////////////////////////////////////////////////////////////////////////////

header("Content-Type: text/html; charset=UTF-8");

require_once __DIR__ . '/config/app.config.php';
require_once __DIR__ . '/config/services.config.php';

class Request {
  
  public $controller;
  public $action;
  public $params;
  
  public function __construct($controller = 'index', $action = 'index', $params = array()) {                                           
    $this->controller = $controller;
    $this->action = $action;
    $this->params = $params;
  }
}     

class Dispatcher {

  public function dispatch($method, $path, $params) {
    $path_ = $this->parsePath($path);
    $params_ = $this->parseParams($params);
    
    $request = route($method, $path_, $params_);
    
    $controller = $this->buildController($request->controller);
    $action = $request->action;      
    
    $model = $controller->$action($request->params);
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
        return new $controllerClassname(new blog\BlogRepoREST(ENDPOINT_BLOG), 
               new blog\articles\ArticleRepoREST(ENDPOINT_ARTICLES));
            
      case 'article':
        require_once "./infrastructure/BlogRepoREST.php";
        require_once "./infrastructure/ArticleRepoREST.php";
        require_once "./infrastructure/CommentRepoREST.php";
        
        $controllerClassname = "blog\\articles\\{$controllerClassname}";        
        return new $controllerClassname(new blog\BlogRepoREST(ENDPOINT_BLOG), 
               new blog\articles\ArticleRepoREST(ENDPOINT_ARTICLES),
               new blog\comments\CommentRepoREST(ENDPOINT_COMMENTS));
        
      case 'static':
        require_once "./infrastructure/BlogRepoREST.php";
        
        $controllerClassname = "blog\\{$controllerClassname}";        
        return new $controllerClassname(new blog\BlogRepoREST(ENDPOINT_BLOG));
    }
        
    throw new Exception("Unknown controller: {$controllerName}");    
  }
  
  private function parsePath($path) {
    $path = !empty($path) && $path[strlen($path) - 1] == '/' ? substr($path, 0, strlen($path) - 1) : $path;
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
      
      case 'article':
        $viewClassname = "blog\\articles\\{$viewClassname}";
        return new $viewClassname($this->model);
        
      case 'static':
        $viewClassname = "blog\\{$viewClassname}";
        return new $viewClassname($this->model);
    }
        
    throw new Exception("Unknown view: {$viewName}");    
  }
}

$dispatcher = new Dispatcher();
$dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_REQUEST);