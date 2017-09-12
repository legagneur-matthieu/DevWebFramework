<?php

/**
 * Cette classe contient quelques fonctions math�matique de base ainsi que des fonctions pour verifier le type de variables
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class math {

    /**
     * Retourne le PGCD de deux nombres ( calcul� par l'algorithme d'Euclide )
     * @param int $nb1 Nombre entier non nul superieur a $nb2
     * @param int $nb2 Nombre entier non nul inferieur a $nb1
     * @return int|boolean Retourne le PGCD ou false en cas d'erreur
     */
    public static function pgcd($nb1, $nb2) {
        $nb1 = (int) $nb1;
        $nb2 = (int) $nb2;
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
     * @return int|boolean Retourne le factoriel ou false en cas d'erreur
     */
    public static function factorielle($nb) {
        $nb = sqrt(pow(((int) $nb), 2));
        if ($nb > 0) {
            $res = 1;
            for ($i = $nb; $i > 1; $i--) {
                $res *= $i;
            }
            return $res;
        }
        return 1;
    }

    /**
     * Retourne le p�rim�tre d'un cercle � partir de son diam�tre
     * @param float $nb Diametre
     * @return float Perimetre
     */
    public static function cercle_get_perimetre_from_diametre($nb) {
        return ($nb * pi());
    }

    /**
     * Retourne le diam�tre d'un cercle � partir de son perim�tre
     * @param float $nb Perimetre
     * @return float Diametre
     */
    public static function cercle_get_diametre_from_perimetre($nb) {
        return ($nb / pi());
    }

    /**
     * Calcule la distance entre deux points depuis leurs coordon�es X (latitude) et Y (longitude)
     * @param float $xa
     * @param float $ya
     * @param float $xb
     * @param float $yb
     * @return float distance entre les deux points
     */
    public static function distance_entre_deux_points($xa, $ya, $xb, $yb) {
        return sqrt(pow(($xb - $xa), 2) + pow(($yb - $ya), 2));
    }

    /**
     * Calcule un pourcentage
     * @param float $nb Nombre de base
     * @param float $percent Poucentage � appliquer
     * @return float Pourcentage du nombre de base
     */
    public static function pourcentage($nb, $percent) {
        return ($nb * $percent / 100);
    }

    /**
     * $a=>$b <br />
     * $c=>$d <br />
     * Effectue un produit en croix ( ou r�gle de trois) <br />
     * La valeur "null" doit etre appliqu�e � la valeur recherch�e (que retournera la fonction)
     *
     * @param float|null $a Valeur a
     * @param float|null $b Valeur b
     * @param float|null $c Valeur c
     * @param float|null $d Valeur d
     * @return float|boolean Retourne la valeur cherch�e ou false en cas d'erreur ( g�n�ralement une division par 0)
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
     * Applique le th�oreme de Pythagore ($c est la longueur de l'hypotenuse)<br />
     * La valeur "null" doit etre appliqu�e � la valeur recherch�e (que retournera la fonction)
     * @param float|null $a Premier cot� adjacent
     * @param float|null $b Second cot� adjacent
     * @param float|null $c Hypotenuse du triangle
     * @return float|boolean Retourne la valeur cherch�e ou false en cas d'erreur
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
     * Retourne si le nombre pass� en param�tre est un nombre heureux ou non <br />
     * S'il est heureux, alors la fonction retourne la liste de la suite logique d�montrant que le nombre est heureux <br />
     * (exemple, 7:[49,97,130,10,1]) <br />
     * sinon la fonction retourne false
     * @param float $nb Nombre a �valuer
     * @return array|boolean Liste de la suite logique d�montrant que le nombre est heureux, false si le nombre est malheureux 
     */
    public static function is_heureux($nb) {
        $nb = ((float) $nb);
        if ($nb == 0) {
            return false;
        }
        $res = array();
        while (true) {
            $a = str_split($nb);
            $nb = 0;
            foreach ($a as $value) {
                $nb += pow($value, 2);
            }
            $res[] = $nb;
            if ($nb == 1) {
                return $res;
            } elseif (in_array($nb, array(4, 16, 37, 58, 89, 145, 42, 20))) {
                return false;
            }
        }
    }

    /**
     * Retourne si un nombre est polygonal ( triangulaire, carr�, pentagonal ,hexagonal, heptagonal, octogonal...)
     * @param int $nb Nombre entier � �valuer
     * @param int $x_gonal Nombre de cot� du polygone (minimum 3 ! )( 3 = triangulaire, 4 = carr�...)
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
     * Retourne si un nombre est t�tra�drique
     * @param int $nb Nombre entier a �valuer
     * @return boolean True si le nombre est t�tra�drique, false si non t�tra�drique
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
     * Retourne si un nombre est pyramidal carr�
     * @param int $nb Nombre entier � �valuer
     * @return boolean True si le nombre est pyramidal carr�, false si non pyramidal carr�
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
     * Retourne le delta et les solution d'une �quation du second degr� d�finit par ax²+bx+c <br />
     * resutat sous la forme : array("delta"=>$delta, "solutions"=> null|array($x1,$x2);
     * @param float $a a de l'�quation ax²+bx+c
     * @param float $b b de l'�quation ax²+bx+c
     * @param float $c c de l'�quation ax²+bx+c
     * @return array array("delta"=>$delta, "solutions"=> null|array($x1,$x2);
     */
    public static function delta($a, $b, $c) {
        $res["delta"] = (pow($b, 2) + (4 * $a * $c));
        if ($res["delta"] == 0) {
            $x = ((-$b) / 2 * $a);
            $res["solution"] = array($x);
        } elseif ($res["delta"] > 0) {
            $x1 = (($b + sqrt($res["delta"])) / 2 * $a);
            $x2 = (($b - sqrt($res["delta"])) / 2 * $a);
            $res["solution"] = array($x1, $x2);
        } else {
            $res["solution"] = null;
        }
        return $res;
    }

    /**
     * Retourne la moyenne d'une liste de nombres
     * @param array $array Liste de nombre : array(n1, n2, n3, ...)
     * @return float Moyenne
     */
    public static function moyenne($array) {
        return array_sum($array) / count($array);
    }

    /**
     * Retourne true si la variable est un int, false dans le cas contraire
     * 
     * @param int $nb Variable a �valuer
     * @return boolean La variable est-il un int ? (true/false)
     */
    public static function is_int($nb) {
        return (((int) $nb) == $nb);
    }

    /**
     * Retourne true si la variable est un float, false dans le cas contraire
     * 
     * @param float $nb Variable � �valuer
     * @return boolean La variable est-il un float ? (true/false)
     */
    public static function is_float($nb) {
        return (((float) $nb) == $nb);
    }

    /**
     * Retourne true si la variable est un boolean, false dans le cas contraire
     * 
     * @param boolean $nb Variable � �valuer
     * @return boolean La variable est-il un boolean ? (true/false)
     */
    public static function is_bool($nb) {
        return (((boolean) $nb) == $nb);
    }

}
