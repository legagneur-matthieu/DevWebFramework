<?php

/**
 * Cette classe permet de créer un diaporama avec la librairie reveal
 * Il n'est pas recommandé d'avoir plusieurs diaporamas sur la même page !
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class reveal {

    /**
     * Permet de vérifier que la librairie reveal a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie reveal a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe permet de créer un diaporama avec la librairie reveal, Il n'est pas recommandé d'avoir plusieurs diaporamas sur la même page !
     * Les diaporamas s'organisent avec des balises "section"
     * @param string $width Largeur des slides (css)
     * @param string $height Hauteur des slides (css)
     * @param string $theme Théme des slides (white par defaut, beige,black,blood,league,noon,night,serif,simple,sky,solarized)
     */
    public function __construct($width = "100%", $height = "300px", $theme = "white") {
        if (!self::$_called) {
            $base = "../commun/src/js/reveal/";
            compact_css::get_instance()->add_css_file($base . "dist/reveal.css");
            compact_css::get_instance()->add_css_file($base . "dist/theme/$theme.css");
            foreach ([
        $base . "dist/reveal.js",
        $base . "plugin/highlight/highlight.js",
        $base . "plugin/markdown/markdown.js",
        $base . "plugin/math/math.js",
        $base . "plugin/notes/notes.js",
        $base . "plugin/search/search.js",
        $base . "plugin/zoom/zoom.js"
            ] as $src) {
                echo html_structures::script($src);
            }
            ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $(".reveal")
                            .css("width", '<?= $width ?>')
                            .css("height", '<?= $height ?>')
                            .css("margin", '0 auto')
                            .css("z-index", '0');
                    Reveal.initialize({
                        embedded: true,
                        plugins: [RevealHighlight, RevealMarkdown, RevealMath, RevealNotes, RevealSearch, RevealZoom]
                    });
                });
            </script>
            <?php
            self::$_called = true;
        }
    }

    /**
     * Ouvre un diaporama
     */
    public function start_reveal() {
        ?>
        <div class="reveal">
            <div class="slides">
                <?php
            }

            /**
             * Ferme le diaporama en cours
             */
            public function close_reveal() {
                ?>
            </div>
        </div>
        <?php
    }

}
