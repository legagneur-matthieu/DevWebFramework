<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of phpsec_loader
 *
 * @author Matthieu
 */
class phpsec_loader {

    public function __construct() {
        foreach (glob(__DIR__ . '/*/*.php') as $phpsec_class) {
            include_once $phpsec_class;
        }
    }

}

new phpsec_loader();
