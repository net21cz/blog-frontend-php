<?php
namespace blog\articles;

require_once __DIR__ . '/BlogController.php';

require_once __DIR__ . '/../domain/BlogRepo.php';
require_once __DIR__ . '/../domain/ArticleRepo.php';
require_once __DIR__ . '/../domain/CommentRepo.php';

require_once __DIR__ . '/dto/BlogInfoDTO.php';
require_once __DIR__ . '/dto/ArticleDetailDTO.php';
require_once __DIR__ . '/dto/CategoryDTO.php';
require_once __DIR__ . '/dto/AuthorDTO.php';
require_once __DIR__ . '/dto/CommentDTO.php';

class ArticleController extends \blog\BlogController {

  private $articleRepo;
  private $commentRepo;

  public function __construct(\blog\BlogRepo $blogRepo, ArticleRepo $articleRepo, CommentRepo $commentRepo){
    parent::__construct($blogRepo);
    $this->articleRepo = $articleRepo;
    $this->commentRepo = $commentRepo;
  }
  
  public function index($params) {
    $blogInfo = $this->loadBlogInfo();
        
    $article = $this->loadArticle((int)$params['id']);
    
    if (!$article || !$article->title) {
      http_response_code(404);      
    }
    
    $blogInfo->title = $article->title;
    
    return array(
      'blog' => $blogInfo,
      'article' => $article
    );
  }
  
  public function saveComment($articleId, $body) {
    $comment = $this->commentRepo->add((int)$articleId, htmlspecialchars($body));
    
    return new CategoryDTO(
      $comment->id,
      $comment->body,
      $comment->createdAt
    );
  }

  private function loadArticle($id) {
    $article = $this->articleRepo->fetchOne((int)$id);
    
    $commentsDto = $this->loadComments($id);
    
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
      ),
      
      $commentsDto
    );
  }
  
  private function loadComments($articleId) {
    $comments = $this->commentRepo->fetchAll((int)$articleId);
    
    $commentsDto = array();
    
    foreach ($comments as $c) {
      $commentsDto[] = new CategoryDTO(
        $c->id,
        $c->body,
        $c->createdAt
      )
    }
    
    return $commentsDto;
  }
}