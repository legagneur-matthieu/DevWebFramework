/**
 * Gestion de formulaire (require une balise form avec un id)
 * @param {string} id Id CSS de la balise form
 * @returns {dwf_form}
 */
function dwf_form(id) {
    this.id = id;

    /**
     * Ajoute un élément au formulaire
     * 
     * @param {string} str Ajoute un élément au formulaire
     * @returns {undefined}
     */
    this.append = function (str) {
        $("#" + this.id).append(str);
    };

    /**
     * Ajoute un input au formulaire
     * @param {string} label Label
     * @param {string} name Name
     * @param {string} type Type de l'input (text par défaut)
     * @param {string} value Value de l'input (null par défaut)
     * @param {boolean} required Le champ est-il requis ? (true/false, false par defaut )
     * @param {string} class_css Class CSS
     * @param {string} list Datalist (null par défaut, sinon indiquer l'id de la dataliste)
     * @returns {undefined}
     */
    this.input = function (label, name, type, value, required, class_css, list) {
        this.append('<div class="form-group ' + (!isset(class_css) ? "" : class_css) +
                '"><label for="' + name + '">' + label + '</label><input id="' + name + '" type="' + (!isset(type) ? "text" : type) +
                '" name="' + name + '" class="form-control" value="' + (!isset(value) ? "" : value) + '"' + (isset(list) ? ' list="' + list + '"' : "") +
                ' /></div>');
        if (isset(required) && required) {
            $("#" + name).attr("required", "required");
        }
    };

    /**
     * Créé une datalist pour un input
     * @param {string} list Id de la liste
     * @param {array} data [{"label":"", "value":""},...]
     * @returns {undefined}
     */
    this.datalist = function (list, data) {
        str = '<datalist id="' + list + '">';
        $.each(data, function (k, v) {
            str += '<option label="' + v["label"] + '" value="' + v["value"] + '" />';
        });
        str += '</datalist>';
        this.append(str);
    };

    /**
     * Ajoute un input hidden
     * @param {type} name Name de l'input
     * @param {type} value Value de l'input
     * @returns {undefined}
     */
    this.hidden = function (name, value) {
        this.append('<div class="form-group"><input type="hidden" name="' + name + '" id="' + name + '" value="' + value + '" /></div>');
    };

    /**
     * Ajoute le bouton submit du formulaire
     * @param {string} class_css Class du bouton ( cf bootstrap )
     * @param {string} value Texte du bouton
     * @param {function} callback Fonction d'execution du formulaire
     * @returns {undefined}
     */
    this.submit = function (class_css, value) {
        this.append('<div class="form-group"><input type="submit" class="btn ' + class_css + '" value="' + (!isset(value) ? "Submit" : value) + '" /></div>');
    };

    /**
     * récupere les varibles du formulaire dans un objet appelable par $_post
     * @returns {undefined}
     */
    this.get_post = function () {
        $_post = "{";
        $("#" + this.id).find("select").each(function () {
            $_post += '"' + $(this).attr("name") + '":"' + addslashes($(this).val()) + '",';
        });
        $("#" + this.id).find("input").each(function () {
            if (isset($(this).attr("name"))) {
                if ($(this).attr("type") == "file") {
                    $_post += '"' + $(this).attr("name") + '":[';
                    $.each($(this)[0].files, function () {
                        if (isset(this.name)) {
                            $_post += '{"name":"' + this.name + '","type":"' + this.type + '","size":"' + this.size + '","data":"' + this.data + '"},';
                        }
                    });
                    $_post += "],";
                } else {
                    if (isset($(this).attr("data-picker"))) {
                        switch ($(this).attr("data-picker")) {
                            case "date":
                                date = explode("/", $(this).val());
                                $(this).val(date[2] + "-" + date[1] + "-" + date[0]);
                                break;
                            case "datetime":
                                date = explode(" ", $(this).val());
                                time = date[1];
                                date = explode("/", date[0]);
                                $(this).val(date[2] + "-" + date[1] + "-" + date[0] + " " + time);
                                break;
                        }
                    }
                    $_post += '"' + $(this).attr("name") + '":"' + addslashes($(this).val()) + '",';
                }
            }
        });
        $("#" + this.id).find("textarea").each(function () {
            $_post += '"' + $(this).attr("name") + '":"' + addslashes($(this).val()) + '",';
        });
        $_post = json_decode(strtr($_post + "}", {",}": "}", ",]": "]"}));

    };

    /**
     * Ajoute une checkbox
     * @param {string} label Label
     * @param {string} name Name
     * @param {string} value Value
     * @param {string} class_css Class CSS
     * @param {boolean} checked Case cochée par défaut ? true/false (false par defaut)
     * @returns {undefined}
     */
    this.checkbox = function (label, name, value, class_css, checked) {
        _id = name + '_' + value;
        this.append('<div class="form-group"><label for="' + _id + '"><input type="checkbox" name="' + name + '" id="' + _id + '" value="' + value +
                '" class="' + (!isset(class_css) ? "" : class_css) + '"' + (isset(checked) && checked ? ' checked="checked"' : "") +
                ' />' + label + '</label> </div>');
    };

    /**
     * Ajoute un groupe de boutons radio
     * @param {string} label Label
     * @param {string} name Name
     * @param {array} radios [[value,text,selected],...] (selected = true/false)
     * @returns {undefined}
     */
    this.radio = function (label, name, radios) {
        str = '<fieldset> <legend>' + label + '</legend>';
        $.each(radios, function (k, v) {
            str += '<div class="radio"><label><input type="radio" name="' + name + '" id="' + name + v[0] + '" value="' + v[0] + '"' +
                    (isset(v[2]) && v[2] == true ? ' checked="checked"' : "") + '>' + v[1] + '</label></div>';
        });
        str += ' </fieldset>';
        this.append(str);
    };

    /**
     * Ajoute un textarea
     * @param {string} label Label
     * @param {string} name Name
     * @param {string} value Value
     * @param {boolean} required Le champ est-il requis ? (true/false, false par defaut )
     * @param {string} class_css Class CSS
     * @returns {undefined}
     */
    this.textarea = function (label, name, value, required, class_css) {
        this.append('<div class="form-group"><label for="' + name + '">' + label + '</label><div><textarea name="' + name + '" id="' + name +
                '" cols="30" rows="10" class="form-control ' + (!isset(class_css) ? "" : class_css) + '" >' + (!isset(value) ? "" : value) +
                '</textarea></div></div>');
        if (isset(required) && required) {
            $("#" + name).attr("required", "required");
        }
    };


    /**
     * ajoute un select ( liste déroulante )
     * @param {string} label Label
     * @param {string} name Name
     * @param {array} option [[value,text,[selected]],...]; ou {"group":[[value,text,selected],...],...};
     * @param {boolean} required Le champ est-il requis ? (true/false, false par defaut )
     * @param {string} class_css Class CSS
     * @returns {undefined}
     */
    this.select = function (label, name, option, required, class_css) {
        this.append('<div class="form-group"><label for="' + name + '">' + label + '</label><select id="' + name + '" name="' + name + '" class="form-control ' +
                (!isset(class_css) ? "" : class_css) + '" >' + this.options(option) + '</select></div>');
        if (isset(required) && required) {
            $("#" + name).attr("required", "required");
        }
    };

    /**
     * retourne des "option"
     * @param {array} option [[value,text,[selected]],...]; ou {"group":[[value,text,selected],...],...};
     * @returns {undefined}
     */
    this.options = function (option) {
        str = '';
        options = this.options;
        $.each(option, function (k, v) {
            str += (!is_int(k) ? '<optgroup label="' + k + '">' + options(v) + '</optgroup>' :
                    '<option value="' + v[0] + '" ' + (isset(v[2]) && v[2] ? ' selected="selected"' : "") + '>' + v[1] + '</option>');
        });
        return str;
    };

    /**
     * Ouvre un fieldset avec une légende pour un formulaire ou une partie de formuaire, <br />
     * ce ferme avec close_fieldset(), les fieldset peuvent être imbriqués (attention aux fermetures !)
     * 
     * @param {string} legende Legende du fieldset
     * @returns {undefined}
     */
    this.new_fieldset = function (legende) {
        this.append('<fieldset> <legend>' + legende + '</legend>');
    };

    /**
     * Ferme un fieldset
     * 
     * @returns {undefined}
     */
    this.close_fieldset = function () {
        this.append('</fieldset>')
    };

    /**
     * Ajoute le datepicker de jquery-ui (date seulement)
     * @param {type} label Label
     * @param {type} name Name
     * @param {type} value Value
     * @param {type} required Le champ est-il requis ? (true/false, false par defaut )
     * @returns {undefined}
     */
    this.datepicker = function (label, name, value, required) {
        this.input(label, name, "text", (!isset(value) ? "" : value), (isset(required) && required));
        $("#" + name).datepicker($.datepicker.regional.fr);
        $("#" + name).datepicker({dateFormat: "dd/mm/yy"});
        $("#" + name).attr("readonly", true);
        $("#" + name).attr("placeholder", "Cliquez pour choisir une date");
        $("#" + name).attr("data-picker", "date");
    };



    /**
     * Ajoute le datepicker de jquery-ui (heur seulement)
     * @param {type} label Label
     * @param {type} name Name
     * @param {type} value Value
     * @param {type} required Le champ est-il requis ? (true/false, false par defaut )
     * @returns {undefined}
     */
    this.timeicker = function (label, name, value, required) {
        this.input(label, name, "text", (!isset(value) ? "" : value), (isset(required) && required));
        $("#" + name).timepicker($.datepicker.regional.fr);
        $("#" + name).timepicker({dateFormat: "dd/mm/yy"});
        $("#" + name).attr("readonly", true);
        $("#" + name).attr("placeholder", "Cliquez pour choisir une date");
        $("#" + name).attr("data-picker", "time");
    };

    /**
     * Ajoute le datepicker de jquery-ui (date + heur)
     * @param {type} label Label
     * @param {type} name Name
     * @param {type} value Value
     * @param {type} required Le champ est-il requis ? (true/false, false par defaut )
     * @returns {undefined}
     */
    this.datetimeicker = function (label, name, value, required) {
        this.input(label, name, "text", (!isset(value) ? "" : value), (isset(required) && required));
        $("#" + name).datetimepicker($.datepicker.regional.fr);
        $("#" + name).datetimepicker({dateFormat: "dd/mm/yy"});
        $("#" + name).attr("readonly", true);
        $("#" + name).attr("placeholder", "Cliquez pour choisir une date");
        $("#" + name).attr("data-picker", "datetime");
    };

    /**
     * Affiche un input de type file 
     * @param {string} label Label
     * @param {string} name Name
     * @param {boolean} required Le champ est-il requis ? (true/false, false par defaut )
     * @param {boolean} multiple Upload de fichiers multiples ? true/false (false par défaut)
     * @returns {undefined}
     */
    this.file = function (label, name, required, multiple) {
        $("#" + this.id).attr("enctype", "multipart/form-data");
        id = name;
        this.input(label, name, "file", "", (isset(required) && required), "");
        if (isset(multiple) && multiple) {
            $("#" + id).attr("multiple", "true");
        }
        $("#" + id).removeAttr("class");
        $("#" + id).change(function () {
            $("#" + id).parent("form").children("input[type='submit']").attr("disabled", "disabled");
            reader = [];
            $.each($("#" + id)[0].files, function (k, v) {
                reader[this.name] = new FileReader();
                reader[this.name].onloadend = function () {
                    $("#" + id).parent("form").children("input[type='submit']").attr("disabled", "disabled");
                    $("#" + id)[0].files[k].data = this.result;
                    $("#" + id).parent("form").children("input[type='submit']").removeAttr("disabled");
                };
                reader[this.name].readAsDataURL($("#" + id)[0].files[k]);
            });
            $("#" + id).parent("form").children("input[type='submit']").removeAttr("disabled");
        });
    };
}