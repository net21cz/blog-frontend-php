<main role="main" class="container">

  <h1 class="blog-title"><?= $this->article->title ?></h1>          
  
  <article class="blog-post">
      <p class="blog-post-meta"><?= gmdate("Y-m-d", $this->article->createdAt) ?> by <a href="/?author=<?= $this->article->author->id ?>"><?= $this->article->author->name ?></a></p>
      <p><?= $this->article->summary ?></p>
      <hr>
      <p><?= $this->article->body ?></p>
  </article>
  
  <aside class="blog-comments">
    <noscript>You need to enable JavaScript to see comments.</noscript>
    <div id="comments" articleid="<?= $this->article->id ?>">Rendering comments...</div>
    <script src="/assets/js/comments.min.js"></script>
    <link href="/assets/css/comments.min.css" rel="stylesheet"> 
  </aside>

</main><!-- /.container -->