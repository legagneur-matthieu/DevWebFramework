<?php

/**
 * Cette classe permet de gèrer le Content-Security-Policy (CSP)
 * 
 * @author LEGAGNEUR Matthieu <leagagneur.matthieu@gmail.com>
 */
class csp extends singleton {

    /**
     * Nonce pour les scripts JS
     * @var string Nonce pour les scripts JS
     */
    private static $_nonce = "";

    /**
     * Retourne de nonce que les scripts JS doivent utiliser sur la page HTML courante
     * @return string nonce pour les scripts JS
     */
    public static function get_nonce() {
        if (empty(self::$_nonce)) {
            self::$_nonce = base64_encode(random_bytes(32));
        }
        return self::$_nonce;
    }

    /**
     * le CSP est-il en repport only ? (True/False)
     * @var boolean le CSP est-il en repport only ? (True/False)
     */
    private $_report_only = true;

    /**
     * Tableau des régles CSP
     * @var array Tableau des régles CSP
     */
    private $_csp = [
        'default-src' => ["'self'", "https:"],
        'script-src' => ["'strict-dynamic'"],
        'style-src' => ["'self'", "https:", "'unsafe-inline'"],
        'img-src' => ["'self'", "https:", "data:"],
        'media-src' => ["'self'", "https:", "data:"],
        'font-src' => ["'self'", "https:", "data:"],
        'connect-src' => ["'self'", "https:"],
        'form-action' => ["'self'"],
        'base-uri' => ["'self'"],
        'object-src' => ["'none'"],
        'frame-ancestors' => ["'self'"],
        'upgrade-insecure-requests' => [],
        'report' => [],
    ];

    /**
     * Cette classe permet de gèrer le Content-Security-Policy (CSP)
     * 
     * @param boolean $report_only le CSP est-il en repport only ? (True/False)
     */
    public function __construct($report_only = true) {
        $this->_report_only = $report_only;
        $host = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://") . str_replace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']);
        $this->add_report("csp-group", "{$host}/commun/csp.php");
        $_SESSION["csp"] = [
            "title" => config::$_title,
            "prefix" => config::$_prefix
        ];
        if (in_array($_SERVER['HTTP_HOST'], ["localhost", "127.0.0.1"])) {
            echo html_structures::script("/commun/src/js/csp.js");
        }
    }

    /**
     * Applique le CSP
     * /!\ ne pas utiliser, appelé automatiquement par le framework
     */
    public function apply() {
        $this->add_script_src("'nonce-" . self::get_nonce() . "'");
        $csp = [];
        foreach ($this->_csp as $dir => $values) {
            if ($dir != 'report') {
                $csp[] = $dir . (empty($values) ? "" : " " . implode(" ", $values));
            }
        }
        if (!empty($report = $this->get_report())) {
            if (!empty($report_uris = array_values($report))) {
                $csp[] = 'report-uri ' . implode(' ', $report_uris);
            }
            if (!empty($report_groups = array_keys($report))) {
                $csp[] = 'report-to ' . implode(' ', $report_groups);
            }
        }
        header("Content-Security-Policy" . ($this->_report_only ? '-Report-Only' : '') . ': ' . implode('; ', $csp));
        $endpoints = [];
        foreach ($report as $group => $url) {
            $endpoints[] = "{$group}=\"{$url}\"";
        }
        if (!empty($endpoints)) {
            header('Reporting-Endpoints: ' . implode(', ', $endpoints));
        }
    }

    /**
     * Ajoute une directive CSP
     * @param string $directive Directive CSP
     * @param string|array $values Une valeur ou un tableau de valeurs
     */
    public function add($directive, $values) {
        if (in_array("'none'", $this->_csp[$directive])) {
            $this->_csp[$directive] = [];
        }
        if (is_array($values)) {
            foreach ($values as $value) {
                $this->add($directive, $value);
            }
        } else {
            $this->_csp[$directive][] = trim($values);
        }
        $this->_csp[$directive] = array_unique($this->_csp[$directive]);
    }

    /**
     * Redéfinit une directive CSP
     * @param string $directive Directive CSP
     * @param string|array $values Une valeur ou un tableau de valeurs
     */
    public function set($directive, $values) {
        $this->_csp[$directive] = is_array($values) ? $values : [$values];
    }

    /**
     * Retourne une directive ("All" pour toutes les directives)
     * @param string $directive La directive a retourner ou "All" pour toutes les directives
     * @return array Valeurs de la directives 
     */
    public function get($directive = "All") {
        if ($directive == "All") {
            return $this->_csp;
        }
        return $this->_csp[$directive];
    }

    /**
     * Ajoute une valeur a la directive default-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_default_src($value) {
        $this->add("default-src", $value);
    }

    /**
     * Ajoute une valeur a la directive script-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_script_src($value) {
        $this->add("script-src", $value);
    }

    /**
     * Ajoute une valeur a la directive style-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_style_src($value) {
        $this->add("style-src", $value);
    }

    /**
     * Ajoute une valeur a la directive img-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_img_src($value) {
        $this->add("img-src", $value);
    }

    /**
     * Ajoute une valeur a la directive media-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_media_src($value) {
        $this->add("media-src", $value);
    }

    /**
     * Ajoute une valeur a la directive font-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_font_src($value) {
        $this->add("font-src", $value);
    }

    /**
     * Ajoute une valeur a la directive connect-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_connect_src($value) {
        $this->add("connect-src", $value);
    }

    /**
     * Ajoute une valeur a la directive form_action
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_form_action($value) {
        $this->add("form-action", $value);
    }

    /**
     * Ajoute une valeur a la directive base_uri
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_base_uri($value) {
        $this->add("base-uri", $value);
    }

    /**
     * Ajoute une valeur a la directive object-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function add_object_src($value) {
        $this->add("object-src", $value);
    }

    /**
     * Ajoute une valeur a la directive report-uri et report-to
     * @param string $group Le nom du groupe de reporting
     * @param string $url URL du groupe de reporting
     */
    public function add_report($group, $url) {
        $this->_csp["report"][$group] = $url;
    }

    /**
     * CSP Report only (true/false)
     * @param boolean $value CSP Report only (true/false)
     */
    public function set_report_only($value = true) {
        $this->_report_only = $value;
    }

    /**
     * Redefinit les valeurs de la directive default-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_default_src($value) {
        $this->set('default-src', $value);
    }

    /**
     * Redefinit les valeurs de la directive script-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_script_src($value) {
        $this->set('script-src', $value);
    }

    /**
     * Redefinit les valeurs de la directive style-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_style_src($value) {
        $this->set('style-src', $value);
    }

    /**
     * Redefinit les valeurs de la directive img-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_img_src($value) {
        $this->set('img-src', $value);
    }

    /**
     * Redefinit les valeurs de la directive media-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_media_src($value) {
        $this->set('media-src', $value);
    }

    /**
     * Redefinit les valeurs de la directive font-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_font_src($value) {
        $this->set('font-src', $value);
    }

    /**
     * Redefinit les valeurs de la directive connect-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_connect_src($value) {
        $this->set('connect-src', $value);
    }

    /**
     * Redefinit les valeurs de la directive form-action
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_form_action($value) {
        $this->set('form-action', $value);
    }

    /**
     * Redefinit les valeurs de la directive base-uri
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_base_uri($value) {
        $this->set('base-uri', $value);
    }

    /**
     * Redefinit les valeurs de la directive object-src
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_object_src($value) {
        $this->set('object-src', $value);
    }

    /**
     * Redefinit les valeurs de la directive frame-ancestors
     * @param string|array $value Une valeur ou un tableau de valeurs
     */
    public function set_frame_ancestors($value) {
        $this->set('frame-ancestors', $value);
    }

    /**
     * Retourne toutes les directeives CSP
     * @return array Toutes les directives CSP
     */
    public function get_all() {
        return $this->get('All');
    }

    /**
     * Retourne la directive default-src
     * @return array Directive default-src
     */
    public function get_default_src() {
        return $this->get('default-src');
    }

    /**
     * Retourne la directive script-src
     * @return array Directive script-src
     */
    public function get_script_src() {
        return $this->get('script-src');
    }

    /**
     * Retourne la directive style-src
     * @return array Directive style-src
     */
    public function get_style_src() {
        return $this->get('style-src');
    }

    /**
     * Retourne la directive img-src
     * @return array Directive img-sr
     */
    public function get_img_src() {
        return $this->get('img-src');
    }

    /**
     * Retourne la directive media-src
     * @return array Directive img-sr
     */
    public function get_media_src() {
        return $this->get('media-src');
    }

    /**
     * Retourne la directive font-src
     * @return array Directive font-src
     */
    public function get_font_src() {
        return $this->get('font-src');
    }

    /**
     * Retourne la directive connect-src
     * @return array Directive connect-src
     */
    public function get_connect_src() {
        return $this->get('connect-src');
    }

    /**
     * Retourne la directive form-action
     * @return array Directive form-action
     */
    public function get_form_action() {
        return $this->get('form-action');
    }

    /**
     * Retourne la directive base-uri
     * @return array Directive base-uri
     */
    public function get_base_uri() {
        return $this->get('base-uri');
    }

    /**
     * Retourne la directive object-src
     * @return array Directive object-src
     */
    public function get_object_src() {
        return $this->get('object-src');
    }

    /**
     * Retourne la directive frame-ancestors
     * @return array Directive frame-ancestors
     */
    public function get_frame_ancestors() {
        return $this->get('frame-ancestors');
    }

    /**
     * Retourne le tableau utilisé pour les directives report-uri et report-to
     * @return array Tableau des groupe de reporting
     */
    public function get_report() {
        return $this->get('report');
    }
}
