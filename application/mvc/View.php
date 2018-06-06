<?php
namespace mvc;

abstract class View {

  protected $model;

  abstract protected function content();
  
  public function render() {
    $CONTENT = $this->content();
    require_once __DIR__ . '/../view/layout.php';
  }
  
  public function __get($key) {
    return isset($this->model->$key) ? $this->model->$key : "__{$key}__";
  }
}