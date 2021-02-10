<?php

/**
 * Cette classe contient quelques fonctions mathématiques de base ainsi que des fonctions pour verifier le type de variables
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class math {

    /**
     * Tableau mémoire (https://fr.wikipedia.org/wiki/M%C3%A9mo%C3%AFsation )
     * @var array Tableau mémoire
     */
    public static $_memoi = [
        "int_partition" => [[]]
    ];

    /**
     * Retourne le PGCD de deux nombres ( calculé par l'algorithme d'Euclide )
     * @param int $nb1 Nombre entier non nul supérieur a $nb2
     * @param int $nb2 Nombre entier non nul inférieur a $nb1
     * @return int|boolean Retourne le PGCD ou false en cas d'erreur
     */
    public static function pgcd($nb1, $nb2) {
        if (function_exists("gmp_gcd")) {
            return floatval(gmp_gcd($nb1, $nb2));
        }
        if ($nb1 > 0 and $nb2 > 0) {
            if ($nb1 < $nb2) {
                $res = $nb1;
                $nb1 = $nb2;
                $nb2 = $res;
            }
            $res = 1;
            while ($res != 0) {
                $res = $nb1 % $nb2;
                if ($res != 0) {
                    $nb1 = $nb2;
                    $nb2 = $res;
                }
            }
            return $nb2;
        }
        return FALSE;
    }

    /**
     * Retourne le factoriel d'un nombre
     * @param int $nb Nombre entier positif
     * @return float Retourne le factoriel
     */
    public static function factorielle($nb) {
        if ($nb == 0) {
            return 1;
        }
        return (function_exists("gmp_fact") ? floatval(gmp_fact($nb)) : array_product(range(1, $nb)));
    }

    /**
     * Retourne le multifactioriel de facteur k
     * @param int $nb Nombre entier positif
     * @param int $k Facteur du factorielle (2= factorielle double, 3= triple, ...)
     * @return int Retourne le multifactoriel
     */
    public static function multifactorielle($nb, $k) {
        if ($k > $nb) {
            return 0;
        }
        return array_product(range(($nb % $k == 0 ? $k : $nb % $k), $nb, $k));
    }

    /**
     * Retourne le périmètre d'un cercle à partir de son diamètre
     * @param float $nb Diamètre
     * @return float Périmètre
     */
    public static function cercle_get_perimetre_from_diametre($nb) {
        return ($nb * pi());
    }

    /**
     * Retourne le diamètre d'un cercle à partir de son perimètre
     * @param float $nb Périmètre
     * @return float Diamètre
     */
    public static function cercle_get_diametre_from_perimetre($nb) {
        return ($nb / pi());
    }

    /**
     * Calcule la distance entre deux points depuis leurs coordonnées X (latitude) et Y (longitude)
     * @param float $xa Coordonnées x du point a
     * @param float $ya Coordonnées y du point a
     * @param float $xb Coordonnées x du point b
     * @param float $yb Coordonnées y du point b
     * @return float distance entre les deux points
     */
    public static function distance_entre_deux_points($xa, $ya, $xb, $yb) {
        return sqrt(pow(($xb - $xa), 2) + pow(($yb - $ya), 2));
    }

    /**
     * Calcule un pourcentage
     * @param float $nb Nombre de base
     * @param float $percent Poucentage à appliquer
     * @return float Pourcentage du nombre de base
     */
    public static function pourcentage($nb, $percent) {
        return ($nb * $percent / 100);
    }

    /**
     * $a=>$b <br />
     * $c=>$d <br />
     * Effectue un produit en croix ( ou règle de trois) <br />
     * La valeur "null" doit etre appliquée à la valeur recherchée (que retournera la fonction)
     *
     * @param float|null $a Valeur a
     * @param float|null $b Valeur b
     * @param float|null $c Valeur c
     * @param float|null $d Valeur d
     * @return float|boolean Retourne la valeur cherchée ou false en cas d'erreur ( généralement une division par 0)
     */
    public static function produit_en_croix($a, $b, $c, $d) {
        if ($a == null and $d != 0) {
            return ($b * $c / $d);
        } elseif ($b == null and $c != 0) {
            return ($a * $d / $c);
        } elseif ($c == null and $b != 0) {
            return ($a * $d / $b);
        } elseif ($d == null and $a != 0) {
            return ($b * $c / $a);
        } else {
            return FALSE;
        }
    }

    /**
     * Applique le théoreme de Pythagore ($c est la longueur de l'hypoténuse)<br />
     * La valeur "null" doit etre appliquée à la valeur recherchée (que retournera la fonction)
     * @param float|null $a Premier coté adjacent
     * @param float|null $b Second coté adjacent
     * @param float|null $c Hypoténuse du triangle
     * @return float|boolean Retourne la valeur cherchée ou false en cas d'erreur
     */
    public static function pythagore($a, $b, $c) {
        if ($a == null) {
            return sqrt((pow($c, 2) - pow($b, 2)));
        } elseif ($b == null) {
            return sqrt((pow($c, 2) - pow($a, 2)));
        } elseif ($c == null) {
            return sqrt((pow($a, 2) + pow($b, 2)));
        } else {
            return false;
        }
    }

    /**
     * Retourne si le nombre est premier ou non (true/false) <br />
     * ATTENTION ! PEUT ETRE LONG !
     * @param int $nb Nombre entier positif
     * @return boolean Le nombre est-il premier ? (true/false)
     */
    public static function is_premier($nb) {
        $nb = sqrt(pow(((int) $nb), 2));
        if ($nb < 2) {
            return false;
        }
        $i = 2;
        while ($i < (((int) ($nb / 2) + 1))) {
            if ($nb % $i == 0) {
                return false;
            }
            $i++;
        }
        return true;
    }

    /**
     * Retourne si le nombre passé en paramètre est un nombre heureux ou non <br />
     * S'il est heureux, alors la fonction retourne la liste de la suite logique démontrant que le nombre est heureux <br />
     * (exemple, 7:[49,97,130,10,1]) <br />
     * sinon la fonction retourne false
     * @param float $nb Nombre a évaluer
     * @return array|boolean Liste de la suite logique démontrant que le nombre est heureux, false si le nombre est malheureux 
     */
    public static function is_heureux($nb) {
        $nb = ((float) $nb);
        if ($nb == 0) {
            return false;
        }
        $res = [];
        while (true) {
            $a = str_split($nb);
            $nb = 0;
            foreach ($a as $value) {
                $nb += pow($value, 2);
            }
            $res[] = $nb;
            if ($nb == 1) {
                return $res;
            } elseif (in_array($nb, [4, 16, 37, 58, 89, 145, 42, 20])) {
                return false;
            }
        }
    }

    /**
     * Retourne si un nombre est polygonal ( triangulaire, carré, pentagonal ,hexagonal, heptagonal, octogonal...)
     * @param int $nb Nombre entier à évaluer
     * @param int $x_gonal Nombre de coté du polygone (minimum 3 ! )( 3 = triangulaire, 4 = carré...)
     * @return boolean True si le nombre est polygonal, false si non polygonal
     */
    public static function is_polygonal($nb, $x_gonal) {
        if ($x_gonal < 3 or ! self::is_int($nb)) {
            return false;
        } else {
            $a = $x_gonal - 2;
            $b = - $x_gonal + 4;
            $i = 1;
            while (($c = ($i / 2) * ($a * $i + $b)) <= $nb) {
                if ($c == $nb) {
                    return true;
                }
                $i++;
            }
            return false;
        }
    }

    /**
     * Retourne si un nombre est tétraédrique
     * @param int $nb Nombre entier à évaluer
     * @return boolean True si le nombre est tétraédrique, false si non tétraédrique
     */
    public static function is_tetrahedral($nb) {
        if ($nb < 1 or ! self::is_int($nb)) {
            return false;
        }
        $i = 1;
        while (($c = (($i * ($i + 1) * ($i + 2)) / 6)) <= $nb) {
            if ($c == $nb) {
                return true;
            }
            $i++;
        }
        return false;
    }

    /**
     * Retourne si un nombre est pyramidal carré
     * @param int $nb Nombre entier à évaluer
     * @return boolean True si le nombre est pyramidal carré, false si non pyramidal carré
     */
    public static function is_square_pyramidal($nb) {
        if ($nb < 1 or ! self::is_int($nb)) {
            return false;
        }
        $i = 1;
        while (($c = (($i * ($i + 1) * (($i * 2) + 2)) / 6)) <= $nb) {
            if ($c == $nb) {
                return true;
            }
            $i++;
        }
        return false;
    }

    /**
     * Retourne le delta et les solution d'une équation du second degré définit par ax²+bx+c <br />
     * resutat sous la forme : array("delta"=>$delta, "solutions"=> null|array($x1,$x2);
     * @param float $a a de l'équation ax²+bx+c
     * @param float $b b de l'équation ax²+bx+c
     * @param float $c c de l'équation ax²+bx+c
     * @return array array("delta"=>$delta, "solutions"=> null|array($x1,$x2);
     */
    public static function delta($a, $b, $c) {
        $res["delta"] = (pow($b, 2) - (4 * $a * $c));
        if ($res["delta"] == 0) {
            $x = ((-$b) / 2 * $a);
            $res["solution"] = [$x];
        } elseif ($res["delta"] > 0) {
            $x1 = (($b + sqrt($res["delta"])) / 2 * $a);
            $x2 = (($b - sqrt($res["delta"])) / 2 * $a);
            $res["solution"] = [$x1, $x2];
        } else {
            $res["solution"] = null;
        }
        return $res;
    }

    /**
     * Retourne la moyenne d'une liste de nombres
     * @param array $array Liste de nombres : array(n1, n2, n3, ...)
     * @return float Moyenne
     */
    public static function moyenne($array) {
        return array_sum($array) / count($array);
    }

    /**
     * Retourne Phi (le nombre d'or)
     * @return float Phi (nombre d'or)
     */
    public static function phi() {
        return ((1 + sqrt(5)) / 2);
    }

    /**
     * Retourne true si la variable est un int, false dans le cas contraire
     * 
     * @param int $nb Variable à évaluer
     * @return boolean La variable est-il un int ? (true/false)
     */
    public static function is_int($nb) {
        return (!is_object($nb) and ( (int) $nb) == $nb);
    }

    /**
     * Retourne true si la variable est un float, false dans le cas contraire
     * 
     * @param float $nb Variable à évaluer
     * @return boolean La variable est-il un float ? (true/false)
     */
    public static function is_float($nb) {
        return (!is_object($nb) and (string) (float) $nb == (string) $nb);
    }

    /**
     * Retourne true si la variable est un boolean, false dans le cas contraire
     * 
     * @param boolean $nb Variable à évaluer
     * @return boolean La variable est-il un boolean ? (true/false)
     */
    public static function is_bool($nb) {
        return (!is_object($nb) and ( (boolean) $nb) == $nb);
    }

    /**
     * Nombre d'arrangements ordonnés de k parmis n
     * @param int $k Nombre d'objets
     * @param int $n Nombre d'ensemble
     * @return int Nombre d'arrangements ordonnés de k parmis n
     */
    public static function arrangements($k, $n) {
        return ($k <= $n ? (self::factorielle($n) / self::factorielle($n - $k)) : 0);
    }

    /**
     * Nombre d'arrangements non ordonnés de k parmis n 
     * (binomial)
     * @param int $k Nombre d'objets
     * @param int $n Nombre d'ensemble
     * @return int Nombre d'arrangements non ordonnés de k parmis n
     */
    public static function combinaisons($k, $n) {
        return (self::arrangements($k, $n) / self::factorielle($k));
    }

    /**
     * Nombres de Catalan
     * @param int $n Indice du nombre de Catalan
     * @return int Nombre de Catalan
     */
    public static function catalan($n) {
        return self::combinaisons($n, 2 * $n) - self::combinaisons($n + 1, 2 * $n);
    }

    /**
     * Retourne la somme de la plage des entiers compris entre $start et $end
     * @param int $start Premier nombre de la plage 
     * @param int $end Dernier nombre de la plage (> $start)
     * @return int Somme de la plage des entiers
     */
    public static function range_sum($start, $end) {
        return (($start + $end) * ($end - $start + 1)) / 2;
    }

    /**
     * Retourne le produit de la plage des entiers compris entre $start et $end
     * @param int $start Premier nombre de la plage (entier >= 1)
     * @param int $end Dernier nombre de la plage (> $start)
     * @return int Produit de la plage des entiers
     */
    public static function range_product($start, $end) {
        return (function_exists("gmp_fact") ? floatval(gmp_fact($end)) / floatval(gmp_fact(($start == 1 ? $start : $start - 1))) : array_product(range($start, $end)));
    }

    /**
     * Retourne la somme harmonique de 1 + 1/(2^p) + 1/(3^p) + ... + 1/(n^p)
     * @param int $n Limite de la somme harmonique
     * @param float $p Puissance au dénominateur de la somme harmonique
     * @return float Résultat de la somme harmonique
     */
    public static function harmonic_sum($n, $p = 1) {
        foreach ($s = range(1, $n) as $k => $v) {
            $s[$k] = 1 / pow($v, $p);
        }
        return array_sum($s);
    }

    /**
     * Tire un nombre aléatoire
     * @param float|int $min Valeur minimal
     * @param float|int $max Valeur maximal
     * @param boolean $get_as_float Le nombre retourné doit-il être un INT ou un FLOAT
     *      (true = float, false = int)
     * @param int $mask Masque, influe sur la randomisation,
     *      (0 = pas de masque,
     *      -1 = masque aléatoire,
     *      Ou un entier positif = masque a appliquer) 
     * @return float|int Nombre aléatoire
     */
    public static function rand($min = 0, $max = 1, $get_as_float = true, $mask = 0) {
        if ($max < $min) {
            return self::rand($max, $min, $get_as_float, $mask);
        }
        if ($min < 0) {
            return self::rand(0, abs($max - $min), $get_as_float, $mask) + $min;
        }
        if ($mask < 0) {
            $mask = self::rand($min, $max, false);
        }
        $i = 90;
        $f = 9169;
        $p = 6;
        $pl = 4;
        $x = ((float) microtime());
        while ($max - $min >= pow(10, $pl)) {
            $p += 4;
            $pl += 4;
            $x .= self::rand(1000, 9999, false);
        }
        $m = 10 ** $p;
        $x = (($x * $m) ^ $mask) / $m;
        for ($j = 0; $j < $i; $j++) {
            $x = self::logistic($x);
        }
        $x = (($x * ($m * $f) % $m) / $m) * ($max + 2) + ($min - 1);
        if ($x < $min or $x > $max) {
            $x = self::rand($min, $max, $get_as_float, $mask);
        }
        return ($get_as_float ? $x : round($x));
    }

    /**
     * Fonction de la suite logistique 
     * https://fr.wikipedia.org/wiki/Suite_logistique
     * @param float $x Nombre en entrée (float compris entre 0 et 1)
     * @param float $r Valeur de µ (float compris entre 0 et 4, 4 par defaut)
     * @return float Nombre en sortie (float compris entre 0 et 1)
     */
    public static function logistic($x, $r = 4) {
        return $r * $x * (1 - $x);
    }

    /**
     * Retourne la partition d'un entier a $k patie,
     * si $k=null, retourne le nombre de partition de $n
     * @param int $n
     * @param int|null $k
     * @return int partition ou nombre de partitions
     */
    public static function int_partition($n, $k = null) {
        if ($k === null) {
            if (isset(self::$_memoi[__FUNCTION__][$n][$n])) {
                return array_sum(self::$_memoi[__FUNCTION__][$n]);
            }
            $s = 0;
            for ($k = 1; $k <= $n; $k++) {
                $s += self::int_partition($n, $k);
            }
            return $s;
        } else {
            if (isset(self::$_memoi[__FUNCTION__][$n][$k])) {
                return self::$_memoi[__FUNCTION__][$n][$k];
            }
            if ($n == $k || $k == 1) {
                return (self::$_memoi[__FUNCTION__][$n][$k] = 1);
            }
            if ($n < $k) {
                return 0;
            } else {
                return (self::$_memoi[__FUNCTION__][$n][$k] = (self::int_partition($n - 1, $k - 1) + self::int_partition($n - $k, $k)));
            }
        }
    }

    /**
     * Retourne le nombre dans la suite de Fibonacci à l'index $n
     * @param int $n Index du nombre dans la suite
     * @return int Le nombre dans la suite de Fibonacci à l'index $n
     */
    public static function fibonacci($n) {
        $negative = false;
        if ($n < 0) {
            $negative = true;
            $n *= -1;
        }
        $s = ( (1 / sqrt(5) * (pow(self::phi(), $n) - pow((-(1 / self::phi())), $n))) );
        return($negative ? ($n % 2 == 0 ? -$s : $s) : $s);
    }

    /**
     * Retourne un bingint (cf phpseclib/Math/BigInteger.php)
     * @param int|Math_BigInteger $x Nombre entier
     * @param int $base Base à utiliser (10 par defaut)
     * @return \Math_BigInteger bingint (cf phpseclib/Math/BigInteger.php)
     */
    public static function bigint($x = 0, $base = 10) {
        if (!class_exists("Math_BigInteger")) {
            include_once __DIR__ . '/phpseclib/Math/BigInteger.php';
        }
        return new Math_BigInteger($x, $base);
    }

}
