<?php
namespace blog;

require_once __DIR__ . '/mvc/Controller.php';

require_once __DIR__ . '/../domain/BlogRepo.php';
require_once __DIR__ . '/../domain/ArticleRepo.php';

require_once __DIR__ . '/dto/BlogInfoDTO.php';
require_once __DIR__ . '/dto/ArticleItemDTO.php';

class IndexController extends \mvc\Controller {

  private $blogRepo;
  private $articleRepo;

  public function __construct(BlogRepo $blogRepo, articles\ArticleRepo $articleRepo){                                           
    $this->blogRepo = $blogRepo;
    $this->articleRepo = $articleRepo;
  }
  
  public function index($params) {  
    $blogInfo = $this->loadBlogInfo();
    
    $articles = $this->loadArticles((int)$params['page']);    
    
    return array(
      'blog' => $blogInfo,
      'articles' => $articles
    );
  }
  
  private function loadArticles($page) {
    $articles = $this->articleRepo->fetchAll(null, null, $page);
    $articlesDto = array();
    
    foreach ($articles['items'] as $a) {
      $articlesDto[] = new articles\ArticleItemDTO(
        $a->id,
        $a->title,
        $a->summary,
        $a->timestamp,
        
        new CategoryDTO(
          $a->category->id,
          $a->category->name
        ), 
                
        new AuthorDTO(
          $a->author->id,
          $a->author->name,
          $a->author->email
        )
      );
    }
    
    return array(
      'items' => $articlesDto,
      'page' => $page,
      'next' => $articles['next'],
      'previous' => $articles['previous'],
    );
  }
  
  private function loadBlogInfo() {
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