<?php

/**
 * Cette classe permet de générer une archive ZIP contenant les fichiers nécessaires à un projet,
 * en fonction des dépendances enregistrées dans un fichier JSON spécifique à chaque projet.
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class export_dwf {

    /**
     * Classe annonyme pour l'ecriture du fichier Json
     * @var bool|object
     */
    private static $_writer = false;
    
    /**
     * Etat de la liste si modifié ou non
     * @var boolean Etat de la liste si modifié ou non
     */
    private static $_change = false;

    /**
     * @var string Chemin absolu vers le répertoire racine du framework.
     */
    private $_base;

    /**
     * @var ZipArchive Objet ZipArchive pour la manipulation des archives ZIP.
     */
    private $_zip;

    /**
     * @var array|bool Liste des fichiers nécessaires au projet, enregistrée dans un fichier JSON.
     *                Cette liste est partagée entre toutes les instances de la classe.
     */
    private static $_files = false;

    /**
     * Retourne la liste des fichiers nécessaires du projet
     * @return array Retourne la liste des fichiers nécessaires du projet
     */
    public static function get_files() {
        return self::$_files;
    }

    /**
     * Retourne si la liste des fichier a changé.
     * @return boolean Retourne si la liste des fichier a changé.
     */
    public static function is_changed() {
        return self::$_change;
    }

    /**
     * Méthode statique pour ajouter des fichiers à la liste des dépendances du projet.
     *
     * @param mixed $files Chemin(s) des fichiers à ajouter.
     *                     Peut être une chaîne de caractères ou un tableau de chaînes de caractères.
     * @return void
     */
    public static function add_files($files) {
        if (!file_exists(__DIR__ . "/export_dwf/.export_disabled")) {
            if (!file_exists(__DIR__ . "/export_dwf")) {
                mkdir(__DIR__ . "/export_dwf", 0777, true);
            }
            $projet = strtr($_SERVER["SCRIPT_NAME"], ["/index.php" => "", "/" => ""]);
            if (!empty($projet)) {
                if (!file_exists($json = __DIR__ . "/export_dwf/{$projet}.json")) {
                    file_put_contents($json, "[]");
                }
                if (!self::$_files) {
                    self::$_files = json_decode(file_get_contents($json), true);
                    self::$_writer = new class($json) {

                        private $_json;

                        public function __construct($json) {
                            $this->_json = $json;
                        }

                        public function __destruct() {
                            if (export_dwf::is_changed()) {
                                file_put_contents($this->_json, json_encode(export_dwf::get_files()));
                            }
                        }
                    };
                }
                foreach (self::$_files as $key => $file) {
                    if (!file_exists($file)) {
                        unset(self::$_files[$key]);
                        self::$_change = true;
                    }
                }
                if (!is_array($files)) {
                    $files = [$files];
                }
                foreach ($files as $file) {
                    if (!in_array($file, self::$_files) and file_exists($file) and stripos($file, "/html/{$projet}/") === false) {
                        self::$_files[] = $file;
                        self::$_change = true;
                    }
                }
                sort(self::$_files);
            }
        }
    }

    /**
     * Cette classe permet de générer une archive ZIP contenant les fichiers nécessaires à un projet,
     * en fonction des dépendances enregistrées dans un fichier JSON spécifique à chaque projet.
     *
     * @param string $project Nom du projet pour lequel générer l'archive ZIP.
     */
    public function __construct($project) {
        $this->_base = realpath(dirname(__DIR__, 2));
        $this->_zip = new ZipArchive();
        $dir = "./commun/export_dwf";
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        debug::print_r([
            "projet" => $project,
            "base" => $this->_base,
            "dir" => $dir,
        ]);
        $zip_file = "{$dir}/{$project}.zip";
        if (file_exists($zip_file)) {
            unlink($zip_file);
        }
        $this->_zip->open($zip_file, ZipArchive::CREATE);
        $json = __DIR__ . "/export_dwf/{$project}.json";
        $files = json_decode(file_get_contents($json));
        $files = array_merge($files, [
            realpath("{$this->_base}/html/{$project}"),
            realpath("{$this->_base}/dwf/.htaccess"),
            realpath("{$this->_base}/dwf/index.php"),
            realpath("{$this->_base}/dwf/class/index.php"),
            realpath("{$this->_base}/dwf/class/phpini"),
            realpath("{$this->_base}/dwf/log/.htaccess"),
            realpath("{$this->_base}/dwf/log/index.php"),
            realpath("{$this->_base}/dwf/cli/.htaccess"),
            realpath("{$this->_base}/dwf/cli/start.php"),
        ]);
        foreach ($files as $file) {
            debug::print_r("add {$file}");
            if (is_dir($file)) {
                $this->add_dir($file);
            } else {
                $this->_zip->addFile($file, strtr($file, ["{$this->_base}" => ""]));
            }
        }
        $this->_zip->addFromString("html/index.php", "<?php header(\"Location: ./{$project}/index.php\"); ?>\n<script>window.location=\"./{$project}/index.php\"</script>");
        $this->_zip->addFromString("dwf/export_dwf/.export_disabled", "This file is just use for disable export_dwf in already exported projects");
        $this->_zip->addEmptyDir("html/{$project}/src/compact/");
        $this->_zip->close();
        header("Location: {$zip_file}");
        js::redir($zip_file);
    }

    /**
     * Méthode privée pour ajouter un répertoire et ses fichiers à l'archive ZIP.
     *
     * @param string $path Chemin du répertoire à ajouter.
     * @return void
     */
    private function add_dir($path) {
        $glob = glob($path . "/*");
        foreach ($glob as $file) {
            if (stripos($file, ".git") === false and stripos($file, "src/compact/") === false) {
                $file = strtr($file, [".//" => ""]);
                if (is_dir($file)) {
                    $this->add_dir($file);
                } else {
                    $this->_zip->addFile($file, strtr($file, ["{$this->_base}" => ""]));
                }
            }
        }
    }
}
