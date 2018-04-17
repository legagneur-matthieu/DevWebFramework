<?php

/**
 * Cette classe permet d'utiliser des template 
 * (en utilisant la librairie Smarty https://www.smarty.net/docsv2/fr/index.tpl)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class template {

    /**
     * Permet de vérifier que la librairie Smarty a bien été appelée qu'une fois.
     * @var boolean Permet de vérifier que la librairie Smarty a bien été appelée qu'une fois.
     */
    private static $_called = false;

    /**
     * Dossier des templates
     * @var string  Dossier des templates
     */
    private $_dir = "./class/tpl";

    /**
     * Cette classe permet d'utiliser des template 
     * (en utilisant la librairie Smarty https://www.smarty.net/docsv2/fr/index.tpl)
     * 
     * @param string $tplname nom du templaite ( nom du fichier sans l'extention .tpl !)
     * @param array $data Tableau associatif de donnée, les clé de la première dimention sont les variables du template
     */
    public function __construct($tplname, $data) {
        if (!self::$_called) {
            include_once __DIR__ . '/smarty/libs/Autoloader.php';
            Smarty_Autoloader::register();
            if (!file_exists($this->_dir)) {
                mkdir($this->_dir);
            }
            self::$_called = true;
        }
        $tpl = $this->_dir . "/" . $tplname . ".tpl";
        if (!file_exists($tpl)) {
            file_put_contents($tpl, "");
        }
        ($smarty = new Smarty());
        $smarty->setCompileDir($this->_dir . "/compile");
        foreach ($data as $key => $value) {
            $smarty->assign($key, $value);
        }
        $smarty->display($tpl);
    }

    /**
     * Permet d'utiliser des template 
     * (en utilisant la librairie Smarty https://www.smarty.net/docsv2/fr/index.tpl)
     * 
     * @param string $tplname nom du templaite ( nom du fichier sans l'extention .tpl !)
     * @param array $data Tableau associatif de donnée, les clé de la première dimention sont les variables du template
     */
    public static function render($tplname, $data) {
        new template($tplname, $data);
    }

}
