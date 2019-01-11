<div class="blog-header">
  <div class="container">
    <h1 class="blog-title"><?= $this->blog->title ?></h1>
    <p class="lead blog-description"><?= $this->blog->description ?></p>
  </div>
</div>

<main role="main" class="container">          
  <?php
  foreach ($this->articles['items'] as $article) { ?>      
    <div class="blog-post">
        <h2 class="blog-post-title"><a href="<?= $this->slugify($article->title . '-' . $article->id) ?>" class="h2"><?= $article->title ?></a></h2>
        <p class="blog-post-meta"><?= gmdate("Y-m-d", $article->createdAt) ?> by <a href="/?author=<?= $article->author->id ?>"><?= $article->author->name ?></a></p>
        <p><?= $article->summary ?></p>
    </div>
  <?php } ?>
    
    <nav class="blog-pagination">
      <?php
      if ($this->hasNext()) { ?>
        <a class="btn btn-outline-primary" href="<?= $this->nextUrl() ?>">Older</a>
      <?php } else { ?>
        <a class="btn btn-outline-primary disabled" href="#">Older</a>
      <?php } ?>
      
      <?php
      if ($this->hasPrevious()) { ?>
        <a class="btn btn-outline-secondary" href="<?= $this->previousUrl() ?>">Newer</a>
      <?php } else { ?>
        <a class="btn btn-outline-secondary disabled" href="#">Newer</a>
      <?php } ?>
    </nav>

</main><!-- /.container -->