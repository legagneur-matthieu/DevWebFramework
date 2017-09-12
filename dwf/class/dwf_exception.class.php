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
    private static $error = array(
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
        // 63X FS
        631 => "Le fichier __ n'a pu être créé (problèmes de droits ou d'espace disque ?)",
    );

    /**
     * Lance une exception qui interrompt le script en cours
     * Il est possible de renseigner une exception personalisée comme par exemple : 
     * dwf_exception::throw_exception($code_erreur,array("msg"=>"$text_erreur"));
     * @param int $errcode Erreur code de l'exception
     * @param array $arg Argument de l'exception, sert à completer les messages d'erreurs
     * @throws dwf_exception
     */
    public static function throw_exception($errcode, $arg = array()) {
        throw new dwf_exception((isset(self::$error[$errcode]) ? strtr(self::$error[$errcode], $arg) : (isset($arg["msg"]) ? $arg["msg"] : "Unknow exeption")), $errcode);
    }

    /**
     * affiche une exception qui n'interrompt pasle script en cours
     * Il est possible de renseigner une exception personalisée comme par exemple : 
     * dwf_exception::warning_exception($code_erreur,array("msg"=>"$text_erreur"));
     * @param int $errcode Erreur code de l'exception
     * @param array $arg Argument de l'exception, sert à completer les messages d'erreurs
     * @throws dwf_exception
     */
    public static function warning_exception($errcode, $arg = array()) {
        self::print_exception(new dwf_exception((isset(self::$error[$errcode]) ? strtr(self::$error[$errcode], $arg) : (isset($arg["msg"]) ? $arg["msg"] : "Unknow exeption")), $errcode));
    }

    /**
     * Affiche l'exception si config::$_debug=true et note l'exception dans dwf/log/
     * @param dwf_exception $e
     * @param string $moreinfo Affiche des informations complémentaires sur l'exception
     * @param boolean $service l'exception survient-elle dans un service ? (true/false, false par defaut)
     */
    public static function print_exception(exception $e, $moreinfo = "", $service = false) {
        if (config::$_debug) {
            if (!html5::$_called) {
                if ($service) {
                    ?>
                    <!DOCTYPE HTML>
                    <html lang="fr-FR">
                        <head>
                            <meta charset="UTF-8">
                            <title><?php echo config::$_title; ?></title>
                        </head>
                        <body>
                            <?php
                        } else {
                            $html = new html5();
                        }
                    }
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <p><?php echo (is_a($e, "dwf_exception") ? "DWF EXCEPTION ! Code " . $e->getCode() : "PHP EXCEPTION") . " : \"" . $e->getMessage() . "\""; ?></p>
                        <pre><?php echo $e->getTraceAsString(); ?></pre>
                    <?php echo $moreinfo; ?>
                    </div>
                    <?php
                    if (!html5::$_called and $service) {
                        ?>
                    </body>
                </html>
                <?php
            }
        }
        (new log_file(true))->severe((is_a($e, "dwf_exception") ? "DWF EXCEPTION ! Code " . $e->getCode() : "PHP EXCEPTION") . " : \"" . $e->getMessage() . "\"\n" . $e->getTraceAsString() . "\n" . $moreinfo . "\n");
    }

    /**
     * Vérifie si un fichier a pu être écrit correctement, renvoit une exception 631 dans le cas contraire
     * @param string $file Chemain du fichier a verrifier.
     */
    public static function check_file_writed($file) {
        if (!file_exists($file)) {
            self::throw_exception(631, array("__" => $file));
        }
    }

}