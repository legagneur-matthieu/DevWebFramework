<?php

class docPHP {

    private $_brush = "php; html-script: true";

    public function __construct() {
        $doc = [
            "introduction",
            "nouveau_projet",
            "configuration",
            "pages",
            "classes_metiers",
            "methodes_evenementielles",
            "entity",
            "bdd",
            "classes_natives",
            "services_general",
            "services_interne",
            "CLI",
            "WebSocket",
            "mise_en_ligne",
        ];
        ?>
        <div class="row">
            <div class="col-3">
                <div class="list-group">
                    <?php
                    foreach ($doc as $d) {
                        echo html_structures::a_link("index.php?page=web&doc=$d", strtr(ucfirst($d), array("_" => " ")) . ($d == "classes_natives" ? " " . html_structures::bi("caret-down-fill") : ""), "list-group-item list-group-item-action " . ((isset($_GET["doc"]) and $_GET["doc"] == $d) ? "active" : ""));
                        if ($d == "classes_natives" and isset($_GET["doc"]) and $_GET["doc"] == $d) {
                            ?>
                            <div class="list-group mx-2">
                                <?php
                                foreach (docPHP_natives::get_methods() as $n) {
                                    if (!in_array($n, ["get_methods", "__construct"])) {
                                        echo html_structures::a_link("index.php?page=web&doc=$d&native=$n", strtr(ucfirst($n), array("_" => " ")) . ($n == "js" ? " " . html_structures::bi("caret-down-fill") : ""), "list-group-item list-group-item-action " . ((isset($_GET["native"]) and $_GET["native"] == $n) ? "active" : ""));
                                        if ($n == "js" and isset($_GET["native"]) and $_GET["native"] == $n) {
                                            ?>
                                            <div class="list-group mx-2">
                                                <?php
                                                foreach (docPHP_natives_js::get_methods() as $js) {
                                                    if (!in_array($js, ["get_methods", "__construct"])) {
                                                        echo html_structures::a_link("index.php?page=web&doc=$d&native=$n&js=$js", strtr(ucfirst($js), array("_" => " ")), "list-group-item list-group-item-action " . ((isset($_GET["js"]) and $_GET["js"] == $js) ? "active" : ""));
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-9">
                <?php
                if (isset($_GET["doc"]) and in_array($_GET["doc"], $doc)) {
                    $d = $_GET["doc"];
                } else {
                    $d = $doc[0];
                }
                ?>
                <h2 class="text-center"><?= strtr(ucfirst($d), array("_" => " ")); ?></h2>
                <?php
                if (isset($_GET["native"]) and in_array($_GET["native"], docPHP_natives::get_methods())) {
                    $h3 = "";
                    if ((isset($_GET["js"]) and in_array($_GET["js"], docPHP_natives_js::get_methods()))) {
                        $h3 = " - " . strtr(ucfirst($_GET["js"]), array("_" => " "));
                    }
                    $h3 = strtr(ucfirst($_GET["native"]), array("_" => " ")) . $h3;
                    echo tags::tag("h3", ["class" => "text-center"], $h3);
                }
                ?>
                <hr>
                <?php
                $this->$d();
                ?>
            </div>
        </div>
        <?php
    }

    private function introduction() {
        ?>
        <p>
            DWF (DevWebFramework) est un framework PHP ayant pour doctrine : <br />
            "Simplicité pour l'utilisateur, liberté pour le développeur" <br />
        </p>
        <h4>Prérequis et règles du framework:</h4>
        <ul>
            <li>Disposer d'un serveur WEB et savoir l'utiliser</li>
            <li>Maîtriser la programmation orienté objet (POO) en PHP</li>
            <li>
                Les classes dans les dossiers <em>html/[votre-projet]/class</em> et <em>dwf/class</em> doivent être nommées <em>[nom_de_classe].class.php</em> <br />
                Ces classes seront chargées (incluses) automatiquement. Si vous créez des classes sans cette syntaxe ou dans des sous dossiers, vous devrez les inclure vous même.
            </li>
            <li>L'utilisateur ne quitte jamais le fichier <em>html/[votre-projet]/index.php</em> hormis pour les exports PDF/CSV ... qui s'ouvrent dans un nouvel onglet.</li>            
        </ul>
        <h4>Structure</h4>
        <?php
        $dir_glyph = html_structures::glyphicon("folder-open", "");
        $arrow_glyph = html_structures::glyphicon("arrow-right", "");
        ?>
        <style type="text/css">
            .no-puces .glyphicon-folder-open, .no-puces .glyphicon-file{
                color: lightblue;
            }
            .no-puces .glyphicon-arrow-right{
                color: gray;
            }
        </style>
        <div class="card">
            <div class="card-body">

                <ul class="no-puces">
                    <li><?= $dir_glyph; ?> html
                        <ul>
                            <li><?= $arrow_glyph; ?> <em>Contient vos projets</em></li>
                            <li><?= $dir_glyph; ?> commun
                                <ul>
                                    <li><?= $arrow_glyph; ?> <em>Contient divers fichiers pour les export PDF/CSV/QRCode et création de nouveaux projets.</em></li>
                                    <li><?= $dir_glyph; ?> src
                                        <ul>
                                            <li><?= $arrow_glyph; ?> <em>Contient tous les fichiers CSS et JS communs à tous les projets (et accessibles aux utilisateurs).</em></li>
                                        </ul>
                                    </li>
                                    <li><?= $dir_glyph; ?> service
                                        <ul>
                                            <li><?= $arrow_glyph; ?> <em>Contient vos services / API</em></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><?= $dir_glyph; ?> [votre-projet]
                                <ul>
                                    <li><?= $arrow_glyph; ?> <em>Contient votre index.php ( à ne pas modifier ).</em></li>
                                    <li><?= $dir_glyph; ?> class
                                    <li><?= $arrow_glyph; ?> <em>Contient vos classes spécifiques au projet ainsi que le fichier de configuration.</em>
                                        <ul>
                                            <li><?= $dir_glyph; ?> entity</li>
                                            <li><?= $arrow_glyph; ?> <em>Contient les entités de votre projet.</em></li>
                                        </ul>
                                    </li>
                                    <li><?= $dir_glyph; ?> src
                                    <li><?= $arrow_glyph; ?> <em>Contient vos fichiers CSS, JS, et médias.</em>
                                        <ul>
                                            <li><?= $dir_glyph; ?> compact</li>
                                            <li><?= $arrow_glyph; ?> <em>Contient vos fichiers CSS et JS minifiés par le framework.</em></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><?= $dir_glyph; ?> dwf
                        <ul>
                            <li><?= $dir_glyph; ?> class
                                <ul>
                                    <li><?= $arrow_glyph; ?> <em>Contient les classes natives de DWF.</em></li>
                                </ul>
                            </li>
                            <li><?= $dir_glyph; ?> cron
                                <ul>
                                    <li><?= $arrow_glyph; ?> <em>Contient vos éventuels cron à faire tourner dans vos consoles.</em></li>
                                </ul>
                            </li>
                            <li><?= $dir_glyph; ?> log
                                <ul>
                                    <li><?= $arrow_glyph; ?> <em>Contient les logs de vos projets.</em></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <?php
    }

    private function nouveau_projet() {
        ?>
        <p>
            La création d'un nouveau projet est automatisé par le fichier <a href="../commun/new_app.php" target="_blank"><em>/html/commun/new_app.php</em></a> accédez à ce fichier par votre navigateur -> localhost <br />
            Ce fichier ne peut se lancer que depuis votre localhost. Il n'est pas possible de s'en servir à distance sans en modifier le code.<br/>
            Une fois l'interface de création de projet ouvert, remplissez les champs correctement. Ces paramètres seront modifiable dans <em>config.class.php</em>
        </p>
        <div class="card">
            <div class="card-body">

                <ul>
                    <li>Application
                        <ul>
                            <li>Nom du dossier (apparait dans l'url) : nom du dossier du projet, il est conseillé de l'écrire en minuscule.</li>
                            <li>Titre de l'application (apparait dans le "title" des pages) : nom réel de votre projet.</li>
                            <li>Préfixe (technique, utilisé pour les sessions, log ...) : le préfixe doit être unique à chaque projet, <br /> il sert à différencier les sessions et les logs utilisés dans les projets.</li>
                            <li>
                                Hash (hash à utiliser pour chiffrer les mots de passe) : c'est ici que vous choisirez quel algorithme de chiffrement, vous utiliserez pour chiffrer vos mots de passe ou autres clés<br />
                                l'algorithme est accessible dans le code via <em>config::$_hash_algo</em>
                            </li>
                        </ul>
                    </li>
                    <li> PDO
                        <ul>
                            <li>Type : type du serveur MySQL ou SQLite</li>
                            <li>Host : host du serveur SQL</li>
                            <li>Login : nom d'utilisateur</li>
                            <li>Password : mot de passe</li>
                            <li>Database : nom de la base de données</li>
                            <li>Créer la base de données (si elle n'existe pas) : si la case est cochée et que la base de données n'existe pas, alors le framework pourra la créer.</li>
                            <li>Service internes (un dossier de service sera créé dans le projet) : votre projet aura t-il besoin de services "internes" (spécifique) ?</li>
                        </ul>
                    </li>
                    <li> SMTP
                        <ul>
                            <li>Host : host du serveur SMTP</li>
                            <li>Auth : True si une authentification est requise ( c'est presque toujours le cas )</li>
                            <li>Login : si Auth = true, nom d'utilisateur SMTP</li>
                            <li>Password : si Auth = true, mot de passe SMTP</li>
                            <li><em>Astuce : si vous n'utilisez pas de SMTP, mettez HOST : localhost et Auth : false</em></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <p>Une fois le formulaire rempli et validé, une notification vous informe du succès de la création et vous redirige vers l'index de votre projet. <br />
            Si vous rencontrez des difficultés, verifiez que votre serveur web a bien les droits d'écriture dans le dossier HTML et que le dossier que vous essayez de créér n'existe pas déjà.
        </p>
        <?php
    }

    private function configuration() {
        ?>
        <p>Une fois votre projet créé, regardons le fichier de configuration, <br />
            nous retrouvons les renseignements saisi dans le formulaire de création de projet :</p>
        <ul>
            <li>public static $_PDO_type</li>
            <li>public static $_PDO_host</li>
            <li>public static $_PDO_dbname</li>
            <li>public static $_PDO_login</li>
            <li>public static $_PDO_psw</li>
            <li>public static $_hash_algo</li>
            <li>public static $_title</li>
            <li>public static $_prefix</li>
            <li>public static $_SMTP_host</li>
            <li>public static $_SMTP_auth</li>
            <li>public static $_SMTP_login</li>
            <li>public static $_SMTP_psw</li>
        </ul>
        <p>
            Première chose que l'on peut remarquer : toutes ces variables sont en <em>"public static"</em>, <br />
            elles sont donc accessibles à tout moment dans le projet depuis <em>config::</em> <br />
            Mais intéressons nous aux autres variables :
        </p>
        <ul>
            <li>public static $_favicon : chemin d'accès à la favicon du projet</li>
            <li>public static $_sitemap : si true, un sitemap sera créé pour le projet (les sitemap sont utiles à l'accessibilité et au référencement)</li>
            <li>public static $_statistiques : si true, des statistiques seront faites sur l'activité des utilisateurs</li>
        </ul>
        <p>Et pour finir :</p>
        <ul>
            <li>public static $_route_auth</li>
            <li>public static $_route_unauth</li>
        </ul>
        <p>
            Il s'agit des routes de l'application, par défaut il y a des routes pour les utilisateurs authentifiés (route_auth) et d'autres pour les non-authentifiés (route_unauth) <br />
            Les routes se définissent dans la fonction <em>config::onbdd_connected()</em> afin de pouvoir etre manipulées à volonté <br />
            ( ajouter des routes conditionelles, faire gérer les routes par une entité, utiliser la classe trad (traductions), ...)<br />
            Une route se définit ainsi :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'self::$_route_unauth = array(\n'
                . '    array(\n'
                . '        "page" => "index",                         //la clé "page" correspond à la variable $_GET["page"],\n'
                . '                                                   //la valeur index correspond au nom d\'une fonction dans "page.class.php"\n'
                . '                                                   //une route et une fonction "index" doivent toujours être présentes dans les routes !\n\n'
                . '        "title" => "Page d \'accueil",              //Title de la page, chaque page doit avoir un title different pour des raisons\n'
                . '                                                   //d\'accessiblilité et de référencement\n\n'
                . '        "text" => "Accueil",                       //FACULTATIF : texte affiché dans le menu principal\n'
                . '                                                   // (si la route n\'est pas destinée à être affichée dans le menu, ne pas renseigner de clé "text")\n\n'
                . '        "description" => "Index de Documentation", //FACULTATIF : meta description de la page pour le réferencement\n'
                . '        "keyword" => "Index, Documentation"        //FACULTATIF : meta keywords de la page pour le réferencement\n'
                . '    ),\n'
                . ');\n'
                . '?>'
        );
    }

    private function pages() {
        ?>
        <p>La classe "pages" est le point de départ de votre projet et est constitué des éléments suivants :</p>
        <ul>
            <li>Le constructeur : contient les opérations communes à toutes les pages (génération / mise à jour de fichiers, vérifications sur les données/entités ...)  </li>
            <li>Le header : c'est le haut de page de votre application, elle est commune à toutes les pages (mais vous pouvez utiliser un switch pour créer une entête à chaque page)</li>
            <li>Le footer : le pied de page, souvent commune à toutes les pages, contient généralement: date de création, contacts, les mentions légales, CGU ...</li>
            <li>Les fonctions liées au routes : ces fonctions doivent être publiques, le nom de la fonction doit correspondre à la valeur de la clé "page" d'une route</li>
            <li>Des fonctions privées : rien ne vous empêche de créer des fonctions privées dans pages qui seront appelées dans le constructeur ou dans les fonctions publiques liées au routes</li>
        </ul>
        <p>Dans les méthodes de "pages", vous êtes entièrement libres : <br />
            saisir du HTML, appeler des fonctions privées de pages, appeler vos "classes métiers", une classe native de DWF ou n'importe quelle autre fonction statique</p>
        <?php
    }

    private function classes_metiers() {
        ?>
        <p>
            Les classes dites "métiers" sont les classes spécifiques à votre projet que vous aurez vous mêmes créé dans le dossier <em>html/[votre-projet]/class/</em>, <br />
            contrairement à d'autres framework comme "symfony" ces classes ne nécessitent pas d'être étendues d'une classe natif de DWF. <br />
            vous êtes libre de lui donner le nom que vous voulez ( sauf un nom déjà pris par une classe native ou une de vos entités) <br />
            libre d'utiliser des "méthodes magiques" tels que les constructeurs, destructeurs... <br />
            La seule restriction est le nom du fichier qui doit être comme ceci : <em>[nom_de_votre_classe]<strong>.class.php</strong></em>. <br />
            Une fois votre classe créée, vous pouvez l'appeler et l'utiliser dans <em>pages.class.php</em>
        </p>
        <?php
    }

    private function methodes_evenementielles() {
        ?>
        <p class="alert alert-warning">
            Pour créer des évenements liés à votre application, préviligiez l'utilisation de <em>event.class.php</em>. <br />
            Elle permet de créer et déclencher des évenements de manière plus stable et avec un meilleur contrôle. <br />
            (cf classes natives > event)
        </p>
        <h4>Utiliser les méthodes événementielles</h4>
        <p>
            Les méthodes événementielles sont des méthodes qui seront appelées lors d'un événement précis du framework, utilisation :            
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'class ma_classe_metier{\n\n'
                . '    public static onload(){\n'
                . '        //Supprimé depuis la version 21.17.12\n'
                . '    }\n\n'
                . '    public static onhtml_head(){\n'
                . '        //Supprimé depuis la version 21.17.12\n'
                . '    }\n\n'
                . '    public static onhtml_body_end(){\n'
                . '        //cette méthode sera executée automatiquement par le framework \n'
                . '        // juste avant les balises <p id="real_title" class="hidden">...</p></body>\n'
                . '        // cette méthode peut se substituer à la methode __destruct() si elle doit générer du HTML\n'
                . '    }\n\n'
                . '    public static onbdd_connected(){\n'
                . '        //cette méthode sera executée automatiquement par le framework \n'
                . '        // dès que la connexion à la base de données est établie\n'
                . '    }\n\n'
                . '}\n?>'
        );
        ?>
        <p>
            Comme indiqué, ces methodes doivent être en <em>public static</em>, elles peuvent étre utilisées dans n'importe quel classe SAUF les entités.<br />
            Ces méthodes ne prennent pas de paramètres et ne retourne rien.
        </p>
        <p class="alert alert-warning">ATTENTION : Depuis la version 21.17.12, <br />
            pour que les methodes évenementielles se déclenchent, la classe concernée doit avoir été appelée au moins une fois avant le déclencheur.<br />
            (instanciation ou appel d'une méthode statique)
        </p>
        <h4>Créer un déclencheur</h4>
        <p>
            Il est possible de créer vos propres déclencheurs d'événements grâce à la méthode.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'application::event("mon_evenement");\n'
                . '?>');
        ?>
        <p>
            Losque cette instruction sera appelée, toutes les méthodes <em>public static mon_evenement()</em> présentes dans les classes seront executées.
        </p>
        <?php
    }

    private function entity() {
        docPHP_natives::entity_generator();
    }

    private function bdd() {
        ?>
        <p class="alert alert-danger">
            Avant le verssion 21.24.04, une fonction dans BDD (bdd::p()) permetait de securiser les variables contre des injections SQL . <br>
            Une évaluation de cette protection a révélé certaines faiblaises. <br>
            Depuis la version 21.24.04 les requetes sont protégé par le système de prépararation et execution, plus robuste.
        </p>
        <p>
            L'objet bdd est l'objet qui permet de gérer la connexion à la base de données et sécuriser les requêtes SQL. <br />
            Cet objet utilise les informations PDO qui sont renseignées dans le fichier de configuration. <br />
            Vos entités et de nombreuses classes native de DWF exploitent cette objet.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//fetch permet d\'éxecuter une requête de type select et d\'en récuperer le résultat sous forme d\'un tableau\n'
                . '$req=application::$bdd->fetch($statement, $params=[]);\n'
                . '//exemple :\n'
                . '$req = application::$bdd->fetch("select * from user where id=:id", $params=[":id"=>1]);\n\n'
                . '//query permet d\'executer des requêtes de type insert into, update et delete, ne retourne rien !\n'
                . 'application::$bdd->query($statement, $params=[]);\n\n'
                . '//exemple :\n'
                . '$req = application::$bdd->query("insert into rang(nom) values (:nom)", $params=[":nom"=>"invité"]);\n\n'
                . '//verif_email retourne true si la chaine rentrée en paramètres respecte le format email, false si non\n'
                . '$is_email=application::$bdd->verif_email($email);\n\n'
                . '//astuce pour les requetes "IN", vous pouvez directement renseigner un tableau de valeurs :\n'
                . '$req=application::$bdd->fetch("select * from user where id in (:ids)", $params=[":ids"=>[3, 5, 9]]);\n\n'
                . '//si vous renseignez un paramettre inutilisé,\n'
                . '//il sera ignoré sans générer d\'erreur, exemple ici avec :login inutilisé\n'
                . '$req = application::$bdd->fetch("select * from user where id=:id", $params=[":id"=>1, ":login"=>"admin"]);\n\n'
                . '//cela peux vous servir dans certain cas specifique où\n'
                . '// des champs demandé sont conditioné par des ternaires.\n\n'
                . '//ATTENTION ! l\'objet BDD ne tolère pas le marqueur "?" seul les marqueurs précédé de ":" sont admis'
                . '?>'
        );
    }

    private function classes_natives() {
        if (isset($_GET["native"]) and in_array($_GET["native"], docPHP_natives::get_methods()) and !in_array($_GET["native"], ["get_methods", "__construct"])) {
            $n = $_GET["native"];
            docPHP_natives::$n();
        } else {
            new docPHP_natives();
        }
    }

    private function services_general() {
        $dir_glyph = html_structures::glyphicon("folder-open", "");
        $arrow_glyph = html_structures::glyphicon("arrow-right", "");
        $file_glyph = html_structures::glyphicon("file", "");
        ?>
        <p>
            Les services sont des classes PHP qui répondent à des requêtes venant soit de plusieurs de vos propres projets,<br />
            soit d'applications extérieures (dans ce dernier cas on peut parler d'API ). <br />
            Ces classes doivent être placées dans <em>/html/commun/service</em>
        </p>
        <p>En général les services ne retournent pas d'HTML, mais des données au format JSON (recommandé), XML, CSV ou Serialisé</p>
        <p>
            Il est recommandé, mais pas obligatoire, de nommer le fichier contenant la classe comme ceci : <em>[nom_du_service]<strong>.service.php</strong></em> <br />
            et de passer par <em>/html/commun/service/index.php?service=nom_du_service</em> pour y acceder. <br />
            En procédant ainsi, votre service pourra exploiter le framework, les classes métiers et les entités.
        </p>
        <p>Exemple pratique, soit le projet avec l'arboressance suivante :</p>
        <div class="card">
            <div class="card-body">

                <ul class="no-puces">
                    <li><?php echo $dir_glyph; ?> mon_projet
                        <ul>
                            <li><?php echo $dir_glyph; ?> class
                                <ul>
                                    <li><?php echo $dir_glyph; ?> entity
                                        <ul>
                                            <li><?php echo $file_glyph; ?> index.php</li>
                                            <li><?php echo $file_glyph; ?> user.class.php</li>
                                        </ul>
                                    </li>
                                    <li><?php echo $file_glyph; ?> config.class.php</li>
                                    <li><?php echo $file_glyph; ?> page.class.php</li>
                                    <li><?php echo $file_glyph; ?> une_classe_metier.class.php</li>
                                </ul>
                            </li>
                            <li><?php echo $file_glyph; ?> index.php</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <p>Et le service <em>/html/commun/service/mon_service.service.php</em> qui retourne les données de l'utilisateur selon l'id</p>
        <?php
        js::monaco_highlighter('<?php\n\n'
                . 'class mon_service {\n\n'
                . '    public function __construct() {\n'
                . '        if (isset($_REQUEST["id"])) {\n'
                . '            //on charge le fichier de config\n'
                . '            include "../../mon_projet/class/config.class.php";\n\n'
                . '            //on charge les entités (cf. méthode plus bas )\n'
                . '            $this->entityloader();\n\n'
                . '            //on lance la connexion de la base de données dans application::$_bdd\n'
                . '            application::$_bdd = new bdd();\n\n'
                . '            //on utilise l\'entité\n'
                . '            $user = user::get_table_ordored_array("id=\'".application::$_bdd->protect_var($_REQUEST["id"])."\'");\n\n'
                . '            //on enlève le mot de passe des données à retourner\n'
                . '            unset($user[$_REQUEST["id"]]["psw"]);\n\n'
                . '            //on affiche le résultat en JSON\n'
                . '            echo json_encode($user);\n'
                . '        }\n'
                . '    }\n\n'
                . '    private function entityloader() {\n'
                . '        foreach (glob("../../mon_projet/class/entity/*.class.php") as $entity) {\n'
                . '            include $entity;\n'
                . '        }\n'
                . '    }\n'
                . '}\n'
                . '?>');
        ?><p>appeler le service :</p>
        <p>En PHP / DWF</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//en get\n'
                . 'echo service::HTTP_GET("http://localhost/commun/service/index.php?service=mon_service&id=1");\n\n'
                . '//en post\n'
                . 'echo service::HTTP_POST_REQUEST("http://localhost/commun/service/index.php", \n'
                . '        array(\n'
                . '            "service"=>"mon_service", \n'
                . '            "id"=>"1"\n'
                . '        )\n'
                . ');'
                . '?>'
        )
        ?>
        <p>En Ajax / JQuery</p>
        <?php
        js::monaco_highlighter(
                '    //en get\n'
                . '    $.get("http://localhost/commun/service/index.php", {service: "mon_service", id: 1}, function (data) {\n'
                . '        //instructions \n'
                . '    },"json");\n\n'
                . '    //en post\n'
                . '    $.post("http://localhost/commun/service/index.php", {service: "mon_service", id: 1}, function (data) {\n'
                . '        //instructions \n'
                . '    },"json");\n', "javascript");
    }

    private function services_interne() {
        ?>
        <p>Les services internes sont des services spécifiques à un projet, ces services exploiteront de base :</p>
        <ul>
            <li>Le fichier de configuration du projet</li>
            <li>Les variables de session</li>
            <li>La connexion à la base de données</li>
            <li>Les entités</li>
        </ul>
        <p>Ces services sont à placer dans <em>html/[votre-projet]/services/</em> et les fichiers doivent être nommés tels que : <em>[nom-service]<strong>.service.php</strong></em></p>
        <p>Pour que le fichier service/index.php soit créé correctement, la case "Services internes" doit être coché à la création du projet.</p>
        <?php
    }

    public static function CLI() {
        ?>
        <p>Les CLI sont des script PHP destiné à tourner en mode console, <br /> 
            très utile pour effectuer des opérations longues telles que des sauvegardes de grandes bases de données par exemple <br />
            ces scripts sont souvent appelés dans des <a href="https://fr.wikipedia.org/wiki/Cron">CRON</a> <br />
            il existe deux types de CLI dans le Framework :</p>
        <h4>Les CLI généraux</h4>
        <p>Ces CLI généraux se trouvent dans le dossier <strong>dwf/cli/</strong> et doivent répondre à certaines règles.</p>
        <ol>
            <li>Les fichiers doivent être nommés comme suit : <em>[nom_du_cli]</em><strong>.cli.php</strong></li>
            <li>Les scripts ne doivent pas utiliser de boucles infinies</li>
            <li>Les fichiers peuvent contenir un script procédural n'utilisant pas de classes (pas de POO), mais il est recommandé d'en utiliser comme ceci :
                <?php
                js::monaco_highlighter('<?php\n'
                        . 'class mon_cli {\n'
                        . '    public function __construct() {\n'
                        . '        //instructions\n'
                        . '    }\n'
                        . '}\n'
                        . 'new mon_cli();\n'
                        . '?>');
                ?>
            </li>
        </ol>
        <p>Une fois que tout vos CLI sont créés, vous pouvez les lancer via la commande :</p>
        <?php
        js::monaco_highlighter("php [chemain]/dwf/cli/start.php");
        ?>
        <p>start.php va charger les classes du framework, puis lancer les CLI un par un dans l'ordre alphabétique</p>
        <h4>Les CLI métiers</h4>   
        <p>Les CLI métiers sont beaucoup moins contraignants, ils peuvent étre placés dans le dossier "class" de votre projet ou dans un sous-dossier que vous aurez créé <br />
            <strong>html/<em>[votre-projet]</em>/class/cli</strong> par exemple,<br />
            il est recommandé (mais pas obligatoire) de nommer le fichier en <strong>.cli.php</strong> <br />
            ne le nommez pas en <strong>.class.php</strong>. Ou le CLI rentrerait en interraction avec l'application "web"
        </p>
        <p>il est recommandé de charger et utiliser la classe "cli" dans vos CLI métiers, comme ceci :</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'class mon_cli {\n'
                . '    public function __construct() {\n'
                . '        include "../../../dwf/class/cli.class.php";\n'
                . '        cli::classloader();\n'
                . '        //instructions\n'
                . '    }\n'
                . '}\n'
                . 'new mon_cli();\n'
                . '?>');
        ?>
        <p>La classe "cli" contient quelques fonctions utiles pour créer une application PHP en mode console,<br />
            cli::classloader() permet de charger les classes du framework et de pouvoir les utiliser. <br />
            Voici un petit script d'exemple avec la classe "cli"            
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//demande une saisie de l\'utilisateur et enregistre la saisie dans $nom\n'
                . '$' . 'nom = cli::read("saisissez votre nom :");\n\n'
                . '//Affiche un message\n'
                . 'cli::write("Bonjour $nom");\n\n'
                . '//exemple de \'progression\'\n'
                . 'cli::write("progression : 0 %");\n'
                . 'for ($i = 0; $i <= 100; $i++) {\n'
                . '    //rewite permet de réécrire la dernière ligne de la console\n'
                . '    cli::rewrite("progression : $i %");\n'
                . '    //marque une pause de 1 seconde\n'
                . '    cli::wait(1);\n'
                . '}\n\n'
                . '//marque une pose de 120 secondes en affichant un minuteur (qui décompte)\n'
                . 'cli::wait(120, true);\n'
                . '?>');
        ?>
        <p>Une fois que tout votre CLI métier est créé, vous pouvez le lancer via la commande :</p>
        <?php
        js::monaco_highlighter('php [chemin]/html/[votre-projet]/class/votre_cli.cli.php');
    }

    private function WebSocket() {
        docPHP_natives::websocket();
    }

    private function mise_en_ligne() {
        ?>
        <p>Lors de la mise en ligne de vos projets, vous devrez définir votre "projet par défaut" pour cela : <br />
            rendez-vous dans le fichier <em>html/commun/config/default.json</em> et modifiez la ligne suivante à votre convenance.
        </p>
        <?php
        js::monaco_highlighter('{"project":"[Votre-projet-par-défaut]"}');
        ?>
        <p>
            Depuis la version 21.24.05, il est possible d'exporter un projet DWF depuis l'interface "parcours de sites" (accessible uniquement en local). <br>
            L'export inclura tous les fichiers contenus dans votre dossier projet (à l'exception des CSS dans src/compact). <br>
            L'export inclura les fonctionnalités et fichiers nécessaires dont dépend votre projet, à condition que <em>export_dwf.class.php</em> ait correctement référencé les dépendances. <br>
            Il est conseillé de tester toutes les fonctionnalités de votre projet avant l'export, puis de tester les fonctionnalités de l'export elle-même.
        </p>
        <p>
            Cf export_dwf pour plus de détails.
            Le fichier JSON d'export est consultable dans <em>dwf/class/export_dwf/</em>.    
        </p>

        <?php
    }
}
