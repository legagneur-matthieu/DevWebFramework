<?php

/**
 * Cette classe permet de gérer les mises à jour de DWF
 * (a plasser dans une inerface d'administration)
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class update_dwf {

    private $_dwf_repo = "https://github.com/legagneur-matthieu/devwebframework.git";

    /**
     * Cette classe permet de gérer les mises à jour de DWF
     * (a plasser dans une inerface d'administration)
     */
    public function __construct() {
        set_time_limit(0);
        $out = [];
        exec("git --version", $out);
        if (count($out) > 0) {
            if (!file_exists("../../.git")) {
                $this->create_git();
            }
            $dwf_path = dirname(realpath("../../.git"));
            exec("cd \"" . $dwf_path . "\"");
            if (isset($_POST["dwf_update"])) {
                exec("git pull " . $this->_dwf_repo);
                js::alertify_alert("DWF a été mis à jour !");
            }
            $local_tags = [];
            $remote_tags = [];
            exec("git fetch " . $this->_dwf_repo, $out);
            exec("git describe --tags", $local_tags);
            exec("git ls-remote --tags " . $this->_dwf_repo, $remote_tags);
            $maj = $this->compare_version($local_tags, $remote_tags);
            echo html_structures::table(["Version GIT courante", "Version DWF courante", "Dernière version DWF disponible", "Status / Mise à jour"], [
                [$out[0], $maj["local"], $maj["remote"],
                    ( $maj["maj"] ?
                            '<form action="#" method="post">'
                            . '<div class="form-group">'
                            . '<input type="submit" class="btn btn-block btn-primary" value="' . $maj["msg"] . '" name="dwf_update" />'
                            . '</div>'
                            . '</form>' :
                            $maj["msg"]
                    )
                ]
            ]);
        } else {
            dwf_exception::warning_exception(632);
        }
    }

    /**
     *  Créé le dossier .git si il n'existe pas
     */
    private function create_git() {
        $dwf_path = dirname(realpath("../../dwf"));
        exec("cd \"" . $dwf_path . "\"");
        exec("git init");
        exec("git remote add origin " . $this->_dwf_repo);
        exec("git fetch " . $this->_dwf_repo);
        exec("git reset origin/master");
        js::alertify_alert($dwf_path . ".git introuvable ! <br />Création de .git depuis la dernière version de DWF ... <br />OK !");
    }

    /**
     * Compare les versions (tags) locale et distante 
     * @param array $local_tags Version (tag) locale
     * @param array $remote_tags Version (tag) distante
     * @return array Resultat de la comparaison
     */
    private function compare_version($local_tags, $remote_tags) {
        $remote_tags = explode("/", $remote_tags[count($remote_tags) - 1]);
        $remote_tags = $remote_tags[count($remote_tags) - 1];
        $local_tags = explode("-", $local_tags[count($local_tags) - 1]);
        $from = [
            "." => ""
        ];
        $maj = (strtr($local_tags[0], $from) < strtr($remote_tags, $from));
        $local_tags = $local_tags[0] . (isset($local_tags[1]) ? "-" . $local_tags[1] : "");
        return [
            "maj" => $maj,
            "local" => $local_tags,
            "remote" => $remote_tags,
            "msg" => ($maj ? "Update from " . $local_tags . " to " . $remote_tags : "Already up-to-date." )
        ];
    }

}
