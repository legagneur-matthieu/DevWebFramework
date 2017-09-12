<?php

/**
 * Cette classe contient les résultats d'une requête à duckduckgo <br />
 * Il est recommandé de passer par (new ddg())->api() ou ddg_api::query() pour récuperer une instance de cette classe
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class ddg_api {

    /**
     * Varibles retournées par l'API de DuckDuckGo
     * @var bool Varibles retournées par l'API de DuckDuckGo
     */
    private $_Abstract = false,
            $_AbstractText = false,
            $_AbstractSource = false,
            $_AbstractURL = false,
            $_Image = false,
            $_ImageWidth = false,
            $_Entity = false,
            $_meta = false,
            $_Heading = false,
            $_Answer = false,
            $_Redirect = false,
            $_AnswerType = false,
            $_Definition = false,
            $_DefinitionSource = false,
            $_DefinitionURL = false,
            $_RelatedTopics = false,
            $_Results = false,
            $_Type = false,
            $_Infobox = false,
            $_ImageIsLogo = false,
            $_ImageHeight = false;

    /**
     * Retourne un objet ddg_api contenant les résultats de la requête
     * @param string $query
     * @return ddg_api obget ddg_api
     */
    public static function query($query) {
        return new ddg_api((array) json_decode(service::HTTP_GET("https://api.duckduckgo.com/?q=" . $query . "&format=json")));
    }

    /**
     * Cette classe contient les résultats d'une requête à duckduckgo <br />
     * il est recommandé de passer par (new ddg())->api() ou ddg_api::query() pour récuperer une instance de cette classe
     * @param array $data données de retour de l'API de duckduckgo
     */
    public function __construct($data) {
        foreach ($data as $key => $value) {
            $set = "set_" . $key;
            $this->$set($value);
        }
    }

    /**
     * 
     * @return type
     */
    public function get_Abstract() {
        return $this->_Abstract;
    }

    /**
     * 
     * @return type
     */
    public function get_AbstractText() {
        return $this->_AbstractText;
    }

    /**
     * 
     * @return type
     */
    public function get_AbstractSource() {
        return $this->_AbstractSource;
    }

    /**
     * 
     * @return type
     */
    public function get_AbstractURL() {
        return $this->_AbstractURL;
    }

    /**
     * 
     * @return type
     */
    public function get_Image() {
        return $this->_Image;
    }

    /**
     * 
     * @return type
     */
    public function get_ImageWidth() {
        return $this->_ImageWidth;
    }

    /**
     * 
     * @return type
     */
    public function get_Entity() {
        return $this->_Entity;
    }

    /**
     * 
     * @return type
     */
    public function get_meta() {
        return $this->_meta;
    }

    /**
     * 
     * @return type
     */
    public function get_Heading() {
        return $this->_Heading;
    }

    /**
     * 
     * @return type
     */
    public function get_Answer() {
        return $this->_Answer;
    }

    /**
     * 
     * @return type
     */
    public function get_Redirect() {
        return $this->_Redirect;
    }

    /**
     * 
     * @return type
     */
    public function get_AnswerType() {
        return $this->_AnswerType;
    }

    /**
     * 
     * @return type
     */
    public function get_Definition() {
        return $this->_Definition;
    }

    /**
     * 
     * @return type
     */
    public function get_DefinitionSource() {
        return $this->_DefinitionSource;
    }

    /**
     * 
     * @return type
     */
    public function get_DefinitionURL() {
        return $this->_DefinitionURL;
    }

    /**
     * 
     * @return type
     */
    public function get_RelatedTopics() {
        return $this->_RelatedTopics;
    }

    /**
     * 
     * @return type
     */
    public function get_Results() {
        return $this->_Results;
    }

    /**
     * 
     * @return type
     */
    public function get_Type() {
        return $this->_Type;
    }

    /**
     * 
     * @return type
     */
    public function get_Infobox() {
        return $this->_Infobox;
    }

    /**
     * 
     * @return type
     */
    public function get_ImageIsLogo() {
        return $this->_ImageIsLogo;
    }

    /**
     * 
     * @return type
     */
    public function get_ImageHeight() {
        return $this->_ImageHeight;
    }

    /**
     * 
     * @param type $Abstract
     */
    public function set_Abstract($Abstract) {
        $this->_Abstract = $Abstract;
    }

    /**
     * 
     * @param type $AbstractText
     */
    public function set_AbstractText($AbstractText) {
        $this->_AbstractText = $AbstractText;
    }

    /**
     * 
     * @param type $AbstractSource
     */
    public function set_AbstractSource($AbstractSource) {
        $this->_AbstractSource = $AbstractSource;
    }

    /**
     * 
     * @param type $AbstractURL
     */
    public function set_AbstractURL($AbstractURL) {
        $this->_AbstractURL = $AbstractURL;
    }

    /**
     * 
     * @param type $Image
     */
    public function set_Image($Image) {
        $this->_Image = $Image;
    }

    /**
     * 
     * @param type $ImageWidth
     */
    public function set_ImageWidth($ImageWidth) {
        $this->_ImageWidth = $ImageWidth;
    }

    /**
     * 
     * @param type $Entity
     */
    public function set_Entity($Entity) {
        $this->_Entity = $Entity;
    }

    /**
     * 
     * @param type $meta
     */
    public function set_meta($meta) {
        $this->_meta = $meta;
    }

    /**
     * 
     * @param type $Heading
     */
    public function set_Heading($Heading) {
        $this->_Heading = $Heading;
    }

    /**
     * 
     * @param type $Answer
     */
    public function set_Answer($Answer) {
        $this->_Answer = $Answer;
    }

    /**
     * 
     * @param type $Redirect
     */
    public function set_Redirect($Redirect) {
        $this->_Redirect = $Redirect;
    }

    /**
     * 
     * @param type $AnswerType
     */
    public function set_AnswerType($AnswerType) {
        $this->_AnswerType = $AnswerType;
    }

    /**
     * 
     * @param type $Definition
     */
    public function set_Definition($Definition) {
        $this->_Definition = $Definition;
    }

    /**
     * 
     * @param type $DefinitionSource
     */
    public function set_DefinitionSource($DefinitionSource) {
        $this->_DefinitionSource = $DefinitionSource;
    }

    /**
     * 
     * @param type $DefinitionURL
     */
    public function set_DefinitionURL($DefinitionURL) {
        $this->_DefinitionURL = $DefinitionURL;
    }

    /**
     * 
     * @param type $RelatedTopics
     */
    public function set_RelatedTopics($RelatedTopics) {
        $this->_RelatedTopics = $RelatedTopics;
    }

    /**
     * 
     * @param type $Results
     */
    public function set_Results($Results) {
        $this->_Results = $Results;
    }

    /**
     * 
     * @param type $Type
     */
    public function set_Type($Type) {
        $this->_Type = $Type;
    }

    /**
     * 
     * @param type $Infobox
     */
    public function set_Infobox($Infobox) {
        $this->_Infobox = $Infobox;
    }

    /**
     * 
     * @param type $ImageIsLogo
     */
    public function set_ImageIsLogo($ImageIsLogo) {
        $this->_ImageIsLogo = $ImageIsLogo;
    }

    /**
     * 
     * @param type $ImageHeight
     */
    public function set_ImageHeight($ImageHeight) {
        $this->_ImageHeight = $ImageHeight;
    }

}
