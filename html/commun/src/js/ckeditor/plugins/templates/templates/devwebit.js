/*
 Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR.addTemplates("default",
        {
            imagesPath: CKEDITOR.getUrl(CKEDITOR.plugins.getPath("templates") + "templates/images/"),
            templates: [
                {
                    title: "Image et titre", image: "template1.gif", description: "Une image principale avec un titre et un texte qui entourent l'image.",
                    html: '<h3> Titre </h3> <figure style="float:left;"><img src=" " alt="" style="margin-right: 10px" height="100" width="100" /><figcaption> Légende </figcaption></figure><p> Texte </p>'
                },
                {
                    title: "Etrange modèle", image: "template2.gif", description: "Un modèle qui définit deux colonnes , chacune avec un titre et un texte .",
                    html: '<table cellspacing="0" cellpadding="0" style="width:100%" border="0"><tr><td style="width:50%"><h3>Titre 1</h3></td><td></td><td style="width:50%"><h3>Titre 2</h3></td></tr><tr><td> Contenu 1 </td><td></td><td> Contenu 2 </td></tr></table><p> Texte </p>'
                },
                {
                    title: "Texte et tableau", image: "template3.gif", description: "Un titre avec un texte et un tableau.",
                    html: '<div style="width: 80%"><h3> Titre </h3><table style="width:150px;float: right" cellspacing="0" cellpadding="0" border="1"><caption style="border:solid 1px black"><strong> Titre </strong></caption><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></table><p> Contenu </p></div>'
                },
                {
                    title: "Titre, texte et image", image: "template4.png", description: "Un titre et un texte, qui entourent l'image de droite.",
                    html: '<h3> Titre </h3><figure style="float:right;"><img src=" " alt="" style="margin-right: 10px" height="100" width="100" align="right" /><figcaption> Légende </figcaption></figure><p> Texte </p>'
                },
                {
                    title: "Accordéon", image: "template6.gif", description: "Un accordéon",
                    html: '<h2>Titre</h2><p>Texte</p><div class="accordion"><h3>Titre accordéon</h3><div><p>Texte accordéon</p></div><h3>Titre accordéon</h3><div><p>Texte accordéon</p></div><h3>Titre accordéon</h3><div><p>Texte accordéon</p></div><h3>Titre accordéon</h3><div><p>Texte accordéon</p></div><h3>Titre accordéon</h3><div><p>Texte accordéon</p></div></div>'
                },
                {
                    title: "Titre, texte et 3 figures", image: "template7.gif", description: "Un titre, un texte ainsi qu'un alignement de trois figures, puis un texte.",
                    html: '<h2>Titre</h2><p>Texte</p><div style="display:table;width:100%;"><div style="width:32%;position:relative;float:left;"><figure><img alt="image" src="" /><figcaption>L&eacute;gende</figcaption></figure></div><div style="width:32%;position:relative;float:left;"><figure><img alt="image" src="" /><figcaption>L&eacute;gende</figcaption></figure></div><div style="width:32%;position:relative;float:left;"><figure><img alt="image" src="" /><figcaption>L&eacute;gende</figcaption></figure></div></div><p>Texte</p>'
                }
            ]
        }
);