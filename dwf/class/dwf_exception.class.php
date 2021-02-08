<?php

/**
 * Cette classe gère les exceptions du framework
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class dwf_exception extends Exception {

    /**
     * Liste des exceptions du framework
     * @var array Liste des exceptions du framework 
     */
    private static $error = [
        // 60X : base de donnée
        601 => "Connexion à la base de données impossible",
        602 => "Requête SQL incorrect : \"__statement__\" ",
        603 => "bdd->__m__() doit être utilisé pour cette requête : \"__statement__\" ",
        // 61X : routes et méthodes
        611 => "Route index introuvable",
        612 => "Méthode page::__ introuvable",
        613 => "Méthode _1_::_2_ introuvable",
        // 62X : Services
        621 => "Service non renseigné",
        622 => "Service _s_ introuvable",
        623 => "Action du service _s_ non renseigné",
        624 => "Action _a_ du service _s_ introuvable",
        // 63X FS / système
        631 => "Le fichier __ n'a pu être créé (problèmes de droits ou d'espace disque ?)",
        632 => "Git ne semble pas être installé sur la machine hôte.",
        633 => "PHPini : le profil n'a pas pu être chargé.",
    ];

    /**
     * Lance une exception qui interrompt le script en cours
     * Il est possible de renseigner une exception personalisée comme par exemple : 
     * dwf_exception::throw_exception($code_erreur,array("msg"=>"$text_erreur"));
     * @param int $errcode Erreur code de l'exception
     * @param array $arg Argument de l'exception, sert à completer les messages d'erreurs
     * @throws dwf_exception
     */
    public static function throw_exception($errcode, $arg = []) {
        throw new dwf_exception((isset(self::$error[$errcode]) ? strtr(self::$error[$errcode], $arg) : (isset($arg["msg"]) ? $arg["msg"] : "Unknow exeption")), $errcode);
    }

    /**
     * Affiche une exception qui n'interrompt pas le script en cours
     * Il est possible de renseigner une exception personalisée comme par exemple : 
     * dwf_exception::warning_exception($code_erreur,array("msg"=>"$text_erreur"));
     * @param int $errcode Erreur code de l'exception
     * @param array $arg Argument de l'exception, sert à completer les messages d'erreurs
     * @throws dwf_exception
     */
    public static function warning_exception($errcode, $arg = []) {
        self::print_exception(new dwf_exception((isset(self::$error[$errcode]) ? strtr(self::$error[$errcode], $arg) : (isset($arg["msg"]) ? $arg["msg"] : "Unknow exeption")), $errcode));
    }

    /**
     * Affiche l'exception si config::$_debug=true et note l'exception dans dwf/log/
     * @param dwf_exception $e
     * @param string $moreinfo Affiche des informations complémentaires sur l'exception
     * @param boolean $service l'exception survient-elle dans un service ? (true/false, false par defaut)
     */
    public static function print_exception(exception $e, $moreinfo = "", $service = false) {
        $etype = (is_a($e, "dwf_exception") ? "DWF EXCEPTION ! Code " . $e->getCode() : "PHP EXCEPTION");
        if (config::$_debug) {
            if (!html5::$_called) {
                if ($service) {
                    ?>
                    <!DOCTYPE HTML>
                    <html lang="fr-FR">
                        <?= tags::tag("head", [], tags::tag("meta", ["charset" => "UTF-8"]) . tags::tag("title", [], config::$_title)); ?>
                        <body>
                            <?php
                        } else {
                            $html = new html5();
                        }
                    }
                    echo tags::tag(
                            "div", ["class" => "alert alert-danger", "role" => "alert"], tags::tag(
                                    "p", [], $etype . " : \"" . $e->getMessage() . "\"") .
                            tags::tag(
                                    "pre", ["class"=>"border alert alert-light"], "\r".$e->getTraceAsString()) . $moreinfo
                    );
                    if (!html5::$_called and $service) {
                        ?>
                    </body>
                </html>
                <?php
            }
        }
        (new log_file(true))->severe($etype . " : \"" . $e->getMessage() . "\"\n" . $e->getTraceAsString() . "\n" . $moreinfo . "\n");
    }

    /**
     * Vérifie si un fichier a pu être écrit correctement, renvoit une exception 631 dans le cas contraire
     * @param string $file Chemin du fichier à vérifier.
     */
    public static function check_file_writed($file) {
        if (!file_exists($file)) {
            self::throw_exception(631, ["__" => $file]);
        }
    }

}
