<?php

/**
 * Cette classe fait office de contrÃ´leur et layout pour l'application
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class application {

    /**
     * Routes de l'application (cf $this->routes(), une route "index" est obligatoire)
     * 
     * @var array Routes de l'application
     */
    private $_routes;

    /**
     * Instance de PAGES
     * 
     * @var pages Instance de PAGES
     */
    private $_pages;

    /**
     * Instance de BDD
     * 
     * @var bdd Instance de BDD
     */
    public static $_bdd;

    /**
     * Cette classe fait office de contrÃ´leur et layout pour l'application
     * (dans le constructeur, les "DWF" sont à modifier)
     */
    public function __construct() {
        if (!isset($_GET['page'])) {
            $_GET['page'] = 'index';
        }
        self::$_bdd = new bdd();
        self::event("onbdd_connected");
        $this->_pages = new pages();
        $this->routes();
        new sitemap();
        $this->contenu();
    }

    /**
     * Cette méthode gère les routes de l'application en fonction de l'authentification (le préfixe d'autentification est celui que vous utiliserez avec auth (class/auth.class.php))<br />
     * LES ROUTES SONT A CONFIGURER DANS LA CLASS CONFIG (class/config.php) <br />
     * Les éléments d'une route : <br />
     * <ul>
     *  <li>page : fait référence à la fonction à appeler dans la vue ( visible dans la variable $_GET["page"])</li>
     *  <li>text : texte à afficher dans le menu pour accéder à cette page ( si text n'est pas set ou est vide, cette route ne s'affichera pas dans le menu)</li>
     *  <li>title : title à attacher au texte</li>
     *  <li>description : meta description de la page (optionel pour les applications internes/intranet)</li>
     *  <li>keyword : meta keyword de la page (optionel pour les applications internes/intranet)</li>
     * </ul>
     * au moins une route "index" est obligatoire, une fois une route créée, il faudra créer sa fonction correspondante dans la classe pages (class/pages.class.php)
     * 
     * @param string $prefix_auth préfixe des variables d'authentification
     */
    private function routes() {
        $this->_routes = (session::get_auth() ? config::$_route_auth : config::$_route_unauth);
    }

    /**
     * Menu de l'application ( généré en fonction des routes )
     */
    private function menu() {
        ?>
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Dérouler le menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php
                    foreach ($this->_routes as $page) {
                        if (isset($page["text"]) and $page["text"] != "") {
                            ?>
                            <li<?php
                            if ($_GET["page"] == $page["page"]) {
                                echo ' class="active"';
                            }
                            ?>><a href="index.php?page=<?php echo $page["page"]; ?>" title="<?php echo $page["title"]; ?>" ><?php echo $page["text"]; ?></a></li>
                                <?php
                            }
                        }
                        ?>
                </ul>
            </div>
        </nav>

        <p class="min alert alert-info"><small><span class="glyphicon glyphicon-info-sign"><span class="sr-only">Information</span></span> Vous êtes sur mobile ou tablette ? Tenez votre appareil à l'horizontale !</small></p>
        <?php
    }

    /**
     * Affiche l'entête, le menu, la vue demandée par l'utilisateur et le pied de page
     */
    private function contenu() {
        $page_finded = false;
        foreach ($this->_routes as $page) {
            if ($_GET["page"] == $page["page"]) {
                $page_finded = true;
                if (!isset($page["description"])) {
                    $page["description"] = "";
                }
                if (!isset($page["keyword"])) {
                    $page["keyword"] = "";
                }
                $html = new html5($page["description"], $page["keyword"]);
                js::before_title($page["title"] . " - ");
                $this->_pages->header();
                $this->menu();
                $p = $page["page"];
                ?>
                <main class="contenu">    
                    <?php
                    try {
                        (method_exists($this->_pages, $p) ? $this->_pages->$p() : dwf_exception::throw_exception(612, array("__" => $p . '()')));
                    } catch (dwf_exception $e) {
                        dwf_exception::print_exception($e);
                    }
                    ?>
                </main>
                <?php
                $this->_pages->footer();
            }
        }
        if (!$page_finded) {
            if ($_GET["page"] != "index") {
                $_GET["page"] = 'index';
                $this->contenu();
            } else {
                dwf_exception::throw_exception(611);
            }
        }
    }

    /**
     * Cette fonction retourne les variables $_GET courante sous forme d'URL 
     * ("index.php?page=une_page&amp;variable_get=une_valeur&amp;")
     * le paramètre $skip_gets permet de ne pas retourner certaines variables indésirables ou réservé à une classe particulière
     * @param array $skip_gets tableau des variable $_GET a exclure
     * @return string Les variables $_GET courante sous forme d'URL 
     */
    public static function get_url($skip_gets = array()) {
        $url = "index.php?";
        foreach ($_GET as $key => $value) {
            if (!in_array($key, $skip_gets)) {
                $url .= $key . "=" . $value . "&amp;";
            }
        }
        return $url;
    }

    /**
     * Declenche l'evenement dont le nom est passé en paramètre
     * @param string $event_name nom de l'evenement
     */
    public static function event($event_name) {
        if (class_exists("website")) {
            foreach (website::$_class as $class) {
                if (method_exists($class, $event_name)) {
                    $class::$event_name();
                }
            }
        }
    }

}
