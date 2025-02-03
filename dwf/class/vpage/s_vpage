<?php

class s_vpage {

    public function __construct() {
        if (isset($_GET["key"])) {
            $vpages = dwf_vpage::get_collection("key=:key;", [":key" => $_GET["key"]]);
            if (isset($vpages[0])) {
                $vpage = $vpages[0];
                if ($vpage->get_mt() >= time()) {
                    self::print_vpage($vpage);
                } else {
                    $vpage->delete();
                    $this->not_found();
                }
            } else {
                $this->not_found();
            }
        } else {
            $this->not_found();
        }
    }

    private static function print_vpage($vpage) {
        $dir=basename(dirname(__FILE__,2));
        new html5("../../{$dir}");
        html5::before_title($vpage->get_title());
        echo $vpage->get_content();
    }

    private function not_found() {
        ?>
        <!doctype html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>404 Not Found</title>
            </head>
            <body>
                <h1>404 - Page Not Found</h1>
            </body>
        </html>
        <?php
    }
}
