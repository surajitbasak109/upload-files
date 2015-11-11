<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Uploading Your File</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="header">
    <div class="banner">
      <div class="container">
        <h1><a href="index.php">File Gallery</a></h1>
      </div>
    </div>
  </header>

  <nav class="navBar">
    <div class="container">
      <ul class="nav-menu">
        <li class="active"><a href="index.php">Home</a></li>
        <li><a href="upload.php">Upload your file</a></li>
      </ul>
    </div>
  </nav>

  <section class="content-wrap">
    <div class="container">
      <aside class="primary sidebar-left">
        <div class="category-container">
          <h2 class="cat-title">Categories</h2>
          <ul class="cat-nav">
            <li>
              <a href="index.php?p=id">General</a>
            </li>
            <li>
              <a href="index.php?p=id">Education</a>
            </li>
            <li>
              <a href="index.php?p=id">E-commerce</a>
            </li>
            <li>
              <a href="index.php?p=id">Business</a>
            </li>
            <li>
              <a href="index.php?p=id">Featured</a>
            </li>
            <li>
              <a href="index.php?p=id">Social</a>
            </li>
            <li>
              <a href="index.php?p=id">Games</a>
            </li>
          </ul>
        </div>
      </aside>

      <section class="content-right">


<?php
try {
  $dbh = new PDO( "mysql:host=localhost; dbname=cms; charset=utf8", "root", "" );
  $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  $st = $dbh->query( "SELECT Id, Filename, Filepath, Filesize, Description, UNIX_TIMESTAMP( pub_date ) AS pub_date FROM uploads ORDER BY pub_date DESC" );  

  while ( $r = $st->fetch() ) { ?>
<div class="posts primary" id="post-<?php echo $r['Id']; ?>">
<header class="post-header">
  <h2 class="post-title"><?php echo $r['Filename'] ?></h2>
  <span class="pub_date">Uploaded on <time datetime="<?php echo date( "Y-m-j H:i", $r["pub_date"] ); ?>"><?php echo date( "F j" ); ?></time></span>
</header>
<article class="post-content">
  <p><?php echo $r['Description'] ?></p>
  <a href="<?php echo $r['Filepath'] ?>">Download <?php echo $r['Filename']; ?></a>
</article>
<footer class="post-footer">
  <p><strong>File size: </strong><?php echo $r['Filesize']; ?></p>
</footer>
</div>

<?php  }
} catch (PDOException $e) {
  echo "<h2>Error</h2>" . $e->getMessage();
}
?>
      </section>
  </section>

  <footer class="footer">
    <div class="container">&copy; File Gallery - 2015-16 designed by <a href="#">Webtorex community</a></div>
  </footer>
</body>
</html>