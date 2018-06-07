<?php
namespace blog\articles;

interface ArticleRepo {
 
    public function fetchAll($categoryId, $authorId, $start);
    
    public function fetchOne($articleId);
}