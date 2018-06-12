<?php
namespace blog;

require_once __DIR__ . '/mvc/View.php';

abstract class BlogView extends \mvc\View {

  public function __construct($model) {
    $this->model = $model;
  }
  
  protected function isActiveCaption($subUrl = null) {
    $url = $this->removeNonCaptions($_SERVER['REQUEST_URI']);
        
    if (empty($subUrl)) {
      if ($url === '/') {
        return TRUE;
      }
    } else {
      $pattern = "/(^\/({$subUrl}$)|(\?((.+)=(.+)(&|&amp;))?({$subUrl})($|(&|&amp;)(.+)=(.+))))/";
      return preg_match($pattern, $url);
    }
    return FALSE;
  }
  
  private function removeNonCaptions($url) {
    $captionParams = array('category');
    
    $queryPos = strpos($url, '?');
    
    if ($queryPos !== FALSE) {
      $query = substr($url, $queryPos + 1);
      $url = substr($url, 0, $queryPos);
      
      $params = preg_split('/(&|&amp;)/', $query);
      
      $paramsNew = array();
      
      foreach ($params as $p) {
        $paramKey = split('=', $p);
        $paramKey = $paramKey[0];
        
        if (in_array($paramKey, $captionParams)) {
          $paramsNew[] = $p;
        }
      }
      
      $query = join('&', $paramsNew);
      
      return $url . (!empty($query) ? '?'. $query : '');
    }
    
    return $url;
  }
}