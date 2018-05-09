phpvm = function () {
    $("head").append('<script type="text/javascript" src="../commun/src/js/php/php.js"></script>');

    /**
     * Interpréte du code PHP
     * @param {string} code Code PHP a executer ( exemple : "<?php echo 'hello world'; ?>")
     * @param {object} optn opteon a passer en paramètres (exemple : {POST:"var1=valeur1&var2=valeur2"} )
     * @returns {PHP.VM@pro;OUTPUT_BUFFERS@call;join|PHP.VM.$strict} resultat du script
     */
    this.raw = function (code, optn) {
        return new PHP(code, optn).vm.OUTPUT_BUFFER;
    };

    /**
     * Interpréte un script PHP
     * @param {string} file Chemain du script a executer,
     * ATTENTION ! Le code de ce script sera publique et accéssible par tous ! ne pas utiliser de code sensible (mot de passe en clair par exemple)
     * ASTUCE : utilisez l'estention de fichier ".phpjs" et faites reconaitre cette ewtention a votre IDE 
     * pour pouvoir développer en PHP sans que votre serveur web n'interprete le fichier par lui même.
     * @param {object} optn opteon a passer en paramètres (exemple : {POST:"var1=valeur1&var2=valeur2"} )
     * @returns {PHP.VM@pro;OUTPUT_BUFFERS@call;join|PHP.VM.$strict} resultat du script
     */
    this.file = function (file, optn) {
        return this.raw(file_get_contents(file), optn);
    };

};
$phpvm = new phpvm();