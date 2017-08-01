<?php

class docTiers {

    public function __construct() {
        $licences = array(
            0 => "MIT",
            1 => "GNU GPL",
            2 => "GNU LGPL",
            3 => "BSD",
            4 => "Apache",
            5 => "CeCILL",
            10 => "AUTRES (Copyleft, copyright particuliers, ...)"
        );
        $lib = array(
            array("Alertify", "Fabien Doiron", "JS", $licences[0]),
            array("Animate", "Daniel Eden", "JS", $licences[0]),
            array("Bootsrap", "Twitter, Inc.", "CSS, JS", $licences[0]),
            array("Bootswatch", "Thomas Park", "CSS", $licences[0]),
            array("Ckeditor", "Frederico Knabben", "JS", $licences[1]),
            array("Cytoscape", "The Cytoscape Consortium", "JS", $licences[10]),
            array("Datatable", "SpryMedia Limited", "JS", $licences[10]),
            array("datetimepicker", "Trent Richardson", "JS", $licences[0]),
            array("Dompdf", "Benj Carson", "PHP", $licences[2]),
            array("elFinder", "Studio 42", "JS, PHP", $licences[3]),
            array("filetypes.js", "Mat Ryer", "JS", $licences[0]),
            array("flot", "IOLA and Ole Laursen", "JS", $licences[0]),
            array("Freetile", "Ioannis (Yannis) Chatzikonstantinou", "JS", $licences[10]),
            array("Fullcalendar", "Adam Shaw", "JS", $licences[0]),
            array("Graphique", "Cyril MAGUIRE", "PHP", $licences[5]),
            array("Html2pdf", "Laurent MINGUET", "PHP", $licences[2]),
            array("JQuery, JQuery-UI", "jQuery Foundation", "JS", $licences[0]),
            array("Jsencrypt.js", "Form.io", "JS", $licences[0]),
            array("Leaflet", "Vladimir Agafonkin, CloudMade", "JS", $licences[10] . ", OSMF"),
            array("Modal", "<em>Inconnu</em>", "JS", $licences[10]),
            array("Php.js", "Niklas von Hertzen", "JS", $licences[0]),
            array("Phpjs", "Kevin van Zonneveld", "JS", $licences[0]),
            array("Phpmailer", "Marcus Bointon, Jim Jagielski, Andy Prevost, Brent R. Matzelle", "PHP", $licences[2]),
            array("Phpqrcode", "Dominik Dzienia", "PHP", $licences[2]),
            array("Phpseclib", "TerraFrost, Jim Wigginton", "PHP", $licences[0]),
            array("Respond", "Scott Jehl", "JS", $licences[0]),
            array("Reveal", "Hakim El Hattab", "JS", $licences[0]),
            array("Shuffleletters", "Martin Angelov", "JS", $licences[0]),
            array("Stalactite", "Jono Brandel", "JS", $licences[4]),
            array("syntaxhighlighter", "Alex Gorbatchev", "JS", $licences[0] . ', ' . $licences[1]),
            array("Videojs", "Brightcove, Inc.", "JS", $licences[4]),
            array("Vticker", "Tadas Juozapaitis", "JS", "<em>Aucune</em>"),
        );
        ?>
        <p>Vous trouverez ici la liste de toutes les librairies tiers utilisées par le framework.</p>
        <?php
        js::datatable();
        echo html_structures::table(array("Libairie", "Auteur", "Langage", "Licence"), $lib, "", "datatable") . html_structures::hr();
        ?>
        <p>Du au fait que certaines librairies tiers soient sous <a href="http://www.gnu.org/licenses/gpl.html">licence GNU GPL</a>, le DevWebFramework est diffusé sous la même licence. <br />
            L'auteur vous remercie pour tous les crédits que vous porterez à son travail.
        </p>
        <?php

    }

}
