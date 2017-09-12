<?php

/**
 * Cette classe offre une légère protection anti-DDOS en bannissant des ip ayant un ratio "requettes/secondes" supérieur à 2.
 * Si l'ip est bannie, l'exécution est interrompue là oÃ¹ ban_ip a été instancié !
 * Il est donc recommandé de placer cette fonction dans le constructeur de "page.class.php"
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class ban_ip {

    /**
     * Cette classe offre une légère protection anti-DDOS en bannissant des ip ayant un ratio "requettes/secondes" supérieur à 2.
     * Si l'ip est bannie, l'exécution est interrompue là oÃ¹ ban_ip a été instancié !
     * Il est donc recommandé de placer cette fonction dans le constructeur de "page.class.php"
     * 
     * @param int $ban_time Temps de bannisement de l'IP
     */
    public function __construct($ban_time = 3600) {
        $data = array(
            array("id", "int", true),
            array("ip", "string", false),
            array("mt", "string", false),
            array("nb_req", "int", false),
            array("ban", "int", false),
        );
        new entity_generator($data, "banip", false, true);
        application::$_bdd->query("delete from banip where mt<'" . application::$_bdd->protect_var(microtime(true) - $ban_time) . "';");
        $where = "ip='" . application::$_bdd->protect_var($_SERVER["REMOTE_ADDR"]) . "'";
        if (banip::get_count($where) > 0) {
            $ip = banip::get_table_array($where);
            $ip = banip::get_from_id($ip[0]["id"]);
            if ($ip->get_mt() + $ban_time < microtime(true)) {
                $ip->set_mt(microtime(true));
                $ip->set_nb_req(0);
                $ip->set_ban(0);
            }
            if ($ip->get_nb_req() > 10) {
                if ($ip->get_ban() != 0 or ( $ip->get_nb_req() / ( microtime(true) - $ip->get_mt() )) > 2) {
                    $ip->set_ban(1);
                    ob_clean();
                    exit(403);
                }
            }
            $ip->set_nb_req($ip->get_nb_req() + 1);
        } else {
            banip::ajout($_SERVER["REMOTE_ADDR"], microtime(true), 0, 0);
            $ip = banip::get_table_array($where);
            $ip = banip::get_from_id($ip[0]["id"]);
        }
    }

}
