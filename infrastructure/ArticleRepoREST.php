<?php
namespace blog\articles;

require_once __DIR__ . '/../domain/Article.php';
require_once __DIR__ . '/../domain/ArticleRepo.php';

class ArticleRepoREST implements ArticleRepo {

  private $endpoint;
  
  public function __construct($endpoint){                                           
    $this->endpoint = $endpoint;
  } 
    
  public function fetchOne($articleId) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->endpoint . '/'. (int)$articleId . '?secret=' . BACKEND_ACCESS_KEY);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    
    $response = curl_exec($curl); 
    //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
       
    $jsonData = json_decode($response);
    
    $a = $jsonData->data;       
      
    $article = new Article();
    
    $article->id = (int)$a->id;
    $article->title = $a->title;
    $article->summary = $a->summary;
    $article->body = $a->body;
    $article->timestamp = $a->createdAt;
    
    $article->category = new ArticleCategory();
    $article->category->id = (int)$a->category->id;
    $article->category->name = $a->category->name;
    
    $article->author = new ArticleAuthor();
    $article->author->id = (int)$a->author->id;
    $article->author->name = $a->author->name;
    $article->author->email = $a->author->email;
         
    return $article;
  }
  
  function fetchAll($categoryId = null, $authorId = null, $page = 0) {
    $params = '';
    if ($categoryId) {
      $params .= "categoryId={$categoryId}&";
    }
    if ($authorId) {
      $params .= "authorId={$authorId}&";
    }
    if ($page) {
      $params .= "page={$page}&";
    }
    
    $params .= 'secret=' . BACKEND_ACCESS_KEY;
     
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->endpoint . '?'. trim($params, '&'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    
    $response = curl_exec($curl); 
    //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
       
    $jsonData = json_decode($response);
    
    $articles = array();  
           
    foreach ($jsonData->articles as $a) {
      $a = $a->data;
      $article = new Article();
      
      $article->id = (int)$a->id;
      $article->title = $a->title;
      $article->summary = $a->summary;
      $article->timestamp = $a->createdAt;
      
      $article->category = new ArticleCategory();
      $article->category->id = (int)$a->category->id;
      $article->category->name = $a->category->name;
      
      $article->author = new ArticleAuthor();
      $article->author->id = (int)$a->author->id;
      $article->author->name = $a->author->name;
      $article->author->email = $a->author->email;
      
      array_push($articles, $article);
    }
         
    return array(
      'items' => $articles,
      'next' => $this->containsRel('next', $jsonData->links),
      'previous' => $this->containsRel('previous', $jsonData->links) 
    );
  }
  
  private function containsRel($rel, $data) {
    if (!empty($data)) {
      foreach ($data as $item) {
        if ($item->rel === $rel) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }
}