<?php

/**
 * Cette classe affiche le gestionaire de fichiers elFinder
 * Il n'est pas recommandé de mettre deux instances de cette classe dans une même page
 * Pour autoriser un utilisateur à utiliser elFinder, vous devrez utiliser session::set_val("elFinder", true);
 * Cf le fichier connector.php (généré par cette classe) pour plus de détails et options)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class g_elFinder {

    /**
     * Cette classe affiche le gestionaire de fichiers elFinder
     * Il n'est pas recommandé de mettre deux instances de cette classe dans une même page
     * Pour autoriser un utilisateur à utiliser elFinder, vous devrez utiliser session::set_val("elFinder", true);
     * Cf le fichier connector.php (généré par cette classe) pour plus de détails et options)
     * 
     * @param string $conector_url URL du connecteur ( il est recommandé de laisser par defaut )
     */
    public function __construct($conector_url = "connector.php") {
        ?>
        <link rel="stylesheet" type="text/css" media="screen" href="../commun/src/js/elFinder/css/elfinder.min.css">
        <script type="text/javascript" src="../commun/src/js/elFinder/js/elfinder.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#elfinder').elfinder({
                    lang: 'fr',
                    url: '<?php echo $conector_url; ?>'
                }).elfinder('instance');
            })
        </script>
        <div id="elfinder"></div>
        <?php
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
        session::start();
        if (session::get_val('elFinder')) {
            $" . "opts = array(
                'roots' => array(
                    array(
                        'driver' => 'LocalFileSystem',
                        'path' => 'files/',
                        'URL' => 'files/',
                        'uploadDeny' => array('all'),
                        'uploadAllow' => array(
                            'image', 'audio', 'video',
                            'application/json', 'application/pdf', 'application/rtf', 'application/x-7z-compressed', 'application/x-latex', 'application/xml', 'application/zip',
                            'text/css', 'text/csv', 'text/plain',
                        ),
                        'uploadOrder' => array('deny', 'allow'),
                        'accessControl' => 'access'
                    )
                )
            );
            $" . "connector = new elFinderConnector(new elFinder($" . "opts));
            $" . "connector->run();
        }
    }
    private function classloader() {
        foreach (glob(__DIR__ . '/../../dwf/class/*.class.php') as $" . "class) {
            include_once $" . "class;
        }
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
