<?php

/**
 * Cette classe génére le robot.txt
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class robotstxt {

    /**
     * Cette classe génére le robot.txt
     */
    public function __construct() {
        $content = "User-agent: *\n";
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/sitemap.xml")) {
            $protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https://" : "http://";
            $domain = $_SERVER["HTTP_HOST"];
            $content .= "Sitemap: {$protocol}{$domain}/sitemap.xml";
        }
        if (!file_exists($robots = $_SERVER["DOCUMENT_ROOT"] . "/robots.txt")) {
            file_put_contents($robots, $content);
        } elseif ((time() - filemtime($robots)) > 84600) {
            file_put_contents($robots, $content);
        }
    }
}
