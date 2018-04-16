<?php

/*
 * Origin Source : http://beta.jdownloader.org/developmentdlc
 *                _  _____                  _
 *               | |/ ____|                | |
 *     _   _ _ __| | |     _ __ _   _ _ __ | |_    ___  ___  _ __ ___
 *    | | | | '__| | |    | '__| | | | '_ \| __|  / __|/ _ \| '_ ` _ \
 *    | |_| | |  | | |____| |  | |_| | |_) | |_ _| (__| (_) | | | | | |
 *     \__,_|_|  |_|\_____|_|   \__, | .__/ \__(_)\___|\___/|_| |_| |_|
 *                               __/ | |
 *                              |___/|_|
 *
 *                           WWW.URLCRYPT.COM
 *
 *
 *    This API class was created by Frank Burian,
 *    for the project www.urlCrypt.com (a link protection service) and
 *    for the download manager jDownloder (http://jdownloader.org)
 * =======================================================================================================      
 *   © Copyright 2009 by JDTeam(jdownloader.org) & AppWork UG    
 * =======================================================================================================
 *    Support: urlcrypt@nquee.com or visit http://forum.nquee.com
 *
 *    Version: 1.0.1
 *    Date: 21 Mar 2009
 * _______________________________________________________________________________________________________
 * 
 * Cette classe a été reprise et optimisé par LEGAGNEUR Matthieu
 */

/**
 * Créer un fichier DLC (Download Link Container)
 * usage : dlc::generate_DLC();
 *
 * @author JDTeam (jdownloader.org)
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class dlc {

    // >>>>>>>>> PLEASE EDIT - START !!!
    // The given ID by the JD-Dev-Team
    const dlc_content_generator_id = 'JDOWNLOADER.ORG';
    // Name of your project
    const dlc_content_generator_name = 'JDownloader.org';
    // URL of your Project
    const dlc_content_generator_url = 'http://www.jdownloader.org';
    // Cache file for save keys - you need a absolute path and a file permission 777
    const dlc_cache_keys_filename = 'dlcapicache.txt';
    // >>>>>>>>> PLEASE EDIT - END !!!
    // DO NOT EDIT!!!

    const dlc_api_version = '1.0';
    const dlc_key_pair_expires_after = 3600;
    const dlccrypt_mainservices = 'http://service.jdownloader.org/dlcrypt/service.php';
    const dlccrypt_services_mirror_1 = false;
    const dlccrypt_services_mirror_2 = false;
    const dlccrypt_services_mirror_3 = false;
    const dlccrypt_services_mirror_4 = false;
    const ccf_key_10 = '5F679C00548737E120E6518A981BD0BA11AF5C719E97502983AD6AA38ED721C3';
    const ccf_key_08 = '171BF8E34C3D0C0C2693FDD2B080423A5B98F4D028A0AF4D82A385D837A8F95F';
    const ccf_key_07 = '026900E977C6402442B661329CFE62D6ED21BDEB0CD6321318A8EDC7BC5A6C86';
    const ccf_iv_10 = 'E3D153AD609EF7358D66684180C7331A';
    const ccf_iv_08 = '9FE95FFF7CA4FC0FCEF25E4F7444AE67';
    const ccf_iv_07 = '8CE1173EBAD76E08584B94573926231E';
    const ccf_id_10 = '1.0';
    const ccf_id_08 = '0.8';
    const ccf_id_07 = '0.7';
    const rsdf_key = '8C35192D964DC3182C6F84F3252239EB4A320D2500000000';
    const rsdf_iv = 'a3d5a33cb95ac1f5cbdb1ad25cb0a7aa';

    // DO NOT EDIT!!!

    protected $intCountErrors = 0;
    protected $arrErrorMessages = [];
    protected $arrModel = [];
    protected $intPackageId = NULL;

    // Constructor
    /*
     * Créer un fichier DLC (Download Link Container)
     * usage : dlc::generate_DLC();
     */
    function __construct() {
        $this->resetDataModel();
    }

    /**
     * Créer un fichier DLC (Download Link Container)
     * @param string $filename Chemain et nom pour le fichier .dlc a générer
     * @param array $data Liste d'url
     * @param string $packagename Nom du package
     */
    public static function generate_DLC($filename, $data, $packagename = "package") {
        $dlc = new dlc();
        $package = $dlc->addFilePackage($dm = $dlc->createDataModel(), $packagename);
        foreach ($data as $url) {
            $dlc->addLink($dm, $package, $url);
        }
        file_put_contents($filename, $dlc->createDLC($dm));
        unlink(dlc::dlc_cache_keys_filename);
    }

    /**
     * Créer un fichier CCF (CryptLoad Container File)
     * @param string $filename Chemain et nom pour le fichier .ccf a générer
     * @param array $data Liste d'url
     * @param string $packagename Nom du package
     */
    public static function generate_CCF($filename, $data, $packagename = "package") {
        $dlc = new dlc();
        $package = $dlc->addFilePackage($dm = $dlc->createDataModel(), $packagename);
        foreach ($data as $url) {
            $dlc->addLink($dm, $package, $url);
        }
        file_put_contents($filename, $dlc->createCCF($dm));
    }

    /**
     * Créer un fichier RSDF (RapidShare Download File)
     * @param string $filename Chemain et nom pour le fichier .rsdf a générer
     * @param array $data Liste d'url
     * @param string $packagename Nom du package
     */
    public static function generate_RSDF($filename, $data, $packagename = "package") {
        $dlc = new dlc();
        $package = $dlc->addFilePackage($dm = $dlc->createDataModel(), $packagename);
        foreach ($data as $url) {
            $dlc->addLink($dm, $package, $url);
        }
        file_put_contents($filename, $dlc->createRSDF($dm));
    }

    // Add error
    protected function addError($strMessage) {
        $this->intCountErrors++;
        $this->arrErrorMessages[] = strip_tags($strMessage);
        return false;
    }

    // Check if errors exists
    public function isError() {
        return ($this->intCountErrors) ? true : false;
    }

    // Show errors
    public function showError() {
        return html_structures::ul($this->arrErrorMessages);
    }

    public function getDataModel() {
        return $this->arrModel;
    }

    // Reset a data model
    public function resetDataModel() {
        $this->arrModel = [];
        $this->intPackageId = NULL;
    }

    // Add a new data model
    public function createDataModel($strUploaderName = 'unknown') {
        $this->arrModel[$intNewModelId = count($this->arrModel)] = ["uploader" => $strUploaderName, "packages" => []];
        return $intNewModelId;
    }

    // Add a file package to a data model
    public function addFilePackage($intModelId, $strName = 'package', $strPasswords = [], $strComment = 'no comment', $strCategory = 'various') {
        if (!isset($this->arrModel[$intModelId])) {
            return $this->addError('(addFilePackage) Data model with Id ' . $intModelId . ' not exists');
        }
        foreach ([",", ";"] as $delimiter) {
            $strPasswords = (!is_array($strPasswords) and strpos($strPasswords, $delimiter) ? explode($delimiter, trim($strPasswords)) : $strPasswords);
            if (is_array($strPasswords)) {
                foreach ($strPasswords as $key => $value) {
                    $strPasswords[$key] = trim($value);
                }
                break;
            }
        }
        $this->arrModel[$intModelId]['packages'][$intNewPackageId = count($this->arrModel[$intModelId]['packages'])] = [
            "name" => trim($strName), "passwords" => $strPasswords, "comment" => trim($strComment), "category" => trim($strCategory), "links" => []
        ];
        return $intNewPackageId;
    }

    // Add a link to a file package
    public function addLink($intModelId, $intPackageId, $strUrl, $strFilename = '', $intFilesize = 0) {
        if (!isset($this->arrModel[$intModelId]['packages'][$intPackageId]['links'])) {
            return $this->addError('(addLink) Package with Id ' . $intPackageId . ' not exists');
        }
        array_push($this->arrModel[$intModelId]['packages'][$intPackageId]['links'], ["url" => trim($strUrl), "filename" => trim($strFilename), "size" => trim($intFilesize)]);
        return true;
    }

    /**
     * DLC
     */
    // Create a DLC stream
    public function createDLC($intModelId, $strApplication = NULL, $strUrl = NULL, $strVersion = NULL) {
        if (!isset($this->arrModel[$intModelId])) {
            return $this->addError('(createDLC) Data model with Id ' . $intModelId . ' not exists');
        }
        # Create XML
        $strXML = '<dlc>'
                . '<header>'
                . '<generator>'
                . '<app>' . $this->dlcDataEncode((!trim($strApplication) ? self::dlc_content_generator_name : NULL)) . '</app>'
                . '<version>' . $this->dlcDataEncode((!trim($strVersion) ? self::dlc_api_version : NULL)) . '</version>'
                . '<url>' . $this->dlcDataEncode((!trim($strUrl) ? self::dlc_content_generator_url : NULL)) . '</url>'
                . '</generator>'
                . '<tribute>'
                . '<name>' . $this->dlcDataEncode($this->arrModel[$intModelId]['uploader']) . '</name>'
                . '</tribute>'
                . '<dlcxmlversion>' . $this->dlcDataEncode('20_02_2008') . '</dlcxmlversion>'
                . '</header>'
                . '<content>';
        foreach ($this->arrModel[$intModelId]['packages'] as $a => $package) {
            $strXML .= '<package name="' . $this->dlcDataEncode($package['name']) . '"';
            $passwords = $package['passwords'];
            if (is_array($passwords)) {
                foreach ($passwords as $b => $password) {
                    $passwords[$b] = '"' . $password . '"';
                }
                $passwords = '{' . implode(', ', $passwords) . '}';
            }
            $strXML .= ($passwords ? ' passwords="' . $this->dlcDataEncode($passwords) . '"' : "");
            foreach (['comment', 'category'] as $key) {
                $strXML .= ($package[$key] ? ' ' . $key . '="' . $this->dlcDataEncode($package[$key]) . '"' : "");
            }
            $strXML .= '>';
            foreach ($package['links'] as $b => $link) {
                $strXML .= '<file>'
                        . '<url>' . $this->dlcDataEncode($link['url']) . '</url>'
                        . '<filename>' . $this->dlcDataEncode($link['filename']) . '</filename>'
                        . '<size>' . $this->dlcDataEncode($link['size']) . '</size>'
                        . '</file>';
            }
            $strXML .= '</package>';
        }
        $strXML .= '</content>'
                . '</dlc>';
        # Encoding XML
        $strXML = base64_encode($strXML);
        # Building keys
        $boolResult = $this->getDLCCacheKeys($strKey, $strEncryptKey, $intUpdateTime);
        if (($boolResult == false) || ($intUpdateTime < (time() - self::dlc_key_pair_expires_after))) {
            $strKey = substr(md5(md5(time() . rand(0, 10000)) . rand(0, 10000)), 0, 16);
            $arrServices = [];
            (self::dlccrypt_services_mirror_1 ? array_push($arrServices, self::dlccrypt_services_mirror_1) : false);
            (self::dlccrypt_services_mirror_2 ? array_push($arrServices, self::dlccrypt_services_mirror_2) : false);
            (self::dlccrypt_services_mirror_3 ? array_push($arrServices, self::dlccrypt_services_mirror_3) : false);
            (self::dlccrypt_services_mirror_4 ? array_push($arrServices, self::dlccrypt_services_mirror_4) : false);
            shuffle($arrServices);
            $strEncryptKey = (strlen(self::dlccrypt_mainservices) > 10 ? $this->callDLCService(self::dlccrypt_mainservices, $strKey) : NULL);
            if (!$strEncryptKey and $arrServices) {
                foreach ($arrServices as $strService) {
                    $strEncryptKey = $this->callDLCService($strService, $strKey);
                }
            }
            if (!$strEncryptKey) {
                return $this->addError('(createDLC) Could not encrypt key');
            }
            if (!$this->setDLCCacheKeys($strKey, $strEncryptKey)) {
                return $this->addError('(createDLC) Could not save cache file for keys');
            }
        }
        if (!$strKey || !$strEncryptKey) {
            return $this->addError('(createDLC) Keys are empty');
        }

        # Build DLC Stream
        @mcrypt_generic_init($hdlDLCCrypt = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', ''), $strKey, $strKey);
        $strStream = mcrypt_generic($hdlDLCCrypt, $strXML);
        mcrypt_generic_deinit($hdlDLCCrypt);
        mcrypt_module_close($hdlDLCCrypt);
        unset($hdlDLCCrypt);
        /*
          // Decrypt
          $hdlDLCCrypt = mcrypt_module_open(MCRYPT_RIJNDAEL_128,'','cbc','');
          @mcrypt_generic_init($hdlDLCCrypt,$strKey,$strKey);
          $strOrgStream = mdecrypt_generic($hdlDLCCrypt, base64_decode($strStream));
          mcrypt_generic_deinit($hdlDLCCrypt);
          mcrypt_module_close($hdlDLCCrypt);
          echo '<hr>'.nl2br(htmlentities(base64_decode($strOrgStream))).'</hr>';
         */
        return base64_encode($strStream) . $strEncryptKey;
    }

    protected function setDLCCacheKeys($strPlainKey, $strEncryptKey) {
        if (!file_exists(self::dlc_cache_keys_filename) || is_writable(self::dlc_cache_keys_filename)) {
            if (!$hdlFile = fopen(self::dlc_cache_keys_filename, "w+")) {
                return $this->addError('(setDLCKey2Cache) Can not open file ' . self::dlc_cache_keys_filename);
            }
            $strCacheContent = $strPlainKey . chr(13) . $strEncryptKey . chr(13) . time();
            if (!fwrite($hdlFile, $strCacheContent)) {
                return $this->addError('(setDLCKey2Cache) Can not write in file ' . self::dlc_cache_keys_filename);
            }
            fclose($hdlFile);
        } else {
            return $this->addError('(setDLCKey2Cache) file ' . self::dlc_cache_keys_filename . ' not writeable');
        }
        return true;
    }

    protected function getDLCCacheKeys(&$strPlainKey = null, &$strEncryptKey = null, &$intUpdateTime = 0) {
        if (is_readable(self::dlc_cache_keys_filename)) {
            $strCacheContent = trim(file_get_contents(self::dlc_cache_keys_filename));
            if (!$strCacheContent) {
                return false;
            }
            $arrCacheContent = explode(chr(13), $strCacheContent);
            if ((!$arrCacheContent) || (count($arrCacheContent) <> 3)) {
                return false;
            }
            $strPlainKey = $arrCacheContent[0];
            $strEncryptKey = $arrCacheContent[1];
            $intUpdateTime = $arrCacheContent[2];
        } else {
            return $this->addError('(getDLCKey2Cache) Can not read file ' . self::dlc_cache_keys_filename);
        }
        return true;
    }

    protected function dlcDataEncode($strValue) {
        return base64_encode(trim(($strValue == NULL ? 'n.A.' : $strValue)));
    }

    protected function callDLCService($strService, $strKey) {
        $arrUrl = parse_url($strService);
        $hdlSock = @fsockopen($arrUrl["host"], 80);
        if (!$hdlSock)
            return $strService;
        fputs($hdlSock, "GET " . $arrUrl["path"] . " HTTP/1.1\r\n");
        fputs($hdlSock, "Host: " . $arrUrl["host"] . "\r\n");
        fputs($hdlSock, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($hdlSock, "Connection: close\r\n\r\n");
        $strResult = '';
        while (!feof($hdlSock)) {
            $strResult .= fgets($hdlSock, 1024);
            if (strpos($strResult, "Location:") > 0) {
                $arrTmp = explode("Location:", $strResult);
                $strService = trim($arrTmp[1]);
                break;
            }
        }
        fclose($hdlSock);
        if (!$strService) {
            return $this->addError('(callDLCService) Could not resolve ' . $strService);
        }
        $strResult = $this->postRequest($strService, "&data=" . $strKey . "&lid=" . base64_encode(self::dlc_content_generator_id . "_" . self::dlccrypt_mainservices . "_" . self::dlc_key_pair_expires_after));
        if (empty($strResult)) {
            return $this->addError('(callDLCService) Could not get Key from ' . $strService);
        }
        if (!strpos($strResult, '</rc>')) {
            return $this->addError('(callDLCService) Service not available ' . $strService);
        }
        $strKey = explode('<rc>', $strResult, 2);
        $strKey = @substr($strKey[1], 0, @strpos($strKey[1], '</rc>'));
        if (empty($strKey) || strlen($strKey) != 88) {
            return $this->addError('(callDLCService) OLD CLIENT OR SERVER VERSION');
        }
        return $strKey;
    }

    // Send a post request
    protected function postRequest($strUrl, $strData, $arrHeader = []) {
        $arrUrl = parse_url($strUrl);
        $hdlSock = @fsockopen($arrUrl["host"], 80);
        if (!$hdlSock)
            return NULL;
        fputs($hdlSock, "POST " . $arrUrl["path"] . (isset($arrUrl["query"]) ? "?" . $arrUrl["query"] : "") . " HTTP/1.1");
        fputs($hdlSock, "\r\n");
        fputs($hdlSock, "Host: " . $arrUrl["host"]);
        fputs($hdlSock, "\r\n");
        if ($arrHeader) {
            foreach ($arrHeader as $strKey => $strValue) {
                fputs($hdlSock, $strKey . ": " . $strValue);
                fputs($hdlSock, "\r\n");
            }
        }
        fputs($hdlSock, "Content-type: application/x-www-form-urlencoded");
        fputs($hdlSock, "\r\n");
        fputs($hdlSock, "Content-length: " . strlen($strData));
        fputs($hdlSock, "\r\n");
        if ($arrHeader && isset($arrHeader["Keep-Alive"])) {
            fputs($hdlSock, "Connection: keep-alive");
            fputs($hdlSock, "\r\n");
        } else {
            fputs($hdlSock, "Connection: close");
            fputs($hdlSock, "\r\n");
        }
        fputs($hdlSock, "\r\n");
        fputs($hdlSock, $strData);
        $strResult = '';
        while (!feof($hdlSock)) {
            $strResult .= fgets($hdlSock, 128);
        }
        fclose($hdlSock);
        return $strResult;
    }

    /**
     * CCF
     */
    // Create a CCF stream
    public function createCCF($intModelId) {
        if (!self::ccf_key_10) {
            return addError("(createCCF) CCF Keyfile is not defined");
        }
        if (!isset($this->arrModel[$intModelId])) {
            return $this->addError('(createDLC) Data model with Id ' . $intModelId . ' not exists');
        }
        # Create XML
        $strXML = '<?xml version="1.0" encoding="utf-8"?>';
        $strXML .= '<CryptLoad>';
        for ($a = 0; $a < count($this->arrModel[$intModelId]['packages']); $a++) {
            $strXML .= '<Package service="" name="' . $this->ccfDataEncode($this->arrModel[$intModelId]['packages'][$a]['name']) . '" url="Directlinks">';
            $strXML .= '<Options>';
            $strTmp = $this->arrModel[$intModelId]['packages'][$a]['comment'];
            if (!trim($strTmp))
                $strTmp = 'create by DLCAPI';
            $strXML .= ($strTmp) ? '<Kommentar>' . $this->ccfDataEncode($strTmp) . '</Kommentar>' : '<Kommentar />';
            $strTmp = $this->arrModel[$intModelId]['packages'][$a]['passwords'];
            if (is_array($strTmp)) {
                $strTmp = implode(',', $strTmp);
            }
            $strXML .= ($strTmp) ? '<Passwort>' . $this->ccfDataEncode($strTmp) . '</Passwort>' : '<Passwort />';
            $strXML .= '</Options>';
            for ($b = 0; $b < count($this->arrModel[$intModelId]['packages'][$a]['links']); $b++) {
                $strXML .= '<Download Url="' . $this->ccfDataEncode($this->arrModel[$intModelId]['packages'][$a]['links'][$b]['url']) . '">';
                $strTmp = $this->arrModel[$intModelId]['packages'][$a]['links'][$b]['size'];
                $strXML .= '<FileSize>' . $this->ccfDataEncode(($strTmp ? $strTmp : 0)) . '</FileSize>';
                $strXML .= '<Url>' . $this->ccfDataEncode($this->arrModel[$intModelId]['packages'][$a]['links'][$b]['url']) . '</Url>';
                $strTmp = $this->arrModel[$intModelId]['packages'][$a]['links'][$b]['filename'];
                $strXML .= ($strTmp) ? '<FileName>' . $this->ccfDataEncode($strTmp) . '</FileName>' : '<FileName/>';
                $strXML .= '</Download>';
            }
            $strXML .= '</Package>';
        }
        $strXML .= '</CryptLoad>';

        # Build CCF stream
        $strXML = utf8_encode($strXML);
        $arrKeyList = array(self::ccf_key_10, self::ccf_key_08, self::ccf_key_07);
        $arrIVList = array(self::ccf_iv_10, self::ccf_iv_08, self::ccf_iv_07);
        $hdlCCFCrypt = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($hdlCCFCrypt, $this->base16Decode($arrKeyList[0]), $this->base16Decode($arrIVList[0]));
        $strStream = mcrypt_generic($hdlCCFCrypt, $strXML);
        mcrypt_generic_deinit($hdlCCFCrypt);
        mcrypt_module_close($hdlCCFCrypt);
        unset($hdlCCFCrypt);
        return $strStream;
    }

    public function ccfDataEncode($strValue) {
        return utf8_encode($strValue);
    }

    public function decryptCCF($strStream) {
        $arrKeyList = array(self::ccf_key_10, self::ccf_key_08, self::ccf_key_07);
        $arrIVList = array(self::ccf_iv_10, self::ccf_iv_08, self::ccf_iv_07);
        $arrVList = array(self::ccf_id_10, self::ccf_id_08, self::ccf_id_07);
        $a = 0;
        $strXML = '';
        while ($a < count($arrKeyList)) {
            $hdlCCFDecrypt = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            @mcrypt_generic_init($hdlCCFDecrypt, $this->base16Decode($arrKeyList[$a]), $this->base16Decode($arrIVList[$a]));
            $strOrgStream = $this->filterString(mdecrypt_generic($hdlCCFDecrypt, $strStream));
            mcrypt_generic_deinit($hdlCCFDecrypt);
            mcrypt_module_close($hdlCCFDecrypt);
            if (strpos(strtolower($strOrgStream), "cryptload") > 0) {
                $strXML = trim($strOrgStream);
                break;
            }
            $a++;
        }
        unset($hdlCCFDecrypt);
        return $strXML;
    }

    /**
     * RSDF
     */
    // Create a RSDF stream
    function createRSDF($intModelId) {
        if (!self::rsdf_key || !self::rsdf_iv) {
            return addError('(createRSDF) RSDF Keyfile is not defined');
        }
        if (!isset($this->arrModel[$intModelId])) {
            return $this->addError('(createRSDF) Data model with Id ' . $intModelId . ' not exists');
        }
        $strReturn = '';
        # Build RSDF stream
        $hdlRSDFCrypt;
        mcrypt_generic_init($hdlRSDFCrypt = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CFB, ''), $this->base16Decode(self::rsdf_key), $this->base16Decode(self::rsdf_iv));
        foreach ($this->arrModel[$intModelId]['packages'] as $package) {
            foreach ($package['links'] as $link) {
                $strReturn .= base64_encode(mcrypt_generic($hdlRSDFCrypt, $link['url'])) . "\r\n";
            }
        }
        mcrypt_generic_deinit($hdlRSDFCrypt);
        mcrypt_module_close($hdlRSDFCrypt);
        unset($hdlRSDFCrypt);
        return $this->base16Encode($strReturn);
    }

    // Decoding string as Base 16
    protected function base16Decode($strValue) {
        $strReturn = '';
        for ($a = 0; $a < strlen($strValue); $a += 2) {
            $strTmp = substr($strValue, $a, 2);
            $int = hexdec($strTmp);
            $strReturn .= chr($int);
        }
        return $strReturn;
    }

    // Encoding string as Base 16
    protected function base16Encode($strValue) {
        $strReturn = '';
        for ($a = 0; $a < strlen($strValue); $a++) {
            $strTmp = ord($strValue[$a]);
            $strHex = dechex($strTmp);
            while (strlen($strHex) < 2) {
                $strHex = "0" . $strHex;
            }
            $strReturn .= $strHex;
        }
        return $strReturn;
    }

    // Filter a string
    protected function filterString($strValue) {
        $strResult = '';
        $strAllowed = 'QWERTZUIOPÜASDFGHJKLÖÄYXCVBNMqwertzuiopasdfghjklyxcvbnm;:,._-&%(){}#~+ 1234567890<>=\'"/';
        $chrChar = '';
        for ($a = 0; $a < strlen($strValue); $a++) {
            if (!(strpos($strAllowed, ($chrChar = substr($strValue, $a, 1))) === false)) {
                $strResult .= $chrChar;
            }
        }
        return $strResult;
    }

}
