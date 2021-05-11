<?php

/**
 * printer_csv
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class printer_csv {

    public function __construct($content, $filename) {
        header("Content-Disposition: attachment; filename=$filename;");
        header("Content-type:text/csv; charset=UTF-8");
        $csv = "";
        foreach ($content as $row) {
            $csv .= "\"" . implode("\";\"", $row) . "\"" . PHP_EOL;
        }
        echo $csv;
    }

}
