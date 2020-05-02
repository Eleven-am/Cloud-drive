<?php

session_abort();
include("json.php");
include("class.php");

    if (isset($_GET["recent"])){
        if (file_exists($_SESSION["JSON"] . "recent.json")){
            $myArray = retrieve($_SESSION["JSON"] . "recent.json");

            $result = array();
            foreach ($myArray as $item => $value) {
                $img_file = $_SESSION["JSON"] . pathinfo($item, PATHINFO_FILENAME) . ".png";
                $tmp = strtolower(end(explode(".", $item)));
                $media = $tmp === "mkv" || $tmp === "mp4" || $tmp === "m4v" || $tmp === "webm";
                $img = $tmp === "png" || $tmp === "jpg";

                if($media || $img){
                    $mediaclass = $media? "recent-div cons media": "recent-div cons image";
                }
                else $mediaclass = "recent-div download";

                $image = base64_encode(file_get_contents($img_file));

                $temp = (object)["name" => basename($item), "media" => $media, "image" => $image, "location" => $value, "mediaclass" => $mediaclass, "dataName" => basename($item)];
                array_push($result, $temp);

            }

        } else { $result = array(); }

        $myJSON = json_encode($result);
        echo $myJSON;

    } else if (isset($_GET["code"])){
        $file = decypher($_GET["code"], 1, TRUE);
        $name = basename($file);
        $size = sizefinder($file);
        $time = date("F d Y H:i:s.", fileatime($file));
        $location = locate($file);

        $info  = (object)["name" => $name, "size" => $size, "time" => $time, "location" => $location];

        $myJSON = json_encode($info);
        echo $myJSON;

    } else {
        if (isset($_GET["test"])) {
            $path = $_GET["test"];
            echo locate($path);
        }
    }



?>