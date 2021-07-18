<?php

class docTiers {

    public function __construct() {
        $licences = [
            0 => "MIT",
            1 => "GNU GPL",
            2 => "GNU LGPL",
            3 => "BSD",
            4 => "Apache",
            5 => "CeCILL",
            10 => "AUTRES (Copyleft, copyright particuliers, ...)"
        ];
        js::datatable("datatable", ["pageLength" => 50]);
        echo tags::tag("p", [], "Vous trouverez ici la liste de toutes les librairies tierces utilisées par le framework.") .
        html_structures::table(["Librairie", "Version", "Auteur", "Langage", "Licence"], [
            [html_structures::a_link("https://alertifyjs.com", "Alertify", "", "", true), "1.13.1", "Fabien Doiron", "JS", $licences[0]],
            [html_structures::a_link("https://animate.style", "Animate", "", "", true), "4.1.1", "Daniel Eden", "JS", $licences[0]],
            [html_structures::a_link("https://getbootstrap.com", "Bootsrap", "", "", true), "5.0.2", "Twitter, Inc.", "CSS, JS", $licences[0]],
            [html_structures::a_link("https://icons.getbootstrap.com", "Bootsrap Icons", "", "", true), "1.5.0", "Twitter, Inc.", "CSS", $licences[0]],
            [html_structures::a_link("https://bootswatch.com", "Bootswatch", "", "", true), "5.0.2", "Thomas Park", "CSS", $licences[0]],
            [html_structures::a_link("https://ckeditor.com/ckeditor-4/", "Ckeditor", "", "", true), "4.16.1", "Frederico Knabben", "JS", $licences[1]],
            [html_structures::a_link("http://csstidy.sourceforge.net", "CSSTidy", "", "", true), "1.7.3", "Cedric Morin", "PHP", $licences[2]],
            [html_structures::a_link("https://js.cytoscape.org", "Cytoscape", "", "", true), "3.19.0", "The Cytoscape Consortium", "JS", $licences[10]],
            [html_structures::a_link("https://www.datatables.net", "Datatable", "", "", true), "1.10.25", "SpryMedia Limited", "JS", $licences[10]],
            [html_structures::a_link("https://github.com/trentrichardson/jQuery-Timepicker-Addon", "Datetimepicker", "", "", true), "1.6.3", "Trent Richardson", "JS", $licences[0]],
            [html_structures::a_link("https://github.com/dompdf/dompdf", "Dompdf", "", "", true), "1.0.2", "Benj Carson", "PHP", $licences[2]],
            [html_structures::a_link("https://jdownloader.org/knowledge/wiki/linkprotection/container/dlcapi", "Dlcapi", "", "", true), "1.0.1", "JDTeam", "PHP", $licences[10]],
            [html_structures::a_link("https://studio-42.github.io/elFinder", "elFinder", "", "", true), "2.1.59", "Studio 42", "JS, PHP", $licences[3]],
            [html_structures::a_link("https://fancyapps.com/fancybox/3", "Fancybox", "", "", true), "3.5.7", "fancyApps", "CSS, JS", $licences[1]],
            [html_structures::a_link("https://github.com/stretchr/filetypes.js", "Filetypes.js", "", "", true), "1.0", "Mat Ryer", "JS", $licences[0]],
            [html_structures::a_link("http://www.raymondhill.net/finediff/viewdiff-ex.php", "Finediff", "", "", true), "0.6", "Raymond Hill", "PHP", $licences[0]],
            [html_structures::a_link("http://www.flotcharts.org", "Flot", "", "", true), "4.2.2", "IOLA and Ole Laursen", "JS", $licences[0]],
            [html_structures::a_link("https://github.com/yconst/Freetile", "Freetile", "", "", true), "0.3.1", "Ioannis (Yannis) Chatzikonstantinou", "JS", $licences[10]],
            [html_structures::a_link("https://fullcalendar.io", "Fullcalendar", "", "", true), "5.8.0", "Adam Shaw", "JS", $licences[0]],
            [html_structures::a_link("https://jquery.com", "JQuery, JQuery-UI", "", "", true), "1.12.1", "jQuery Foundation", "JS", $licences[0]],
            [html_structures::a_link("https://willowsystems.github.io/jSignature", "JSignature", "", "", true), "2.0", "Willowsystems", "JS", $licences[0]],
            [html_structures::a_link("https://github.com/cozmo/jsQR", "jsQR", "", "", true), "2021.04", "Cozmo", "JS", $licences[4]],
            [html_structures::a_link("https://leafletjs.com", "Leaflet", "", "", true), "1.7.1", "Vladimir Agafonkin, CloudMade", "JS", $licences[10] . ", OSMF"],
            [html_structures::a_link("#", "Modal", "", "", true), "1.0", "<em>Inconnu</em>", "JS", $licences[10]],
            [html_structures::a_link("http://paypal.github.io/PayPal-PHP-SDK/", "PayPal-PHP-SDK", "", "", true), "1.14.0", "PAYPAL INC.", "PHP", $licences[10]],
            [html_structures::a_link("https://phpjs.hertzen.com/", "Php.js", "", "", true), "1.2", "Niklas von Hertzen", "JS", $licences[0]],
            [html_structures::a_link("https://locutus.io/php/", "Phpjs", "", "", true), "1.3.2", "Kevin van Zonneveld, Locutus", "JS", $licences[0]],
            [html_structures::a_link("https://github.com/facebookarchive/php-graph-sdk", "Php-graph-sdk", "", "", true), "5.7.0", "Facebook INC.", "PHP", $licences[10]],
            [html_structures::a_link("https://github.com/PHPMailer/PHPMailer", "Phpmailer", "", "", true), "6.5.0", "Marcus Bointon, Jim Jagielski, Andy Prevost, Brent R. Matzelle", "PHP", $licences[2]],
            [html_structures::a_link("http://phpqrcode.sourceforge.net/", "Phpqrcode", "", "", true), "1.1.4", "Dominik Dzienia", "PHP", $licences[2]],
            [html_structures::a_link("https://github.com/scottjehl/Respond", "Respond", "", "", true), "1.4.2", "Scott Jehl", "JS", $licences[0]],
            [html_structures::a_link("https://revealjs.com/", "Reveal", "", "", true), "4.2.1", "Hakim El Hattab", "JS", $licences[0]],
            [html_structures::a_link("#", "ReversoLib", "", "", true), "1.0", "Dyrk", "PHP", "<em>Aucune</em>"],
            [html_structures::a_link("https://tutorialzine.com/2011/09/shuffle-letters-effect-jquery", "Shuffleletters", "", "", true), "1.0", "Martin Angelov", "JS", $licences[0]],
            [html_structures::a_link("https://github.com/christopherwk210/SimpleParallax", "SimpleParallax", "", "", true), "0.1", "Chris Anselmo (christopherwk210)", "JS", $licences[0]],
            [html_structures::a_link("https://www.smarty.net", "Smarty", "", "", true), "3.1.39", "Uwe Tews, Rodney Rehm", "PHP", $licences[2]],
            [html_structures::a_link("https://jonobr1.com/stalactite/", "Stalactite", "", "", true), "0.1", "Jono Brandel", "JS", $licences[4]],
            [html_structures::a_link("https://github.com/syntaxhighlighter/syntaxhighlighter", "syntaxhighlighter", "", "", true), "3.0.83", "Alex Gorbatchev", "JS", $licences[0] . ', ' . $licences[1]],
            [html_structures::a_link("https://videojs.com/", "Videojs", "", "", true), "7.13.3", "Brightcove, Inc.", "JS", $licences[4]],
            [html_structures::a_link("http://richhollis.github.io/vticker/", "Vticker", "", "", true), "1.21", "Richard Hollis", "JS", $licences[0] . ", " . $licences[3]],
            [html_structures::a_link("https://github.com/Machy8/xhtml-formatter", "Xhtml-formatter", "", "", true), "1.0", "Vladimír Macháček", "PHP", $licences[3]],
                ], "", "datatable") . html_structures::hr() .
        tags::tag("p", [], "Dû au fait que certaines librairies tierces soient sous " .
                html_structures::a_link("http://www.gnu.org/licenses/gpl.html", "licence GNU GPL") .
                "le DevWebFramework est diffusé sous la même licence. " . "<br />" .
                "L'auteur vous remercie pour tous les crédits que vous porterez à son travail.");
    }

}
