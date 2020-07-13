<?php

//création du formulaire
form::new_form();
form::input("Input de type text", "input_1");
form::input("Input de type password", "input_2", "password");
form::input("Input avec valeur initiale", "input_3", "text", "valeur initiale");
form::datepicker("Un datepicker", "datepicker_1");
form::select("Un selecteur", "select_1", array(
    array(1, "Abricots"),
    array(2, "Poires", true), //Poires est selectioné par defaut
    array(3, "Pommes"),
));
form::textarea("Un textarea", "ta_1");
//création d"un CKEditor
form::textarea("Un ckeditor", "ta_2");
$cke = js::ckeditor("ta_2");

//bouton de soumition
form::submit("btn-primary");
//fermeture du formulaire
form::close_form();

//execution du formulaire
if (isset($_POST["input_1"])) {

    //recupere la date du datepicker au format US
    $date = form::get_datepicker_us("datepicker_1");
    //filtre les balises utilisé dans CKEditor, protection XSS
    $ta_2 = $cke->parse($_POST["ta_2"]);

    //message de succes ou erreur
    js::alert("le formulaire a bien été soumis");
    //redirection vers la page courante = rafraichisement de la page
    js::redir("");
}
?>