<?php
namespace blog;

class SitemapEntryDTO {

  public $loc;
  public $lastmod;

  public function __construct($loc, $lastmod) {
    $this->loc = $loc;                                           
    $this->lastmod = $lastmod;
  }
}