<?php
namespace blog\articles;

require_once __DIR__ . '/../domain/ArticleRepo.php';
require_once __DIR__ . '/dto/ArticleDetailDTO.php';
require_once __DIR__ . '/dto/ArticleItemDTO.php';
require_once __DIR__ . '/dto/CategoryDTO.php';
require_once __DIR__ . '/dto/AuthorDTO.php';

class ArticleController {

  private $repo;

  public function __construct(ArticleRepo $repo){                                           
    $this->repo = $repo;
  }

  public function detailRequest($id) {
    $article = $this->repo->fetchOne((int)$id);
    
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
  
  public function listRequest($params) {
    $limit = 10;
    
    $categoryId = $this->getIfSet($params, 'categoryId');   
    $authorId = $this->getIfSet($params, 'authorId');   
    $page = $this->getIfSet($params, 'page', 0);
    
    $articles = $this->repo->fetchAll((int)$categoryId, (int)$authorId, $page * $limit, $limit);
    
    $count = $this->repo->count((int)$categoryId, (int)$authorId);
    
    $articlesDto = array(
      'count' => $count,
      'page' => $page,
      'limit' => $limit,
      'data' => array()
    );

    foreach ($articles as $article) {
      $articlesDto['data'][] = new ArticleItemDTO(
        $article->id,
        $article->title,
        $article->summary,
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
    
    return $articlesDto;
  }
  
  private function getIfSet($params, $var, $def = null) {
    return isset($params[$var]) ? $params[$var] : $def;
  }
}