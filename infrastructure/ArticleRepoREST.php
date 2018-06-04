<?php
namespace blog\articles;

require_once __DIR__ . '/../domain/Article.php';
require_once __DIR__ . '/../domain/ArticleRepo.php';

class ArticleRepoREST implements ArticleRepo {
    
    public function fetchOne($articleId) {
        $q = "SELECT a.id, a.title, a.body summary, a.extended text, a.timestamp, 
                  c.categoryId, c.category_name categoryName, 
                  au.authorId, au.realname authorName, au.email authorEmail
                FROM {$this->articles_table} a
                    LEFT JOIN {$this->articles_categories_table} ac ON a.id = ac.entryid
                    LEFT JOIN {$this->categories_table} c ON c.categoryId = ac.categoryId
                    LEFT JOIN {$this->authors_table} au ON a.authorid = au.authorid
                WHERE a.id = :id ";
                        
        $stmt = $this->conn->prepare($q);        
        $stmt->bindValue('id', (int)$articleId, PDO::PARAM_INT);        
        $stmt->execute();
        
        $article = null;  
               
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $article = new Article();
          
          $article->id = (int)$row['id'];
          $article->title = $row['title'];
          $article->summary = $row['summary'];
          $article->body = $row['text'];
          $article->timestamp = $row['timestamp'];
          
          $article->category = new ArticleCategory();
          $article->category->id = (int)$row['categoryId'];
          $article->category->name = $row['categoryName'];
          
          $article->author = new ArticleAuthor();
          $article->author->id = (int)$row['authorId'];
          $article->author->name = $row['authorName'];
          $article->author->email = $row['authorEmail'];
        }
             
        return $article;
    }
    
    function fetchAll($categoryId = null, $authorId = null, $start = 0, $limit = 10) {   
        $q = "SELECT a.id, a.title, a.body summary, a.timestamp, 
                  c.categoryId, c.category_name categoryName,
                  au.authorId, au.realname authorName, au.email authorEmail
                FROM {$this->articles_table} a
                    LEFT JOIN {$this->articles_categories_table} ac ON a.id = ac.entryid
                    LEFT JOIN {$this->categories_table} c ON c.categoryId = ac.categoryId
                    LEFT JOIN {$this->authors_table} au ON a.authorid = au.authorid
                WHERE 1=1 ";
                
        $params = array('start' => (int)$start, 'limit' => (int)$limit);

        if ($categoryId) {
            $q .= " AND ac.categoryid = :categoryId";
            $params['categoryId'] = (int)$categoryId;
        }
        if ($authorId) {
            $q .= " AND au.authorId = :authorId";
            $params['authorId'] = (int)$authorId;
        }                    
                    
        $q .="  ORDER BY a.timestamp DESC, a.id DESC
                LIMIT :start,:limit";
        
        $stmt = $this->conn->prepare($q);
        
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        $articles = array();  
               
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $article = new Article();
          
          $article->id = (int)$row['id'];
          $article->title = $row['title'];
          $article->summary = $row['summary'];
          $article->timestamp = $row['timestamp'];
          
          $article->category = new ArticleCategory();
          $article->category->id = (int)$row['categoryId'];
          $article->category->name = $row['categoryName'];
          
          $article->author = new ArticleAuthor();
          $article->author->id = (int)$row['authorId'];
          $article->author->name = $row['authorName'];
          $article->author->email = $row['authorEmail'];
          
          array_push($articles, $article);
        }
             
        return $articles;
    }
    
    public function count($categoryId, $authorId) {
        $q = "SELECT COUNT(DISTINCT a.id) count
                FROM {$this->articles_table} a
                    LEFT JOIN {$this->articles_categories_table} ac ON a.id = ac.entryid
                    LEFT JOIN {$this->authors_table} au ON a.authorid = au.authorid
                WHERE 1=1 ";
                
        $params = array();

        if ($categoryId) {
            $q .= " AND ac.categoryid = :categoryId";
            $params['categoryId'] = (int)$categoryId;
        }
        if ($authorId) {
            $q .= " AND au.authorId = :authorId";
            $params['authorId'] = (int)$authorId;
        }
        
        $stmt = $this->conn->prepare($q);
        
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        $articles = array();  
               
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          
          return (int)$row['count'];
        }
             
        return 0;
    }
}