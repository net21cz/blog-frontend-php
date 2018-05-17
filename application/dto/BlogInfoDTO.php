<?php
namespace blog;

class BlogInfoDTO {

  public $title;
  public $description;  
  public $categories;
  public $authors;

  public function __construct($title, $description, $categories, $authors) {
    $this->title = $title;                                           
    $this->description = $description;
    $this->categories = $categories;
    $this->authors = $authors;
  }
}

class CategoryDTO {
  
  public $id;
  public $name;
  
  public function __construct($id, $name) {
    $this->id = $id;
    $this->name = $name;
  }
}

class AuthorDTO {
  
  public $id;
  public $name;
  public $email;
  
  public function __construct($id, $name, $email) {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
  }
}