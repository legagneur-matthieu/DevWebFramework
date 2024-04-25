<?php

/**
 * Compresse des scripts CSS en deux fichiers minifiés
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class compact_css extends singleton {

    /**
     * Liste de fichiers CSS
     * @var array Liste de fichiers CSS 
     */
    private $_files = [];

    /**
     * Liste de scripts CSS et JS 
     * @var array Liste de scripts CSS et JS 
     */
    private $_custom = [];

    /**
     * Liste des fichiers en cache
     * @var array Liste des fichiers en cache
     */
    private $_cache_files = [
        "f" => [
            "file" => "",
            "mt" => 0
        ],
        "c" => [
            "file" => "",
            "mt" => 0
        ]
    ];

    /**
     * Ajoute un fichier CSS
     * @param string $href Chemin du fichier CSS
     * @return $this
     */
    public function add_css_file($href) {
        $this->_files[] = $href;
        export_dwf::add_files([realpath($href)]);
        return $this;
    }

    /**
     * Ajoute un script CSS
     * @param string $style Script CSS 
     * @return $this
     */
    public function add_style($style) {
        $this->_custom[] = $style;
        return $this;
    }

    /**
     * Compacte les fichiers en un seul
     * @return string Nom du fichier compressé
     */
    private function compact_files() {
        if (!file_exists("./src")) {
            mkdir("./src");
            dwf_exception::check_file_writed("./src");
        }
        if (!file_exists("./src/compact")) {
            mkdir("./src/compact");
            dwf_exception::check_file_writed("./src/compact");
        }
        if (($fc = count($this->_files)) === 0) {
            return false;
        }
        $filename = "./src/compact/f_" . $fc . "_" . sha1(implode("&", $_GET)) . ".css";
        $regen = !file_exists($filename);
        $mt_gen = (($regen) ? 0 : filemtime($filename));
        foreach ($this->_files as $file) {
            $mt_file = (int) filemtime($file);
            if (($mt_file and $mt_gen < $mt_file) or $regen) {
                $regen = true;
                break;
            }
        }
        if ($regen) {
            $content = "";
            foreach ($this->_files as $file) {
                $content .= file_get_contents($file) . PHP_EOL;
            }
            $content = self::css_minify($content);
            $content = strtr($content, ['/}' => "}"]);
            file_put_contents($filename, $content);
            dwf_exception::check_file_writed($filename);
        }
        return $filename;
    }

    /**
     * Compacte les scripts en un seul fichier
     * @return string Nom du fichier compressé
     */
    private function compact_custom() {
        if (($fc = count($this->_custom)) === 0) {
            return false;
        }
        $filename = "./src/compact/c_" . $fc . "_" . sha1(implode("&", $_GET)) . ".css";
        $custom = self::css_minify(implode(" ", $this->_custom));
        if (!file_exists($filename) or sha1($custom) !== sha1(file_get_contents($filename))) {
            file_put_contents($filename, $custom);
        }
        return $filename;
    }

    /**
     * Minifie un script CSS
     * @param string $css Script CSS
     * @return string Script CSS minifié
     */
    public static function css_minify($css) {
        csstidy_loader::autoloader();
        $csstidy = new csstidy();
        $csstidy->set_cfg("merge_selectors", 2);
        $csstidy->set_cfg("template", "highest_compression");
        $csstidy->parse($css);
        $csstidy->optimise->postparse();
        return $csstidy->print->plain();
    }

    private function clear($files) {
        foreach (glob("./src/compact/*_*_" . sha1(implode("&", $_GET)) . ".css") as $f) {
            if (!in_array($f, $files)) {
                unlink($f);
            }
        }
        foreach (glob("./src/compact/*_*_*.css") as $f) {
            if (filemtime($f) < microtime(true) - 31536000) {
                unlink($f);
            }
        }
    }

    /**
     * Affiche les scripts de liaison des fichiers compressé
     */
    public function render() {
        $files = [
            "css_f" => $this->compact_files(),
            "css_c" => $this->compact_custom(),
        ];
        ?>
        <script type="text/javascript">
            function add_link_in_head(href) {
                if (href !== "" || document.querySelector("link[href='" + href + "']") === null) {
                    let link = document.createElement("link");
                    link.rel = "stylesheet";
                    link.href = href;
                    document.querySelector("head").appendChild(link);
                }
            }
            add_link_in_head("<?= ($files["css_f"] ? $files["css_f"] : ""); ?>");
            add_link_in_head("<?= ($files["css_c"] ? $files["css_c"] : ""); ?>");
        </script>
        <?php
        $this->clear($files);
    }

    /**
     * Retourne les link des fichiers en cache
     * @return string Les link et script des fichiers en cache
     */
    public function get_file_in_cache() {
        foreach (["f", "c"] as $p) {
            foreach (glob("./src/compact/" . $p . "_*_" . sha1(implode("&", $_GET)) . ".css") as $f) {
                if (($nmt = filemtime($f)) > $this->_cache_files[$p]["mt"]) {
                    $this->_cache_files[$p] = [
                        "file" => $f,
                        "mt" => $nmt
                    ];
                }
            }
        }
        $src = "";
        foreach (["f", "c"] as $p) {
            if ($this->_cache_files[$p]["mt"] > 0) {
                $src .= html_structures::link($this->_cache_files[$p]["file"]);
            }
        }
        return $src;
    }
}
