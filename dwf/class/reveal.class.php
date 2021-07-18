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
     * @param int $width Largeur en px des slides
     * @param int $height Hauteur en px des slides
     * @param string $theme Théme des slides (white par defaut, beige,black,blood,league,noon,night,serif,simple,sky,solarized)
     */
    public function __construct($width = 600, $height = 600, $theme = "white") {
        if (!self::$_called) {
            $base = "../commun/src/js";
            compact_css::get_instance()->add_css_file("$base/reveal/dist/reveal.css");
            compact_css::get_instance()->add_css_file("$base/reveal/dist/theme/$theme.css");
            compact_css::get_instance()->add_css_file("$base/reveal/plugin/highlight/monokai.css");
            foreach ([
        "$base/reveal/dist/reveal.js",
        "$base/reveal/plugin/highlight/highlight.js",
        "$base/reveal/plugin/markdown/markdown.js",
        "$base/reveal/plugin/math/math.js",
        "$base/reveal/plugin/notes/notes.js",
        "$base/reveal/plugin/search/search.js",
        "$base/reveal/plugin/zoom/zoom.js"
            ] as $src) {
                echo html_structures::script($src);
            }
            ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $(".reveal")
                            .css("width", '<?= $width ?>px')
                            .css("height", '<?= $height ?>px')
                            .css("margin", '0 auto')
                            .css("z-index", '0');
                    Reveal.initialize({
                        width: <?= $width ?>,
                        height: <?= $height ?>,
                        margin: 0,
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
