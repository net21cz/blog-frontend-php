<?php
namespace blog;

require_once __DIR__ . '/BlogController.php';

require_once __DIR__ . '/../domain/BlogRepo.php';

require_once __DIR__ . '/dto/BlogInfoDTO.php';

class StaticController extends BlogController {

  public function __construct(BlogRepo $blogRepo){                                           
    parent::__construct($blogRepo);
  }
  
  public function index($params) {
    $blogInfo = $this->loadBlogInfo();
    
    return array(
      'content' => $params['content'], 
      'blog' => $blogInfo
    );
  }
}