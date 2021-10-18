<?php
require_once "./helpers.php";

if( isset($_REQUEST["file"])) {
    $file = $_REQUEST["file"];
    $filename = explode("@", $file)[0];

    $extension = get_extension($file);
    $mime_type = get_mime_type( $extension );
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename.$extension");
    header("Content-Type: $mime_type"); //change later for audio/video
    header("Content-Transfer-Encoding: binary"); 
    header('Content-Length: ' . filesize("./output/$file")); //finalize path
    readfile("./output/$file"); //send the output file to the user to download
}
