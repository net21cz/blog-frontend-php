<?php
namespace blog\comments;

require_once __DIR__ . '/../domain/Comment.php';
require_once __DIR__ . '/../domain/CommentRepo.php';

class CommentRepoREST implements CommentRepo {

  private $endpoint;
  
  public function __construct($endpoint){                                           
    $this->endpoint = $endpoint;
  } 
  
  function fetchAll($articleId) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->endpoint . '?articleId='. $articleId);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Key: ' . BACKEND_ACCESS_KEY));
    
    $response = curl_exec($curl); 
    //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
       
    $jsonData = json_decode($response);
    
    $comments = array();  
           
    foreach ($jsonData->comments as $c) {
      $comment = new Comment();
      
      $comment->id = (int)$c->id;
      $comment->body = $c->body;
      $comment->createdAt = $c->createdAt;
      
      array_push($comments, $comment);
    }
         
    return array(
      'items' => $comments,
      'next' => $this->containsRel('next', $jsonData->links),
      'previous' => $this->containsRel('previous', $jsonData->links) 
    );
  }
  
  function add($articleId, $body) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_POST, true); 
    curl_setopt($curl, CURLOPT_POSTFIELDS, array('articleId' => $articleId, 'body' => $body));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Key: ' . BACKEND_ACCESS_KEY)); 
    
    $response = curl_exec($curl); 
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
        
    $comment = new Comment();
      
    $comment->id = $status === 201 ? 1 : -1;
    $comment->body = $body;
    $comment->createdAt = time();
    
    return $comment;
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