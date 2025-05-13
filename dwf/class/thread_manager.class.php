<?php

/**
 * Cette classe permet de milti-thread une fonction static avec un tableau de données
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class thread_manager {

    /** @var int Nombre maximum de threads simultanés autorisés */
    private $maxthread;

    /** @var int[] Liste des IDs de threads en cours d'exécution */
    private $threads = [];

    /** @var array Résultats des threads terminés, indexés par ID */
    private $results = [];

    /** @var float[] Timestamps de démarrage de chaque thread (en microsecondes) */
    private $thread_times_start = [];

    /** @var float[] Durée d'exécution des derniers threads terminés */
    private $thread_times = [];

    /**
     * Cette classe permet de milti-thread une fonction static avec un tableau de données
     *
     * @param array $data Données à traiter (chaque élément est un tableau de paramètres)
     * @param string $static_function Nom de la méthode statique à appeler (exemple : maclass::mafonction )
     * @param int $maxthread Nombre maximal de threads simultanés (défaut = nb de cœurs CPU)
     */
    public function __construct($data, $static_function, $maxthread = 0) {
        if (!$maxthread) {
            $maxthread = $this->get_nbcore();
        }
        $this->maxthread = $maxthread;
        entity_generator::generate([
            "dwf_thread" => [
                ["id", "int", true],
                ["created_at", "int", false],
                ["statut", "bool", false],
                ["output", "string", false],
                ["f", "string", false],
                ["params", "array", false],
            ]
        ]);
        application::$_bdd->query("delete from dwf_thread where created_at<:time", [
            ":time" => (time() - (84600 * 31))
        ]);
        if (!file_exists("./services/thread.service.php")) {
            copy(__DIR__ . "/thread_tpl/thread", "./services/thread.service.php");
        }
        foreach ($data as $params) {
            while (!$this->get_clean()) {
                usleep($this->get_wait_time());
            }

            if (!is_array($params)) {
                $params = [$params];
            }

            $id = dwf_thread::ajout(time(), 0, "", $static_function, $params)->get_id();
            $this->threads[$id] = $id;
            $this->thread_times_start[$id] = microtime(true);
            service::HTTP_POST($this->get_service_url(), ["service" => "thread", "id" => $id]);
        }

        while (count($this->threads)) {
            $this->get_clean();
            usleep($this->get_wait_time());
        }
    }

    /**
     * Récupère les résultats des threads terminés.
     *
     * @return array Résultats indexés par ID
     */
    public function get_results() {
        ksort($this->results);
        return $this->results;
    }

    /**
     * Calcule le temps d'attente avant de relancer une vérification.
     * Basé sur la moyenne glissante des durées de threads récents.
     *
     * @return int Temps en microsecondes
     */
    private function get_wait_time() {
        if (count($this->thread_times)) {
            $avg = array_sum($this->thread_times) / count($this->thread_times);
            return min(1000000, max(100000, (int) ($avg * 1000000 * 0.30)));
        } else {
            return 100000;
        }
    }

    /**
     * Vérifie quels threads sont terminés et collecte les résultats.
     * Met également à jour la liste des threads actifs.
     *
     * @return bool True si au moins un thread est terminé ou si un nouveau peut être lancé
     */
    private function get_clean() {
        $return = false;
        $threads = dwf_thread::get_table_array("id in (:ids)", [":ids" => $this->threads]);
        foreach ($threads as $thread) {
            if ($thread["statut"] == 1) {
                $this->results[$thread["id"]] = $thread["output"];
                $this->thread_times[] = microtime(true) - $this->thread_times_start[$thread["id"]];
                if (count($this->thread_times) > 10) {
                    array_shift($this->thread_times);
                }
                $return = true;
                unset($this->threads[$thread["id"]]);
            }
        }
        if ($return) {
            $threads = [];
            foreach (array_values($this->threads) as $id) {
                $threads[$id] = $id;
            }
            $this->threads = $threads;
        }
        if (count($this->threads) < $this->maxthread) {
            $return = true;
        }
        return $return;
    }

    /**
     * Détecte automatiquement le nombre de cœurs logiques de l'OS courant.
     *
     * @return int Nombre de cœurs
     */
    private function get_nbcore() {
        if (strncasecmp(PHP_OS, 'Linux', 5) === 0) {
            $count = (int) trim(shell_exec('nproc 2>/dev/null'));
            if ($count > 0) {
                return $count;
            }
        }
        if (strncasecmp(PHP_OS, 'Darwin', 6) === 0) {
            $count = (int) trim(shell_exec("sysctl -n hw.logicalcpu 2>/dev/null"));
            if ($count > 0) {
                return $count;
            }
        }
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            $count = (int) getenv('NUMBER_OF_PROCESSORS');
            if ($count > 0) {
                return $count;
            }
        }
        return 1;
    }

    /**
     * Construit l'URL du service de thread à appeler via HTTP.
     *
     * @return string URL complète du service
     */
    private function get_service_url() {
        $loc = application::get_loc();
        if (strpos($loc, 'index.php') !== false) {
            $loc = substr($loc, 0, strpos($loc, 'index.php'));
        }
        if (substr($loc, -1) !== '/') {
            $loc .= '/';
        }
        return $loc . 'services/index.php';
    }
}
