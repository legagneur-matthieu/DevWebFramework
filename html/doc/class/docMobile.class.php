<?php

class docMobile {

    public function __construct() {
        $doc = array(
            "introduction",
            "debuter",
            "heritage_du_framework_php"
        );
        js::accordion("accordion", true, true);
        ?>
        <style type="text/css">
            h4{
                text-decoration: grey underline;
                font-weight: bold;
            }

        </style>
        <h2>Framework Mobile</h2>
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
        <div class="alert alert-warning">
            <p>
                <strong>Note du développeur</strong> <br />
                La version cordova du framework n'est plus maintenue depuis 2018 par manque de temps. <br />
                Mais il n'est pas abandonné ! Une mise à niveau sera faite dès que cela sera possible.
            </p>
        </div>
        <p>
            Le framework mobile est un framework JS conçu pour être utilisé dans une application <a href="https://cordova.apache.org/" target="_blank">Cordova</a> <br />
            contrairement au framework PHP, le framework JS ne peut pas servir à créer plusieurs projets, chaque application devra avoir sa copie du framework.
        </p>
        <h4>Prérequis</h4>
        <ul>
            <li>Connaitre Javascript et JQuery</li>
            <li>Connaitre le principe de "Single Page Application" (SPA)</li>
            <li>Savoir comment configurer correctement Cordova</li>
            <li>Connaitre le principe de prototype (objet) en JS (pas obligatoire mais c'est un plus !)</li>
        </ul>
        <h4>Structure</h4>
        <p>La structure du Framework JS est très simple</p>
        <?php
        $dir_glyph = html_structures::glyphicon("folder-open", "");
        $arrow_glyph = html_structures::glyphicon("arrow-right", "");
        $file_glyph = html_structures::glyphicon("file", "");
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
            <li><?php echo $dir_glyph; ?> src
                <ul>
                    <li><?php echo $arrow_glyph; ?> <em>Contient l'ensemble des librairie JS intégrées</em></li>
                    <li><?php echo $dir_glyph; ?> dwf
                        <ul>
                            <li><?php echo $arrow_glyph; ?> <em>Contient des "prototypes" du framework</em></li>
                        </ul>
                    </li>
                    <li><?php echo $file_glyph; ?> dwf.js
                        <ul>
                            <li><?php echo $arrow_glyph; ?> <em>Contient le "prototype" principal du framework</em></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><?php echo $file_glyph; ?> index.html
                <ul>
                    <li><?php echo $arrow_glyph; ?> <em>Comme pour tout projet Cordova</em></li>
                </ul>
            </li>
        </ul>
        <p>
            A noter que les lignes suivantes sont nécessaires au bon fonctionnement de Cordova, <br />
            ces fichiers sont générés à la compilation de l'application, ne créez pas ces fichiers  !
        </p>
        <?php
        js::syntaxhighlighter('<script type="text/javascript" src="cordova.js"></script>' . "\n"
                . '<script type="text/javascript" src="js/index.js"></script>', "html");
    }

    private function debuter() {
        ?>
        <p>
            Le framework mobile vous offre une quantité non négligeable de méthodes pour vous aider dans le développement de votre application <br />
            ces méthodes sont accessibles via <em>$dwf</em>
        </p>
        <h4>Appel d'un script JS</h4>
        <p>Pour débuter, vous pouvez créer vos propres fichiers de fonctions/prototypes JS/JQuery. Pour les ajouter, deux méthodes s'offrent à vous :</p>
        <ol>
            <li>Ajoutez-les directement dans le head du fichier index.html, le fichier sera donc toujours chargé au démarrage de l'application</li>
            <li>Utilisez <em>$dwf.include_script()</em> pour inclure un fichier JS,<br />
                cette méthode permet de contextualiser l'appel du fichier <br />
                pour ne pas avoir à le charger si l'utilisateur n'en a pas l'utilité dans son contexte d'utilisation</li>
        </ol>
        <p>Exemple d'utilisation dans le fichier index.html</p>
        <?php
        js::syntaxhighlighter("<script type=\"text/javascript\">\n"
                . "    $(document).ready(function () {\n\n"
                . "        my_proto_called = false; //variable à true si le script a déja été appelé\n\n"
                . "        function utilise_dans_un_contexte(){\n"
                . "            if(!my_proto_called){ //on verifie si le script n'a pas déjà été appelé\n"
                . "                $" . "dwf.include_script(\"my_scripts_dir/my_proto.js\"); //on inclue my_proto.js\n"
                . "                my_proto_called = true; //on passe la variable à true\n"
                . "            }\n"
                . "            //suite d'instructions\n"
                . "        }\n"
                . "    });\n"
                . "</script>", "js; html-script: true");
        ?>
        <h4>Vérifier la connexion réseau du téléphone</h4>
        <p>
            Admettons que votre application aura obligatoirement besoin d'une connexion internet (wifi ou 3G/4G), vous pouvez effectuer une vérification grâce à <br />
            <em>$dwf.cordova().is_connected_web();</em> qui retourne true si l'appareil est connecté, sinon false;

        </p>
        <h4>les formulaires avec $dwf</h4>
        <p>Le framework permet de gérer les formulaires en toute simplicité, en 6 étapes</p>
        <ol>
            <li>Création de la balise form avec jquery.html()</li>
            <li>Récupération du proto formulaire avec $dwf.form();</li>
            <li>Ajout des champs du formulaire avec
                <ul>
                    <li>$dwf.form().input()</li>
                    <li>$dwf.form().select()</li>
                    <li>$dwf.form().radio()</li>
                    <li>$dwf.form().checkbox()</li>
                    <li>$dwf.form().datepicker()</li>
                    <li>$dwf.form().submit()</li>
                    <li>...</li>
                </ul>
                Les champs seront stylisés avec bootstrap
            </li>
            <li>Soumission du formulaire avec jquery.submit()</li>
            <li>Récupération des saisies du formulaire avec $dwf.get_post() (retourne un tableau associatif nommé $_post, comme en PHP mais en minuscule)</li>
            <li>Utilisation des données pour localStorage ou requête Ajax</li>
        </ol>
        <?php
        js::syntaxhighlighter("$(document).ready(function () {\n"
                . "    $(\"body\").html('<form action=\"#\" id=\"mon_form\"></form>'); //étape 1 création de form#mon_form\n\n"
                . "    mon_form = $" . "dwf.form(\"mon_form\"); //étape 2 \n\n"
                . "    mon_form.input(\"Label de l'input\", \"name_input\"); //étape 3\n"
                . "    mon_form.select(\"Label du select\", \"name_select\", [\n"
                . "        [\"value\", \"text\"],\n"
                . "        [1, \"oui\"],\n"
                . "        [2, \"non\"]\n"
                . "    ]);\n"
                . "    mon_form.submit(\"btn-primary\", \"Valider\"); //ajout du bouton de soumission\n\n"
                . "    $(\"#mon_form\").submit(function (e) { //etape 4\n"
                . "        e.preventDefault(); //évite le rafraichissement du formulaire\n"
                . "        mon_form.get_post(); //étape 5\n\n"
                . "        //utilisation d'une saisie formulaire dans localStorage\n"
                . "        localStorage.setItem(\"clé\", $" . "_post[\"name_input\"]);\n"
                . "        //utilisation d'une saisie formulaire dans une requête ajax\n"
                . "        $.post(\"http://url.fr/\", {data: $" . "_post[\"name_input\"]}, function (data) {\n"
                . "            //réponse de la requête\n"
                . "        });\n"
                . "    });\n"
                . "});");
    }

    private function heritage_du_framework_php() {
        ?>
        <p>
            Le framework mobile est apparu après le framework PHP, <br />
            il est donc évident que le framework mobile s'inspire, voir même retranscrive, du code du framework PHP <br />
            au-delà des prototypes "form", "math", "time" et "trad" qui sont de retranscription du code PHP vers JS. <br />
            Et le prototype "Cordova" qui est une spécificité, le framework mobile reprend principalement la classe "js" du framework PHP. <br />
            ainsi la fonction PHP <em>js::datatable()</em> existe sous la forme JS <em>$dwf.datatable()</em> <br />
            pour plus d'informations, voir la documentation technique.
        </p>
        <?php
    }

}
