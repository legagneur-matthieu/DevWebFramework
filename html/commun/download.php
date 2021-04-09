<?php

/**
 * Cette classe permet de télécharger un fichier préalablement autorisé par 
 * downloader.class.php
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class download {

    public function __construct() {
        ini_set("display_errors", "On");
        session_start();
        $key = "dwf_downloads_allow";
        if (
                isset($_SESSION[$key]) &&
                isset($_POST["hash"]) &&
                isset($_SESSION[$key][$_POST["hash"]])
        ) {
            $file = $_SESSION[$key][$_POST["hash"]];
            if (file_exists($file)) {
                $mime = mime_content_type($file);
                $filename = basename($file);
                header("Content-Disposition: attachment; filename=$filename;");
                header('Content-Length: ' . filesize($file));
                header("Content-type:$mime; charset=UTF-8");
                echo file_get_contents($file);
            } else {
                header("HTTP/1.1 404 Not Found");
                ?><h1>404 Not Found</h1><?php
            }
        } else {
            header("HTTP/1.1 403 Forbidden");
            ?><h1>403 Forbidden</h1><?php
        }
    }

}

new download();
