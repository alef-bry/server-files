<?php
require_once "./helpers.php";

if( isset($_REQUEST["path"]) ) { //check path parameter if exist
    header('Content-type: application/json');

    $media_path = $_REQUEST["path"]; //get file from path
    $quality = 0; //set 
    $quality_query = ""; //empty means quality value = 23 by default

  
    $filename = get_filename($media_path);
    $base_filename = get_base_filename($filename);
    $extension = get_extension($filename);
    $mime_type = get_mime_type( $extension );

    if( isset( $_REQUEST["filename"]) ) {
        $base_filename = $_REQUEST["filename"];
    }

    $temp_filename = $base_filename."@".time();

    if( !is_filename_valid( $base_filename ) ) { //check if the filename is invalid then redirect to form
        $params = get_query_string();
        header("Location: ./index.php?$params");
    }

    if( isset( $_REQUEST["q"]) ) { //if quality is set, add the set value to the default value

        switch ($_REQUEST["q"]) {
            case 1:
                $quality = 23;
                break;
            case 2:
                $quality = 30;
                break;
            case 3:
                $quality = 35;
                break;
            case 4:
                $quality = 40;
                break;
            case 5:
                $quality = 50;
                break;
        
            default:
                # code...
                break;
        }

        //set the quality query
        if($extension == 'mp4') {
            $quality_query = "-crf $quality"; 
        }
        else {
            $quality_query = "-q $quality"; 
        }
    }

    $command = "ffmpeg -y -i $media_path $quality_query ./output/$temp_filename.mp4"; //command for the ffmpeg

    system($command); //execute/run the ffmpeg command

    //set header control for the output file to be downloaded
    // header("Cache-Control: public");
    // header("Content-Description: File Transfer");
    // header("Content-Disposition: attachment; filename=$base_filename.mp4");
    // header("Content-Type: $mime_type"); //change later for audio/video
    // header("Content-Transfer-Encoding: binary"); 
    // header('Content-Length: ' . filesize("./output/$filename")); //finalize path

    // readfile("./output/$filename"); //send the output file to the user to download
    // unlink("./$filename");
   //TODO logic for temporary output file

    $response = array(
        'filesize' => filesize("./output/$temp_filename.mp4"),
        'file' => $temp_filename.".mp4"
    );

    echo json_encode($response); 

} else {
    header("Location: ./index.php");
}