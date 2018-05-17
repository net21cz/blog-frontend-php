<?php
namespace blog;

use \PDO;

require_once __DIR__ . '/../domain/Option.php';
require_once __DIR__ . '/../domain/OptionsRepo.php';

class OptionsRepoPDO implements OptionsRepo {
 
    private $conn;
    
    private $options_table = "serendipity_config";
  
    public function __construct(PDO $conn){
        $this->conn = $conn;
    }
    
    function fetchAll($items) {   
        $q = "SELECT o.name, o.value
                FROM {$this->options_table} o
                WHERE o.name IN ('" . implode("','", $items) . "')
                ORDER BY o.name ";
                        
        $stmt = $this->conn->prepare($q);                
        $stmt->execute();
        
        $options = array();  
               
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $option = new Option();
          
          $option->name = $row['name'];
          $option->value = $row['value'];
          
          $options[$row['name']] = $option;
        }
        
        return $options;
    }
}