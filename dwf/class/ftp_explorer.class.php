<?php

/**
 * Cette classe permet d'afficher et explorer une arborescence FTP <strong>PUBLIQUE</strong>
 * le compte FTP renseigné dans cette classe ne doit avoir que des droits de consultation ( aucun droit d'ajout/modification/suppression)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class ftp_explorer {

    /**
     * Ressources de connexion FTP 
     * @var ressource ftp_connect
     */
    private $_ftp_connect;

    /**
     * Tableau associatif concernant les informations de connexion 
     * @var array array(host,user,psw)
     */
    private $_ftp_data;

    /**
     * Cette classe permet d'afficher et explorer une arborescence FTP <strong>PUBLIQUE</strong>
     * le compte FTP renseigné dans cette classe ne doit avoir que des droits de consultation ( aucun droit d'ajout/modification/suppression)
     * @param string $host hôte du serveur FTP ( ne pas indiquer ftp:// !)
     * @param string $user login du compte FTP publique
     * @param string $psw mot de passe du compte FTP publique ( LE MOT DE PASSE EST VISIBLE DANS L'EXPLORATEUR !)
     * @param boolean $ssl utiliser le protocole FTPS/SFTP ? (true ou false, false par default)
     */
    public function __construct($host, $user, $psw, $ssl = false) {
        $this->_ftp_data = ["host" => $host, "user" => $user, "psw" => $psw];
        ($ssl ? ftp_login($this->_ftp_connect = ftp_ssl_connect($host), $user, $psw) : ftp_login($this->_ftp_connect = ftp_connect($host), $user, $psw));
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("div.ftp_explorer > ul.ftp_explorer").menu();
            });
        </script>
        <?php

        echo tags::tag("div", ["class" => "ftp_explorer"], $this->explore("/"));
    }

    /**
     * Explore le serveur FTP et affiche son arborescence
     * @param string $dir dossier FTP à explorer 
     */
    private function explore($dir) {
        if ($nlist = ftp_nlist($this->_ftp_connect, $dir)) {
            $li = "";
            foreach ($nlist as $file) {
                $name = strtr($dirorfile, ["../" => "", "./" => "", $dir . "/" => ""]);
                if (@ftp_chdir($this->_ftp_connect, $file)) {
                    $id = strtr($file, [" " => "", "/" => "_"]);
                    $a = tags::tag("a", ["href" => "#" . $id, "id" => $id, "name" => $id], html_structures::glyphicon("folder-open", "Dossier") . " " . $name) .
                            $this->explore($file . "/");
                } else {
                    $a = tags::tag("a", ["target" => "_blank", "href" => "ftp://" . $this->_ftp_data["user"] . ":" . $this->_ftp_data["psw"] . "@" . $this->_ftp_data["host"] . $file], html_structures::glyphicon("file", "Fichier") . " " . $name);
                }
                $li .= tags::tag("li", [], $a);
            }
            return tags::tag(ul, ["class" => "ftp_explorer"], $li);
        }
    }

}
