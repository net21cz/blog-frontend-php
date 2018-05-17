<?php
namespace blog;

use \PDO;

require_once __DIR__ . '/../domain/Author.php';
require_once __DIR__ . '/../domain/AuthorRepo.php';

class AuthorRepoPDO implements AuthorRepo {
 
    private $conn;
    
    private $author_table = "serendipity_authors";
  
    public function __construct(PDO $conn){
        $this->conn = $conn;
    }
    
    function fetchAll() {   
        $q = "SELECT a.authorid id, a.realname name, a.email email
                FROM {$this->author_table} a
                ORDER BY id ";
                        
        $stmt = $this->conn->prepare($q);        
        $stmt->execute();
        
        $authors = array();  
               
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $author = new Author();
          
          $author->id = (int)$row['id'];
          $author->name = $row['name'];
          $author->email = $row['email'];
          
          array_push($authors, $author);
        }
             
        return $authors;
    }
}