<?php

class docPHP_natives_gl {

    public static function g_agenda() {
        ?>
        <p>Cette classe permet de gérer un agenda d'évènements minimalistes, <br />
            souvent utilisé pour avertir des visiteurs des prochains évenements<br />
            (salons, conventions, spectacles, ...)</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Affiche l\'interface d\'administration\n'
                . '(new g_agenda())->admin();\n\n'
                . '//Affiche la liste d\'évènements prévus (les 10 prochains par défaut)\n'
                . '(new g_agenda())->agenda_page($lim = 10);\n'
                . '?>');
    }

    public static function g_elFinder() {
        ?>
        <p>Cette classe affiche le gestionnaire de fichiers elFinder <br />
            Il n'est pas recommandé de mettre deux instances de cette classe dans une même page<br />
            Pour autoriser un utilisateur à utiliser elFinder, vous devrez utiliser session::set_val("elFinder", true);<br />
            Cf : le fichier connector.php (généré par cette classe) pour plus de détails et options)
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Sécurité via la varible de session\n'
                . 'session::set_val("elFinder", true);\n\n'
                . '//Affiche le gestionnaire de fichiers elFinder\n'
                . 'new g_elFinder();\n'
                . '?>');
    }

    public static function gestion_article() {
        ?>
        <p>Cette classe permet de gérer et afficher des articles</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Affiche l\'administration des articles\n'
                . '(new gestion_article())->admin();\n\n'
                . '//Affiche le parcours des articles (pour les utilisateurs)\n'
                . '(new gestion_article())->article();\n\n'
                . '//Affiche un module avec les dernières actualités\n'
                . '//Plusieurs modules peuvent être créés, chaque module est identifié par un nom,\n'
                . '//possède une limite d\'affichage et sont liés à une ou plusieurs catégories d\'articles\n'
                . '(new gestion_article())->module($name="default")\n'
                . '?>');
    }

    public static function giphy() {
        ?>
        <p>
            Cette classe permet de retourner des Gifs de l'API GIPHY
        </p>
        <p>
            Afin d'etre utilisatble, la clé api doit etre renseigné dans <strong>config.class.php</strong> ainsi :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'public static static $_giphy_key= "VOTRE CLE API";\n'
                . '?>');
        echo'<p>Usage :</p>';
        js::monaco_highlighter('<?php\n'
                . 'new giphy();\n'
                . '?>');
    }

    public static function git() {
        ?>
        <p>Utilisez GIT depuis PHP (requiert GIT sur le serveur) <a href="https://github.com/kbjr/Git.php">https://github.com/kbjr/Git.php</a></p>
        <?php
    }

    public static function google_oauth() {
        ?>
        <p>Cette classe permet de gérer une authentification via Google <br />
            Requiert la création d'une application sur https://console.cloud.google.com/apis/credentials</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$gOauth = new google_oauth($clientId, $clientSecret, $redirectUri);\n' .
                'if ($gOauth->getAccessToken_session()) {\n' .
                '    $userinfo = $gOauth->get_OpenId();\n' .
                '    //TODO : utilisez $userinfo, session::set_auth(true) et requêtes SQL\n' .
                '} else {\n' .
                '    echo html_structures::a_link($gOauth->getLoginUrl(), "Google Oauth");\n' .
                '}\n' .
                '?>'
        );
    }

    public static function graphique() {
        ?>
        <p>Cette classe permet de créér des graphiques.</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$data = [\n' .
                '    [\n' .
                '        "label" => "Nombres heureux",\n' .
                '        "data" => [[1, 1],[2, 7],[3, 10],[4, 13],[5, 19]]\n' .
                '    ],\n' .
                '    [\n' .
                '        "label" => "Nombres premiers",\n' .
                '        "data" => [[1, 2],[2, 3],[3, 5],[4, 7],[5, 11]]\n' .
                '    ]\n' .
                '];\n' .
                '//Affiche un graphique en ligne/courbe\n' .
                '(new graphique("graph1", $size = ["width" => "600px", "height" => "300px"]))->line($data, $ticks = [], $show_points = true, $fill = false);\n' .
                '//Affiche un graphique en points\n' .
                '(new graphique("graph2"))->points($data);\n' .
                '//Affiche un graphique en bars\n' .
                '(new graphique("graph3"))->bars($data);\n\n' .
                '$data = [\n' .
                '    [\n' .
                '        "label" => "Allemagne",\n' .
                '        "data" => 3466.76\n' .
                '    ],\n' .
                '    [\n' .
                '        "label" => "Royaume uni",\n' .
                '        "data" => 2618.89\n' .
                '    ],\n' .
                '    [\n' .
                '        "label" => "France",\n' .
                '        "data" => 2465.45\n' .
                '    ]\n' .
                '];' .
                '//Affiche un graphique en "camembert"\n' .
                '(new graphique("graph4"))->pie($data);\n' .
                '//Affiche un graphique en anneau\n' .
                '(new graphique("graph5"))->ring($data);\n' .
                '?>'
        );
        $data = [
            [
                "label" => "Nombres heureux",
                "data" => [[1, 1], [2, 7], [3, 10], [4, 13], [5, 19]]
            ],
            [
                "label" => "Nombres premiers",
                "data" => [[1, 2], [2, 3], [3, 5], [4, 7], [5, 11]]
            ]
        ];
        $data2 = [
            [
                "label" => "Allemagne",
                "data" => 3466.76
            ],
            [
                "label" => "Royaume uni",
                "data" => 2618.89
            ],
            [
                "label" => "France",
                "data" => 2465.45
            ]
        ];
        ?>
        <p>Résultats :</p>
        <div class="row">
            <div class="col-sm-4">
                <?php
                (new graphique("graph1", $size = ["width" => "100%", "height" => "300px"]))->line($data);
                ?>
            </div>
            <div class="col-sm-4">
                <?php
                (new graphique("graph2", $size))->points($data);
                ?>
            </div>
            <div class="col-sm-4">
                <?php
                (new graphique("graph3", $size))->bars($data);
                ?>
            </div>
        </div>
        <div class="row">
            <p>PIB 2016</p>
            <div class="col-sm-5">
                <?php
                (new graphique("graph4", $size))->pie($data2);
                ?>
            </div>
            <div class="col-sm-5">
                <?php
                (new graphique("graph5", $size))->ring($data2);
                ?>
            </div>
        </div>
        <?php
    }

    public static function html5() {
        ?>
        <p>
            Cette classe gère l'en-tête HTML5 et son pied de page. <br />
            Cette classe est utilisée automatiquement par le framework dans <em>application.class.php</em> <br />
            Les balises : title, meta description et meta keywords peuvent être modifiées grâce aux fonctions suivantes :

        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Ajoute un préfixe au titre de la page en cours\n'
                . 'html5::before_title($text);\n'
                . '//Définit la description de la page en cours\n'
                . 'html5::set_description($description);\n'
                . '//Définit les mots clés de la page en cours\n'
                . 'html5::set_keywords($keywords);\n'
                . '//Ajoute des mots clés de la page en cours\n'
                . 'html5::add_keywords($keywords);\n'
                . '?>');
    }

    public static function html_structures() {
        ?>
        <p>html_structures est une classe qui permet de créer des structures HTML en PHP,<br />
            Ces structures respectent les normes W3C et répondent à quelques normes d'accessibilité <br />
            Exemple :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//affiche une liste de deux liens\n'
                . '//le premier affiche le glyphicon "home" et renvoie vers l\'acceuil\n'
                . '//le second affiche le glyphicon "search" et ouvre un onglet vers DuckDuckGo\n'
                . 'echo html_structures::ul(array(\n'
                . '    html_structures::a_link("index.php", html_structures::glyphicon("home","") . " Retour à l\'accueil"),\n'
                . '    html_structures::a_link("https://duckduckgo.com/", html_structures::glyphicon("search","") . " Rechercher sur le web","","(nouvel onglet)", true),\n'
                . '));\n'
                . '?>');
        ?>
        <p>Résultat :</p>
        <?php
        echo html_structures::ul(
                array(
                    html_structures::a_link('index.php', html_structures::glyphicon('home', '') . ' Retour à l\'accueil'),
                    html_structures::a_link('https://duckduckgo.com/', html_structures::glyphicon('search', '') . ' Rechercher sur le web', '', '(nouvel onglet)', true),
                )
        );
        ?>
        <p>Méthodes (toutes static) :</p>
        <?php
        echo html_structures::table(["Méthode", "Description"], [
            ["table", "Retourne un tableau à partir d'un array d'entête et d'un array à deux dimensions comprenant les données"],
            ["ul, ol, dl", "Retourne une liste au format HTML à partir d'un array ( prend en compte l'imbrication des arrays)"],
            ["a_link", "Retourne un lien"],
            ["Ancre", "Retourne une ancre a"],
            ["img", "Retourne une image img"],
            ["figure", "Retourne une figure ( illustration et légende )"],
            ["new_map, area et close_map", "Mapping d'image"],
            ["media", "Retourne les données passées en paramètres sous forme de média (bootstrap)"],
            ["glyphicon", "Retourne un glyphicon (avec un texte alternative)"],
            ["hr", "Retourne un séparateur horizontal"],
            ["time", "La balise time permet d'afficher une date avec une valeur SEO sémantique"],
            ["link_in_body", "Permet de faire appel à une balise LINK dans le body"],
            ["script_in_body", "Permet de faire appel à une balise SCRIPT dans le body"],
            ["script et link", "Sont utilisés par le framework (dans html5.class.php)"],
            ["popover", "Permet d'afficher un lien avec un popover"],
            ["parallax", "Permet d'afficher une DIV qui aura un effet de parallaxe"]
        ]);
    }

    public static function http2() {
        ?>
        <p>
            Cette classe permet de gérer le header LINK de http/2.0 <br />
            elle n'est active que si le protocole http/2 est actif <br />
            si c'est le cas sont renseignés d'office :
        </p>
        <ul>
            <li>Les liens du menu (prérender)</li>
            <li>Les fichiers css et js appelés depuis html_structure (preload)</li>
        </ul>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Ajoute le préchargement d\'une image\n'
                . 'http2::get_instance()->preload("./src/img.jpg");\n'
                . '?>');
    }

    public static function ip_access() {
        ?>
        <p>Cette classe sert à blacklister une liste de plages d'adresses IP en les redirigeant vers un autre site</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//exemple : on bloque tout les access depuis les ip de localhost et on redirige vers DuckDuckGO\n'
                . 'new ip_access(array(array("127.0.0.0", "127.255.255.255")), "http://duckduckgo.com");\n'
                . '?>');
    }

    public static function ip_api() {
        ?>
        <p>
            Cette classe permet de récupérer des informations sur une adresse IP (géolocalisation, opérateur...). <br>
            Attention, json() est limité à 45 requêtes par minute, <br>
            batch() est limité à 15 requêtes par minute et 100 IP max par requête.
            (Cf <a href="https://ip-api.com">https://ip-api.com</a>)
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Le serveur envois une requête a ip-api pour une IP\n'
                . '$ip_infos=ip_api::get_instance()->json($ip);\n\n'
                . '//Le serveur envois une requête a ip-api pour une liste IP (100 max)\n'
                . '$ip_infos=ip_api::get_instance()->batch($ips);\n\n'
                . '//Le client envois une requête a ip-api pour une liste IP (100 max)\n'
                . '$ip_infos=ip_api::get_instance()->json_browser($ip, function($ip_data){\n'
                . '    return $ip_data;\n'
                . '});\n\n'
                . '//Le client envois une requête a ip-api pour une liste IP (100 max)\n'
                . '$ip_infos=ip_api::get_instance()->batch_browser($ip, function($ip_data){\n'
                . '    return $ip_data;\n'
                . '});\n'
                . '?>');
        ?>
        <p>
            Attention pour json_browser() et batch_browser() : les informations transiteront par le navigateur du client ! <br>
            Les informations peuvent être altérées !
        </p>
        <?php
    }

    public static function js() {
        if (isset($_GET["js"]) and in_array($_GET["js"], docPHP_natives_js::get_methods()) and !in_array($_GET["js"], ["get_methods", "__construct"])) {
            $js = $_GET["js"];
            docPHP_natives_js::$js();
        } else {
            new docPHP_natives_js();
        }
    }

    public static function leaflet() {
        ?>
        <p>Cette classe permet d'afficher une carte exploitant OSM (OpenStreetMap)</p>
        <?php
        $leaflet = new leaflet(array("x" => 48.85341, "y" => 2.3488, "zoom" => 13));
        $leaflet->add_marker(48.85341, 2.3488, 'Paris');
        $leaflet->add_marker(51.50853, -0.12574, 'Londres');
        $leaflet->add_marker(50.85045, 4.34878, 'Bruxelles');
        $leaflet->add_circle(50.85045, 4.34878, 100000, 'Belgique');
        $leaflet->add_polygon(array(
            array("x" => "50.9519", "y" => "1.8689"),
            array("x" => "48.582325", "y" => "7.750871"),
            array("x" => "43.774483", "y" => "7.497540"),
            array("x" => " 43,3885129", "y" => "-1,6596374"),
            array("x" => "48,3905283", "y" => "-4,4860088"),
                ), 'Hexagone');
        js::monaco_highlighter('<?php\n' .
                '//Initialise le Leaflet\n' .
                '$leaflet=new leaflet(array("x" => 48.85341, "y" => 2.3488, "zoom" => 13));\n' .
                '//Ajoute des marqueurs\n' .
                '$leaflet->add_marker(48.85341, 2.3488, \'Paris\');\n' .
                '$leaflet->add_marker(51.50853,  -0.12574, \'Londres\');\n' .
                '$leaflet->add_marker(50.85045, 4.34878, \'Bruxelles\');\n' .
                '//Ajoute un cercle autour d\'un point\n' .
                '$leaflet->add_circle(50.85045, 4.34878, 100000, \'Belgique\');\n' .
                '//Ajoute un polygone sur la carte\n' .
                '$leaflet->add_polygon(array(\n' .
                '    array("x"=>"50.9519","y"=>"1.8689"),\n' .
                '    array("x"=>"48.582325","y"=>"7.750871"),\n' .
                '    array("x"=>"43.774483","y"=>"7.497540"),\n' .
                '    array("x"=>" 43,3885129","y"=>"-1,6596374"),\n' .
                '    array("x"=>"48,3905283","y"=>"-4,4860088"),\n' .
                '), \'Hexagone\');\n' .
                '//Affiche la carte\n' .
                '$leaflet->print_map();\n' .
                '//Trace l\'itinéraire sans activer la position de l\'utilisateur\n' .
                '$leaflet->tracer_itineraire($add_client_marker=false);\n' .
                '//Trace l\'itinéraire en ajoutant la position de l\'utilisateur par géolocalisation (droits demandés par le navigateur)\n' .
                '$leaflet->tracer_itineraire(true);\n' .
                '?>');
        echo html_structures::hr();
        $leaflet->print_map();
    }

    public static function log_file() {
        ?>
        <p>Cette classe permet de créer un log sous forme de fichiers. <br />
            Elle vous permet d'enregistrer les comportements anormaux de votre application <br />
            Selon vos paramètres, le log est écrit dans <em>dwf/log/log_[votre_projet]_[date_format_us].txt</em> ou <em>dwf/log/log_[votre_projet].txt</em>
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//instencie l\'objet de log\n' .
                '$log=new log_file($a_log_a_day=false);\n' .
                '//inscrit un message d\'informations dans le log\n' .
                '$log->info($message);\n' .
                '//inscrit un message d\'alerte dans le log\n' .
                '$log->warning($message);\n' .
                '//inscrit un message grave dans le log\n' .
                '$log->severe($message);\n' .
                '?>'
        );
    }

    public static function log_mail() {
        ?>
        <p>Cette classe permet de vous envoyer automatiquement un mail en cas de comportement anormal de votre application</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//instencie l\'objet de log\n' .
                '$log=new log_mail($from, $to);\n' .
                '//envoie un mail d\'informations\n' .
                '$log->info($message);\n' .
                '//envoie un mail d\'alerte\n' .
                '$log->warning($message);\n' .
                '//envoie un mail grave\n' .
                '$log->severe($message);\n' .
                '?>'
        );
    }

    public static function lorem_ipsum() {
        ?>
        <p>Cette classe permet de générer un faux texte (Lorem ipsum) <br />
            Le texte est généré depuis le vocabulaire du texte de Cicero : De finibus. 
            <br />Sources : <br />
            <a href="http://www.thelatinlibrary.com/cicero/fin1.shtml">Liber Primus</a> <br />
            <a href="http://www.thelatinlibrary.com/cicero/fin.shtml">Oeuvre complète</a></p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Lorem Ipsum de 100 mots ne commencant pas par "Lorem ipsum ..."\n' .
                'echo lorem_ipsum::generate(100);\n\n' .
                '//Lorem Ipsum de 100 mots commencant par \n' .
                '//"Lorem ipsum dolor sit amet, consectetur adipiscing elit."\n' .
                'echo lorem_ipsum::generate(100, true);\n\n' .
                '//Lorem Ipsum de 100 mots commencant par \n' .
                '//"Lorem ipsum dolor sit amet, consectetur adipiscing elit."\n' .
                '//et utilisant le vocalulaire de toute l\'oeuvre\n' .
                '//(10035 mots au lieu des 2732 mots du Liber Primus)\n' .
                'echo lorem_ipsum::generate(100, true, true);\n' .
                '?>');
        ?>
        <p>Resultat (<em>lorem_ipsum::generate(100, true)</em>) : </p>
        <p>
            <?php
            echo lorem_ipsum::generate(100, true);
            ?>
        </p>
        <?php
    }

    public static function lurl() {
        ?>
        <p class="alert alert-danger">
            Lurl.fr a férmé définitivement en juillet 2024
        </p>
        <p>
            Cette classe permet de générer des liens LURL ou un bouton LURL sponsorisé qui redirige vers la page courante
            <a href="https://lurl.fr/ref/legagneur">LURL</a>
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '(new lurl($api_key))->get_lurl($url);\n' .
                '(new lurl($api_key))->selfpage_support_btn();\n' .
                '?>'
        );
    }
}
