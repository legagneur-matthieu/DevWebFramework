<?php

/**
 * Cette classe permet l'affichage de données dans un tableau à partir du fichier .csv
 * 
 * @author Bernard Rodolphe <mr.rodolphe.bernard@gmail.com>
 */
class printcsv {

    /**
     * Cette classe permet l'affichage de données dans un tableau à partir du fichier .csv     * 
     * @param array $data tableau de données a 2 dimensions
     */
    public function __construct($data) {
        $csv = "";
        foreach ($data as $value) {
            foreach ($value as $v) {
                $csv .= '"' . $v . '";';
            }
            $csv .= "\n";
        }
        echo html_structures::a_link("../commun/csv.php?value=" . base64_encode(utf8_decode($csv)), "Exporter en CSV", "btn btn-primary", "(Nouvel onglet)", TRUE);
    }

}
