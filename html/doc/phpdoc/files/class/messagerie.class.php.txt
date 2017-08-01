<?php

/**
 * Cette classe gère la messagerie, elle permet d'envoyer, de réceptionner ou de supprimer un message.
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class messagerie {

    /**
     * Instance de time
     * @var time Instance de time
     */
    private $_time;

    /**
     * Nom de la table utilisateurs
     * @var string Nom de la table utilisateurs
     */
    private $_table_user;

    /**
     * Nom du tuble de login/pseudo/nom de la table utilisateur
     * @var string Nom du tuble de login/pseudo/nom de la table utilisateur
     */
    private $_tuple_user;

    /**
     * Cette classe gère la messagerie, elle permet d'envoyer, de réceptionner ou de supprimer un message. 
     * 
     * @param string $table_user Nom de la table utilisateurs
     * @param string $tuple_user Nom du tuble de login/pseudo/nom de la table utilisateur
     */
    public function __construct($table_user, $tuple_user) {
        $this->_time = new time();
        $this->_table_user = $table_user;
        $this->_tuple_user = $tuple_user;
        $data = array(
            array("id", "int", true),
            array("heur", "string", false),
            array("contenu", "string", false),
            array("emet", "user", false),
            array("dest", "user", false),
            array("supp", "int", false),
        );
        new entity_generator($data, "message", true, true);
        $this->sub_menu();
    }

    /**
     * Affiche le sous-menu de la messagerie
     */
    private function sub_menu() {
        $route = array(
            array("action" => "write", "title" => "Ecrire un message", "text" => "ECRIRE UN MESSAGE"),
            array("action" => "get", "title" => "Boite de réception", "text" => "BOITE DE RECEPTION"),
            array("action" => "send", "title" => "Message envoyé", "text" => "MESSAGE ENVOYE")
        );
        if (!isset($_GET["action"])) {
            $_GET["action"] = "get";
        }
        ?>
        <ul class="nav nav-tabs">
            <?php
            foreach ($route as $value) {
                ?>
                <li <?php
                if ($_GET["action"] == $value["action"]) {
                    ?>class="active"<?php
                    }
                    ?>><a href="index.php?page=<?php echo $_GET["page"] . "&amp;action=" . $value["action"]; ?>" title="<?php echo $value["title"] ?>"><?php echo $value["text"]; ?></a></li>
                    <?php
                }
                ?>
        </ul>
        <?php
        $action_finded = false;
        foreach ($route as $value) {
            if ($_GET["action"] == $value["action"]) {
                $action_finded = true;
                $v = $value["action"];
                $this->$v();
            }
        }
        if (!$action_finded) {
            $this->get();
        }
    }

    /**
     * Vue de l'envoie de message
     */
    private function write() {
        if (!isset($_GET["dest"])) {
            $_GET["dest"] = 0;
        } else {
            $_GET["dest"] = ((int) $_GET["dest"]);
        }
        $user = $this->_table_user;
        $users = $user::get_table_array("id!='" . application::$_bdd->protect_var($_SESSION[config::$_prefix . "_user"]) . "';");
        $option = array();
        foreach ($users as $value) {
            $option[] = array($value["id"], $value[$this->_tuple_user], ($value["id"] == $_GET["dest"]));
        }
        form::new_form();
        form::select("Envoyer a", "dest", $option);
        form::textarea("Message", "msg");
        form::submit("btn-default", "");
        form::close_form();
        if (isset($_POST["dest"])) {
            message::ajout(date("Y-m-d H:i:s"), $_POST["msg"], $_SESSION[config::$_prefix . "_user"], $_POST["dest"], 0);
        }
    }

    /**
     * Vue de la boite de réception
     */
    private function get() {
        if (isset($_GET["action"]) and isset($_GET["id"]) and $_GET["action"] == "supp") {
            if (message::get_count("dest='" . application::$_bdd->protect_var($_SESSION[config::$_prefix . "_user"]) . "' and id='" . application::$_bdd->protect_var(((int) $_GET["id"])) . "';") != 0) {
                $msg = message::get_from_id(((int) $_GET["id"]));
                $msg->set_supp(1);
            }
            js::redir("index.php?page=" . $_GET["page"] . "&amp;action=get");
        } else {
            $msg = message::get_collection("dest='" . application::$_bdd->protect_var($_SESSION[config::$_prefix . "_user"]) . "' and supp=0;");
            $data = array();
            if ($msg) {
                foreach ($msg as $value) {
                    $date = explode(" ", $value->get_heur());
                    $data[] = array(html_structures::time($value->get_heur(), $this->_time->convert_date($date[0]) . "<br />" . $date[1]), $value->get_emet()->get_login(), $value->get_contenu(), html_structures::a_link("index.php?page=" . $_GET["page"] . "&amp;action=get&amp;action=supp&amp;id=" . $value->get_id(), html_structures::glyphicon("remove", "Supprimer"), "btn btn-xs btn-danger btn_supp"));
                }
            }
            js::datatable();
            ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $(".btn_supp").click(function () {
                        return confirm("Estes vous sur de vouloir supprimer ce message ?");
                    });
                });
            </script>
            <div class="datatable">
                <?php
                echo html_structures::table(array("date", "Emetteur", "Message", "Supprimer"), $data, "Messages reçus", "datatable");
                ?>
            </div>
            <?php
        }
    }

    /**
     * Vue de la boite d'envoi
     */
    private function send() {
        $msg = message::get_collection("emet='" . application::$_bdd->protect_var($_SESSION[config::$_prefix . "_user"]) . "';");
        $data = array();
        if ($msg) {
            foreach ($msg as $value) {
                $date = explode(" ", $value->get_heur());
                $data[] = array(html_structures::time($value->get_heur(), $this->_time->convert_date($date[0]) . "<br />" . $date[1]), $value->get_dest()->get_login(), $value->get_contenu());
            }
        }
        js::datatable();
        ?>
        <div class="datatable">
            <?php
            echo html_structures::table(array("date", "Destinataire", "Message"), $data, "Messages envoyé", "datatable");
            ?>
        </div>
        <?php
    }

    /**
     * Fonction de nettoyage automatique des messages
     * @param string $table_msg Nom de la table des messages
     * @param int $years Durée de vie (en années) des messages avant suppression (2 par defaut)
     */
    public static function purge_msg($table_msg, $years = 2) {
        $today_years = (date("Y") - $years) . "-" . date("m-d H:i:s");
        application::$_bdd->query("delete from " . application::$_bdd->protect_var($table_msg) . " where heur<='" . $today_years . "';");
    }

}
