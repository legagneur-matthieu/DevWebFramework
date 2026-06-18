<?php

class docPHP_natives_df {

    public static function datatable() {
        ?>
        <p>Cette classe permet de convertir un tableau HTML en datatable (jquery) <br />
            il est préférable de l'appeler via <em>js::datatable()</em> (cf js)
        </p>
        <hr>
        <h3 class="text-center">js::datatable</h3>
        <hr>
        <?php
        docPHP_natives_js::datatable();
    }

    public static function ddg__ddg_api() {
        ?>
        <p>La classe ddg permet d'exploiter le moteur de recherche DuckDuckGO</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Affiche une searchbar de DuckDuckGO\n' .
                '(new ddg())->print_searchbar();\n\n' .
                '//retourne un objet ddg_api qui contient les résultats d\'une recherche\n' .
                '$ddg_api=(new ddg())->api("DuckDuckGO");\n' .
                '?>');
        ?>
        <p>Résultat :</p>
        <div class="row" style="overflow: visible; height: 300px;">
            <div class="col-sm-5">
                <?php
                (new ddg())->print_searchbar();
                ?>
            </div>
            <div class="col-sm-7"> 
                <style type="text/css">
                    #ddg_api{
                        border-left: black solid 1px;
                        padding-left: 10px;
                    }
                    #ddg_api>pre{
                        overflow-y: visible;
                        height: 300px;
                    }
                </style>
                <div id="ddg_api">
                    <?php
                    debug::print_r((new ddg())->api("DuckDuckGO"));
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    public static function debug() {
        ?>
        <p>Cette classe est une boîte à outils de débogage</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Affiche la structure d\'une variable ( optimisée pour les arrays et objets )\n' .
                'debug::print_r($var);\n\n' .
                '//Affiche le contenu et le type d\'une variable ( optimisée pour les type nombres, chaînes de caractères et les booléans )\n' .
                'debug::var_dump($var);\n\n' .
                '//Affiche la trace de l\'application pour arriver au point de débug ( trace des fichiers et méthodes qui ont été appelés)\n' .
                'debug::get_trace();\n\n' .
                '//Affiche le rapport d\'activités de PHP en bas de page\n' .
                'debug::show_report();\n' .
                '?>');
    }

    public static function dictionary() {
        ?>
        <p>Cette classe permet de convertir et gérer une liste lourde comme étant un dictionnaire</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//créé un dictionnaire\n' .
                '$dictionnaire = new dictionary($words = ["All", "My", "Words"], $chunk_size = 100000);\n\n' .
                '//Ajoute des mots\n' .
                '$dictionnaire->add(["New", "Elements"]);\n\n' .
                '//Vérifie si un mot existe\n' .
                '$dictionnaire->word_exist("Words");\n\n' .
                '//Supprime des mots\n' .
                '$dictionnaire->remove(["Words", "Elements"]);\n\n' .
                '//Nombre de mots dans le dictionnaire\n' .
                '$dictionnaire->count_words();\n\n' .
                '//Gestion de la taille des sections du dictionnaire\n' .
                '$dictionnaire->set_chunk_size($chunk_size = 100000);' .
                '$dictionnaire->get_chunk_size();\n\n' .
                '//Accès au sections\n' .
                '$dictionnaire->count_sections();\n' .
                '$dictionnaire->get_section($key = 0);\n' .
                '?>');
    }

    public static function dlc() {
        ?><p>Cette classe permet de générer des fichiers :</p><?php
        echo html_structures::ul([
            "DLC (Download Link Container, recommandé)",
            "CCF (CryptLoad Container File)",
            "RSDF (RapidShare Download File)"
        ]);
        ?><p>Servant de librairie de téléchargement pour des logiciels comme <?= html_structures::a_link("http://jdownloader.org", "JDownloader") ?></p><?php
        js::monaco_highlighter('<?php\n' .
                '$data=[\n' .
                '    "http://url/image1.jpg",\n' .
                '    "http://url/image2.jpg",\n' .
                '    "http://url/image3.jpg"\n' .
                '];\n' .
                '//Génère un fichier DLC\n' .
                'dlc::generate_DLC("monDLC.dlc", $data);\n\n' .
                '//Génère un fichier CCF\n' .
                'dlc::generate_CCF("monCCF.ccf", $data);\n\n' .
                '//Génère un fichier RSDF\n' .
                'dlc::generate_RSDF("monRSDF.rsdf", $data);\n' .
                '?>');
    }

    public static function downloader() {
        ?><p>Cette classe permet à l'utilisateur de télécharger un fichier spécifique sur le serveur</p><?php
        js::monaco_highlighter('<?php\n' .
                '//Vide le cache des fichiers téléchargeable\n' .
                'downloader::clear();\n\n' .
                '//rend un fichier du serveur téléchargeable \n' .
                '//et affiche un bouton de téléchargement\n' .
                'echo downloader::file($fullPathFile, $btn_txt = "Télécharger");\n' .
                '?>');
    }

    public static function dwf_exception() {
        ?> 
        <p>Cette classe gère les exceptions du framework <br />
            les codes d'erreurs des exceptions natives du framework sont compris entre 600 et 699 :</p>
        <ul>
            <li>les codes d'erreurs 60X concernent la base de données</li>
            <li>les codes d'erreurs 61X concernent les routes et méthodes</li>
            <li>les codes d'erreurs 62X concernent les services</li>
            <li>les codes d'erreurs 63X concernent le système</li>
        </ul>
        <p>Il est cependant possible pour vous de créer des exceptions personnalisées avec dwf_exeption :</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$code_erreur=700;\n' .
                '$text_erreur="un message d\'erreur";\n' .
                '//Affiche une exception qui n\'interrompt pas le script en cours\n' .
                'dwf_exception::warning_exception($code_erreur, array("msg"=>"$text_erreur"));\n\n' .
                '//Lance une exception qui interrompt le script en cours\n' .
                'dwf_exception::throw_exception($code_erreur, array("msg"=>"$text_erreur"));\n\n' .
                '//try catch pour gérer et afficher une exception\n' .
                'try {\n' .
                '    //conditions menant à une exception\n' .
                '} catch (Exception $e) {\n' .
                '    dwf_exception::print_exception($e);\n' .
                '}\n' .
                '?>');
        ?>
        <p>Les exceptions sont toujours retranscrites dans un log : <em>dwf/log/log_[votre_projet]_[date_format_us].txt</em></p>
        <p>Exemple de <em>dwf_exception::warning_exception()</em> :</p>
        <div class="alert alert-danger" role="alert">
            <p>DWF EXCEPTION ! Code 700 : "Exemple d'exception"</p>
            <pre class="border alert alert-light"><?= "#0 /var/www/html/doc/class/docPHP_natives.class.php(294): dwf_exception::warning_exception('700', Array)
#1 /var/www/html/doc/class/docPHP_natives.class.php(21): docPHP_natives->dwf_exception()
#2 /var/www/html/doc/class/docPHP.class.php(427): docPHP_natives->__construct()
#3 /var/www/html/doc/class/docPHP.class.php(38): docPHP->classes_natives()
#4 /var/www/html/doc/class/pages.class.php(91): docPHP->__construct()
#5 /var/www/dwf/class/application.class.php(126): pages->web()
#6 /var/www/dwf/class/application.class.php(44): application->contenu()
#7 /var/www/dwf/index.php(20): application->__construct()
#8 /var/www/html/doc/index.php(18): index->__construct()
#9 /var/www/html/doc/index.php(40): website->__construct()
#10 {main}"; ?></pre>
        </div>
        <?php
    }

    public static function easteregg() {
        ?><p>Cette classe permet d'afficher des "oeufs de Pâques" qui s'affichent à certaines dates de l'année. <br />
            Liste des dates : </p>
        <ul>
            <li>31/12 et 01/01 : Jour de l'an, affiche une guirlande interactive (les ampoules éclatent au survol de la souris)</li>
            <li>06/01 : Epiphanie, affiche une couronne des rois en pied de page</li>
            <li>14/02 : Saint Valentin, une pluie de coeurs sur le site</li>
            <li>21/03 : Printemps, des pétales de cerisiers tombent sur le site</li>
            <li>01/04 : Poisson d'Avril ! un poisson animé traverse l'écran</li>
            <li>01/05 : Fête du travail, une couronne de muguet en pied de page</li>
            <li>Entre le 25/05 et 31/05 : Fête des mères (dernier dimanche de mai), un collier de nouilles "bonne fête maman" en pied de page</li>
            <li>21/06 : Été, la lueur chaude du soleil apparait en pied de page</li>
            <li>Entre le 16/05 et 22/06 : Fête des pères (troisième dimanche de mai) un modal avec le diplôme du père de l'année apparait (qu'une fois par session)</li>
            <li>14/07 : Fête Nationale française, des feux d'artifices sont tirés sur le site</li>
            <li>23/09 : Automne, des feuilles mortes tombent sur le site</li>
            <li>25/11 : Sainte-Catherine, un chapeau de Sainte-Catherine en pied de page</li>
            <li>Du 22/12 aux 25/15 : Hiver et Noël, des flocons de neiges tombent sur le site</li>
        </ul>
        <p>Astuce : une interface pour activer les eastereggs peut être affichée en tapant le "Konami code" : ↑ ↑ ↓ ↓ ← → ← → B A</p>
        <p>Pour activer les eastereggs sur toutes les pages, placez cette ligne dans <em>pages->header()</em></p>
        <?php
        new easteregg();
        js::monaco_highlighter("<?php new easteregg(); ?>");
    }

    public static function entity_generator() {
        ?>
        <p class="alert alert-danger">
            Note de version 21.24.04 : <br>
            Comme expliqué plus en détail dans la section BDD la gestion des requêtes a changé,<br>
            il est recomandé de regénérer vos entity si elles ont été généré avant cette version !
        </p>
        <p>
            Les entités font office d'ORM dans votre projet,<br />
            une classe entité vous permet de lire, ajouter, modifier ou supprimer des entrées de votre base de données sans avoir à saisir une requête SQL <br />
            (ormis une eventuelle condition "where" ). Les entités exploitent un objet bdd accessible via <em>application::$_bdd</em> <br />
            les entités seront capable de recréer la structure de leur base de données si celle-ci est perdue (mais ne permettent pas de sauvegarder les données ).
        </p>
        <h4>Créer des entités</h4>
        <p>
            La création d'une entité est simple, il est conseillé de mettre la création d'entité soit dans le constructeur de pages.class.php <br />
            soit dans le constructeur de la classe métier qui exploitera cette entité. Voici le code (consultez la documentation technique pour plus d'informations)
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$data=array(                         //$data est un tableau à deux dimensions qui definit\n'
                . '                                     //la structure de l\'entité et de sa table dans la base de données\n\n'
                . '    array("id","int",true),          //créé un champ/attribut nommé "id" de type entier,\n'
                . '                                     //le "true" indique une clé primaire, le setter de "id" sera en privé\n'
                . '                                     //Depuis la version 21.22.10 cette clé primaire est automatiquement renseigné,\n'
                . '                                     //il n\'est donc plus nessaicaire de la déclarer dans le code.\n'
                . '    array("login","string",false),\n'
                . '    array("psw","string",false),\n'
                . ');\n'
                . '$table="user";                       //nom de la table et de l\'entité\n\n'
                . 'new entity_generator($data, $table); //Créé l\'entité et sa table si elle n\'existe pas\n'
                . '                                     //Attention : si la structure de l\'entité est modifié \n'
                . '                                     //il faudra supprimer la classe et la table de l\'entité\n'
                . '?>');
        ?>
        <p>
            Créer des relations entres les entités et astuces pour créer plusieurs entités facilement
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$datas=array(\n'
                . '    "rang"=>array( //on créé une entité rang\n'
                . '        array("id","int",true),\n'
                . '        array("nom","string",false),\n'
                . '    ),\n'
                . '    "user"=>array( //on créé une entité user\n'
                . '        array("id","int",true),\n'
                . '        array("login","string",false),\n'
                . '        array("psw","string",false),\n'
                . '        array("rang","rang",false), //on met en relation le fait qu\'un user a un rang (de type "rang")\n'
                . '    ),\n'
                . ');\n'
                . 'foreach($datas as $table => $data){\n'
                . '    new entity_generator($data, $table);\n'
                . '}\n'
                . '?>'
        );
        ?>
        <h4>MAJ 21.18.02</h4>
        <p>Depuis la version 21.18.02, il est possible de créer l'ensemble de vos entités ainsi :</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'entity_generator::generate([\n'
                . '    "rang"=>[ //on créé une entité rang\n'
                . '        ["id","int",true],\n'
                . '        ["nom","string",false],\n'
                . '    ],\n'
                . '    "user"=>[ //on créé une entité user\n'
                . '        ["id","int",true],\n'
                . '        ["login","string",false],\n'
                . '        ["psw","psw",false], //password géré automatiquement depuis 21.25.02\n'
                . '        ["rang","rang",false], //on met en relation le fait qu\'un user a un rang (de type \'rang\')\n'
                . '    ],\n'
                . ']);\n'
                . '?>'
        );
        ?>
        <h4>Utilisation des entités</h4>
        <p>Une fois les entités créées, elles peuvent être utilisées (nous utiliserons l'exemple des 'user' et 'rang')</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//ajoute un utilisateur\n'
                . 'user::ajout($login, application::hash($psw)); //avant la 21.25.02\n\n'
                . 'user::ajout($login, $psw) //depuis a 21.25.02;\n\n'
                . '//récuperer tout les utilisateurs sous forme de tableaux de données\n'
                . '$users = user::get_table_array();\n'
                . 'echo $users[0]["login"]; //affiche le login du premier utilisateur de la table\n\n'
                . '//récuperer tout les utilisateurs du rang 1 (utilisation d\'une requête préparé cf. bdd)\n'
                . '$users = user::get_table_array("rang=:rang",[":rang"=>1]);\n\n'
                . '//astuce pour récuperer tout les utilisateurs par ordre alphabétique de login\n'
                . '$users = user::get_table_array("1=1 order by login");\n\n'
                . '//récuperer tout les rang sous forme de table ordonnée par leur ID (les id sont les clés du tableau)\n'
                . '$rangs = rang::get_table_ordored_array();\n'
                . 'echo $rangs[1]["nom"]; //affiche le nom du rang ayant l\'identifiant 1\n\n'
                . '//récuperer les utilisateurs sous forme de collection (tableau d\'objets)\n'
                . '//DECONSEILLÉ ! potentiellement lourd !\n'
                . '$users = user::get_collection();\n'
                . 'echo $users[0]->get_rang()->get_nom(); //affiche le nom du rang du premier utilisateur de la table\n\n'
                . '//récupere l\'objet d\'un utilisateur à partir de son id\n'
                . '$user = user::get_from_id(1);\n'
                . 'echo $user->get_login(); //affiche le login de l\'utilisateur 1\n'
                . '$user->set_login($nouveau_login); //redéfinit le login de l\'utilisateur 1,\n'
                . '                                  //la modification dans la base de données sera prise en compte à la fin du script\n\n'
                . '//supprimer un utilisateur : 2 solutions\n'
                . '//1 : supprimer un utilisateur non instancié depuis son id\n'
                . 'user::delete_by_id($id);\n'
                . '//2 : supprimer un utilisateur instancié\n'
                . '$user->delete();'
                . '?>');
        ?>
        <p class="alert alert-danger">
            ATTENTION : si vous utilisez des variables dans les paramètres $where : <br>
            utilisez le tableau $params afin d'utiliser les requêtes préparé et vous protéger des injections SQL <br />
            pensez également à la fonction <a href="https://secure.php.net/manual/fr/function.strip-tags.php" target="_blank">strip_tags</a> pour vous protéger des failles XSS.
        </p>
        <h4>Les types de champ/attribut</h4>
        <?php
        echo html_structures::table(["Type (code PHP)", "Type (SQL)", "Description"], [
            ["int, integer", "int(11)", "un champ de nombre entiers"],
            ["bool, boolean", "int(1)", "0 ou 1"],
            ["string", "text", "un champ de texte, peut contenir aussi du HTML, des dates, ou des nombres"],
            ["mail", "text", "un champ de texte pour les mail, une verification est faite en PHP par l'entité avant l'enregistrement en base de données"],
            ["array", "text", "(depuis la version 21.18.03) un champ de texte JSON, les conversions de array (coté PHP) en JSON (coté SQL) et inversement sont gérées en PHP par l'entité. <br />"
                . "Inutile donc d'utiliser json_encode() et json_decode()"],
            ["psw (ou password)", "text", "(depuis la version 21.25.02) un champ de texte pour les mot de passe, le hash se fait automatiquement lors de l'appel de set_psw()"],
        ]);
    }

    public static function entity_model() {
        ?><p>Cette classe permet d'afficher des pseudos MCD à partir de vos entités</p><?php
        js::monaco_highlighter('<?php\n' .
                '//Exemple avec la petite structure vue dans "Entity"\n\n' .
                '//Affiche un pseudo MCD sous forme de tableau HTML\n' .
                'echo entity_model::table("user");\n\n' .
                '//Affiche un pseudo MCD sous forme de div HTML\n' .
                'echo entity_model::div("user");\n' .
                '?>');
        ?><p>Résultats :</p>
        <div class="row">
            <div class="col-sm-6">
                <p>- entity_model::table("user") :</p>
                <?= entity_model::table("user"); ?>
            </div>
            <div class="col-sm-6">
                <p>- entity_model::div("user") :</p>
                <?= entity_model::div("user"); ?>
            </div>
        </div>
        <p class="alert alert-info">Pour obtenir un MCD plus standardisé utilisez plutot la classe <a href="index.php?page=web&doc=classes_natives&native=mocodo">Mocodo</a></p>
        <?php
    }

    public static function espeak() {
        ?><p>Cette classe convertit un texte en flux audio. <br />
            /!\ Nécessite que espeak soit installé sur le serveur. <br />
            http://espeak.sourceforge.net/</p><?php
        js::monaco_highlighter('<?php\n' .
                '//exemple avec les paramètres par défaut \n' .
                '$wav = (new espeak("espeak"))' .
                '    ->set_amplitude(100)' .
                '    ->set_pitch(50)' .
                '    ->set_speed(175)' .
                '    ->set_wordgap(0)' .
                '    ->set_voice("fr")' .
                '    ->set_variant("")' .
                '    ->set_output("base64")' .
                '    ->TTS("Bonjour le monde !");\n\n' .
                'echo tags::tag("audio", ["src" => $wav, "controls" => ""], " ");\n' .
                '?>');
        ?><p>Résultats :</p>
        <audio src="data:audio/wav;base64,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AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==" controls=""></audio>
        <p class="alert alert-info">Pour utiliser des voix plus modernes dans un cadre non comercial, tournez vous vers les classes 
            <a href="index.php?page=web&doc=classes_natives&native=MSEdgeTTS">MSEdgeTTS</a> et 
            <a href="index.php?page=web&doc=classes_natives&native=MSEdgeTTS_JS">MSEdgeTTS_JS</a>
            <?php
        }

        public static function event() {
            ?>
        <p>
            Cette classe permet de créer des événements (listener et emiter) <br />
            l'utilisation de cette classe différe des "méthodes événementielles" dans la mesure où ces dernières font appel à des methodes "statiques" d'autres classes.
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Définit un listener et une action associée\n' .
                'event::on("mon_listener",function(){\n' .
                '    //do somthings\n' .
                '}\n\n' .
                '//Ajoute une autre action au listener associée\n' .
                'event::on("mon_listener",function(){\n' .
                '    //do another somthings\n' .
                '}\n\n' .
                '//Déclenche l\'événement (emiter)\n' .
                'event::run("mon_listener");\n\n' .
                '//Autre exemple avec des paramètres\n' .
                'event::on("alert.warning",function($text){\n' .
                '    ?>\n' .
                '        <p class="alert alert-warning">\n' .
                '            <?= $text; ?>\n' .
                '        </p>\n' .
                '    <?php\n' .
                '}\n' .
                'event::run("alert.warning","Attention !");\n' .
                '?>'
        );
    }

    public static function export_dwf() {
        ?>
        <p>
            Cette classe permet de générer une archive ZIP contenant les fichiers nécessaires à un projet DWF,<br>
            en fonction des dépendances enregistrées dans un fichier JSON spécifique à chaque projet. <br>
            Ce fichier JSON est consultable dans <em>dwf/class/export_dwf/</em>. <br>
            export_dwf est normalement autonome et ajoute vos dépendances tout au long de votre développement. <br>
            L'export inclura tous les fichiers contenus dans votre dossier projet (à l'exception des CSS dans src/compact). <br>
            Cependant, si vous désirez inclure un fichier ou un dossier spécifique à votre projet lors de l'export :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//ajoute des fichier\n'
                . 'export_dwf::add_files([\n'
                . '    realpath("./un_ficher.php"),\n'
                . '    realpath("./un_ficher.js"),\n'
                . '    realpath("./un_ficher.css")\n'
                . ']);\n\n'
                . '//ajoute un dossier (recursif)\n'
                . 'export_dwf::add_files([realpath("./le_dossier")]);\n'
                . '?>');
        ?>
        <p>
            Il est déconseillé d'instancier export_dwf car son instanciation est réservée à l'export depuis html/index.php. <br>
            De même, les autres fonctions statiques sont utilisées en interne de la classe.
        </p>
        <?php
    }

    public static function fancybox() {
        ?>
        <h3 class="text-center">js::fancybox</h3>
        <hr>
        <?php
        docPHP_natives_js::fancybox();
    }

    public static function fb() {
        ?>
        <p>
            Cette classe permet de gérer une authentification via FaceBook. <br />
            Requiert la création d'une application sur https://developers.facebook.com/ <br />
            Exemple d'usage :
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Execution en cas de Logout\n' .
                'if(isset($_GET["fb"]) and $_GET["fb"]=="logout"){\n' .
                '    session_destroy();\n' .
                '    js::redir("index.php");\n' .
                '}\n\n' .
                '$fb = new fb($app_id, $app_secret);\n' .
                'if ($fb->getAccessToken_session()) {\n' .
                '    //Bouton logout\n' .
                '    echo html_structures::a_link($fb->getLogoutUrl("http://mon-site/index.php?fb=logout"), "logout");\n\n' .
                '    //Données de l\'utilisateur FB\n' .
                '    debug::print_r($fb->getGraphUser());\n\n' .
                '    //TODO : utilisez session::set_auth(true) et requêtes SQL\n' .
                '} else {\n' .
                '    //Bouton login\n' .
                '    echo html_structures::a_link($fb->getLoginUrl("http://mon-site/index.php"), "login");\n' .
                '}\n' .
                '?>'
        );
    }

    public static function file_explorer() {
        ?><p>Cette classe permet d'afficher et explorer une arborescence</p><?php
        js::monaco_highlighter('<?php new file_explorer("./files"); ?>');
        ?> <p>Résultat :</p><?php
        new file_explorer("./files");
    }

    public static function fluent() {
        ?>
        <p>Cette classe permet de rendre une autre classe pseudo-fluent.</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                'class exemple extends fluent{\n' .
                '    public function metode_1($param){\n' .
                '        //do something;\n' .
                '    }\n' .
                '    public function metode_2(){\n' .
                '        return true;\n' .
                '    }\n' .
                '}\n\n' .
                '$exemple = new exemple();\n' .
                '//Exécute la méthode et retourne l\'instance si la fonction ne retourne rien ou null\n' .
                '$exemple->fluentOnNull("methode_1", "$params")->fluentOnNull("methode_2");\n' .
                '//Exécute la méthode et retourne l\'instance uniquement \n' .
                '//(tout éventuel retour de la méthode n\'est pas récupérable)\n' .
                '$exemple->fluentStrict("methode_1", "$params")->fluentStrict("methode_2");\n' .
                '//Exécute la méthode et retourne l\'instance uniquement,\n' .
                '//tout éventuel retour de la méthode est mis dans un cache récupérable via getFluentBuffer()\n' .
                '$exemple->fluentBuffered("methode_1", "$params")->fluentBuffered("methode_2");\n' .
                '$result = $exemple->getFluentBuffer();\n' .
                '?>');
    }

    public static function form() {
        ?>
        <p>La classe form permet de créer des formulaires en php stylisés par bootstrap, accessibles et respectant les normes W3C</p>
        <p>Première méthode : création et rendu</p>
        <?php
        $tmce = js::summernote("ta_2");
        js::monaco_highlighter('<?php\n' .
                '//création du formulaire\n' .
                '$form = new form();\n' .
                '$form->input("Input de type text", "input_1");\n' .
                '$form->input("Input de type password", "input_2", "password");\n' .
                '$form->input("Input avec valeur initiale", "input_3", "text", "valeur initiale");\n' .
                '$form->datepicker("Un datepicker", "datepicker_1");\n' .
                '$form->select("Un selecteur", "select_1", [\n' .
                '    [1, "Abricots"],\n' .
                '    [2, "Poires", true], //Poires est sélectionné par défaut\n' .
                '    [3, "Pommes"],\n' .
                ']);\n' .
                '$form->textarea("Un textarea", "ta_1");\n' .
                '//création d\'un Summernote\n' .
                '$form->textarea("Un Summernote", "ta_2");\n' .
                '$tmce = js::tinymce("ta_2");\n' .
                '//bouton de soumission\n' .
                '$form->submit("btn-primary");\n' .
                '//Rendu du formulaire\n' .
                'echo $form->render();\n' .
                '//Exécution du formulaire\n' .
                'if (isset($_POST["input_1"])) {\n' .
                '    //Récupère la date du datepicker au format US\n' .
                '    $date = form::get_datepicker_us("datepicker_1");\n' .
                '    //Filtre les balises utilisées dans Summernote, protection XSS\n' .
                '    $ta_2 = $tmce->parse();\n' .
                '    //Message de succès ou erreur\n' .
                '    js::alert("le formulaire a bien été soumis");\n' .
                '    //Redirection vers la page courante = rafraîchissement de la page\n' .
                '    js::redir("");\n' .
                '}\n' .
                '?>'
        );
        ?>
        <p>Seconde méthode : affichage direct</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//création du formulaire\n' .
                '$form = new form();\n' .
                '//affichage de la balise d\'ouverture\n' .
                'echo $form->get_open_form();\n' .
                'echo $form->input("Input de type text", "input_1");\n' .
                'echo $form->input("Input de type password", "input_2", "password");\n' .
                'echo $form->input("Input avec valeur initiale", "input_3", "text", "valeur initiale");\n' .
                'echo $form->datepicker("Un datepicker", "datepicker_1");\n' .
                'echo $form->select("Un selecteur", "select_1", [\n' .
                '    [1, "Abricots"],\n' .
                '    [2, "Poires", true], //Poires est sélectionné par défaut\n' .
                '    [3, "Pommes"],\n' .
                ']);\n' .
                'echo $form->textarea("Un textarea", "ta_1");\n' .
                '//création d\'un Summernote\n' .
                'echo $form->textarea("Un Summernote", "ta_2");\n' .
                '$tmce = js::tinymce("ta_2");\n' .
                '//bouton de soumission\n' .
                'echo $form->submit("btn-primary");\n' .
                '//affichage de la balise de fermeture\n' .
                'echo $form->get_close_form();\n' .
                '//éxécution du formulaire\n' .
                'if (isset($_POST["input_1"])) {\n' .
                '    //récupère la date du datepicker au format US\n' .
                '    $date = form::get_datepicker_us("datepicker_1");\n' .
                '    //filtre les balises utilisées dans Summernote, protection XSS\n' .
                '    $ta_2 = $tmce->parse();\n' .
                '    //message de succès ou erreur\n' .
                '    js::alert("le formulaire a bien été soumis");\n' .
                '    //redirection vers la page courante = rafraîchissement de la page\n' .
                '    js::redir("");\n' .
                '}\n' .
                '?>'
        );
        ?>
        <p>Résultat (visuel uniquement, exécution désactivée):</p>
        <div class="row" style="border: 1px solid #ccc; border-radius: 4px;">
            <div class="col-sm-3">
                <script type="text/javascript">
                    $(document).ready(function () {
                        $(".no-sub").submit(function (e) {
                            e.preventDefault();
                            return false;
                        });
                    });
                </script>
            </div>
            <div class="col-sm-6">
                <?php
                $form = new form("no-sub");
                $form->get_open_form();
                $form->input("Input de type text", "input_1");
                $form->input("Input de type password", "input_2", "password");
                $form->input("Input avec valeur initiale", "input_3", "text", "valeur initiale");
                $form->datepicker("Un datepicker", "datepicker_1");
                $form->select("Un sélecteur", "select_1", [
                    [1, "Abricots"],
                    [2, "Poires", true], //Poires est sélectioné par défaut
                    [3, "Pommes"],
                ]);
                $form->textarea("Un textarea", "ta_1");
                $form->textarea("Un Summernote", "ta_2");
                $form->submit("btn-primary");
                echo $form->render();
                ?>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <hr>
        <h4 class="text-center">Formulaire d'upload</h4>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$form=new form();\n' .
                '$form->hidden("upload", 1);//pour avoir une valeur a tester\n' .
                '$form->file("Votre fichier (.jpg)", "fichier");\n' .
                '$form->submit("btn-primary");\n' .
                'echo $form->render();\n' .
                'if(isset($_POST["upload"])){\n' .
                '    form::get_upload($name="fichier", $path="./files/uploads", $type=["image/jpeg"], $fname="nouveau nom.jpg");\n' .
                '}\n' .
                '?>'
        );
    }

    public static function freetile() {
        ?>
        <h3 class="text-center">js::freetile</h3>
        <hr>
        <?php
        docPHP_natives_js::freetile();
    }

    public static function ftp_explorer() {
        ?> 
        <p>Cette classe permet d'afficher et explorer une arborescence FTP <strong>PUBLIQUE</strong> <br />
            le compte FTP renseigné dans cette classe ne doit avoir que des droits de consultation ( aucun droit d'ajout/modification/suppression) <br />
            le rendu est le même que <em>file_explorer()</em>
        </p>
        <?php
        js::monaco_highlighter('<?php new ftp_explorer($host, $user, $psw, $ssl=false); ?>');
    }

    public static function fullcalendar() {
        ?>
        <p>Cette classe permet d'afficher un Fullcalendar <br />
            <a href="https://fullcalendar.io/">https://fullcalendar.io/</a>
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Affiche l\'administration du Fullcalendar\n' .
                'fullcalendar::admin();\n\n' .
                '//Affiche le fullcalendar avec les évènements enregistrés en base de données\n' .
                'new fullcalendar($id="fullcanlendar", fullcalendar_event::get_table_array());\n\n' .
                '//Affiche le fullcalendar avec l\'agenda d\'un compte Google\n' .
                'new fullcalendar($id="fullcanlendar", null, $api_key, "abcd1234@group.calendar.google.com");\n' .
                '?>');
        ?><p>Exemple</p><?php
        new fullcalendar("fullcalendar", [
            [
                "title" => "évènement de démonstation",
                "start" => date("Y-m-d") . " " . date("H:i:s"),
                "end" => date("Y-m-d") . " " . (date("H") + 1) . date(":i:s")
            ]
        ]);
    }
}
