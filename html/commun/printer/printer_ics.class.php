<?php

/**
 * printer_ics : sert un agenda iCalendar (RFC 5545) préparé par fullcalendar::export_btn()
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer_ics {

    /**
     * Envoie le fichier .ics en téléchargement
     * @param array $content Tableau ["filename" => nom du fichier, "content" => contenu ICS]
     */
    public function __construct($content) {
        if (!is_array($content) or empty($content["content"]) or empty($content["filename"]) or !preg_match('/^[A-Za-z0-9._-]+\.ics$/', $content["filename"])) {
            header("HTTP/1.1 400 Bad Request");
            return;
        }
        header("Content-Type: text/calendar; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"{$content["filename"]}\"");
        header("Content-Length: " . strlen($content["content"]));
        header("X-Content-Type-Options: nosniff");
        echo $content["content"];
    }
}
