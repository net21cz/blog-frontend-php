<?php
namespace blog;

require_once __DIR__ . '/../domain/CategoryRepo.php';
require_once __DIR__ . '/../domain/AuthorRepo.php';
require_once __DIR__ . '/../domain/OptionsRepo.php';
require_once __DIR__ . '/dto/BlogInfoDTO.php';

class IndexController {

  private $categoryRepo;
  private $authorRepo;
  private $optionsRepo;

  public function __construct(CategoryRepo $categoryRepo, AuthorRepo $authorRepo, OptionsRepo $optionsRepo){                                           
    $this->categoryRepo = $categoryRepo;
    $this->authorRepo = $authorRepo;
    $this->optionsRepo = $optionsRepo;
  }
  
  public function index() {
    $categories = $this->categoryRepo->fetchAll();
    $categoriesDto = array();
        
    foreach ($categories as $c) {
      $categoriesDto[] = new CategoryDTO(
        $c->id,
        $c->name
      );
    }
    
    $authors = $this->authorRepo->fetchAll();
    $authorsDto = array();
        
    foreach ($authors as $a) {
      $authorsDto[] = new AuthorDTO(
        $a->id,
        $a->name,
        $a->email
      );
    }
    
    $options = $this->optionsRepo->fetchAll(array('blogTitle', 'blogDescription'));
        
    return new BlogInfoDTO(
      $options['blogTitle']->value,
      $options['blogDescription']->value,
      $categoriesDto,
      $authorsDto
    );
  }
  
  private function getIfSet($params, $var, $def = null) {
    return isset($params[$var]) ? $params[$var] : $def;
  }
}