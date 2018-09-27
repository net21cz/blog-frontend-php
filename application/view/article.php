<main role="main" class="container">

  <h1 class="blog-title"><?= $this->article->title ?></h1>          
  
  <article class="blog-post">
      <p class="blog-post-meta"><?= gmdate("d/m/Y", $this->article->createdAt) ?> by <a href="/?author=<?= $this->article->author->id ?>"><?= $this->article->author->name ?></a></p>
      <p><?= $this->article->summary ?></p>
      <hr>
      <p><?= $this->article->body ?></p>
  </article>
  
  <?php 
  $comment = $this->addingComment; 
  $errors = $this->errors;
  ?>
  <aside class="blog-comments">
    <form action="" method="post">
    <div class="form-group">
      <label for="comment_body" class="sr-only">Add a new comment:</label>
      <textarea id="comment_body" name="body" class="form-control" placeholder="Add a new comment..."><?php        
        if (!empty($comment)) { ?><?= $comment->body ?><?php } ?></textarea>
    </div>
    <div class="form-inline">
      <div class="form-group mx-2">
        <img src="/captcha.php?suffix=blogcomments" class="mr-3" />
        <label for="captcha" class="sr-only">Captcha</label>
        <input class="form-control <?php        
        if (!empty($errors) && in_array('captcha', $errors)) { ?>is-invalid<?php } 
        ?>" id="captcha" name="captcha" size="5" />              
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>            
    </div>
    </form>
    
    <?php
    $commentAdded = $this->addedComment;
    if (!empty($commentAdded->id)) { ?>
      <div class="alert alert-info mt-3" role="alert">
        Thank you for the comment!
      </div>
      <div class="mt-3 pt-3 border-top">
        <?= $commentAdded->body ?>
      </div>
    <?php }
    
    if (!empty($this->article->comments)) {
      foreach($this->article->comments as $c) { ?>
        <div class="mt-3 pt-3 border-top">
          <?= $c->body ?>
        </div>  
    <?php } } ?>  
  </aside>

</main><!-- /.container -->