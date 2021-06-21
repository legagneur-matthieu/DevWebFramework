<?php

class docPHP_natives_js {

    private $_brush = "php; html-script: true";

    public function __construct() {
        ?>
        <p>La classe js permet d'exploiter un grand nombre de librairies javascript intégrées à DWF notamment pour les appliquer à des éléments html de la page.</p>
        <div id="accordion_classes_natives_js">
            <?php
            foreach (get_class_methods(__CLASS__) as $m) {
                if ($m != __FUNCTION__) {
                    ?>
                    <h5>js::<?php echo strtr($m, array("__" => ", ")); ?></h5>
                    <div><?php $this->$m(); ?></div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
        js::accordion("accordion_classes_natives_js", true, true);
    }

    public function before_title() {
        ?> 
        <p>
            Ajoute un préfixe au titre (balise title) de la page en cours.
        </p>
        <?php
        js::syntaxhighlighter("<?php js::before_title($" . "text); ?>", $this->_brush);
    }

    public function alert() {
        ?> 
        <p>Affiche un message à l'écran de l'utilisateur</p>
        <?php
        js::syntaxhighlighter("<?php js::alert($" . "msg);?>", $this->_brush);
    }

    public function alertify_alert() {
        ?> 
        <p>Affiche un message à l'écran de l'utilisateur (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::syntaxhighlighter("<?php js::alertify_alert($" . "msg); ?>", $this->_brush);
    }

    public function alertify_alert_redir() {
        ?> 
        <p>Affiche un message à l'écran de l'utilisateur avant redirection (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::syntaxhighlighter("<?php js::alertify_alert_redir($" . "msg, $" . "url); ?>", $this->_brush);
    }

    public function log_std() {
        ?> 
        <p>Affiche un message de log (standard) à l'écran de l'utilisateur (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::syntaxhighlighter("<?php js::log_std($" . "msg); ?>", $this->_brush);
    }

    public function log_success() {
        ?> 
        <p>Affiche un message de log (de succes) à l'écran de l'utilisateur (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::syntaxhighlighter("<?php js::log_success($" . "msg); ?>", $this->_brush);
    }

    public function log_error() {
        ?> 
        <p>Affiche un message de log (erreur) à l'écran de l'utilisateur (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::syntaxhighlighter("<?php js::log_error($" . "msg); ?>", $this->_brush);
    }

    public function redir() {
        ?> 
        <p>Redirige l'utilisateur vers l'url renseignée en paramètre (peut être un chemin relatif)</p>
        <?php
        js::syntaxhighlighter("<?php js::redir($" . "url); ?>", $this->_brush);
    }

    public function timer() {
        ?> 
        <p>Affiche un timer avant redirection</p>
        <?php
        js::syntaxhighlighter("<?php js::timer(86400, 'js_timer', 'index.php?page=web');?>\n"
                . "<p id=\"js_timer\"></p>", $this->_brush);
        ?>
        <p>Résultat : </p>
        <?php
        js::timer(86400, 'js_timer', 'index.php?page=web');
        ?>
        <p id="js_timer"></p>
        <?php
    }

    public function ckeditor() {
        ?> 
        <p>(cf form)</p>
        <?php
    }

    public function jsqr() {
        ?> 
        <p>Cette classe permet de lire un QRCode depuis une camera et afficher le resultat dans un élément HTML <br />
            Si l'élément HTML est un input alors le résultat deviendra la valeur de l'input <br />
            Il est déconseillé d'utiliser cette classe plusieurs fois dans la même page</p>
        <?php
        js::syntaxhighlighter("<?php \n"
                . "$" . "form=new form();\n"
                . "$" . "form->input('Mon input','mon_input');\n"
                . "echo $" . "form->render();\n"
                . "js::jsqr('mon_input');\n?>", $this->_brush);
    }

    public function vTicker() {
        ?> 
        <p>Créé un vTicker (suite de phrases qui défilent)</p>
        <?php
        js::syntaxhighlighter("<?php js::vTicker(array('Lorem Ipsum','...'), $" . "id='vticker'); ?>", $this->_brush);
    }

    public function datatable() {
        ?> 
        <p>Transforme un tableau HTML en Datatable</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "js::datatable($'.'id = 'datatable');\n"
                . "//création d'un tableau HTML avec html_structures (cf html_structures)\n"
                . "echo html_structures::table(array('#', 'Film', 'Année', 'Genre', 'Entrées (France)'), array(\n"
                . "        array(1, 'La Ligne Verte', '1999', 'Fantastique, Drame', '1 714 080'),\n"
                . "        array(2, 'Le Cinquième Élément', '1997', 'Science-Fiction', '7 727 697'),\n"
                . "        array(3, 'Matrix', '1999', 'Action, Science-Fiction', '2 426 543'),\n"
                . "    ), '', 'datatable');\n"
                . "?>", $this->_brush);
        ?>
        <p>Résultat : </p>
        <?php
        js::datatable($id = 'datatable');
        echo html_structures::table(array('#', 'Film', 'Année', 'Genre', 'Entrées (France)'), array(
            array(1, 'La Ligne Verte', '1999', 'Fantastique, Drame', '1 714 080'),
            array(2, 'Le Cinquième Élément', '1997', 'Science-Fiction', '7 727 697'),
            array(3, 'Matrix', '1999', 'Action, Science-Fiction', '2 426 543'),
                ), '', 'datatable');
    }

    public function syntaxhighlighter() {
        ?> 
        <p>Afficher du code formaté et stylisé par la librairie <a href="http://alexgorbatchev.com/SyntaxHighlighter/">SyntaxHightlighter</a> <br />
            code :
        </p>
        <?php
        debug::print_r("&lt;?php\n"
                . "js::syntaxhighlighter(\"&lt;?php\\n\"\n"
                . "    .\"//mon code php\\n\"\n"
                . "    .\"$\".\"a='Hello World';\\n\"\n"
                . "    .\"echo $\".\"a;\\n\"\n"
                . "    .\"?&gt;\", \"php; html-script: true\"); \n"
                . "?&gt;");
        ?>
        <p>Résultat : </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//mon code php\n"
                . "$" . "a='Hello World';\n"
                . "echo $" . "a;\n"
                . "?>", $this->_brush);
        ?><p>(Vous l'aurez compris, cette documentation utilise beaucoup de syntaxhighlighter)</p><?php
    }

    public function fancybox() {
        ?>
        <p>Cette classe permet l'affichage de galeries photos et vidéos via la librairie Fancybox <br />
            la clé "caption" dans le tableau $data est facultative.
        </p>
        <?php
        js::syntaxhighlighter("<style type='text/css'>\n"
                . "    #doc_fancybox {\n"
                . "        margin: 0 auto;\n"
                . "        width: 650px;\n"
                . "    }\n"
                . "    #doc_fancybox img {\n"
                . "        width : 300px;\n"
                . "        margin: 0 auto;\n"
                . "    }\n"
                . "</style>\n\n"
                . "<?php\n"
                . "js::fancybox('doc_fancybox', [\n"
                . "    ['small'=>'img/php.png', 'big'=>'img/php.png', 'caption'=>'<p>Logo PHP</p>' ],\n"
                . "    ['small'=>'img/cordova.png', 'big'=>'img/cordova.png', 'caption'=>'<p>Logo Cordova</p>']\n"
                . "]);\n"
                . "?>", $this->_brush);
        ?>
        <style type='text/css'>
            #doc_fancybox {
                margin: 0 auto;
                width: 650px;
            }
            #doc_fancybox img {
                width : 300px;
                margin: 0 auto;
            }
        </style>
        <?php
        js::fancybox('doc_fancybox', [
            ['small' => 'img/php.png', 'big' => 'img/php.png', 'caption' => '<p>Logo PHP</p>'],
            ['small' => 'img/cordova.png', 'big' => 'img/cordova.png', 'caption' => '<p>Logo Cordova</p>']
        ]);
    }

    public function freetile() {
        ?> 
        <p>(cf freetile)</p>
        <?php
    }

    public function stalactite() {
        ?> 
        <p>Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery <a href="http://jonobr1.com/stalactite/">"stalactite"</a></p>
        <?php
        js::syntaxhighlighter("<?php\n js::stalactite('stalactite'); ?>\n"
                . "<div id=\"stalactite\"></div>", $this->_brush);
    }

    public function shuffle_letters() {
        ?> 
        <p>Applique l'effet <a href="https://tutorialzine.com/2011/09/shuffle-letters-effect-jquery">"shuffleLetters"</a> à un élément au chargement de la page</p>
        <?php
        js::syntaxhighlighter("<?php js::shuffle_letters('shuffleLetters'); ?>\n"
                . "<p id=\"shuffleLetters\"></p>", $this->_brush);
    }

    public function dialog() {
        ?> 
        <p>Affiche la boite de dialogue de jquery-ui</p>
        <?php
        js::syntaxhighlighter("<?php js::dialog('js_dialog', 'Boite de dialogue', '<p>Ceci est une boite de dialogue JQuery</p>'); ?>", $this->_brush);
    }

    public function accordion() {
        ?> 
        <p>Applique un effet accordéon à une structure</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "<div id=\"js_accordion1\">\n"
                . "    <h3>accordéon1</h3>\n"
                . "    <div>\n"
                . "        <p>contenu accordéon 1</p>\n"
                . "            <div id = \"js_accordion2\">\n"
                . "            <h4>sous accordéon 1</h4>\n"
                . "            <div><p>contenu sous accordéon 1</p></div>\n"
                . "            <h4>sous accordéon 2</h4>\n"
                . "            <div><p>contenu sous accordéon 2</p></div>\n"
                . "         </div>\n"
                . "    </div>\n"
                . "    <h3>accordéon 2</h3>\n"
                . "    <div>\n"
                . "        <p>contenu accordéon 2</p>\n"
                . "    </div>\n"
                . "</div>\n"
                . "<?php\n"
                . "js::accordion('js_accordion1', true, true);\n"
                . "js::accordion('js_accordion2', true, true);\n"
                . "?>", $this->_brush);
        ?>
        <p>Résultat : </p>
        <div id = "js_accordion1">
            <h3>accordéon1</h3>
            <div>
                <p>contenu accordéon 1</p>
                <div id = "js_accordion2">
                    <h4>sous accordéon 1</h4>
                    <div><p>contenu sous accordéon 1</p></div>
                    <h4>sous accordéon 2</h4>
                    <div><p>contenu sous accordéon 2</p></div>
                </div>
            </div>
            <h3>accordéon 2</h3>
            <div>
                <p>contenu accordéon 2</p>
            </div>
        </div>
        <?php
        js::accordion('js_accordion1', true, true);
        js::accordion('js_accordion2', true, true);
        ?>
        <p>(Vous l'aurez compris, cette documentation utilise beaucoup d'accordéons)</p><?php
    }

    public function menu() {
        ?> 
        <p>Transforme une liste ul>li en menu</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "js::menu($" . "id = 'menu');\n"
                . "?>\n"
                . "<ul id=\"menu\">\n"
                . "    <li><a href=\"#menu\">Item 1</a></li>\n"
                . "    <li><a href=\"#menu\">Item 2</a></li>\n"
                . "    <li><a href=\"#menu\">Item 3</a></li>\n"
                . "</ul>", $this->_brush);
        ?>
        <p>Résultat : </p>
        <?php
        js::menu($id = 'menu');
        ?>
        <ul id="menu">
            <li><a href="#menu">Item 1</a></li>
            <li><a href="#menu">Item 2</a></li>
            <li><a href="#menu">Item 3</a></li>
        </ul>
        <?php
    }

    public function slider() {
        ?> 
        <p>Affiche le carousel/slide de bootstrap</p>
        <?php
        js::syntaxhighlighter("<style type=\"text/css\">\n"
                . "    #slider{\n"
                . "        width: 300px;\n"
                . "        margin: 0 auto;\n"
                . "    }\n"
                . "    #slider img{\n"
                . "        height: 300px;\n"
                . "    }\n"
                . "</style>\n"
                . "<?php\n"
                . "js::slider('slider', array(\n"
                . "    array(\"img\"=>\"img/php.png\", \"caption\"=>\"Logo PHP\"),\n"
                . "    array(\"img\"=>\"img/cordova.png\", \"caption\"=>\"Logo Cordova\")\n"
                . "    ));\n?>", $this->_brush);
        ?>
        <p>Résultat : </p>
        <style type="text/css">
            #slider{
                width: 300px;
                margin: 0 auto;
            }
            #slider img{
                height: 300px;
            }
        </style>
        <?php
        js::slider("slider", array(
            array("img" => "img/php.png", "caption" => "Logo PHP"),
            array("img" => "img/cordova.png", "caption" => "Logo Cordova")
        ));
    }

    private function wled() {
        ?>
        <p>Cette classe permet d'exploiter l'API HTTP de <a href="https://github.com/Aircoookie/WLED" target="_blank">WLED</a></p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//créé un objet WLED\n"
                . "var wled = new wled('192.168.1.10');\n"
                . "//change les couleur rouge, vert et bleu et envois la requête a WLED\n"
                . "wled.set_red(255).set_green(255).set_blue(255).exec();\n"
                . "//redémarre WLED\n"
                . "wled.reboot().exec();\n"
                . "?>", $this->_brush);
    }

}
