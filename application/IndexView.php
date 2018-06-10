<?php
namespace blog;

require_once __DIR__ . '/mvc/View.php';

class IndexView extends \mvc\View {

  public function __construct($model) {
    $this->model = $model;
  }
  
  function hasNext() {
    return $this->model['articles']['next'];
  }
  
  function hasPrevious() {
    return $this->model['articles']['previous'];
  }
  
  function nextUrl() {
    return $this->model['articles']['next'] ? $this->pageUrl($this->model['articles']['page'] + 1) : $this->pageUrl();
  }
  
  function previousUrl() {
    return $this->model['articles']['previous'] ? $this->pageUrl($this->model['articles']['page'] - 1) : $this->pageUrl();
  }
  
  private function pageUrl($page = 0) {
    $params = array();
        
    if ($this->model['articles']['categoryId']) {
      $params[] = "category={$this->model['articles']['categoryId']}";
    }
    if ($this->model['articles']['authorId']) {
      $params[] = "author={$this->model['articles']['authorId']}";
    }
    if ($page) {
      $params[] = "page={$page}";
    }
    
    return '/' . (!empty($params) ? '?' . join('&', $params) : '');
  }
}