<?php

/**
 * Les CLI sont des scripts � ex�cuter en mode console, ils ne doivent pas �tre ex�cutable depuis un navigateur ! ( Pour cela, voir la condition isset($_SERVER["REMOTE_ADDR"].) <br />
 * Les CLI servent � ex�cuter un traitement PHP long. ( Potentiellement superieurs � 30 secondes. ) <br />
 * Pour cr�er un nouveau CLI, vous devez cr�er un fichier se terminant par ".cli.php" dans le dossier "cli". <br/>
 * (Il est recommand� de cr�er des classe instancier � la fin du fichier, mais ce n'est pas une obligation. ) <br />
 * Pour ex�cuter les CLI, la commande � taper dans la console est : <br />
 * php [Chemain]/cli/start.php
 * 
 * Ce script (start.php) va ex�cuter les CLI les uns apr�s les autres par ordre alphab�tique. 
 * IL EST DONC DECONSEILLE DE CRÉER UN CRON AVEC UNE BOUCLE INFINI ! ( Sinon les CLI suivants ne pouront pas s'ex�cuter.)
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com> 
 */
class start {

/**
 * Les CLI sont des scripts � ex�cuter en mode console, ils ne doivent pas �tre ex�cutable depuis un navigateur ! ( Pour cela, voir la condition isset($_SERVER["REMOTE_ADDR"].) <br />
 * Les CLI servent � ex�cuter un traitement PHP long. ( Potentiellement superieurs � 30 secondes. ) <br />
 * Pour cr�er un nouveau CLI, vous devez cr�er un fichier se terminant par ".cli.php" dans le dossier "cli". <br/>
 * (Il est recommand� de cr�er des classe instancier � la fin du fichier, mais ce n'est pas une obligation. ) <br />
 * Pour ex�cuter les CLI, la commande � taper dans la console est : <br />
 * php [Chemain]/cli/start.php
 * 
 * Ce script (start.php) va ex�cuter les CLI les uns apr�s les autres par ordre alphab�tique. 
 * IL EST DONC DECONSEILLE DE CRÉER UN CRON AVEC UNE BOUCLE INFINI ! ( Sinon les CLI suivants ne pouront pas s'ex�cuter.)
 */
    public function __construct() {
        if (isset($_SERVER["REMOTE_ADDR"])) {
            exit();
        }
        include __DIR__.'../class/cli.class.php';
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