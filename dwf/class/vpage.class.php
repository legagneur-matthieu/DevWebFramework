<?php

/** 
 * Affiche une page virtuelle (iframe) à partir du contenu fourni.
 * /!\ les services sont requis pour utiliser cette classe
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class vpage {

    /**
     * Affiche une page virtuelle (iframe)  à partir du contenu fourni.
     * /!\ les services sont requis pour utiliser cette classe
     *
     * @param string $content Le contenu HTML de la page virtuelle.
     * @param string $title   Le titre de la page virtuelle (défaut : "vpage").
     * @param int    $ttl     Le temps de vie de la page en secondes (défaut : 86400).
     */
    public function __construct($content, $title = "vpage", $ttl = 86400) {
        if (!file_exists("./services/index.php")) {
            dwf_exception::warning_exception("625", ["__c__" => __CLASS__]);
        } else {
            entity_generator::generate([
                "dwf_vpage" => [
                    ["id", "int", true],
                    ["mt", "int", false],
                    ["key", "string", false],
                    ["title", "string", false],
                    ["content", "string", false],
                ]
            ]);
            if (!file_exists($sfile = "./services/s_vpage.service.php")) {
                file_put_contents($sfile, file_get_contents(__DIR__ . "/vpage/s_vpage"));
            }
            application::$_bdd->query("delete from dwf_vpage where mt<:mt;", [":mt" => time()]);
            $key = application::hash("{$title}_{$content}");
            dwf_vpage::ajout(time() + $ttl, $key, $title, $content);
            echo tags::tag("iframe", ["src" => "./services/index.php?service=s_vpage&key={$key}", "id" => $title, "class" => "w-100"], "");
            ?>
            <script>
                $('#<?= $title ?>').on('load', function () {
                    $(this).height($(this).contents().find('html').outerHeight(true));
                });
            </script>
            <?php
        }
    }
}
