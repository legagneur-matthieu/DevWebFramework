<?php

/**
 * Cette classe permet l'export de données aux formats PDF, CSV ou QRCode
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer {

    /**
     * Retourne un formulaire pour exporter un contenu HTML en PDF
     * @param string $content HTML, corps du PDF
     * @param string $filename Nom du fichier
     * @return \form Formulaire d'export
     */
    public static function PDF($content, $filename) {
        $content = '<html><head><meta charset="UTF-8"><title>' . $filename . '</title></head><body>' . $content . '</body></html>';
        $form = new form("\" target=\"_blank\"", "../commun/printer.php");
        $form->hidden("type", "PDF");
        $form->hidden("filename", $filename);
        $form->hidden("content", base64_encode(gzcompress($content)));
        $form->submit("btn btn-secondary", "Export PDF");
        return $form->render();
    }

    /**
     * Retourne un formulaire pour exporter un array en CSV
     * @param array $content Tableau de données a 2 dimentions
     * @param string $filename Nom du fichier
     * @return \form Formulaire d'export
     */
    public static function CSV($content, $filename) {
        $form = new form("\" target=\"_blank\"", "../commun/printer.php");
        $form->hidden("type", "CSV");
        $form->hidden("filename", $filename);
        $form->hidden("content", base64_encode(gzcompress(json_encode($content))));
        $form->submit("btn btn-secondary", "Export CSV");
        return $form->render();
    }

    /**
     * Retourne soit un formulaire pour exporter un text ou une URL en QRCode
     * soit le QRCode en png base64
     * @param string $content Text ou URL
     * @param boolean $get_png_b64 Si true la methode retournera le png en base64, 
     *                              si false, un formulaire d'export (false par defaut)
     * @return \form|string Formulaire d'export ou png base64
     */
    public static function QRCODE($content, $get_png_b64 = false) {
        if ($get_png_b64) {
            return "data:image/png;base64," . base64_encode(service::HTTP_POST_REQUEST($_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . strtr($_SERVER["SCRIPT_NAME"], ["index.php" => ""]) . "../commun/printer.php", [
                                "type" => "QRCODE",
                                "content" => base64_encode(gzcompress($content))
            ]));
        } else {
            $form = new form("\" target=\"_blank\"", "../commun/printer.php");
            $form->hidden("type", "QRCODE");
            $form->hidden("content", base64_encode(gzcompress($content)));
            $form->submit("btn btn-secondary", "Export QRCode");
            return $form->render();
        }
    }

}
