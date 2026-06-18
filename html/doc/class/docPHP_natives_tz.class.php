<?php

class docPHP_natives_tz {

    public static function tags() {
        ?>
        <p>
            Cette classe permet de créer et manipuler des balises HTML avant de les afficher.
        </p>
        <p class="alert alert-info">
            Note 21.18.08 : dès sa création, cette classe est devenue centrale dans la génération de HTML dans les classes natives du framework. <br />
            Utiliser cette classe permet d'obtenir un code 100% PHP plus lisible et plus facile à maintenir qu'un code PHP "entrecoupé" de codes HTML. <br />
            Les impacts négatifs de cette classe sur l'utilisation de la mémoire et le temps d'exécution de PHP sont très faibles. <br />
            Bien entendu l'utilisation de cette classe dans vos projets et classes métiers reste facultative 
        </p>
        <p>Usage :</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Créé une balise p.maClasse et la retourne sous forme d\'une chaine de caractère\n' .
                'echo tags::tag("p", ["class"=>"maClasse"], "mon contenu");\n\n' .
                '//Créé une balise auto-fermée\n' .
                'echo tags::tag("input", ["name"=>"monInput"], false);\n\n' .
                '//Créé une balise ul.maListe sous forme d\'objet\n' .
                '$ul = tags::ul(["class"=>"maListe"], "");\n\n' .
                '//Ajout/modification d\'un attribut \n' .
                '$ul->set_attr("id", "maListe");\n\n' .
                '//Suppression d\'un attribut \n' .
                '$ul->del_attr("class");\n\n' .
                '//Retourne la valeur d\'un attribut\n' .
                '$ul->get_attr("class"); //retournera null si l\'attribut n\'existe pas\n\n' .
                '//Retourne le tag de la balise\n' .
                '$ul->get_tag(); //retourne "ul"\n\n' .
                '//Redéfinit le tag de la balise \n' .
                '$ul->set_tag("ol");\n\n' .
                '//Retourne le contenu de la balise\n' .
                '$ul->get_content();\n\n' .
                '//Redefinit le contenu de la balise, (ici une balise li)\n' .
                '$ul->set_content(tags::tag("li", [], "1e item"));\n\n' .
                '//Ajoute du contenu à la balise\n' .
                '$ul->append_content(tags::tag("li", [], "2e item"));\n\n' .
                '//Affiche la balise et son contenu\n' .
                'echo $ul;\n' .
                '?>'
        );
        ?>
        <p>Exemple :</p>
        <div class="row">
            <div class="col-sm-6">
                <p>Code :</p>
                <?php
                js::monaco_highlighter('<?php\n' .
                        '$ul = tags::ul();\n' .
                        'foreach (["Pomme", "Pêche", "Poire", "Abricot"] as $fruit) {\n' .
                        '    $ul->append_content(tags::tag("li", [], $fruit));\n' .
                        '}\n' .
                        'echo tags::tag("div", [], tags::tag(\n' .
                        '     "p", [], "Ma liste de " . tags::tag(\n' .
                        '         "strong", [], "fruit")\n' .
                        '     ) . $ul\n' .
                        ');\n\n' .
                        '// ou plus simplement avec html_structures\n' .
                        'echo tags::tag("div", [], tags::tag(\n' .
                        '     "p", [], "Ma liste de " . tags::tag(\n' .
                        '         "strong", [], "fruit")\n' .
                        '     ) . html_structures::ul(["Pomme", "Pêche", "Poire", "Abricot"])\n' .
                        ');\n' .
                        '?>'
                );
                ?>
            </div>
            <div class="col-sm-6">
                <p>Résultat :</p>
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

    public static function task_manager() {
        ?>
        <p>
            Cette classe permet de gérer les tâches planifiées. <br />
            Contrairement à un pseudo cron qui dépend de l'activité des utilisateurs, <br />
            les tâches planifiées sont lancées à une heure précise, mais cela nécessite l'exécution permanente d'un script en CLI.
        </p>
        <h5>Initialisation</h5>
        <p>
            La méthode statique suivante doit être appelée dans votre projet,<br />
            cela initialisera une table et une entité "task" ainsi qu'un dossier "task_worker" dans votre projet.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'task_manager::init();\n'
                . '?>');
        ?>
        <h5>Workers</h5>
        <p>Un worker est une classe qui sera utilisée pour effectuer une tâche précise, <br />
            Exemple : <br />
        </p>
        <ul>
            <li>Envoyer des mails</li>
            <li>Nettoyer/sauvegarder la base de données</li>
            <li>Lancer des audits et créer des rapports</li>
        </ul>
        <p>Il doit être placé dans le dossier "task_worker" et se nommer "[nom_du_worker].worker.php"</p>
        <h6>Exemple de worker (affiche un hello world dans la console)</h6>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'class hello {\n'
                . '    public static function run($param = []) {\n'
                . '        cli::write("Hello world !");\n'
                . '    }\n'
                . '}\n'
                . '?>');
        ?>
        <p>A noter que les classes du framework sont accessibles depuis les workers, un worker peut donc faire appel au task_manager pour programmer une nouvelle tâche (en cas d'erreur par exemple). <br />
            Exemple : votre worker doit envoyer un mail, l'envoi échoue car le SMTP est indisponible, vous pouvez capturer l'erreur pour programmer une nouvelle tentative d'envoi dans 1 heure.</p>
        <h5>Utilisation (dans le projet)</h5>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Programme une tâche utilisant le worker "send_mail" dans 30 secondes,\n'
                . '//on indique le mail dans le tableau de paramètres\n'
                . 'task_manager::add(time() + 30, "send_mail", ["mail" => "someone@host.com"]);\n\n'
                . '//Affiche les tâches en attente et terminées (à placer dans une administration\n'
                . 'task_manager::print_tasks();'
                . '?>'
        );
        ?>
        <h5>Execution</h5>
        <p>Pour lancer le script d'exécution des tâches, il faut ouvrir un terminal et exécuter la commande suivante :</p>
        <?php
        js::monaco_highlighter("php [votre-projet]/task_worker/run.php");
    }

    public static function template() {
        ?><p>Cette classe permet d'utiliser des templates en utilisant la librairie  
        <?= html_structures::a_link("https://www.smarty.net/docsv2/fr/index.tpl", "Smarty") ?></p>
        <p>Les templates doivent être créés dans le dossier <em>html/[votre-projet]/class/tpl</em> <br /> 
            ce dossier peut être créé par la classe template si vous ne le créez pas au préalable <br />
            le fichier de template doit être un fichier .tpl ( exemple <em>mon_template.tpl</em>) <br />
            les droits en écriture sur le dossier <em>html/[votre-projet]/class/tpl.compile</em> doivent être donnés au service web
        </p>
        <p>exemple, fichier <em>mon_template.tpl</em></p>
        <?php
        js::monaco_highlighter(''
                . '<p>Bienvenue { $name}</p>\n'
                . '<div class="row">\n'
                . '    <div class="col-sm-6">\n'
                . '        <ul>\n'
                . '            {foreach from=$list item=value}\n'
                . '                <li>{ $value}</li>\n'
                . '            {/foreach}\n'
                . '        </ul>\n'
                . '    </div>\n'
                . '    <div class="col-sm-6">\n'
                . '        <dl class="dl-horizontal">\n'
                . '            {foreach from=$list_asso key=key item=value}\n'
                . '                <dt>{ $key}</dt> <dd>{ $value}</dd>\n'
                . '            {/foreach}\n'
                . '        </dl>\n'
                . '    </div>\n'
                . '</div>'
                . '');
        ?>
        <p>Appel du template dans le code php (pages.class.php par exemple)</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'new template("mon_template", [\n'
                . '    "name" => "Matthieu",\n'
                . '    "list" => ["une","simple","liste"],\n'
                . '    "list_asso" => [\n'
                . '        "une liste"=>"associatif",\n'
                . '        "tel"=>"0123456789",\n'
                . '        "mail"=>"mon.mail@monfai.fr"\n'
                . '        ]\n'
                . ']);\n'
                . '?>'
        );
    }

    public static function tenor() {
        ?>
        <p>
            Cette classe permet de retourner des Gifs de l'API TENOR
        </p>
        <p>
            Afin d'etre utilisable, la clé api doit etre renseignée dans <strong>config.class.php</strong> ainsi :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'public static static $_tenor_key= "VOTRE CLE API";\n'
                . '?>');
        echo'<p>Usage :</p>';
        js::monaco_highlighter('<?php\n'
                . 'new tenor();\n'
                . '?>');
    }

    public static function thread_manager() {
        ?>
        <p>Cette classe permet de multi-thread une fonction static avec un tableau de données</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'maclass{\n'
                . '    public static function ma_fonction_static($param1,$param2){\n'
                . '        usleep(500000); //simule un temps d\'éxécution de 0.5s\n'
                . '        return "something";\n'
                . '    }\n'
                . '}\n\n'
                . '$data=[\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ...\n'
                . '];\n'
                . '$static_function="maclass::ma_fonction_static";\n'
                . '$maxthread=4;\n'
                . '$manager = new thread_manager($data, $static_function, $maxthead);\n'
                . '$results = $manager->get_results();\n'
                . '//temps d\'éxécution théorique (pour 20 lignes dans $data):\n'
                . '?>');
        ?>
        <p>Notes sur les temps théoriques et réels d'éxécution :</p>
        <ul>
            <li>Temps théorique et réél monothread classique : 10s</li>
            <li>Temps théorique multi-thread (4) : 2.5s</li>
            <li>temps réel multi-thread (4) : 3.5s</li>
        </ul>
        <p>L'écart entre le temps réel et théorique est dû aux requêtes SQL et HTTP au service de threads mais reste avantageux face au monothread</p>
        <p>Si vous utilisez cette classe gardez en tête que PHP n'est pas conçu pour le multi-threading et que cette classe est juste un outil pour paralleliser des traitements de données</p>
        <?php
    }

    public static function time() {
        ?>
        <p>La classe "time" permet d'effectuer des calculs sur les dates et des conversions de format de dates US <=> FR</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Démarre un chronomètre pour chronometrer la durée d\'éxécution d\'un bout de code,\n'
                . '//il est possible d\'utiliser plusieurs chronomètres en leurs spécifiant un identifiant \n'
                . '//l\'identifiant peut être un nombre ou une chaine de caractères\n'
                . 'time::chronometer_start($id = 0);\n\n'
                . '//Retourne le temps mesuré par un chronomètre depuis son lancement\n'
                . 'time::chronometer_get($id = 0);\n\n'
                . '//retourne si une année est bissextile ou non.\n'
                . 'time::anne_bisextile($an);\n\n'
                . '//Retourne le mois "en lettres" du numéro de mois passé en paramètre\n'
                . 'time::convert_mois($num_mois);\n\n'
                . '//Convertit une date au format FR (dd/mm/yyyy) au format US (yyyy-mm-dd) \n'
                . 'time::date_fr_to_us($dateFR);\n\n'
                . '//Convertit une date au format US (yyyy-mm-dd) au format FR (dd/mm/yyyy)\n'
                . 'time::date_us_to_fr($dateUS);\n\n'
                . '//Cette fonction permet d\'additioner ou de soustraire un nombre de mois à une date initiale\n'
                . 'time::date_plus_ou_moins_mois($date, $mois);\n\n'
                . '//Affiche un élément de formulaire pour renseigner une date (jour/mois/année)\n'
                . '//(il est plus commun d\'utiliser un datepicker (cf form)\n'
                . 'time::form_date($label, $post, $value = null);\n\n'
                . '//Retourne la date saisie dans l\'élément de formulaire time::form_date()\n'
                . 'time::get_form_date($post);\n\n'
                . '//Retourne un tableau d\'informations sur la date passée en paramètre\n'
                . 'time::get_info_from_date($date_us);\n\n'
                . '//Retourne le nombre de jours dans un mois \n'
                . '//(l\'année doit être renseignée pour gérer les années bissextiles)\n'
                . 'time::get_nb_jour($num_mois, $an);\n\n'
                . '//Retourne l\'âge actuel en fonction d\'une date de naissance\n'
                . 'time::get_yers_old($d, $m, $y);\n\n'
                . '//Parse un temps en secondes en jours/heures/minutes/secondes \n'
                . '//pour les temps inférieurs à 1 seconde, le parse peut se faire en millisecondes ou microsecondes\n'
                . 'time::parse_time($secondes);\n\n'
                . '//astuce pour afficher un chronomètre bien présenté\n'
                . 'echo time::parse_time(time::chronometer_get($id));\n'
                . '?>');
    }

    public static function tinymce() {
        ?>
        <p class="alert alert-warning">
            <span>Déprécié depuis la version 21.25.02, utilisez plutôt Summernote</span>
        </p>
        <hr>
        <h3 class="text-center">js::summernote</h3>
        <hr>
        <?php
        docPHP_natives_js::summernote();
    }

    public static function tor() {
        ?>
        <p>Cette classe permet de récupérer une ressource en passant par tor</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$data = (new tor())->wget($url);\n'
                . '?>');
    }

    public static function trad() {
        ?>
        <p>Cette classe permet de créer des traductions à partir de clés, <br />
            l'administration de clés=>traductions se fait par une interface à placer dans la partie administration de l'application. <br />
            le langage de l'utilisateur est défini dans session::get_lang() (peut être modifié par session::set_lang()) <br />
            Les traductions peuvent être gérées en base de données (par défaut) ou par des fichiers JSON (CF : paramètres du constructeur)
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//affiche l\'interface d\'administration\n'
                . '(new trad())->admin();\n\n'
                . '//affiche les traductions liées au clés \'CLE_1\' et \'CLE_2\'\n'
                . '$trad=new trad();\n'
                . 'echo $trad->t("CLE_1");\n'
                . 'echo $trad->t("CLE_2");\n'
                . '?>'
        );
    }

    public static function update_dwf() {
        ?>
        <p>Cette classe permet de gérer les mises à jour de DWF (à placer dans une interface d'administration) <br />
            ATTENTION ! GIT doit être installé sur la machine hôte !
        </p>
        <?php
        js::monaco_highlighter('<?php new update_dwf(); ?>');
        $vers = "21.25.02";
        $versm1 = "21.24.10";
        $vgit = "2.34.1";
        echo html_structures::table(["Version GIT courante", "Version DWF courante", "Dernière version DWF disponible", "Statut / Mise à jour"], [
            ["git version " . $vgit, $vers, $vers, "Already up-to-date."],
            ["OU", "", "", ""],
            ["git version " . $vgit, $versm1, $vers, '<input type="submit" class="btn btn-block btn-primary" value="Update from ' . $versm1 . ' to ' . $vers . '" />']
        ]);
    }

    public static function video() {
        ?>
        <p>Cette classe permet d'afficher une vidéo avec un player accessible</p>
        <?php
        js::monaco_highlighter('<?php new video("./files/videos/nuagesMusicman921.webm",$id="video-js"); ?>');
        ?>
        <div style='width:600px' class="mx-auto">
            <?php
            new video('./files/videos/nuagesMusicman921.webm');
            ?>
        </div>
        <p>Crédit : <br />
            Vidéo : Nuages - Libre de Droits <a href="https://www.youtube.com/watch?v=NqIw5wHvGYQ">https://www.youtube.com/watch?v=NqIw5wHvGYQ</a> <br />
            Musique  : Dread (v2) - musicman921 <a href="https://musicman921.newgrounds.com/">https://musicman921.newgrounds.com/</a>
        </p><?php
    }

    public static function vpage() {
        ?>
        <p>Affiche une page virtuelle (iframe) à partir du contenu fourni</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'ob_start();\n'
                . '//contenu\n'
                . 'new vpage(ob_get_clean(),$title="vpage",$ttl = 86400);\n'
                . '?>');
        new vpage("<p>test</p>", $title = "vpage", $ttl = 86400);
    }

    public static function vticker() {
        ?>
        <h3 class="text-center">js::vTicker</h3>
        <hr>
        <?php
        docPHP_natives_js::vTicker();
    }

    public static function w3c_validate() {
        ?>
        <p>Inscrit les erreurs HTML du site dans le log. <br />
            requiert que le sitemap soit actif, et que le site soit en ligne </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//vérifie si les pages du site sont conformes W3C\n'
                . 'new w3c_validate();\n'
                . '//Retourne le statut de la page passée en paramètre\n'
                . '//(si la page est conforme W3C)\n'
                . 'w3c_validate::validate_from_url($url);\n'
                . '?>');
    }

    public static function websocket() {
        ?>
        <p> 
            Les WebSocket permettent de faire de la communication en temps réel (Real Time Connexion, RTC). <br />
            L'utilisation première des WebSocket est pour les tchats entre utilisateurs 
            mais ils peuvent aussi être utilisé pour notifier un utilisateur <br />
            ou afficher une donnée très variable dans le temps en temps réel (exemple : un stock dans une application de gestion)
        </p>
        <p>
            Un serveur de WebSocket tourne indépendamment du serveur web et écoute son propre port (9000 par défaut dans DWF, paramétrable dans la config du projet). <br />
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
        <p>Côté client, la connexion peut être géré avec l'objet <?= html_structures::a_link("https://javascript.info/websocket", "JS natif WebSocket", "", "", true) ?> </p>
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
            Les websockets de DWF fonctionnent avec l'envoi et la rêception de chaînes JSON. <br />
            dans les chaînes d'envoi vers le serveur une clé "action" est obligatoire afin d'indiquer au websocket quel traitement appliquer. <br />
            le reste des clés sont libres. <br />
            la seule action définie par défaut est l'action d'authentification qui prend en seconde clé un token d'authentification (qui peut être vide) :
        </p>
        <?php
        js::monaco_highlighter('{"action":"auth","token":""}');
        ?>
        <p>Le retour est une des ces possibilités :</p>
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
                . '     * @param array $message La chaine JSON déjà convertie en tableau\n'
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
            La classe <strong>websocket_request</strong> permet de lancer des requêtes au serveur websocket depuis PHP
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

    public static function wled() {
        ?>
        <p>Cette classe permet d'exploiter l'API HTTP de <a href="https://github.com/Aircoookie/WLED" target="_blank">WLED</a></p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//créé un objet WLED\n'
                . '$wled = new wled("192.168.1.10");\n'
                . '//change les couleur rouge, vert et bleu et envoie la requête a WLED\n'
                . '$wled->set_red(255)->set_green(255)->set_blue(255)->exec();\n'
                . '//redémarre WLED\n'
                . '$wled->sreboot()->exec();\n'
                . '?>');
    }

    public static function writer() {
        ?>
        <p>Cette classe permet de gérer un buffer à l'écriture de fichiers </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Ajoute un fichier au buffer\n'
                . 'writer::get_instance()->add($file, $content);' . '\n\n'
                . '//Vérifie si un fichier est dans le buffer\n'
                . 'writer::get_instance()->exist($file);' . '\n\n'
                . '//Retourne le contenu d\'un fichier du buffer (chaine vide si non)\n'
                . 'writer::get_instance()->content($file);' . '\n\n'
                . '//Retourne le nombre de fichiers dans le buffer\n'
                . 'writer::get_instance()->count();' . '\n\n'
                . '//Supprime un fichier du buffer\n'
                . 'writer::get_instance()->clear($file);' . '\n\n'
                . '//Supprime tout les fichiers du buffer\n'
                . 'writer::get_instance()->clear();' . '\n\n'
                . '//Ecrit les fichiers du buffer sur le disque dur (et vide le buffer)\n'
                . 'writer::get_instance()->write();' . '\n\n'
                . '//Ecrit les fichiers du buffer dans une archive (et vide le buffer)\n'
                . 'writer::get_instance()->write_zip($zipname);\n'
                . '?>'
        );
    }
}
