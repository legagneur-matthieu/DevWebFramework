<?php

/**
 * (Tests incomplets)
 * Cette classe g�re les "sitemap" du site <br />
 * Pour les routes qui d�pendent d'une variable, renseignez dans la route (par exemple): <br />
 * "sitemap" => array("var" => "id", "entity" => "user", "tuple" => "login")
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.fr>
 */
class sitemap {

    /**
     * Liste des sitemap
     * @var array|stdClass Liste des sitemap
     */
    private $_sitemap;

    /**
     * Chemain relatif du sitemap JSON
     * @var string Chemain relatif du sitemap JSON
     */
    private static $_file_json = "./sitemap.json";

    /**
     * Chemain relatif du sitemap XML
     * @var string Chemain relatif du sitemap XML
     */
    private static $_file_xml = "./sitemap.xml";

    /**
     * Cette classe g�re les "sitemap" du site
     */
    public function __construct() {
        if (config::$_sitemap) {
            if (!isset($_SERVER["REQUEST_SCHEME"])) {
                $schame = explode("://", $_SERVER["HTTP_REFERER"]);
                $_SERVER["REQUEST_SCHEME"] = $schame[0];
            }
            $host = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"];
            foreach (config::$_route_unauth as $route) {
                if (isset($route["sitemap"]["var"]) and isset($route["sitemap"]["entity"]) and isset($route["sitemap"]["tuple"])) {
                    $node = $route["sitemap"]["entity"];
                    $node = $node::get_table_array(application::$_bdd);
                    foreach ($node as $value) {
                        $this->_sitemap[] = array("loc" => $host . "?page=" . $route["page"] . "&amp;" . $route["sitemap"]["var"] . "=" . $value["id"], "text" => $value[$route["sitemap"]["tuple"]], "cat" => $route["title"]);
                    }
                } else {
                    if (isset($route["text"])) {
                        $this->_sitemap[] = array("loc" => $host . "?page=" . $route["page"], "text" => $route["title"]);
                    }
                }
            }
            $this->json();
        }
    }

    /**
     * G�n�re le fichier JSON pour le XML et la vue HTML
     */
    private function json() {
        $json = (is_file(self::$_file_json) ? json_decode(file_get_contents(self::$_file_json)) : array());
        $this->_sitemap["last_update"] = date("Ymd");
        if (!isset($json->last_update) or $json->last_update < $this->_sitemap["last_update"]) {
            file_put_contents(self::$_file_json, json_encode($this->_sitemap));
            dwf_exception::check_file_writed(self::$_file_json);
            $this->XML();
        }
    }

    /**
     * G�n�re le fichier XML
     */
    private function XML() {
        $xml_file = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($this->_sitemap as $value) {
            if (isset($value["loc"])) {
                $xml_file .= '<url><loc>' . $value["loc"] . '</loc></url>';
            }
        }
        $xml_file .= "</urlset>";
        file_put_contents(self::$_file_xml, $xml_file);
        dwf_exception::check_file_writed(self::$_file_xml);
    }

    /**
     * Vue HTML
     * @param boolean $show_xml Lien vers le sitemap XML
     * @param boolean $show_json Lien vers le sitemap JSON
     */
    public static function html($show_xml = true, $show_json = false) {
        if (is_file(self::$_file_json)) {
            $json = json_decode(file_get_contents(self::$_file_json));
            ?>
            <ul>
                <?php
                foreach ($json as $value) {
                    if (isset($value->loc)) {
                        ?>
                        <li><?php
                            echo(isset($value->cat) ? html_structures::a_link($value->loc, $value->cat . " - " . $value->text) : html_structures::a_link($value->loc, $value->text));
                            ?></li>
                        <?php
                    }
                }
                ?>
            </ul>
            <?php
            if ($show_json or $show_xml) {
                ?>
                <p><small>
                        <?php
                        if ($show_xml) {
                            echo html_structures::a_link("sitemap.xml", "Sitemap XML", "", "(nouvel onglet)", true) . " ";
                        }
                        if ($show_json) {
                            echo html_structures::a_link("sitemap.json", "Sitemap JSON", "", "(nouvel onglet)", true);
                        }
                        ?>
                    </small></p>
                <?php
            }
        } else {
            if (config::$_sitemap) {
                new sitemap();
                self::html();
            } else {
                new Exception("Sitemap non configur� !", 200);
            }
        }
    }

}
