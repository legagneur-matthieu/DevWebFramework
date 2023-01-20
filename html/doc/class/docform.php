<?php

//création du formulaire
form::new_form();
form::input("Input de type text", "input_1");
form::input("Input de type password", "input_2", "password");
form::input("Input avec valeur initiale", "input_3", "text", "valeur initiale");
form::datepicker("Un datepicker", "datepicker_1");
form::select("Un sélecteur", "select_1", array(
    array(1, "Abricots"),
    array(2, "Poires", true), //Poires est selectioné par defaut
    array(3, "Pommes"),
));
form::textarea("Un textarea", "ta_1");
//création d'un CKEditor
form::textarea("Un ckeditor", "ta_2");
$cke = js::ckeditor("ta_2");

//bouton de soumission
form::submit("btn-primary");
//fermeture du formulaire
form::close_form();

//exécution du formulaire
if (isset($_POST["input_1"])) {

    //récupère la date du datepicker au format US
    $date = form::get_datepicker_us("datepicker_1");
    //filtre les balises utilisées dans CKEditor, protection XSS
    $ta_2 = $cke->parse($_POST["ta_2"]);

    //message de succès ou erreur
    js::alert("le formulaire a bien été soumis");
    //redirection vers la page courante = rafraichissement de la page
    js::redir("");
}
?>