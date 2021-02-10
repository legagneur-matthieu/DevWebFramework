<?php

/**
 * Cette class permet de génerer un modal
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class modal {

    /**
     * Permet de vérifier que la librairie modal a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie modal a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Initialise tout ce qui est nécessaire pour la création de modals
     */
    public function __construct() {
        if (!self::$_called) {
            compact_css::get_instance()->add_css_file("../commun/src/js/modal/modal-window.css");
            echo html_structures::script("../commun/src/js/modal/modal-window.js") .
            tags::tag("div", ["role" => "dialog", "aria-hidden" => "true", "id" => "modal", "class" => "modal-content", "style" => "display: none; z-index:1001;"], tags::tag(
                            "div", ["style" => "max-height: 100%; overflow: auto;"], "") .
                    tags::tag("button", ["id" => "modalCloseButton", "class" => "modalCloseButton btn btn-secondary", "title" => "Fermer la fenêtre"], html_structures::glyphicon("remove"))
            ) .
            tags::tag("div", ["tabindex" => "-1", "id" => "modalOverlay", "style" => "display: none; z-index:1000;"], "");
            self::$_called = true;
        }
    }

    /**
     * Créé un lien avec les données que contiendra le modal
     * @param string $a_text Texte du lien
     * @param string $id Identifiant unique du lien
     * @param string $title Title du lien
     * @param string $titre Titre du modal
     * @param string $data HTML du modal
     * @param string $class class CSS
     */
    public function link_open_modal($a_text, $id, $title, $titre, $data, $class) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("a#<?= $id; ?>").click(function () {
                    $("#modal>div").html(stripslashes("<h1>" + $(this).attr("data-titre")) + "</h1><hr />" + base64_decode($(this).attr("data-data")));
                    showModal($('#modal'));
                });
            });
        </script>
        <?php
        return tags::tag("a", ["href" => "#" . $id, "id" => $id, "class" => $class, "data-titre" => addslashes($titre), "data-data" => base64_encode($data), "title" => $title], $a_text);
    }

}
