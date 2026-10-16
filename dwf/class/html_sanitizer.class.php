<?php

/**
 * Cette classe permet de nettoyer du HTML (issu d'un éditeur WYSIWYG par exemple)
 * Les balises sont filtrées par liste blanche (les balises inconnues sont retirées en conservant leur texte,
 * les balises dangereuses comme script/style/iframe/svg sont retirées avec leur contenu).
 * Les attributs sont filtrés par liste noire : tout attribut est autorisé sur une balise autorisée, sauf
 * les attributs d'événements (on*), les attributs dangereux (srcdoc, formaction, xmlns*, attributs avec ":", ...),
 * les styles dangereux (expression, url(, behavior, ..., ou tout style contenant un antislash "\" pour bloquer les échappements CSS), les valeurs contenant javascript: ou vbscript:
 * et les URL non sûres (seuls http, https, mailto, tel et les URL relatives sont acceptés,
 * ainsi que les images base64 png/jpeg/gif/webp/bmp dans le src d'une balise img).
 * Utilise DOMDocument (natif PHP), sans regex.
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class html_sanitizer {

    /**
     * Balises autorisées (liste blanche)
     * @var array
     */
    private static $_allowed = [
        "p", "br", "hr", "span", "div", "font",
        "b", "strong", "i", "em", "u", "s", "sub", "sup", "small", "big", "time",
        "h1", "h2", "h3", "h4", "h5", "h6",
        "ul", "ol", "li", "blockquote", "pre", "code", "dl", "dt", "dd", "figure", "figcaption",
        "a", "img",
        "table", "caption", "thead", "tbody", "tfoot", "tr", "th", "td",
    ];

    /**
     * Balises retirées avec tout leur contenu
     * @var array
     */
    private static $_removed = ["script", "style", "iframe", "object", "embed", "form", "svg", "math"];

    /**
     * Balises vides en HTML5 (sans contenu) : libxml peut y imbriquer à tort le texte qui suit,
     * elles sont donc retirées en conservant (et nettoyant) leurs enfants au lieu de les supprimer avec leur contenu
     * @var array
     */
    private static $_void_removed = ["embed", "source", "track", "param", "link", "meta", "base"];

    /**
     * Attributs toujours interdits (liste noire, en plus des on*, xmlns* et des noms contenant ":")
     * @var array
     */
    private static $_forbidden_attr = ["srcdoc", "formaction", "action", "xlink:href", "is"];

    /**
     * Attributs contenant une URL à vérifier
     * @var array
     */
    private static $_url_attr = ["href", "src", "srcset", "action", "background", "poster", "cite", "longdesc", "data", "formaction", "xlink:href"];

    /**
     * Motifs interdits dans un attribut style (comparés en minuscules, sans espaces)
     * L'antislash est interdit pour bloquer les échappements CSS (ex : \75rl( = url( )
     * @var array
     */
    private static $_forbidden_style = ["expression", "url(", "javascript:", "behavior", "-moz-binding", "@import", "\\"];

    /**
     * Protocoles autorisés dans les URL
     * @var array
     */
    private static $_protocols = ["http", "https", "mailto", "tel"];

    /**
     * Préfixes d'images base64 autorisés dans le src d'une balise img
     * @var array
     */
    private static $_data_images = ["data:image/png;base64,", "data:image/jpeg;base64,", "data:image/jpg;base64,", "data:image/gif;base64,", "data:image/webp;base64,", "data:image/bmp;base64,"];

    /**
     * Nettoie un contenu HTML
     *
     * @param string $html Contenu HTML à nettoyer
     * @param array|string $more_tags Balises supplémentaires à autoriser : tableau de noms (["mark", "abbr"])
     * ou chaîne au format strip_tags ("<mark></mark><abbr></abbr>"). Les balises de self::$_removed restent toujours interdites.
     * @return string Contenu HTML nettoyé
     */
    public static function clean($html, $more_tags = []) {
        if (trim((string) $html) === "") {
            return "";
        }
        $dom = new DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="html_sanitizer_root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        $root = $dom->getElementById("html_sanitizer_root");
        if ($root === null) {
            return "";
        }
        self::clean_node($root, self::parse_more_tags($more_tags));
        $out = "";
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }
        return $out;
    }

    /**
     * Nettoie récursivement les enfants d'un noeud
     *
     * @param DOMNode $node Noeud à nettoyer
     * @param array $extra Balises supplémentaires autorisées (noms en minuscules)
     */
    private static function clean_node($node, $extra = []) {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);
                $void = in_array($tag, self::$_void_removed, true);
                if (in_array($tag, self::$_removed, true) and !$void) {
                    $node->removeChild($child);
                    continue;
                }
                if ($void or !(in_array($tag, self::$_allowed, true) or in_array($tag, $extra, true))) {
                    self::clean_node($child, $extra);
                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);
                    continue;
                }
                self::clean_attributes($child, $tag);
                self::clean_node($child, $extra);
            } elseif ($child instanceof DOMComment or $child instanceof DOMProcessingInstruction) {
                $node->removeChild($child);
            }
        }
    }

    /**
     * Convertit la liste de balises supplémentaires en tableau de noms en minuscules
     *
     * @param array|string $more_tags Tableau de noms ou chaîne au format strip_tags ("<mark></mark>")
     * @return array Noms de balises en minuscules
     */
    private static function parse_more_tags($more_tags) {
        if (is_string($more_tags)) {
            preg_match_all('/<\s*\/?\s*([a-z][a-z0-9]*)/i', $more_tags, $m);
            $more_tags = $m[1];
        }
        return array_values(array_unique(array_map("strtolower", (array) $more_tags)));
    }

    /**
     * Retire d'un élément les attributs de la liste noire et ceux contenant une URL ou un style dangereux
     *
     * @param DOMElement $el Élément à nettoyer
     * @param string $tag Nom de la balise (en minuscules)
     */
    private static function clean_attributes($el, $tag) {
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->nodeName);
            $value = self::normalize($attr->value);
            if (self::is_forbidden_attr($name) or str_contains($value, "javascript:") or str_contains($value, "vbscript:")) {
                $el->removeAttributeNode($attr);
            } elseif ($name === "style" and self::is_forbidden_style($value)) {
                $el->removeAttributeNode($attr);
            } elseif ($name === "srcset" and !self::is_safe_srcset($attr->value)) {
                $el->removeAttributeNode($attr);
            } elseif ($name !== "srcset" and in_array($name, self::$_url_attr, true) and !self::is_safe_url($attr->value, ($tag === "img" and $name === "src"))) {
                $el->removeAttributeNode($attr);
            }
        }
        if ($el->getAttribute("target") === "_blank") {
            $el->setAttribute("rel", "noopener noreferrer");
        }
    }

    /**
     * Retourne une valeur d'attribut normalisée pour comparaison : entités décodées, minuscules, sans espaces ni caractères de contrôle
     *
     * @param string $value Valeur à normaliser
     * @return string Valeur normalisée
     */
    private static function normalize($value) {
        return strtolower(str_replace(["\0", "\t", "\n", "\r", "\f", "\v", " "], "", html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, "UTF-8")));
    }

    /**
     * Indique si un nom d'attribut est dans la liste noire
     *
     * @param string $name Nom de l'attribut (en minuscules)
     * @return boolean L'attribut est interdit (true/false)
     */
    private static function is_forbidden_attr($name) {
        return (str_starts_with($name, "on") or str_starts_with($name, "xmlns") or str_contains($name, ":") or in_array($name, self::$_forbidden_attr, true));
    }

    /**
     * Indique si une valeur de style (normalisée) contient un motif interdit
     *
     * @param string $value Valeur normalisée de l'attribut style
     * @return boolean Le style est dangereux (true/false)
     */
    private static function is_forbidden_style($value) {
        foreach (self::$_forbidden_style as $pattern) {
            if (str_contains($value, $pattern)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifie chaque URL candidate d'un attribut srcset ("url1 1x, url2 2x")
     *
     * @param string $srcset Valeur de l'attribut srcset
     * @return boolean Toutes les URL sont sûres (true/false)
     */
    private static function is_safe_srcset($srcset) {
        foreach (explode(",", html_entity_decode((string) $srcset, ENT_QUOTES | ENT_HTML5, "UTF-8")) as $candidate) {
            $url = explode(" ", trim($candidate))[0];
            if ($url !== "" and !self::is_safe_url($url)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Vérifie qu'une URL est sûre (relative, protocole autorisé, ou image base64 si autorisé)
     *
     * @param string $url URL à vérifier
     * @param boolean $allow_data_image Autoriser les images base64 (png, jpeg, gif, webp, bmp) : uniquement pour le src d'une img
     * @return boolean L'URL est sûre (true/false)
     */
    private static function is_safe_url($url, $allow_data_image = false) {
        $url = trim(html_entity_decode((string) $url, ENT_QUOTES | ENT_HTML5, "UTF-8"));
        $url = str_replace(["\0", "\t", "\n", "\r", "\f", "\v", " "], "", $url);
        if ($allow_data_image) {
            $lower = strtolower($url);
            foreach (self::$_data_images as $prefix) {
                if (str_starts_with($lower, $prefix)) {
                    return true;
                }
            }
        }
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if ($scheme === false) {
            return false;
        }
        return $scheme === null or in_array(strtolower($scheme), self::$_protocols, true);
    }

}
