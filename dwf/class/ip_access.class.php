<?php

/**
 * CETTE CLASSE EST EXPERIMENTALE ET N'A PAS ETE TESTÃ‰E !
 * Cette classe sert à blacklister une liste de plages d'adresses IP en les redirigeant vers un autre site
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class ip_access {

    /**
     * Liste des plages d'adresses IP à blacklister
     * 
     * @var array Liste des plages d'adresses IP à blacklister
     */
    private $_ips;

    /**
     * Url de redirection
     * 
     * @var string Url de redirection
     */
    private $_redir;

    /**
     * CETTE CLASSE EST EXPERIMENTALE ET N'A PAS ETE TESTÃ‰E !
     * Cette classe sert à blacklister une liste de plages d'adresses IP en les redirigeant vers un autre site
     * 
     * 
     * @param array $array_ip liste des plages d'adresses IP à blacklister array(array(début,fin),...);
     * @param string $error_redir url de redirection (duckduckgo par defaut)
     */
    public function __construct($array_ip, $error_redir = "http://duckduckgo.com/") {
        $this->_ips = $ip;
        $this->_redir = $error_redir;
        $remote_addr = $this->ipv4_to_int($_SERVER["REMOTE_ADDR"]);
        $find = false;
        if (count($remote_addr) == 4) {
            foreach ($this->_ips as $value) {
                $debut = $this->ipv4_to_int($value[0]);
                $fin = $this->ipv4_to_int($value[1]);
                if (count($debut) == 4 and count($fin) == 4 and $debut <= $remote_addr and $fin >= $remote_addr) {
                    $find = true;
                }
            }
        } else {
            $remote_addr = $this->ipv6_to_int($_SERVER["REMOTE_ADDR"]);
            if ($_SERVER["REMOTE_ADDR"] != "::1") {
                foreach ($this->_ips as $value) {
                    $debut = $this->ipv6_to_int($value[0]);
                    $fin = $this->ipv6_to_int($value[1]);
                    if ($debut <= $remote_addr and $fin >= $remote_addr) {
                        $find = true;
                    }
                }
            }
        }
        if (!$find) {
            $this->redir();
        }
    }

    /**
     * Formate l'adresse IPv4 entrée en paramètre
     * 
     * @param string $ip ip v4 à formater
     * @return string ip formaté
     */
    private function ipv4_to_int($ip) {
        explode(".", $ip);
        $i = 0;
        while (isset($ip[$i])) {
            if ($ip[$i] < 100 and $ip[$i] >= 10) {
                $ip[$i] = "0" . $ip[$i];
            } elseif ($ip[$i] < 10) {
                $ip[$i] = "00" . $ip[$i];
            }
            $i++;
        }
        $n_ip = "";
        foreach ($ip as $d) {
            $n_ip .= $d;
        }
        return $n_ip;
    }

    /**
     * Formate l'adresse IPv6 entrée en paramètre
     * 
     * @param string $ip ip v6 à formater
     * @return string ip formatée
     */
    private function ipv6_to_int($ip) {
        $from = array(
            array("a" => "10"),
            array("b" => "11"),
            array("c" => "12"),
            array("d" => "13"),
            array("e" => "14"),
            array("f" => "15"),
            array("A" => "10"),
            array("B" => "11"),
            array("C" => "12"),
            array("D" => "13"),
            array("E" => "14"),
            array("F" => "15"),
        );
        return strtr(bin2hex(inet_pton($ip)), $from);
    }

    /**
     * Script de redirection
     */
    private function redir() {
        ?>
        <script type="text/javascript">
            window.location = "<?php echo $this->_redir; ?>";
        </script>
        <?php
        exit();
    }

}
