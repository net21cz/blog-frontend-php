<?php
namespace blog;

require_once __DIR__ . '/mvc/View.php';

class SitemapView extends \mvc\View {

  public function __construct($model) {
    $this->model = $model;
  }
  
  public function render() {
    $CONTENT = $this->content();
    require_once __DIR__ . "/view/xml.php";
  }
  
  protected function viewName() {
    return 'sitemap';
  }
}