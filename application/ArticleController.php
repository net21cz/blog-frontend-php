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

@session_start();

class ArticleController extends \blog\BlogController {

  private $articleRepo;
  private $commentRepo;

  public function __construct(\blog\BlogRepo $blogRepo, ArticleRepo $articleRepo, \blog\comments\CommentRepo $commentRepo){
    parent::__construct($blogRepo);
    $this->articleRepo = $articleRepo;
    $this->commentRepo = $commentRepo;
  }
  
  public function index($params) {
    global $_SESSION;
    
    $blogInfo = $this->loadBlogInfo();
        
    $article = $this->loadArticle((int)$params['id']);
    
    if (!$article || !$article->title) {
      http_response_code(404);      
    }
    
    $blogInfo->title = $article->title;
    
    $model = array(
      'blog' => $blogInfo,
      'article' => $article
    );
    
    // add a new comment
    if (!empty($params['body']) && isset($params['captcha'])) {
      
      if ($params['captcha'] !== $_SESSION['security_code_blogcomments']) {
        $model['errors'] = array('captcha'); 
        $model['addingComment'] = new CommentDTO(0, $params['body'], time());
      
      } else {
        $newComment = $this->saveComment($article->id, $params['body']);        
        $model['addedComment'] = $newComment;
        $this->notifyNewCommentAdded($newComment->id, $newComment->body);
      }
    }
    
    return $model;
  }
  
  private function saveComment($articleId, $body) {
    $comment = $this->commentRepo->add((int)$articleId, htmlspecialchars($body));
    
    return new CommentDTO(
      $comment->id,
      $comment->body,
      $comment->createdAt
    );
  }
  
  private function notifyNewCommentAdded($id, $body) {
    mail(EMAIL_ADMIN, 'new comment added', "ID: {$id}\n{$body}");
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
    
    foreach ($comments['items'] as $c) {
      $commentsDto[] = new CommentDTO(
        $c->id,
        $c->body,
        $c->createdAt
      );
    }
    
    return $commentsDto;
  }
}