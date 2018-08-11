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
            "mise_en_ligne",
        );
        js::accordion("accordion", true, true);
        ?>
        <style type="text/css">
            h4{
                text-decoration: grey underline;
                font-weight: bold;
            }

        </style>
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
                Ces classes seront chargées (inclu) automatiquement. Si vous créez des classes sans cette syntaxe ou dans des sous dossiers, vous devrez les inclures vous même.
            </li>
            <li>L'utilisateur ne quitte jamais le fichier <em>html/[votre-projet]/index.php</em> hormis pour les export PDF/CSV ... qui s'ouvrent dans un nouvel onglet</li>            
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
            <li><?php echo $dir_glyph; ?> html
                <ul>
                    <li><?php echo $arrow_glyph; ?> <em>Contient vos projets</em></li>
                    <li><?php echo $dir_glyph; ?> commun
                        <ul>
                            <li><?php echo $arrow_glyph; ?> <em>Contient divers fichiers pour les export PDF/CSV/QRCode et création de nouveaux projets</em></li>
                            <li><?php echo $dir_glyph; ?> src
                                <ul>
                                    <li><?php echo $arrow_glyph; ?> <em>Contient toutes les fichier CSS et JS commun à tous les projets (et accessible aux utilisateurs)</em></li>
                                </ul>
                            </li>
                            <li><?php echo $dir_glyph; ?> service
                                <ul>
                                    <li><?php echo $arrow_glyph; ?> <em>Contient vos services / API</em></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><?php echo $dir_glyph; ?> [votre-projet]
                        <ul>
                            <li><?php echo $arrow_glyph; ?> <em>Contient votre index.php ( à ne pas modifier !)</em></li>
                            <li><?php echo $dir_glyph; ?> class
                            <li><?php echo $arrow_glyph; ?> <em>Contient vos classes specifiques au projet ainsi que le fichier de configuration</em>
                                <ul>
                                    <li><?php echo $dir_glyph; ?> entity</li>
                                    <li><?php echo $arrow_glyph; ?> <em>Contient les entités de votre projet</em></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><?php echo $dir_glyph; ?> dwf
                <ul>
                    <li><?php echo $dir_glyph; ?> class
                        <ul>
                            <li><?php echo $arrow_glyph; ?> <em>Contient les classes natives de DWF</em></li>
                        </ul>
                    </li>
                    <li><?php echo $dir_glyph; ?> cron
                        <ul>
                            <li><?php echo $arrow_glyph; ?> <em>Contient vos eventuelles cron à faire tourner dans vos consoles</em></li>
                        </ul>
                    </li>
                    <li><?php echo $dir_glyph; ?> log
                        <ul>
                            <li><?php echo $arrow_glyph; ?> <em>Contient les logs de vos projets</em></li>
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
            Ce fichier ne peut se lancer que depuis votre localhost ! il n'est pas possible de s'en servir à distance sans en modifier le code. <br />
            Une fois l'interface de création de projet ouvert, remplissez les champs correctement. Ces paramètres seront modifiable dans <em>config.class.php</em>
        </p>
        <ul>
            <li>Application
                <ul>
                    <li>Nom du dossier (apparait dans l'url) : nom du dossier du projet, il est conseillé de l'écrire en minuscule</li>
                    <li>Titre de l'application (apparait dans le "title" des page) : nom réel de votre projet</li>
                    <li>Préfixe (technique, utilisée pour les sessions, log ...) : le préfixe doit être unique à chaque projet, <br /> il sert à différencier les sessions et les logs utilisés dans les projets</li>
                    <li>
                        Hash (hash à utiliser pour chiffrer les mots de passe) : c'est ici que vous choisirez quel algorithme de chiffrement, vous utiliserez pour chiffrer vos mots de passe ou autres clés <br />
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
                    <li>Créer la base de données (si elle n'existe pas) : si la case est cochée et que la base de données n'existe pas, alors le framework pourra la créér.</li>
                    <li>Services interne (un dossier de service sera créé dans le projet) : votre projet auras t-il besoins de services "interne" (spécifique) ?</li>
                </ul>
            </li>
            <li> SMTP
                <ul>
                    <li>Host : host du serveur SMTP</li>
                    <li>Auth : True si une authentification est requise ( c'est presque toujours le cas)</li>
                    <li>Login : si Auth = true, nom d'utilisateur SMTP</li>
                    <li>Password : si Auth = true, mot de passe SMTP</li>
                    <li><em>astuce, si vous n'utilisez pas de SMTP metez HOST : localhost et Auth : false</em></li>
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
            nous retrouvons les renseignements saisis dans le formulaire de création de projet :</p>
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
            Il s'agit des routes de l'application, par défaut il y a des routes pour les utilisateur authentifiés (route_auth) et d'autres pour les non-authentifiés (route_unauth) <br />
            Les routes se définisent dans la fonction <em>config::onbdd_connected()</em> afin de pouvoir etre manipulé a volonté <br />
            ( ajouter des routes conditionelles, faire gérer les routes par une entitité, utiliser la classe trad (traductions), ...)<br />
            Une route se définit ainsi :
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "self::$" . "_route_unauth = array(\n"
                . "    array(\n"
                . "        \"page\" => \"index\",                         //la clé 'page' correspond à la variable $" . "_GET['page'],\n"
                . "                                                   //la valeur index correspond au nom d'une fonction dans 'page.class.php'\n"
                . "                                                   //une route et une fonction 'index' doivent toujours être présente dans les routes !\n\n"
                . "        \"title\" => \"Page d 'accueil\",              //Title de la page, chaque page doit avoir un title different pour des raisons\n"
                . "                                                   //d'accessiblilité et de référencement\n\n"
                . "        \"text\" => \"Accueil\",                       //FACULTATIF : texte affiché dans le menu principal\n"
                . "                                                   // (si la route n'est pas destinée à être affichée dans le menu, ne pas renseigner de clé 'text')\n\n"
                . "        \"description\" => \"Index de Documentation\", //FACULTATIF : meta description de la page pour le réferencement\n"
                . "        \"keyword\" => \"Index, Documentation\"        //FACULTATIF : meta keywords de la page pour le réferencement\n"
                . "    ),\n"
                . ");\n"
                . "?>", $this->_brush);
    }

    private function pages() {
        ?>
        <p>La classe "pages" est le point de départ de votre projet et est constitué des éléments suivants :</p>
        <ul>
            <li>Le constructeur : contient les opérations commune à toutes les pages (génération / mise à jour de fichiers, vérifications sur les données/entités ...)  </li>
            <li>Le header : c'est le haut de page de votre application, elle est commune à toutes les pages (mais vous pouvez utiliser un switch pour créer une entête à chaque page)</li>
            <li>Le footer : le pied de page, souvent commune à toutes les pages, contient généralement: date de création, contacts, les mentions légales, CGU ...</li>
            <li>Les fonctions liées au routes : ces fonctions doivent être publiques, le nom de la fonction doit correspondre à la valeur de la clé "page" d'une route</li>
            <li>Des fonctions privées : rien ne vous empêche de créer des fonctions privées dans page qui seront appelées dans le constructeur ou dans les fonctions publiques liées au routes</li>
        </ul>
        <p>Dans les fonctions de "page", vous êtes entièrement libre : <br />
            saisir du HTML, appeler des fonction privées de page, appeler vos "classes métiers", une classe natif de DWF ou n'importe quelle autre fonction statique</p>
        <?php
    }

    private function classes_metiers() {
        ?>
        <p>
            Les classes dites "métiers" sont les classes spécifiques à votre projet que vous aurez vous même créé dans le dossier <em>html/[votre-projet]/class/</em>, <br />
            contrairement à d'autres framework comme "symfony" ces classes ne nécessitent pas d'être étendues d'une classe natif de DWF ! <br />
            vous êtes libre de lui donnéer le nom que vous voulez ( sauf un nom déja pris par une classe native ou une de vos entités) <br />
            libre d'utiliser des "methodes magiques" tels que les constructeur, destructeur ... <br />
            La seule restriction est le nom du fichier qui doit être comme ceci : <em>[nom_de_votre_classe]<strong>.class.php</strong></em>. <br />
            Une fois votre classe créée, vous pouvez l'appeler et l'utiliser dans <em>pages.class.php</em>
        </p>
        <?php
    }

    private function methodes_evenementielles() {
        ?>
        <p class="alert alert-warning">
            Pour créer des evenements lié a votre application, previligiez l'utilisation de <em>event.class.php</em>. <br />
            Elle permet de créer et déclancher des evenements de manière plus stable et avec un meilleur contrôle. <br />
            (cf classes natives > event)
        </p>
        <h4>Utiliser les méthodes événementielles</h4>
        <p>
            Les méthodes événementielles sont des méthodes qui seront appelées lors d'un événement précis du framework, utilisation :            
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "class ma_classe_metier{\n\n"
                . "    public static onload(){\n"
                . "        //Supprimé depuis la verssion 21.17.12\n"
                . "    }\n\n"
                . "    public static onhtml_head(){\n"
                . "        //Supprimé depuis la verssion 21.17.12\n"
                . "    }\n\n"
                . "    public static onhtml_body_end(){\n"
                . "        //cette méthode sera executée automatiquement par le framework \n"
                . "        // juste avant les balises <p id=\"real_title\" class=\"hidden\">...</p></body>\n"
                . "        // cette méthode peut se substituer à la methode __destruct() si elle doit générer du HTML\n"
                . "    }\n\n"
                . "    public static onbdd_connected(){\n"
                . "        //cette méthode sera executée automatiquement par le framework \n"
                . "        // dès que la connexion à la base de données est établie\n"
                . "    }\n\n"
                . "}\n?>", $this->_brush);
        ?>
        <p>
            Comme indiqué, ces methodes doivent être en <em>public static</em>, elles peuvent étre utilisées dans n'importe quel classe SAUF les entités ! <br />
            Ces méthodes ne prennent pas de paramètres et ne retourne rien.
        </p>
        <p class="alert alert-warning">ATTENTION : Depuis la version 21.17.12, <br />
            pour que les methodes evenementielles ce déclanchent, la classe concerné doit avoir été appelé au moins une fois avant le déclancheur ! <br />
            (instanciation ou appel d'une methode static)
        </p>
        <h4>Créer un déclencheur</h4>
        <p>
            Il est possible de créer vos propres déclencheurs d'événements grâce à la methode.
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "application::event('mon_evenement');\n"
                . "?>", $this->_brush);
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
            une classe entité vous permet de lire, ajouter, modifier ou supprimer des entrées de votre base de données sans avoir à saisir une requete SQL <br />
            (ormis une eventuelle condition "where" ). Les entités exploitent un objet bdd accessible via <em>application::$_bdd</em> <br />
            les entités seront capable de recréer la structure de leur base de données si celle-ci est perdue (mais ne permettent pas de sauvegarder les données !)
        </p>
        <h4>Créer des entités</h4>
        <p>
            La création d'une entité est simple, il est conseillé de mettre la création d'entité soit dans le constructeur de pages.class.php <br />
            soit dans le constructeur de la classe métier qui exploitera cette entité. Voici le code (consultez la doc technique pour plus d'informations)
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "data=array(                         //$" . "data est un tableau à deux dimensions qui definit\n"
                . "                                     //la structure de l'entité et de sa table dans la base de données\n\n"
                . "    array('id','int',true),          //créé un champ/attribut nommé 'id' de type entier,\n"
                . "                                     //le 'true' indique une clé primaire, le setter de 'id' sera en privé\n"
                . "    array('login','string',false),\n"
                . "    array('psw','string',false),\n"
                . ");\n"
                . "$" . "table='user';                       //nom de la table et de l'entité\n\n"
                . "new entity_generator($" . "data, $" . "table); //Créé l'entité et sa table si elle n'existe pas\n"
                . "                                     //Attention : si la structure de l'entité est modifié \n"
                . "                                     //il faudra supprimer la classe et la table de l'entité\n"
                . "?>", $this->_brush);
        ?>
        <p>
            Créer des relations entres les entités et Astuce pour créer plusieurs entités facilement
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "datas=array(\n"
                . "    'rang'=>array( //on créé une entité rang\n"
                . "        array('id','int',true),\n"
                . "        array('nom','string',false),\n"
                . "    ),\n"
                . "    'user'=>array( //on créé une entité user\n"
                . "        array('id','int',true),\n"
                . "        array('login','string',false),\n"
                . "        array('psw','string',false),\n"
                . "        array('rang','rang',false), //on met en relation le fait qu'un user a un rang (de type 'rang')\n"
                . "    ),\n"
                . ");\n"
                . "foreach($" . "datas as $" . "table => $" . "data){\n"
                . "    new entity_generator($" . "data, $" . "table);\n"
                . "}\n"
                . "?>", $this->_brush);
        ?>
        <h4>MAJ 21.18.02</h4>
        <p>Depuis la version 21.18.02 il est possible de créer l'enssemble de vos entités ainsi :</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "entity_generator::generate([\n"
                . "    'rang'=>[ //on créé une entité rang\n"
                . "        ['id','int',true],\n"
                . "        ['nom','string',false],\n"
                . "    ],\n"
                . "    'user'=>[ //on créé une entité user\n"
                . "        ['id','int',true],\n"
                . "        ['login','string',false],\n"
                . "        ['psw','string',false],\n"
                . "        ['rang','rang',false], //on met en relation le fait qu'un user a un rang (de type 'rang')\n"
                . "    ],\n"
                . "]);\n"
                . "?>", $this->_brush);
        ?>
        <h4>Utilisation des entités</h4>
        <p>Une fois les entités créées, elles peuvent être utilisées (nous utiliserons l'exemple des 'user' et 'rang')</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//ajoute un utilisateur\n"
                . "user::ajout($" . "login, hash(config::$" . "_hash_algo, $" . "psw));\n\n"
                . "//récuperer tout les utilisateurs sous forme de tableau de données\n"
                . "$" . "users = user::get_table_array();\n"
                . "echo $" . "users[0]['login']; //affiche le login du premier utilisateur de la table\n\n"
                . "//recuperer tout les utilisateurs du rang 1\n"
                . "$" . "users = user::get_table_array('rang=1');\n\n"
                . "//astuce pour récuperer tout les utilisateurs par ordre alphabetique de login\n"
                . "$" . "users = user::get_table_array('1=1 order by login');\n\n"
                . "//récuperer tout les rang sous forme de table ordoné par leur ID (les id sont les clé du tableau)\n"
                . "$" . "rangs = rang::get_table_ordored_array();\n"
                . "echo $" . "rangs[1]['nom']; //affiche le nom du rang ayant l'identifiant 1\n\n"
                . "//récuperer les utilisateur sous forme de collection (tableau d'objet)\n"
                . "//DECONSEILLÉ ! potentiellement lourd !\n"
                . "$" . "users = user::get_collection();\n"
                . "echo $" . "users[0]->get_rang()->get_nom(); //affiche le nom du rang du premier utilisateur de la table\n\n"
                . "//recupere l'objet d'un utilisateur à partir de son id\n"
                . "$" . "user = user::get_from_id(1);\n"
                . "echo $" . "user->get_login(); //affiche le login de l'utilisateur 1\n"
                . "$" . "user->set_login($" . "nouveau_login); //redéfini le login de l'utilisateur 1,\n"
                . "                                  //la modification dans la base de donnée sera prise en compte a la fin du script\n\n"
                . "//supprimer un utilisateur : 2 solutions\n"
                . "//1 : supprimer un utilisateur non instancié depuis son id\n"
                . "user::delete_by_id($" . "id);\n"
                . "//2 : supprimer un utilisateur instancié\n"
                . "$" . "user->delete();"
                . "?>", $this->_brush);
        ?>
        <p class="alert alert-danger">
            ATTENTION : si vous utilisez des variables dans les paramètres $where : utilisez <em>application::$_bdd->protect_var()</em> pour vous protéger : <br />
            - des injections SQL <br />
            - des injections XSS <br />
            pensez egalement à la fonction <a href="https://secure.php.net/manual/fr/function.strip-tags.php" target="_blank">strip_tags</a> en cas de besoin.
        </p>
        <h4>Les types de champ/attribut</h4>
        <?php
        echo html_structures::table(["Type (code PHP)", "Type (SQL)", "Description"], [
            ["int, integer", "int(11)", "un champ de nombre entier"],
            ["string", "text", "un champ de texte, peut contenir aussi du HTML, des dates, ou des nombres"],
            ["mail", "text", "un champ de texte pour les mail, une verification est faite en PHP par l'entité avant l'enregistrement en base de donnée"],
            ["array", "text", "(depuis la version 21.18.03) un champ de texte JSON, les converssions de array (coté PHP) en JSON (coté SQL) et inversement son géré en PHP par l'entité. <br />"
                . "Inutile donc d'utiliser json_encode() et json_decode()"],
        ]);
    }

    private function bdd() {
        ?>
        <p>
            L'objet bdd est l'objet qui permet de gèrer la connexion a la base de données et sécuriser les variables déstinées à être utilisées dans des requêtes SQL. <br />
            Cet objet utilise les informations PDO qui sont renseignées dans le fichier de configuration. <br />
            Vos entités et de nombreuses classes native de DWF exploitent cette objet.
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//fetch permet d'executer une requete de type select et d'en recuperer le resultat sous forme d'un tableau\n"
                . "$" . "req=application::$" . "_bdd->fetch($" . "statement);\n\n"
                . "//query permet d'executer des requetes de type insert into, update et delete, ne retourne rien !\n"
                . "application::$" . "_bdd->query($" . "statement);\n\n"
                . "//protect_var permet déchaper les caractaires dangereux pour votre base de donée et votre application\n"
                . "$" . "var = application::$" . "_bdd->protect_var($" . "var);\n\n"
                . "//verif_email retourne true si la chaine rentré en paramètres respecte le format email, false si non\n"
                . "$" . "is_email=application::$" . "_bdd->verif_email($" . "email);\n\n"
                . "//unprotect_var est déprécié si vous utilisez une version de php superieur à 5.4\n"
                . "//servait a annuler l'échapement de caractaires pour les variable destiné a être affiché\n"
                . "$" . "var = application::$" . "_bdd->unprotect_var($" . "var);\n"
                . "?>", $this->_brush);
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
            Les services sont des classe PHP qui répondent a des requetes venant soit de plusieurs de vos propres projets,<br />
            soit d'applications exterieurs (dans se dernier cas on peux parler d'API ). <br />
            ces classes doivent ètre placé dans <em>/html/commun/service</em>
        </p>
        <p>En général les services ne retourne pas d'HTML mais des données au format JSON (recomandé), XML, CSV ou Serialisé</p>
        <p>
            Il est recomandé, mais pas obligatoire, de nommer le fichier contenant la classe comme ceci : <em>[nom_du_service]<strong>.service.php</strong></em> <br />
            et de passer par <em>/html/commun/service/index.php?service=nom_du_service</em> pour y acceder. <br />
            En procédant ainsi votre service pourra exploiter le framework.
        </p>
        <p>exemple pratique, soit le projet avec l'arboressance suivante :</p>
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
        js::syntaxhighlighter("<?php\n\n"
                . "class mon_service {\n\n"
                . "    public function __construct() {\n"
                . "        if (isset($" . "_REQUEST[\"id\"])) {\n"
                . "            //on charge le fichier de config\n"
                . "            include '../../mon_projet/class/config.class.php';\n\n"
                . "            //on charge les entitées (cf. methode plus bas )\n"
                . "            $" . "this->entityloader();\n\n"
                . "            //on lance la connexion de la base de donée dans application::$" . "_bdd\n"
                . "            application::$" . "_bdd = new bdd();\n\n"
                . "            //on utilise l'entitée\n"
                . "            $" . "user = user::get_table_ordored_array(\"id='\".application::$" . "_bdd->protect_var($" . "_REQUEST[\"id\"]).\"'\");\n\n"
                . "            //on enleve le mot de passe des données a retourner\n"
                . "            unset($" . "user[$" . "_REQUEST[\"id\"]][\"psw\"]);\n\n"
                . "            //on affiche le resultat en JSON\n"
                . "            echo json_encode($" . "user);\n"
                . "        }\n"
                . "    }\n\n"
                . "    private function entityloader() {\n"
                . "        foreach (glob(\"../../mon_projet/class/entity/*.class.php\") as $" . "entity) {\n"
                . "            include $" . "entity;\n"
                . "        }\n"
                . "    }\n"
                . "}\n\n"
                . "?>", "php; html-script: true");
        ?><p>appeler le service :</p>
        <p>En PHP / DWF</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//en get\n"
                . "echo service::HTTP_GET(\"http://localhost/commun/service/index.php?service=mon_service&id=1\");\n\n"
                . "//en post\n"
                . "echo service::HTTP_POST_REQUEST(\"http://localhost/commun/service/index.php\", \n"
                . "        array(\n"
                . "            \"service\"=>\"mon_service\", \n"
                . "            \"id\"=>\"1\"\n"
                . "        )\n"
                . ");\n"
                . "?>", "php; html-script: true")
        ?>
        <p>En Ajax / JQuery</p>
        <?php
        js::syntaxhighlighter("<script type=\"text/javascript\">\n"
                . "    //en get\n"
                . "    $.get(\"http://localhost/commun/service/index.php\", {service: mon_service, id: 1}, function (data) {\n"
                . "        //instructions \n"
                . "    },'json');\n\n"
                . "    //en post\n"
                . "    $.post(\"http://localhost/commun/service/index.php\", {service: mon_service, id: 1}, function (data) {\n"
                . "        //instructions \n"
                . "    },'json');\n"
                . "</script>", "js; html-script: true");
    }

    private function services_interne() {
        ?>
        <p>Les services internes sont des services spécifiques a un projet, ces services exploiteront de base :</p>
        <ul>
            <li>Le fichier de configuration du projet</li>
            <li>Les variables de sessions</li>
            <li>La connexion a la base de donnée</li>
            <li>Les entitées</li>
        </ul>
        <p>Ces services sont a placer dans <em>html/[votre-projet]/services/</em> et les fichiers doivent être nommé tel que : <em>[nom-service]<strong>.service.php</strong></em></p>
        <p>Pour que le fichier service/index.php soit créé correctement la case "Services interne" doit être coché a la création du projet !</p>
        <?php
    }

    private function CLI() {
        ?>
        <p>Les CLI sont des script PHP déstiné a tourner en mode console, <br /> 
            très utile pour effectuer des operations longues tels que des sauvegarded de grosses bases de données par exemple <br />
            ces script sont souvant appelé dans des <a href="https://fr.wikipedia.org/wiki/Cron">CRON</a> <br />
            il existe deux types de CLI dans le Framework :</p>
        <h4>Les CLI généraux</h4>
        <p>Ces CLI généraux se trouvent dans le dossier <strong>dwf/cli/</strong> et doivent répondre a certaine régles</p>
        <ol>
            <li>Les fichiers doivent être nomé comme suis : <em>[nom_du_cli]</em><strong>.cli.php</strong></li>
            <li>Les script ne doivent pas utiliser de boucles infini</li>
            <li>les fichier peuvent contenir un script procédurale n'utilisant pas de classe (pas de POO), mais il est recomandé d'en utiliser comme ceci :
                <?php
                js::syntaxhighlighter("<?php\n"
                        . "class mon_cli {\n"
                        . "    public function __construct() {\n"
                        . "        //instructions\n"
                        . "    }\n"
                        . "}\n"
                        . "new mon_cli();\n"
                        . "?>", $this->_brush);
                ?>
            </li>
        </ol>
        <p>Une fois que tout vos CLI sont créé vous pouvez les lancer via la comande :</p>
        <?php
        js::syntaxhighlighter("php [chemain]/dwf/cli/start.php");
        ?>
        <p>start.php va charger les classes du framework puis lancer las CLI un par un dans l'ordre alphabetique</p>
        <h4>Les CLI métiers</h4>   
        <p>Les CLI métier sont beaucoup moins contraignant ils peuvent étre placé dans le dossier "class" de votre projet ou dans un sous dossier que vous aurez créé <br />
            <strong>html/<em>[votre-projet]</em>/class/cli</strong> par exemple,<br />
            il est recommandé (mais pas obligatoire) de nommer le fichier en <strong>.cli.php</strong> <br />
            ne le nommez pas en <strong>.class.php</strong> ! ou le CLI rentrerait en interaction avec l'application "web"
        </p>
        <p>il est recomendé de charger et utilisé la classe "cli" dans vos CLI métier, comme ceci :</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "class mon_cli {\n"
                . "    public function __construct() {\n"
                . "        include '../../../dwf/class/cli.class.php';\n"
                . "        cli::classloader();\n"
                . "        //instructions\n"
                . "    }\n"
                . "}\n"
                . "new mon_cli();\n"
                . "?>", $this->_brush);
        ?>
        <p>la class "cli" contiens quelques fonction utiles pour créer une application PHP en mode concole,<br />
            cli::classloader() permet de charger les classes du framework et de pouvoir les utiliser. <br />
            voisi un petit script d'exemple avec la classe "cli"            
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//demande une saisie de l'utilisateur et enregistre la saisis dans $" . "nom\n"
                . "$" . "nom = cli::read('saisisez votre nom :');\n\n"
                . "//Affiche un message\n"
                . "cli::write('Bonjour '.$" . "nom);\n\n"
                . "//exemple de \"progression\"\n"
                . "cli::write('progression : 0 %');\n"
                . "for ($" . "i = 0; $" . "i <= 100; $" . "i++) {\n"
                . "    //rewite permet de réécrire la dernière ligne de la console\n"
                . "    cli::rewrite('progression : '.$" . "i.' %');\n"
                . "    //marque une pause de 1 seconde\n"
                . "    cli::wait(1);\n"
                . "}\n\n"
                . "//marque une pose de 120 secondes en affichant un minuteur (qui décompte)\n"
                . "cli::wait(120, true);\n"
                . "?>", $this->_brush);
        ?>
        <p>Une fois que tout votre CLI métier est créé vous pouvez le lancer via la comande :</p>
        <?php
        js::syntaxhighlighter("php [chemain]/html/[votre-projet]/class/votre_cli.cli.php");
    }

    private function mise_en_ligne() {
        ?>
        <p>Lors de la mise en ligne de vos projets vous devrez définir votre "projet par defaut" pour cela : <br />
            rendez vous dans le fichier <em>html/index.php</em> et modifiez la ligne suivante à votre convenance.
        </p>
        <?php
        js::syntaxhighlighter("<?php\nheader(\"Location: ./doc/index.php\"); \n ?>", $this->_brush);
        ?>
        <p>Par :</p>
        <?php
        js::syntaxhighlighter("<?php\nheader(\"Location: ./[Votre-projet-par-défaut]/index.php\"); \n?>", $this->_brush);
    }

}
