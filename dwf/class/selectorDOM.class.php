<?php

/**
 * https://github.com/tj/php-selector v1.1.6
 * edit LEGAGNEUR Matthieu
 * 
 * USE : selectorDOM::select_elements() !
 * 
 * SelectorDOM.
 *
 * Persitant object for selecting elements.
 *
 *   $dom = new SelectorDOM($html);
 *   $links = $dom->select('a');
 *   $list_links = $dom->select('ul li a');
 *
 */
class selectorDOM {

    /**
     * Instance of DOMXpath
     * @var DOMXpath Instance of DOMXpath
     */
    private $xpath;

    /**
     * USE : selectorDOM::select_elements() !
     * @param DOMDocument $data DOMDocument
     */
    public function __construct($data) {
        if ($data instanceof DOMDocument) {
            $this->xpath = new DOMXpath($data);
        } else {
            $dom = new DOMDocument();
            @$dom->loadHTML($data);
            $this->xpath = new DOMXpath($dom);
        }
    }

    /**
     * Retourne les éléments du DOM indiqué par le selecteur 
     * (les selecteurs ont la même syntaxe que Jquery)
     * @param string $selector Sélecteur dans le DOM
     * @param boolean $as_array Le resultat doit-il être sous forme de tableau (true) ou d'objet DOMNodeList (false) ? (true par defaut)
     * @return array|DOMNodeList Eléments du DOM indiqué par le selecteur 
     */
    public function select($selector, $as_array = true) {
        $elements = $this->xpath->evaluate($this->selector_to_xpath($selector));
        return $as_array ? $this->elements_to_array($elements) : $elements;
    }

    /**
     * Select elements from $html using the css $selector.
     * When $as_array is true elements and their children will
     * be converted to array's containing the following keys (defaults to true):
     *
     *  - name : element name
     *  - text : element text
     *  - children : array of children elements
     *  - attributes : attributes array
     *
     * Otherwise regular DOMElement's will be returned.
     *      
     * @param string $selector Selecteur dans le DOM
     * @param string $html HTML
     * @param boolean $as_array le resultat doit-il être sous forme de tableau (true) ou d'objet DOMNodeList (false) ? (true par defaut)
     * @return array|DOMNodeList éléments du DOM indiqué par le selecteur 
     * @return array|DOMNodeList éléments du DOM indiqué par le selecteur
     */
    public static function select_elements($selector, $html, $as_array = true) {
        return (new SelectorDOM($html))->select($selector, $as_array);
    }

    /**
     * Convert $elements to an array.
     * 
     * @param DOMNodeList $elements object DOMNodeList
     * @return array DOMNodeList to array
     */
    private function elements_to_array($elements) {
        $array = [];
        for ($i = 0, $length = $elements->length; $i < $length; ++$i) {
            if ($elements->item($i)->nodeType == XML_ELEMENT_NODE) {
                array_push($array, $this->element_to_array($elements->item($i)));
            }
        }
        return $array;
    }

    /**
     * Convert $element to an array.
     * 
     * @param DOMNode $element object DOMNode
     * @return array DOMNode to array
     */
    private function element_to_array($element) {
        $array = [
            'name' => $element->nodeName,
            'attributes' => [],
            'text' => $element->textContent,
            'children' => $this->elements_to_array($element->childNodes)
        ];
        if ($element->attributes->length) {
            foreach ($element->attributes as $key => $attr) {
                $array['attributes'][$key] = $attr->value;
            }
        }
        return $array;
    }

    /**
     * Convert $selector into an XPath string.
     * @param string $selector Selecteur dans le DOM
     */
    private function selector_to_xpath($selector) {
        // remove spaces around operators
        $selector = preg_replace([
            '/\s*>\s*/',
            '/\s*~\s*/',
            '/\s*\+\s*/',
            '/\s*,\s*/'
                ], [
            '>',
            '~',
            '+',
            ','
                ], $selector);
        $selectors = preg_split('/\s+(?![^\[]+\])/', $selector);
        foreach ($selectors as &$selector) {
            // ,
            $selector = preg_replace('/,/', '|descendant-or-self::', $selector);
            // input:checked, :disabled, etc.
            $selector = preg_replace('/(.+)?:(checked|disabled|required|autofocus)/', '\1[@\2="\2"]', $selector);
            // input:autocomplete, :autocomplete
            $selector = preg_replace('/(.+)?:(autocomplete)/', '\1[@\2="on"]', $selector);
            // input:button, input:submit, etc.
            $selector = preg_replace('/:(text|password|checkbox|radio|button|submit|reset|file|hidden|image|datetime|datetime-local|date|month|time|week|number|range|email|url|search|tel|color)/', 'input[@type="\1"]', $selector);
            // foo[id]
            $selector = preg_replace('/(\w+)\[([_\w-]+[_\w\d-]*)\]/', '\1[@\2]', $selector);
            // [id]
            $selector = preg_replace('/\[([_\w-]+[_\w\d-]*)\]/', '*[@\1]', $selector);
            // foo[id=foo]
            $selector = preg_replace('/\[([_\w-]+[_\w\d-]*)=[\'"]?(.*?)[\'"]?\]/', '[@\1="\2"]', $selector);
            // [id=foo]
            $selector = preg_replace('/^\[/', '*[', $selector);
            // div#foo
            $selector = preg_replace('/([_\w-]+[_\w\d-]*)\#([_\w-]+[_\w\d-]*)/', '\1[@id="\2"]', $selector);
            // #foo
            $selector = preg_replace('/\#([_\w-]+[_\w\d-]*)/', '*[@id="\1"]', $selector);
            // div.foo
            $selector = preg_replace('/([_\w-]+[_\w\d-]*)\.([_\w-]+[_\w\d-]*)/', '\1[contains(concat(" ",@class," ")," \2 ")]', $selector);
            // .foo
            $selector = preg_replace('/\.([_\w-]+[_\w\d-]*)/', '*[contains(concat(" ",@class," ")," \1 ")]', $selector);
            // div:first-child
            $selector = preg_replace('/([_\w-]+[_\w\d-]*):first-child/', '*/\1[position()=1]', $selector);
            // div:last-child
            $selector = preg_replace('/([_\w-]+[_\w\d-]*):last-child/', '*/\1[position()=last()]', $selector);
            // :first-child
            $selector = str_replace(':first-child', '*/*[position()=1]', $selector);
            // :last-child
            $selector = str_replace(':last-child', '*/*[position()=last()]', $selector);
            // :nth-last-child
            $selector = preg_replace('/:nth-last-child\((\d+)\)/', '[position()=(last() - (\1 - 1))]', $selector);
            // div:nth-child
            $selector = preg_replace('/([_\w-]+[_\w\d-]*):nth-child\((\d+)\)/', '*/*[position()=\2 and self::\1]', $selector);
            // :nth-child
            $selector = preg_replace('/:nth-child\((\d+)\)/', '*/*[position()=\1]', $selector);
            // :contains(Foo)
            $selector = preg_replace('/([_\w-]+[_\w\d-]*):contains\((.*?)\)/', '\1[contains(string(.),"\2")]', $selector);
            // >
            $selector = preg_replace('/>/', '/', $selector);
            // ~
            $selector = preg_replace('/~/', '/following-sibling::', $selector);
            // +
            $selector = preg_replace('/\+([_\w-]+[_\w\d-]*)/', '/following-sibling::\1[position()=1]', $selector);
            $selector = str_replace(']*', ']', $selector);
            $selector = str_replace(']/*', ']', $selector);
        }
        // ' '
        $selector = implode('/descendant::', $selectors);
        $selector = 'descendant-or-self::' . $selector;
        // :scope
        $selector = preg_replace('/(((\|)?descendant-or-self::):scope)/', '.\3', $selector);
        // $element
        $sub_selectors = explode(',', $selector);
        foreach ($sub_selectors as $key => $sub_selector) {
            $parts = explode('$', $sub_selector);
            $sub_selector = array_shift($parts);
            if (count($parts) && preg_match_all('/((?:[^\/]*\/?\/?)|$)/', $parts[0], $matches)) {
                $results = $matches[0];
                $results[] = str_repeat('/..', count($results) - 2);
                $sub_selector .= implode('', $results);
            }
            $sub_selectors[$key] = $sub_selector;
        }
        $selector = implode(',', $sub_selectors);

        return $selector;
    }

}
