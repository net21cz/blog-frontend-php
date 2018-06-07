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
      $stmt->bindValue('id', (int)$articleId, PDO::PARAM_INT);        
      
      $article = null;  
             
      if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $article = new Article();
        
        $article->id = (int)$row['id'];
        $article->title = $row['title'];
        $article->summary = $row['summary'];
        $article->body = $row['text'];
        $article->timestamp = $row['timestamp'];
        
        $article->category = new ArticleCategory();
        $article->category->id = (int)$row['categoryId'];
        $article->category->name = $row['categoryName'];
        
        $article->author = new ArticleAuthor();
        $article->author->id = (int)$row['authorId'];
        $article->author->name = $row['authorName'];
        $article->author->email = $row['authorEmail'];
      }
           
      return $article;
  }
  
  function fetchAll($categoryId = null, $authorId = null, $start = 0) {
    $params = '';
    if ($categoryId) {
      $params .= "category={$categoryId}&";
    }
    if ($authorId) {
      $params .= "author={$authorId}&";
    }
    if ($start > 0) {
      $params .= "page={$categoryId}&";
    }
     
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
    foreach ($data as $item) {
      if ($item->rel === $rel) {
        return true;
      }
    }
    return false;
  }
}