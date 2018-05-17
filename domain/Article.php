<?php
namespace blog\articles;

class Article {
 
    public $id;
    public $title;
    public $summary;
    public $body;
    public $timestamp;
    
    public $category;    
    public $author;         
}

class ArticleCategory {
  
    public $id;
    public $name;
}

class ArticleAuthor {
  
    public $id;
    public $name;
    public $email;
}