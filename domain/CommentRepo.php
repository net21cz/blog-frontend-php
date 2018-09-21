<?php
namespace blog\comments;

interface CommentRepo {
 
    public function fetchAll($articleId);
    
    public function add($articleId, $body);
}