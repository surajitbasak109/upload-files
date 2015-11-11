<?php

date_default_timezone_set( "Asia/Kolkata" );

if ( isset( $_POST['upload'] ) ) {

  if ( empty( $_FILES['filename']['name'] ) ) {
    $error[] = "No file was selected for upload.";
  }

  if ( empty( $_POST['description'] ) ) {
    $error[] = "Description field is recommended.";
    $description = mysql_real_escape_string( $_POST['description'] );
  }

  if ( !isset( $error ) ) {

    // target directory to upload files
    $target = "uploads/";
    $file = $_FILES['filename'];
    $file_name = basename( $file['name'] );
    $file_type = $file['type'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $date = date( 'jmy-hms' );
    if ( !is_dir( $date ) ) mkdir( $target . "/" . $date );
    $file_path = $target . $date . "/" . $file_name;
    $description = $_POST['description'];

    // humanFileSize function to make bytes to KB/MB/GB unit
    function humanFileSize($size,$unit="") {
      if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
      if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
      if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
      return number_format($size)." bytes";
    }

    $file_size = humanFileSize( $file_size );

    // check to see if file is already uploaded or file name is duplicate
    if ( file_exists( $file_path ) ) {
      $error[] = "File already exists";
    }

    // check to see if file is larger than 15MB
    if ( $file['size'] > 14865981 ) {
      $error[] = "Sorry, your file is too large.";
    } 

    if ( !isset( $error ) ) { // if validation passes then move the uploaded file to target folder

      if ( move_uploaded_file( $file_tmp, $file_path ) ) {

        // give success message to the user
        $success = "Your <strong>". $file_name ."</strong> file has been uploaded.";

        // escaping string avoiding SQL injection
        $file_name = mysql_real_escape_string( $file_name );

        // make database connection
        $dbh = new PDO( "mysql:host=localhost; dbname=cms; charset=utf8", "root", "" );
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $st = $dbh->prepare(
          "INSERT INTO uploads (Filename, Description, Filepath, Filesize) VALUES (:file_name, :description, :file_path, :file_size)"
        );

        $st->execute(
          array(
            ":file_name" => $file_name,
            ":description" => $description,
            ":file_path" => $file_path,
            ":file_size" => $file_size
          )
        );

      } else {
        $error[] = "Sorry, your file couldn't be uploaded.";
      }
    }
  }

}

?>

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
        <li><a href="index.php">Home</a></li>
        <li class="active"><a href="upload.php">Upload your file</a></li>
      </ul>
    </div>
  </nav>

  <section class="content-wrap">
    <div class="container">
      <div class="upload-form">
        <h2>Upload you file</h2>
        <form action="" method="post" enctype="multipart/form-data">
          <label for="filename">Upload</label>
          <input type="file" name="filename" id="filename" class="input-file">
          <label for="description">Description</label>
          <textarea name="description" id="description"></textarea>
          <input type="submit" value="Upload" name="upload">
        </form>
        <div class="status">
          <?php
          if ( isset( $error ) ) {
            foreach ($error as $error ) {
              echo "<span class='error'>$error</span>";
            }
          }

          if ( isset( $success ) ) {
            echo "<span class='success'>$success</span>";
          }
          ?>
        </div>
      </div>
    </div>
  </section>
  <footer class="footer">
    <div class="container">&copy; File Gallery - 2015-16 designed by <a href="#">Webtorex community</a></div>
  </footer>
</body>
</html>