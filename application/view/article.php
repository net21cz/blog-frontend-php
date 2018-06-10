<main role="main" class="container">

  <h1 class="blog-title"><?= $this->article->title ?></h1>          
  
  <article class="blog-post">
      <p class="blog-post-meta"><?= gmdate("d/m/Y", $this->article->createdAt) ?> by <a href="/?author=<?= $this->article->author->id ?>"><?= $this->article->author->name ?></a></p>
      <p><?= $this->article->summary ?></p>
      <p><?= $this->article->body ?></p>
  </article>

</main><!-- /.container -->