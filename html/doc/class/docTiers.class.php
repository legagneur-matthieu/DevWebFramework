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
        echo html_structures::table(["Libairie", "Auteur", "Langage", "Licence"], [
            ["Alertify", "Fabien Doiron", "JS", $licences[0]],
            ["Animate", "Daniel Eden", "JS", $licences[0]],
            ["Bootsrap", "Twitter, Inc.", "CSS, JS", $licences[0]],
            ["Bootswatch", "Thomas Park", "CSS", $licences[0]],
            ["Ckeditor", "Frederico Knabben", "JS", $licences[1]],
            ["Cytoscape", "The Cytoscape Consortium", "JS", $licences[10]],
            ["Datatable", "SpryMedia Limited", "JS", $licences[10]],
            ["Datetimepicker", "Trent Richardson", "JS", $licences[0]],
            ["Dompdf", "Benj Carson", "PHP", $licences[2]],
            ["Dlcapi", "JDTeam", "PHP", $licences[10]],
            ["elFinder", "Studio 42", "JS, PHP", $licences[3]],
            ["Fancybox", "fancyApps", "CSS, JS", $licences[1]],
            ["Filetypes.js", "Mat Ryer", "JS", $licences[0]],
            ["Finediff", "Raymond Hill", "PHP", $licences[0]],
            ["Flot", "IOLA and Ole Laursen", "JS", $licences[0]],
            ["Freetile", "Ioannis (Yannis) Chatzikonstantinou", "JS", $licences[10]],
            ["Fullcalendar", "Adam Shaw", "JS", $licences[0]],
            ["Html2pdf", "Laurent MINGUET", "PHP", $licences[2]],
            ["JQuery, JQuery-UI", "jQuery Foundation", "JS", $licences[0]],
            ["Jsencrypt.js", "Form.io", "JS", $licences[0]],
            ["Leaflet", "Vladimir Agafonkin, CloudMade", "JS", $licences[10] . ", OSMF"],
            ["Modal", "<em>Inconnu</em>", "JS", $licences[10]],
            ["PayPal-PHP-SDK", "PAYPAL INC.", "PHP", $licences[10]],
            ["Php.js", "Niklas von Hertzen", "JS", $licences[0]],
            ["Phpjs", "Kevin van Zonneveld", "JS", $licences[0]],
            ["Php-graph-sdk", "Facebook INC.", "PHP", $licences[10]],
            ["Phpmailer", "Marcus Bointon, Jim Jagielski, Andy Prevost, Brent R. Matzelle", "PHP", $licences[2]],
            ["Phpqrcode", "Dominik Dzienia", "PHP", $licences[2]],
            ["Phpseclib", "TerraFrost, Jim Wigginton", "PHP", $licences[0]],
            ["Respond", "Scott Jehl", "JS", $licences[0]],
            ["Reveal", "Hakim El Hattab", "JS", $licences[0]],
            ["ReversoLib", "Dyrk", "PHP", "<em>Aucune</em>"],
            ["Shuffleletters", "Martin Angelov", "JS", $licences[0]],
            ["Smarty", "Uwe Tews, Rodney Rehm", "PHP", $licences[2]],
            ["Stalactite", "Jono Brandel", "JS", $licences[4]],
            ["syntaxhighlighter", "Alex Gorbatchev", "JS", $licences[0] . ', ' . $licences[1]],
            ["Videojs", "Brightcove, Inc.", "JS", $licences[4]],
            ["Vticker", "Tadas Juozapaitis", "JS", "<em>Aucune</em>"],
                ], "", "datatable") . html_structures::hr();
        ?>
        <p>Du au fait que certaines librairies tiers soient sous <a href="http://www.gnu.org/licenses/gpl.html">licence GNU GPL</a>, le DevWebFramework est diffusé sous la même licence. <br />
            L'auteur vous remercie pour tous crédits que vous porterez à son travail.
        </p>
        <?php

    }

}
