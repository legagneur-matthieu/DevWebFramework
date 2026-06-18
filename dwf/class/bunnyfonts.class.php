<?php

/**
 * Cette classe parmet de charger des fonts/polices en provenance de https://fonts.bunny.net
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class bunnyfonts {

    /**
     * Liste des fonts disponible sur fonts.bunny.net
     * @var array Liste des fonts disponible sur fonts.bunny.net
     */
    private static $_list = false;

    /**
     * Charge la list des fonts disponibles
     */
    private static function load_list() {
        if (!self::$_list) {
            if (!file_exists($json_file = __DIR__ . "/fonts/bunnyfonts.json")) {
                file_put_contents($json_file, file_get_contents("https://fonts.bunny.net/list"));
            }
            self::$_list = json_decode(file_get_contents(__DIR__ . "/fonts/bunnyfonts.json"), true);
        }
    }

    /**
     * Import une font/police depuis l'API de fonts.bunny.net
     * @param string $font Font normal, exemple : "amaranth:400,400i"
     * @param string|boolean $font_bold Font grasse, exemple : "amaranth:700,700i". False pour ne pas charger de font grasse.
     */
    public static function import($font, $font_bold = false) {
        $fname = explode(":", $font)[0];
        $fweight = explode(",", explode(":", $font)[1])[0];
        self::load_list();
        if (isset(self::$_list[$fname])) {
            $familyName = self::$_list[$fname]["familyName"];
            compact_css::get_instance()->add_style("@import url(https://fonts.bunny.net/css?family={$font});" . ".{$fname}_{$fweight}{font-family:\"{$familyName}\";font-weight: {$fweight};}");
            if ($font_bold and $fname == explode(":", $font_bold)[0]) {
                self::import($font_bold);
            }
        }
    }

    /**
     * Télécharge une font/police depuis l'API de fonts.bunny.net pour ensuite etre délivré par le serveur
     * @param string $font Font normal, exemple : "amaranth:400,400i"
     * @param string|boolean $font_bold Font grasse, exemple : "amaranth:700,700i". False pour ne pas charger de font grasse.
     */
    public static function import_local($font, $font_bold = false) {
        $fname = explode(":", $font)[0];
        $fweight = explode(",", explode(":", $font)[1])[0];
        self::load_list();
        if (isset(self::$_list[$fname])) {
            if (!file_exists($dir = "./src/fonts")) {
                mkdir($dir);
            }
            $familyName = self::$_list[$fname]["familyName"];
            foreach (self::$_list[$fname]["variants"] as $variant => $null) {
                foreach (self::$_list[$fname]["styles"] as $style) {
                    $filename = "{$fname}-{$variant}-{$fweight}-{$style}";
                    $url = "https://fonts.bunny.net/{$fname}/files/";
                    foreach (["woff2", "woff"] as $ext) {
                        if (!file_exists($wfile = "{$dir}/{$filename}.{$ext}")) {
                            file_put_contents($wfile, file_get_contents("{$url}{$filename}.{$ext}"));
                        }
                    }
                    compact_css::get_instance()->add_style("/* latin */ "
                            . "@font-face {"
                            . "font-family: '{$familyName}'; "
                            . "font-style: {$style}; "
                            . "font-weight: {$fweight}; "
                            . "src: url(../../src/fonts/{$filename}.woff2) format('woff2'), url(../../src/fonts/{$filename}.woff) format('woff'); "
                            . "}");
                }
            }
            compact_css::get_instance()->add_style(".{$fname}_{$fweight}{font-family:\"{$familyName}\";font-weight: {$fweight};}");
            if ($font_bold and $fname == explode(":", $font_bold)[0]) {
                self::import_local($font_bold);
            }
        }
    }

    /**
     * Retourne la liste des fonts disponible sur fonts.bunny.net
     * @return string La liste des fonts disponible sur fonts.bunny.net
     */
    public static function get_list() {
        self::load_list();
        $fonts = [];
        foreach (self::$_list as $ff => $fd) {
            foreach ($fd["weights"] as $w) {
                $fonts[] = "{$ff}:{$w}" . (in_array("italic", $fd["styles"]) ? ",{$w}i" : "");
            }
        }
        return $fonts;
    }
}
