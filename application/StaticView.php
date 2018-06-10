<?php
namespace blog;

require_once __DIR__ . '/mvc/View.php';

class StaticView extends \mvc\View {

  public function __construct($model) {
    $this->model = $model;
  }
  
  protected function viewName() {
    return $this->model['content'];
  }
}