<?php

/**
 * Cette classe permet de gèrer des profils de configuration PHP (comme avoir plusieurs fichier php.ini interchangeable à volonté)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class phpini {

    /**
     * Configuration par défaut telle que décrite dans la documentation officielle de php.ini
     */
    const MODE_DEFAULT = "default";

    /**
     * Configuration de développement telle que décrite dans la documentation officielle de php.ini
     */
    const MODE_DEV = "dev";

    /**
     * Configuration de production telle que décrite dans la documentation officielle de php.ini
     */
    const MODE_PROD = "PROD";

    /**
     * Configuration de développement conseillée pour DWF
     */
    const MODE_DWF_DEV = "dwf_dev";

    /**
     * Configuration de production conseillée pour DWF
     */
    const MODE_DWF_PROD = "dwf_prod";

    /**
     * Configuration perssonalisée
     */
    const MODE_CUSTOM = "custom";

    /**
     * Liste des configurations
     * @var array $_ini Liste des configurations 
     */
    private static $_ini = [
        "default" => [
            "display_errors" => "On",
            "display_startup_errors" => "Off",
            "error_reporting" => "E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED",
            "html_errors" => "On",
            "log_errors" => "Off",
            "max_input_time" => "-1",
            "output_buffering" => "Off",
            "register_argc_argv" => "On",
            "request_order" => "None",
            "session__gc_divisor" => "100",
            "session__sid_bits_per_character" => "4",
            "short_open_tag" => "Off",
            "track_errors" => "Off",
            "variables_order" => "EGPCS",
            "max_execution_time" => "30",
            "memory_limit" => "128M",
            "post_max_size" => "8M",
            "session__gc_maxlifetime" => "1440"
        ],
        "dev" => [
            "display_errors" => "On",
            "display_startup_errors" => "On",
            "error_reporting" => "E_ALL",
            "html_errors" => "On",
            "log_errors" => "On",
            "max_input_time" => "60",
            "output_buffering" => "4096",
            "register_argc_argv" => "Off",
            "request_order" => "GP",
            "session__gc_divisor" => "1000",
            "session__sid_bits_per_character" => "5",
            "short_open_tag" => "Off",
            "track_errors" => "On",
            "variables_order" => "GPCS",
            "max_execution_time" => "30",
            "memory_limit" => "128M",
            "post_max_size" => "8M",
            "session__gc_maxlifetime" => "1440"
        ],
        "prod" => [
            "display_errors" => "Off",
            "display_startup_errors" => "Off",
            "error_reporting" => "E_ALL & ~E_DEPRECATED & ~E_STRICT",
            "html_errors" => "On",
            "log_errors" => "On",
            "max_input_time" => "60",
            "output_buffering" => "4096",
            "register_argc_argv" => "Off",
            "request_order" => "GP",
            "session__gc_divisor" => "1000",
            "session__sid_bits_per_character" => "5",
            "short_open_tag" => "Off",
            "track_errors" => "Off",
            "variables_order" => "GPCS",
            "max_execution_time" => "30",
            "memory_limit" => "128M",
            "post_max_size" => "8M",
            "session__gc_maxlifetime" => "1440"
        ],
        "dwf_dev" => [
            "display_errors" => "On",
            "display_startup_errors" => "On",
            "error_reporting" => "E_ALL",
            "html_errors" => "On",
            "log_errors" => "On",
            "max_input_time" => "60",
            "output_buffering" => "4096",
            "register_argc_argv" => "Off",
            "request_order" => "None",
            "session__gc_divisor" => "1000",
            "session__sid_bits_per_character" => "5",
            "short_open_tag" => "Off",
            "track_errors" => "On",
            "variables_order" => "EGPCS",
            "max_execution_time" => "60",
            "memory_limit" => "256M",
            "post_max_size" => "100M",
            "session__gc_maxlifetime" => "3600"
        ],
        "dwf_prod" => [
            "display_errors" => "Off",
            "display_startup_errors" => "Off",
            "error_reporting" => "E_ALL & ~E_DEPRECATED & ~E_STRICT",
            "html_errors" => "On",
            "log_errors" => "On",
            "max_input_time" => "60",
            "output_buffering" => "4096",
            "register_argc_argv" => "Off",
            "request_order" => "None",
            "session__gc_divisor" => "1000",
            "session__sid_bits_per_character" => "5",
            "short_open_tag" => "Off",
            "track_errors" => "OFF",
            "variables_order" => "EGPCS",
            "max_execution_time" => "60",
            "memory_limit" => "256M",
            "post_max_size" => "100M",
            "session__gc_maxlifetime" => "3600"
        ],
        "custom" => [],
    ];

    /**
     * Charge des configurations personalisées
     */
    private static function set_custom() {
        $file = __DIR__ . "/phpini/custom.json";
        if (!file_exists($file)) {
            file_put_contents($file, "{}");
        }
        self::$_ini[self::MODE_CUSTOM] = json_decode(file_get_contents($file), true);
    }

    /**
     * Charge une configutation
     * @param string $mode Configuration à charger : phpini::MODE_DEFAULT, phpini::MODE_DEV, phpini::MODE_PROD, phpini::MODE_DWF_DEV, phpini::MODE_DWF_PROD ou phpini::MODE_CUSTOM
     * @param string $profil Nom de la configuration personalisée si $mode = phpini::MODE_CUSTOM
     */
    public static function set_mode($mode, $profil = "") {
        if ($mode !== self::MODE_CUSTOM) {
            foreach (self::$_ini[$mode] as $varname => $newvalue) {
                $varname = strtr($varname, ["__" => "."]);
                if (ini_get($varname) != $newvalue) {
                    ini_set($varname, $newvalue);
                }
            }
        } else {
            self::set_custom();
            if (isset(self::$_ini[$mode][$profil])) {
                foreach (self::$_ini[$mode][$profil] as $varname => $newvalue) {
                    if (ini_get($varname) != $newvalue) {
                        ini_set($varname, $newvalue);
                    }
                }
            } else {
                dwf_exception::warning_exception(633);
            }
        }
    }

    /**
     * Affiche l'interface pour créer vos propres profils de configuration
     */
    public static function admin() {
        self::set_custom();
        if (!isset($_POST["profil"]) and!isset($_GET["new_profile"])) {
            if (count(self::$_ini[self::MODE_CUSTOM]) != 0) {
                $option = [];
                foreach (array_keys(self::$_ini[self::MODE_CUSTOM]) as $profil) {
                    $option[] = [$profil, $profil, false];
                }
                $form = new form();
                $form->select("Profil", "profil", $option);
                $form->submit("btn-primary", "Modifier");
                echo $form->render();
            }
            echo html_structures::a_link(application::get_url() . "new_profile=1", html_structures::glyphicon("plus") . " Nouveau profil", "btn btn-primary");
        } else {
            (isset($_POST["profil_name"]) ? self::admin_exec() : self::admin_form());
        }
    }

    /**
     * Exécution du formulaire d'administration
     */
    private static function admin_exec() {
        self::$_ini[self::MODE_CUSTOM][$_POST["profil_name"]] = [
            "display_errors" => $_POST["display_errors"],
            "display_startup_errors" => $_POST["display_startup_errors"],
            "error_reporting" => "E_ALL" . (!isset($_POST["E_NOTICE"]) ? " & ~E_NOTICE" : "") . (!isset($_POST["E_STRICT"]) ? " & ~E_STRICT" : "") . (!isset($_POST["E_DEPRECATED"]) ? " & ~E_DEPRECATED" : ""),
            "html_errors" => $_POST["html_errors"],
            "log_errors" => $_POST["log_errors"],
            "max_input_time" => $_POST["max_input_time"],
            "output_buffering" => $_POST["output_buffering"],
            "register_argc_argv" => $_POST["register_argc_argv"],
            "request_order" => $_POST["request_order"],
            "session__gc_divisor" => $_POST["session__gc_divisor"],
            "session__sid_bits_per_character" => $_POST["session__sid_bits_per_character"],
            "short_open_tag" => $_POST["short_open_tag"],
            "track_errors" => $_POST["track_errors"],
            "variables_order" => $_POST["variables_order"],
            "max_execution_time" => $_POST["max_execution_time"],
            "memory_limit" => $_POST["memory_limit_qnt"] . $_POST["memory_limit_unit"],
            "post_max_size" => $_POST["post_max_size_qnt"] . $_POST["post_max_size_unit"],
            "session__gc_maxlifetime" => $_POST["session__gc_maxlifetime"]
        ];
        file_put_contents(__DIR__ . "/phpini/custom.json", json_encode(self::$_ini[self::MODE_CUSTOM]));
        js::alertify_alert_redir("Le profil PHPini " . $_POST["profil_name"] . " a bien été créé / modifié", application::get_url(["new_profile"]));
    }

    /**
     * Formulaire d'administration
     */
    private static function admin_form() {
        $form = new form("w100");
        echo $form->get_open_form();
        ?>
        <div class="row">
            <div class="col-sm-4">
                <?php
                if (isset($_GET["new_profile"])) {
                    echo $form->input("Nom du profil", "profil_name");
                } else {
                    echo tags::tag("p", [], "Profil : " . $_POST["profil"]) .
                    $form->hidden("profil_name", $_POST["profil"]) .
                    $form->hidden("profil", $_POST["profil"]);
                }
                $option_on = [];
                $option_off = [];
                $display_errors = "display_errors";
                $display_startup_errors = "display_startup_errors";
                $html_errors = "html_errors";
                $log_errors = "log_errors";
                echo $form->select($display_errors, $display_errors, self::get_option($display_errors)) .
                $form->select($display_startup_errors, $display_startup_errors, self::get_option($display_startup_errors, "Off")) .
                $form->select($html_errors, $html_errors, self::get_option($html_errors)) .
                $form->select($log_errors, $log_errors, self::get_option($log_errors, "Off")) .
                $form->open_fieldset("error_reporting");
                foreach (["E_NOTICE", "E_STRICT", "E_DEPRECATED"] as $E) { // E_ALL
                    $checked = (isset($_POST["profil"]) ? !strpos(self::$_ini[self::MODE_CUSTOM][$_POST["profil"]]["error_reporting"], "~" . $E) : false);
                    echo $form->checkbox($E, $E, "on", "", $checked);
                }
                echo $form->close_fieldset();
                ?>
            </div>
            <div class="col-sm-4">
                <?php
                $max_input_time = "max_input_time";
                $output_buffering = "output_buffering";
                $register_argc_argv = "register_argc_argv";
                $request_order = "request_order";
                $session__gc_divisor = "session__gc_divisor";
                $session__sid_bits_per_character = "session__sid_bits_per_character";
                $max_execution_time = "max_execution_time";
                echo $form->input($max_input_time . " (-1 = Unlimited)", $max_input_time, "number", (isset($_POST["profil"]) ? self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$max_input_time] : "-1")) .
                $form->select($output_buffering, $output_buffering, self::get_option($output_buffering, "Off")) .
                $form->select($register_argc_argv, $register_argc_argv, self::get_option($register_argc_argv)) .
                $form->select($request_order, $request_order, [
                    ["None", "None", (isset($_GET["new_profile"]) ?
                                true :
                                (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$request_order] == "None")
                        )],
                    ["GP", "GP", (isset($_GET["new_profile"]) ?
                                false :
                                (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$request_order] == "GP")
                        )]
                ]) .
                $form->input(strtr($session__gc_divisor, ["__" => "."]) . " (default : 100, recommended : 1000)", $session__gc_divisor, "number", (isset($_POST["profil"]) ? self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$session__gc_divisor] : "100")) .
                $form->radios(strtr($session__sid_bits_per_character, ["__" => "."]), $session__sid_bits_per_character, [
                    [4, 4, (isset($_GET["new_profile"]) ?
                                false :
                                (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$session__sid_bits_per_character] == 4)
                        )],
                    [5, 5, (isset($_GET["new_profile"]) ?
                                true :
                                (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$session__sid_bits_per_character] == 5)
                        )],
                    [6, 6, (isset($_GET["new_profile"]) ?
                                false :
                                (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$$session__sid_bits_per_character] == 6)
                        )],
                ]) .
                $form->input($max_execution_time . " (0 = Unlimited)", $max_execution_time, "number", (isset($_POST["profil"]) ? self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$max_execution_time] : "30")) .
                $form->open_fieldset("memory_limit");
                $qnt = (isset($_GET["new_profile"]) ?
                        128 :
                        (int) (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]]["memory_limit"])
                        );
                $unit = (isset($_GET["new_profile"]) ?
                        "M" :
                        strtr(self::$_ini[self::MODE_CUSTOM][$_POST["profil"]]["memory_limit"], [$qnt => ""])
                        );
                echo $form->input("Quantité", "memory_limit_qnt", "number", $qnt) .
                $form->select("Unité", "memory_limit_unit", [
                    ["K", "K", ("K" == $unit)],
                    ["M", "M", ("M" == $unit)],
                    ["G", "G", ("G" == $unit)],
                    ["T", "T", ("T" == $unit)],
                ]) .
                $form->close_fieldset();
                ?>
            </div>
            <div class="col-sm-4">
                <?php
                $short_open_tag = "short_open_tag";
                $track_errors = "track_errors";
                $variables_order = "variables_order";
                $session__gc_maxlifetime = "session__gc_maxlifetime";
                echo $form->select($short_open_tag, $short_open_tag, self::get_option($short_open_tag, "Off")) .
                $form->select($track_errors, $track_errors, self::get_option($track_errors, "Off")) .
                $form->select($variables_order, $variables_order, [
                    ["EGPCS", "EGPCS", (isset($_GET["new_profile"]) ?
                                true :
                                (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$variables_order] == "EGPCS")
                        )],
                    ["GPCS", "GPCS", (isset($_GET["new_profile"]) ?
                                false :
                                (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$variables_order] == "GPCS")
                        )]
                ]) .
                $form->input(strtr($session__gc_maxlifetime, ["__" => "."]), $session__gc_maxlifetime, "number", (isset($_POST["profil"]) ? self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$session__gc_maxlifetime] : "1440")) .
                $form->open_fieldset("post_max_size");
                $qnt = (isset($_GET["new_profile"]) ?
                        "8" :
                        (int) (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]]["post_max_size"])
                        );
                $unit = (isset($_GET["new_profile"]) ?
                        "M" :
                        strtr(self::$_ini[self::MODE_CUSTOM][$_POST["profil"]]["post_max_size"], [$qnt => ""])
                        );
                echo $form->input("Quantité", "post_max_size_qnt", "number", $qnt) .
                $form->select("Unité", "post_max_size_unit", [
                    ["K", "K", ("K" == $unit)],
                    ["M", "M", ("M" == $unit)],
                    ["G", "G", ("G" == $unit)],
                    ["T", "T", ("T" == $unit)],
                ]) .
                $form->close_fieldset() .
                $form->submit("btn-primary", "Enregistrer");
                ?>
            </div>
        </div>
        <?php
        echo $form->get_close_form();
    }

    /**
     * Retourne les options des select (On, Off) 
     * @param string $name Nom de la variable de configuration
     * @param string $default Valeur par defaut ( On par défaut ou Off)
     * @return array Tableau des options
     */
    private static function get_option($name, $default = "On") {
        return [
            ["On", "On", (isset($_GET["new_profile"]) ?
                ("On" == $default) :
                (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$name] == "On")
                )],
            ["Off", "Off", (isset($_GET["new_profile"]) ?
                ("Off" == $default) :
                (self::$_ini[self::MODE_CUSTOM][$_POST["profil"]][$name] == "Off")
                )],
        ];
    }

}
