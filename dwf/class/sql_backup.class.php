<?php

/**
 * Cette classe permet de faire des backup (sauvegardes) de la base de données à partir des entités de l'application courante
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class sql_backup {

    /**
     * Cette classe permet de faire des backup (sauvegardes) de la base de données à partir des entités de l'application courante
     */
    public function __construct() {
        
    }

    /**
     * Cette fonction permet de créer un backup dans le dossier passé en paramètre <br />
     * ( il est recommandé de renseigner un chemin vers un disque dur de sauvegarde différent du disque dur du système !)
     * @param string $path chemin vers le dossier de sauvegarde ( ne doit pas se terminer par un '/' ou '\'
     */
    public function backup_to_path($path) {
        if (!file_exists($path)) {
            mkdir($path);
        }
        if (!file_exists($htaccess = $path . "/.htaccess")) {
            file_put_contents($htaccess, "Order Deny,Allow \n Deny from All \n Allow from localhost");
            dwf_exception::check_file_writed($htaccess);
        }
        if (!file_exists($backup = $path . "/" . config::$_prefix . "_" . date("Y.m.d") . ".sql")) {
            file_put_contents($backup, $this->get_sql());
            dwf_exception::check_file_writed($backup);
        }
    }

    /**
     * Cette fonction permet de créer un backup dans un dossier sur un serveur FTP distant
     * @param string $dir Chemin du dossier de sauvegarde sur le FTP
     * @param string $host Host du FTP
     * @param string $login Login du FTP
     * @param string $psw Password du FTP
     * @param boolean $ssl utiliser le protocole SFTP ? (true/false, false par defaut)
     */
    public function backup_to_ftp($dir, $host, $login, $psw, $ssl = false) {
        ($ssl ? ftp_login($ftp = ftp_ssl_connect($host), $login, $psw) : ftp_login($ftp = ftp_connect($host), $login, $psw));
        if (!@ftp_chdir($ftp, $dir)) {
            ftp_mkdir($ftp, $dir);
            ftp_chdir($ftp, $dir);
        }
        $remote_file = config::$_prefix . "_" . date("Y.m.d") . ".sql";
        $path = __DIR__ . "/backup_temp";
        $local_file = $path . "/" . config::$_prefix . "_" . date("Y.m.d") . ".sql";
        if (!@ftp_get($ftp, $local_file, $remote_file, FTP_BINARY)) {
            $this->backup_to_path($path);
            ftp_put($ftp, $remote_file, $local_file, FTP_BINARY);
        }
        unlink($local_file);
    }

    /**
     * Retourne le contenu du fichier de sauvegarde à enregistrer ( sous forme de requêtes SQL)
     * @return string requêtes SQL
     */
    private function get_sql() {
        $from = array(
            array(
                "class/entity/" => "",
                ".class.php" => ""
            ),
            array("(," => "("),
            array(",__" => ";")
        );
        $sql = "";
        foreach (glob("class/entity/*.class.php") as $entity) {
            $entity = strtr($entity, $from[0]);
            $structure = $entity::get_structure();
            $data = $entity::get_table_array(application::$_bdd);
            $ai = $entity::get_count(application::$_bdd);
            $create = "CREATE TABLE IF NOT EXISTS ";
            $insert = "INSERT INTO ";
            $pk = "PRIMARY KEY (";
            $create .= $entity . " (";
            $insert .= $entity . " (";
            foreach ($structure as $s) {
                $create .= $s[0];
                $insert .= "," . $s[0];
                if ($s[1] == "string" or $s[1] == "mail") {
                    $create .= " text NOT NULL";
                } else {
                    $create .= " int(11) NOT NULL";
                }
                if ($s[2]) {
                    $create .= " AUTO_INCREMENT,";
                    $pk .= "," . $s[0];
                } else {
                    $create .= ",";
                }
            }
            $create .= strtr($pk, $from[1]) . ")) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=" . $ai . ";";
            $insert = strtr($insert, $from[1]) . ") VALUES ";
            foreach ($data as $line) {
                $values = "(";
                foreach ($line as $cell) {
                    $values .= ",'" . $cell . "'";
                }
                $insert .= strtr($values, $from[1]) . "),";
            }
            $insert .= "__";
            $insert = strtr($insert, $from[2]);
            $sql .= $create . $insert;
        }
        return $sql;
    }

}
