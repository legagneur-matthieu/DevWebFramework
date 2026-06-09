<?php

/**
 * Cette classe affiche du code dans un monaco editor non modifiable
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class monaco_highlighter {

    /**
     * Permet de vérifier que la librairie monaco editor a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie monaco editor a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Numéro d'identifiant du monaco_highlighter
     * @var int Numéro d'identifiant du monaco_highlighter
     */
    private static $_id = 0;

    /**
     * Cette classe affiche du code dans un monaco editor non modifiable
     * @param string $code Code a afficher
     * @param string $language Langage de programation (php, js, csharp, python, ...)
     */
    public function __construct($code, $language = "php") {
        if (!self::$_called) {
            echo html_structures::script('../commun/src/js/monaco/min/vs/loader.js');
            self::$_called = true;
            ?>
            <script nonce="<?= csp::get_nonce() ?>">
                require.config({paths: {vs: '../commun/src/js/monaco/min/vs'}});
                function create_editor(code, language = "php", id) {
                    let editor = monaco.editor.create(document.getElementById(id), {
                        value: code,
                        language: language,
                        autoIndent: true,
                        formatOnPaste: true,
                        formatOnType: true,
                        readOnly: true,
                        automaticLayout: true,
                        scrollBeyondLastLine: false,
                        scrollbar: {
                            vertical: 'hidden'
                        }
                    });
                    editor.onDidChangeModelContent(function () {
                        adjust_editor_height(id, editor)
                    });
                    adjust_editor_height(id, editor);
                }
                function adjust_editor_height(id, editor) {
                    var lineHeight = editor.getOption(monaco.editor.EditorOption.lineHeight);
                    var lineCount = editor.getModel().getLineCount();
                    var newHeight = lineHeight * lineCount + 5;
                    document.getElementById(id).style.height = newHeight + 'px';
                    editor.layout();
                }
            </script>
            <?php
        }
        ?>
        <div id="monaco_highlighter_<?= self::$_id; ?>"></div>
        <script nonce="<?= csp::get_nonce() ?>">
            require(['vs/editor/editor.main'], function () {
                create_editor("<?= strtr(htmlspecialchars_decode($code), ['"' => '\"']) ?>", "<?= $language ?>", "monaco_highlighter_<?= self::$_id; ?>");
            });
        </script>
        <?php
        self::$_id++;
    }
}
