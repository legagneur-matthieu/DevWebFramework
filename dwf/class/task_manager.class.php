<?php

/**
 * Cette classe permet de gérer les taches planifié
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class task_manager {

    /**
     * Initialise les éléments nécessaire a task_manager
     * A utiliser dans l'application web
     */
    public static function init() {
        export_dwf::add_files([realpath(__DIR__ . "/task_tpl")]);
        if (!class_exists("cli")) {
            include __DIR__ . '/cli.class.php';
            cli::classloader();
        }
        if (!file_exists("./task_worker")) {
            mkdir("./task_worker");
        }
        if (!file_exists("./task_worker/.htaccess")) {
            file_put_contents("./task_worker/.htaccess", "Order Deny,Allow\nDeny from All\nAllow from localhost");
        }
        if (!file_exists("./task_worker/run.php")) {
            file_put_contents("./task_worker/run.php", file_get_contents(__DIR__ . "/task_tpl/run"));
        }
        entity_generator::generate([
            "task" => [
                ["id", "int", true],
                ["status", "int", false], //0 : planifié, 1 : Executé
                ["t_create", "int", false],
                ["t_exec", "int", false],
                ["worker", "string", false],
                ["params", "array", false]
            ]
        ]);
    }

    /**
     * Ajoute une tache a la fille d'atente
     * @param int $exec_timestamp Date d'execution de la tache, un timestamp UNIX est attendu, 0 pour une execution immediate.
     * @param string $worker Le worker de la tache
     * @param array $params Les parametres de la tache (tableau vide par defaut)
     */
    public static function add($exec_timestamp, $worker, $params = []) {
        task::ajout(0, time(), $exec_timestamp, $worker, $params);
    }

    /**
     * Supprime les task éxécuté les plus anciènes
     * 30 jours par defaut, 24h minimum !
     * @param int $expire_time temps d'archivage des taches éxécuté
     */
    public static function cleanup($expire_time = 2592000) {
        if (math::is_int($expire_time) && $expire_time >= 84600) {
            task::get_table_array("status=1 and t_exec < :texec", [":texec" => (time() - $expire_time)]);
        } else {
            dwf_exception::warning_exception(600, ["msg" => "task_manager::cleanup(), expire_time invalid !"]);
        }
    }

    /**
     * Returne les taches dans un tableau
     * trié selon la date d'execution de la plus loin dans le future a la plus anciènne
     * @param int $expire_time temps d'archivage des taches éxécuté
     * @return array Tableau des taches
     */
    public static function get_tasks($expire_time = 2592000) {
        self::cleanup($expire_time = 2592000);
        return task::get_table_array("1=1 order by t_exec desc");
    }

    /**
     * Affiche les tableaux de taches en cours et términé
     * @param int $expire_time temps d'archivage des taches éxécuté
     */
    public static function print_tasks($expire_time = 2592000) {
        $head = ["ID", "Worker", "Création", "Execution", "Params (mouseover)"];
        $data_0 = [];
        $data_1 = [];
        foreach (self::get_tasks($expire_time) as $task) {
            if ($task["status"] == 0) {
                $data_0[] = [$task["id"], $task["worker"], date("Y-m-d H:i:s", $task["t_create"]), ($task["t_exec"] <= $task["t_create"] ? "0, Immediate" : date("Y-m-d H:i:s", $task["t_exec"])), tags::tag("span", ["title" => json_encode($task["params"])], "Params")];
            } else {
                $data_1[] = [$task["id"], $task["worker"], date("Y-m-d H:i:s", $task["t_create"]), ($task["t_exec"] <= $task["t_create"] ? "0, Immediate" : date("Y-m-d H:i:s", $task["t_exec"])), tags::tag("span", ["title" => json_encode($task["params"])], "Params")];
            }
        }
        echo tags::tag("h2", ["class" => "text-center"], "Taches en attentes") .
        html_structures::table($head, $data_0) .
        tags::tag("h2", ["class" => "text-center"], "Taches terminées") .
        html_structures::table($head, $data_1);
    }

    /**
     * /!\ Ne pas utiliser ! Est utilisé dans task_worker/run.php
     * Lance l'execution des taches.
     */
    public static function run() {
        cli::write(date("Y-m-d H:i:s") . " DWF Task Manager start for " . config::$_title . " project !");
        cli::write(self::get_waiting_tasks_lenght());
        while (true) {
            $sleep = true;
            foreach (task::get_collection("status=0 and t_exec<=:texec", [":texec" => time()]) as $task) {
                $sleep = false;
                $worker = $task->get_worker();
                if (file_exists("./$worker.worker.php") && class_exists($worker)) {
                    cli::write(date("Y-m-d H:i:s") . " Task #{$task->get_id()} {$task->get_worker()} (C " .
                            date("Y-m-d H:i:s", $task->get_t_create()) . " | E " .
                            date("Y-m-d H:i:s", $task->get_t_exec()) . ") in execution ...");
                    $task->set_status(1);
                    time::chronometer_start(__CLASS__);
                    $worker::run($task->get_params());
                    $task->update();
                    cli::write(date("Y-m-d H:i:s") . " Task #{$task->get_id()} DONE ! (" . time::parse_time(time::chronometer_get(__CLASS__)) . ")");
                }
            }
            if ($sleep) {
                cli::rewrite(self::get_waiting_tasks_lenght());
                cli::wait(1);
            } else {
                cli::write(self::get_waiting_tasks_lenght());
            }
        }
    }

    private static function get_waiting_tasks_lenght() {
        return date("Y-m-d H:i:s") . " " . task::get_count("status=0") . " Taches planifie";
    }
}
