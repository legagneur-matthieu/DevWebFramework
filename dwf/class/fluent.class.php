<?php

/**
 * Cette classe permet de rendre une autre classe pseudo-fluent.
 * Utilisez l'héritage :
 * class ma_classe extends fluent{ }
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class fluent {

    private $_fuent_buffer = [];

    /**
     * Exécute la méthode et retourne l'instance si la fonction ne retourne rien ou null
     * @param string $method Méthode à appeler
     * @param array $params Paramètres de la méthode
     * @return $this|mixed
     */
    public function fluentOnNull($method, $params = []) {
        $res = (new ReflectionMethod(get_called_class(), $method))->invokeArgs($this, $params);
        return(is_null($res) ? $this : $res);
    }

    /**
     * Exécute la méthode et retourne l'instance uniquement (tout éventuel retour de la méthode n'est pas récuperable)
     * @param string $method Méthode à appeler
     * @param array $params Paramètres de la méthode
     * @return $this
     */
    public function fluentStrict($method, $params = []) {
        (new ReflectionMethod(get_called_class(), $method))->invokeArgs($this, $params);
        return $this;
    }

    /**
     * Exécute la méthode et retourne l'instance uniquement,
     * tout éventuel retour de la methode est mis dans un cache récuperable via getFluentBuffer()
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
