<?php

/**
 * Permet d'afficher les différences entre deux chaines de caractères
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class php_finediff {

    /**
     * Permet de vérifier que la librairie finediff a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie finediff a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Retourne le HTML du finediff entre les deux chaines
     * @param string $from_text Texte d'origine
     * @param string $to_text Texte final
     * @return string HTML du finediff
     */
    public static function DiffToHTML($from_text, $to_text) {
        if (!self::$_called) {
            include __DIR__ . '/finediff/finediff.php';
            export_dwf::add_files([realpath(__DIR__ . "/finediff")]);
            self::$_called = true;
        }
        return FineDiff::renderDiffToHTMLFromOpcodes($from_text, FineDiff::getDiffOpcodes($from_text, $to_text, FineDiff::$wordGranularity));
    }

}
