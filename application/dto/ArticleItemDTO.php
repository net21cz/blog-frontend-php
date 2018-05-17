<?php
namespace blog\articles;

class ArticleItemDTO {

  public $id;
  public $title;
  public $summary;
  public $createdAt;
  public $category;
  public $author;

  public function __construct($id, $title, $summary, $createdAt, $category, $author) {
    $this->id = $id;
    $this->title = $title;
    $this->summary = $summary;    
    $this->createdAt = $createdAt;
    $this->category = $category;
    $this->author = $author;
  }
}