<?php

/**
 * Cette classe permet de gérer des traductions sur le principe de "clés" afin de créer facilement des applications multilingues 
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class trad {

    /**
     * Sigle de la langue de l'utilisateur
     * @var string Sigle de la langue de l'utilisateur
     */
    private $_lang;

    /**
     * Système utilisé pour gérer les traductions ( SQL ou JSON )
     * @var string Système utilisé pour gérer les traductions ( SQL ou JSON )
     */
    private $_type;

    /**
     * Cette classe permet de gérer des traduction sur le principe de "clés" afin de créer facilement des applications multilingues 
     * 
     * @param string $lang_default Langue par défaut de l'utilisateur ( fr par defaut )
     * @param string $type Type de système utilisé pour les traductions (sql par defaut, ou json)
     */
    public function __construct($lang_default = "fr", $type = "sql") {
        if (!session::get_lang()) {
            session::set_lang($lang_default);
        }
        $this->_type = $type;
        switch ($this->_type) {
            case "sql":
                new entity_generator(array(array("id", "int", true), array("keyword", "string", false), array("lang", "string", false), array("texte", "string", false)), "lang_sub", true, true);
                $lang = lang_sub::get_table_array("lang='" . application::$_bdd->protect_var(session::get_lang()) . "'");
                foreach ($lang as $value) {
                    $this->_lang[$value["keyword"]][$value["texte"]];
                }
                break;
            case "json":
                $file = "lang/" . session::get_lang() . ".json";
                (!is_dir("lang") ? mkdir("lang") : dwf_exception::check_file_writed($file));
                $this->_lang = json_decode(file_get_contents($file), true);
                break;
            default:
                break;
        }
    }

    /**
     * Traduit un texte en fonction de la clé passée en parametre et la langue de l'utilisateur 
     * 
     * @param string $key Clé de traduction
     * @return string Tradution associée selon la langue choisie 
     */
    public function t($key) {
        return ((isset($this->_lang[$key]) or ! empty($this->_lang[$key]) ) ? $this->_lang[$key] : $key);
    }

    /**
     * Affiche l'interface d'administration pour gérer les traductions
     */
    public function admin() {
        switch ($this->_type) {
            case "sql":
                new admin_controle("lang_sub");
                break;
            case "json":
                if (isset($_GET["lang"])) {
                    if (isset($_POST["trad_form"])) {
                        $data = array();
                        foreach ($_POST["trad"] as $value) {
                            if (!empty($value["key"])) {
                                $data[$value["key"]] = $value["value"];
                            }
                        }
                        file_put_contents("lang/" . $_GET["lang"] . ".json", json_encode($data));
                        js::alert("Le fichier de traduction a bien été mis à jour");
                        js::redir(application::get_url(array("lang")));
                    } else {
                        if (file_exists($file = "lang/" . $_GET["lang"] . ".json")) {
                            $lang = json_decode(file_get_contents($file), true);
                            //js::datatable();
                            ?>
                            <h2>Fichier de traduction <?php echo $_GET["lang"]; ?></h2>
                            <?php
                            form::new_form("form_trad");
                            form::hidden("trad_form", 1);
                            ?>
                            <style type="text/css">
                                .form_trad{
                                    width: 900px;
                                }
                                #datatable{
                                    margin: 0 auto;
                                }
                                #datatable>tbody>tr>td>input{
                                    width: 300px;
                                }
                                #datatable>tbody>tr>td+td>input{
                                    width: 600px;

                                }
                            </style>
                            <table id="datatable">
                                <thead>
                                    <tr>
                                        <th>Keyword</th>
                                        <th>Texte</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($lang as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><input type="text" name="trad[<?php echo $i; ?>][key]" value="<?php echo $key; ?>"</td>
                                            <td><input type="text" name="trad[<?php echo $i; ?>][value]" value="<?php echo $value; ?>"</td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    <tr>
                                        <td><input type="text" name="trad[<?php echo $i; ?>][key]" /></td>
                                        <td><input type="text" name="trad[<?php
                                            echo $i;
                                            $i++;
                                            ?>][value]" /></td>
                                    </tr>
                                </tbody>
                            </table>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    i = <?php echo $i; ?>;
                                    $("#addkey").click(function (e) {
                                        e.preventDefault();
                                        $("#datatable>tbody").append('<tr><td><input type="text" name="trad[' + i + '][key]" /></td><td><input type="text" name="trad[' + i + '][value]" /></td></tr>');
                                        i++;
                                    });
                                });
                            </script>
                            <a href="#" id="addkey">Ajouter une clé</a>
                            <?php
                            form::submit('btn-default');
                            form::close_form();
                        } else {
                            js::alert("Le fichier de traduction n'existe pas !");
                            js::redir(application::get_url(array("lang")));
                        }
                    }
                } else {
                    ?>
                    <ul>
                        <?php
                        foreach (glob("lang/*.json") as $value) {
                            $v = explode("lang/", $value);
                            $v = strtr($v[1], array(".json" => ""));
                            ?>
                            <li><a href="<?php echo application::get_url(array("lang")) . "lang=" . $v; ?>"><?php echo $v ?></a></li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                    form::new_form();
                    form::input("Langue (Sigle, exemple : en, es, it, ru, ...)", "add_lang");
                    form::submit("btn-default", "Ajouter");
                    form::close_form();
                    if (isset($_POST["add_lang"])) {
                        $file = "lang/" . ($add_lang = strtr($_POST["add_lang"], array("." => "", "/" => "", "\\" => "", "'" => "", '"' => ""))) . ".json";
                        if (!file_exists($file)) {
                            file_put_contents($file, "{}");
                            js::alert("Le fichier de traduction a bien été créé");
                            js::redir(application::get_url() . "lang=" . $add_lang);
                        } else {
                            js::alert("Le fichier de traduction existe déjà !");
                            js::redir(application::get_url() . "lang=" . $add_lang);
                        }
                    }
                }
                break;
            default:
                break;
        }
    }

}
