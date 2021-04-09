<?php

/**
 * Cette classe permet a l'utilisateur de télécharger un fichier specifique sur le serveur
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class downloader {

    public static function file($fullPathFile, $btn_txt = "Télécharger") {
        $key = "dwf_downloads_allow";
        $_SESSION[$key] = (isset($_SESSION[$key]) ? $_SESSION[$key] : []);
        $_SESSION[$key][$hash = sha1(uniqid($fullPathFile))] = $fullPathFile;
        $form = new form("", "../commun/download.php", "POST", true);
        $form->hidden("hash", $hash);
        $form->submit("btn-primary", $btn_txt);
        return $form->render();
    }

    public function clear() {
        $_SESSION["dwf_downloads_allow"] = [];
    }

}
