<?php

/**
 * Cette classe gère des règles de sécurité en header et php ini
 * /!\ ne pas utiliser, appelé automatiquement par le framework
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class security {

    /**
     * Gère des règles de sécurité en header et php ini
     * /!\ ne pas utiliser, appelé automatiquement par le framework
     */
    public static function run() {
        self::headers();
        self::display_errors();
    }

    /**
     * Régles de sécurité en header http
     * CF csp.class.php pour modifier le Content-Security-Policy
     */
    private static function headers() {
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
        csp::get_instance()->apply();
    }

    /**
     * Display error ON en local uniquement
     */
    private static function display_errors() {
        $host = explode(':', $_SERVER['HTTP_HOST'] ?? '')[0];
        $p = "0";
        if (in_array($host, ['localhost', '127.0.0.1'], true)) {
            $p = "1";
        }
        ini_set('display_errors', $p);
    }
}
