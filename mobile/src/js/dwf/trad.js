/**
 * Objet de traduction
 * @param {string} lang Langue de traduction
 * @returns {dwf_trad}
 */
function dwf_trad(lang) {

    lang = json_decode(file_get_contents("src/js/dwf/lang/" + lang + ".json"));

    /**
     * Retourne la traduction lié a la clé passé en patrametre 
     * @param {string} k Clé de traduction
     * @returns {string} Traduction
     */
    this.trad = function (k) {
        return lang[k];
    };
}