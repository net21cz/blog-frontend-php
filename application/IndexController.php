<?php
namespace blog;

require_once __DIR__ . '/mvc/Controller.php';

require_once __DIR__ . '/../domain/BlogRepo.php';

require_once __DIR__ . '/dto/BlogInfoDTO.php';

class IndexController extends \mvc\Controller {

  private $blogRepo;

  public function __construct(BlogRepo $blogRepo){                                           
    $this->blogRepo = $blogRepo;
  }
  
  public function index() {  
    $categories = $this->blogRepo->categories();
    $categoriesDto = array();
        
    foreach ($categories as $c) {
      $categoriesDto[] = new CategoryDTO(
        $c->id,
        $c->name
      );
    }
    
    $authors = $this->blogRepo->authors();
    $authorsDto = array();
        
    foreach ($authors as $a) {
      $authorsDto[] = new AuthorDTO(
        $a->id,
        $a->name,
        $a->email
      );
    }
    
    $options = $this->blogRepo->options();
        
    return new BlogInfoDTO(
      $options['blogTitle']->value,
      $options['blogDescription']->value,
      $categoriesDto,
      $authorsDto
    );
  }
}