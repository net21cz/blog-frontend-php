<?php
namespace blog\articles;

class ArticleDetailDTO {

  public $id;
  public $title;
  public $summary;
  public $body;
  public $createdAt;
  public $category;
  public $author;

  public function __construct($id, $title, $summary, $body, $createdAt, $category, $author) {
    $this->id = $id;
    $this->title = $title;
    $this->summary = $summary;                                           
    $this->body = $body;
    $this->createdAt = $createdAt;
    $this->category = $category;
    $this->author = $author;
  }
}