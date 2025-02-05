<?php

class docPHP_natives_js {

    public static function get_methods() {
        return get_class_methods(__CLASS__);
    }

    public function __construct() {
        ?>
        <p>La classe js permet d'exploiter un grand nombre de librairies javascript intégrées à DWF notamment pour les appliquer à des éléments html de la page.</p>
        <?php
        $functions = get_class_methods(__CLASS__);
        sort($functions);
        $ul = [];
        foreach ($functions as $js) {
            if (!in_array($js, ["get_methods", "__construct"])) {
                $ul[] = html_structures::a_link("index.php?page=web&doc=classes_natives&native=js&js=$js", strtr(ucfirst($js), array("_" => " ")));
            }
        }
        echo html_structures::ul($ul);
    }

    public static function before_title() {
        ?> 
        <p>
            Ajoute un préfixe au titre (balise title) de la page en cours.
        </p>
        <?php
        js::monaco_highlighter('<?php js::before_title($text); ?>');
    }

    public static function alert() {
        ?> 
        <p>Affiche un message à l'écran de l'utilisateur</p>
        <?php
        js::monaco_highlighter('<?php js::alert($msg);?>');
        $form = new form();
        $form->hidden("test", "1");
        $form->submit("btn-primary w-100", "Tester");
        echo $form->render();
        if (isset($_POST["test"])) {
            js::alert("Alerte de démonstation");
        }
    }

    public static function alertify_alert() {
        ?> 
        <p>Affiche un message à l'écran de l'utilisateur (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::monaco_highlighter('<?php js::alertify_alert($msg); ?>');
        $form = new form();
        $form->hidden("test", "1");
        $form->submit("btn-primary w-100", "Tester");
        echo $form->render();
        if (isset($_POST["test"])) {
            js::alertify_alert("Alerte de démonstation");
        }
    }

    public static function alertify_alert_redir() {
        ?> 
        <p>Affiche un message à l'écran de l'utilisateur avant redirection (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::monaco_highlighter('<?php js::alertify_alert_redir($msg, $url); ?>');
        $form = new form();
        $form->hidden("test", "1");
        $form->submit("btn-primary w-100", "Tester");
        echo $form->render();
        if (isset($_POST["test"])) {
            js::alertify_alert_redir("Alerte de démonstation", "");
        }
    }

    public static function log_std() {
        ?> 
        <p>Affiche un message de log (standard) à l'écran de l'utilisateur (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::monaco_highlighter('<?php js::log_std($msg); ?>');
        $form = new form();
        $form->hidden("test", "1");
        $form->submit("btn-primary w-100", "Tester");
        echo $form->render();
        if (isset($_POST["test"])) {
            js::log_std("Log de démonstation");
        }
    }

    public static function log_success() {
        ?> 
        <p>Affiche un message de log (de succès) à l'écran de l'utilisateur (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::monaco_highlighter('<?php js::log_success($msg); ?>');
        $form = new form();
        $form->hidden("test", "1");
        $form->submit("btn-primary w-100", "Tester");
        echo $form->render();
        if (isset($_POST["test"])) {
            js::log_success("Log de démonstation");
        }
    }

    public static function log_warning() {
        ?> 
        <p>Affiche un message de log (avertissement) à l'écran de l'utilisateur (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::monaco_highlighter('<?php js::log_warning($msg); ?>');
        $form = new form();
        $form->hidden("test", "1");
        $form->submit("btn-primary w-100", "Tester");
        echo $form->render();
        if (isset($_POST["test"])) {
            js::log_warning("Log de démonstation");
        }
    }
    
    public static function log_error() {
        ?> 
        <p>Affiche un message de log (erreur) à l'écran de l'utilisateur (utilise la librairie <a href="http://www.alertifyjs.com/">alertyfy</a>)</p>
        <?php
        js::monaco_highlighter('<?php js::log_error($msg); ?>');
        $form = new form();
        $form->hidden("test", "1");
        $form->submit("btn-primary w-100", "Tester");
        echo $form->render();
        if (isset($_POST["test"])) {
            js::log_error("Log de démonstation");
        }
    }

    public static function redir() {
        ?> 
        <p>Redirige l'utilisateur vers l'url renseignée en paramètre (peut être un chemin relatif)</p>
        <?php
        js::monaco_highlighter('<?php js::redir($url); ?>');
    }

    public static function timer() {
        ?> 
        <p>Affiche un timer avant redirection</p>
        <?php
        js::monaco_highlighter('<?php js::timer(86400, "js_timer", "index.php?page=web");?>\n'
                . '<p id="js_timer"></p>');
        ?>
        <p>Résultat : </p>
        <?php
        js::timer(86400, "js_timer", "index.php?page=web");
        ?>
        <p id="js_timer"></p>
        <?php
    }

    public static function ckeditor() {
        docPHP_natives::ckeditor();
    }

    public static function jsqr() {
        ?> 
        <p>Cette classe permet de lire un QRCode depuis une caméra et afficher le résultat dans un élément HTML.<br />
            Si l'élément HTML est un input alors le résultat deviendra la valeur de l'input.<br />
            Il est déconseillé d'utiliser cette classe plusieurs fois dans la même page</p>
        <?php
        js::jsqr("qrcode");
        js::monaco_highlighter('<?php\n'
                . '$form=new form();\n'
                . '$form->input("Mon input","mon_input");\n'
                . 'echo $form->render();\n'
                . 'js::jsqr("mon_input");\n?>');
        $form = new form();
        $form->input("QRCode", "qrcode");
        echo $form->render();
    }

    public static function vTicker() {
        ?> 
        <p>Créé un vTicker (suite de phrases qui défilent)</p>
        <?php
        js::monaco_highlighter('<?php js::vTicker(array("Lorem Ipsum","..."), $id="vticker"); ?>');
        echo html_structures::hr();
        js::vTicker([
            lorem_ipsum::generate(10, true),
            lorem_ipsum::generate(10),
            lorem_ipsum::generate(10),
            lorem_ipsum::generate(10),
            lorem_ipsum::generate(10)
        ]);
    }

    public static function datatable() {
        ?> 
        <p>Transforme un tableau HTML en Datatable</p>
        <?php
        js::datatable($id = 'datatable');
        js::monaco_highlighter('<?php \n'
                . 'js::datatable($id = "datatable");\n'
                . '//création d\'un tableau HTML avec html_structures (cf html_structures)\n'
                . 'echo html_structures::table(array("#", "Film", "Année", "Genre", "Entrées (France)"), array(\n'
                . '        array(1, "La Ligne Verte", "1999", "Fantastique, Drame", "1 714 080"),\n'
                . '        array(2, "Le Cinquième Élément", "1997", "Science-Fiction", "7 727 697"),\n'
                . '        array(3, "Matrix", "1999", "Action, Science-Fiction", "2 426 543"),\n'
                . '    ), "", "datatable");\n'
                . '?>');
        ?>
        <p>Résultat : </p>
        <?php
        echo html_structures::table(array('#', 'Film', 'Année', 'Genre', 'Entrées (France)'), array(
            array(1, 'La Ligne Verte', '1999', 'Fantastique, Drame', '1 714 080'),
            array(2, 'Le Cinquième Élément', '1997', 'Science-Fiction', '7 727 697'),
            array(3, 'Matrix', '1999', 'Action, Science-Fiction', '2 426 543'),
                ), '', 'datatable');
    }

    public static function monaco_highlighter() {
        ?> 
        <p>Afficher du code formaté et stylisé par la librairie <a href="http://alexgorbatchev.com/SyntaxHighlighter/">SyntaxHightlighter</a> <br />
            code :
        </p>
        <?php
        debug::print_r("&lt;?php\n"
                . "js::monaco_highlighter('&lt;?php\\n'\n"
                . "    .'//mon code php\\n'\n"
                . "    .'$\".\"a='Hello World';\\n'\n"
                . "    .'echo $\".\"a;\\n'\n"
                . "    .'?&gt;', \"php\"); \n"
                . "?&gt;");
        ?>
        <p>Résultat : </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//mon code php\n'
                . '$a="Hello World";\n'
                . 'echo $a;\n'
                . '?>');
        ?>
        <p>(Vous l'aurez compris, cette documentation utilise beaucoup de monaco_highlighter)</p>
        <p class="alert alert-warning">
            Attention : Monaco a tendance a rentrer en conflit avec d'autres librairie JS et JQuery ! <br>
            des solutions existe comme :<br>
            - appelé monaco après toutes les autres librairies dans la page<br>
            - vpage (page virtuel/iframe)<br>
        </p>    
        <?php
    }

    public static function fancybox() {
        ?>
        <p>Cette classe permet l'affichage de galeries photos et vidéos via la librairie Fancybox <br />
            la clé "caption" dans le tableau $data est facultative.
        </p>
        <?php
        js::monaco_highlighter('<style type="text/css">\n'
                . '    #doc_fancybox {\n'
                . '        margin: 0 auto;\n'
                . '        width: 650px;\n'
                . '    }\n'
                . '    #doc_fancybox img {\n'
                . '        width : 300px;\n'
                . '        margin: 0 auto;\n'
                . '    }\n'
                . '</style>\n\n'
                . '<?php\n'
                . 'js::fancybox("doc_fancybox", [\n'
                . '    ["small"=>"img/php.png", "big"=>"img/php.png", "caption"=>"<p>Logo PHP</p>" ],\n'
                . '    ["small"=>"img/cordova.png", "big"=>"img/cordova.png", "caption"=>"<p>Logo Cordova</p>"]\n'
                . ']);\n'
                . '?>');
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
        js::fancybox("doc_fancybox", [
            ["small" => "img/php.png", "big" => "img/php.png", "caption" => "<p>Logo PHP</p>"],
            ["small" => "img/cordova.png", "big" => "img/cordova.png", "caption" => "<p>Logo Cordova</p>"]
                ], 6);
    }

    public static function freetile() {
        ?>
        <p>Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "freetile". <br />
            Il est préferable d'appeler cette librairie via <em>js::freetile()</em> <br />
            Les sous éléments peuvent être des images ou des DIV de différentes tailles.
        </p>
        <?php
        js::freetile("freetile");
        js::monaco_highlighter('<?php js::freetile("freetile"); ?>\n'
                . '<div id="freetile"></div>');
        $div = tags::div(["id" => "freetile"], "");
        for ($i = 0; $i < 100; $i++) {
            $width = rand(1, 10) * 10;
            $height = rand(1, 10) * 10;
            $r = rand(10, 255);
            $g = rand(10, 255);
            $b = rand(10, 255);
            $div->append_content(tags::tag("div", ["style" => "width:{$width}px;height:{$height}px;background-color:rgb({$r},{$g},{$b});"], ""));
        }
        ?>
        <hr>
        <p>Exemple avec 100 div généré aléatoirement :</p>
        <?php
        echo $div;
    }

    public static function stalactite() {
        ?> 
        <p>Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery <a href="http://jonobr1.com/stalactite/">"stalactite"</a></p>
        <?php
        js::stalactite("stalactite");
        js::monaco_highlighter('<?php\n js::stalactite("stalactite"); ?>\n'
                . '<div id="stalactite"></div>');
        $div = tags::div(["id" => "stalactite"], "");
        for ($i = 0; $i < 100; $i++) {
            $width = rand(1, 10) * 10;
            $height = rand(1, 10) * 10;
            $r = rand(10, 255);
            $g = rand(10, 255);
            $b = rand(10, 255);
            $div->append_content(tags::tag("div", ["style" => "width:{$width}px;height:{$height}px;background-color:rgb({$r},{$g},{$b});"], ""));
        }
        ?>
        <hr>
        <p>Exemple avec 100 div généré aléatoirement :</p>
        <?php
        echo $div;
    }

    public static function summernote() {
        ?> 
        <p>Applique un éditeur Summernote (WYSIWYG) à un textarea</p>
        <?php
        js::summernote("summernote");
        js::monaco_highlighter('<?php\n '
                . '$form = new form();\n '
                . '$form->textarea("Summernote", "summernote");\n '
                . 'echo $form->render();\n '
                . '$sn=js::summernote("summernote");\n'
                . 'if(isset($_POST["summernote"])){\n'
                . '    $value=$sn->parse();\n'
                . '}');
        echo html_structures::hr();
        $form = new form();
        $form->textarea("Summernote", "summernote");
        echo $form->render();
    }

    public static function shuffle_letters() {
        ?> 
        <p>Applique l'effet <a href="https://tutorialzine.com/2011/09/shuffle-letters-effect-jquery">"shuffleLetters"</a> à un élément au chargement de la page</p>
        <?php
        js::shuffle_letters("shuffleLetters");
        js::monaco_highlighter('<?php js::shuffle_letters("shuffleLetters"); ?>\n'
                . '<p id="shuffleLetters"></p>');
        echo html_structures::hr() . tags::tag("p", ["id" => "shuffleLetters"], lorem_ipsum::generate(100, true));
    }

    public static function dialog() {
        ?> 
        <p>Affiche la boite de dialogue de jquery-ui</p>
        <?php
        js::monaco_highlighter('<?php js::dialog("js_dialog", "Boite de dialogue", "<p>Ceci est une boite de dialogue JQuery</p>"); ?>');
        $form = new form();
        $form->hidden("test", "1");
        $form->submit("btn-primary w-100", "Tester");
        echo $form->render();
        if (isset($_POST["test"])) {
            js::dialog("js_dialog", "Boite de dialogue", "<p>Ceci est une boite de dialogue JQuery</p>");
        }
    }

    public static function accordion() {
        ?> 
        <p>Applique un effet accordéon à une structure</p>
        <?php
        js::monaco_highlighter(''
                . '<div id="js_accordion1">\n'
                . '    <h3>accordéon1</h3>\n'
                . '    <div>\n'
                . '        <p>contenu accordéon 1</p>\n'
                . '            <div id = "js_accordion2">\n'
                . '            <h4>sous accordéon 1</h4>\n'
                . '            <div><p>contenu sous accordéon 1</p></div>\n'
                . '            <h4>sous accordéon 2</h4>\n'
                . '            <div><p>contenu sous accordéon 2</p></div>\n'
                . '         </div>\n'
                . '    </div>\n'
                . '    <h3>accordéon 2</h3>\n'
                . '    <div>\n'
                . '        <p>contenu accordéon 2</p>\n'
                . '    </div>\n'
                . '</div>\n'
                . '<?php\n'
                . 'js::accordion("js_accordion1", true, true);\n'
                . 'js::accordion("js_accordion2", true, true);\n'
                . '?>');
        ?>
        <hr>
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
        js::accordion("js_accordion1", true, true);
        js::accordion("js_accordion2", true, true);
        ?>
        <p>(Vous l'aurez compris, cette documentation utilise beaucoup d'accordéons)</p><?php
    }

    public static function menu() {
        ?> 
        <p>Transforme une liste ul>li en menu</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'js::menu($id = "menu");\n'
                . '?>\n'
                . '<ul id="menu">\n'
                . '    <li><a href="#menu">Item 1</a></li>\n'
                . '    <li><a href="#menu">Item 2</a></li>\n'
                . '    <li><a href="#menu">Item 3</a></li>\n'
                . '</ul>');
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

    public static function slider() {
        ?> 
        <p>Affiche le carousel/slide de bootstrap</p>
        <?php
        js::monaco_highlighter('<style type="text/css">\n'
                . '    #slider{\n'
                . '        width: 300px;\n'
                . '        margin: 0 auto;\n'
                . '    }\n'
                . '    #slider img{\n'
                . '        height: 300px;\n'
                . '    }\n'
                . '</style>\n'
                . '<?php\n'
                . 'js::slider("slider", array(\n'
                . '    array("img"=>"img/php.png", "caption"=>"Logo PHP"),\n'
                . '    array("img"=>"img/cordova.png", "caption"=>"Logo Cordova")\n'
                . '    ));\n?>');
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

    public static function wled() {
        ?>
        <p>Cette classe permet d'exploiter l'API HTTP de <a href="https://github.com/Aircoookie/WLED" target="_blank">WLED</a></p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//créé un objet WLED\n'
                . 'var wled = new wled("192.168.1.10");\n'
                . '//change les couleur rouge, vert et bleu et envois la requête a WLED\n'
                . 'wled->set_red(255)->set_green(255)->set_blue(255)->exec();\n'
                . '//redémarre WLED\n'
                . 'wled->reboot()->exec();\n'
                . '?>');
    }
}
