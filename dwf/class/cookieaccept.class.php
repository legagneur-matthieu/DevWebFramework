<?php

/**
 * Cette classe permet d'afficher un message d'informations sur l'utilisation de cookies ou autre technologies similaires
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class cookieaccept {

    /**
     * Cette classe permet d'afficher un message d'informations sur l'utilisation de cookies ou autre technologies similaires
     */
    public function __construct() {
        echo tags::tag(
                "div", [], tags::tag(
                        "p", [], tags::tag(
                                "a", ["href"=>"https://www.cnil.fr/fr/cookies-les-outils-pour-les-maitriser", "title"=>"En savoir plus sur les cookies", "target"=>"_blank"], "En savoir plus")
                ).tags::tag(
                        "p", [], tags::tag(
                                "a", ["id"=>"cookieaccept_accept", "href"=>"#", "class"=>"btn btn-info"], "J'ai compris")
                )
        );
    }

}
