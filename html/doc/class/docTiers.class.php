<?php

class docTiers {

    public function __construct() {
        ?>
        <p>Vous trouverez ici la liste de toutes les librairies tiers utilisées par le framework.</p>
        <?php

        $licences = [
            0 => "MIT",
            1 => "GNU GPL",
            2 => "GNU LGPL",
            3 => "BSD",
            4 => "Apache",
            5 => "CeCILL",
            10 => "AUTRES (Copyleft, copyright particuliers, ...)"
        ];
        js::datatable();
        echo html_structures::table(["Libairie", "Version", "Auteur", "Langage", "Licence"], [
            ["Alertify", "0.3.11", "Fabien Doiron", "JS", $licences[0]],
            ["Animate", "3.6.0", "Daniel Eden", "JS", $licences[0]],
            ["Bootsrap", "3.3.7", "Twitter, Inc.", "CSS, JS", $licences[0]],
            ["Bootswatch", "3.3.7", "Thomas Park", "CSS", $licences[0]],
            ["Ckeditor", "4.9.2", "Frederico Knabben", "JS", $licences[1]],
            ["Cytoscape", "3.2.12", "The Cytoscape Consortium", "JS", $licences[10]],
            ["Datatable", "1.10.18", "SpryMedia Limited", "JS", $licences[10]],
            ["Datetimepicker", "1.6.3", "Trent Richardson", "JS", $licences[0]],
            ["Dompdf", "0.8.2", "Benj Carson", "PHP", $licences[2]],
            ["Dlcapi", "1.0.1", "JDTeam", "PHP", $licences[10]],
            ["elFinder", "2.1.38", "Studio 42", "JS, PHP", $licences[3]],
            ["Fancybox", "3.3.5", "fancyApps", "CSS, JS", $licences[1]],
            ["Filetypes.js", "1.0", "Mat Ryer", "JS", $licences[0]],
            ["Finediff", "0.6", "Raymond Hill", "PHP", $licences[0]],
            ["Flot", "1.1", "IOLA and Ole Laursen", "JS", $licences[0]],
            ["Freetile", "0.3.1", "Ioannis (Yannis) Chatzikonstantinou", "JS", $licences[10]],
            ["Fullcalendar", "3.9.0", "Adam Shaw", "JS", $licences[0]],
            ["JQuery, JQuery-UI", "1.12.1", "jQuery Foundation", "JS", $licences[0]],
            ["Jsencrypt.js", "2.3.1", "Form.io", "JS", $licences[0]],
            ["JSignature", "2.0", "Willowsystems", "JS", $licences[0]],
            ["Leaflet", "1.2.1", "Vladimir Agafonkin, CloudMade", "JS", $licences[10] . ", OSMF"],
            ["Modal", "1.0", "<em>Inconnu</em>", "JS", $licences[10]],
            ["PayPal-PHP-SDK", "1.13.0", "PAYPAL INC.", "PHP", $licences[10]],
            ["Php.js", "1.2", "Niklas von Hertzen", "JS", $licences[0]],
            ["Phpjs", "1.3.2", "Kevin van Zonneveld", "JS", $licences[0]],
            ["Php-graph-sdk", "5.6.1", "Facebook INC.", "PHP", $licences[10]],
            ["Phpmailer", "6.0.5", "Marcus Bointon, Jim Jagielski, Andy Prevost, Brent R. Matzelle", "PHP", $licences[2]],
            ["Phpqrcode", "1.1.4", "Dominik Dzienia", "PHP", $licences[2]],
            ["Phpseclib", "1.0", "TerraFrost, Jim Wigginton", "PHP", $licences[0]],
            ["Respond", "1.0", "Scott Jehl", "JS", $licences[0]],
            ["Reveal", "3.6.0", "Hakim El Hattab", "JS", $licences[0]],
            ["ReversoLib", "1.0", "Dyrk", "PHP", "<em>Aucune</em>"],
            ["Shuffleletters", "1.0", "Martin Angelov", "JS", $licences[0]],
            ["Smarty", "3.1.30", "Uwe Tews, Rodney Rehm", "PHP", $licences[2]],
            ["Stalactite", "0.1", "Jono Brandel", "JS", $licences[4]],
            ["syntaxhighlighter", "3.0.83", "Alex Gorbatchev", "JS", $licences[0] . ', ' . $licences[1]],
            ["Videojs", "6.8.0", "Brightcove, Inc.", "JS", $licences[4]],
            ["Vticker", "1.0", "Tadas Juozapaitis", "JS", "<em>Aucune</em>"],
            ["Xhtml-formatter", "1.0", "Vladimír Macháček", "PHP", $licences[3]],
                ], "", "datatable") . html_structures::hr();
        ?>
        <p>Du au fait que certaines librairies tiers soient sous <a href="http://www.gnu.org/licenses/gpl.html">licence GNU GPL</a>, le DevWebFramework est diffusé sous la même licence. <br />
            L'auteur vous remercie pour tous crédits que vous porterez à son travail.
        </p>
        <?php

    }

}
