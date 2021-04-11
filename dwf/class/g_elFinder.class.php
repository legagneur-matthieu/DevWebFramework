<?php

/**
 * Cette classe affiche le gestionnaire de fichiers elFinder
 * Il n'est pas recommandé de mettre deux instances de cette classe dans une même page
 * Pour autoriser un utilisateur à utiliser elFinder, vous devrez utiliser session::set_val("elFinder", true);
 * Cf le fichier connector.php (généré par cette classe) pour plus de détails et options)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class g_elFinder {

    /**
     * Cette classe affiche le gestionnaire de fichiers elFinder
     * Il n'est pas recommandé de mettre deux instances de cette classe dans une même page
     * Pour autoriser un utilisateur à utiliser elFinder, vous devrez utiliser session::set_val("elFinder", true);
     * Cf le fichier connector.php (généré par cette classe) pour plus de détails et options)
     * 
     * @param string $conector_url URL du connecteur ( il est recommandé de laisser par defaut )
     */
    public function __construct($conector_url = "connector.php") {
        echo html_structures::link_in_body("../commun/src/js/elFinder/css/elfinder.min.css") .
        html_structures::script("../commun/src/js/elFinder/js/elfinder.min.js");
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#elfinder').elfinder({
                    lang: 'fr',
                    url: '<?= $conector_url; ?>'
                }).elfinder('instance');
            })
        </script>
        <?php
        echo tags::tag("div", ["id" => "elfinder"], "");
        if (!file_exists($conector_url)) {
            $this->create_connector($conector_url);
        }
    }

    /**
     * Créé le connecteur
     * @param string $conector_url URL du connecteur ( il est recommandé de laisser par defaut )
     */
    private function create_connector($connector_url) {
        $file = "<?php
/*
* CF https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
*/
class elFinder_connector {
    public function __construct() {
        $" . "this->classloader();
        session::start(false);
        if (session::get_val('elFinder')) {
            $" . "opts = [
                'roots' => [
                    [
                        'driver' => 'LocalFileSystem',
                        'path' => 'files/',
                        'URL' => 'files/',
                        'uploadDeny' => ['all'],
                        'uploadAllow' => [
                            'image', 'audio', 'video',
                            'application/json', 'application/pdf', 'application/rtf', 'application/x-7z-compressed', 'application/x-latex', 'application/xml', 'application/zip',
                            'text/css', 'text/csv', 'text/plain',
                        ],
                        'uploadOrder' => ['deny', 'allow'],
                        'accessControl' => 'access'
                    ]
                ]
            ];
            $" . "connector = new elFinderConnector(new elFinder($" . "opts));
            $" . "connector->run();
        }
    }
    private function classloader() {
        spl_autoload_register(function($" . "class){
            if(file_exists($" . "filename=__DIR__ . '/../../dwf/class/'.$" . "class.'.class.php')){
                include_once $" . "filename;
            }
        });
        include __DIR__ . '/class/config.class.php';
        include __DIR__ . '/../../dwf/class/elFinder/autoload.php';
    }
}
new elFinder_connector();
";
        file_put_contents($connector_url, $file);
        @mkdir("files");
    }

}
