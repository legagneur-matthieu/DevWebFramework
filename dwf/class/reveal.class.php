<?php

/**
 * Cette classe permet de créer un diaporama avec la librairie reveal
 * Il n'est pas recommandé d'avoir plusieurs diaporamas sur la même page !
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class reveal {

    /**
     * Cette classe permet de créer un diaporama avec la librairie reveal, Il n'est pas recommandé d'avoir plusieurs diaporamas sur la même page !
     * Les diaporamas s'organisent avec des balises "section"
     * @param int $width Largeur en px des slides
     * @param int $height Hauteur en px des slides
     * @param string $theme Théme des slides (white par defaut, beige,black,blood,league,noon,night,serif,simple,sky,solarized)
     */
    public function __construct($width = 600, $height = 600, $theme = "white") {
        compact_css::get_instance()->add_css_file("../commun/src/js/reveal/css/reveal.css");
        compact_css::get_instance()->add_css_file("../commun/src/js/reveal/css/theme/" . $theme . ".css");
        compact_css::get_instance()->add_css_file("../commun/src/js/reveal/css/reveal_correctif.css");
        echo html_structures::script("../commun/src/js/reveal/lib/js/classList.js") .
        html_structures::script("../commun/src/js/reveal/lib/js/head.min.js") .
        html_structures::script("../commun/src/js/reveal/js/reveal.js");
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $(".reveal .controls").ready(function () {
                    $(".reveal,.slides").css("width", "<?= $width; ?>px").css("height", "<?= $height; ?>px");
                    $(".reveal .controls").css("margin-top", "<?= (-800 + $height); ?>px").css("margin-left", "<?= ($width - 100); ?>px");
                });
                Reveal.initialize({
                    controls: true,
                    progress: false,
                    history: true,
                    center: true,
                    transition: 'slide', // none/fade/slide/convex/concave/zoom

                    // Optional reveal.js plugins
                    dependencies: [
                        {src: '../commun/src/js/reveal/lib/js/classList.js', condition: function () {
                                return !document.body.classList;
                            }},
                        {src: '../commun/src/js/reveal/plugin/markdown/marked.js', condition: function () {
                                return !!document.querySelector('[data-markdown]');
                            }},
                        {src: '../commun/src/js/reveal/plugin/markdown/markdown.js', condition: function () {
                                return !!document.querySelector('[data-markdown]');
                            }},
                        {src: '../commun/src/js/reveal/plugin/highlight/highlight.js', async: true, callback: function () {
                                hljs.initHighlightingOnLoad();
                            }},
                        {src: '../commun/src/js/reveal/plugin/zoom-js/zoom.js', async: true},
                        {src: '../commun/src/js/reveal/plugin/notes/notes.js', async: true}
                    ]
                });
            });
        </script>
        <?php
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
