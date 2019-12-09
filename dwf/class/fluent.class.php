<?php

/**
 * Cette classe permet de rendre une autre classe pseudo-fluent.
 * Utilisez l'heritage :
 * class ma_classe extends fluent{ }
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class fluent {

    private $_fuent_buffer = [];

    /**
     * Execute la méthode et retourne l'instance si la fonction ne retourne rien ou null
     * @param string $method Méthode à appeler
     * @param array $params Paramètres de la méthode
     * @return $this|mixed
     */
    public function fluentOnNull($method, $params = []) {
        $res = (new ReflectionMethod(get_called_class(), $method))->invokeArgs($this, $params);
        return(is_null($res) ? $this : $res);
    }

    /**
     * Execute la méthode et retourne l'instance iniquement (tout éventuel retour de la methode n'est pas recuperable)
     * @param string $method Méthode à appeler
     * @param array $params Paramètres de la méthode
     * @return $this
     */
    public function fluentStrict($method, $params = []) {
        (new ReflectionMethod(get_called_class(), $method))->invokeArgs($this, $params);
        return $this;
    }

    /**
     * Execute la méthode et retourne l'instance iniquement,
     * tout éventuel retour de la methode et mis dans un cache récuperable via getFluentBuffer()
     * @param string $method Méthode à appeler
     * @param array $params Paramètres de la méthode
     * @return $this
     */
    public function fluentBuffered($method, $params = []) {
        $res = (new ReflectionMethod(get_called_class(), $method))->invokeArgs($this, $params);
        if (!is_null($res)) {
            $this->_fuent_buffer[] = $res;
        }
        return $this;
    }

    /**
     * Retourne le cache des retours des méthodes appelé avec fluentBuffered()
     * @param boolean $cleanBuffer Le cache doit il être vidé après sa récupération ? (true par defaut)
     * @return array Cache des retours
     */
    public function getFluentBuffer($cleanBuffer = true) {
        if ($cleanBuffer) {
            $buffer = $this->_fuent_buffer;
            $this->_fuent_buffer = [];
            return $buffer;
        } else {
            return $this->_fuent_buffer;
        }
    }

}
