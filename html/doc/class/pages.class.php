<?php

/**
 * Cette classe sert de "Vue" à votre application, 
 * vous pouvez y développer votre application comme bon vous semble : 
 * HTML, créér et appeler une fonction "private" dans une fonction "public", faire appel à des classes exterieures ...  
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class pages { /**
 *  Cette classe sert de "Vue" à votre application, * vous pouvez y développer votre application comme bon vous semble : 
 * HTML, créé et appelle une fonction "private" dans une fonction "public", faire appel à des classes exterieures ...  */

    public function __construct() {
        new robotstxt();
    }

    /*
     * Entete des pages 
     */

    public function header() {
        compact_css::get_instance()->add_style(
                (new css())->add_rule("header #boostap_switch_theme", [
                    "position" => "absolute", "right" => "-160px", "top" => "70px"
        ]));
        ?> 
        <header class="page-header bg-info">
            <h1>Documentation <br /><small>Documentation de DWF</small></h1> 
            <div id="boostap_switch_theme"><?= bootstrap_theme::switch_theme() ?></div>
        </header> 
        <?php
    }

    /**
     * Pied des pages 
     */
    public function footer() {
        ?> 
        <footer> <hr /> 
            <p> 2016-<?php echo date("Y"); ?> D&eacute;velopp&eacute; par <a href="mailto:legagneur.matthieu@gmail.com">LEGAGNEUR Matthieu</a></p> 
        <!--[if (IE 6)|(IE 7)]> <p><big>Ce site n'est pas compatible avec votre version d'internet explorer !</big></p> <![endif]--> 
        </footer> <?php
    }

    /**
     * Fonction par défaut / page d'accueil 
     */
    public function index() {
        ?>
        <style type="text/css">
            .index{
                width: 300px;
                margin: 0 auto;
            }
            .index img{
                width: 300px;
            }
            .index p{
                text-align: center;
                font-size: 24px;
            }
            .index2{
                width: 200px;
                margin: 0 auto;
            }
            .index2 img{
                width: 200px;
            }
        </style>
        <div class="row">
            <div class="col-sm-6">
                <div class="index">
                    <p>
                        <a href="index.php?page=web">Framework PHP</a>
                    </p>
                    <a href="index.php?page=web" style="">
                        <img src="img/php.png" alt="Framework PHP"/>
                    </a>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="index index2">
                    <p>
                        <a href="index.php?page=mobile">Framework Mobile</a>
                    </p>
                    <a href="index.php?page=mobile">
                        <img src="img/cordova.png" alt="Framework Mobile"/>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    public function web() {
        new docPHP();
    }

    public function mobile() {
        new docMobile();
    }

    public function tiers() {
        new docTiers();
    }

    public function deco() {
        auth::unauth();
        js::redir("index.php");
    }

}
