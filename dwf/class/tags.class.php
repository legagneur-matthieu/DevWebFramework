<?php

/**
 * Cette classe parmet de créer des balises et génerer des structures HTML
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class tags {

    /**
     * Nom de la balise
     * @var string Nom de la balise
     */
    private $_tag = "";

    /**
     * Liste des attributs de la balise
     * @var array Liste des attributs de la balise
     */
    private $_attr = [];

    /**
     * Contenue de la balise (false = balise auto fermante)
     * @var string|\tags Contenue de la balise (false = balise auto fermante)
     */
    private $_content;

    /**
     * Créé une balise
     * @param string $tag Nom de la balise
     * @param array $attr Liste des attributs de la balise
     * @param string|\tags $content Contenu de la balise (false = balise auto fermante)
     */
    public function __construct($tag, $attr = [], $content = false) {
        $this->_tag = $tag;
        $this->_attr = $attr;
        $this->_content = $content;
    }

    /**
     * Créé une balise 
     * @param string $tag Nom de la balise
     * @param array $attr Liste des attributs de la balise
     * @param string|\tags $content Contenu de la balise (false = balise auto fermante)
     */
    public static function tag($tag, $attr = [], $content = false) {
        $str = "<{$tag}";
        foreach ($attr as $key => $value) {
            $str .= " {$key}=\"{$value}\"";
        }
        $str .= ($content !== false ? ">{$content}</{$tag}>" : "/>") . PHP_EOL;
        return $str;
    }

    /**
     * Retourne la balise et son contenu au format HTML
     * @return string Retourne la balise et son contenu au format HTML
     */
    public function __toString() {
        return self::tag($this->_tag, $this->_attr, $this->_content);
    }

    /**
     * Modifie le nom de la balise
     * @param string $tag Nom de la balise
     */
    public function set_tag($tag) {
        $this->_tag = $tag;
    }

    /**
     * Retourne le nom de la balise
     * @return string $tag Nom de la balise
     */
    public function get_tag() {
        return $this->_tag;
    }

    /**
     * Modifie un attribut
     * @param string $key Nom de l'attribut
     * @param string $value Valeur de l'attribut
     */
    public function set_attr($key, $value) {
        $this->_attr[$key] = $value;
    }

    /**
     * Retourne un attribut
     * @param string $key Nom de l'attribut
     * @return string Valeur de l'attribut, null si l'attribut n'existe pas
     */
    public function get_attr($key) {
        return (isset($this->_attr[$key]) ? $this->_attr[$key] : null);
    }

    /**
     * Supprime un attribut
     * @param string $key Nom de l'attribut
     */
    public function del_attr($key) {
        unset($this->_attr[$key]);
    }

    /**
     * Modifie le contenu de la balise
     * @param string $content Contenu de la balise (false = balise auto fermante)
     */
    public function set_content($content) {
        $this->_content = $content;
    }

    /**
     * Ajoute du contenu dans la balise
     * @param string $content Contenu de la balise (false = balise auto fermante)
     */
    public function append_content($content) {
        $this->_content .= $content;
    }

    /**
     * Retourne le contenu de la balise
     * @return string Contenu de la balise (false = balise auto fermante)
     */
    public function get_content() {
        return $this->_content;
    }

    /**
     * Créé une balise
     * @param string $name Nom de la balise
     * @param array $arguments Attributs et contenu de la balise
     * @return \tags Balise
     */
    public static function __callStatic($name, $arguments) {
        if (isset($arguments[0])) {
            if (is_array($arguments[0])) {
                $attr = $arguments[0];
                $content = (isset($arguments[1]) ? $arguments[1] : false);
            } elseif (isset($arguments[1]) and is_array($arguments[1])) {
                $attr = $arguments[1];
                $content = ($arguments[0] !== null ? $arguments[0] : false);
            } else {
                $attr = [];
                $content = ($arguments[0] !== null ? $arguments[0] : false);
            }
        } else {
            $attr = [];
            $content = false;
        }
        return new tags($name, $attr, $content);
    }

}
