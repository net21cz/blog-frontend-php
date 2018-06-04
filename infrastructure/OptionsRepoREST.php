<?php
namespace blog;

require_once __DIR__ . '/../domain/Option.php';
require_once __DIR__ . '/../domain/OptionsRepo.php';

class OptionsRepoREST implements OptionsRepo {

  private $endpoint = 'http://blog.net21.cz/api/';
 
  function fetchAll($items) {   
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    
    $response = curl_exec($curl); 
    //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
       
    $json = json_decode($response);
    
    $options = array();  
           
    $blogTitle = new Option();      
    $blogTitle->name = 'blogTitle';
    $blogTitle->value = $json->title;
    
    $options[$blogTitle->name] = $blogTitle;
    
    $blogDescription = new Option();      
    $blogDescription->name = 'blogDescription';
    $blogDescription->value = $json->description;
    
    $options[$blogDescription->name] = $blogDescription;
    
    return $options;
  }
}