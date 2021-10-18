<?php
require_once "./helpers.php";

if (isset($_REQUEST["path"])) { //check path parameter if exist
    header('Content-type: application/json');
    $api_uri = "http://api.resmush.it/ws.php?";
    $media_path = $_REQUEST["path"]; //get file from path
    $quality = 60; //set default
    $quality_query = ""; //empty means quality value = 23 by default

    $filename = get_filename($media_path);
    $base_filename = get_base_filename($filename);
    $extension = get_extension($filename);
    $mime_type = get_mime_type($extension);

    if (isset($_REQUEST["filename"])) {
        $base_filename = $_REQUEST["filename"];
    }

    $temp_filename = $base_filename."@".time();

    if (!is_filename_valid($base_filename)) { //check if the filename is invalid then redirect to form
        $params = get_query_string();
        header("Location: ./index.php?$params");
    }

    if (isset($_REQUEST["q"])) { //if quality is set, add the set value to the default value

        switch ($_REQUEST["q"]) {
            case 1:
                $quality = 80;
                break;
            case 2:
                $quality = 60;
                break;
            case 3:
                $quality = 45;
                break;
            case 4:
                $quality = 30;
                break;
            case 5:
                $quality = 10;
                break;
        
            default:
                # code...
                break;
        }
    }

    $quality_query = "qlty=$quality&";

    $api_uri = $api_uri . $quality_query . "img=";

    define('WEBSERVICE', $api_uri);
    $s = $media_path;
    $o = json_decode(file_get_contents(WEBSERVICE . $s));

    if (isset($o->error)) {
        die('Error');
    }

    if (file_put_contents("./output/$temp_filename.$extension", file_get_contents($o->dest))) {
        $response = array(
            'filesize' => filesize("./output/$temp_filename.$extension"),
            'file' => "$temp_filename.$extension"
        );
        echo json_encode($response);
    } else {
        echo "File downloading failed.";
    }

    // //set header control for the output file to be downloaded
    // header("Cache-Control: public");
    // header("Content-Description: File Transfer");
    // header("Content-Disposition: attachment; filename=$filename");
    // header("Content-Type: $mime_type"); //change later for audio/video
    // header("Content-Transfer-Encoding: binary"); 

    // readfile($o->dest); //send the output file to the user to download

} else {
    header("Location: ./index.php");
}
