<?php
namespace blog;

require_once __DIR__ . '/BlogController.php';

require_once __DIR__ . '/../domain/BlogRepo.php';
require_once __DIR__ . '/../domain/ArticleRepo.php';

require_once __DIR__ . '/dto/BlogInfoDTO.php';
require_once __DIR__ . '/dto/ArticleItemDTO.php';

class IndexController extends BlogController {

  private $articleRepo;

  public function __construct(BlogRepo $blogRepo, articles\ArticleRepo $articleRepo){                                           
    parent::__construct($blogRepo);
    $this->articleRepo = $articleRepo;
  }
  
  public function index($params) {
    $blogInfo = $this->loadBlogInfo();
    
    $articles = $this->loadArticles((int)$params['category'], (int)$params['author'], (int)$params['page']);    
    
    return array(
      'blog' => $blogInfo,
      'articles' => $articles
    );
  }
  
  private function loadArticles($categoryId, $authorId, $page) {
    $articles = $this->articleRepo->fetchAll($categoryId, $authorId, $page);
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
      'categoryId' => $categoryId,
      'authorId' => $authorId,
      'page' => $page,
      'next' => $articles['next'],
      'previous' => $articles['previous'],
    );
  }
}