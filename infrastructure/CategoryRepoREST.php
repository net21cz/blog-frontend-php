<?php
namespace blog;

require_once __DIR__ . '/../domain/Category.php';
require_once __DIR__ . '/../domain/CategoryRepo.php';

class CategoryRepoREST implements CategoryRepo {

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
    
    $categories = array();  
           
    foreach ($json->categories as $c) {
      $category = new Category();
      
      $category->id = (int)$c->id;
      $category->name = $c->name;
      
      array_push($categories, $category);
    }
         
    return $categories;
  }
}