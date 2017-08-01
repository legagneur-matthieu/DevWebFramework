/**
 * Objet contenant des fonctions mathématiques
 * @returns {dwf_math}
 */
function dwf_math() {

    /**
     * Retourne le PGCD de deux nombre ( calculé par l'algorithme d'Euclide )
     * @param {int} nb1 Nombre entier non nul superieur a nb2
     * @param {int} nb2 Nombre entier non nul inferieur a nb1
     * @returns {int|boolean} Retourne le PGCD ou false en cas d'erreur
     */
    this.pgcd = function (nb1, nb2) {
        if (nb1 > 0 && nb2 > 0) {
            if (nb1 < nb2) {
                res = nb1;
                nb1 = nb2;
                nb2 = res;
            }
            res = 1;
            while (res != 0) {
                res = nb1 % nb2;
                if (res != 0) {
                    nb1 = nb2;
                    nb2 = res;
                }
            }
            return nb2;
        }
        return false;
    };

    /**
     * Retourne le factorielle d'un nombre
     * @param {int} nb  Nombre entier positif
     * @returns {int} Retourne le factorielle ou false en cas d'erreur
     */
    this.factorielle = function (nb) {
        nb = sqrt(pow((nb), 2));
        if (nb > 0) {
            res = 1;
            for (i = nb; i > 1; i--) {
                res *= i;
            }
            return res;
        }
        return 1;
    };

    /**
     * Retourne le perimetre d'un cercle a partir de son diametre
     * @param {int} nb Diametre
     * @returns {float} Perimetre
     */
    this.cercle_get_perimetre_from_diametre = function (nb) {
        return (nb * pi());
    };

    /**
     * Retourne le diametre d'un cercle a partir de son perimetre
     * @param {int} nb Diametre
     * @returns {float} Perimetre
     */
    this.cercle_get_diametre_from_perimetre = function (nb) {
        return (nb / pi());
    };

    /**
     * Calcule un pourcentage
     * @param {float} nb Nombre de base
     * @param {float} percent Poucentage a appliquer
     * @returns {float} Pourcentage du nombre de base
     */
    this.pourcentage = function (nb, percent) {
        return (nb * percent / 100);
    };

    /**
     * a=>b <br />
     * c=>d <br />
     * Effectue un produit eb croix ( ou régle de trois) <br />
     * La valeur "null" doit etre appliqué a la valeur recherché (que retournera la fonction)
     *
     * @param {float|null} a Valeur a
     * @param {float|null} b Valeur b
     * @param {float|null} c Valeur c
     * @param {float|null} d Valeur d
     * @returns {float|boolean} Retourne la valeur cherché ou false en cas d'erreur ( généralement une division par 0)
     */
    this.produit_en_croix = function (a, b, c, d) {
        if (a == null && d != 0) {
            return (b * c / d);
        } else {
            if (b == null && c != 0) {
                return (a * d / c);
            } else {
                if (c == null && b != 0) {
                    return (a * d / b);
                } else {
                    if (d == null && a != 0) {
                        return (b * c / a);
                    } else {
                        return FALSE;
                    }
                }
            }
        }
    };

    /**
     * Applique le théoreme de pythagore ($c est la longueur de l'hypotenuse)<br />
     * La valeur "null" doit etre appliqué a la valeur recherché (que retournera la fonction)
     * @param {float|null} a Premier coté adjacent
     * @param {float|null} b Second coté adjacent
     * @param {float|null} c Hypotenuse du triangle
     * @returns {float|boolean} Retourne la valeur cherché ou false en cas d'erreur
     */
    this.pythagore = function (a, b, c) {
        if (a == null) {
            return sqrt((pow(c, 2) - pow(b, 2)));
        } else {
            if (b == null) {
                return sqrt((pow(c, 2) - pow(a, 2)));
            } else {
                if (c == null) {
                    return sqrt((pow(a, 2) + pow(b, 2)));
                } else {
                    return false;
                }
            }
        }
    };

    /**
     * Retourne si le nombre est premier ou non (true/false) <br />
     * ATTENTION ! PEUT ETRE LONG !
     * @param {int} nb Nombre entier positif
     * @returns {boolean} Le nombre est-il premier ? (true/false)
     */
    this.is_premier = function (nb) {
        nb = sqrt(pow((nb), 2));
        if (nb < 2) {
            return false;
        }
        i = 2;
        while (i < ((parseInt((nb / 2) + 1)))) {
            if (nb % i == 0) {
                return false;
            }
            i++;
        }
        return true;
    };

    /**
     * Retourne si le nombre passé en parametre est un nombre heureux ou non <br />
     * si il est heureux alors la fonction retourne la liste de la suite logique démontrant que le nombre est heureux <br />
     * (exemple, 7:[49,97,130,10,1]) <br />
     * sinon la fonction retourne false
     * @param {float} nb Nombre a évaluer
     * @returns {array|boolean} Liste de la suite logique démontrant que le nombre est heureux, false si le nombre est malheureux 
     */
    this.is_heureux = function (nb) {
        if (nb == 0) {
            return false;
        }
        res = [];
        while (true) {
            a = str_split(nb);
            nb = 0;
            $.each(a, function (k, v) {
                nb += pow(v, 2);
            });
            res.push(nb);
            if (nb == 1) {
                return res;
            } else {
                if (in_array(nb, array(4, 16, 37, 58, 89, 145, 42, 20))) {
                    return false;
                }
            }
        }
    };

    /**
     * Retourne si un nombre est polygonal ( triangulaire, carré, pentagonal ,hexagonal, heptagonal, octogonal...)
     * @param {int} nb Nombre entier a évaluer
     * @param {int} x_gonal Nombre de coté du polygone (minimum 3 ! )( 3 = triangulaire, 4 = carré...)
     * @returns {Boolean}True si le nombre est polygonal, false si non polygonal
     */
    this.is_polygonal = function (nb, x_gonal) {
        if (x_gonal < 3) {
            return false;
        } else {
            a = x_gonal - 2;
            b = -x_gonal + 4;
            i = 1;
            while ((c = (i / 2) * (a * i + b)) <= nb) {
                if (c == nb) {
                    return true;
                }
                i++;
            }
            return false;
        }
    };

    /**
     * Retourne si un nombre est tétraédrique
     * @param {int} nb Nombre entier a évaluer
     * @returns {Boolean} True si le nombre est tétraédrique, false si non tétraédrique
     */
    this.is_tetrahedral = function (nb) {
        if (nb < 1) {
            return false;
        }
        i = 1;
        while ((c = ((i * (i + 1) * (i + 2)) / 6)) <= nb) {
            if (c == nb) {
                return true;
            }
            i++;
        }
        return false;
    };
    /**
     *  Retourne si un nombre est pyramidal carré
     * @param {int} nb Nombre entier a évaluer
     * @returns {Boolean}True si le nombre est pyramidal carré, false si non pyramidal carré
     */
    this.is_square_pyramidal = function (nb) {
        if (nb < 1) {
            return false;
        }
        i = 1;
        while ((c = ((i * (i + 1) * ((i * 2) + 2)) / 6)) <= nb) {
            if (c == nb) {
                return true;
            }
            i++;
        }
        return false;
    };
    /**
     * Retourne le delta et les solution d'une équation du second degré définit par ax²+bx+c <br />
     * resutat sous la forme : {"delta":delta, "solutions":null|array(x1,x2)}
     * @param {float} a a de l'équation ax²+bx+c
     * @param {float} b b de l'équation ax²+bx+c
     * @param {float} c c de l'équation ax²+bx+c
     * @returns {array} {"delta":delta, "solutions":null|array(x1,x2)}
     */
    this.delta = function (a, b, c) {
        res = {};
        res.delta = (pow(b, 2) + (4 * a * c));
        if (res.delta == 0) {
            x = ((-b) / 2 * a);
            res.solution = array(x);
        } else {
            if (res.delta > 0) {
                x1 = ((b + sqrt(res.delta)) / 2 * a);
                x2 = ((b - sqrt(res.delta)) / 2 * a);
                res.solution = [x1, x2];
            } else {
                res.solution = null;
            }
        }
        return res;
    };
}