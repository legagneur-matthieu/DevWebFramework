<?php

/**
 * Cette classe permet de généré une representation graphique d'une base de donnée
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class mocodo {

    /**
     * Style de police Arial.
     */
    const SHAPES_arial = "arial";

    /**
     * Style de police Copperplate.
     */
    const SHAPES_copperplate = "copperplate";

    /**
     * Style de police Georgia.
     */
    const SHAPES_georgia = "georgia";

    /**
     * Style inspiré de Mondrian (géométrique, intégration de couleurs primaires).
     */
    const SHAPES_mondrian = "mondrian";

    /**
     * Style de police sans-serif.
     */
    const SHAPES_sans = "sans";

    /**
     * Style de police serif.
     */
    const SHAPES_serif = "serif";

    /**
     * Style de police Times.
     */
    const SHAPES_times = "times";

    /**
     * Style de police Trebuchet.
     */
    const SHAPES_trebuchet = "trebuchet";

    /**
     * Style de police Verdana.
     */
    const SHAPES_verdana = "verdana";

    /**
     * Style Xinnian.
     */
    const SHAPES_xinnian = "xinnian";

    /**
     * Pas de couleurs, simple.
     */
    const COLORS_blank = "blank";

    /**
     * Noir et blanc.
     */
    const COLORS_bw = "bw";

    /**
     * Noir et blanc avec transparence alpha.
     */
    const COLORS_bw_alpha = "bw-alpha";

    /**
     * Palette thématique désert (beiges, bruns, sables).
     */
    const COLORS_desert = "desert";

    /**
     * Thème fond sombre.
     */
    const COLORS_dark = "dark";

    /**
     * Palette désert sombre.
     */
    const COLORS_dark_desert = "dark-desert";

    /**
     * Palette océan sombre (bleus profonds).
     */
    const COLORS_dark_ocean = "dark-ocean";

    /**
     * Palette étang sombre (verts profonds).
     */
    const COLORS_dark_pond = "dark-pond";

    /**
     * Palette keepsake ou vintage (pastels doux).
     */
    const COLORS_keepsake = "keepsake";

    /**
     * Palette Mondrian (couleurs primaires: rouge, jaune, bleu).
     */
    const COLORS_mondrian = "mondrian";

    /**
     * Palette thématique océan (bleus, aquas).
     */
    const COLORS_ocean = "ocean";

    /**
     * Palette thématique étang (verts, bleus).
     */
    const COLORS_pond = "pond";

    /**
     * Blanc et noir.
     */
    const COLORS_wb = "wb";

    /**
     * Palette Xinnian (rouges, ors, festifs).
     */
    const COLORS_xinnian = "xinnian";

    /**
     * Palette Brewer 1 – Jaune → Vert clair → Vert foncé (YlGn)
     */
    const COLORS_brewer_up_1 = "brewer+1";

    /**
     * Palette Brewer 1 inversée – Vert foncé → Vert clair → Jaune (YlGn)
     */
    const COLORS_brewer_down_1 = "brewer-1";

    /**
     * Palette Brewer 2 – Jaune → Vert → Bleu (YlGnBu) → la plus utilisée et équilibrée
     */
    const COLORS_brewer_up_2 = "brewer+2";

    /**
     * Palette Brewer 2 inversée – Bleu → Vert → Jaune (YlGnBu)
     */
    const COLORS_brewer_down_2 = "brewer-2";

    /**
     * Palette Brewer 3 – Vert clair → Bleu clair → Bleu foncé (GnBu)
     */
    const COLORS_brewer_up_3 = "brewer+3";

    /**
     * Palette Brewer 3 inversée – Bleu foncé → Bleu clair → Vert clair (GnBu)
     */
    const COLORS_brewer_down_3 = "brewer-3";

    /**
     * Palette Brewer 4 – Bleu clair → Vert clair → Vert foncé (BuGn)
     */
    const COLORS_brewer_up_4 = "brewer+4";

    /**
     * Palette Brewer 4 inversée – Vert foncé → Vert clair → Bleu clair (BuGn)
     */
    const COLORS_brewer_down_4 = "brewer-4";

    /**
     * Palette Brewer 5 – Violet → Bleu → Vert (PuBuGn)
     */
    const COLORS_brewer_up_5 = "brewer+5";

    /**
     * Palette Brewer 5 inversée – Vert → Bleu → Violet (PuBuGn)
     */
    const COLORS_brewer_down_5 = "brewer-5";

    /**
     * Palette Brewer 6 – Jaune → Orange → Rouge (YlOrRd) → parfait pour montrer une intensité croissante
     */
    const COLORS_brewer_up_6 = "brewer+6";

    /**
     * Palette Brewer 6 inversée – Rouge → Orange → Jaune (YlOrRd)
     */
    const COLORS_brewer_down_6 = "brewer-6";

    /**
     * Palette Brewer 7 – Orange clair → Rouge clair → Rouge foncé (OrRd)
     */
    const COLORS_brewer_up_7 = "brewer+7";

    /**
     * Palette Brewer 7 inversée – Rouge foncé → Rouge clair → Orange clair (OrRd)
     */
    const COLORS_brewer_down_7 = "brewer-7";

    /**
     * Palette Brewer 8 – Rose → Violet clair → Magenta (PuRd)
     */
    const COLORS_brewer_up_8 = "brewer+8";

    /**
     * Palette Brewer 8 inversée – Magenta → Violet clair → Rose (PuRd)
     */
    const COLORS_brewer_down_8 = "brewer-8";

    /**
     * Palette Brewer 9 – Bleu très clair → Bleu clair → Bleu foncé (Blues) → la plus sobre et professionnelle
     */
    const COLORS_brewer_up_9 = "brewer+9";

    /**
     * Palette Brewer 9 inversée – Bleu foncé → Bleu clair → Bleu très clair (Blues)
     */
    const COLORS_brewer_down_9 = "brewer-9";

    /**
     * Préserve l'arrangement actuel.
     */
    const ARRANGE_current = "arrange:current";

    /**
     * Arrange les boîtes en disposition large, horizontale.
     */
    const ARRANGE_wide = "arrange:wide";

    /**
     * Arrangement équilibré avec facteur d'équilibre 0 (moins d'emphase sur l'équilibre).
     */
    const ARRANGE_ballanced_0 = "arrange:ballanced=0";

    /**
     * Arrangement équilibré avec facteur d'équilibre 1 (plus d'emphase sur l'équilibre).
     */
    const ARRANGE_ballanced_1 = "arrange:ballanced=1";

    /**
     * Arrangement libre sans contraintes spécifiques.
     */
    const ARRANGE_freely = "arrange";

    private $_mocodo = [
        "text" => "",
        "svg" => "",
        "zip" => "",
    ];
    private $_shapes;
    private $_colors;
    private $_arrangement;

    /**
     * Cette classe permet de généré une representation graphique d'une base de donnée
     *
     * @param array $db Le tableau représentant la structure de la base de données.
     * @param string $arrangement Les arrangement du MCD (par défaut 'arrange:balanced=0').
     * @param string $colors Le schéma de couleurs (par défaut 'brewer-1').
     * @param string $shapes Le style des formes (par défaut 'verdana').
     */
    public function __construct($db, $arrangement = self::ARRANGE_ballanced_0, $colors = self::COLORS_brewer_up_1, $shapes = self::SHAPES_verdana) {
        $this->_shapes = $shapes;
        $this->_colors = $colors;
        $this->_arrangement = $arrangement;
        $this->_mocodo["text"] = $this->optimizeText($this->convertDbToMocodoText($db));
        $this->generate();
    }

    /**
     * Convertit le tableau de la base de données en texte formaté pour Mocodo.
     *
     * @param array $db Le tableau représentant la structure de la base de données.
     * @return string Le dictionaire de données formaté pour Mocodo.
     */
    private function convertDbToMocodoText($db) {
        $lines = [];
        foreach ($db as $entityName => $fields) {
            $entityText = strtoupper($entityName) . ':';
            $fieldList = [];
            foreach ($fields as $field) {
                $fieldName = $field[0];
                $fieldType = $field[1];
                if (in_array($fieldType, ['int', 'integer', 'bool', 'boolean', 'string', 'mail', 'array', 'psw', 'password'])) {
                    $fieldList[] = $fieldName;
                } else {
                    $fieldList[] = '#' . $fieldName . '>' . strtoupper($fieldType) . '>id';
                }
            }
            $entityText .= implode(',', $fieldList);
            $lines[] = $entityText;
        }
        return implode("\n", $lines);
    }

    /**
     * Optimise le dictionaire de données via mocodo.
     *
     * @param string $text Le dictionaire de données à optimiser.
     * @return string Le dictionaire de données optimisé ou original en cas d'erreur.
     * @throws Exception Si la réponse JSON est invalide.
     */
    private function optimizeText($text) {
        // Optimiser le texte via rewrite.php
        $rewriteData = [
            'args' => $this->_arrangement,
            'text' => $text,
        ];
        $rewriteUrl = 'https://mocodo.net/web/rewrite.php';
        $ch = curl_init($rewriteUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($rewriteData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode === 200) {
            $jsonResponse = json_decode($response, true);
            if (isset($jsonResponse['text'])) {
                $optimizedText = $jsonResponse['text'];
            } else {
                // Gérer l'erreur si la clé n'est pas présente
                throw new Exception('Réponse JSON invalide : clé "text" manquante.');
            }
        } else {
            // Gérer l'erreur HTTP, ou utiliser le texte original en fallback
            $optimizedText = $text; // Fallback au texte original en cas d'erreur
            // Optionnel : throw new Exception('Erreur HTTP lors de l\'optimisation : ' . $httpCode);
        }
        return $optimizedText;
    }

    /**
     * Génère le SVG et le ZIP via Mocodo.
     *
     * @throws Exception En cas d'erreur HTTP ou de réponse JSON invalide.
     */
    private function generate() {
        $ch = curl_init('https://mocodo.net/web/generate.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'state' => 'ready',
            'title' => config::$_PDO_dbname,
            'text' => $this->_mocodo["text"],
            'shapes' => $this->_shapes,
            'colors' => $this->_colors,
            'adjust_width' => '1.00',
            'detect_overlaps' => 'on',
            'conversions' => ['_url.url'], // Tableau comme dans l'exemple
            'fk_format' => '{label}',
            'sql_case' => 'snake_case',
            'strengthen_card' => '_1,1_',
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode === 200) {
            $jsonResponse = json_decode($response, true);
            if (isset($jsonResponse['svg']) && isset($jsonResponse['zipURL'])) {
                $this->_mocodo["svg"] = $jsonResponse['svg'];
                $this->_mocodo["zip"] = $jsonResponse['zipURL'];
            } else {
                throw new Exception('Réponse JSON invalide : clés "svg" ou "zip" manquantes.');
            }
        } else {
            throw new Exception('Erreur HTTP : ' . $httpCode);
        }
    }

    /**
     * Retourne le dictionaire de données Mocodo dans une balise pre.
     *
     * @return string Le texte formaté en HTML.
     */
    public function get_text() {
        return tags::tag("pre", [], $this->_mocodo["text"]);
    }

    /**
     * Retourne le SVG généré par Mocodo.
     *
     * @return string Le SVG.
     */
    public function get_svg() {
        return $this->_mocodo["svg"];
    }

    /**
     * Retourne un lien de téléchargement du ZIP.
     *
     * @return string Le lien du ZIP.
     */
    public function get_zip() {
        return html_structures::a_link(
                        $this->_mocodo["zip"],
                        html_structures::bi("file-earmark-zip-fill") . " " . basename($this->_mocodo["zip"]),
                        "btn btn-primary");
    }
}
