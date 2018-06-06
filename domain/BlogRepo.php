<?php
namespace blog;

interface BlogRepo {
 
    public function categories();
    
    public function authors();
    
    public function options();
}