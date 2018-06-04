<?php
namespace blog;

require_once __DIR__ . '/../domain/Author.php';
require_once __DIR__ . '/../domain/AuthorRepo.php';

class AuthorRepoREST implements AuthorRepo {

  private $endpoint = 'http://blog.net21.cz/api/';
   
  function fetchAll() {   
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    
    $response = curl_exec($curl); 
    //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
       
    $json = json_decode($response);
    
    $authors = array();  
           
    foreach ($json->authors as $a) {
      $author = new Author();
      
      $author->id = (int)$a->id;
      $author->name = $a->name;
      $author->email = $a->email;
      
      array_push($authors, $author);
    }
         
    return $authors;
  }
}