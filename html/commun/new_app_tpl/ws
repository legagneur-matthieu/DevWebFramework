<?php

class ws_start {

    public function __construct() {
        set_time_limit(0);
        require "../../../dwf/class/cli.class.php";
        require "../class/config.class.php";
        cli::classloader();
        application::$_bdd = new bdd();
        spl_autoload_register(function ($class) {
            $file = __DIR__ . "/../class/entity/" . $class . ".class.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
        spl_autoload_register(function ($class) {
            $file = __DIR__ . "/" . $class . ".ws.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
        new websocket_server(config::$_WS_port, config::$_WS_host);
    }

}

new ws_start();
