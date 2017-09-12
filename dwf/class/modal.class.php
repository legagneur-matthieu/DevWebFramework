<?php

/**
 * Cette class permet de g�nerer un modal
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class modal {

    /**
     * Permet de v�rifier que la librairie modal a bien �t� appel�e qu'une fois.
     * @var boolean Permet de v�rifier que la librairie modal a bien �t� appel�e qu'une fois.
     */
    private static $_called = false;

    /**
     * Initialise tout ce qui est n�cessaire pour la cr�ation de modals
     */
    public function __construct() {
        if (!self::$_called) {
            echo html_structures::link_in_body("../commun/src/js/modal/modal-window.css");
            ?>
            <script type="text/javascript" src="../commun/src/js/modal/modal-window.js"></script>
            <div role="dialog" aria-hidden="true" id="modal" class="modal-content" style="display: none;">
                <div style="max-height: 100%; overflow: auto;"></div>
                <button id="modalCloseButton" class="modalCloseButton btn btn-default" title="Fermer la fen�tre"><span class="glyphicon glyphicon-remove"></span></button>
            </div>
            <div tabindex="-1" id="modalOverlay" style="display: none;"></div>
            <?php
            self::$_called = true;
        }
    }

    /**
     * Cr�� un lien avec les donn�es que contiendra le modal
     * @param string $a_text Texte du lien
     * @param string $id identifiant unique du lien
     * @param string $title Title du lien
     * @param string $titre Titre du modal
     * @param string $data HTML du modal
     * @param string $class class CSS
     */
    public function link_open_modal($a_text, $id, $title, $titre, $data, $class) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("a#<?php echo $id; ?>").click(function () {
                    $("#modal>div").html(stripslashes("<h1>" + $(this).attr("data-titre")) + "</h1><hr />" + base64_decode($(this).attr("data-data")));
                    showModal($('#modal'));
                });
            });
        </script>
        <a href="#<?php echo $id; ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>" data-titre="<?php echo addslashes($titre); ?>" data-data='<?php echo base64_encode($data); ?>' title="<?php echo $title; ?>"><?php echo $a_text; ?></a>
        <?php
    }

}
