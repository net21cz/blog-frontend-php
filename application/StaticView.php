<?php
namespace blog;

require_once __DIR__ . '/BlogView.php';

class StaticView extends BlogView {

  public function __construct($model) {
    parent::__construct($model);
  }
  
  protected function viewName() {
    return $this->model['content'];
  }
  
  protected function isActiveCaption($subUrl = null) {  
    return !$subUrl;
  }
}