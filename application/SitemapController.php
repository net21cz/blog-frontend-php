<?php
namespace blog;

require_once __DIR__ . '/mvc/Controller.php';

require_once __DIR__ . '/../domain/ArticleRepo.php';

require_once __DIR__ . '/dto/SitemapEntryDTO.php';

require_once __DIR__ . "/util/slugify.php";

class SitemapController extends \mvc\Controller {

  private $articleRepo;

  public function __construct(articles\ArticleRepo $articleRepo){                                           
    $this->articleRepo = $articleRepo;
  }
  
  public function index($params) {
    $sitemapEntriesDto = array();
    $nextPage = 0;
    do {
      $articles = $this->articleRepo->fetchAll(null, null, $nextPage); 
                
      foreach ($articles['items'] as $a) {
        $sitemapEntriesDto[] = new SitemapEntryDTO(
          slugify($a->title . '-' . $a->id),
          $a->timestamp
        );
      }
      
      $nextPage = !empty($articles['next']) ? $nextPage + 1 : null;
      
    } while ($nextPage > 0);
    
    return array(
      'entries' => $sitemapEntriesDto
    );
  }
}