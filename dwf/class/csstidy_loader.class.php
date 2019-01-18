<?php

/**
 * Cette class permet de charger CSSTidy
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class csstidy_loader {

    /**
     * Cette class permet de charger CSSTidy
     */
    public static function autoloader() {
        if (!class_exists("csstidy")) {
            include_once __DIR__ . '/CSSTidy/class.csstidy.php';
            include_once __DIR__ . '/CSSTidy/class.csstidy_optimise.php';
            include_once __DIR__ . '/CSSTidy/class.csstidy_print.php';
        }
    }

}
