<?php

/**
 * Cette classe permet de génerer des feuilles de style personalisé
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class css {

    /**
     * Feuille de style
     * @var array Feuille de style 
     */
    private $_css = [];

    /**
     * Ajoute une règle à la feuille de style
     * @param string $selector Selector CSS
     * @param array $rules Règles CSS, exemple : ["margin" => "5px", padding => "5px", ...]
     * @return css $this Fluent 
     */
    public function add_rule($selector, $rules) {
        if (!isset($this->_css[$selector])) {
            $this->_css[$selector] = [];
        }
        $this->_css[$selector] = array_merge($this->_css[$selector], $rules);
        return $this;
    }

    /**
     * Ajoute une règle à la feuille de style a une balise     * 
     * @param string $name Selector CSS
     * @param array $rules Règles CSS, exemple : ["margin" => "5px", padding => "5px", ...]
     * @return css $this Fluent
     */
    public function __call($name, $rules) {
        return $this->add_rule($name, $rules[0]);
    }

    /**
     * Compact les règles CSS de la feuilles de style
     */
    private function compact() {
        foreach ($this->_css as $selector => $rules) {
            foreach ($this->_css as $selector2 => $rules2) {
                foreach ($rules as $key => $value) {
                    if ($selector !== $selector2 and isset($this->_css[$selector2][$key]) and $this->_css[$selector2][$key] === $value) {
                        isset($this->_css[$selector . "," . $selector2]) ?
                                        $this->_css[$selector . "," . $selector2][$key] = $value :
                                        $this->_css[$selector . "," . $selector2] = [$key => $value];
                        unset($this->_css[$selector][$key]);
                        unset($this->_css[$selector2][$key]);
                        try {
                            $this->compact();
                        } catch (Exception $exc) {
                            break(3);
                        }
                        break(3);
                    }
                }
            }
        }
    }

    /**
     * Retourn la feuilles de style minifié
     * @return string Retourn la feuilles de style minifié
     */
    public function __toString() {
        $this->compact();
        $str = "";
        foreach ($this->_css as $selector => $rules) {
            $str .= $selector . "{";
            foreach ($rules as $key => $value) {
                $str .= $key . ": " . $value . ";";
            }
            $str .= "}";
        }
        return compact_css::css_minify($str);
    }

}
