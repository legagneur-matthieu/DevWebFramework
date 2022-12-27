<?php

/**
 * Cette classe permet de formatter (réindenter) du code php/html/js
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class php_simple_formater {

    /**
     * Memento des indentations
     * @var array Memento des indentations
     */
    private $_memento_indent = [];

    /**
     * Nombre d'espace pour l'indentation, 0 = utilise les tabulations
     * @var int Nombre d'espace pour l'indentation, 0 = utilise les tabulations
     */
    private $_indent_spaces = 0;

    /**
     * Retourne le code PHP (HTML et JS) formaté
     * @param string $code Code PHP (HTML et JS)
     * @param int $indent_spaces Nombre d'espace pour l'indentation, 0 = utilise les tabulations
     * @return string Code formaté
     */
    public function format($code, $indent_spaces = 0) {
        $this->_indent_spaces = $indent_spaces;
        $new_code = "";
        while ($code != ($new_code = strtr($code, [
    "  " => " ",
    "\t" => "",
    "]" => "\n]",
    "[" => "[\n",
    "}" => "\n}",
    "{" => "{\n",
    "\n " => "\n",
    " \n" => "\n",
    "\n\n" => "\n",
    "\n \n" => "\n",
    "\n\t\n" => "\n",
    "\n \t\n" => "\n",
    "\n\t \n" => "\n",
    "\n \t \n" => "\n",
        ]))) {
            $code = $new_code;
        };
        $indetedcode = "";
        $indent = 0;
        foreach (explode("\n", $code) as $line) {
            if (!empty($line) && $line != "\n") {
                if (
                        $indent > 0 && (
                        strpos($line, "}") !== false ||
                        strpos($line, "]") !== false ||
                        strpos($line, "</") !== false
                        )
                ) {
                    $indent--;
                }
                $indetedcode .= $this->indent($indent) . strtr(trim($line), ["\n" => ""]) . "\n";
                if (
                        strpos($line, "{") !== false ||
                        strpos($line, "[") !== false ||
                        (
                        strpos($line, ">") !== false &&
                        strpos($line, "/>") === false &&
                        strpos($line, "</") === false &&
                        strpos($line, "?>") === false &&
                        strpos($line, "@author") === false
                        )
                ) {
                    $indent++;
                }
            }
        }
        return $indetedcode;
    }

    /**
     * Retourne les indentations pour une ligne
     * @param int $nb nombre d'indentations (niveau)
     * @return string Indentations de la ligne
     */
    private function indent($nb) {
        if (isset($this->_memento_indent[$nb])) {
            return $this->_memento_indent[$nb];
        }
        $this->_memento_indent[$nb] = $indent = str_repeat(($this->_indent_spaces == 0 ? "\t" : str_repeat(" ", $this->_indent_spaces)), $nb);
        return $indent;
    }

}
