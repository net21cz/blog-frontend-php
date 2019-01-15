<?php
namespace blog;

require_once __DIR__ . '/../domain/BlogRepo.php';

require_once __DIR__ . '/../domain/Category.php';
require_once __DIR__ . '/../domain/Author.php';
require_once __DIR__ . '/../domain/Option.php';

class BlogRepoREST implements BlogRepo {

  private $endpoint;
  
  private $jsonData;
  
  public function __construct($endpoint){                                           
    $this->endpoint = $endpoint;
    $this->load();
  } 
  
  private function load() {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->endpoint . '?secret=' . BACKEND_ACCESS_KEY);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    
    $response = curl_exec($curl); 
    //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
       
    $this->jsonData = json_decode($response);
  }

  public function categories() {
    $categories = array();  
           
    foreach ($this->jsonData->categories as $c) {
      $category = new Category();
      
      $category->id = (int)$c->id;
      $category->name = $c->name;
      
      array_push($categories, $category);
    }
         
    return $categories;
  }
  
  public function authors() {
    $authors = array();  
           
    foreach ($this->jsonData->authors as $a) {
      $author = new Author();
      
      $author->id = (int)$a->id;
      $author->name = $a->name;
      $author->email = $a->email;
      
      array_push($authors, $author);
    }
         
    return $authors;
  }
  
  public function options() { 
    $options = array();  
           
    $blogTitle = new Option();      
    $blogTitle->name = 'blogTitle';
    $blogTitle->value = $this->jsonData->title;
    
    $options[$blogTitle->name] = $blogTitle;
    
    $blogDescription = new Option();      
    $blogDescription->name = 'blogDescription';
    $blogDescription->value = $this->jsonData->description;
    
    $options[$blogDescription->name] = $blogDescription;
    
    return $options;
  }
}