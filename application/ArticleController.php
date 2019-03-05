<?php
namespace blog\articles;

require_once __DIR__ . '/BlogController.php';

require_once __DIR__ . '/../domain/BlogRepo.php';
require_once __DIR__ . '/../domain/ArticleRepo.php';

require_once __DIR__ . '/dto/BlogInfoDTO.php';
require_once __DIR__ . '/dto/ArticleDetailDTO.php';
require_once __DIR__ . '/dto/CategoryDTO.php';
require_once __DIR__ . '/dto/AuthorDTO.php';

@session_start();

class ArticleController extends \blog\BlogController {

  private $articleRepo;

  public function __construct(\blog\BlogRepo $blogRepo, ArticleRepo $articleRepo){
    parent::__construct($blogRepo);
    $this->articleRepo = $articleRepo;
  }
  
  public function index($params) {
    global $_SESSION;
    
    $blogInfo = $this->loadBlogInfo();
        
    $article = $this->loadArticle((int)$params['id']);
    
    if (!$article || !$article->title) {
      http_response_code(404);      
    }
    
    $blogInfo->title = $article->title . ' by ' . $article->author->name;
    
    return array(
      'blog' => $blogInfo,
      'article' => $article
    );
  }

  private function loadArticle($id) {
    $article = $this->articleRepo->fetchOne((int)$id);
    
    return new ArticleDetailDTO(
      $article->id,
      $article->title,
      $article->summary,
      $article->body,
      $article->timestamp,
      
      new CategoryDTO(
        $article->category->id,
        $article->category->name
      ),      
      
      new AuthorDTO(
        $article->author->id,
        $article->author->name,
        $article->author->email
      )
    );
  }
}