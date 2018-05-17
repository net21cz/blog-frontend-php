<?php
namespace blog;

use \PDO;

require_once __DIR__ . '/../domain/Category.php';
require_once __DIR__ . '/../domain/CategoryRepo.php';

class CategoryRepoPDO implements CategoryRepo {
 
    private $conn;
    
    private $category_table = "serendipity_category";
  
    public function __construct(PDO $conn){
        $this->conn = $conn;
    }
    
    function fetchAll() {   
        $q = "SELECT c.categoryid id, c.category_name name
                FROM {$this->category_table} c
                ORDER BY id ";
                        
        $stmt = $this->conn->prepare($q);        
        $stmt->execute();
        
        $categories = array();  
               
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $category = new Category();
          
          $category->id = (int)$row['id'];
          $category->name = $row['name'];
          
          array_push($categories, $category);
        }
             
        return $categories;
    }
}