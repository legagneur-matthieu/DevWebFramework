/**
 * Cette fonction permet de formater l'affichage d'un nombre dans un INPUT de type text
 * @param {string} id ID CSS de l'input
 * @param {boolean} integer False si l'input doit tolérer les type float
 * @param {string} thousands Caractère d'espacement entre les milliers
 * @param {string} decimal Cacactère pour les décimales
 */
function maskNumber(id, integer = false, thousands = " ", decimal = ".") {
    let from = {};
    from[thousands] = "";
    from[decimal] = ".";
    $("#" + id).val(number_format(parseFloat(strtr($("#" + id).val(), from)), (integer ? 0 : 2), decimal, thousands))
            .unbind("change")
            .change(function () {
                maskNumber(id, integer, thousands, decimal);
            });
}
