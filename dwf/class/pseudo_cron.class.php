<?php

/**
 * Cette classe permet de lancer des pseudo cron : 
 * L'activation du pseudo cron ce fait par comparaison du timestamp lors d'une activité utilisateur
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class pseudo_cron {

    /**
     * Liste des pseudo cron
     * @var array Liste des pseudo cron
     */
    private $_pcron;

    /**
     * Type de stockage des pseudo cron
     * @var string Type de stockage des pseudo cron
     */
    private $_db;

    /**
     * Durée de vie maximal d'un pseudo cron inactif (en secondes)
     * @var int Durée de vie maximal d'un pseudo cron inactif (en secondes)
     */
    private $_ttl = 31536000;

    /**
     * Cette classe permet de lancer des pseudo cron : 
     * L'activation du pseudo cron ce fait par comparaison du timestamp lors d'une activité utilisateur
     * 
     * @param string $db Type de stockage des pseudo cron :
     * - sql : par defaut, une entity "pcron" sera créé
     * - json : un dossier "pcron" sera créé dans le dossier "class" du projet
     */
    public function __construct($db = "sql") {
        $this->_db = $db;
        switch ($db) {
            case "sql":
                entity_generator::generate([
                    "pcron" => [
                        ["id", "int", true],
                        ["hkey", "string", false],
                        ["mt", "string", false],
                    ]
                ]);
                $this->_pcron = pcron::get_table_array();
                break;
            case "json":
            default:
                if (!file_exists($file = "./class/pcron")) {
                    mkdir($file);
                }
                if (!file_exists($file = "./class/pcron/pcron.json")) {
                    file_put_contents($file, "[]");
                }
                dwf_exception::check_file_writed($file);
                $this->_pcron = json_decode(file_get_contents($file), true);
                break;
        }
    }

    /**
     * Retourne de timestamp de la dernière execution d'un pseudo cron
     * @param string $hkey hkey du pseudo cron
     * @return float Timestamp de la dernière execution
     */
    private function get_mt_from_hkey($hkey) {
        foreach ($this->_pcron as $pcron) {
            if ($pcron["hkey"] === $hkey) {
                return $pcron["mt"];
            }
        }
        $this->new_pcron($hkey);
        return 0;
    }

    /**
     * Créé une nouvelle entré dans le registre des pseudo cron
     * @param string $hkey hkey du pseudo cron
     */
    private function new_pcron($hkey) {
        switch ($this->_db) {
            case "sql":
                pcron::ajout($hkey, 0);
                $this->_pcron = pcron::get_table_array();
                break;
            case "json":
            default:
                $this->_pcron[] = ["hkey" => $hkey, "mt" => 0];
                file_put_contents("./class/pcron/pcron.json", json_encode($this->_pcron));
                break;
        }
    }

    /**
     * Sauvegarde le nouveau timestamp du pseudo cron
     * @param string $hkey hkey du pseudo cron
     */
    private function save($hkey) {
        switch ($this->_db) {
            case "sql":
                application::$_bdd->query("UPDATE `pcron` SET mt='" . application::$_bdd->protect_var(microtime(true)) . "' WHERE hkey='" . application::$_bdd->protect_var($hkey) . "';");
                break;
            case "json":
            default:
                foreach ($this->_pcron as $key => $pcron) {
                    if ($pcron["hkey"] === $hkey) {
                        $this->_pcron[$key]["mt"] = microtime(true);
                        break;
                    }
                }
                file_put_contents("./class/pcron/pcron.json", json_encode($this->_pcron));
                break;
        }
    }

    /**
     * Retourne les données du pseudo cron "fn"
     * @param Closure $callback Fonction du pseudo cron
     * @return array Données du pseudo cron
     */
    private function get_callable_data(Closure $callback) {
        $hkey = hash(config::$_hash_algo, (new ReflectionFunction($callback))->export($callback, true));
        return ["hkey" => $hkey, "mt" => $this->get_mt_from_hkey($hkey)];
    }

    /**
     * Retourne les données du pseudo cron "file"
     * @param string $file Fichier du pseudo cron
     * @return array Données du pseudo cron
     */
    private function get_file_data($file) {
        $hkey = hash(config::$_hash_algo, $file);
        return ["hkey" => $hkey, "mt" => $this->get_mt_from_hkey($hkey)];
    }

    /**
     * redefini la durée de vie maximal d'un pseudo cron inactif
     * @param int $ttl Durée de vie maximal d'un pseudo cron inactif (en secondes, 31536000 par defaut soit 1 an)
     */
    public function set_clear($ttl = 31536000) {
        $this->_ttl = $ttl;
    }

    /**
     * Execute la fonction de callback lors d'une activité utilisateur si elle n'a pas été éxécuté depuis le temps défini dans l'interval
     * @param int $interval Intervale de temps minimal entre deux executions (en secondes)
     * @param Closure $callback Fonction à executer
     * @param array $args eventuels arguments de la fonction
     * @return Mixed Retour de la foncion si elle retourne une valeur, null si la fonction de retourne rien ou n'est pas executé
     */
    public function fn($interval, Closure $callback, $args = []) {
        $result = null;
        $data = $this->get_callable_data($callback);
        if ($data["mt"] < microtime(true) - $interval) {
            $result = call_user_func_array($callback, $args);
            $this->save($data["hkey"]);
        }
        return $result;
    }

    /**
     * Execute un fichier php lors d'une activité utilisateur si elle n'a pas été éxécuté depuis le temps défini dans l'interval
     * Requière que php soit reconue comme commande interne du serveur.
     * A MANIPULER AVEC PRECAUTIONS !
     * @param int $interval Intervale de temps minimal entre deux executions (en secondes)
     * @param string $file Chemain absolu vers le fichier php a executer 
     * @return string|null Retour de la console, null si le fichier n'est pas éxécuté
     */
    public function file($interval, $file) {
        $result = null;
        $data = $this->get_file_data($file);
        if ($data["mt"] < microtime(true) - $interval) {
            $result = shell_exec("php -f " . $file);
            $this->save($data["hkey"]);
        }
        return $result;
    }

    /**
     * supprime les entrées des pseudo cron qui n'ont pas été éxécuté depuis le temps défini par pseudo_cron::set_clear() (1 an par défaut)
     */
    public function __destruct() {
        $hkeys = [];
        foreach ($this->_pcron as $key => $pcron) {
            if (isset($pcron["mt"]) and $pcron["mt"] < microtime(true) - $this->_ttl) {
                switch ($this->_db) {
                    case "sql":
                        $hkeys[] = "'" . application::$_bdd->protect_var($pcron["hkey"]) . "'";
                        break;
                    case "json":
                    default:
                        unset($this->_pcron[$key]);
                        break;
                }
            }
        }
        switch ($this->_db) {
            case "sql":
                if (count($hkeys) > 0) {
                    application::$_bdd->query("DELETE FROM `pcron` WHERE hkey in(" . implode(",", $hkeys) . ");");
                }
                break;
            case "json":
            default:
                $pcron = [];
                foreach ($this->_pcron as $value) {
                    if ($value !== null) {
                        $pcron[] = $value;
                    }
                }
                file_put_contents("./class/pcron/pcron.json", json_encode($pcron));
                break;
        }
    }

}
