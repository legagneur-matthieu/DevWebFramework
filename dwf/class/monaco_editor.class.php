<?php

/**
 * Cette classe génère le monaco_editor.
 * /!\ cette classe est à placer précautionneusement dans une partie administration restreinte !
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 * @author BERNARD Rodolphe <mr.rodolphe.bernard@gmail.com>
 * @author ROLAND Michel <michel.roland.sio@gmail.com>
 */
class monaco_editor {

    /**
     * Permet de vérifier que la librairie monaco editor a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie monaco editor a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe génère le monaco_editor.
     * /!\ cette classe est à placer précautionneusement dans une partie administration restreinte !
     */
    public function __construct() {
        if (!self::$_called) {
            session::set_val('monaco_editor', true);
            if (!file_exists($sfile = "./services/s_monaco_editor.service.php")) {
                file_put_contents($sfile, file_get_contents(__DIR__ . "/monaco_editor_tpl/s_monaco_editor"));
            }
            echo html_structures::script('../commun/src/js/monaco/min/vs/loader.js') .
            html_structures::script("../commun/src/js/monaco_editor.js");
            compact_css::get_instance()->add_css_file("../commun/src/css/monaco_editor.css");
            export_dwf::add_files([realpath(__DIR__ . "/monaco_editor_tpl")]);
            self::$_called = true;
        }
        ?>
        <div class="row">
            <div class="col-3">
            </div>
            <div class="col-9">
                <button id="monaco_editor_save" class="btn btn-light">
                    <?= html_structures::glyphicon('floppy-disk') ?> Save
                </button>
                <button id="monaco_editor_format" class="btn btn-light">
                    <?= html_structures::glyphicon('indent-left') ?> Format
                </button>
            </div>
            <div class="col-3">
                <div id="monaco_files">
                    <?=
                    $this->files() .
                    html_structures::ul(
                            [
                                html_structures::a_link(
                                        '#addfile',
                                        html_structures::glyphicon('plus') .
                                        ' Nouveau fichier',
                                        'monaco_files_addfile btn btn-light'
                                ),
                                html_structures::a_link(
                                        '#adddir',
                                        html_structures::glyphicon('plus') .
                                        ' Nouveau dossier',
                                        'monaco_files_adddir btn btn-light'
                                ),
                                html_structures::a_link(
                                        '#rename',
                                        html_structures::glyphicon('edit') .
                                        ' Renomer',
                                        'monaco_files_rename btn btn-light'
                                ),
                                html_structures::a_link(
                                        '#supp',
                                        html_structures::glyphicon('remove') .
                                        ' Supprimer',
                                        'monaco_files_delete btn btn-light'
                                ),
                            ],
                            'monaco_files_contextmenu list-unstyled'
                    )
                    ?>
                </div>
            </div>
            <div class="col-9">
                <div id="monaco_editor"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Cette fonction permet de parcourir les fichiers.
     * @param string $path Chemin du dossier
     */
    private function files($path = '.') {
        $li = [];
        foreach (glob($path . '/*') as $file) {
            if (!strpos($file, 'src/compact') and !strpos($file, '.sqlite')) {
                if (is_dir($file)) {
                    $li[] = tags::tag(
                                    'a',
                                    [
                                        'href' => '#/',
                                        'data-monaco-id' => sha1("{$path}/{$file}"),
                                        'data-monaco-file' => $file,
                                        'data-monaco-ext' => 'DIR',
                                        'data-monaco-path' => $path,
                                        'data-monaco-fullpath' => $file,
                                    ],
                                    html_structures::bi('folder') .
                                    tags::tag(
                                            'span',
                                            ['class' => 'monaco_editor_files_name'],
                                            strtr($file, [$path . '/' => ' '])
                                    )
                    );
                    $li[] = $this->files($file);
                } else {
                    $file = strtr($file, [$path . '/' => '']);
                    $ext = explode('.', $file);
                    $ext = strtr(end($ext), ['sqlite' => 'sql', 'map' => 'js']);
                    $li[] = tags::tag(
                                    'a',
                                    [
                                        'href' => '#/',
                                        'data-monaco-id' => sha1("{$path}/{$file}"),
                                        'data-monaco-file' => $file,
                                        'data-monaco-ext' => $ext,
                                        'data-monaco-path' => $path,
                                        'data-monaco-fullpath' => "{$path}/{$file}",
                                    ],
                                    html_structures::bi('filetype-' . $ext) .
                                    tags::tag(
                                            'span',
                                            ['class' => 'monaco_editor_files_name'],
                                            $file
                                    )
                    );
                }
            }
        }
        return html_structures::ul($li, 'list-unstyled');
    }
}
