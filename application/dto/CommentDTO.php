<?php
namespace blog\articles;

class CommentDTO {

  public $id;
  public $author;
  public $body;
  public $createdAt;

  public function __construct($id, $author, $body, $createdAt) {
    $this->id = $id;
    $this->author = $author;
    $this->body = $body;                                           
    $this->createdAt = $createdAt;
  }
}