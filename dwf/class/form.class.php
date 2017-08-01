<?php

/**
 * Cette classe gère l'affichage d'un formulaire
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class form {

    /**
     * REQUIERS JQUERY
     * Donne le focus automatiquement à un élément 
     * 
     * @param string $name Id CSS de l'élément à focus
     */
    public static function focus($name) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                document.getElementById('<?php echo $name; ?>').focus();
            });
        </script>
        <?php
    }

    /**
     * Affiche un input
     * 
     * @param string $label Label
     * @param string $name Name
     * @param string $type Type de l'input (text par défaut)
     * @param string $value Value de l'input (null par défaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Class css
     * @param null|string $list Datalist (null par défaut, sinon indiquer l'id de la dataliste)
     */
    public static function input($label, $name, $type = "text", $value = null, $required = true, $class = "", $list = null) {
        if (!empty($class)) {
            ?>
            <div class="<?php echo $class; ?>">            
                <?php
            }
            ?>
            <div class="form-group">
                <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
                <input id="<?php echo $name; ?>" type="<?php echo $type; ?>" name="<?php echo $name; ?>" <?php if ($value != null) { ?>value="<?php echo $value; ?>" <?php } ?> class="form-control" <?php if ($list != null) { ?>list="<?php echo $list; ?>" <?php } ?> <?php if ($required) { ?>required="required"<?php } ?>/>
            </div>
            <?php
            if (!empty($class)) {
                ?>
            </div>
            <?php
        }
    }

    /**
     * Créé une datalist pour un input
     * 
     * @param string $list Id de la liste
     * @param array $data array(array("label"=>"", "value"=>""), ... );
     */
    public static function datalist($list, $data) {
        ?>
        <datalist id="<?php echo $list ?>">
            <?php
            foreach ($data as $value) {
                ?>
                <option label="<?php echo $value["label"]; ?>" value="<?php echo $value["value"]; ?>" />
                <?php
            }
            ?>
        </datalist>
        <?php
    }

    /**
     * Affiche un input de type file     * 
     * @param string $label Label
     * @param string $name Name
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Class css
     * @param boolean $multiple Upload de fichiers multiples ? true/false (false par défaut)
     */
    public static function file($label, $name, $required = true, $class = "", $multiple = false) {
        if (!empty($class)) {
            ?>
            <div class="<?php echo $class; ?>">            
                <?php
            }
            ?>
            <div class="form-group">
                <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
                <input id="<?php echo $name; ?>" type="file" name="<?php
                echo $name;
                if ($multiple) {
                    ?>[]<?php } ?>" <?php if ($multiple) { ?>multiple="true" <?php } ?> <?php if ($required) { ?>required="required"<?php } ?>/>
            </div>
            <?php
            if (!empty($class)) {
                ?>
            </div>
            <?php
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#<?php echo $name; ?>").parents("form").attr("enctype", "multipart/form-data");
            });
        </script>
        <?php
    }

    /**
     * Récupère le fichier l'upload dans un input de type file (avec le $multiple = false)
     * 
     * @param string $name Name de l'input à récupérer
     * @param string $path Chemin d'accès où doit être stocké le fichier ( ne pas terminer le path par un / )
     * @param array $type Liste des types tolérés pour l'upload exemple : image/jpg
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
                if (move_uploaded_file($_FILES[$name]["tmp_name"], $path . "/" . $_FILES[$name]["name"])) {
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
     * Récupère les fichiers l'upload dans un input de type file (avec le $multiple = true)
     * 
     * @param string $name Name de l'input à récupérer
     * @param string $path Chemin d'accès où doit être stocké les fichiers ( ne pas terminer le path par un / )
     * @param array $type Liste des types tolérés pour l'upload exemple : image/jpg
     * @param null|string $fname Renomme les fichiers pendant l'upload
     * @return array Retourne un tableau avec la clé "error"=true/false si il y a eu une erreur ou non , si il y a eu une erreur alors le nom du fichier responsable est dans la clé "doc"
     */
    public static function get_multi_upload($name, $path, $type, $fname = null) {
        $i = 0;
        $doc = "";
        $error = false;
        while (isset($_FILES[$name]["name"][$i]) and $_FILES[$name]["size"][$i] != 0) {
            if (in_array($_FILES[$name]["type"][$i], $type)) {
                if ($fname != null) {
                    $_FILES[$name]["name"][$i] = $i . "_" . $fname;
                }
                $_FILES[$name]["name"][$i] = strtr($_FILES[$name]["name"][$i], form::get_acii());
                if (move_uploaded_file($_FILES[$name]["tmp_name"][$i], $path . "/" . $_FILES[$name]["name"][$i])) {
                    $doc[] = $_FILES[$name]["name"][$i];
                } else {
                    $error = true;
                    js::alert("erreur lors de l'upload du ficchier " . $i);
                }
            } else {
                $error = true;
                js::alert("le fichier " . $i . " n'est pas du bon type !");
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

    /**
     * Tableau contenant la table ascii et les substituts à utiliser dans le $from de la fonction php : strtr($str,$from);
     * Ce tableau est à administrer selon vos besoins
     * 
     * @return array Table ascci est caractères de substitution
     */
    public static function get_acii() {
        return array(
            "'" => ' ',
            '"' => ' ',
            '!' => '',
            '$' => '',
            '@' => '',
            '*' => '',
            '/' => '-',
            '+' => '',
            '.' => '.',
            ',' => '',
            ';' => '',
            ':' => '',
            '!' => '',
            '&' => '',
            '~' => '',
            '{' => '',
            '[' => '',
            '|' => ' ',
            '-' => '-',
            '`' => '',
            '^' => '',
            ']' => '',
            '}' => '',
            '=' => '',
            '€' => '',
            '$' => '',
            '¤' => '',
            'é' => 'e',
            'è' => 'e',
            'ç' => 'c',
            'à' => 'a',
            '¨' => '',
            'µ' => '',
            'ù' => 'u',
            '%' => '',
            '§' => '',
            '?' => '',
            'â' => 'a',
            'ê' => 'e',
            'ô' => 'o',
            'î' => 'i',
            'û' => 'u',
            'ä' => 'a',
            'ë' => 'e',
            'ö' => 'o',
            'ï' => 'i',
            'ü' => 'u',
            'ÿ' => 'y',
            '☺' => '',
            '☻' => '',
            '♥' => '',
            '♦' => '',
            '♣' => '',
            '♠' => '',
            '•' => '',
            '◘' => '',
            '○' => '',
            '◙' => '',
            '♂' => '',
            '♀' => '',
            '♪' => '',
            '♫' => '',
            '☼' => '',
            '►' => '',
            '◄' => '',
            '↕' => '',
            '‼' => '',
            '¶' => '',
            '§' => '',
            '▬' => '',
            '↨' => '',
            '↑' => '',
            '↓' => '',
            '→' => '',
            '←' => '',
            '∟' => '',
            '↔' => '',
            '▲' => '',
            '▼' => '',
            '_' => '_',
            'ñ' => 'n',
            '⌂' => '',
            'Ç' => 'c',
            'Ä' => 'a',
            'Ë' => 'e',
            'Ö' => 'o',
            'Ï' => 'i',
            'Ü' => 'u',
            'É' => 'e',
            'æ' => 'oe',
            'Æ' => 'ae',
            'ò' => 'o',
            'ø' => 'o',
            'Ø' => '',
            'ƒ' => '',
            '×' => '',
            'Ñ' => 'n',
            'ª' => '',
            'º' => ' ',
            '¿' => '',
            '®' => '',
            '¬' => '',
            '½' => '',
            '¼' => '',
            '¡' => '',
            '«' => '',
            '»' => '',
            '░' => '',
            '▓' => '',
            '│' => '',
            '┤' => '',
            '©' => '',
            '╣' => '',
            '║' => '',
            '╗' => '',
            '╝' => '',
            '¢' => '',
            '¥' => '',
            '┐' => '',
            '└' => '',
            '┴' => '',
            '┬' => '',
            '├' => '',
            '─' => '',
            '┼' => '',
            'ã' => '',
            'Ã' => '',
            '╚' => '',
            '╔' => '',
            '╩' => '',
            '╦' => '',
            '╠' => '',
            '═' => '',
            '╬' => '',
            'ð' => '',
            'Ð' => '',
            'Ê' => '',
            'Ë' => '',
            'È' => '',
            'ı' => '',
            'Í' => '',
            'Î' => '',
            'Ï' => '',
            '┘' => '',
            '┌' => '',
            '█' => '',
            '▄' => '',
            '¦' => '',
            'Ì' => '',
            '▀' => '',
            'Ó' => 'o',
            'ß' => '',
            'Ô' => 'o',
            'Ò' => 'o',
            'õ' => 'o',
            'Õ' => 'o',
            'þ' => '',
            'Þ' => '',
            'Ú' => 'u',
            'Û' => 'u',
            'Ù' => 'u',
            'ý' => 'y',
            'Ý' => 'y',
            '¯' => '',
            '´' => '',
            '±' => '',
            '‗' => '',
            '¾' => '',
            '¶' => '',
            '§' => '',
            '÷' => '',
            '¸' => '',
            '°' => '',
            '·' => '',
            '¹' => '',
            '³' => '',
            '²' => '',
            '■' => '',
            ' ' => '',
        );
    }

    /**
     * Affiche un input hidden
     * 
     * @param string $name Name de l'input
     * @param string $value Value de l'input
     */
    public static function hidden($name, $value) {
        ?>
        <div class="form-group">
            <input type="hidden" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $value; ?>" />
        </div>
        <?php
    }

    /**
     * Affiche une checkbox     * 
     * @param string $label Nabel
     * @param string $name Name
     * @param string $value Value de la checkbox
     * @param string $class Class css
     * @param boolean $checked Case cochée par défaut ? true/false (false par defaut)
     */
    public static function checkbox($label, $name, $value, $class = "", $checked = false) {
        if (!empty($class)) {
            ?>
            <div class="<?php echo $class; ?>">            
                <?php
            }
            ?>
            <div class="form-group checkbox">
                <label for="<?php echo $name; ?>">
                    <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($checked) { ?>checked="checked" <?php } ?>/>
                    <?php echo $label; ?></label>
            </div>
            <?php
            if (!empty($class)) {
                ?>
            </div>
            <?php
        }
    }

    /**
     * Créer un groupe de boutons radio     * 
     * @param string $label Label
     * @param string $name Name
     * @param array $radios array(array(value,text,[selected]),...);
     * @param string $class Class css
     */
    public static function radios($label, $name, $radios, $class = "") {
        ?>
        <div class="form_radio <?php echo $class; ?>">
            <?php
            form::new_fieldset($label);
            foreach ($radios as $value) {
                ?>
                <div class="radio">
                    <label>
                        <input type="radio" name="<?php echo $name; ?>" id="<?php echo $name . $value[0]; ?>" value="<?php echo $value[0]; ?>" <?php
                        if (isset($value[2]) and $value[2] == true) {
                            ?>checked="checked" <?php
                               }
                               ?>>
                               <?php echo $value[1]; ?>
                    </label>
                </div>
                <?php
            }
            form::close_fieldset();
            ?>
        </div>
        <?php
    }

    /**
     * REQUIERS JQUERY-UI ! <br />
     * Affiche le datetimepicker de jquery-ui (date + heur)
     * 
     * @param string $label Label
     * @param string $name Name
     * @param null|string $value Value (null par defaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Class css
     */
    public static function datetimepicker($label, $name, $value = null, $required = true, $class = "") {
        ?>
        <script>
            $(document).ready(function () {
                $("#<?php echo $name ?>").datetimepicker($.timepicker.regional["fr"]);
                $("#<?php echo $name ?>").datetimepicker({dateFormat: "dd/mm/yy"});
                $("#<?php echo $name ?>").attr("readonly", true);
                $("#<?php echo $name ?>").attr("placeholder", "Cliquez pour choisir une date");
            });
        </script>
        <?php
        form::input($label, $name, "text", $value, $required, $class);
    }

    /**
     * Récupère la valeur du datetimepicker au format US
     * 
     * @param string $name Name
     * @return boolean|array Date US ou false
     */
    public static function get_datetimepicker_us($name) {
        $date = explode(" ", $_POST[$name]);
        $time = $date[1];
        $date = explode("/", $date[0]);
        if (isset($date[2])) {
            return $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $time;
        } else {
            return FALSE;
        }
    }

    /**
     * REQUIERS JQUERY-UI ! <br />
     * Affiche le datepicker de jquery-ui (date seulement) 
     * 
     * @param string $label Label
     * @param string $name Name
     * @param null|string $value Value (null par defaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Class css
     */
    public static function datepicker($label, $name, $value = null, $required = true, $class = "") {
        ?>
        <script>
            $(document).ready(function () {
                $("#<?php echo $name ?>").datepicker($.timepicker.regional["fr"]);
                $("#<?php echo $name ?>").datepicker({dateFormat: "dd/mm/yy"});
                $("#<?php echo $name ?>").attr("readonly", true);
                $("#<?php echo $name ?>").attr("placeholder", "Cliquez pour choisir une date");
            });
        </script>
        <?php
        form::input($label, $name, "text", $value, $required, $class);
    }

    /**
     * Récupère la valeur du datepicker au format US
     * 
     * @param string $name Name
     * @return boolean|array Date US ou false
     */
    public static function get_datepicker_us($name) {
        $date = explode("/", $_POST[$name]);
        if (isset($date[2])) {
            return $date[2] . "-" . $date[1] . "-" . $date[0];
        } else {
            return FALSE;
        }
    }

    /**
     * REQUIERS JQUERY-UI ! <br />
     * Affiche le datepicker de jquery-ui (heur seulement) 
     * 
     * @param string $label Label
     * @param string $name Name
     * @param null|string $value Value (null par defaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Class css
     */
    public static function timepicker($label, $name, $value = null, $required = true, $class = "") {
        ?>
        <script>
            $(document).ready(function () {
                $("#<?php echo $name ?>").timepicker($.timepicker.regional["fr"]);
                $("#<?php echo $name ?>").timepicker();
                $("#<?php echo $name ?>").attr("readonly", true);
                $("#<?php echo $name ?>").attr("placeholder", "Cliquez pour choisir une date");
            });
        </script>
        <?php
        form::input($label, $name, "text", $value, $required, $class);
    }

    /**
     * Affiche le bouton submit du formulaire
     * 
     * @param string $class Class css
     * @param string $value Texte du bouton (null par default, dépend du navigateur client)
     */
    public static function submit($class, $value = null) {
        ?>
        <div class="form-group">
            <input type="submit" class="btn <?php echo $class; ?>" <?php if ($value != null) { ?>value="<?php echo $value; ?>" <?php } ?> />
        </div>
        <?php
    }

    /**
     * Affiche un sélecteur (balise select) <br />
     * les options y sont renseignés par un tableau à deux dimensions (cf $option)
     * 
     * @param string $label Label
     * @param string $name Name
     * @param array $option array(array(value,text,[selected]),...); ou array("group"=>array(array(value,text,[selected])),...);
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Class css
     */
    public static function select($label, $name, $option, $required = true, $class = "") {
        if (!empty($class)) {
            ?>
            <div class="<?php echo $class; ?>">            
                <?php
            }
            ?>
            <div class="form-group">
                <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
                <select id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="form-control" <?php if ($required) { ?>required="required"<?php } ?>>
                    <?php
                    form::options($option);
                    ?>
                </select>
            </div>
            <?php
            if (!empty($class)) {
                ?>
            </div>
            <?php
        }
    }

    /**
     * Utilisé de base dans form::select(),<br />
     * Gère les options de form::select(),
     * 
     * @param array $option array(array(value,text,[selected]),...); ou array("group"=>array(array(value,text,[selected])),...);
     */
    public static function options($option) {
        foreach ($option as $key => $value) {
            if (!is_int($key)) {
                ?>
                <optgroup label="<?php echo $key; ?>">
                    <?php
                    form::options($value);
                    ?>
                </optgroup>
                <?php
            } else {
                ?>
                <option value="<?php echo $value[0]; ?>" <?php if (isset($value[2]) and $value[2] == true) { ?> selected="selected" <?php } ?>><?php echo $value[1]; ?></option>
                <?php
            }
        }
    }

    /**
     * Affiche un textarea
     * 
     * @param string $label Label
     * @param string $name Name
     * @param null|string $value Value (null par defaut)
     * @param string $required Le champ est-il requis ? (true/false, true par defaut )
     * @param string $class Class css
     * @param int $cols cols, Taille en x du textarea
     * @param int $rows rows, Taille en y du textarea
     */
    public static function textarea($label, $name, $value = null, $required = true, $class = "", $cols = 30, $rows = 10) {
        ?>
        <div class="form-group">
            <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
            <div>
                <textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="<?php echo $cols; ?>" rows="<?php echo $rows; ?>" class="form-control <?php echo $class; ?>" <?php if ($required) { ?>required="required"<?php } ?>><?php
                    if ($value != null) {
                        echo $value;
                    }
                    ?></textarea>

            </div>
        </div>
        <?php
    }

    /**
     * Ouvre un fieldset avec une légende pour un formulaire ou une partie de formuaire, <br />
     * ce ferme avec close_fieldset(), les fieldset peuvent être imbriqués (attention aux fermetures !)
     * 
     * @param string $legend Legende du fieldset
     */
    public static function new_fieldset($legend) {
        ?>
        <fieldset>
            <legend><?php echo $legend; ?></legend>
            <?php
        }

        /**
         * Ferme un fieldset
         */
        public static function close_fieldset() {
            ?>
        </fieldset>
        <?php
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
     * Ouvre un nouveau formulaire ( doit être fermé avec $this->close_form())
     * 
     * @param string $class Class css
     * @param string $action Action (url d'action du formulaire, "#" par defaut)
     * @param string $method Methode d'envoi du formulaire ("post" par defaut)
     */
    public static function new_form($class = "", $action = "#", $method = "post") {
        ?>
        <form action="<?php echo $action; ?>" method="<?php echo $method; ?>" <?php if ($class != "") { ?> class="<?php echo $class; ?>" <?php } ?> > 
            <?php
        }

        /**
         * Ferme le formulaire en cours
         * @param boolean $use_token Le formulaire doit-il posseder un token (sécurité, true/false, false par defaut)
         */
        public static function close_form($use_token = false) {
            if ($use_token) {
                self::hidden("token", self::get_token());
            }
            ?>
        </form>
        <?php
    }

}
