<?php

class docPHP {

    private $_brush = "php; html-script: true";

    public function __construct() {
        $doc = array(
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
        );
        ?>
        <style type="text/css">
            h4{
                text-decoration: grey underline;
                font-weight: bold;
            }

        </style>
        <!--resout les conflits avec monaco editor-->
        <table class="table" id="datatable_loading">
            <thead>
                <tr>
                    <th>Chargement ...</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="display: none;">
                            <?php new video("./files/videos/nuagesMusicman921.webm", "videojs_loading"); ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
        js::datatable("datatable_loading");
        ?>
        <script>
            $(document).ready(function () {
                setTimeout(function () {
                    $("#datatable_loading").DataTable().destroy();
                    $("#datatable_loading").remove();
                }, 200);
            })
        </script>
        <!--fin de la resolution de conflit-->
        <h2>Framework PHP</h2>
        <div id="accordion">
            <?php
            foreach ($doc as $d) {
                ?>
                <h3><?php echo strtr(ucfirst($d), array("_" => " ")); ?></h3>
                <div><?php $this->$d(); ?></div>
                <?php
            }
            ?>
        </div>
        <?php
        js::accordion("accordion", true, true);
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

        <?php
    }

    private function nouveau_projet() {
        ?>
        <p>
            La création d'un nouveau projet est automatisé par le fichier <a href="../commun/new_app.php" target="_blank"><em>/html/commun/new_app.php</em></a> accédez à ce fichier par votre navigateur -> localhost <br />
            Ce fichier ne peut se lancer que depuis votre localhost. Il n'est pas possible de s'en servir à distance sans en modifier le code.<br/>
            Une fois l'interface de création de projet ouvert, remplissez les champs correctement. Ces paramètres seront modifiable dans <em>config.class.php</em>
        </p>
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
        ?>
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
                . '        ["psw","string",false],\n'
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
                . 'user::ajout($login, hash(config::$$_hash_algo, $psw));\n\n'
                . '//récuperer tout les utilisateurs sous forme de tableaux de données\n'
                . '$users = user::get_table_array();\n'
                . 'echo $users[0]["login"]; //affiche le login du premier utilisateur de la table\n\n'
                . '//récuperer tout les utilisateurs du rang 1\n'
                . '$users = user::get_table_array("rang=1");\n\n'
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
            ATTENTION : si vous utilisez des variables dans les paramètres $where : utilisez <em>application::$_bdd->protect_var()</em> pour vous protéger : <br />
            - des injections SQL <br />
            - des injections XSS <br />
            pensez également à la fonction <a href="https://secure.php.net/manual/fr/function.strip-tags.php" target="_blank">strip_tags</a> en cas de besoin.
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
        ]);
    }

    private function bdd() {
        ?>
        <p>
            L'objet bdd est l'objet qui permet de gérer la connexion à la base de données et sécuriser les variables destinées à être utilisées dans des requêtes SQL. <br />
            Cet objet utilise les informations PDO qui sont renseignées dans le fichier de configuration. <br />
            Vos entités et de nombreuses classes native de DWF exploitent cette objet.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//fetch permet d\'éxecuter une requête de type select et d\'en récuperer le résultat sous forme d\'un tableau\n'
                . '$req=application::$bdd->fetch($statement);\n\n'
                . '//query permet d\'executer des requêtes de type insert into, update et delete, ne retourne rien !\n'
                . 'application::$bdd->query($statement);\n\n'
                . '//protect_var permet d\'échapper les caractères dangereux pour votre base de données et votre application\n'
                . '$var = application::$bdd->protect_var($var);\n'
                . '//Depuis la version 21.23.08 cette méthode a été remplacé par \n'
                . 'bdd:p($var)\n\n'
                . '//verif_email retourne true si la chaine rentrée en paramètres respecte le format email, false si non\n'
                . '$is_email=application::$bdd->verif_email($email);\n\n'
                . '//unprotect_var est déprécié si vous utilisez une version de php superieur à 5.4\n'
                . '//servait à annuler l\'échappement de caractères pour les variables destinés à être affichés\n'
                . '$var = application::$bdd->unprotect_var($var);\n'
                . '//Depuis la version 21.23.08 cette méthode a été remplacé par \n'
                . 'bdd:up($var)\n\n'
                . '?>'
        );
    }

    private function classes_natives() {
        new docPHP_natives();
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

    private function CLI() {
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
        ?>
        <p> 
            Les WebSocket permettent de faire de la communication en temps réel (Real Time Connexion, RTC). <br />
            L'utilisation première des WebSocket est pour les tchats entre utilisateurs 
            mais ils peuvent aussi être utilisé pour notifier un utilisateur <br />
            ou afficher une donnée très variable dans le temps en temps réel (exemple : un stock dans une application de gestion)
        </p>
        <p>
            Un serveur de WebSocket tourne indépendament du serveur web et écoute son propre port (9000 par défaut dans DWF, parametrable dans la config du projet). <br />
            il est possible de lancer le serveur en mode console (CLI), notamment pour débugger :
        </p>
        <?php
        js::monaco_highlighter('php [chemin]/html/[votre-projet]/websocket/index.php');
        ?>
        <p>En production, il est possible de laisser l'application lancer elle même le serveur de websocket en utilisant la classe services pour lancer une requête qui lancera le serveur :</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'service::HTTP_POST("http://localhost/[votre-projet]/websocket/index.php");\n'
                . '?>');
        ?>
        <p>L'application ne lancera le serveur qu'une seule fois.</p>
        <p>Coté client, la connexion peut être géré avec l'objet <?= html_structures::a_link("https://javascript.info/websocket", "JS natif WebSocket", "", "", true) ?> </p>
        <?php
        js::monaco_highlighter(''
                . '    var socket = null;\n'
                . '    (function start_websocket() {\n'
                . '        socket = new WebSocket("ws://localhost:9000/");\n'
                . '        socket.addEventListener("open", function (e) {\n'
                . '            //cette ligne permet d\'authentifier un utilisateur qui serait authentifié sur l\'aplication\n'
                . '            socket.send(\'{"action": "auth", "token": "<?= websocket_server::auth() ?>"}\');\n'
                . '        });\n'
                . '        socket.addEventListener("close", function (e) {\n'
                . '            //retente une connexion toutes les 10 secondes en cas de coupure\n'
                . '            socket = null;\n'
                . '            let si_ws_reco = setInterval(function () {\n'
                . '                if (socket) {\n'
                . '                    start_websocket();\n'
                . '                    clearInterval(si_ws_reco);\n'
                . '                }\n'
                . '            }, 10000);\n'
                . '        });\n'
                . '        socket.addEventListener("message", function (e) {\n'
                . '            data = JSON.parse(e.data);\n'
                . '            //verifie si l\'utilisateur est authentifié\n'
                . '            if (undefined != data.auth) {\n'
                . '                if (data.auth) {\n'
                . '                    //l\'utilisateur est authentifié\n'
                . '                } else {\n'
                . '                    //l\'utilisateur n\'est pas authentifié, affiche l\'erreur\n'
                . '                    alert(data.message);\n'
                . '                }\n'
                . '            }\n'
                . '        });\n'
                . '    })();\n');
        ?>
        <p>
            ATTENTION ! Actuellement DWF ne gère pas le tunnel de chiffreement (SSL/TLS), <br />
            c'est à vous de le mettre en place via le système de proxy de votre serveur web.
        </p>
        <p>
            Les websockets de DWF fonctionnent avec l'envoi et la reception de chaines JSON. <br />
            dans les chaines d'envoi vers le serveur une clé "action" est obligatoire afin d'indiquer au websocket quel traitement appliquer. <br />
            le reste des clés sont libre. <br />
            la seule action définie par défaut et l'action d'authentification qui prend en seconde clé un token d'authentification (qui peut être vide) :
        </p>
        <?php
        js::monaco_highlighter('{"action":"auth","token":""}');
        ?>
        <p>Le retour est une des ses possibilité :</p>
        <?php
        js::monaco_highlighter('{"auth":false,"message":"Token empty"}\n' .
                '{"auth":false,"message":"Invalid token"}\n' .
                '{"auth":false,"message":"Token conflict"}\n' .
                '{"auth":true,"message":"OK"}');
        ?>
        <p>Pour rajouter une action, il suffit de rajouter une classe dans le dossier "websocket" du projet qui sera nommé : <br />
            <strong>[nomDeLAction].ws.php</strong> <br />
            et qui doit avoir la forme suivante :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'class nomDeLAction {\n'
                . '\n'
                . '    /**\n'
                . '     * \n'
                . '     * @param websocket_client $client le client qui a emit la requête\n'
                . '     * @param array $message La chaine JSON déja convertie en tableau\n'
                . '     */\n'
                . '    public function __construct(&$client, &$message) {\n'
                . '        //traitement à faire\n'
                . '        //Les classes de DWF et les Entity du projet sont utilisables\n'
                . '\n'
                . '        //envoi une réponse à l\'émetteur\n'
                . '        $client->write("réponse");\n'
                . '    }\n'
                . '}\n'
                . '?>\n');
        ?>
        <p>La classe <strong>websocket_client</strong> permet de gérer les utilisateurs connectés, <br />
            elle possède aussi des méthodes statiques qui permettent de sélectionner d'autres utilisateurs connectés. <br />
            Gardez en tête qu'un utilisateur peut avoir des connexions multiples (s'il ouvre plusieurs onglets par exemple).
        </p>
        <p>
            La classe <strong>websocket_request</strong> permet de lancer des requetes au serveur websocket depuis PHP
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$wr = new websocket_request($host = "127.0.0.1", $port = 9000);\n'
                . '//envoie un message sans attendre de réponse\n'
                . '$wr->send("message");\n'
                . '//envoie un message en attendant une réponse\n'
                . '$reponse = $wr->request("message");\n'
                . '//Ferme la connexion\n'
                . '$wr->close();\n'
                . '?>\n');
    }

    private function mise_en_ligne() {
        ?>
        <p>Lors de la mise en ligne de vos projets, vous devrez définir votre "projet par défaut" pour cela : <br />
            rendez-vous dans le fichier <em>html/commun/config/default.json</em> et modifiez la ligne suivante à votre convenance.
        </p>
        <?php
        js::monaco_highlighter('{"project":"[Votre-projet-par-défaut]"}');
    }
}
