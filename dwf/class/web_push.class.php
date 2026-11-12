<?php

/**
 * Cette classe permet d'envoyer des notifications Web Push (API Push des navigateurs)
 * sans librairie externe : authentification VAPID (RFC 8292) et chiffrement aes128gcm (RFC 8291 / RFC 8188).
 * Les clés VAPID sont générées automatiquement au premier usage, hors du dossier web, dans le dossier de configuration
 * de l'utilisateur du serveur web (Linux : ~/.config/dwf, Windows : %APPDATA%\dwf, macOS : ~/Library/Application Support/dwf),
 * ou dans config::$_web_push_dir s'il est défini (un fichier par projet),
 * le service worker (src/web_push_sw.js) et le service d'abonnement (services/s_web_push.service.php) sont créés s'ils n'existent pas.
 * Attention : l'API Push n'est disponible que dans un contexte sécurisé (HTTPS ou localhost).
 * 
 * Exemple :
 * $push = new web_push("mailto:contact@mon-site.fr");
 * echo $push->button();
 * $push->send("Titre", "Message", "index.php?page=messages");
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class web_push {

    /**
     * Configuration OpenSSL d'une clé EC P-256
     */
    private const KEY_CONFIG = ["curve_name" => "prime256v1", "private_key_type" => OPENSSL_KEYTYPE_EC];

    /**
     * Préfixe DER (SubjectPublicKeyInfo) d'une clé publique P-256 non compressée
     */
    private const SPKI_PREFIX = "3059301306072a8648ce3d020106082a8648ce3d030107034200";

    /**
     * Service worker généré dans le dossier src du projet
     */
    private const SW_JS = <<<'JS'
var root = new URL("../", self.registration.scope).href;
self.addEventListener("push", function (event) {
    var data = {};
    try {
        data = event.data ? event.data.json() : {};
    } catch (e) {
        data = {body: event.data.text()};
    }
    event.waitUntil(self.registration.showNotification(data.title || "Notification", {
        body: data.body || "",
        icon: data.icon || undefined,
        data: {url: data.url || root}
    }));
});
self.addEventListener("notificationclick", function (event) {
    event.notification.close();
    var url = new URL((event.notification.data && event.notification.data.url) || "", root);
    if (url.origin !== self.location.origin) {
        url = new URL(root);
    }
    event.waitUntil(clients.matchAll({type: "window", includeUncontrolled: true}).then(function (list) {
        for (var i = 0; i < list.length; i++) {
            if (list[i].url === url.href && "focus" in list[i]) {
                return list[i].focus();
            }
        }
        return clients.openWindow(url.href);
    }));
});

JS;

    /**
     * Service d'abonnement généré dans le dossier services du projet
     */
    private const SERVICE_PHP = "<?php\n\n/**\n * Service d'enregistrement des abonnements Web Push (généré par la classe web_push)\n * \n * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>\n */\n"
            . "class s_web_push {\n\n    /**\n     * Enregistre l'abonnement Web Push envoyé par le navigateur et retourne le résultat en JSON\n     */\n"
            . "    public function __construct() {\n        header(\"Content-Type: application/json; charset=utf-8\");\n        echo json_encode(web_push::subscribe());\n    }\n}\n";

    /**
     * Suffixes des hôtes de services push autorisés (protection SSRF : le serveur ne contacte que ces hôtes en HTTPS)
     * @var array Suffixes des hôtes de services push autorisés
     */
    public static $_push_hosts = [
        "fcm.googleapis.com",
        "android.googleapis.com",
        "push.services.mozilla.com",
        "notify.windows.com",
        "push.apple.com",
    ];

    /**
     * Clé privée VAPID
     * @var OpenSSLAsymmetricKey|false Clé privée VAPID
     */
    private $_private = false;

    /**
     * Clé publique VAPID (point P-256 non compressé, base64url)
     * @var string Clé publique VAPID (point P-256 non compressé, base64url)
     */
    private $_public = "";

    /**
     * Contact VAPID ("mailto:" ou URL https)
     * @var string Contact VAPID ("mailto:" ou URL https)
     */
    private $_subject;

    /**
     * Cette classe permet d'envoyer des notifications Web Push (API Push des navigateurs)
     * Génère au besoin la table des abonnements, les clés VAPID, le service worker et le service d'abonnement
     * 
     * @param string $subject Contact VAPID transmis aux services push ("mailto:adresse" ou URL https, par défaut "mailto:webmaster@" + nom du serveur)
     */
    public function __construct($subject = null) {
        $this->_subject = (!empty($subject) ? $subject : "mailto:webmaster@" . (!empty($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : "localhost"));
        entity_generator::generate([
            "web_push_subscription" => [
                ["id", "int", true],
                ["endpoint", "string", false],
                ["p256dh", "string", false],
                ["auth", "string", false],
                ["user", "int", false],
                ["mt", "int", false],
            ]
        ]);
        if (file_exists($file = "./services/index.php") and !file_exists($file = "./services/s_web_push.service.php") and !file_put_contents($file, self::SERVICE_PHP)) {
            dwf_exception::warning_exception(631, ["__" => $file]);
        }
        if (is_dir("src") and !file_exists($file = "./src/web_push_sw.js") and !file_put_contents($file, self::SW_JS)) {
            dwf_exception::warning_exception(631, ["__" => $file]);
        }
        $root = (is_dir("class") ? "." : "..");
        $dir = (isset(config::$_web_push_dir) ? config::$_web_push_dir : "");
        if (empty($dir)) {
            $home = (function_exists("posix_getpwuid") ? (posix_getpwuid(posix_geteuid())["dir"] ?? "") : "");
            $home = (!empty($home) ? $home : (getenv("HOME") ?: getenv("USERPROFILE")));
            $dir = match (PHP_OS_FAMILY) {
                "Windows" => (getenv("APPDATA") ?: "{$home}\\AppData\\Roaming") . "\\dwf",
                "Darwin" => "{$home}/Library/Application Support/dwf",
                default => (getenv("XDG_CONFIG_HOME") ?: "{$home}/.config") . "/dwf",
            };
        }
        $file = rtrim($dir, "/\\") . DIRECTORY_SEPARATOR . "web_push_" . substr(hash("sha256", realpath($root)), 0, 16) . ".pem";
        if (!is_dir(dirname($file)) and !mkdir(dirname($file), 0700, true)) {
            dwf_exception::warning_exception(631, ["__" => dirname($file)]);
            return;
        }
        if (!file_exists($file) and file_exists($old = "{$root}/class/web_push_vapid.pem")) {
            rename($old, $file);
        }
        if (!file_exists($file)) {
            if (!($key = openssl_pkey_new(self::KEY_CONFIG)) or !openssl_pkey_export($key, $pem) or !file_put_contents($file, $pem)) {
                dwf_exception::warning_exception(641, ["__" => $file, "__e__" => "génération ou enregistrement impossible : " . openssl_error_string()]);
                return;
            }
            chmod($file, 0640);
        }
        if (!($this->_private = openssl_pkey_get_private(file_get_contents($file)))) {
            dwf_exception::warning_exception(641, ["__" => $file, "__e__" => "clé illisible ou invalide"]);
            return;
        }
        $this->_public = self::b64u_encode(self::raw_public_key($this->_private));
    }

    /**
     * Retourne le bouton d'activation des notifications et son script :
     * demande de permission, enregistrement du service worker, abonnement push et envoi de l'abonnement au service s_web_push
     * 
     * @param string $text Texte du bouton
     * @param string $class Classe CSS du bouton
     * @return string Bouton et script (vide si les clés VAPID sont indisponibles)
     */
    public function button($text = "Activer les notifications", $class = "btn btn-primary") {
        if (empty($this->_public)) {
            return "";
        }
        if (empty(session::get_val("web_push_token"))) {
            session::set_val("web_push_token", bin2hex(random_bytes(16)));
        }
        $csp = csp::get_instance();
        (isset($csp->get()["worker-src"]) ? $csp->add("worker-src", "'self'") : $csp->set("worker-src", "'self'"));
        $id = "web_push_" . substr(sha1($this->_public), 0, 8);
        $key = json_encode($this->_public);
        $token = json_encode(session::get_val("web_push_token"));
        return tags::tag("button", ["type" => "button", "id" => $id, "class" => $class], $text) .
                html_structures::script_inline(<<<JS
$(document).ready(function () {
    var btn = $("#{$id}");
    if (!window.isSecureContext || !("serviceWorker" in navigator) || !("PushManager" in window) || !("Notification" in window)) {
        btn.prop("disabled", true).attr("title", "Notifications indisponibles : navigateur incompatible ou site non sécurisé (HTTPS ou localhost requis)");
        return;
    }
    var raw = atob({$key}.replace(/-/g, "+").replace(/_/g, "/"));
    var key = new Uint8Array(raw.length);
    for (var i = 0; i < raw.length; i++) {
        key[i] = raw.charCodeAt(i);
    }
    btn.on("click", function () {
        Notification.requestPermission().then(function (permission) {
            if (permission !== "granted") {
                throw new Error("permission refusée par le navigateur");
            }
            return navigator.serviceWorker.register("./src/web_push_sw.js", {scope: "./src/"});
        }).then(function () {
            return navigator.serviceWorker.ready;
        }).then(function (reg) {
            return reg.pushManager.getSubscription().then(function (sub) {
                if (sub && sub.options.applicationServerKey && new Uint8Array(sub.options.applicationServerKey).toString() !== key.toString()) {
                    return sub.unsubscribe().then(function () {
                        return null;
                    });
                }
                return sub;
            }).then(function (sub) {
                return sub || reg.pushManager.subscribe({userVisibleOnly: true, applicationServerKey: key});
            });
        }).then(function (sub) {
            $.post("./services/index.php?service=s_web_push", {subscription: JSON.stringify(sub), token: {$token}}, function (data) {
                (data.ok ? alertify.success(data.msg) : alertify.error(data.msg));
            }, "json").fail(function () {
                alertify.error("Notifications : le service d'abonnement ne répond pas");
            });
        }).catch(function (e) {
            alertify.error("Notifications : " + e.message);
        });
    });
});
JS);
    }

    /**
     * Enregistre l'abonnement envoyé par le navigateur ($_POST["subscription"] en JSON et $_POST["token"])
     * à appeler depuis un service (cf services/s_web_push.service.php), l'abonnement est rattaché à l'utilisateur connecté (0 si non connecté)
     * 
     * @return array Résultat ["ok" => true/false, "msg" => message]
     */
    public static function subscribe() {
        $token = session::get_val("web_push_token");
        if (empty($token) or !isset($_POST["token"]) or !is_string($_POST["token"]) or !hash_equals($token, $_POST["token"])) {
            return ["ok" => false, "msg" => "Jeton de sécurité invalide, rechargez la page."];
        }
        $sub = (isset($_POST["subscription"]) and is_string($_POST["subscription"])) ? json_decode($_POST["subscription"], true) : null;
        $endpoint = $sub["endpoint"] ?? null;
        $p256dh = $sub["keys"]["p256dh"] ?? null;
        $auth = $sub["keys"]["auth"] ?? null;
        if (!is_string($endpoint) or !is_string($p256dh) or !is_string($auth) or !self::check_endpoint($endpoint) or strlen(self::b64u_decode($auth)) != 16 or !self::public_key(self::b64u_decode($p256dh))) {
            return ["ok" => false, "msg" => "Abonnement refusé : service push inconnu ou clés invalides."];
        }
        $user = (int) session::get_user_id();
        if (!empty($subs = web_push_subscription::get_collection("endpoint=:endpoint", [":endpoint" => $endpoint]))) {
            $subs[0]->set_p256dh($p256dh);
            $subs[0]->set_auth($auth);
            $subs[0]->set_user($user);
            $subs[0]->set_mt(time());
        } else {
            web_push_subscription::ajout($endpoint, $p256dh, $auth, $user, time());
        }
        return ["ok" => true, "msg" => "Notifications activées !"];
    }

    /**
     * Envoie une notification à tous les abonnés ou aux abonnements d'un utilisateur,
     * les abonnements expirés (réponse 404 ou 410 du service push) sont supprimés.
     * Les erreurs sont signalées sans interrompre le script (dwf_exception::warning_exception)
     * 
     * @param string $title Titre de la notification
     * @param string $body Texte de la notification
     * @param string $url URL ouverte au clic, relative à la racine du projet ("index.php" par défaut)
     * @param int $user Id de l'utilisateur destinataire (null = tous les abonnements)
     * @param int $ttl Durée de conservation par le service push en secondes si l'appareil est hors ligne (86400 par défaut)
     * @return array Bilan de l'envoi ["sent" => envoyées, "expired" => abonnements expirés supprimés, "failed" => échecs]
     */
    public function send($title, $body, $url = "index.php", $user = null, $ttl = 86400) {
        $result = ["sent" => 0, "expired" => 0, "failed" => 0];
        if (empty($this->_public)) {
            return $result;
        }
        $payload = json_encode([
            "title" => $title,
            "body" => $body,
            "url" => $url,
            "icon" => (!empty(config::$_favicon) ? config::$_favicon : "")
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if (strlen($payload) > 3993) {
            dwf_exception::warning_exception(642, ["__e__" => "notification trop volumineuse : " . strlen($payload) . " octets, 3993 maximum"]);
            return $result;
        }
        $jwts = [];
        foreach (($user === null ? web_push_subscription::get_collection() : web_push_subscription::get_collection("user=:user", [":user" => (int) $user])) as $sub) {
            if (!self::check_endpoint($endpoint = $sub->get_endpoint()) or !($ua_key = self::public_key($ua_pub = self::b64u_decode($sub->get_p256dh()))) or strlen($auth = self::b64u_decode($sub->get_auth())) != 16) {
                $sub->delete();
                $result["failed"]++;
                continue;
            }
            $aud = parse_url($endpoint, PHP_URL_SCHEME) . "://" . parse_url($endpoint, PHP_URL_HOST);
            if (!isset($jwts[$aud])) {
                $jwt = self::b64u_encode(json_encode(["typ" => "JWT", "alg" => "ES256"])) . "." .
                        self::b64u_encode(json_encode(["aud" => $aud, "exp" => time() + 43200, "sub" => $this->_subject], JSON_UNESCAPED_SLASHES));
                if (!openssl_sign($jwt, $der, $this->_private, OPENSSL_ALGO_SHA256)) {
                    dwf_exception::warning_exception(642, ["__e__" => "échec de la signature VAPID : " . openssl_error_string()]);
                    return $result;
                }
                $r = substr($der, 4, ord($der[3]));
                $s = substr($der, 6 + strlen($r), ord($der[5 + strlen($r)]));
                $jwts[$aud] = $jwt . "." . self::b64u_encode(str_pad(ltrim($r, "\0"), 32, "\0", STR_PAD_LEFT) . str_pad(ltrim($s, "\0"), 32, "\0", STR_PAD_LEFT));
            }
            if (!($as_key = openssl_pkey_new(self::KEY_CONFIG)) or !($ecdh = openssl_pkey_derive($ua_key, $as_key, 32))) {
                dwf_exception::warning_exception(642, ["__e__" => "échec du chiffrement : " . openssl_error_string()]);
                $result["failed"]++;
                continue;
            }
            $as_pub = self::raw_public_key($as_key);
            $salt = random_bytes(16);
            $ikm = hash_hkdf("sha256", $ecdh, 32, "WebPush: info\0" . $ua_pub . $as_pub, $auth);
            $cipher = openssl_encrypt($payload . "\x02", "aes-128-gcm", hash_hkdf("sha256", $ikm, 16, "Content-Encoding: aes128gcm\0", $salt), OPENSSL_RAW_DATA, hash_hkdf("sha256", $ikm, 12, "Content-Encoding: nonce\0", $salt), $tag);
            $curl = curl_init($endpoint);
            curl_setopt_array($curl, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $salt . pack("N", 4096) . chr(65) . $as_pub . $cipher . $tag,
                CURLOPT_HTTPHEADER => [
                    "Authorization: vapid t={$jwts[$aud]}, k={$this->_public}",
                    "Content-Encoding: aes128gcm",
                    "Content-Type: application/octet-stream",
                    "TTL: " . (int) $ttl,
                    "Urgency: normal",
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 10,
            ]);
            $response = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
            $error = curl_error($curl);
            curl_close($curl);
            if ($code >= 200 and $code < 300) {
                $result["sent"]++;
            } elseif (in_array($code, [404, 410])) {
                $sub->delete();
                $result["expired"]++;
            } else {
                $result["failed"]++;
                dwf_exception::warning_exception(643, ["__h__" => parse_url($endpoint, PHP_URL_HOST), "__c__" => $code, "__e__" => (!empty($error) ? $error : substr(strip_tags((string) $response), 0, 200))]);
            }
        }
        return $result;
    }

    /**
     * Vérifie qu'un endpoint est une URL HTTPS d'un service push autorisé (cf web_push::$_push_hosts)
     * 
     * @param string $endpoint URL de l'endpoint
     * @return boolean L'endpoint est-il autorisé ? (true/false)
     */
    private static function check_endpoint($endpoint) {
        $url = parse_url($endpoint);
        if (strlen($endpoint) > 1024 or !filter_var($endpoint, FILTER_VALIDATE_URL) or empty($url["host"]) or ($url["scheme"] ?? "") !== "https" or isset($url["user"]) or isset($url["pass"]) or (isset($url["port"]) and $url["port"] != 443)) {
            return false;
        }
        $host = strtolower($url["host"]);
        foreach (self::$_push_hosts as $suffix) {
            if ($host === $suffix or str_ends_with($host, "." . $suffix)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retourne la clé OpenSSL d'un point P-256 brut non compressé (65 octets), false si le point est invalide
     * 
     * @param string $raw Point P-256 brut (0x04 || X || Y)
     * @return OpenSSLAsymmetricKey|false Clé publique OpenSSL ou false
     */
    private static function public_key($raw) {
        if (strlen($raw) != 65 or $raw[0] !== "\x04") {
            return false;
        }
        return openssl_pkey_get_public("-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode(hex2bin(self::SPKI_PREFIX) . $raw), 64, "\n") . "-----END PUBLIC KEY-----\n");
    }

    /**
     * Retourne le point public brut non compressé (65 octets) d'une clé P-256
     * 
     * @param OpenSSLAsymmetricKey $key Clé P-256
     * @return string Point public brut (0x04 || X || Y)
     */
    private static function raw_public_key($key) {
        $ec = openssl_pkey_get_details($key)["ec"];
        return "\x04" . str_pad($ec["x"], 32, "\0", STR_PAD_LEFT) . str_pad($ec["y"], 32, "\0", STR_PAD_LEFT);
    }

    /**
     * Encode une chaine en base64url sans padding
     * 
     * @param string $data Données binaires
     * @return string Chaine base64url
     */
    private static function b64u_encode($data) {
        return rtrim(strtr(base64_encode($data), "+/", "-_"), "=");
    }

    /**
     * Décode une chaine base64url (avec ou sans padding)
     * 
     * @param string $data Chaine base64url
     * @return string Données binaires (chaine vide si invalide)
     */
    private static function b64u_decode($data) {
        return (string) base64_decode(strtr($data, "-_", "+/"), true);
    }
}
