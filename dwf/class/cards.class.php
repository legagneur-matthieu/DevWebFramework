<?php

/**
 * Cette classe permet de gèrer un paquet de 32, 52, 54 ou 78 cartes
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class cards {

    /**
     * Paquet de cartes
     * @var array 
     */
    private $_deck = array();

    /**
     * Cette classe permet de gérer un paquet de 32, 52, 54 ou 78 cartes
     * 
     * @param array|int $deck Soit un paquet de cartes existant <br />
     * soit le "type" de paquet a générer : <br />
     * 32 : paquet de cartes pour la belote par exemple <br />
     * 52 : paquet standard ( paquet par défaut) <br />
     * 54 : paquet standard, plus 2 jokers <br />
     * 78 : paquet de tarot
     */
    public function __construct($deck = 52) {
        if (is_array($deck)) {
            $this->set_deck($deck);
        } else {
            $icones = array("&hearts;", "&diams;", "&clubs;", "&spades;");
            $value = array();
            switch ($deck) {
                case 32:
                    $value = array("A", "K", "Q", "J", 10, 9, 8, 7);
                    break;
                case 52:
                    $value = array("A", "K", "Q", "J", 10, 9, 8, 7, 6, 5, 4, 3, 2);
                    break;
                case 54:
                    $value = array("A", "K", "Q", "J", 10, 9, 8, 7, 6, 5, 4, 3, 2);
                    $this->_deck[] = "Joker red";
                    $this->_deck[] = "Joker black";
                    break;
                case 78:
                    $value = array("A", "K", "Q", "C", "J", 10, 9, 8, 7, 6, 5, 4, 3, 2);
                    for ($i = 1; $i <= 21; $i++) {
                        $this->_deck[] = $i . "&star;";
                    }
                    $this->_deck[] = "Excuse";
                    break;
                default:
                    return false;
                    break;
            }
            foreach ($icones as $i) {
                foreach ($value as $v) {
                    $this->_deck[] = $v . $i;
                }
            }
            $this->shuffle_deck();
        }
    }

    /**
     * Retourne le paquet de cartes courant
     * @return array Le paquet de carte courant
     */
    public function get_deck() {
        return $this->_deck;
    }

    /**
     * Redéfinit le paquet de cartes courant
     * @param array $deck Le paquet de carte
     */
    public function set_deck($deck) {
        $this->_deck = $_deck;
    }

    /**
     * Melange le paquet de cartes
     */
    public function shuffle_deck() {
        shuffle($this->_deck);
    }

    /**
     * Tire une carte du paquet ( la carte est retiré du paquet !)
     * @return string|boolean La carte tiré, false si le paquet est vide
     */
    public function drow_from_deck() {
        if ($count = count($this->_deck) > 0) {
            $card = $this->_deck[$r = rand(0, $count - 1)];
            unset($this->_deck[$r]);
            $this->_deck = array_values($this->_deck);
            return $card;
        }
        return false;
    }

}
