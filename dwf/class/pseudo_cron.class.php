<?php

/**
 * Cette classe permet de lancer des pseudo cron : 
 * L'activation du pseudo cron se fait par comparaison du timestamp lors d'une activité utilisateur
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class pseudo_cron extends singleton {

    /**
     * Liste des pseudos cron
     * @var array Liste des pseudos cron
     */
    private $_pcron;

    /**
     * Type de stockage des pseudos cron
     * @var string Type de stockage des pseudos cron
     */
    private $_db;

    /**
     * Chemin d'accès réel/absolu au fichier pcron.js
     * @var string Chemin d'accès réel/absolu au fichier pcron.js
     */
    private $_rp;

    /**
     * Durée de vie maximale d'un pseudo cron inactif (en secondes)
     * @var int Durée de vie maximale d'un pseudo cron inactif (en secondes)
     */
    private $_ttl = 31536000;

    /**
     * Cette classe permet de lancer des pseudos cron : 
     * L'activation du pseudo cron se fait par comparaison du timestamp lors d'une activité utilisateur
     * 
     * @param string $db Type de stockage des pseudos cron :
     * - sql : par defaut, une entity "pcron" sera créée
     * - json : un dossier "pcron" sera créée dans le dossier "class" du projet
     */
    public function __construct($db = "sql") {
        $this->_db = $db;
        switch ($db) {
            case "json":
                if (!file_exists($file = "./class/pcron")) {
                    mkdir($file);
                }
                if (!file_exists($file = "./class/pcron/pcron.json")) {
                    file_put_contents($file, "[]");
                }
                $this->_rp = realpath($file);
                dwf_exception::check_file_writed($file);
                $this->_pcron = json_decode(file_get_contents($file), true);
                break;
            case "sql":
            default:
                entity_generator::generate([
                    "pcron" => [
                        ["id", "int", true],
                        ["hkey", "string", false],
                        ["mt", "string", false],
                    ]
                ]);
                $this->_pcron = pcron::get_table_array();
                break;
        }
    }

    /**
     * Retourne de timestamp de la dernière exécution d'un pseudo cron
     * @param string $hkey hkey du pseudo cron
     * @return float Timestamp de la dernière exécution
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
     * Créé une nouvelle entrée dans le registre des pseudos cron
     * @param string $hkey hkey du pseudo cron
     */
    private function new_pcron($hkey) {
        switch ($this->_db) {
            case "json":
                $this->_pcron[] = ["hkey" => $hkey, "mt" => 0];
                file_put_contents($this->_rp, json_encode($this->_pcron));
                break;
            case "sql":
            default:
                pcron::ajout($hkey, 0);
                $this->_pcron = pcron::get_table_array();
                break;
        }
    }

    /**
     * Sauvegarde le nouveau timestamp du pseudo cron
     * @param string $hkey hkey du pseudo cron
     */
    private function save($hkey) {
        switch ($this->_db) {
            case "json":
                foreach ($this->_pcron as $key => $pcron) {
                    if ($pcron["hkey"] === $hkey) {
                        $this->_pcron[$key]["mt"] = microtime(true);
                        break;
                    }
                }
                file_put_contents($this->_rp, json_encode($this->_pcron));
                break;
            case "sql":
            default:
                application::$_bdd->query("UPDATE `pcron` SET mt=:mt WHERE hkey=:hkey", [":mt" => microtime(true), ":hkey" => $hkey]);
                $this->_pcron = pcron::get_table_array();
                break;
        }
    }

    /**
     * Retourne les données du pseudo cron "fn"
     * @param Closure $callback Fonction du pseudo cron
     * @return array Données du pseudo cron
     */
    private function get_callable_data(Closure $callback) {
        $hkey = application::hash((new ReflectionFunction($callback))->export($callback, true));
        return ["hkey" => $hkey, "mt" => $this->get_mt_from_hkey($hkey)];
    }

    /**
     * Retourne les données du pseudo cron "file"
     * @param string $file Fichier du pseudo cron
     * @return array Données du pseudo cron
     */
    private function get_file_data($file) {
        $hkey = application::hash($file);
        return ["hkey" => $hkey, "mt" => $this->get_mt_from_hkey($hkey)];
    }

    /**
     * Redéfinit la durée de vie maximale d'un pseudo cron inactif
     * @param int $ttl Durée de vie maximale d'un pseudo cron inactif (en secondes, 31536000 par défaut soit 1 an)
     */
    public function set_clear($ttl = 31536000) {
        $this->_ttl = $ttl;
    }

    /**
     * Exécute la fonction de callback lors d'une activité utilisateur si elle n'a pas été éxécutée depuis le temps défini dans l'intervalle
     * @param int $interval Intervalle de temps minimal entre deux exécutions (en secondes)
     * @param Closure $callback Fonction à executer
     * @param array $args Eventuels arguments de la fonction
     * @return Mixed Retour de la foncion si elle retourne une valeur, null si la fonction de retourne rien ou n'est pas executée
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
     * Exécute un fichier php lors d'une activité utilisateur si elle n'a pas été éxécutée depuis le temps défini dans l'intervalle
     * Requiert que php soit reconnu comme commande interne du serveur.
     * A MANIPULER AVEC PRECAUTIONS !
     * @param int $interval Intervalle de temps minimal entre deux exécutions (en secondes)
     * @param string $file Chemin absolu vers le fichier php à exécuter 
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
     * Supprime les entrées des pseudos cron qui n'ont pas été éxécuté depuis le temps défini par pseudo_cron::set_clear() (1 an par défaut)
     */
    public function __destruct() {
        $hkeys = [];
        foreach ($this->_pcron as $key => $pcron) {
            if (isset($pcron["mt"]) and $pcron["mt"] < microtime(true) - $this->_ttl) {
                switch ($this->_db) {
                    case "json":
                        unset($this->_pcron[$key]);
                        break;
                    case "sql":
                    default:
                        $hkeys[] = "'" . bdd::p($pcron["hkey"]) . "'";
                        break;
                }
            }
        }
        switch ($this->_db) {
            case "json":
                $pcron = [];
                foreach ($this->_pcron as $value) {
                    if ($value !== null) {
                        $pcron[] = $value;
                    }
                }
                file_put_contents($this->_rp, json_encode($pcron));
                break;
            case "sql":
            default:
                if (count($hkeys) > 0) {
                    foreach ($hkeys as $hkey) {
                        application::$_bdd->query("DELETE FROM `pcron` WHERE hkey=:hkey", [":hkey" => $hkey]);
                    }
                }
                break;
        }
    }
}
