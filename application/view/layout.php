<!doctype html>
<html lang="en">
  <head>
    <title><?= $this->blog->title ?></title>
    <meta name="description" content="<?= $this->blog->description ?>">
    <meta name="author" content="Tomas Tulka - NET21 s.r.o.">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="/assets/favicon.ico">
                    
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/blog.css" rel="stylesheet">
    <link href="/assets/css/syntaxhighlighter-theme-eclipse.css" rel="stylesheet">
  </head>
  <body>
    <header>
      <div class="blog-masthead">
        <div class="container">
          <div class="row no-gutters">
            <nav class="nav col">
              <a class="nav-link active" href="/">Home</a>
              <?php
              foreach($this->blog->categories as $category) { 
              ?>            
                <a class="nav-link extended-nav" href="/?category=<?= $category->id ?>"><?= $category->name ?></a>              
              <?php } ?>            
              <a class="nav-link" href="/about-me-1">About</a>
            </nav>
            <div class="ext-links">
              <a href="https://twitter.com/tomas_tulka"><img src="/assets/img/twitter.png"></a>
              <a href="https://github.com/ttulka"><img src="/assets/img/github.png"></a>
            </div>
          </div>
        </div>
      </div>  
    </header>

    <?= $CONTENT ?>

    <footer class="blog-footer">
      <div class="container">
        <div class="row no-gutters">
          <div class="col extended-nav">© <?= date('Y') ?> Tomas Tulka, NET21 s.r.o.</div>
          <div class="col footer-menu">
            <a href="/">Home</a> |
            <a href="/privacypolicy" target="_blank">Privacy Policy</a>
          </div>
        </div>
      </div>
    </footer>
    
    <script src="/assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/syntaxhighlighter.min.js"></script>                
  </body>
</html>