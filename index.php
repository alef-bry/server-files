<?php
require_once "./helpers.php";
$media_path = "";
$quality = 0;
$filename = "";

if (isset($_GET["path"])) {
    $media_path = $_GET["path"];
}

$filename = get_filename($media_path);
$base_filename = get_base_filename($filename);

if (isset($_GET["q"])) {
    $quality = $_GET["q"];
}


if (isset($_GET["filename"])) {
    $filename = $_GET["filename"];
}



?>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="./common/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./common/fontawesome/css/all.min.css" />

    <style>
        body {
            background: #f8f8f8;
        }

        label.input-group-text {
            min-width: 155px;
        }

        .container {
            max-width: 850px;
        }

        #download {
            margin-left: 10px;
        }

        * {
            box-shadow: none !important;
            outline: none !important;
        }
        .disabled {
            cursor: not-allowed !important;
        }
    </style>
</head>

<body>
    <div class="container p-4 mt-5 shadow-sm mb-5 bg-white rounded">
        <form class="m-0">
            <div class="mb-4">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="path">Media path *</label>
                    </div>
                    <!-- <div class="col-sm-10"> -->
                    <input type="text" class="form-control" id="path" aria-describedby="emailPath" value="<?php echo $media_path; ?>" />
                    <!-- </div> -->
                    <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                </div>
                <small class="text-muted">
                    Valid file extension/type: [ jpg, jpeg, png, gif, svg, mp3, aac, mp4, avi, flv, wmv ]
                </small>
            </div>
            <div class="mb-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="quality">Compression level</label>
                    </div>
                    <!-- <div class="col-sm-10"> -->
                    <select class="form-control" id="quality">
                        <option id="quality_opt_0" value="0">Default</option>
                        <option id="quality_opt_1" value="1" <?php echo ($quality == 1) ?  "selected" : "";  ?>>1 (Default)</option>
                        <option id="quality_opt_2" value="2" <?php echo ($quality == 2) ?  "selected" : "";  ?>>2</option>
                        <option id="quality_opt_3" value="3" <?php echo ($quality == 3) ?  "selected" : "";  ?>>3</option>
                        <option id="quality_opt_4" value="4" <?php echo ($quality == 4) ?  "selected" : "";  ?>>4</option>
                        <option id="quality_opt_5" value="5" <?php echo ($quality == 5) ?  "selected" : "";  ?>>5</option>
                    </select>
                    <!-- </div> -->
                </div>
            </div>
            <div class="mb-4">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="filename">File name</label>
                    </div>
                    <input type="text" class="form-control" id="filename" aria-describedby="filename" value="<?php echo $base_filename; ?>" />
                </div>
                <small id="passwordHelpInline" class="text-muted">
                    Acceptable file name:
                    <ul class="mt-1 mb-2">
                        <li>
                            Alpha-numerical [ A-z and 0-9 ]
                        </li>
                        <li>
                            Accepts underscore "_"
                        </li>
                        <li>
                            First character must not be underscore "_"
                        </li>
                    </ul>
                    Ex: EN_MLO_2_fish
                </small>
            </div>
            <div class="input-group mb-3">
                <!-- <input type="text" class="form-control" value="" placeholder="Some path" id="copy-input" readonly>
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary" id="copy-button" type="button" data-toggle="tooltip" data-placement="top" title="Copy to Clipboard">
                        <i class="far fa-copy"></i>
                    </button>
                    <button class="btn btn-outline-secondary go-to-button" type="button" id="go-to" data-toggle="tooltip" data-placement="top" title="" data-original-title="Copy to Clipboard">
                        <i class="fas fa-file-import"></i>
                    </button>
                </span> -->
                <button class="btn btn-primary" id="compress">
                    Compress
                </button>
                <a href="" class="btn btn-success d-none" id="download" download target="_blank">Download</a>
            </div>
            <small class="result-status text-muted"></small>
        </form>
    </div>

    <script src="./common/jquery/jquery-3.0.0.min.js"></script>
    <script src="./common/bootstrap/js/bootstrap.bundle.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var is_filename_edited = false;
            var is_compressing = false;
            var path_iput = $("#path");
            var quality_iput = $("#quality");
            var filename_iput = $("#filename");

            var quality_query = "";
            var filename_query = "";
            var path_query = "";
            var path = window.location.origin;
            var file_type = null;
            var is_filename_valid = true;

            $('[data-toggle="tooltip"]').tooltip()

            function getFileName(path) {
                var file_name = path.split("/");
                return file_name = file_name[file_name.length - 1]
            };

            function getBaseFileName(filename) {
                var base_file_name = filename.split(".");
                base_file_name.pop();
                base_file_name = base_file_name.join(".");
                return base_file_name;
            };

            function updateCompressURL() {
                var base_url = window.location.origin;
                path_query = "path=" + path_iput.val();
                quality_query = quality_iput.val() != "0" ? "&q=" + quality_iput.val() : "";
                filename_query = is_filename_edited ? "&filename=" + filename_iput.val() : "";
                path = base_url + "/compressor/compress-image.php?" + path_query + quality_query + filename_query;

                $("#copy-input").val(path);
            }

            function validateFilename() {
                var filename = filename_iput.val();
                is_filename_valid = true;
                if (filename.charAt(0) == "_") {
                    is_filename_valid = is_filename_valid && false;
                }
                if ((/[^a-z_0-9]/i).test(filename)) {
                    is_filename_valid = is_filename_valid && false;
                }
                if (!is_filename_valid) {
                    filename_iput.addClass("is-invalid");
                    $("#compress").addClass("disabled");
                } else {
                    filename_iput.removeClass("is-invalid");
                    $("#compress").removeClass("disabled");
                }
            }

            function checkFileType() {
                var ext = path_iput.val().split(".");
                if (ext.length) {
                    ext = ext[ext.length - 1];
                    switch (ext.toLowerCase()) {
                        case 'mp4':
                        case 'avi':
                        case 'flv':
                        case 'wmv':
                            file_type = "video";
                            break;
                        case 'mp3':
                        case 'wav':
                        case 'aac':
                            file_type = "audio";
                            break;
                        case 'mp3':
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'svg':
                            file_type = "image";
                            break;
                        case 'mp3':
                        default:
                            break;
                    }
                }
                if( file_type == "image" ) {
                    $("#quality_opt_1").html("1");
                    $("#quality_opt_2").html("2 (Default)");
                } else {
                    $("#quality_opt_1").html("1 (Default)");
                    $("#quality_opt_2").html("2");
                }
            }

            function bytesToSize(bytes) {
                var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                if (bytes == 0) return 'n/a';
                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                if (i == 0) return bytes + ' ' + sizes[i];
                return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
            };

            $("#path").on("change paste keyup", function() {
                if (!is_filename_edited) {
                    var media_path = path_iput.val();
                    var filename = getFileName(media_path);
                    var base_filename = getBaseFileName(filename);
                    filename_iput.val(base_filename);
                    validateFilename();
                }
                updateCompressURL();
                checkFileType();
            })

            $("#quality").on("change", function() {
                updateCompressURL();
            })

            $("#filename").on("change paste keyup", function() {
                is_filename_edited = true;

                validateFilename();

                updateCompressURL();
            })

            $('#go-to').bind('click', function() {

                window.open(path, '_blank');
            })

            $('#copy-button').bind('click', function() {
                console.log("test");
                var copyText = document.getElementById("copy-input");
                /* Select the text field */
                copyText.select();
                copyText.setSelectionRange(0, 99999); /*For mobile devices*/
                try {
                    var success = document.execCommand('copy');
                    if (success) {
                        $('#copy-button').trigger('copied', ['Copied!']);
                    } else {
                        $('#copy-button').trigger('copied', ['Copy with Ctrl-c']);
                    }
                } catch (err) {
                    $('#copy-button').trigger('copied', ['Copy with Ctrl-c']);
                }
            });

            // Handler for updating the tooltip message.
            $('#copy-button').bind('copied', function(event, message) {
                $(this).attr('title', message)
                    .tooltip('_fixTitle')
                    .tooltip('show')
                    .attr('title', "Copy to Clipboard")
                    .tooltip('_fixTitle');
            });

            $("#compress").click(function(e) {
                e.preventDefault();
                checkFileType();
                if (!is_compressing && is_filename_valid ) {
                    $("#download").addClass("d-none");
                    $("#compress").addClass("disabled");
                    $("#compress").html("Compressing...");
                    $(".result-status").html("");
                    is_compressing = true;
                    data = {};
                    data.path = path_iput.val();
                    data.filename = filename_iput.val()
                    if (quality_iput.val() != "0") {
                        data.q = quality_iput.val()
                    }
                    var url = "";
                    console.log(file_type, data, "file_type")
                    switch (file_type) {
                        case "video":
                            url = "./compress-video.php";
                            break;
                        case "audio":
                            url = "./compress-audio.php";
                            break;
                        case "image":
                            url = "./compress-image.php";
                            break;
                        default:
                            break;
                    }
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: data,
                        success: function(data) {
                            $("#compress").removeClass("disabled");
                            $("#compress").html("Compress");
                            console.log(data, "success");
                            is_compressing = false;
                            $("#download").removeClass("d-none").attr("href", "./download.php?file="+data.file);
                            $(".result-status").html("Compressed file size: " + bytesToSize(data.filesize))
                            const mb = 1048576;
                            var filesize = data.filesize;
                            var size_limit = mb;
                            switch (file_type) {
                                case "video":
                                    size_limit = mb * 30;
                                    break;
                                case "audio":
                                    size_limit = mb * 3;
                                    break;
                                case "image":
                                    size_limit = mb;
                                    break;
                                default:
                                    break;
                            }
                            if( filesize > size_limit ) {
                                $(".result-status").append("<div class='text-danger'><strong>Warning</strong>: File size must not exceed "+bytesToSize(size_limit)+" for "+file_type+". Try increasing the compression level.</div>");
                            }
                        },
                        error: function(error) {
                            $("#compress").removeClass("disabled");
                            $("#compress").html("Compress");
                            is_compressing = false;
                            console.log("Error:");
                            console.log(error);
                            $(".result-status").html("<div class='text-danger'><strong>Error</strong>: Something went wrong. Please double check the file path/URL if valid.</div>");
                        }
                    });
                }
            })

            updateCompressURL();
            validateFilename();
            checkFileType();

        })
    </script>

</body>

</html>