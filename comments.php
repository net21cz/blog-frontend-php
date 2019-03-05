<?php
header("Access-Control-Allow-Origin: *");    // TODO http://blog.net21.cz
header("Access-Control-Allow-Methods: GET,POST");

require_once __DIR__ . '/config/app.config.php';
require_once __DIR__ . '/config/services.config.php';

switch ($_SERVER['REQUEST_METHOD']) {  
  case 'GET':
    $articleId = parseArticleIdFromPath($_SERVER['REQUEST_URI']);
    if (!empty($articleId)) {
      header("Content-Type: application/json; charset=UTF-8");
      
      $commentId = parseCommentIdFromPath($_SERVER['REQUEST_URI']);
      if (!empty($commentId)) {
        answersRequest($articleId, $commentId, $_REQUEST['page']);
      } else {
        commentsRequest($articleId, $_REQUEST['page']);
      }
      
    } else {
      header("Content-Type: text/plain; charset=UTF-8");
      http_response_code(400);
      echo 'Article ID must be set!';
    }    
    break;                           
  
  case 'OPTIONS':
    header('Allow: GET POST OPTIONS');
    break;
          
  default:
    http_response_code(405);
    header('Allow: GET POST OPTIONS');
}

function commentsRequest($articleId, $page = null) {
    echo getRequest((int)$articleId . '/comments' . (!empty($page) ? "?page={$page}" : ''));
}

function answersRequest($articleId, $commentId, $page = null) {
    echo getRequest((int)$articleId . '/comments/' . (int)$commentId . (!empty($page) ? "?page={$page}" : ''));
}

function getRequest($href) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, ENDPOINT_ARTICLES . '/'. $href);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Key: ' . BACKEND_ACCESS_KEY)); 
    
    $response = curl_exec($curl); 
    //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    $json = json_decode($response);
    
    if (!empty($json->href)) {
      $json->href = str_replace('/api/', '/', $json->href);
    }
    if (!empty($json->next)) {
      $json->next = str_replace('/api/', '/', $json->next);
    }
    
    if (!empty($json->comments)) {      
      foreach ($json->comments as $c) {
        if (!empty($c->next)) {
          $c->next = str_replace('/api/', '/', $c->next);
        }
      }
    }
    if (!empty($json->answers)) {      
      foreach ($json->answers as $a) {
        if (!empty($a->next)) {
          $a->next = str_replace('/api/', '/', $a->next);
        }
      }
    }
       
    return json_encode($json);
}

function parseArticleIdFromPath($path) {
  $path = !empty($path) && $path[strlen($path) - 1] == '/' ? substr($path, 0, strlen($path) - 1) : $path;
  if (empty($path)) {
    return array();
  }
  $queryPos = strpos($path, '?');
  if ($queryPos !== FALSE) {
    $path = substr($path, 0, $queryPos);
  }
  $parts = explode('/', $path[0] == '/' ? substr($path, 1) : $path);
  $firstParamIndex = array_search('articles', $parts) + 1;
  return $firstParamIndex < sizeof($parts) ? $parts[$firstParamIndex] : null;
}

function parseCommentIdFromPath($path) {
  $path = !empty($path) && $path[strlen($path) - 1] == '/' ? substr($path, 0, strlen($path) - 1) : $path;
  if (empty($path)) {
    return array();
  }
  $queryPos = strpos($path, '?');
  if ($queryPos !== FALSE) {
    $path = substr($path, 0, $queryPos);
  }
  $parts = explode('/', $path[0] == '/' ? substr($path, 1) : $path);  
  $firstParamIndex = array_search('articles', $parts) + 1;
  return $firstParamIndex + 2 < sizeof($parts) ? $parts[$firstParamIndex + 2] : null;
}