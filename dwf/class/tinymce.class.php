<?php

/**
 * Cette classe permet d'appliquer l'éditeur TinyMCE (WYSIWYG) à un textarea
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class tinymce {

    /**
     * Permet de vérifier que la librairie TinyMCE a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie TinyMCE a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Paramètres par défaut du TinyMCE
     * 
     * @var array Paramètres par défaut du TinyMCE
     */
    private $_id;

    /**
     * Cette classe permet d'appliquer l'éditeur TinyMCE (WYSIWYG) à un textarea 
     * 
     * @param array $id Id du textarea
     * @param array $params Surcharge les paramètres à appliquer au TinyMCE ( laissez par défaut ou voir la documentation )
     */
    public function __construct($id) {
        if (!self::$_called) {
            echo html_structures::script("../commun/src/js/tinymce/tinymce.min.js");
            export_dwf::add_files([realpath("../commun/src/js/tinymce")]);
            self::$_called = true;
        }
        $this->_id = $id;
        ?>
        <script type="text/javascript" nonce="<?= csp::get_nonce() ?>">
            $(document).ready(function () {
                tinymce.init({
                    selector: "#<?= $this->_id ?>",
                    language: "fr_FR",
                    plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
                    menubar: 'file edit view insert format tools table help',
                    toolbar: "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
                    powerpaste_allow_local_images: true,
                    autosave_ask_before_unload: true,
                    autosave_interval: '30s',
                    autosave_prefix: '{path}{query}-{id}-',
                    autosave_restore_when_empty: false,
                    autosave_retention: '2m',
                    image_advtab: true,
                    image_caption: true,
                    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                    toolbar_mode: 'sliding',
                    contextmenu: 'link image table',
                    setup: function (editor) {
                        editor.on('change', function () {
                            tinymce.triggerSave();
                        });
                    }
                });
                $("#<?= $this->_id; ?>").parents("form").css("width", "100%");
            });
        </script>
        <?php
    }

    /**
     * Filtre de sécurité à utiliser lors de l'exécution d'un formulaire pour filter les balises utilisées dans TinyMCE ( protection XSS )
     * 
     * @param string $more_tags Balises supplémentaires à autoriser (format strip_tags : "<mark></mark>"), transmises à html_sanitizer::clean()
     */
    public function parse($more_tags = "") {
        $str = $_POST[$this->_id];
        return html_sanitizer::clean($str, $more_tags);
    }
}
