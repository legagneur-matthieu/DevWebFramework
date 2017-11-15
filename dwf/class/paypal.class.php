<?php

/**
 * Cette classe permet de créer, verifier et executer des payment via l'API REST de PayPal 
 * @autor LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class paypal {

    /**
     * ApiContext
     * @var PayPal\Rest\ApiContext ApiContext
     */
    private $_api_context;

    /**
     * Devise utilisé ("EUR" par defaut)
     * @var string Devise utilisé ("EUR" par defaut)
     */
    private $_currency;

    /**
     * Methode de paymoent utilisé ("paypal" par defaut)
     * @var string Methode de paymoent utilisé ("paypal" par defaut)
     */
    private $_payment_method;

    /**
     * Permet de vérifier que le SDK PayPaj a bien été appelé qu'une fois.
     * @var boolean Permet de vérifier que le SDK PayPaj a bien été appelé qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe permet de créer, verifier et executer des payment via l'API REST de PayPal 
     * 
     * @param string $clientId clientId renseigné dans l'application de l'API REST de PayPal
     * @param string $clientSecret clientSecret renseigné dans l'application de l'API REST de PayPal
     * @param string $currency Devise utilisé ("EUR" par defaut)
     * @param string $payment_method Methode de paymoent utilisé ("paypal" par defaut)
     */
    public function __construct($clientId, $clientSecret, $currency = "EUR", $payment_method = "paypal") {
        if (!self::$_called) {
            include __DIR__ . "/PayPal-PHP-SDK/autoload.php";
            self::$_called = true;
        }
        $this->_api_context = new PayPal\Rest\ApiContext(new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret));
        $this->_currency = $currency;
        $this->_payment_method = $payment_method;
    }

    /**
     * Créé un Payment
     * @param array $item_list Liste des articles vendu sous la forme : 
     *     [ 
     *         ["Name"=>"article1", "Price"=>"10.50", "Quantity"=>"1"],
     *         ["Name"=>"article2","Price"=>"1.99","Quantity"=>"5"]
     *     ]
     *     ATTENTION : Les majuscules aux noms des clé son importantes !
     * @param int|float $vat Taux de TVA (exemple : 20 pour 20% de TVA)
     * @param string $description Description de la transaction
     * @param string $returnurl Url de retour lors du suucées de la création de payment
     * @param string $cancelurl Url  de retour en cas d'annulation ou d'erreur du payment
     * @param string $custom Champ libre pour stoquer des données lié au payment (json utilisable)
     * @param int|float $shipping frais de livraison (0 aur defaut)
     * @param string $intent Type de transaction ( par defaut "sale" pour une vente directe)
     * @return string|boolean Retourne le lien de payment pour l'utilisateur ou false en cas d'erreur.
     */
    public function create_payment($item_list, $vat, $description, $returnurl, $cancelurl, $shipping = 0, $custom = "", $intent = "sale") {
        $amt = 0;
        foreach ($item_list as $item) {
            $amt += ($item["Price"] * $item["Quantity"]);
        }
        $amt_vat = floatval(number_format(math::pourcentage($amt, $vat), 2));
        $payment = (new \PayPal\Api\Payment())
                ->setIntent($intent)
                ->setRedirectUrls((new \PayPal\Api\RedirectUrls())->setReturnUrl($returnurl)->setCancelUrl($cancelurl))
                ->addTransaction((new \PayPal\Api\Transaction())
                        ->setItemList($this->itemlist_from_array($item_list))
                        ->setDescription($description)
                        ->setAmount((new \PayPal\Api\Amount())
                                ->setTotal($amt + $amt_vat)
                                ->setCurrency($this->_currency)
                                ->setDetails((new \PayPal\Api\Details())->setTax($amt_vat)->setSubtotal($amt)->setShipping($shipping))
                        )
                        ->setCustom($custom))
                ->setPayer((new \PayPal\Api\Payer())->setPaymentMethod($this->_payment_method));
        try {
            $payment->create($this->_api_context);
            return $payment->getApprovalLink();
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            dwf_exception::print_exception($e, "PayPal : " . $e->getData());
            return FALSE;
        }
    }

    /**
     * Retourne le payment retourné par PayPal
     * @param string $paymentId $_GET["paymentId"]
     * @return \PayPal\Api\Payment Payment retourné par PayPal
     */
    public function get_payment($paymentId) {
        return \PayPal\Api\Payment::get($paymentId, $this->_api_context);
    }

    /**
     * Execute un payment
     * @param PayPal\Api\Payment $payment le payment retourné par PayPal
     * @return boolean false en cas d'erreur ( true = OK )
     */
    public function execute_payment(PayPal\Api\Payment $payment) {
        try {
            $payment->execute((new \PayPal\Api\PaymentExecution())
                            ->setPayerId($payment->getPayer()->getPayerInfo()->getPayerId())
                            ->setTransactions($payment->getTransactions())
                    , $this->_api_context);
            return true;
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            dwf_exception::print_exception($e, "PayPal : " . $e->getData());
            return FALSE;
        }
    }

    /**
     * Convertis une liste d'articles "array" en \PayPal\Api\ItemList()
     * @param array $item_list Liste d'articles
     * @return \PayPal\Api\ItemList Objet ItemList 
     */
    private function itemlist_from_array($item_list) {
        $itemlist = new \PayPal\Api\ItemList();
        foreach ($item_list as $item) {
            $item_obj = new PayPal\Api\Item();
            foreach ($item as $key => $value) {
                $set = "set" . $key;
                $item_obj->$set($value);
            }
            $item_obj->setCurrency($this->_currency);
            $itemlist->addItem($item_obj);
        }
        return $itemlist;
    }

}
