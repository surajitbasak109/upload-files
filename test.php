<?php
$target = "uploads/";
$file_name = "myFile.txt";
$date = date( 'jmy-hms' );
if ( !is_dir( $date ) ) mkdir( $target . "/" . $date );
$file_path = $target . $date . "/" . $file_name;
echo $file_path;
?>