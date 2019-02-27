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
        new entity_generator([
            ["id", "int", true],
            ["heur", "string", false],
            ["contenu", "string", false],
            ["emet", "user", false],
            ["dest", "user", false],
            ["supp", "int", false]
                ], "message");
        $this->sub_menu();
    }

    /**
     * Affiche le sous-menu de la messagerie
     */
    private function sub_menu() {
        $route = [
            ["action" => "get", "title" => "Boite de réception", "text" => "BOITE DE RECEPTION"],
            ["action" => "write", "title" => "Ecrire un message", "text" => "ECRIRE UN MESSAGE"],
            ["action" => "send", "title" => "Message envoyé", "text" => "MESSAGE ENVOYE"]
        ];
        (new sub_menu($this, $route, "action", "get"));
    }

    /**
     * Vue de l'envoie de message
     */
    public function write() {
        if (!isset($_GET["dest"])) {
            $_GET["dest"] = 0;
        } else {
            $_GET["dest"] = ((int) $_GET["dest"]);
        }
        $user = $this->_table_user;
        
        $users = $user::get_table_array("id!='" . application::$_bdd->protect_var(session::get_user()) . "'");
        $option = [];
        foreach ($users as $value) {
            $option[] = [$value["id"], $value[$this->_tuple_user], ($value["id"] == $_GET["dest"])];
        }
        $form=new form();
        $form->select("Envoyer a", "dest", $option);
        $form->textarea("Message", "msg");
        $form->submit("btn-default", "");
        echo $form->render();
        if (isset($_POST["dest"])) {
            message::ajout(date("Y-m-d H:i:s"), $_POST["msg"], session::get_user(), $_POST["dest"], 0);
        }
    }

    /**
     * Vue de la boite de réception
     */
    public function get() {
        if (isset($_GET["action"]) and isset($_GET["id"]) and $_GET["action"] == "supp") {
            if (message::get_count("dest='" . application::$_bdd->protect_var(session::get_user()) . "' and id='" . application::$_bdd->protect_var(((int) $_GET["id"])) . "'") != 0) {
                $msg = message::get_from_id(((int) $_GET["id"]));
                $msg->set_supp(1);
            }
            js::redir("index.php?page={$_GET["page"]}&action=get");
        } else {
            $msg = message::get_collection("dest='" . application::$_bdd->protect_var(session::get_user()) . "' and supp=0");
            $data = [];
            if ($msg) {
                foreach ($msg as $value) {
                    $date = explode(" ", $value->get_heur());
                    $data[] = [html_structures::time($value->get_heur(), $this->_time->convert_date($date[0]) . "<br />" . $date[1]), $value->get_emet()->get_login(), $value->get_contenu(), html_structures::a_link("index.php?page=" . $_GET["page"] . "&action=get&action=supp&id=" . $value->get_id(), html_structures::glyphicon("remove", "Supprimer"), "btn btn-xs btn-danger btn_supp")];
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
            <?php

            echo tags::tag("div", ["class" => "datatable"], html_structures::table(["date", "Emetteur", "Message", "Supprimer"], $data, "Messages reÃ§us", "datatable"));
        }
    }

    /**
     * Vue de la boite d'envoi
     */
    public function send() {
        $msg = message::get_collection("emet='" . application::$_bdd->protect_var(session::get_user()) . "';");
        $data =[];
        if ($msg) {
            foreach ($msg as $value) {
                $date = explode(" ", $value->get_heur());
                $data[] = [html_structures::time($value->get_heur(), $this->_time->convert_date($date[0]) . "<br />" . $date[1]), $value->get_dest()->get_login(), $value->get_contenu()];
            }
        }
        js::datatable();
        echo tags::tag("div", ["class" => "datatable"], html_structures::table(array("date", "Destinataire", "Message"), $data, "Messages envoyé", "datatable"));
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
