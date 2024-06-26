﻿<?php

/**
 * Les CLI sont des scripts à exécuter en mode console, ils ne doivent pas être exécutable depuis un navigateur ! ( Pour cela, voir la condition isset($_SERVER["REMOTE_ADDR"].) <br />
 * Les CLI servent à exécuter un traitement PHP long. ( Potentiellement superieurs à 30 secondes. ) <br />
 * Pour créer un nouveau CLI, vous devez créer un fichier se terminant par ".cli.php" dans le dossier "cli". <br/>
 * (Il est recommandé de créer des classe instancier à la fin du fichier, mais ce n'est pas une obligation. ) <br />
 * Pour exécuter les CLI, la commande à taper dans la console est : <br />
 * php [Chemain]/cli/start.php
 * 
 * Ce script (start.php) va exécuter les CLI les uns après les autres par ordre alphabétique. 
 * IL EST DONC DECONSEILLE DE CRÃ‰ER UN CRON AVEC UNE BOUCLE INFINI ! ( Sinon les CLI suivants ne pouront pas s'exécuter.)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class start {

    /**
     * Les CLI sont des scripts à exécuter en mode console, ils ne doivent pas être exécutable depuis un navigateur ! ( Pour cela, voir la condition isset($_SERVER["REMOTE_ADDR"].) <br />
     * Les CLI servent à exécuter un traitement PHP long. ( Potentiellement superieurs à 30 secondes. ) <br />
     * Pour créer un nouveau CLI, vous devez créer un fichier se terminant par ".cli.php" dans le dossier "cli". <br/>
     * (Il est recommandé de créer des classe instancier à la fin du fichier, mais ce n'est pas une obligation. ) <br />
     * Pour exécuter les CLI, la commande à taper dans la console est : <br />
     * php [Chemain]/cli/start.php
     * 
     * Ce script (start.php) va exécuter les CLI les uns après les autres par ordre alphabétique. 
     * IL EST DONC DECONSEILLE DE CRÃ‰ER UN CRON AVEC UNE BOUCLE INFINI ! ( Sinon les CLI suivants ne pouront pas s'exécuter.)
     */
    public function __construct() {
        if (isset($_SERVER["REMOTE_ADDR"])) {
            exit();
        }
        include __DIR__ . '/../class/cli.class.php';
        cli::classloader();
        cli::write("[START] \n");
        foreach (glob(__DIR__ . "/*.cli.php") as $cli) {
            echo "\nStart : " . $cli . "\n";
            include_once $cli;
        }
        cli::write("[END]");
    }

}

$start = new start();
