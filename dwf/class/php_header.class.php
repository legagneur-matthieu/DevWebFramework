<?php

/**
 * Cette classe permet de modifier le header HTTP
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class php_header {

    /**
     * Liste des statuts codes
     * @var array Liste des statuts codes
     */
    private $_status_codes;

    /**
     *
     * Liste des mimes
     * @var array Liste des mimes
     */
    private $_mimes;

    /**
     * Cette classe permet de modifier le header HTTP
     */
    public function __construct() {
        $this->_status_codes = json_decode(file_get_contents(__DIR__ . "/php_header/status.json"), true);
        $this->_mimes = json_decode(file_get_contents(__DIR__ . "/php_header/mimes.json"), true);
    }

    /**
     * D�finit le statut code de la page, si le statut code est invalide, le code 200 est utilis� par d�faut
     * @param int $code statut code
     */
    public function status_code($code) {
        if (!isset($this->_status_codes[$code])) {
            $code = 200;
        }
        header($this->_status_codes[$code], true, $code);
    }

    /**
     * Renseigne le type (mime) du document.
     * Renseignez juste l'extention du fichier ( par exemple "json" ou "csv"), la fonction sera retrouv�e le mime corespondant.
     * @param string $type extension du fichier (sans le point "." ) ou le mime du fichier
     * @param string $force_upload_file forcer le navigateur � t�l�charger le fichier, si oui : renseigner le nom du fichier (avec son extention)
     * @param string $charset charset du fichier, UTF-8 par d�faut
     */
    public function content_type($type, $force_upload_file = "", $charset = "UTF-8") {
        if (!empty($force_upload_file)) {
            header("Content-Disposition: attachment; filename=" . $force_upload_file . ";");
        }
        (isset($this->_mimes[$type]) ? header("Content-type:" . $this->_mimes[$type] . "; charset=" . $charset) : header("Content-type:" . $type . "; charset=" . $charset));
    }

    /**
     * Redirige l'utilisateur (imm�diatement ou avec un d�lai)
     * 
     * @param string $url URL de redirection
     * @param int $second d�lai (0 par defaut)
     */
    public function redir($url, $second = 0) {
        ($second > 0 ? header('Refresh: ' . $second . '; url=' . $url) : header('Location: ' . $url));
    }

}
