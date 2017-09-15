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
        ?> 
        <script type="text/javascript" src="../commun/src/js/cookieaccept.js"></script>
        <div id="cookieaccept">
            <p>En appliquant sur notre site, vous acceptez l'utilisation de cookies ou autres technologies similaires.</p>
            <p><a href="https://www.cnil.fr/fr/cookies-les-outils-pour-les-maitriser" title="En savoir plus sur les cookies" target="_blank">En savoir plus</a></p>
            <p><a id="cookieaccept_accept" href="#" class="btn btn-info">J'ai compris</a></p>
        </div> 
        <?php

    }

}
