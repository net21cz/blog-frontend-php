<?php
namespace blog;

require_once __DIR__ . '/mvc/View.php';

require_once __DIR__ . '/dto/BlogInfoDTO.php';

class IndexView extends \mvc\View {

  public function __construct(BlogInfoDTO $blogInfo){                                           
    $this->model = $blogInfo;
  }
  
  protected function content() {    
    return 'abc';
  }
}