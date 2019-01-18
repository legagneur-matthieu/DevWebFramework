<?php

/**
 * Compresse des scripts CSS et JS en quatre fichiers minifié
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class compact_src extends singleton {

    /**
     * Liste de fichier CSS et JS
     * @var array Liste de fichier CSS et JS 
     */
    private $files = [
        "css" => [],
        "js" => []
    ];

    /**
     * Liste de script CSS et JS 
     * @var array Liste de script CSS et JS 
     */
    private $custom = [
        "css" => [],
        "js" => []
    ];

    /**
     * Ajoute un fichier CSS
     * @param string $href Chemain du fichier CSS
     * @return $this
     */
    public function add_css_file($href) {
        return $this->add_file("css", $href);
    }

    /**
     * Ajoute un fichier JS
     * @param string $src Chemain du fichier js
     * @return $this
     */
    public function add_js_file($src) {
        return $this->add_file("js", $src);
    }

    /**
     * Ajoute un script CSS
     * @param string $style Script CSS 
     * @return $this
     */
    public function add_style($style) {
        return $this->add_custom("css", $style);
    }

    /**
     * Ajoute un script JS
     * @param string $script Script jS
     * @return $this
     */
    public function add_script($script) {
        return $this->add_custom("js", $script);
    }

    /**
     * ajoute un fichier
     * @param string $type Type du fichier (CSS/JS)
     * @param string $file Chemain du fichier
     * @return $this
     */
    private function add_file($type, $file) {
        $this->files[$type][] = $file;
        return $this;
    }

    /**
     * Ajoute un script
     * @param string $type Type du script (CSS/JS)
     * @param string $custom Script
     * @return $this
     */
    private function add_custom($type, $custom) {
        $this->custom[$type][] = $custom;
        return $this;
    }

    /**
     * Compacte les fichiers en un seul
     * @param string $type Type des fichiers (CSS/JS)
     * @return string Nom du fichier compréssé
     */
    private function compact_files($type) {
        if (!file_exists("./src")) {
            mkdir("./src");
            dwf_exception::check_file_writed("./src");
        }
        if (!file_exists("./src/compact")) {
            mkdir("./src/compact");
            dwf_exception::check_file_writed("./src/compact");
        }
        if (($fc = count($this->files[$type])) === 0) {
            return false;
        }
        $filename = "./src/compact/f_" . $fc . "_" . sha1(implode("&", $_GET)) . "." . $type;
        $mt_gen = (($regen = !file_exists($filename)) ? 0 : filemtime($filename));
        $regen = !$regen;
        foreach ($this->files[$type] as $file) {
            $mt_file = filemtime($file);
            if (($mt_file and $mt_gen < $mt_file) or $regen) {
                $regen = true;
                break;
            }
        }
        if ($regen) {
            $content = "";
            foreach ($this->files[$type] as $file) {
                $content .= file_get_contents($file) . PHP_EOL;
            }
            $content = self::minify_content($type, $content);
            file_put_contents($filename, $content);
            dwf_exception::check_file_writed($filename);
        }
        return $filename;
    }

    /**
     * Compacte les scripts en un seul fichier
     * @param string $type Type des scripts (CSS/JS)
     * @return string Nom du fichier compréssé
     */
    private function compact_custom($type) {
        if (($fc = count($this->custom[$type])) === 0) {
            return false;
        }
        $filename = "./src/compact/c_" . $fc . "_" . sha1(implode("&", $_GET)) . "." . $type;
        $custom = self::minify_content($type, implode(" ", $this->custom[$type]));
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
    private static function css_minify($css) {
        csstidy_loader::autoloader();
        $csstidy = new csstidy();
        $csstidy->set_cfg("merge_selectors", 2);
        $csstidy->set_cfg("template", "highest_compression");
        $csstidy->parse($css);
        $csstidy->optimise->postparse();
        return $csstidy->print->plain();
    }

    /**
     * Minifie un script CSS ou JS
     * @param string $type "css" ou "js"
     * @param string $content Script CSS ou JS
     * @return string Script JS minifié
     */
    public static function minify_content($type, $content) {
        switch ($type) {
            case "css":
                $content = self::css_minify($content);
                break;
            case "js":
                $content = JSMin::minify($content);
                break;
            default:
                break;
        }
        return $content;
    }

    private function clear($files) {
        foreach (glob("./src/compact/*_*_" . sha1(implode("&", $_GET)) . ".*") as $f) {
            if (!in_array($f, $files)) {
                unlink($f);
            }
        }
        foreach (glob("./src/compact/*_*_*.*") as $f) {
            if (filemtime($f) < microtime(true) - 31536000) {
                unlink($f);
            }
        }
    }

    /**
     * Affiche les scripts de liaison des fichiers compréssé
     */
    public function render() {
        $files = [
            "css_f" => $this->compact_files("css"),
            "css_c" => $this->compact_custom("css"),
            "js_f" => $this->compact_files("js"),
            "js_c" => $this->compact_custom("js")
        ];
        echo ($files["js_f"] ? html_structures::script($files["js_f"]) : "") .
        ($files["js_c"] ? html_structures::script($files["js_c"]) : "");
        ?>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function () {
                function add_link_in_head(href) {
                    if (href !== "") {
                        let link = document.createElement("link");
                        link.rel = "stylesheet";
                        link.href = href;
                        document.querySelector("head").appendChild(link);
                    }
                }
                add_link_in_head("<?= ($files["css_f"] ? $files["css_f"] : ""); ?>");
                add_link_in_head("<?= ($files["css_c"] ? $files["css_c"] : ""); ?>");
            });
        </script>
        <?php
        $this->clear($files);
    }

}
