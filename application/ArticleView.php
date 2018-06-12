<?php
namespace blog\articles;

require_once __DIR__ . '/BlogView.php';

class ArticleView extends \blog\BlogView {

  public function __construct($model) {
    parent::__construct($model);
  }
  
  protected function isActiveCaption($subUrl = null) {
    $categoryId = $this->model['article']->category->id;
    $matchSubUrl = "category=$categoryId";
  
    return $subUrl === $matchSubUrl;
  }
}