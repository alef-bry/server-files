<?php

function get_filename( $text ) {
    $filename = explode("/", $text);
    $filename = $filename[count($filename) - 1];

    return $filename;
}

function get_base_filename( $text ) {
    $base_filename =  explode(".", $text);
    array_pop($base_filename);
    $base_filename = implode(".", $base_filename);

    return $base_filename;
}

function get_extension( $text ) {
    $extension = explode(".", $text);
    $extension =  strtolower( $extension[count($extension) - 1] );

    return $extension;
}


function get_mime_type( $extension ) {
    $mime_type = "";
    switch ($extension) {
        //for image
        case 'jpg':
        case 'jpeg':
            $mime_type = "image/jpeg";
            break;
        case 'png':
            $mime_type = "image/png";
            break;
        case 'gif':
            $mime_type = "image/gif";
            break;
        case 'svg':
            $mime_type = "image/svg+xml";
            break;
        //for audio
        case 'mp3':
        case 'wav':
        case 'aac':
            $mime_type = "audio/mpeg";
        //for video
        case 'mp4':
        case 'avi':
        case 'flv':
        case 'wmv':
            $mime_type = "video/mp4";
            break;
        default:
            break;
    }

    return $mime_type;
}

function is_filename_valid( $filename ) {
    $is_valid = true;

    if( substr(  $filename, 0, 1 ) == "_" ) {
        $is_valid = $is_valid && false;
    }

    if( preg_match('/[^a-z_0-9]/i', $filename) ) {
        $is_valid = $is_valid && false;
    }
    return $is_valid;
}

function get_query_string() {
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]".strtok($_SERVER["REQUEST_URI"], '?');
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    return str_replace( "$base_url?", "", $actual_link );
}

