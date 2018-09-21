<?php
namespace blog\articles;

class CommentDTO {

  public $id;
  public $body;
  public $createdAt;

  public function __construct($id, $body, $createdAt) {
    $this->id = $id;
    $this->body = $body;                                           
    $this->createdAt = $createdAt;
  }
}