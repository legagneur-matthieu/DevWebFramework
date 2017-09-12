<?php

/**
 * Cette classe permet de générer le robot.txt d'un site 
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class robotstxt {

    /**
     * Cette classe permet de générer le robot.txt d'un site 
     * @param string $data lignes à ajouter au robot.txt (commencer par \n et séparer chaque ligne par \n)
     */
    public function __construct($data = "") {
        $robots_file = "./robots.txt";
        if (!file_exists($robots_file) or filemtime($robots_file) < (microtime(true) - 86400)) {
            $robot = "User-agent: *\nDisallow: /class/";
            $robot .= $data;
            if (config::$_sitemap) {
                $robot .= "\nSitemap: sitemap.xml";
            }
            file_put_contents($robots_file, $robot);
            dwf_exception::check_file_writed($robots_file);
        }
    }

}
