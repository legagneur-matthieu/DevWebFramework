<?php

/**
 * Cette classe permet de retourner des Gifs de l'API TENOR
 * @author BERNARD Rodolphe <mr.rodolphe.bernard@gmail.com>
 */
class tenor {

    /**
     * Cette classe permet de retourner des Gifs de l'API TENOR
     */
    public function __construct() {
        if (file_exists('./services/index.php')) {
            if (isset(config::$_tenor_key)) {
                if (!file_exists('./services/s_tenor.service.php')) {
                    $this->mk_service();
                }
                $this->search();
            } else {
                dwf_exception::warning_exception(700, [
                    'msg' => 'Absence de la clé API',
                ]);
            }
        } else {
            dwf_exception::warning_exception(622, ['_s_' => 's_tenor']);
        }
    }

    /**
     * Recherche des Gifs de l'API TENOR
     */
    private function search() {
        $form = new form('form_tenor');
        $form->input('TENOR Search', 'tenorsearch');
        $form->submit('btn btn-outline-primary', 'Rechercher');
        echo $form->render();
        ?>
        <div class="card">
            <div class="card-body tenors"></div> 
        </div>
        <script>
            $(document).ready(function () {
                $(".tenors").parent(".card").hide();
                $(".form_tenor").submit(function (e) {
                    e.preventDefault();
                    $(".tenors").parent(".card").show();
                    $.get("./services/index.php?service=s_tenor&action=search_html&q=" + $("#tenorsearch").val(), function (data) {
                        $(".tenors").html(data);
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
        file_put_contents('./services/s_tenor.service.php', file_get_contents(__DIR__ . '/tenor_tpl/s_tenor'));
    }

}
?>
