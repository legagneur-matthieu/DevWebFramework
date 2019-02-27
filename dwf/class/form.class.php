<?php

/**
 * Cette classe gère la cration et l'affichage d'un formulaire
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class form {

    /**
     * Formulaire
     * @var tags Formulaire
     */
    private $_form;

    /**
     * Créé un nouveau formulaire
     * @param string $class Classe CSS
     * @param string $action Action du formulaire
     * @param string $method Methode du formulaire
     */
    public function __construct($class = "", $action = "#", $method = "post") {
        $this->_form = tags::form(["class" => $class, "action" => $action, "method" => $method], "");
    }

    /**
     * Retourne la balise d'ouverture du formulaire
     * @return string Balise d'ouverture du formulaire
     */
    public function get_open_form() {
        return "<form class=\"{$this->_form->get_attr("class")}\" action=\"{$this->_form->get_attr("action")}\" class=\"{$this->_form->get_attr("class")}\" method=\"{$this->_form->get_attr("method")}\">";
    }

    /**
     * Retourne la balise de fermeture du formulaire et eventuelement un token
     * @param boolean $use_token Ajouter un token (true/false, false par defaut)
     * @return string Balise de fermeture du formulaire
     */
    public function get_close_form($use_token = false) {
        return ($use_token ? $this->token() : "") . "</form>";
    }

    /**
     * Retourne la balise d'ouverture d'un fieldset
     * @return string Balise d'ouverture d'un fieldset
     */
    public function open_fieldset($legend) {
        return $this->append("<fieldset><legend>{$legend}</legend>");
    }

    /**
     * Retourne la balise de fermeture d'un fieldset
     * @return string Balise de fermeture d'un fieldset
     */
    public function close_fieldset() {
        return $this->append("</fieldset>");
    }

    /**
     * Ajoute un élément au formulaire (string ou tags)
     * @param string|tags $item l'élément
     * @return string l'élément
     */
    private function append($item) {
        $this->_form->append_content($item);
        return $item;
    }

    /**
     * Donne le focus automatiquement à un élément 
     * @param string $name Id CSS de l'élément à focus
     * @return string Script de focus
     */
    public function focus($name) {
        return $this->append(tags::tag("script", ["type" => "text/javascript"], "$(document).ready(function () { document.getElementById('{$name}').focus()});"));
    }

    /**
     * Ajoute et retourne un champ input
     * @param string $label Label de l'input
     * @param string $name Nom de l'input
     * @param string $type Type de l'input (text par défaut)
     * @param string $value Valeur de l'input (null par défaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Classe CSS
     * @param null|string $list Datalist (null par défaut, sinon indiquer l'id de la dataliste)
     * @return string Le champ input
     */
    public function input($label, $name, $type = "text", $value = null, $required = true, $class = "", $list = null) {
        $attr = ["id" => $name, "name" => $name, "type" => $type, "class" => "form-control"];
        if ($value !== null) {
            $attr["value"] = $value;
        }
        if ($list !== null) {
            $attr["list"] = $list;
        }
        if ($required) {
            $attr["required"] = "required";
        }
        return $this->append(tags::tag("div", ["class" => "form-group {$class}"], tags::tag("label", ["for" => $name], $label) . tags::tag("input", $attr, false)));
    }

    /**
     * Créé et retourne une dataliste associé a un input
     * @param string $list Id de la liste
     * @param array $data array(valeur1, valeur2, ... );
     * @return string La dataliste
     */
    public function datalist($list, $data) {
        $options = "";
        foreach ($data as $value) {
            $options .= tags::tag("option", ["label" => $value["label"], "value" => $value["value"]], false);
        }
        return $this->append(tags::tag("datalist", ["id" => $list], $options));
    }

    /**
     * Créé et retourne un input de type file     * 
     * @param string $label Label de l'input
     * @param string $name Nom de l'input
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Classe css
     * @param boolean $multiple Upload de fichiers multiples ? (true/false, false par défaut)
     * @return string
     */
    public function file($label, $name, $required = true, $class = "", $multiple = false) {
        $attr = ["id" => $name, "name" => $name, "type" => "file"];
        if ($multiple) {
            $attr["name"] .= "[]";
            $attr["multiple"] = "true";
        }
        if ($required) {
            $attr["required"] = "required";
        }
        $script = tags::tag("script", ["type" => "text/javascript"], "$(document).ready(function () { $(\"#{$name}\").parents(\"form\").attr(\"enctype\", \"multipart/form-data\");});");
        return $this->append(tags::tag("div", ["class" => "form-group {$class}"], tags::tag("label", ["for" => $name], $label) . tags::tag("input", $attr, false)) . $script);
    }

    /**
     * Récupère le fichier upload dans un input de type file (avec le $multiple = false)
     * 
     * @param string $name Name de l'input à récupérer
     * @param string $path Chemin d'accès où doit être stocké le fichier ( ne pas terminer le path par un / )
     * @param array $type Liste des types tolérés pour l'upload exemple : ["image/jpg", "image/png"]
     * @param null|string $fname Renomme le fichier pendant l'upload
     * @return array Retourne un tableau avec la clé "error"=true/false si il y a eu une erreur ou non , si il y a eu une erreur alors le nom du fichier responsable est dans la clé "doc"
     */
    public static function get_upload($name, $path, $type, $fname = null) {
        $doc = "";
        $error = false;
        if (isset($_FILES[$name]["name"]) and $_FILES[$name]["size"] != 0) {
            if (in_array($_FILES[$name]["type"], $type)) {
                if ($fname != null) {
                    $_FILES[$name]["name"] = $fname;
                }
                $_FILES[$name]["name"] = strtr($_FILES[$name]["name"], form::get_acii());
                if (move_uploaded_file($_FILES[$name]["tmp_name"], "{$path}/{$_FILES[$name]["name"]}")) {
                    $doc = $_FILES[$name]["name"];
                } else {
                    $error = true;
                    js::alert("Erreur lors de l'upload du fichier !");
                }
            } else {
                $error = true;
                js::alert("Le fichier n'est pas du bon type !");
            }
        }
        return array("doc" => $doc, "error" => $error);
    }

    /**
     * Récupère le fichier upload dans un input de type file (avec le $multiple = true)
     * 
     * @param string $name Name de l'input à récupérer
     * @param string $path Chemin d'accès où doit être stocké le fichier ( ne pas terminer le path par un / )
     * @param array $type Liste des types tolérés pour l'upload exemple : ["image/jpg", "image/png"]
     * @param null|string $fname Renomme le fichier pendant l'upload
     * @return array Retourne un tableau avec la clé "error"=true/false si il y a eu une erreur ou non , si il y a eu une erreur alors le nom du fichier responsable est dans la clé "doc"
     */
    public static function get_multi_upload($name, $path, $type, $fname = null) {
        $i = 0;
        $doc = "";
        $error = false;
        while (isset($_FILES[$name]["name"][$i]) and $_FILES[$name]["size"][$i] != 0) {
            if (in_array($_FILES[$name]["type"][$i], $type)) {
                if ($fname != null) {
                    $_FILES[$name]["name"][$i] = "{$i}_{$fname}";
                }
                $_FILES[$name]["name"][$i] = strtr($_FILES[$name]["name"][$i], form::get_acii());
                if (move_uploaded_file($_FILES[$name]["tmp_name"][$i], "{$path}/{$_FILES[$name]["name"][$i]}")) {
                    $doc[] = $_FILES[$name]["name"][$i];
                } else {
                    $error = true;
                    js::alert("erreur lors de l'upload du ficchier {$i}");
                }
            } else {
                $error = true;
                js::alert("le fichier {$i} n'est pas du bon type !");
            }
            $i++;
        }
        return array("doc" => $doc, "error" => $error);
    }

    /**
     * Cette fonction renomme et redimensionne les photos envoyées.
     * @param $img String Chemin absolu de l'image d'origine.
     * @param $to String Chemin absolu de l'image générée (.png).
     * @param $width Int Largeur de l'image générée. Si 0, valeur calculée en fonction de $height.
     * @param $height Int Hauteur de l'image génétée. Si 0, valeur calculée en fonction de $width.
     * Si $height = 0 et $width = 0, dimensions conservées mais conversion en .png
     * @param boolean $alpha Garder la transparence du PNG ? (true/false, true par defaut)
     * @param int $color Couleur de transition à utiliser pour la transparence, a générer avec imagecolorallocate() (blanc par défaut) 
     * @return boolean
     */
    public static function resize_img($img, $to, $width = 0, $height = 0, $alpha = true, $color = 16777215) {
        $dimensions = getimagesize($img);
        $ratio = $dimensions[0] / $dimensions[1];
        if ($width == 0 and $height == 0) {
            $width = $dimensions[0];
            $height = $dimensions[1];
        } elseif ($height == 0) {
            $height = round($width / $ratio);
        } elseif ($width == 0) {
            $width = round($height * $ratio);
        } elseif ($width != 0 and $height != 0) {
            return false;
        }
        $dimX = $width;
        $dimY = $height;
        $decalX = 0;
        $decalY = 0;
        if ($dimensions[0] > ($width / $height) * $dimensions[1]) {
            $dimX = round($height * $dimensions[0] / $dimensions[1]);
            $decalX = ($dimX - $width) / 2;
        }
        if ($dimensions[0] < ($width / $height) * $dimensions[1]) {
            $dimY = round($width * $dimensions[1] / $dimensions[0]);
            $decalY = ($dimY - $height) / 2;
        }
        $pattern = imagecreatetruecolor($width, $height);
        if ($alpha) {
            imagefilledrectangle($pattern, 0, 0, $width, $height, $color);
            imagecolortransparent($pattern, $color);
        }
        switch (substr(mime_content_type($img), 6)) {
            case 'jpeg':
                $image = imagecreatefromjpeg($img);
                break;
            case 'gif':
                $image = imagecreatefromgif($img);
                break;
            case 'png':
                $image = imagecreatefrompng($img);
                break;
        }
        imagecopyresampled($pattern, $image, 0, 0, 0, 0, $dimX, $dimY, $dimensions[0], $dimensions[1]);
        imagedestroy($image);
        imagepng($pattern, $to);
        return true;
    }

    public function token() {
        return $this->hidden("token", self::get_token());
    }
    
    /**
     * Retourne un token à partir de l'algo de hash, de l'ip et du navigateur enregistré en session
     * @return string Token
     */
    public static function get_token() {
        return hash(config::$_hash_algo, session::get_val("ip") . session::get_val("browser"));
    }

    /**
     * Retourne si le token d'un formulaire est conforme à la session en cours
     * @return boolean True si le token est conforme sinon false
     */
    public static function validate_token() {
        return (isset($_POST["token"]) and $_POST["token"] == self::get_token() and $_SERVER["REMOTE_ADDR"] == session::get_val("ip") and $_SERVER["HTTP_USER_AGENT"] == session::get_val("browser"));
    }

    /**
     * Créé et retourne un input hidden     * 
     * @param string $name Name de l'input
     * @param string $value Valeur de l'input
     * @return string L'input de type hidden
     */
    public function hidden($name, $value) {
        return $this->append(tags::tag("div", ["class" => "form-group"], tags::tag("input", ["type" => "hidden", "name" => $name, "id" => $name, "value" => $value], false)));
    }

    /**
     * Créé et retourne une checkbox     * 
     * @param string $label Label de la checkbox
     * @param string $name Nom de la checkbox
     * @param string $value Valeur de la checkbox
     * @param string $class Classe CSS
     * @param boolean $checked Case cochée par défaut ? true/false (false par defaut)
     * @return string La checkbox
     */
    public function checkbox($label, $name, $value, $class = "", $checked = false) {
        $attr = ["id" => $name, "name" => $name, "type" => "checkbox", "value" => $value];
        if ($checked) {
            $att["checked"] = "checked";
        }
        return $this->append(tags::tag("div", ["class" => "form-group checkbox {$class}"], tags::tag("label", ["for" => $name], tags::tag("input", $attr, false) . $label)));
    }

    /**
     * Créé et retourne un groupe de boutons radios     * 
     * @param string $label Label du groupe de radios
     * @param string $name Nom du groupe de radios
     * @param array $radios array(array(value,text,[selected]),...);
     * @param string $class Classe CSS
     * @return string Les boutons radios
     */
    public function radios($label, $name, $radios, $class = "") {
        $divs = "";
        foreach ($radios as $value) {
            $attr = ["id" => $name . $value[0], "name" => $name, "type" => "radio", "value" => $value[0]];
            if (isset($value[2]) and $value[2] == true) {
                $attr["checked"] = "checked";
            }
            $divs .= tags::tag("div", ["class" => "radio"], tags::tag("label", [], tags::tag("input", $attr, false) . $value[1]));
        }
        return $this->append(tags::tag("div", ["class" => "form_radio {$class}"], tags::tag("fieldset", [], tags::tag("legend", [], $label) . $divs)));
    }

    /**
     * Retourne le script nécéssaire a faire fonctioner le datepicker
     * @param string $name Nom du datepicker
     * @param string $fn Fonction du datepiker
     * @return string Le script nécéssaire a faire fonctioner le datepicker
     */
    private function datepicker_script($name, $fn) {
        return $this->append(tags::tag("script", ["type" => "text/javascript"], "$(document).ready(function () { $(\"#{$name}\").{$fn}($.timepicker.regional[\"fr\"]).{$fn}({dateFormat: \"dd/mm/yy\"}).attr(\"readonly\", true).attr(\"placeholder\", \"Cliquez pour choisir une date\");});"));
    }

    /**
     * Créé et retourne le datetimepicker de jquery-ui (date + heur)     * 
     * @param string $label Label du datetimepicker
     * @param string $name Nom du datetimepicker
     * @param null|string $value Valeur (null par defaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Classe CSS
     * @return string le datetimepicker
     */
    public function datetimepicker($label, $name, $value = null, $required = true, $class = "") {
        return $this->input($label, $name, "text", $value, $required, $class) . $this->datepicker_script($name, __FUNCTION__);
    }

    /**
     * Récupère la valeur du datetimepicker au format US     * 
     * @param string $name Name
     * @return string|boolean Date US ou false
     */
    public static function get_datetimepicker_us($name) {
        $date = explode(" ", $_POST[$name]);
        $time = $date[1];
        $date = explode("/", $date[0]);
        return (isset($date[2]) ? $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $time : false);
    }

    /**
     * Créé et retourne datepicker de jquery-ui (date seulement)     * 
     * @param string $label Label du datepicker
     * @param string $name Nom du datepicker
     * @param null|string $value Valeur (null par defaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Classe CSS
     * @return string le datepicker
     */
    public function datepicker($label, $name, $value = null, $required = true, $class = "") {
        return $this->input($label, $name, "text", $value, $required, $class) . $this->datepicker_script($name, __FUNCTION__);
    }

    /**
     * Récupère la valeur du datepicker au format US     * 
     * @param string $name Name
     * @return string|boolean Date US ou false
     */
    public static function get_datepicker_us($name) {
        $date = explode("/", $_POST[$name]);
        return (isset($date[2]) ? $date[2] . "-" . $date[1] . "-" . $date[0] : false);
    }

    /**
     * Créé et retourne le timepicker de jquery-ui (heur seulement)    * 
     * @param string $label Label du timepicker
     * @param string $name Nom du timepicker
     * @param null|string $value Valeur (null par defaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Classe CSS
     * @return string le timepicker
     */
    public function timepicker($label, $name, $value = null, $required = true, $class = "") {
        return $this->input($label, $name, "text", $value, $required, $class) . $this->datepicker_script($name, __FUNCTION__);
    }

    /**
     * Créé et retourne le bouton submit du formulaire     * 
     * @param string $class Classe CSS
     * @param string $value Texte du bouton (null par default, dépend du navigateur client)
     * @return string Le bouton submit du formulaire
     */
    public function submit($class, $value = null) {
        $attr = ["type" => "submit", "class" => "btn {$class}"];
        if ($value != null) {
            $attr["value"] = $value;
        }
        return $this->append(tags::tag("div", ["class" => "form-group"], tags::tag("input", $attr, false)));
    }

    /**
     * Créé et retourne un sélecteur (balise select) <br />
     * les options y sont renseignés par un tableau à deux dimensions (cf $option)
     * 
     * @param string $label Label du selecteur
     * @param string $name Nom du selecteur
     * @param array $option array(array(value,text,[selected]),...); ou array("group"=>array(array(value,text,[selected])),...);
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Classe CSS
     * @return string le selecteur
     */
    public function select($label, $name, $option, $required = true, $class = "") {
        $attr = ["id" => $name, "name" => $name, "class" => "form-control"];
        if ($required) {
            $attr["required"] = "required";
        }
        return $this->append(tags::tag("div", ["class" => "form-group {$class}"], tags::tag("label", ["for" => $name], $label) . tags::tag("select", $attr, $this->options($option))));
    }

    /**
     * Créé et retourne les options des selecteurs
     * @param array $option Options du selecteur
     * @return string Options
     */
    private function options($option) {
        $options = "";
        foreach ($option as $key => $value) {
            if (!is_int($key)) {
                $options .= tags::tag("optgroup", ["label" => $key], $this->options($value));
            } else {
                $attr = ["value" => $value[0]];
                if (isset($value[2]) and $value[2] == true) {
                    $attr["selected"] = "selected";
                }
                $options .= tags::tag("option", $attr, $value[1]);
            }
        }
        return $options;
    }

    /**
     * Créé et retourne un textarea
     * 
     * @param string $label Label du textarea
     * @param string $name Nom du textarea
     * @param null|string $value Valeur (null par defaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Classe CSS
     * @param int $cols cols, Taille en x du textarea
     * @param int $rows rows, Taille en y du textarea
     * @return string Le textarea
     */
    public function textarea($label, $name, $value = " ", $required = true, $class = "", $cols = 30, $rows = 10) {
        $attr = ["name" => $name, "id" => $name, "cols" => $cols, "rows" => $rows, "class" => "form-control"];
        if ($required) {
            $attr["required"] = "required";
        }
        return $this->append(tags::tag("div", ["class" => "form-group {$class}"], tags::tag("label", ["for" => $name], $label) . tags::tag("div", [], tags::tag("textarea", $attr, $value))));
    }

    /**
     * Créé et retourne un champs de formulaire pour les signatures numeriques     * 
     * @param string $id id CSS pour jSignature
     * @param string $label Label de la jSignature
     * @param string $dataformat Format de donné returné : svgbase64 (defaut), svg ou base30
     * @return string La jSignature
     */
    public function jSignature($id, $label = "Signature", $dataformat = "svgbase64") {
        return $this->append((new jSignature($id, $label, $dataformat))->render());
    }

    /**
     * Retourne l'integalité du formulaire HTML
     * @return string Le formulaire HTML
     */
    public function render() {
        return (string) $this->_form;
    }

}
