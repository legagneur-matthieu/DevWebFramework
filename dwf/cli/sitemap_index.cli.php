<?php

/**
 * Cette classe génère le sitemap index des projets contenant un sitemap
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class sitemap_index {

    /**
     * Cette classe génère le sitemap index des projets contenant un sitemap
     */
    public function __construct() {
        if (!class_exists("cli")) {
            include __DIR__ . '/../class/cli.class.php';
        }
        cli::write("[SitemapIndex]");
        $host = cli::read("Saisir l'adresse du domaine (ex : https://dwf.sytes.net , laissez vide pour skip) : ");
        if (!empty($host)) {
            $dir = __DIR__ . "/../../html";
            $file = "$dir/sitemap.xml";
            $xml = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach (glob("$dir/*/sitemap.xml") as $sitemap) {
                $loc = strtr($sitemap, [$dir => $host]);
                $lastmod = date("Y-m-d", filemtime($sitemap));
                $xml .= "<sitemap><loc>$loc</loc><lastmod>$lastmod</lastmod></sitemap>";
            }
            $xml .= "</sitemapindex>";
            file_put_contents($file, $xml);
        }
    }

}

new sitemap_index();
