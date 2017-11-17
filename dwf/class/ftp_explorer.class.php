<?php

/**
 * Cette classe permet d'afficher et explorer une arborescence FTP <strong>PUBLIQUE</strong>
 * le compte FTP renseigné dans cette classe ne doit avoir que des droits de consultation ( aucun droits d'ajout/modification/suppression)
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
     * le compte FTP renseigné dans cette classe ne doit avoir que des droits de consultation ( aucun droits d'ajout/modification/suppression)
     * @param string $host hÃ´te du serveur FTP ( ne pas indiquer ftp:// !)
     * @param string $user login du compte FTP publique
     * @param string $psw mot de passe du compte FTP publique ( LE MOT DE PASSE EST VISIBLE DANS L'EXPLORATEUR !)
     * @param boolean $ssl utiliser le protocole FTPS/SFTP ? (true ou false, false par default)
     */
    public function __construct($host, $user, $psw, $ssl = false) {
        $this->_ftp_data = array("host" => $host, "user" => $user, "psw" => $psw);
        ($ssl ? ftp_login($this->_ftp_connect = ftp_ssl_connect($host), $user, $psw) : ftp_login($this->_ftp_connect = ftp_connect($host), $user, $psw));
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("div.ftp_explorer > ul.ftp_explorer").menu();
            });
        </script>
        <div class="ftp_explorer">
            <?php
            $this->explore("/");
            ?>
        </div>
        <?php
    }

    /**
     * Explore le serveur FTP et affiche son arborescence
     * @param string $dir dossier FTP à explorer 
     */
    private function explore($dir) {
        if ($nlist = ftp_nlist($this->_ftp_connect, $dir)) {
            ?>            
            <ul class="ftp_explorer">
                <?php
                foreach ($nlist as $file) {
                    ?>
                    <li>
                        <?php
                        $name = strtr($dirorfile, array("../" => "", "./" => "", $dir . "/" => ""));
                        if (@ftp_chdir($this->_ftp_connect, $file)) {
                            $id = strtr($file, array(" " => "", "/" => "_"));
                            ?>
                            <a href="#<?php echo $id; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>"><?php echo html_structures::glyphicon("folder-open", "Dossier") . " " . $name; ?></a>
                            <?php
                            $this->explore($file . "/");
                        } else {
                            ?>
                            <a target="_blank" href="ftp://<?php echo $this->_ftp_data["user"] . ":" . $this->_ftp_data["psw"] . "@" . $this->_ftp_data["host"] . $file ?>"><?php echo html_structures::glyphicon("file", "Fichier") . " " . $name; ?></a>
                            <?php
                        }
                        ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
        }
    }

}
