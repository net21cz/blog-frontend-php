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
    $articles = $this->articleRepo->fetchAll(); 
    $sitemapEntriesDto = array(); 
        
    foreach ($articles['items'] as $a) {
      $sitemapEntriesDto[] = new SitemapEntryDTO(
        slugify($a->title . '-' . $a->id),
        $a->timestamp
      );
    }
    
    return array(
      'entries' => $sitemapEntriesDto
    );
  }
}