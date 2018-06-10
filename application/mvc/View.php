<?php
namespace mvc;

abstract class View {

  protected $model;
  
  public function render() {
    $CONTENT = $this->content();
    require_once __DIR__ . "/../view/layout.php";
  }
  
  protected function viewName() {
    $classNameWhole = get_class($this);
    
    $lastPosNamespace = strrpos($classNameWhole, '\\');
    $className = $lastPosNamespace === FALSE ? $classNameWhole : substr($classNameWhole, $lastPosNamespace + 1);
    
    return strtolower(substr($className, 0, strlen($className) - /*View*/4));
  }
  
  protected function content() {
    ob_start();
    
    $viewName = $this->viewName();
    require_once __DIR__ . "/../view/{$viewName}.php";
    
    $out = ob_get_contents();
    ob_end_clean();
    
    return $out;
  }
  
  function slugify($s){
     return strtolower(preg_replace('/-+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '---', $s)));
  }
  
  public function __get($key) {
    if (is_array($this->model)) {
      if (isset($this->model[$key])) {
        return $this->model[$key];
      }
    } else {                       
      if (isset($this->model->$key)) {
        return $this->model->$key;
      }
    }                   
    return "__{$key}__";    
  }
}