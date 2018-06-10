<?php
namespace blog\articles;

require_once __DIR__ . '/mvc/View.php';

class ArticleView extends \mvc\View {

  public function __construct($model) {
    $this->model = $model;
  }
}