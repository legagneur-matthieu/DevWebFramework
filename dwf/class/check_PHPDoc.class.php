<?php

/**
 * Cette classe permet de vérifier la qualité et la complétude
 * de la documentation PHPDoc d'un fichier PHP (classe uniquement).
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class check_PHPDoc {

    /**
     * Chemin du fichier PHP à analyser
     * @var string Chemin du fichier PHP à analyser
     */
    private string $filePath;

    /**
     * Instance de ReflectionClass de la classe analysée
     * @var ReflectionClass|null Instance de ReflectionClass de la classe analysée
     */
    private ?ReflectionClass $reflection = null;

    /**
     * Tableau des erreurs détectées
     * @var array Tableau des erreurs détectées
     */
    private array $errors = [];

    /**
     * Cette classe permet de vérifier la qualité et la complétude
     * de la documentation PHPDoc d'un fichier PHP (classe uniquement).
     *
     * @param string $filePath Chemin vers le fichier PHP à analyser
     */
    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    /**
     * Affiche le checkup de la documentation du framework
     * Attention : peut être long a executer, mode CLI recomandé.
     */
    public static function check_DWF() {
        $report = [];
        foreach (glob(__DIR__ . "/*.class.php") as $class) {
            $r = check_PHPDoc::check_file($class);
            if (!$r["valid"]) {
                $report[] = $r;
            }
        }
        debug::print_r($report);
    }

    /**
     * Analyse rapidement un fichier PHP (méthode statique).
     *
     * @param string $filePath Chemin vers le fichier PHP à analyser
     * @return array Rapport de la PHPDoc
     */
    public static function check_file(string $filePath) {
        return (new self($filePath))->check();
    }

    /**
     * Lance l'analyse complète du fichier.
     *
     * @return array Rapport de la PHPDoc
     */
    public function check() {
        $this->errors = [];
        if (!$this->checkSyntax()) {
            return $this->getResult();
        }
        $className = $this->extractClassNameFromFile();
        if ($className === null) {
            $this->addError('Aucune classe trouvée dans ce fichier.');
            return $this->getResult();
        }
        if (!class_exists($className)) {
            require_once $this->filePath;
        }
        if (!class_exists($className)) {
            $this->addError("Impossible de charger la classe : {$className}");
            return $this->getResult();
        }
        $this->reflection = new ReflectionClass($className);
        if ($this->shouldIgnoreClass()) {
            return [
                'file' => $this->filePath,
                'class' => $className,
                'valid' => true,
                'errors' => []
            ];
        }
        $this->checkClassDocumentation();
        $this->checkAllProperties();
        $this->checkAllMethods();
        return $this->getResult();
    }

    /**
     * Vérifie s'il y a une erreur de syntaxe dans le fichier.
     *
     * @return bool Syntax valide (True/False)
     */
    private function checkSyntax() {
        $output = shell_exec('php -l ' . escapeshellarg($this->filePath) . ' 2>&1');
        if (!str_contains($output, 'No syntax errors detected')) {
            $this->addError('Erreur de syntaxe PHP : ' . trim($output));
            return false;
        }
        return true;
    }

    /**
     * Extrait le nom complet de la classe (avec namespace) via les tokens.
     *
     * @return string|null Class du fichier
     */
    private function extractClassNameFromFile() {
        $content = file_get_contents($this->filePath);
        $tokens = token_get_all($content);
        $namespace = '';
        $className = null;
        for ($i = 0; $i < count($tokens); $i++) {
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_NAMESPACE) {
                $namespace = '';
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if (is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                        $namespace .= $tokens[$j][1];
                    } elseif (is_array($tokens[$j]) && $tokens[$j][0] === T_NS_SEPARATOR) {
                        $namespace .= '\\';
                    } elseif ($tokens[$j] === ';') {
                        break;
                    }
                }
            }
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_CLASS) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if (is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                        $className = $tokens[$j][1];
                        break 2;
                    }
                }
            }
        }
        if ($className === null) {
            return null;
        }
        return $namespace ? $namespace . '\\' . $className : $className;
    }

    /**
     * Vérifie la documentation de la classe.
     */
    private function checkClassDocumentation() {
        $docComment = $this->reflection->getDocComment();
        if (!$docComment) {
            $this->addError('La classe n\'a pas de PHPDoc.');
            return;
        }
        $parsed = $this->parseDocComment($docComment);
        if (empty(trim($parsed['description']))) {
            $this->addError('La classe n\'a pas de description dans sa PHPDoc.');
        }
        if (empty($parsed['author'])) {
            $this->addError('La classe n\'a pas de tag @author.');
        } elseif (!preg_match('/^[A-Za-zÀ-ÿ\s\-]+ <[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}>$/', $parsed['author'])) {
            $this->addError('Format @author incorrect. Exemple attendu : "Nom Prénom <email@exemple.com>"');
        }
    }

    /**
     * Vérifie toutes les méthodes de la classe.
     */
    private function checkAllMethods() {
        foreach ($this->reflection->getMethods() as $method) {
            $this->checkMethodDocumentation($method);
        }
    }

    /**
     * Vérifie la documentation d'une méthode.
     *
     * @param ReflectionMethod $method ReflectionMethod à analyser
     */
    private function checkMethodDocumentation(ReflectionMethod $method) {
        $docComment = $method->getDocComment();
        $methodName = $method->getName();
        if (!$docComment) {
            $this->addError("La méthode `{$methodName}` n'a pas de PHPDoc.");
            return;
        }
        $parsed = $this->parseDocComment($docComment);
        if (empty(trim($parsed['description']))) {
            $this->addError("La méthode `{$methodName}` n'a pas de description.");
        }
        // Vérification des paramètres
        foreach ($method->getParameters() as $param) {
            $paramName = '$' . $param->getName();
            if (!isset($parsed['params'][$paramName])) {
                $this->addError("Le paramètre `{$paramName}` de la méthode `{$methodName}` n'est pas documenté.");
                continue;
            }
            $docParam = $parsed['params'][$paramName];
            if (empty($docParam['type'])) {
                $this->addError("Le paramètre `{$paramName}` de `{$methodName}` n'a pas de type dans @param.");
            }
            if (empty(trim($docParam['description']))) {
                $this->addError("Le paramètre `{$paramName}` de `{$methodName}` n'a pas de description.");
            }
        }
        // Vérification du @return (sauf constructeurs et void)
        $returnType = $method->getReturnType();
        $isVoid = $returnType instanceof \ReflectionNamedType && $returnType->getName() === 'void';
        if ($methodName !== '__construct' && !$isVoid) {
            if ($method->hasReturnType() && empty($parsed['return'])) {
                $this->addError("La méthode `{$methodName}` a un type de retour mais n'a pas de @return.");
            }
            if (!empty($parsed['return']) && empty(trim($parsed['return']['description']))) {
                $this->addError("Le @return de la méthode `{$methodName}` n'a pas de description.");
            }
        }
    }

    /**
     * Vérifie toutes les propriétés de la classe.
     */
    private function checkAllProperties() {
        foreach ($this->reflection->getProperties() as $property) {
            $this->checkPropertyDocumentation($property);
        }
    }

    /**
     * Vérifie la documentation d'une propriété.
     *
     * @param ReflectionProperty $property ReflectionProperty à analyser
     */
    private function checkPropertyDocumentation(ReflectionProperty $property) {
        $docComment = $property->getDocComment();
        $propertyName = $property->getName();
        if (!$docComment) {
            $this->addError("La propriété `{$propertyName}` n'a pas de PHPDoc.");
            return;
        }
        $parsed = $this->parseDocComment($docComment);
        if (empty($parsed['var'])) {
            $this->addError("La propriété `{$propertyName}` n'a pas de tag @var.");
            return;
        }
        if (empty($parsed['var']['type'])) {
            $this->addError("La propriété `{$propertyName}` n'a pas de type dans @var.");
        }
        if (empty(trim($parsed['var']['description']))) {
            $this->addError("La propriété `{$propertyName}` n'a pas de description dans @var.");
        }
    }

    /**
     * Parse une PHPDoc et retourne un tableau structuré.
     *
     * @param string $docComment Documentation a parser
     * @return array Structure de la documentation
     */
    private function parseDocComment($docComment) {
        $result = [
            'description' => '',
            'params' => [],
            'return' => null,
            'var' => null,
            'author' => null,
        ];
        $lines = preg_split('/\r\n|\r|\n/', $docComment);
        $descriptionParts = [];
        foreach ($lines as $line) {
            $clean = trim(preg_replace('/^\s*\*\s?/', '', $line));
            if (preg_match('/^@param\s+([^\s]+)\s+(\$[a-zA-Z_][a-zA-Z0-9_]*)\s*(.*)$/i', $clean, $m)) {
                $result['params'][$m[2]] = [
                    'type' => $m[1],
                    'description' => trim($m[3])
                ];
            } elseif (preg_match('/^@return\s+([^\s]+)\s*(.*)$/i', $clean, $m)) {
                $result['return'] = [
                    'type' => $m[1],
                    'description' => trim($m[2])
                ];
            } elseif (preg_match('/^@var\s+([^\s]+)\s*(.*)$/i', $clean, $m)) {
                $result['var'] = [
                    'type' => $m[1],
                    'description' => trim($m[2])
                ];
            } elseif (preg_match('/^@author\s+(.+)$/i', $clean, $m)) {
                $result['author'] = trim($m[1]);
            } elseif (!empty($clean) && !str_starts_with($clean, '@')) {
                $descriptionParts[] = $clean;
            }
        }
        $result['description'] = implode(' ', $descriptionParts);
        return $result;
    }

    /**
     * Vérifie si la classe doit être ignorée (via attribut ou héritage).
     *
     * @return bool la classe doit être ignorée
     */
    private function shouldIgnoreClass() {
        if (!empty($this->reflection->getAttributes(IgnoreCheckPHPDoc::class))) {
            return true;
        }
        $parent = $this->reflection->getParentClass();
        if ($parent) {
            $parentName = $parent->getName();
            $ignoredParents = ['Exception', 'Error', 'Throwable', 'DateTime', 'DateTimeImmutable'];
            if (in_array($parentName, $ignoredParents)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Ajoute une erreur.
     *
     * @param string $message Message d'erreur
     */
    private function addError($message) {
        $this->errors[] = $message;
    }

    /**
     * Retourne le résultat de l'analyse.
     *
     * @return array Résultat de l'analyse
     */
    private function getResult() {
        return [
            'file' => $this->filePath,
            'class' => $this->reflection?->getName(),
            'valid' => empty($this->errors),
            'errors' => $this->errors,
        ];
    }
}

/**
 * Attribut pour ignorer le contrôle PHPDoc sur une classe
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
#[Attribute(Attribute::TARGET_CLASS)]
class IgnoreCheckPHPDoc {
    
}
