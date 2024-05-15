<?php

/**
 * Cette classe permet de générer le robot.txt
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class robotstxt {

    /**
     * Cette classe permet de générer le robot.txt
     */
    public function __construct() {
        if (!file_exists($robots = $_SERVER["DOCUMENT_ROOT"] . "/robots.txt")) {
            $content = "User-agent: *\n";
            if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/sitemap.xml")) {
                $protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https://" : "http://";
                $domain = $_SERVER["HTTP_HOST"];
                $content .= "Sitemap: {$protocol}{$domain}/sitemap.xml";
            }
            file_put_contents($robots, $content);
        }
    }
}
