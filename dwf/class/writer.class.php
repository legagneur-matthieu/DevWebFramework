<?php

/**
 * Cette classe permet de gèrer un buffer a l'écriture de fichiers
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class writer extends singleton {

    private $_buffer = [];

    /**
     * Ajoute un fichier au buffer
     * @param string $file Chemin du fichier qui sera écrit
     * @param string $content Contenu du fichier
     * @param boolean $rewrite Si le fichier est déja en buffer, faut-il le réecrire ? (true ou false, true par defaut)
     */
    public function add($file, $content, $rewrite = true) {
        if (!$this->exist($file) || $rewrite) {
            $this->_buffer[$file] = $content;
        }
    }

    /**
     * Vide le buffer ou supprime le fichier entré en paramètre
     * @param strig/boolean $file fichier à supprimer du buffer ou false (par defaut) pour vider entièrement le buffer
     */
    public function clear($file = false) {
        if ($file) {
            if ($this->exist($file)) {
                unset($this->_buffer[$file]);
            }
        } else {
            $this->_buffer = [];
        }
    }

    /**
     * Ecrit les fichiers mis en buffer et le vide
     */
    public function write() {
        foreach ($this->_buffer as $file => $content) {
            file_put_contents($file, $content);
        }
        $this->clear();
    }

    /**
     * Ecrit les fichiers mis en buffer dans une achive et vide le buffer
     * @param string $zipname Nom de l'achive ("Archive.zip", par exemple)
     * @param booelan $memory_less Si true alors l'ecriture de l'archive prendra moins de mémoire mais sera plus lente
     */
    public function write_zip($zipname, $memory_less = false) {
        if ($memory_less) {
            foreach ($this->_buffer as $file => $content) {
                ($zip = new ZipArchive())->open($zipname, ZipArchive::CREATE);
                $zip->addFromString($file, $content);
                $zip->close();
            }
        } else {
            ($zip = new ZipArchive())->open($zipname, ZipArchive::CREATE);
            foreach ($this->_buffer as $file => $content) {
                $zip->addFromString($file, $content);
            }
            $zip->close();
        }
        $this->clear();
    }

    /**
     * Retourne le nombre de fichiers dans le buffer
     * @return int Le nombre de fichiers dans le buffer
     */
    public function count() {
        return count($this->_buffer);
    }

    /**
     * Retourne si un fichier existe dans le buffer ou non
     * @param string $file Chemain du fichier
     * @return boolean True si le fichier existe, sinon false
     */
    public function exist($file) {
        return isset($this->_buffer[$file]);
    }

    /**
     * Retourne le contenu d'un fichier du buffer
     * @param string $file Chemain du fichier
     * @return string Contenu du fichier
     */
    public function content($file) {
        return ($this->exist($file) ? $this->_buffer[$file] : "");
    }

    /**
     * Ecrit les fichiers
     */
    public function __destruct() {
        $this->write();
    }

}
