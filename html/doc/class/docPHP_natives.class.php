<?php

class docPHP_natives {

    private $_brush = "php; html-script: true";

    public function __construct() {
        ?>
        <p>
            Voici quelques classes natives de DWF et quelques exemples d'utilisation, pour plus d'informations, chaque classe et fonction sont commentées (document technique) <br />
            si une classe/fonction a mal été commentée ( ou pas du tout commentée) merci de nous le signaler. <br />
            (il s'agit de quelques unes des classes les plus utiles du framework, le framework compte plus de <?= count(glob("../../dwf/class/*.class.php")); ?> classes natives)
        </p>
        <div id="accordion_classes_natives">
            <?php
            foreach (get_class_methods(__CLASS__) as $m) {
                if ($m != __FUNCTION__) {
                    ?>
                    <h4><?= strtr($m, array("__" => ", ")); ?></h4>
                    <div><?php $this->$m(); ?></div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
        js::accordion("accordion_classes_natives", true, true);
    }

    private function admin_controle() {
        ?>
        <h4>admin_controle</h4>
        <p>admin_controle est une classe permettant de créer une interface d'administration "user-friendly", <br />
            Cette classe est destinée à être utilisée dans une partie "administration" du site. <br />
            Cette classe affiche l'administration d'une table de la base de données, <br />
            elle a besoin de l'entité de cette table pour fonctionner et sait gérer les relations entre les tables.
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//affiche l'interface d'administration de la table 'rang'\n"
                . "new admin_controle('rang');\n"
                . "echo '<hr />'\n"
                . "//affiche l'interface d'administration de la table 'user' avec la relation user.rang = rang.nom\n"
                . "new admin_controle('user', array('rang'=>'nom'));\n"
                . "?>", $this->_brush);
        ?>
        <p>
            Si l'entité contient un champ "array", ce champ n’apparaitra pas dans les datatables. <br />
            Dans les formulaires, les données de ce champ seront accessible en JSON dans un input de type hidden. <br />
            Vous devrez créer une interface en JavaScript pour administrer ce champ à votre convenance (en manipulant la chaine JSON). 
        </p>
        <?php
    }

    private function application() {
        ?>
        <p>Cette classe fait office de contrôleur et layout pour l'application, <br />
            elle fait la liaison entre les routes et les pages (cf configuration et pages) <br />
            et met à disposition quelques outils :</p>
        <ul>
            <li>Instancie la connexion à la base de données par un objet bdd accessible via application::$_bdd (cf bdd)</li>
            <li>Permet d'utiliser des méthodes évènementielles à partir de application::event() (cf : méthodes évènementielles)</li>
        </ul>
        <?php
    }

    private function audio() {
        ?>
        <p>Cette classe permet de générer un lecteur audio.</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Créé un lecteur avec une seule source\n"
                . "new audio($" . "src=\"./files/musiques/GM-The Search.mp3\", $" . "id=\"player\");\n\n"
                . "//Créé un lecteur avec une playlist\n"
                . "(new audio(\"\", \"player2\"))->playlit(array(\n"
                . "    array(\"src\"=>\"./files/musiques/GM-The Search.mp3\", \"titre\"=>\"InYourDreams - The Search\"),\n"
                . "    array(\"src\"=>\"./files/musiques/IYD-New World.mp3\", \"titre\"=>\"InYourDreams - New World\"),\n"
                . "));\n"
                . "?>", $this->_brush);
        ?>
        <div class="row">
            <div class="col-sm-6">
                <?php
                new audio("./files/musiques/GM-The Search.mp3");
                ?>
            </div>
            <div class="col-sm-6">
                <?php
                (new audio("", "player2"))->playlist(array(
                    array("src" => "./files/musiques/GM-The Search.mp3", "titre" => "InYourDreams - The Search"),
                    array("src" => "./files/musiques/IYD-New World.mp3", "titre" => "InYourDreams - New World")
                ));
                ?>
            </div>
        </div>
        <p>Crédits : <a href="https://inyourdreams.newgrounds.com/" target="_blank">InYourDreams (Newgrounds)</a></p>
        <?php
    }

    private function auth() {
        ?>
        <p>
            Auth est la classe qui gère l'authentification des utilisateurs, <br />
            il prend en paramètres : le nom de la table/entité utilisateur ( dans cette documentation : 'user'), le nom du champ de login ('login'), le nom du champ de mot de passe ('psw'). <br />
            auth utilise deux variables de sessions accessibles via la classe "session" <br />
            (session::set_auth(),session::get_auth(),session::set_user() et session::get_user()) <br />
            lorsque l'utilisateur est autentifié, session::get_auth() retourne true et session::get_user() contient l'identifiant de l'utilisateur (id de la base de données) <br />
            sinon session::get_auth() et session::get_user() retourne false et auth affiche un formulaire d'authentification.

        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//affiche le formulaire d'authentification si non authentifié\n"
                . "new auth('user', 'login', 'psw');\n\n"
                . "//version avec une sécurité (token)\n"
                . "new auth('user', 'login', 'psw', true);\n"
                . "?>", $this->_brush);
    }

    /* private function ban_ip() {

      }

      private function bbParser() {

      }

      private function bdd() {

      } */

    private function bootstrap_theme() {
        ?>
        <p>Cette classe permet de gèrer les thèmes de bootswatch,<br />
            le thème par défaut peut être défini dans <em>config.class.php</em></p>        
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Affiche une interface (modal) permettant à l'utilisateur de choisir un thème\n"
                . "echo bootstrap_theme::user_custom();\n"
                . "?>", $this->_brush);
    }

    private function cache() {
        ?>
        <p>Cette classe permet de gérer une mise en cache ( côté serveur ) <br />
            utilise session::set_val("cache",[ ]) et session::get_val("cache")</p>        
        <?php
        js::syntaxhighlighter("<?php\n"
                . "if($" . "contenu= cache::get('ma_cle')){\n"
                . "    echo $" . "contenu;\n"
                . "}else{\n"
                . "    //Fonction longue à éxecuter\n"
                . "    echo ($" . "contenu = fonction_longue_a_executer()); \n"
                . "    //Stocke le résultat de la fonction longue en cache pour 5 minutes\n"
                . "    cache::set('ma_cle', $" . "contenu, 600);\n"
                . "}\n"
                . "//Supprimer une valeur dans le cache \n"
                . "cache::del('ma_cle');\n"
                . "//Supprimer toutes les valeurs dans le cache \n"
                . "cache::del();\n"
                . "?>", $this->_brush);
    }

    private function cards() {
        ?>
        <p>Cette classe permet de gérer un paquet de 32, 52, 54 ou 78 cartes</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Génère un paquet de 52 cartes (par defaut)\n"
                . "$" . "paquet = new cards();\n\n"
                . "//Génère un paquet de 32 cartes\n"
                . "$" . "paquet = new cards(32);\n\n"
                . "//Mélange le paquet\n"
                . "$" . "paquet->shuffle_deck();\n\n"
                . "//Tire une carte du paquet, la carte est retirée du paquet.\n"
                . "//retourne false si le paquet est vide\n"
                . "$" . "card=$" . "paquet->drow_from_deck();\n"
                . "?>", $this->_brush);
        ?> <p>Contenu d'un paquet de 52 cartes :</p><p><?php
            $deck = (new cards())->get_deck();
            sort($deck);
            foreach ($deck as $cards) {
                echo $cards . " ";
            }
            ?></p><p>Contenu d'un paquet de 78 cartes :</p><p><?php
            $deck = (new cards(78))->get_deck();
            sort($deck);
            foreach ($deck as $cards) {
                echo $cards . " ";
            }
            ?>
        </p>
        <?php
    }

    private function check_password() {
        ?>
        <p>Cette classe permet d'appliquer une politique de mots de passe</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "if (isset($" . "_POST[\"psw\"])) {\n\n"
                . "    // Vérifie le mot de passe avec la politique suivante ( par défaut) :\n"
                . "    // - Le mot de passe doit faire au minimum 8 caractères\n"
                . "    // - Contenir au moins un nombre, une majuscule et une minuscule\n"
                . "    // - L'utilisation de caractères spéciaux est ici facultatif\n"
                . "    $" . "check = new check_password($" . "_POST[\"psw\"], $" . "minlen = 8, $" . "special = false, $" . "number = true, $" . "upper = true, $" . "lower = true);\n\n"
                . "    // On vérifie si le mot de passe est conforme à la politique de mots de passe\n"
                . "    if ($" . "check->is_valid()) {\n"
                . "        //mot de passe ok \n"
                . "    } else {\n\n"
                . "        //si le mot de passe n'est pas conforme, on affiche un message d'erreur\n"
                . "        $" . "check->print_errormsg();\n"
                . "    }\n"
                . "}\n"
                . "form::new_form();\n"
                . "form::input(\"Mot de passe\", \"psw\", \"password\");\n"
                . "form::submit(\"btn-primary\");\n"
                . "form::close_form();\n"
                . "?>", $this->_brush);
        ?>
        <p>Démonstration du message d'erreur (avec au moins un caractère special obligatoire)</p>
        <?php
        (new check_password("", 8, true))->print_errormsg();
        ?>
        <p>Il est possible de modifier les messages avant d'appeler print_errormsg() :</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "check=new check_password('');\n"
                . "//Modifie le message d'erreur lié à la longueur du mot de passe\n"
                . "$" . "check->set_errormsg_minlen($" . "msg);\n\n"
                . "//Modifie le message d'erreur lié au manque de caractère special dans le mot de passe\n"
                . "$" . "check->set_errormsg_special($" . "msg);\n\n"
                . "//Modifie le message d'erreur lié au manque de chiffre dans le mot de passe\n"
                . "$" . "check->set_errormsg_number($" . "msg);\n\n"
                . "//Modifie le message d'erreur lié au manque de majuscule dans le mot de passe\n"
                . "$" . "check->set_errormsg_upper($" . "msg);\n\n"
                . "//Modifie le message d'erreur lié au manque de minuscule dans le mot de passe\n"
                . "$" . "check->set_errormsg_lower($" . "msg);\n\n"
                . "//Modifie le message \"Erreur ! votre mot de passe :\"\n"
                . "$" . "check->print_errormsg($" . "msg);\n"
                . "?>", $this->_brush);
    }

    private function citations() {
        ?>
        <p>Cette classe affiche une citation célèbre à chaque chargement de page. <br />
            les citations se trouvent dans <em>dwf/class/citations/citations.json</em> <br />
            Vos contributions sont les bienvenues.
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "new citations();\n"
                . "?>", $this->_brush);
        ?>Resultat :<?php
        new citations();
    }

    private function ckeditor() {
        ?>
        <p>Cette classe est exploitée par la classe form (cf. form)</p>
        <?php
    }

    private function cli() {
        ?>
        <p>Cette classe est utile pour les CLI (cf. CLI)</p>
        <?php
    }

    private function compact_css() {
        ?>
        <p>Compresse des scripts CSS en deux fichiers minifié</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//ajoute un fichier CSS\n"
                . "compact_css::get_instance()->add_css_file($" . "href);\n\n"
                . "//ajoute un script CSS (string ou CF \"CSS\")\n"
                . "compact_css::get_instance()->add_style($" . "style);\n\n"
                . "//fluent peut-etre utilisé :\n"
                . "compact_css::get_instance()->add_css_file($" . "href)\n"
                . "        ->add_style($" . "style)\n"
                . "?>", $this->_brush);
        ?>
        <p>La méthode <em>"render()"</em> est utilisée automatiquement dans <em>html5.class.php</em>, il n'est pas utile de l'appeler</p>
        <?php
    }

    private function cookieaccept() {
        ?>
        <p>Cette classe permet d'afficher un message d'informations sur l'utilisation de cookies ou autre technologies similaires. <br />
            Cette ligne est à placer dans <em>pages->header()</em>
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "new cookieaccept();\n"
                . "?>", $this->_brush);
    }

    private function css() {
        ?>
        <p>Cette classe permet de génerer des feuilles de style personalisée. <br />            
            A utiliser avec <em>"compact_src::get_instance()->add_style()"</em></p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Definit une regle CSS\n"
                . "((new css())->add_rule($" . "selector, $" . "rules));\n"
                . "//exemple (avec un echo. Fluent peut être utilisé)\n"
                . "echo (new css())->add_rule(\"p\", [\"padding\" => \"5px\"])\n"
                . "                ->add_rule(\"div\", [\n"
                . "                    \"padding\" => \"5px\",\n"
                . "                    \"margin\" => \"0 auto\"\n"
                . "                ])\n"
                . "                ->add_rule(\"#mon_id\", [\"background-color\" => \"lightblue\"]);\n"
                . "?>", $this->_brush);
        ?>
        <p>La feuille de style retournée sera optimisée et minifiée,<br />
            dans l'exemple nous voyons que "p" et "div" ont la même règle de padding, elles seront donc fusionnées. <br />
            Voici le resultat :</p>
        <?php
        debug::print_r((string) (new css())->add_rule("p", ["padding" => "5px"])
                        ->add_rule("div", [
                            "padding" => "5px",
                            "margin" => "0 auto"
                        ])
                        ->add_rule("#mon_id", ["background-color" => "lightblue"])
        );
    }

    private function cytoscape() {
        ?>
        <p>Il est préférable d'appeler cette classe via <em>(new graphique())->cytoscape()</em> (cf. graphique)
            Cette classe affiche un graphe d'analyse et de visualisation (utilise la librairie jquery cytoscape) <br />
            (Requiert une règle CSS sur l'ID CSS)
        </p>
        <?php
        js::syntaxhighlighter("<style type=\"text/css\">\n"
                . "    #cytoscape{\n"
                . "        width: 300px;\n"
                . "        height: 300px;\n"
                . "    }\n"
                . "</style>\n"
                . "<?php\n"
                . "(new graphique())->cytoscape(\"cytoscape\",array(\n"
                . "    'A'=>array('B','C'),\n"
                . "    'B'=>array('C'),\n"
                . "    'C'=>array('A')\n"
                . "));\n"
                . "?>", $this->_brush);
    }

    private function datatable() {
        ?>
        <p>Cette classe permet de convertir un tableau HTML en datatable (jquery) <br />
            il est préférable de l'appeler via <em>js::datatable()</em> (cf js)
        </p>
        <?php
    }

    private function ddg__ddg_api() {
        ?>
        <p>La classe ddg permet d'exploiter le moteur de recherche DuckDuckGO</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Affiche une searchbar de DuckDuckGO\n"
                . "(new ddg())->print_searchbar();\n\n"
                . "//retourne un objet ddg_api qui contient les résultats d'une recherche\n"
                . "$" . "ddg_api=(new ddg())->api('DuckDuckGO');\n"
                . "?>", $this->_brush);
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

    private function debug() {
        ?>
        <p>Cette classe est une boîte à outils de débogage</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Affiche la structure d'une variable ( optimisé pour les arrays et objets )\n"
                . "debug::print_r($" . "var);\n\n"
                . "//Affiche le contenu et le type d'une variable ( optimisé pour les type nombres, chaines de caractères et les booléans )\n"
                . "debug::var_dump($" . "var);\n\n"
                . "//Affiche la trace de l'application pour arriver au point de débug ( trace des fichiers et méthodes qui ont été appelés)\n"
                . "debug::get_trace();\n\n"
                . "//Affiche le rapport d'activités de PHP en bas de page\n"
                . "debug::show_report();\n"
                . "?>", $this->_brush);
    }

    private function dictionary() {
        ?>
        <p>Cette classe permet de convertir et gérer une liste lourde comme étant un dictionnaire</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//créé un dictionnaire\n"
                . "$" . "dictionnaire = new dictionary($" . "words = [\"All\", \"My\", \"Words\"], $" . "chunk_size = 100000);\n\n"
                . "//Ajoute des mots\n"
                . "$" . "dictionnaire->add([\"New\", \"Elements\"]);\n\n"
                . "//Vérifie si un mot existe\n"
                . "$" . "dictionnaire->word_exist(\"Words\");\n\n"
                . "//Supprime des mots\n"
                . "$" . "dictionnaire->remove([\"Words\", \"Elements\"]);\n\n"
                . "//Nombre de mots dans le dictionnaire\n"
                . "$" . "dictionnaire->count_words();\n\n"
                . "//Gestion de la taille des sections du dictionnaire\n"
                . "$" . "dictionnaire->set_chunk_size($" . "chunk_size = 100000);\n"
                . "$" . "dictionnaire->get_chunk_size();\n\n"
                . "//Accès au sections\n"
                . "$" . "dictionnaire->count_sections();\n"
                . "$" . "dictionnaire->get_section($" . "key = 0);\n"
                . "?>", $this->_brush);
    }

    private function dlc() {
        ?><p>Cette classe permet de générer des fichiers :</p><?php
        echo html_structures::ul([
            "DLC (Download Link Container, recommandé)",
            "CCF (CryptLoad Container File)",
            "RSDF (RapidShare Download File)"
        ]);
        ?><p>Servant de librairie de téléchargement pour des logiciels comme <?= html_structures::a_link("http://jdownloader.org", "JDownloader") ?></p><?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "data=[\n"
                . "    'http://url/image1.jpg',\n"
                . "    'http://url/image2.jpg',\n"
                . "    'http://url/image3.jpg'\n"
                . "];\n"
                . "//Génère un fichier DLC\n"
                . "dlc::generate_DLC('monDLC.dlc', $" . "data);\n\n"
                . "//Génère un fichier CCF\n"
                . "dlc::generate_CCF('monCCF.ccf', $" . "data);\n\n"
                . "//Génère un fichier RSDF\n"
                . "dlc::generate_RSDF('monRSDF.rsdf', $" . "data);\n"
                . "?>", $this->_brush);
    }

    private function dwf_exception() {
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
        js::syntaxhighlighter("<?php\n"
                . "$" . "code_erreur=700;\n"
                . "$" . "text_erreur=\"un message d'erreur\"\n"
                . "//Affiche une exception qui n'interrompt pas le script en cours\n"
                . "dwf_exception::warning_exception($" . "code_erreur,array(\"msg\"=>\"$" . "text_erreur\"));\n\n"
                . "//Lance une exception qui interrompt le script en cours\n"
                . "dwf_exception::throw_exception($" . "code_erreur,array(\"msg\"=>\"$" . "text_erreur\"));\n\n"
                . "//try catch pour gérer et afficher une exception\n"
                . "try {\n"
                . "    //conditions menant à une exception\n"
                . "} catch (Exception $" . "e) {\n"
                . "    dwf_exception::print_exception($" . "e);\n"
                . "}\n"
                . "?>", $this->_brush);
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

    private function easteregg() {
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
        js::syntaxhighlighter("<?php new easteregg(); ?>", $this->_brush);
    }

    private function entity_generator() {
        ?><p>(cf Entity)</p><?php
    }

    private function entity_model() {
        ?><p>Cette classe permet d'afficher des pseudos MCD à partir de vos entités</p><?php
        js::syntaxhighlighter("<?php\n"
                . "//Exemple avec la petite structure vue dans 'Entity'\n\n"
                . "//Affiche un pseudo MCD sous forme de tableau HTML\n"
                . "echo entity_model::table('user');\n\n"
                . "//Affiche un pseudo MCD sous forme de div HTML\n"
                . "echo entity_model::div('user');\n"
                . "?>", $this->_brush);
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
        <?php
    }

    private function event() {
        ?>
        <p>
            Cette classe permet de créer des événements (listener et emiter) <br />
            l'utilisation de cette classe différe des "méthodes événementielles" dans la mesure où ces dernières font appel à des methodes "static" d'autres classes.
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Définit un listener et une action associée\n"
                . "event::on('mon_listener',function(){\n"
                . "    //do somthings\n"
                . "}\n\n"
                . "//Ajoute une autre action au listener associée\n"
                . "event::on('mon_listener',function(){\n"
                . "    //do another somthings\n"
                . "}\n\n"
                . "//Déclenche l'événement (emiter)\n"
                . "event::run('mon_listener');\n\n"
                . "//Autre exemple avec des paramètres\n"
                . "event::on('alert.warning',function($" . "text){\n"
                . "    ?>\n"
                . "        <p class='alert alert-warning'>\n"
                . "            <?= $" . "text; ?>\n"
                . "        </p>\n"
                . "    <?php\n"
                . "}\n"
                . "event::run('alert.warning','Attention !');\n"
                . "?>", $this->_brush);
    }

    private function fancybox() {
        ?>
        <p>(cf js)</p>
        <?php
    }

    private function fb() {
        ?>
        <p>
            Cette classe permet de gérer une authentification via FaceBook. <br />
            Requiert la création d'une application sur https://developers.facebook.com/ <br />
            Exemple d'usage :
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Execution en cas de Logout\n"
                . "if(isset($" . "_GET['fb']) and $" . "_GET['fb']=='logout'){\n"
                . "    session_destroy();\n"
                . "    js::redir('index.php');\n"
                . "}\n\n"
                . "$" . "fb = new fb($" . "app_id, $" . "app_secret);\n"
                . "if ($" . "fb->getAccessToken_session()) {\n"
                . "    //Bouton logout\n"
                . "    echo html_structures::a_link($" . "fb->getLogoutUrl('http://mon-site/index.php?fb=logout'), 'logout');\n\n"
                . "    //Données de l'utilisateur FB\n"
                . "    debug::print_r($" . "fb->getGraphUser());\n\n"
                . "    //TODO : utilisez session::set_auth(true) et requetes SQL\n"
                . "} else {\n"
                . "    //Bouton login\n"
                . "    echo html_structures::a_link($" . "fb->getLoginUrl('http://mon-site/index.php'), 'login');\n"
                . "}\n"
                . "?>", $this->_brush);
    }

    private function file_explorer() {
        ?><p>Cette classe permet d'afficher et explorer une arborescence</p><?php
        js::syntaxhighlighter("<?php new file_explorer(\"./files\"); ?>", $this->_brush);
        ?> <p>Résultat :</p><?php
        new file_explorer("./files");
    }

    private function form() {
        ?>
        <p>La classe form permet de créer des formulaires en php stylisés par bootstrap, accessibles et respectant les normes W3C</p>
        <?php
        js::accordion("accordion_classes_natives_form", true, true);
        ?>
        <div id="accordion_classes_natives_form">
            <h5>Depuis la version 21.19.03</h5>
            <div>
                <p>Première méthode : création et rendu</p>
                <?php
                js::syntaxhighlighter("<?php\n"
                        . "//création du formulaire\n"
                        . "$" . "form = new form();\n"
                        . "$" . "form->input(\"Input de type text\", \"input_1\");\n"
                        . "$" . "form->input(\"Input de type password\", \"input_2\", \"password\");\n"
                        . "$" . "form->input(\"Input avec valeur initiale\", \"input_3\", \"text\", \"valeur initiale\");\n"
                        . "$" . "form->datepicker(\"Un datepicker\", \"datepicker_1\");\n"
                        . "$" . "form->select(\"Un selecteur\", \"select_1\", [\n"
                        . "    [1, \"Abricots\"],\n"
                        . "    [2, \"Poires\", true], //Poires est selectioné par défaut\n"
                        . "    [3, \"Pommes\"],\n"
                        . "]);\n"
                        . "$" . "form->textarea(\"Un textarea\", \"ta_1\");\n"
                        . "//création d\"un CKEditor\n"
                        . "$" . "form->textarea(\"Un ckeditor\", \"ta_2\");\n"
                        . "$" . "cke = js::ckeditor(\"ta_2\");\n"
                        . "//bouton de soumission\n"
                        . "$" . "form->submit(\"btn-primary\");\n"
                        . "//Rendu du formulaire\n"
                        . "echo $" . "form->render();\n"
                        . "//Exécution du formulaire\n"
                        . "if (isset($" . "_POST[\"input_1\"])) {\n"
                        . "\n"
                        . "    //Récupère la date du datepicker au format US\n"
                        . "    $" . "date = form::get_datepicker_us(\"datepicker_1\");\n"
                        . "    //Filtre les balises utilisées dans CKEditor, protection XSS\n"
                        . "    $" . "ta_2 = $" . "cke->parse($" . "_POST[\"ta_2\"]);\n"
                        . "\n"
                        . "    //Message de succès ou erreur\n"
                        . "    js::alert(\"le formulaire a bien été soumis\");\n"
                        . "    //Redirection vers la page courante = rafraichissement de la page\n"
                        . "    js::redir(\"\");\n"
                        . "}\n?>", $this->_brush);
                ?>
                <p>Seconde méthode : affichage direct</p>
                <?php
                js::syntaxhighlighter("<?php\n"
                        . "//création du formulaire\n"
                        . "$" . "form = new form();\n"
                        . "//affichage de la balise d'ouverture\n"
                        . "echo $" . "form->get_open_form();\n"
                        . "echo $" . "form->input(\"Input de type text\", \"input_1\");\n"
                        . "echo $" . "form->input(\"Input de type password\", \"input_2\", \"password\");\n"
                        . "echo $" . "form->input(\"Input avec valeur initiale\", \"input_3\", \"text\", \"valeur initiale\");\n"
                        . "echo $" . "form->datepicker(\"Un datepicker\", \"datepicker_1\");\n"
                        . "echo $" . "form->select(\"Un selecteur\", \"select_1\", [\n"
                        . "    [1, \"Abricots\"],\n"
                        . "    [2, \"Poires\", true], //Poires est selectioné par défaut\n"
                        . "    [3, \"Pommes\"],\n"
                        . "]);\n"
                        . "echo $" . "form->textarea(\"Un textarea\", \"ta_1\");\n"
                        . "//création d\"un CKEditor\n"
                        . "echo $" . "form->textarea(\"Un ckeditor\", \"ta_2\");\n"
                        . "$" . "cke = js::ckeditor(\"ta_2\");\n"
                        . "//bouton de soumission\n"
                        . "echo $" . "form->submit(\"btn-primary\");\n"
                        . "//affichage de la balise de fermeture\n"
                        . "echo $" . "form->get_close_form();\n"
                        . "//execution du formulaire\n"
                        . "if (isset($" . "_POST[\"input_1\"])) {\n"
                        . "\n"
                        . "    //récupère la date du datepicker au format US\n"
                        . "    $" . "date = form::get_datepicker_us(\"datepicker_1\");\n"
                        . "    //filtre les balises utilisées dans CKEditor, protection XSS\n"
                        . "    $" . "ta_2 = $" . "cke->parse($" . "_POST[\"ta_2\"]);\n"
                        . "\n"
                        . "    //message de succès ou erreur\n"
                        . "    js::alert(\"le formulaire a bien été soumis\");\n"
                        . "    //redirection vers la page courante = rafraichissement de la page\n"
                        . "    js::redir(\"\");\n"
                        . "}\n?>", $this->_brush);
                ?>

            </div>
            <h5>Avant la version 21.19.03</h5>
            <div>
                <?php
                js::syntaxhighlighter("<?php\n"
                        . "//création du formulaire\n"
                        . "form::new_form();\n"
                        . "form::input(\"Input de type text\", \"input_1\");\n"
                        . "form::input(\"Input de type password\", \"input_2\", \"password\");\n"
                        . "form::input(\"Input avec valeur initiale\", \"input_3\", \"text\", \"valeur initiale\");\n"
                        . "form::datepicker(\"Un datepicker\", \"datepicker_1\");\n"
                        . "form::select(\"Un selecteur\", \"select_1\", array(\n"
                        . "    array(1, \"Abricots\"),\n"
                        . "    array(2, \"Poires\", true), //Poires est selectioné par defaut\n"
                        . "    array(3, \"Pommes\"),\n"
                        . "));\n"
                        . "form::textarea(\"Un textarea\", \"ta_1\");\n"
                        . "//création d\"un CKEditor\n"
                        . "form::textarea(\"Un ckeditor\", \"ta_2\");\n"
                        . "$" . "cke = js::ckeditor(\"ta_2\");\n"
                        . "\n"
                        . "//bouton de soumission\n"
                        . "form::submit(\"btn-primary\");\n"
                        . "//fermeture du formulaire\n"
                        . "form::close_form();\n"
                        . "\n"
                        . "//exécution du formulaire\n"
                        . "if (isset($" . "_POST[\"input_1\"])) {\n"
                        . "\n"
                        . "    //récupère la date du datepicker au format US\n"
                        . "    $" . "date = form::get_datepicker_us(\"datepicker_1\");\n"
                        . "    //filtre les balises utilisées dans CKEditor, protection XSS\n"
                        . "    $" . "ta_2 = $" . "cke->parse($" . "_POST[\"ta_2\"]);\n"
                        . "\n"
                        . "    //message de succès ou erreur\n"
                        . "    js::alert(\"le formulaire a bien été soumis\");\n"
                        . "    //redirection vers la page courante = rafraichissement de la page\n"
                        . "    js::redir(\"\");\n"
                        . "}\n?>", $this->_brush);
                ?>
            </div>
        </div>
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
                $form->textarea("Un ckeditor", "ta_2");
                $cke = js::ckeditor("ta_2");
                $form->submit("btn-primary");
                echo $form->render();
                ?>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <?php
    }

    private function freetile() {
        ?>
        <p>Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "freetile". <br />
            Il est préferable d'appeler cette librairie via <em>js::freetile()</em> <br />
            Les sous éléments peuvent être des images ou des DIV de différentes tailles.
        </p>
        <?php
        js::syntaxhighlighter("<?php js::freetile('freetile'); ?>\n"
                . "<div id=\"freetile\"></div>", $this->_brush);
    }

    private function ftp_explorer() {
        ?> 
        <p>Cette classe permet d'afficher et explorer une arborescence FTP <strong>PUBLIQUE</strong> <br />
            le compte FTP renseigné dans cette classe ne doit avoir que des droits de consultation ( aucun droit d'ajout/modification/suppression) <br />
            le rendu est le même que <em>file_explorer()</em>
        </p>
        <?php
        js::syntaxhighlighter("<?php new ftp_explorer($" . "host, $" . "user, $" . "psw, $" . "ssl=false); ?>", $this->_brush);
    }

    private function fullcalendar() {
        ?>
        <p>Cette classe permet d'afficher un Fullcalendar <br />
            <a href="https://fullcalendar.io/">https://fullcalendar.io/</a>
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Affiche l'administration du Fullcalendar\n"
                . "fullcalendar::admin();\n\n"
                . "//Affiche le fullcalendar avec les évènements enregistrés en base de données\n"
                . "new fullcalendar($" . "id=\"fullcanlendar\", fullcalendar_event::get_table_array());\n\n"
                . "//Affiche le fullcalendar avec l'agenda d'un compte Google\n"
                . "new fullcalendar($" . "id=\"fullcanlendar\", null, $" . "api_key, 'abcd1234@group.calendar.google.com');\n"
                . "?>", $this->_brush);
        ?><p>Exemple</p><?php
        new fullcalendar("fullcalendar", [
            [
                "title" => "évènement de démonstation",
                "start" => date("Y-m-d") . " " . date("H:i:s"),
                "end" => date("Y-m-d") . " " . (date("H") + 1) . date(":i:s")
            ]
        ]);
    }

    private function g_agenda() {
        ?>
        <p>Cette classe permet de gérer un agenda d'évènements minimalistes, <br />
            souvent utilisé pour avertir des visiteurs des prochains évenements<br />
            (salons, conventions, spectacles, ...)</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Affiche l'interface d'administration\n"
                . "(new g_agenda())->admin();\n\n"
                . "//Affiche la liste d'évènements prévus (les 10 prochains par défaut)\n"
                . "(new g_agenda())->agenda_page($" . "lim = 10);\n"
                . "?>", $this->_brush);
    }

    private function g_elFinder() {
        ?>
        <p>Cette classe affiche le gestionnaire de fichiers elFinder <br />
            Il n'est pas recommandé de mettre deux instances de cette classe dans une même page<br />
            Pour autoriser un utilisateur à utiliser elFinder, vous devrez utiliser session::set_val("elFinder", true);<br />
            Cf : le fichier connector.php (généré par cette classe) pour plus de détails et options)
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Sécurité via la varible de session\n"
                . "session::set_val('elFinder', true);\n\n"
                . "//Affiche le gestionnaire de fichiers elFinder\n"
                . "new g_elFinder();\n"
                . "?>", $this->_brush);
    }

    private function gestion_article() {
        ?>
        <p>Cette classe permet de gérer et afficher des articles</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Affiche l'administration des articles\n"
                . "(new gestion_article())->admin();\n\n"
                . "//Affiche le parcours des articles (pour les utilisateurs)\n"
                . "(new gestion_article())->article();\n\n"
                . "//Affiche un module avec les dernières actualités\n"
                . "//Plusieurs modules peuvent être créés, chaque module est identifié par un nom,\n"
                . "//possède une limite d'affichage et sont liés à une ou plusieurs catégories d'articles\n"
                . "(new gestion_article())->module($" . "name='default')\n"
                . "?>", $this->_brush);
    }

    private function git() {
        ?>
        <p>Utilisez git depuis PHP (requiert git sur le serveur) <a href="https://github.com/kbjr/Git.php">https://github.com/kbjr/Git.php</a></p>
        <?php
    }

    private function graphique() {
        ?>
        <p>Cette classe permet de créér des graphiques.</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "data = [\n"
                . "    [\n"
                . "        \"label\" => \"Nombres heureux\",\n"
                . "        \"data\" => [[1, 1],[2, 7],[3, 10],[4, 13],[5, 19]]\n"
                . "    ],\n"
                . "    [\n"
                . "        \"label\" => \"Nombres premiers\",\n"
                . "        \"data\" => [[1, 2],[2, 3],[3, 5],[4, 7],[5, 11]]\n"
                . "    ]\n"
                . "];\n"
                . "\n//Affiche un graphique en ligne/courbe\n"
                . "(new graphique(\"graph1\", $" . "size = [\"width\" => \"600px\", \"height\" => \"300px\"]))->line($" . "data, $" . "ticks = [], $" . "show_points = true, $" . "fill = false);\n"
                . "\n//Affiche un graphique en points\n"
                . "(new graphique(\"graph2\")->points($" . "data);\n"
                . "\n//Affiche un graphique en bars\n"
                . "(new graphique(\"graph3\")->bars($" . "data);\n\n"
                . "$" . "data = [\n"
                . "    [\n"
                . "        \"label\" => \"Allemagne\",\n"
                . "        \"data\" => 3466.76\n"
                . "    ],\n"
                . "    [\n"
                . "        \"label\" => \"Royaume uni\",\n"
                . "        \"data\" => 2618.89\n"
                . "    ],\n"
                . "    [\n"
                . "        \"label\" => \"France\",\n"
                . "        \"data\" => 2465.45\n"
                . "    ]\n"
                . "];"
                . "\n//Affiche un graphique en \"camembert\"\n"
                . "(new graphique(\"graph4\")->pie($" . "data);\n"
                . "\n//Affiche un graphique en anneau\n"
                . "(new graphique(\"graph5\")->ring($" . "data);\n"
                . "?>", $this->_brush);
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

    private function html5() {
        ?>
        <p>
            Cette classe gère l'en-tête HTML5 et son pied de page. <br />
            Cette classe est utilisée automatiquement par le framework dans <em>application.class.php</em> <br />
            Les balises : title, meta description et meta keywords peuvent être modifiées grâce aux fonction suivantes :

        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Ajoute un préfixe au titre de la page en cours\n"
                . "html5::before_title($" . "text);\n"
                . "//Définit la description de la page en cours\n"
                . "html5::set_description($" . "description);\n"
                . "//Défini les mots clé de la page en cours\n"
                . "html5::set_keywords($" . "keywords);\n"
                . "//Ajoute des mots clé de la page en cours\n"
                . "html5::add_keywords($" . "keywords);\n"
                . "?>", $this->_brush);
    }

    private function html_structures() {
        ?>
        <p>html_structures est une classe qui permet de créer des structures HTML en PHP,<br />
            Ces structures respectent les normes W3C et répondent à quelques normes d'accessibilité <br />
            Exemple :
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//affiche une liste de deux liens\n"
                . "//le premier affiche le glyphicon 'home' et renvoie vers l'accueil\n"
                . "//le second affiche le glyphicon 'search' et ouvre un onglet vers DuckDuckGo\n"
                . "echo html_structures::ul(array(\n"
                . "    html_structures::a_link('index.php', html_structures::glyphicon('home','') . ' Retour à l\'accueil'),\n"
                . "    html_structures::a_link('https://duckduckgo.com/', html_structures::glyphicon('search','') . ' Rechercher sur le web','','(nouvel onglet)', true),\n"
                . "));\n"
                . "?>", $this->_brush);
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
        <p>Methodes (toutes static) :</p>
        <?php
        echo html_structures::table(["Methode", "Description"], [
            ["table", "Retourne un tableau à partir d'un array d'entête et d'un array à deux dimensions comprenant les données"],
            ["ul, ol, dl", "Retourne une liste au format HTML à partir d'un array ( prend en compte l'imbrication des array)"],
            ["a_link", "Retourne un lien"],
            ["Ancre", "Retourne une ancre a"],
            ["img", "Retourne une image img"],
            ["figure", "Retourne une figure ( illustration + légende )"],
            ["new_map, area et close_map", "Mapping d'image"],
            ["media", "Retourne les données passées en paramètres sous forme de média (bootstrap)"],
            ["glyphicon", "Retourne un glyphicon (avec un texte alternative)"],
            ["hr", "Retourne un séparateur horizontal"],
            ["time", "La balise time permet d'afficher une date avec une valeur SEO sémantique"],
            ["link_in_body", "Permet de faire appel à une balise LINK dans le body"],
            ["script_in_body", "Permet de faire appel à une balise SCRIPT dans le body"],
            ["script et link", "Sont utilisé par le framework (dans html5.class.php)"],
            ["popover", "Permet d'afficher un lien avec un popover"],
            ["parallax", "Permet d'afficher une DIV qui aurra un effet de parallax"]
        ]);
    }

    private function ip_access() {
        ?>
        <p>Cette classe sert à blacklister une liste de plages d'adresses IP en les redirigeant vers un autre site</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//exemple : on bloque tout les access depuis les ip de localhost et on redirige vers DuckDuckGO\n"
                . "new ip_access(array(array('127.0.0.0', '127.255.255.255')), 'http://duckduckgo.com');\n"
                . "?>", $this->_brush);
    }

    private function js() {
        new docPHP_natives_js();
    }

    private function leaflet() {
        ?>
        <p>Cette classe permet d'afficher une carte exploitant OSM (OpenStreetMap)</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Initialise le Leaflet\n"
                . "$" . "leaflet=new leaflet(array(\"x\" => 48.85341, \"y\" => 2.3488, \"zoom\" => 13));\n"
                . "//Ajoute des marqueurs\n"
                . "$" . "leaflet->add_marker(48.85341, 2.3488, 'Paris');\n"
                . "$" . "leaflet->add_marker(51.50853,  -0.12574, 'Londres');\n"
                . "$" . "leaflet->add_marker(50.85045, 4.34878, 'Bruxelles');\n"
                . "//Ajoute un cercle autour d'un point\n"
                . "$" . "leaflet->add_circle(50.85045, 4.34878, 100000, 'Belgique');\n"
                . "//Ajoute un polygone sur la carte\n"
                . "$" . "leaflet->add_polygon(array(\n"
                . "    array('x'=>'50.9519','y'=>'1.8689'),\n"
                . "    array('x'=>'48.582325','y'=>'7.750871'),\n"
                . "    array('x'=>'43.774483','y'=>'7.497540'),\n"
                . "    array('x'=>' 43,3885129','y'=>'-1,6596374'),\n"
                . "    array('x'=>'48,3905283','y'=>'-4,4860088'),\n"
                . "), 'Hexagone');\n"
                . "//Affiche la carte\n"
                . "$" . "leaflet->print_map();\n"
                . "//Trace l'itinéraire sans activer la position de l'utilisateur\n"
                . "$" . "leaflet->tracer_itineraire($" . "add_client_marker=false);\n"
                . "//Trace l'itinéraire en ajoutant la position de l'utilisateur par géolocalisation (droits demandés par le navigateur)\n"
                . "$" . "leaflet->tracer_itineraire(true);\n"
                . "?>", $this->_brush);
    }

    private function log_file() {
        ?>
        <p>Cette classe permet de créer un log sous forme de fichiers. <br />
            Elle vous permet d'enregistrer les comportements anormaux de votre application <br />
            Selon vos paramètres, le log est écrit dans <em>dwf/log/log_[votre_projet]_[date_format_us].txt</em> ou <em>dwf/log/log_[votre_projet].txt</em>
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//instencie l'objet de log\n"
                . "$" . "log=new log_file($" . "a_log_a_day=false);\n"
                . "//inscrits un message d'informations dans le log\n"
                . "$" . "log->info($" . "message);\n"
                . "//inscrit un message d'alerte dans le log\n"
                . "$" . "log->warning($" . "message);\n"
                . "//inscrit un message grave dans le log\n"
                . "$" . "log->severe($" . "message);\n"
                . "?>", $this->_brush);
    }

    private function log_mail() {
        ?>
        <p>Cette classe permet de vous envoyer automatiquement un mail en cas de comportement anormal de votre application</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//instencie l'objet de log\n"
                . "$" . "log=new log_mail($" . "from, $" . "to);\n"
                . "//envoie un mail d'informations\n"
                . "$" . "log->info($" . "message);\n"
                . "//envoie un mail d'alerte\n"
                . "$" . "log->warning($" . "message);\n"
                . "//envoie un mail grave\n"
                . "$" . "log->severe($" . "message);\n"
                . "?>", $this->_brush);
    }

    private function lorem_ipsum() {
        ?>
        <p>Cette classe permet de générer un faux texte (Lorem ipsum) <br />
            Le texte est généré depuis le vocabulaire du texte de Cicero : De finibus. 
            <br />Sources : <br />
            <a href="http://www.thelatinlibrary.com/cicero/fin1.shtml">Liber Primus</a> <br />
            <a href="http://www.thelatinlibrary.com/cicero/fin.shtml">Oeuvre compléte</a></p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Lorem Ipsum de 100 mots ne commencant pas par \"Lorem ipsum ...\"\n"
                . "echo lorem_ipsum::generate(100);\n\n"
                . "//Lorem Ipsum de 100 mots commencant par \n"
                . "//\"Lorem ipsum dolor sit amet, consectetur adipiscing elit.\"\n"
                . "echo lorem_ipsum::generate(100, true);\n\n"
                . "//Lorem Ipsum de 100 mots commencant par \n"
                . "//\"Lorem ipsum dolor sit amet, consectetur adipiscing elit.\"\n"
                . "//et utilisant le vocalulaire de toute l'oeuvre\n"
                . "//(10035 mots au lieu des 2732 mots du Liber Primus)\n"
                . "echo lorem_ipsum::generate(100, true, true);\n"
                . " ?>", $this->_brush);
        ?>
        <p>Resultat (<em>lorem_ipsum::generate(100, true)</em>) : </p>
        <p>
            <?php
            echo lorem_ipsum::generate(100, true);
            ?>
        </p>
        <?php
    }

    private function mail() {
        ?>
        <p>Cette classe vous permet d'envoyer un mail depuis votre application en une ligne de code (utilise les paramètres SMTP de <em>config.class.php</em>)</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "from='your.mail@your-smtp.com';\n"
                . "$" . "from_name='Your Name';\n"
                . "$" . "to='target@mail.com';\n"
                . "$" . "subject='The Subject';\n"
                . "$" . "msg='Hello World';\n"
                . "(new mail())->send($" . "from, $" . "from_name, $" . "to, $" . "subject, $" . "msg);\n"
                . "?>", $this->_brush);
    }

    private function maskNumber() {
        ?>
        <p>Cette classe permet de formater l'affichage d'un nombre dans un INPUT de type text</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "maskNumber::set(\"masknumber\");\n"
                . "$" . "form=new form();\n"
                . "$" . "form->input(\"Nombre\", \"masknumber\");\n"
                . "$" . "form->submit(\"btn-primary\");\n"
                . "echo $" . "form->render();\n"
                . "if(isset($" . "_POST[\"masknumber\"])){\n"
                . "    maskNumber::get(\"masknumber\"); //converti les saisis dans $" . "_POST\n"
                . "}\n"
                . "?>", $this->_brush);
        ?>
        <p>Attention ! maskNumber::set() doit être executé avant l'execution du formulaire ! <br />
            Resultat :</p>
        <?php
        maskNumber::set("masknumber");
        $form = new form();
        $form->input("Nombre", "masknumber");
        $form->submit("btn-primary");
        echo $form->render();
    }

    private function math() {
        ?>
        <p>Cette classe contient quelques méthodes statiques mathématiques de base ainsi que des fonctions pour vérifier le type de variables</p>
        <?php
    }

    private function messageries() {
        ?>
        <p>Cette classe gère la messagerie, elle permet d'envoyer, de réceptionner ou de supprimer un message entre les utilisateurs de l'application.</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Purge les messages de plus de 2 ans\n"
                . "messagerie::purge_msg($" . "table_msg, $" . "years = 2);\n"
                . "//créé une interface de messagerie pour les utilisateurs\n"
                . "new messagerie($" . "table_user, $" . "tuple_user);\n"
                . "?>", $this->_brush);
    }

    private function modal() {
        ?>
        <p>"Modal" est une classe permettant d'afficher des modals (appelés aussi layout ou pop-in). Une "modal" s'ouvre lors d'un clic sur un lien lui correspondant</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "a_text='Cliquez ici pour ouvrir la modal';\n"
                . "$" . "id='demo_modal';\n"
                . "$" . "title='(pop-up)';\n"
                . "$" . "titre='Démonstartion';\n"
                . "$" . "data='<p class=\"text-center\">Bienvenue sur la démonstration de modals</p>';\n"
                . "$" . "class='';\n"
                . "echo (new modal())->link_open_modal($" . "a_text, $" . "id, $" . "title, $" . "titre, $" . "data, $" . "class);\n"
                . "?>", $this->_brush);
        ?>
        <p>Résultat :</p>
        <?php
        echo (new modal())->link_open_modal("Cliquez ici pour ouvrir la modal", "demo_modal", "(pop-up)", "Démonstartion", "<p class=\"text-center\">Bienvenue sur la démonstration de modal</p>", "");
    }

    private function openweather() {
        ?>
        <p>Cette classe permet d'afficher la météo d'une ville en temps réel (utilise openweather et nécessite une clé API)</p>
        <?php
        js::syntaxhighlighter("<?php new openweather($" . "api_key, $" . "city); ?>", $this->_brush);
        ?>
        <p>Le resultat est le suivant (exemple fictif) :</p>        
        <div class = "openwether">
            <p>
                <span class = "dt">Météo de [$city] (<?php date("d/m H:i"); ?>)</span><br>
                <span class = "weather"><span><img src = "../commun/openweather/01d.png" alt = ""><span>
                        </span>Ciel clair</span></span><br><span class = "temp">Température : 19°C</span><br>
                <span class = "pressure">Pression : 1016 Hpa</span><br>
                <span class = "humidity">Humidité : 51%</span><br>
                <span class = "wind">Vent : 10km/h, 120.0° (Sud-Est)</span><br>
            </p>
        </div>
        <?php
    }

    private function pagination() {
        ?>
        <p>Cette classe gère la pagination dans une page</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "get = 'p';\n"
                . "$" . "per_page = 100;\n"
                . "$" . "count_all = mon_entite::get_count();\n"
                . "$" . "pagination = pagination::get_limits($" . "get, $" . "per_page, $" . "count_all);\n"
                . "foreach (mon_entite::get_table_array('1=1 limit ' . $" . "pagination[0] . ',' . $" . "pagination[1] . ';') as $" . "value) {\n"
                . "    //affichage des informations\n"
                . "}\n"
                . "pagination::print_pagination($" . "get, $" . "pagination[3]);\n"
                . "?>", $this->_brush);
    }

    private function paypal() {
        ?>
        <p>Cette classe permet de créer, verifier et executer des payment via l'API REST de PayPal</p>
        <p>Exemple d'utilisation :</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//liste des produit que veux acheter l'utilisateur\n"
                . "$" . "item_list = [\n"
                . "    [\n"
                . "        'Name' => 'produit1',\n"
                . "        'Price' => 10.50,\n"
                . "        'Quantity' => 1\n"
                . "    ],\n"
                . "    [\n"
                . "        'Name' => 'produit2',\n"
                . "        'Price' => 1.99,\n"
                . "        'Quantity' => 5\n"
                . "    ]\n"
                . "];\n\n"
                . "//Id et Secret renseigné dans l'application de l'API REST de PayPal\n"
                . "$" . "clientId='Votre-clientId';\n"
                . "$" . "clientSecret='Votre-clientSecret';\n"
                . "$" . "paypal = new paypal($" . "clientId, $" . "clientSecret);\n\n"
                . "if (!isset($" . "_GET['paypal_action'])) {\n"
                . "    $" . "_GET['paypal_action'] = '';\n"
                . "}\n"
                . "switch ($" . "_GET['paypal_action']) {\n"
                . "    case 'return':\n"
                . "        $" . "payment = $" . "paypal->get_payment($" . "_GET['paymentId']);\n"
                . "        //TODO: verifier les données du payment\n"
                . "        //Execute le payment\n"
                . "        $" . "paypal->execute_payment($" . "payment);\n"
                . "        //TODO : envoyer une copie de la facture par mail\n"
                . "        js::alertify_alert_redir('Payment accepté! retour a l\'accueil', 'index.php');\n"
                . "        break;\n"
                . "    case 'cancel':\n"
                . "        js::alertify_alert_redir('Vous avez annulé le payment, retour a l\'accueil', 'index.php');\n"
                . "        break;\n"
                . "    default:\n"
                . "        $" . "url = 'http://monsite.fr/' . strtr(application::get_url(['paypal_action']), ['&amp;' => '&']);\n"
                . "        //créé le payment et retourne le lien de payment pour l'utilisateur ou false en cas d'erreur\n"
                . "        if ($" . "link = $" . "paypal->create_payment(\n"
                . "                $" . "item_list, 20, 'Ventes de monsite.fr', $" . "url . 'paypal_action = return', $" . "url . 'paypal_action = cancel'\n"
                . "                )) {\n"
                . "            //affiche le lien pour le payment ( à fournir à l'utilisateur)\n"
                . "            //A adapter si vous voulez afficher un bouton PayPal \"officiel\"\n"
                . "            echo html_structures::a_link($" . "link, 'Payer');\n"
                . "        }\n"
                . "        break;\n"
                . "}\n"
                . "?>", $this->_brush);
        ?>
        <p>Plus de renseignements dans la doc technique et sur <a href="https://developer.paypal.com" target="_blank">PayPal Developer</a></p>
        <?php
    }

    private function phpQRCode__printcsv__printer() {
        ?>
        <p>Ces classes servent à afficher ou exporter des données en QRCode CSV ou PDF, ils utilisent les fichiers :</p>
        <ul>
            <li>html/commun/qrcode.php</li>
            <li>html/commun/csv.php</li>
            <li>html/commun/printer.php</li>
        </ul>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Affiche le qrcode contenant le texte ou l'URL passé en paramètre\n"
                . "phpQRCode::print_img('texte ou url');\n\n"
                . "//Affiche le qrcode contenant l'URL de la page courante, utilise un modal !\n"
                . "//utile pour les utilisateurs qui désirent continuer une lecture sur leur smartphone\n"
                . "phpQRCode::this_page_to_qr();\n\n"
                . "//Affiche un lien qui ouvre un onglet vers un export CSV des données passées en paramètre\n"
                . "new printcsv($" . "data);\n\n"
                . "//Affiche un bouton qui ouvre un onglet vers un export PDF\n"
                . "$" . "pdf=new printer($" . "lib); //$" . "lib = html2pdf ou dompdf\n"
                . "$" . "pdf->add_content($" . "content); //contenu HTML\n"
                . "$" . "pdf->print_buton($" . "filename='printer.pdf'); //affiche le bouton(formulaire) et defini le nom du fichier\n"
                . "?>", $this->_brush);
    }

    private function php_finediff() {
        ?>
        <p>Permets d'afficher les différences entre deux chaines de caractères</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . 'echo php_finediff::DiffToHTML("Texte de départ", "Texte final");'
                . "\n?>", $this->_brush);
        debug::print_r(php_finediff::DiffToHTML("Texte de départ", "Texte final"));
    }

    private function php_header() {
        ?>
        <p>Cette classe permet de modifier le header HTTP</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "// Renseigne le type (mime) du document. Renseignez juste l'extention du fichier ( par exemple \"json\" ou \"csv\"),\n"
                . "// la fonction sera retrouvée le mime corespondant.\n"
                . "(new php_header())->content_type($" . "type, $" . "force_upload_file=false);\n"
                . "// Redirige l'utilisateur (immédiatement ou avec un délai)\n"
                . "(new php_header())->redir($" . "url, $" . "second=0);\n"
                . "// Définit le statut code de la page, si le statut code est invalide, le code 200 est utilisé par défaut\n"
                . "(new php_header())->status_code($" . "code);\n"
                . "?>", $this->_brush);
    }

    private function phpini() {
        ?>
        <p>
            Cette classe permet de gèrer des profils de configuration PHP (comme avoir plusieurs fichier php.ini interchangeable à volonté)            
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "// Affiche l'interface pour créer vos propres profils de configuration.\n"
                . "// Est accèssible de base par le fichier html/index.php -> PHPini\n"
                . "phpini::admin();\n\n"
                . "//Charge un profil standard\n"
                . "phpini::set_mode(phpini::MODE_DEFAULT);\n\n"
                . "//Les diferants profils standard sont :\n"
                . "//Congiguration par défaut tel que décrite dans la doc officiel de php.ini\n"
                . "phpini::MODE_DEFAULT;\n\n"
                . "//Congiguration de développement tel que décrite dans la doc officiel de php.ini\n"
                . "phpini::MODE_DEV;\n\n"
                . "//Congiguration de production tel que décrite dans la doc officiel de php.ini\n"
                . "phpini::MODE_PROD;\n\n"
                . "//Congiguration de développement conseillé pour DWF\n"
                . "phpini::MODE_DWF_DEV;\n\n"
                . "//Congiguration de production conseillé pour DWF\n"
                . "phpini::MODE_DWF_PROD;\n\n"
                . "//Charge un profi de configuration de votre création\n"
                . "phpini::set_mode(phpini::MODE_CUSTOM,'mon_profil');\n\n"
                . "?>", $this->_brush);
    }

    private function pseudo_cron() {
        ?>
        <p>Cette classe permet de lancer des "pseudo cron", <br />
            contairement à des vrais cron qui s'executent a des heures fixes planifié par le sistème, <br />
            ici les pseudo cron s'executent : lors d'une activité utilisateur et si il n'a pas été executé depuis un certain temps définis</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "// Instanciation du sistème de pseudo cron en utilisant un registre json (ou SQL par defaut)\n"
                . "//pseudo_cron est un singleton\n"
                . "$" . "pcron = pseudo_cron::get_instance('json');\n\n"
                . "// Execute la fonction lors d'une activité utilisateur \n"
                . "// et si la fonction n'a pas été appelé au cours des dernières 24 heurs (86400 secondes) \n"
                . "$" . "nom = 'world';\n"
                . "$" . "pcron->fn(86400, function($" . "nom){\n"
                . "    echo 'hello '.$" . "nom;\n"
                . "},[$" . "nom]);\n\n"
                . "// Ou même fonction en utilisant le \"use\" \n"
                . "$" . "nom = 'world';\n"
                . "$" . "pcron->fn(86400, function() use ($" . "nom){\n"
                . "    echo 'hello '.$" . "nom;\n"
                . "});\n\n"
                . "// si la fonction retourne un resultat il peut être récupéré,\n"
                . "// si la fonction n'est pas executé fn() retourne null\n"
                . "$" . "result = $" . "pcron->fn(86400, function(){return 'hello world';});\n"
                . "if($" . "result !== null){\n"
                . "    echo $" . "result;\n"
                . "}\n\n"
                . "// Execute un fichier (toujours dans les mêmes conditions)\n"
                . "// le fichier est executé dans une console !\n"
                . "$" . "pcron->file(86400,'mon_chemain/mon_script.php');\n"
                . "?>", $this->_brush);
        ?>
        <p>L'intéret des cron étant de pouvoir lancer des operations lourdes a un rithme régulié sans ralentir l'utilisateur <br />
            il est possible de lancer un pseudo cron de façon "asynchrone" en utilisant un service et la methode service::HTTP_POST().
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "pseudo_cron::get_instance()->fn(86400,function(){\n"
                . "    service::HTTP_POST(\"http://localhost/mon_projet/services/index.php\", [\"service\"=>\"mon_service\"]);\n"
                . "});\n"
                . "?>", $this->_brush);
        ?>
        <p>Les pseudo cron sont renseigné dans un registe ( soit un fichier json soit une table en base de donnée ) <br />
            une entré est supprimé si elle n'est pas mise a jour (executé) pendant 1 an, cette durée peut être modifié via la methode</p>
        <?php
        js::syntaxhighlighter("<?php pseudo_cron::get_instance()->set_clear(31536000); ?>", $this->_brush);
    }

    private function ratioblocks() {
        ?>
        <p>Cette classe permet d'afficher un bloc css avec les proportions passées en paramètres.</p>
        <?php
        js::syntaxhighlighter("<?php new ratioblocks($" . "id, $" . "width, $" . "ratio, $" . "contenu); ?>", $this->_brush);
    }

    private function reveal() {
        ?>
        <p>
            Cette classe permet de créer un diaporama avec la librairie reveal <br />
            Il n'est pas recommandé d'avoir plusieurs diaporamas sur la même page !
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "reveal = new reveal($" . "width = 600, $" . "height = 600, $" . "theme = 'white');\n"
                . "$" . "reveal->start_reveal();\n"
                . "?>\n"
                . "<section><p style=\"font-size: 48px;\">Ceci est un Reveal</p></section>\n"
                . "<section>\n"
                . "    <section><p style=\"font-size: 48px;\">Reveal est une librairie JS qui permet de faire des présentations en HTML</p></section>\n"
                . "    <section><p style=\"font-size: 48px;\">Le PowerPoint est mort, vive le Reveal !</p></section>\n"
                . "</section>\n"
                . "<section><p style=\"font-size: 48px;\">Site officiel de Reveal : <br /><a href=\"http://lab.hakim.se/reveal-js/\">http://lab.hakim.se/reveal-js/</a></p></section>\n"
                . "<?php\n"
                . "$" . "reveal->close_reveal();\n"
                . "?>", $this->_brush);
        $reveal = new reveal($width = 600, $height = 600, $theme = 'white');
        $reveal->start_reveal();
        ?>
        <section><p style="font-size: 48px;">Ceci est un Reveal</p></section>
        <section>
            <section><p style="font-size: 48px;">Reveal est une librairie JS qui permet de faire des présentations en HTML</p></section>
            <section><p style="font-size: 48px;">Le PowerPoint est mort, vive le Reveal !</p></section>
        </section>
        <section>
            <p style="font-size: 48px;">Site officiel de Reveal : <br /><a href="http://lab.hakim.se/reveal-js/">http://lab.hakim.se/reveal-js/</a></p>
        </section>
        <?php
        $reveal->close_reveal();
    }

    private function reversoLib() {
        ?>
        <p>Cette classe utilise l'API de Reverso pour corriger un texte et 
            vous affiche les corrections à appliquer au texte grace à la librairie finediff</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . 'echo (new reversoLib())->correctionText("Un texte avec une grosse fote");'
                . "\n"
                . "?>", $this->_brush);
        debug::print_r("Un texte avec une grosse <del>fote</del><ins>faute</ins>");
    }

    private function robotstxt() {
        ?>
        <p>Cette classe permet de générer le robot.txt d'un site </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . '//$data : lignes à ajouter au robot.txt (commencer par \n et séparer chaque ligne par \n)'
                . "\nnew robotstxt($" . "data=\"\");\n"
                . "?>", $this->_brush);
    }

    private function selectorDOM() {
        ?>
        <p>Source <a href="https://github.com/tj/php-selector">https://github.com/tj/php-selector</a> <br />
            "selectorDOM" permet de manipuler le DOM d'un document en PHP.
        </p>
        <div class="alert alert-danger" role="alert"><p>Attention à l'utilisation de cette classe sur des pages tiers ! <br />
                La copie même partielle d'un site tiers sans autorisation préalable (et en dehors d'une utilisation strictement privée) est un délit ! <br />
                (Article L.713-2 du Code de la propriété intellectuelle)</p></div>        

        <?php
        js::syntaxhighlighter("<?php\n"
                . "//les selecteurs ont la même syntaxe que Jquery\n"
                . "selectorDOM::select_elements('header', file_get_contents('http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?page=index'));\n"
                . "?>", $this->_brush);
        ?><p>Résultat :</p><?php
        debug::print_r(json_decode('[{"name":"header","attributes":{"class":"page-header bg-info"},"text":"\r\n        DocumentationDocumentation de DWF\r\n      \r\n    ","children":[{"name":"h1","attributes":[],"text":"\r\n        DocumentationDocumentation de DWF\r\n      ","children":[{"name":"br","attributes":[],"text":"","children":[]},{"name":"small","attributes":[],"text":"Documentation de DWF","children":[]}]}]}]'));
    }

    private function services() {
        ?>
        <p>
            La classe service est une classe qui permet de communiquer avec des services web d'un tiers ou que vous aurez vous même créé.
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//envoie une requête GET à un service et en retourne la réponse \n"
                . "service::HTTP_GET($" . "url);\n\n"
                . "//envoie une requête POST à un service et en retourne la réponse\n"
                . "service::HTTP_POST_REQUEST($" . "url, $" . "params, $" . "ssl = false);\n\n"
                . "//envoie une requête POST à un service SANS en retourner la réponse\n"
                . "service::HTTP_POST($" . "url, $" . "params, $" . "ssl); //$" . "ssl \n\n"
                . "//cette fonction est à utiliser pour filtrer les IP autorisées à acceder à votre script/service\n"
                . "service::security_check($" . "ip_allow=array('localhost', '127.0.0.1', '::1'));\n\n"
                . "//fonctions de conversion xml/csv -> json\n"
                . "$" . "json=xml_to_json($" . "xml_string);\n"
                . "$" . "json=csv_to_json($" . "csv_string);\n"
                . "?>", $this->_brush);
    }

    private function session() {
        ?>
        <p>La classe session permet de gérer les variables de sessions liées au projet courant,<br />
            cette classe créée vos variables en exploitant config::$_prefix</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "session::get_user(); \n"
                . "//equivaut à $" . "_SESSION[config::$" . "_prefix . '_user'];\n\n"
                . "session::set_val($" . "key, $" . "value);\n"
                . "//plus simple que $" . "_SESSION[config::$" . "_prefix . '_' . $" . "key] = $" . "value;\n\n"
                . "session::get_val($" . "key);\n"
                . "//plus simple que $" . "_SESSION[config::$" . "_prefix . '_' . $" . "key];\n"
                . "?>", $this->_brush);
    }

    private function shuffle_letters() {
        ?>
        <p>(cf js)</p>
        <?php
    }

    private function singleton() {
        ?>
        <p>
            Cette classe sert de base pour créer des singleton. <br />
            la methode "get_instance" retournera toujours la même instance.
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "class ma_classe extends singleton{\n"
                . "    public function une_fonction(){\n"
                . "        return true;\n"
                . "    }\n\n"
                . "    public static function une_fonction_static(){\n"
                . "        //usage en interne\n"
                . "        return self::get_instance()->une_fonction();\n"
                . "    }\n"
                . "}\n"
                . "//usage en externe\n"
                . "ma_class::get_instance()->une_fonction();"
                . "?>", $this->_brush);
    }

    private function sitemap() {
        ?>
        <p>Cette classe gère les "sitemap" du site <br />
            Pour les routes qui dépendent d'une variable, renseignez dans la route (par exemple): <br />
            "sitemap" => array("var" => "id", "entity" => "user", "tuple" => "login") <br />
            Le sitemap est généré automatiquement par <em>application.class.php</em> si <em>config::$_sitemap=true</em>
        </p>
        <?php
    }

    private function sms_gateway() {
        ?>
        <p>Cette classe permet de faciliter l'utilisation d'un gateway SMS afin de pouvoir envoyer des SMS depuis une application Web. <br />
            Cette classe a été conçue pour fonctionner par défaut avec le logiciel SMS Gateway installé sur un appareil Android.<br />
            <a href="https://dwf.sytes.net/smsgateway">SMS Gateway</a><br />
            Si vous utilisez un autre programme, veillez à adapter les paramètres en conséquence.</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "sms_gateway = new sms_gateway($" . "gateway_host, $" . "gateway_port = 8080, $" . "gateway_page_send = 'sendmsg', $" . "gateway_page_index = 'run');\n"
                . "//Retourne si le service répond ou non (true/false)\n"
                . "$" . "sms_gateway->is_runing();\n"
                . "//Envoi de SMS par URL\n"
                . "$" . "sms_gateway->send_by_url(array('phone' => '0654321987', 'text' => 'le sms'), $" . "methode = 'post', $" . "ssl = false);\n"
                . "//Envoi de SMS par URL avec password\n"
                . "$" . "sms_gateway->send_by_url(array('phone' => '0654321987', 'text' => 'le sms', 'psw'=>'motdepasse'), $" . "methode = 'post', $" . "ssl = false);\n"
                . "?>", $this->_brush);
    }

    private function sql_backup() {
        ?>
        <p>
            La classe sql_backup permet de créer des backup quotidiens de la base de données <br />
            les backup peuvent être stockés sur un disque dur (différent du disque de l'application) ou sur un (s)ftp distant <br />
            il est recommandé de placer la ligne de sql_backup dans le constructeur de <em>pages.class.php</em>
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//créé un backup quotidien dans un dossier\n"
                . "(new sql_backup())->backup_to_path($" . "path);\n\n"
                . "//créé un backup quotidien sur un (s)ftp distant\n"
                . "(new sql_backup())->backup_to_ftp($" . "dir, $" . "host, $" . "login, $" . "psw, $" . "ssl);\n"
                . "?>", $this->_brush);
    }

    private function ssl() {
        ?>
        <p>Cette classe permet de chiffrer et déchiffrer des messages <br />
            actuellement, un bug est connu dans la librairie JS: <br />
            si <em>(new ssl())->ssl_js()</em> est utilisé pour que l'utilisateur puisse chiffrer ces messages,<br />
            la taille de la clé limite la taille maximale du message à chiffrer : <br />
        </p>
        <ul>
            <li>1024 bits = 117 caractères</li>
            <li>2048 bits = 245 caractères</li>
            <li>4096 bits = 501 caractères</li>
        </ul>
        <p>en attendant que le bug JS soit corrigé, cette classe reste très pratique pour échanger des messages chiffrés entre deux services PHP par exemple.</p>           
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//créé les clés publiques et privés\n"
                . "$" . "ssl = new ssl($" . "bits = 1024);\n"
                . "//retourne la clé publique\n"
                . "$" . "ssl->get_public_key();\n"
                . "//Chiffre un message avec la clé publique\n"
                . "$" . "ssl->encrypt($" . "data);\n"
                . "//Charge les librairie JS et chiffre la saisie des utilisateurs dans les formulaires de la page\n"
                . "$" . "ssl->ssl_js();\n"
                . "//Déchiffre les variables $" . "_POST ( cf $" . "ssl->ssl_js() )\n"
                . "$" . "ssl->decrypt_post();\n"
                . "?>", $this->_brush);
        ?><p>Exemple, message à chiffrer : "Hello World"</p><?php
        $ssl = new ssl();
        ?><p>Clé publique ssl (<em>$ssl->get_public_key()</em>) :</p><?php
        debug::print_r($ssl->get_public_key());
        ?><p>Message chiffré (<em>$msg=$ssl->encrypt("Hello World")</em>) :</p><?php
        debug::print_r($msg = $ssl->encrypt("Hello World"));
        ?><p>Message déchiffré (<em>$ssl->decrypt($msg)</em>) :</p><?php
        debug::print_r($ssl->decrypt($msg));
    }

    private function stalactite() {
        ?>
        <p>(cf js)</p>
        <?php
    }

    private function statistiques() {
        ?>
        <p>Cette classe permet de recueillir et d'afficher des statistiques liés à l'activité des utilisateurs</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "stat=new statistiques();\n"
                . "//Cette fonction permet d'enregistrer l'activité des utilisateurs sur la page actuelle\n"
                . "$" . "stat->add_stat();\n"
                . "//Cette fonction permet d'afficher les statistiques ( il est conseillé de ne pas appeler cette fonction sur une page \"publique\" )\n"
                . "$" . "stat->get_stat();\n"
                . "?>", $this->_brush);
    }

    private function sub_menu() {
        ?>
        <p>La classe sub_menu vous permet de créer un sous_menu en utilisant un système de "sous-routes"</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "key = 'key'; //clé principale utilisé dans les sous-routes\n"
                . "$" . "route = array(\n"
                . "    array('key' => 'sous_route_1', 'title' => 'title 1', 'text' => 'texte 1'),\n"
                . "    array('key' => 'sous_route_2', 'title' => 'title 2', 'text' => 'texte 2'),\n"
                . "    array('key' => 'sous_route_masque', 'title' => 'title de la route masqué'),\n"
                . ");\n"
                . "$" . "route_default = 'sous_route_1'; //route par defaut\n"
                . "new sub_menu($" . "this, $" . "route, $" . "key, $" . "route_default);\n"
                . "//$" . "this est l'assesseur de la classe courante,\n"
                . "//cette classe devra par la suite disposer de méthodes publiques ayant pour nom les valeurs des routes\n"
                . "//ici notre classe devra contenir les méthodes publiques : \n"
                . "//sous_route_1(), sous_route_2() et sous_route_masque()\n"
                . "?>", $this->_brush);
    }

    private function syntaxhighlighter() {
        ?>
        <p>(cf js)</p>
        <?php
    }

    private function tags() {
        ?>
        <p>
            Cette classe permet de créer et manipuler des balises HTML avant de les afficher.
        </p>
        <p class="alert alert-info">
            Note 21.18.08 : dès sa création cette classe est devenue centrale dans la génération de HTML dans les classes natives du framework. <br />
            Utiliser cette classe permet d'obtenir un code 100% PHP plus lisible et plus facile à maintenir qu'un code PHP "entrecoupé" de codes HTML. <br />
            Les impacts négatifs de cette classe sur l'utilisation de la mémoire et le temps d'exécution de PHP sont très faibles. <br />
            Bien entendu l'utilisation de cette classe dans vos projets et classes métiers reste facultative 
        </p>
        <p>Usage :</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Créé une balise p.maClasse et la retourne sous forme d'une chaine de caractère\n"
                . "echo tags::tag('p',['class'=>'maClasse'],'mon contenu');\n\n"
                . "//Créé une balise auto-fermé\n"
                . "echo tags::tag('input',['name'=>'monInput'],false);\n\n"
                . "//Créé une balise ul.maListe sous forme d'objet\n"
                . "$" . "ul = tags::ul(['class'=>'maListe'],'');\n\n"
                . "//Ajout/modification d'un atribut \n"
                . "$" . "ul->set_attr('id','maListe');\n\n"
                . "//Suppression d'un attribut \n"
                . "$" . "ul->del_attr('class');\n\n"
                . "//Retourne la valeur d'un attribut\n"
                . "$" . "ul->get_attr('class'); //retournera null si l'attribut n'existe pas\n\n"
                . "//Retourne le tag de la balse\n"
                . "$" . "ul->get_tag(); //retourne 'ul'\n\n"
                . "//Redéfini le tag de la balise \n"
                . "$" . "ul->set_tag('ol');\n\n"
                . "//Retourne le contenu de la balise\n"
                . "$" . "ul->get_content();\n\n"
                . "//Redefini le contenu de la balise, (ici une balise li)\n"
                . "$" . "ul->set_content(tags::tag(li,[],'1e item'));\n\n"
                . "//Ajoute du contenu a la balise\n"
                . "$" . "ul->append_content(tags::tag(li,[],'2e item'));\n\n"
                . "//Affiche la balise et son contenu\n"
                . "echo $" . "ul;\n"
                . "?>", $this->_brush);
        ?>
        <p>Exemple :</p>
        <div class="row">
            <div class="col-sm-6">
                <p>Code :</p>
                <?php
                js::syntaxhighlighter("<?php\n"
                        . "$" . "ul = tags::ul();\n"
                        . "foreach (['Pomme', 'Pêche', 'Poire', 'Abricot'] as $" . "fruit) {\n"
                        . "    $" . "ul->append_content(tags::tag('li', [], $" . "fruit));\n"
                        . "}\n"
                        . "echo tags::tag('div', [], tags::tag(\n"
                        . "     'p', [], 'Ma liste de ' . tags::tag(\n"
                        . "         'strong', [], 'fruit')\n"
                        . "     ) . $" . "ul\n"
                        . ");\n\n"
                        . "// ou plus simplement avec html_structures\n"
                        . "echo tags::tag('div', [], tags::tag(\n"
                        . "     'p', [], 'Ma liste de ' . tags::tag(\n"
                        . "         'strong', [], 'fruit')\n"
                        . "     ) . html_structures::ul(['Pomme', 'Pêche', 'Poire', 'Abricot'])\n"
                        . ");\n"
                        . "?>", $this->_brush);
                ?>
            </div>
            <div class="col-sm-6">
                <p>Resultat :</p>
                <?php
                echo tags::tag('div', [], tags::tag(
                                'p', [], 'Ma liste de ' . tags::tag(
                                        'strong', [], 'fruit')
                        ) . html_structures::ul(['Pomme', 'Pêche', 'Poire', 'Abricot'])
                );
                ?>
            </div>
        </div>
        <?php
    }

    private function template() {
        ?><p>Cette classe permet d'utiliser des template en utilisant la librairie  
            <?= html_structures::a_link("https://www.smarty.net/docsv2/fr/index.tpl", "Smarty") ?></p>
        <p>Les templates doivent étre créé dans le dossier <em>html/[votre-projet]/class/tpl</em> <br /> 
            ce dossier peut être créé par la classe template si vous ne le créez pas au préalable <br />
            le ficher de template doit être un fichier .tpl ( exemple <em>mon_template.tpl</em>) <br />
            les droits en écriture sur le dossier <em>html/[votre-projet]/class/tpl.compile</em> doivent être donné au service web
        </p>
        <p>exemple, ficher <em>mon_template.tpl</em></p>
        <?php
        js::syntaxhighlighter(""
                . "<p>Bienvenu { $" . "name}</p>\n"
                . "<div class=\"row\">\n"
                . "    <div class=\"col-sm-6\">\n"
                . "        <ul>\n"
                . "            {foreach from=$" . "list item=value}\n"
                . "                <li>{ $" . "value}</li>\n"
                . "            {/foreach}\n"
                . "        </ul>\n"
                . "    </div>\n"
                . "    <div class=\"col-sm-6\">\n"
                . "        <dl class=\"dl-horizontal\">\n"
                . "            {foreach from=$" . "list_asso key=key item=value}\n"
                . "                <dt>{ $" . "key}</dt> <dd>{ $" . "value}</dd>\n"
                . "            {/foreach}\n"
                . "        </dl>\n"
                . "    </div>\n"
                . "</div>"
                . "", $this->_brush);
        ?>
        <p>Appel du template dans le code php (pages.class.php par exemple)</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "new template('mon_template', [\n"
                . "    'name' => 'Matthieu',\n"
                . "    'list' => ['une','simple','liste'],\n"
                . "    'list_asso' => [\n"
                . "        'une liste'=>'associatif',\n"
                . "        'tel'=>'0123456789',\n"
                . "        'mail'=>'mon.mail@monfai.fr'\n"
                . "        ]\n"
                . "])\n"
                . "?>", $this->_brush);
    }

    private function time() {
        ?>
        <p>La classe "time" permet d'effectuer des calculs sur les dates et des conversions de format de dates US <=> FR</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Démarre un chronomètre pour chronometrer la durée d'éxécution d'un bout de code,\n"
                . "//il est possible d'utiliser plusieurs chronomètres en leurs spécifiant un identifiant \n"
                . "//l'identifiant peut être un nombre ou une chaine de caractères\n"
                . "time::chronometer_start($" . "id = 0);\n\n"
                . "//Retourne le temps mesuré par un chronomètre depuis son lancement\n"
                . "time::chronometer_get($" . "id = 0);\n\n"
                . "//retourne si une année est bisextile ou non.\n"
                . "time::anne_bisextile($" . "an);\n\n"
                . "//Retourne le mois \"en lettres\" du numéro de mois passé en paramètre\n"
                . "time::convert_mois($" . "num_mois);\n\n"
                . "//Convertit une date au format FR (dd/mm/yyyy) au format US (yyyy-mm-dd) \n"
                . "time::date_fr_to_us($" . "dateFR);\n\n"
                . "//Convertit une date au format US (yyyy-mm-dd) au format FR (dd/mm/yyyy)\n"
                . "time::date_us_to_fr($" . "dateUS);\n\n"
                . "//Cette fonction permet d'additioner ou de soustraire un nombre de mois à une date initiale\n"
                . "time::date_plus_ou_moins_mois($" . "date, $" . "mois);\n\n"
                . "//Affiche un élément de formulaire pour renseigner une date (jour/mois/année)\n"
                . "//(il est plus commun d'utiliser un datepicker (cf form)\n"
                . "time::form_date($" . "label, $" . "post, $" . "value = null);\n\n"
                . "//Retourne la date saisie dans l'élément de formulaire time::form_date()\n"
                . "time::get_form_date($" . "post);\n\n"
                . "//Retourne un tableau d'information sur la date passée en paramètre\n"
                . "time::get_info_from_date($" . "date_us);\n\n"
                . "//Retourne le nombre de jours dans un mois \n"
                . "//(l'année doit être renseignée pour gérer les années bisextiles)\n"
                . "time::get_nb_jour($" . "num_mois, $" . "an);\n\n"
                . "//Retourne l'âge actuel en fonction d'une date de naissance\n"
                . "time::get_yers_old($" . "d, $" . "m, $" . "y);\n\n"
                . "//Parse un temps en secondes en jours/heures/minutes/secondes \n"
                . "//pour les temps inférieurs à 1 seconde, le parse peut se faire en millisecondes ou microsecondes\n"
                . "time::parse_time($" . "secondes);\n\n"
                . "//astuce pour afficher un chronomètre bien présenté\n"
                . "echo time::parse_time(time::chronometer_get($" . "id));\n"
                . "?>", $this->_brush);
    }

    private function tor() {
        ?>
        <p>Cette classe permet de recuperer une ressource en passant par tor</p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "$" . "data = (new tor())->wget($" . "url);\n"
                . "?>", $this->_brush);
    }

    private function trad() {
        ?>
        <p>Cette classe permet de créer des traductions à partir de clés, <br />
            l'administration de clé=>traductions se fait par une interface à placer dans la partie administration de l'application. <br />
            le langage de l'utilisateur est défini dans session::get_lang() (peut être modifié par session::set_lang()) <br />
            Les traductions peuvent être gérées en base de données (par défaut) ou par des fichier JSON (cf paramètres du constructeur)
        </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//affiche l'interface d'administration\n"
                . "(new trad())->admin();\n\n"
                . "//affiche les traductions liées au clés 'CLE_1' et 'CLE_2'\n"
                . "$" . "trad=new trad();\n"
                . "echo $" . "trad->t('CLE_1');\n"
                . "echo $" . "trad->t('CLE_2');\n"
                . "?>", $this->_brush);
    }

    private function update_dwf() {
        ?>
        <p>Cette classe permet de gérer les mises à jour de DWF (a plasser dans une inerface d'administration) <br />
            ATTENTION ! Git doit être installé sur la machine hôte !
        </p>
        <?php
        js::syntaxhighlighter("<?php new update_dwf(); ?>", $this->_brush);
        $vers = "21.18.08";
        $versm1 = "21.18.07";
        $vgit = "2.18.0";
        echo html_structures::table(["Version GIT courante", "Version DWF courante", "Dernière version DWF disponible", "Status / Mise à jour"], [
            ["git version " . $vgit, $vers, $vers, "Already up-to-date."],
            ["OU", "", "", ""],
            ["git version " . $vgit, $versm1, $vers, '<input type="submit" class="btn btn-block btn-primary" value="Update from ' . $versm1 . ' to ' . $vers . '" />']
        ]);
    }

    private function video() {
        ?>
        <p>Cette classe permet d'afficher une vidéo avec un player accessible</p>
        <?php
        js::syntaxhighlighter("<?php new video('./files/videos/nuagesMusicman921.webm',$" . "id='video-js'); ?>", $this->_brush);
        new video('./files/videos/nuagesMusicman921.webm');
        ?><p>Credit : <br />
            Vidéo : Nuages - Libre de Droits <a href="https://www.youtube.com/watch?v=NqIw5wHvGYQ">https://www.youtube.com/watch?v=NqIw5wHvGYQ</a> <br />
            Musique  : Dread (v2) - musicman921 <a href="https://musicman921.newgrounds.com/">https://musicman921.newgrounds.com/</a>
        </p><?php
    }

    private function vticker() {
        ?>
        <p>(cf js)</p>
        <?php
    }

    private function w3c_validate() {
        ?>
        <p>Inscrit les erreurs HTML du site dans le log. <br />
            requiert que le sitemap soit actif, et que le site soit en ligne </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//vérifie si les pages du sites sont conformes W3C\n"
                . "new w3c_validate();\n"
                . "//Retourne le statut de la page passée en paramètre\n"
                . "//(si la page est conforme W3C)\n"
                . "w3c_validate::validate_from_url($" . "url);\n"
                . "?>", $this->_brush);
    }

    private function writer() {
        ?>
        <p>Cette classe permet de gèrer un buffer a l'ecriture de fichiers </p>
        <?php
        js::syntaxhighlighter("<?php\n"
                . "//Ajoute un fichier au buffer\n"
                . "writer::get_instance()->add($" . "file, $" . "content);\n\n"
                . "//Verifie si un ficher est dans le buffer\n"
                . "writer::get_instance()->exist($" . "file);\n\n"
                . "//Retourne le contenu d'un fichier du buffer (chaine vide si non)\n"
                . "writer::get_instance()->content($" . "file);\n\n"
                . "//Retourne le nombre de fichiers dans le buffer\n"
                . "writer::get_instance()->count();\n\n"
                . "//Supprime un fichier du buffer\n"
                . "writer::get_instance()->clear($" . "file);\n\n"
                . "//Supprime tout les fichiers du buffer\n"
                . "writer::get_instance()->clear();\n\n"
                . "//Ecris les fichier du buffer sur le disque dur (et vide le buffer)\n"
                . "writer::get_instance()->write();\n\n"
                . "//Ecris les fichier du buffer dans une archive (et vide le buffer)\n"
                . "writer::get_instance()->write_zip($" . "zipname);\n"
                . "?>", $this->_brush);
    }

}
