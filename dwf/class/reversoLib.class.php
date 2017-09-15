<?php

/**
 * Cette classe utilise l'API de Reverso pour corriger un texte et 
 * vous affiche les corrections à appliquer au texte grace à la librairie finediff <br />
 * Auteur original : Dyrk<br />
 * Modifié par LEGAGNEUR Matthieu
 * @url https://dyrk.org/2015/12/11/php-une-api-pour-la-correction-de-vos-fautes-dorthographe/
 * @author Dyrk <https://dyrk.org>
 */
class reversoLib {

    private $_langue = "fra";
    private $_url = "http://orthographe.reverso.net/RISpellerWS/RestSpeller.svc/v1/CheckSpellingAsXml/language={langue}?outputFormat=json&doReplacements=true&interfLang={langue}&dictionary=both&spellOrigin=interactive&includeSpellCheckUnits=true&includeExtraInfo=true&isStandaloneSpeller=true";

    /**
     * Langue de la correction ("fra" par defaut)
     * @param string $lg Langue de la correction ("fra" par defaut)
     */
    public function set_langue($lg) {
        $this->$_langue = $lg;
    }

    /**
     * Utilise l'API de Reverso pour corriger un texte et 
     * vous affiche les corrections a appliquer au texte grace a la librairie finediff
     * @param string $txt Texte à corriger
     * @return type Texte avec les correction a appliquer
     */
    public function correctionText($txt) {
        curl_setopt($ch = curl_init(), CURLOPT_HTTPHEADER, array(
            'User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36',
            'Origin:http://www.reverso.net',
            'Referer:http://www.reverso.net/orthographe/correcteur-francais/',
            'Host:orthographe.reverso.net',
            'Accept-Encoding:gzip, deflate, sdch',
            'Accept-Language:fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4',
            'Access-Control-Request-Headers:accept, content-type, created, username, x-requested-with',
            'Access-Control-Request-Method:POST',
            'Accept:*/*',
            'Created: 01/01/0001 00:00:00',
            'Username: OnlineSpellerWS'
        ));
        curl_setopt($ch, CURLOPT_URL, str_replace('{langue}', $this->_langue, $this->_url));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $txt);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = json_decode(curl_exec($ch), true);
        $datas = curl_getinfo($ch);
        curl_close($ch);
        return php_finediff::DiffToHTML($result["OriginalText"], $result["AutoCorrectedText"]);
    }

}
