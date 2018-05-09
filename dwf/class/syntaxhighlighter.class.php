<?php

/**
 * Cette classe permet d'afficher du code formaté et stylisé par la librairie SyntaxHightlighter
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class syntaxhighlighter {

    /**
     * Permet de vérifier que la librairie syntaxhighlighter a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie syntaxhighlighter a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe permet d'afficher du code formaté et stylisé par la librairie SyntaxHightlighter
     *
     * @param strig $code Le code à afficher
     * @param string $brush Le brush est lié au langage a utiliser (js par défaut) <br />
     * Astuce script html/php : "php; html-script: true" <br />
     *  http://alexgorbatchev.com/SyntaxHighlighter/manual/brushes/
     * @param string $theme Le thème de SyntaxHightlighter à utiliser http://alexgorbatchev.com/SyntaxHighlighter/manual/themes/
     */
    public function __construct($code, $brush = "js", $theme = "Default") {
        if (!self::$_called) {
            ?><script type="text/javascript" src="../commun/src/js/syntaxhighlighter/scripts/shCore.js"></script><?php
            foreach (glob("../commun/src/js/syntaxhighlighter/scripts/shBrush*.js") as $fbrush) {
                ?><script type="text/javascript" src="<?php echo $fbrush; ?>"></script><?php
            }
            echo html_structures::link_in_body("../commun/src/js/syntaxhighlighter/styles/shCore" . $theme . ".css");
            echo html_structures::link_in_body("../commun/src/js/syntaxhighlighter/styles/shTheme" . $theme . ".css");
            echo html_structures::link_in_body("../commun/src/css/syntaxhighlighter.css");
            ?> 
            <script type="text/javascript">
                $(document).ready(function () {
                    SyntaxHighlighter.config.tagName = "code";
                    SyntaxHighlighter.defaults['toolbar'] = false;
                    SyntaxHighlighter.all();
                });</script> 
            <?php
            self::$_called = true;
        }
        ?>
        <code class="brush: <?php echo $brush; ?>"><?php echo htmlspecialchars($code); ?></code>
        <?php
    }

}
