<?php

/**
 * Cette classe permet convertir et gerrer une liste lourde comme étant un dictionnaire
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class dictionary {

    /**
     * Dictionnaire
     * @var array Dictionnaire
     */
    private $_dictionary = [];

    /**
     * Taille d'une section du dictionnaire (100 000 mots par defaut)
     * @var int Taille d'une section du dictionnaire
     */
    private $_chunk_size = 100000;

    /**
     * Cette classe permet convertir et gerrer une liste lourde comme étant un dictionnaire
     * @param array $words Liste de mots a entrer dans le dictionnaire
     * @param int $chunk_size Taille d'une section du dictionnaire (100 000 mots par defaut)
     */
    public function __construct($words = [], $chunk_size = 100000) {
        $this->set_chunk_size($chunk_size);
        $this->add($words);
    }

    /**
     * Retourne la taille des sections
     * @return int Taille des sections
     */
    public function get_chunk_size() {
        return $this->_chunk_size;
    }

    /**
     * Definis la taille d'une section du dictionnaire
     * @param int $chunk_size Taille d'une section du dictionnaire
     */
    public function set_chunk_size($chunk_size) {
        $this->_chunk_size = $chunk_size;
    }

    /**
     * Retourne le nombre de mots dans le dictionnaire
     * @return int Nombre de mots dans le dictionnaire
     */
    public function count_words() {
        return count($this->_dictionary, COUNT_RECURSIVE) - $this->count_sections();
    }

    /**
     * Retourne le nombre de sections dans le dictionnaire
     * @return int Nombre de sections dans le dictionnaire
     */
    public function count_sections() {
        return count($this->_dictionary);
    }

    /**
     * Retourne une section du dictionnaire
     * @param int $key Index de la section
     * @return array Liste des mots de la section
     */
    public function get_section($key) {
        return $this->_dictionary[$key];
    }

    /**
     * Verifie si un mot existe dans le dictionnaire (true ou false)
     * @param string $word mot a verifier
     * @return boolean true : le mot est dans le dictionnaire, false : il n'y est pas
     */
    public function word_exist($word) {
        foreach ($this->_dictionary as $section) {
            if (strcasecmp($word, $section[0]) >= 0 and strcasecmp($word, $section[count($section) - 1]) <= 0) {
                return in_array($word, $section);
            }
        }
        return false;
    }

    /**
     * Ajoute une liste de mots au dictionaire et re-sectione le dictionnaire
     * @param array $words Mots à ajouter
     */
    public function add($words) {
        if (!is_array($words)) {
            $words = [$words];
        }
        foreach ($this->_dictionary as $key => $section) {
            $words = array_merge_recursive($words, $section);
            unset($this->_dictionary[$key]);
        }
        sort($words, SORT_STRING);
        $this->_dictionary = array_chunk($words, $this->_chunk_size);
    }

    /**
     * Supprime une liste de mots du dictionnaire
     * @param array $words Mots a supprimer
     * @param boolean $reorder le dictionnaire doit-il être re_sectioné après les suppresions ? (true ou false, false par defaut)
     */
    public function remove($words, $reorder = false) {
        if (!is_array($words)) {
            $words = [$words];
        }
        foreach ($words as $word) {
            foreach ($this->_dictionary as $key => $section) {
                if (strcasecmp($word, $section[0]) >= 0 and strcasecmp($word, $section[count($section) - 1]) <= 0 and in_array($word, $section)) {
                    foreach (array_keys($section, $word) as $k) {
                        unset($this->_dictionary[$key][$k]);
                    }
                }
            }
        }
        if ($reorder) {
            $this->add([]);
        }
    }

}
