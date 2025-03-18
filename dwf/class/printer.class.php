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
     * @param int $landscape 0 pour orientation Portrait, 1 pour Paysage
     * @return \form Formulaire d'export
     */
    public static function PDF($content, $filename, $landscape = 0) {
        $content = '<html><head><meta charset="UTF-8"><title>' . $filename . '</title></head><body>' . $content . '</body></html>';
        $key = "PDF_" . sha1($content);
        $_SESSION[$key] = $content;
        export_dwf::add_files([
            realpath(__DIR__ . "/printer.php"),
            realpath(__DIR__ . "/dompdf"),
            realpath(__DIR__ . "/printer"),
        ]);
        $form = new form("", "../commun/printer.php", "post", true);
        $form->hidden("filename", $filename);
        $form->hidden("content", $key);
        $form->hidden("landscape", $landscape);
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
        $content = json_encode($content);
        $key = "CSV_" . sha1($content);
        $_SESSION[$key] = $content;
        export_dwf::add_files([
            realpath(__DIR__ . "/printer.php"),
            realpath(__DIR__ . "/printer"),
        ]);
        $form = new form("", "../commun/printer.php", "post", true);
        $form->hidden("filename", $filename);
        $form->hidden("content", $key);
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
        $key = "QRCODE_" . sha1($content);
        $_SESSION[$key] = $content;
        export_dwf::add_files([
            realpath(__DIR__ . "/printer.php"),
            realpath(__DIR__ . "/printer"),
            realpath(__DIR__ . "/phpqrcode"),
        ]);
        if ($get_png_b64) {
            return "data:image/png;base64," . base64_encode(service::HTTP_POST_REQUEST($_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . strtr($_SERVER["SCRIPT_NAME"], ["index.php" => ""]) . "../commun/printer.php", [
                                "type" => "QRCODE",
                                "content" => base64_encode(gzcompress($content))
            ]));
        } else {
            $form = new form("", "../commun/printer.php", "post", true);
            $form->hidden("content", $key);
            $form->submit("btn btn-secondary", "Export QRCode");
            return $form->render();
        }
    }
}
