<!doctype html>
<html lang="en">
  <head>
    <title><?= $this->blog->title ?></title>
    <meta name="description" content="<?= $this->blog->description ?>">
    <meta name="author" content="Tomas Tulka - NET21 s.r.o.">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="assets/favicon.ico">
                    
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/blog.css" rel="stylesheet">
    <link href="assets/css/syntaxhighlighter-theme-eclipse.css" rel="stylesheet">
  </head>
  <body>
    <header>
      <div class="blog-masthead">
        <div class="container">
          <div class="row no-gutters">
            <nav class="nav col">
              <a class="nav-link active" href="/">Home</a>
              <a class="nav-link" href="programming.html">Programming</a>
              <a class="nav-link" href="stuff.html">Another nerdy stuff</a>
              <a class="nav-link" href="about.html">About</a>            
            </nav>
            <div class="col-2 ext-links">
              <a href="https://twitter.com/tomas_tulka"><img src="assets/img/twitter.png"></a>
              <a href="https://github.com/ttulka"><img src="assets/img/github.png"></a>
            </div>
          </div>
        </div>
      </div>  
    </header>

    <?= $CONTENT ?>

    <footer class="blog-footer">
      <div class="container">
        <div class="row no-gutters">
          <div class="col">© Tomas Tulka, NET21 s.r.o. </div>
          <div class="col text-right">
            <a href="/">Home</a> |
            <a href="/privacypolicy.html" target="_blank">Privacy Policy</a>
          </div>
        </div>
      </div>
    </footer>
    
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/syntaxhighlighter.min.js"></script>                
  </body>
</html>