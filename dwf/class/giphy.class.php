<?php

/**
 * Cette classe permet de retourner des Gifs de l'API GIPHY
 * @author BERNARD Rodolphe <mr.rodolphe.bernard@gmail.com>
 */
class giphy {

    /**
     * Cette classe permet de retourner des Gifs de l'API GIPHY
     */
    public function __construct() {
        export_dwf::add_files(__DIR__."/gyphy_tpl");
        if (file_exists('./services/index.php')) {
            if (isset(config::$_giphy_key)) {
                if (!file_exists('./services/s_giphy.service.php')) {
                    $this->mk_service();
                }
                $this->search();
            } else {
                dwf_exception::warning_exception(700, [
                    'msg' => 'Absence de la clé API',
                ]);
            }
        } else {
            dwf_exception::warning_exception(622, ['_s_' => 's_giphy']);
        }
    }

    /**
     * Recherche des Gifs de l'API GIPHY
     */
    private function search() {
        $form = new form('form_Fgiphy');
        $form->input('GIPHY Search', 'giphysearch');
        $form->submit('btn btn-outline-primary', 'Rechercher');
        echo $form->render();
        ?>
        <div class="card">
            <div class="card-body giphys"></div>        
        </div>
        <script>
            $(document).ready(function () {
                $(".giphys").parent(".card").hide();
                $(".form_giphy").submit(function (e) {
                    e.preventDefault();
                    $(".giphys").parent(".card").show();
                    $.get("./services/index.php?service=s_giphy&action=search_html&q=" + $("#giphysearch").val(), function (data) {
                        $(".giphys").html(data);
                    }, "html");
                });
            });
        </script>
        <?php
    }

    /**
     * Permet d'écrire dans le fichier
     */
    private function mk_service() {
        file_put_contents('./services/s_giphy.service.php', file_get_contents(__DIR__ . '/giphy_tpl/s_giphy'));
    }

}
?>

