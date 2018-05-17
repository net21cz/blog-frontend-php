<?php
namespace blog\articles;

interface ArticleRepo {
 
    public function fetchAll($categoryId, $authorId, $start, $limit);
    
    public function fetchOne($articleId);
    
    public function count($categoryId, $authorId);
}