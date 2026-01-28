<?php

/**
 * Cette classe permet de créer, vérifier et exécuter des paiements via l'API REST de PayPal 
 * @autor LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class paypal {

    /**
     * PayPalClient
     * @var PaypalServerSdkLib\PaypalServerSdkClient PayPalClient
     */
    private $_client;

    /**
     * Devise utilisée ("EUR" par defaut)
     * @var string Devise utilisée ("EUR" par defaut)
     */
    private $_currency;

    /**
     * Méthode de paiement utilisée ("paypal" par defaut)
     * @var string Méthode de paiement utilisée ("paypal" par defaut)
     */
    private $_payment_method;

    /**
     * Permet de vérifier que le SDK PayPal a bien été appelé qu'une fois.
     * @var boolean Permet de vérifier que le SDK PayPal a bien été appelé qu'une fois.
     */
    private static $_called = false;

    /**
     * Cette classe permet de créer, verifier et exécuter des paiements via l'API REST de PayPal 
     * 
     * @param string $clientId clientId renseigné dans l'application de l'API REST de PayPal
     * @param string $clientSecret clientSecret renseigné dans l'application de l'API REST de PayPal
     * @param string $currency Devise utilisée ("EUR" par defaut)
     * @param string $payment_method Méthode de paiement utilisée ("paypal" par defaut)
     */
    public function __construct($clientId, $clientSecret, $currency = "EUR", $payment_method = "paypal") {
        if (!self::$_called) {
            spl_autoload_register(function ($class) {
                $prefix = 'PaypalServerSdkLib\\';
                if (strpos($class, $prefix) === 0) {
                    $relativeClass = substr($class, strlen($prefix));
                    $file = __DIR__ . '/PayPal-PHP-Server-SDK/src/' . str_replace('\\', '/', $relativeClass) . '.php';
                    if (file_exists($file)) {
                        require $file;
                    }
                }
            });
            export_dwf::add_files([realpath(__DIR__ . "/PayPal-PHP-Server-SDK")]);
            self::$_called = true;
        }
        $this->_client = PaypalServerSdkLib\PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder::init(
                    $clientId,
                    $clientSecret
                )
            )
            ->environment(PaypalServerSdkLib\Environment::SANDBOX) // Change to ::PRODUCTION for live
            ->build();
        $this->_currency = $currency;
        $this->_payment_method = $payment_method;
    }

    /**
     * Créé un paiement
     * @param array $item_list Liste des articles vendus sous la forme : 
     *     [ 
     *         ["Name"=>"article1", "Price"=>"10.50", "Quantity"=>"1"],
     *         ["Name"=>"article2","Price"=>"1.99","Quantity"=>"5"]
     *     ]
     *     ATTENTION : Les majuscules aux noms des clés sont importantes !
     * @param int|float $vat Taux de TVA (exemple : 20 pour 20% de TVA)
     * @param string $description Description de la transaction
     * @param string $returnurl Url de retour lors du suucées de la création de paiement
     * @param string $cancelurl Url de retour en cas d'annulation ou d'erreur du paiement
     * @param string $custom Champ libre pour stocker des données liés au paiement (json utilisable)
     * @param int|float $shipping frais de livraison (0 par defaut)
     * @param string $intent Type de transaction ( par defaut "sale" pour une vente directe)
     * @return string|boolean Retourne le lien de paiement pour l'utilisateur ou false en cas d'erreur.
     */
    public function create_payment($item_list, $vat, $description, $returnurl, $cancelurl, $shipping = 0, $custom = "", $intent = "sale") {
        $amt = 0;
        foreach ($item_list as $item) {
            $amt += ($item["Price"] * $item["Quantity"]);
        }
        $amt_vat = floatval(number_format(math::pourcentage($amt, $vat), 2));
        $total = $amt + $amt_vat + $shipping;

        $body = [
            "intent" => strtoupper($intent) === "SALE" ? "CAPTURE" : "AUTHORIZE",
            "application_context" => [
                "return_url" => $returnurl,
                "cancel_url" => $cancelurl,
            ],
            "purchase_units" => [
                [
                    "description" => $description,
                    "custom_id" => $custom,
                    "amount" => [
                        "currency_code" => $this->_currency,
                        "value" => number_format($total, 2, '.', ''),
                        "breakdown" => [
                            "item_total" => [
                                "currency_code" => $this->_currency,
                                "value" => number_format($amt, 2, '.', ''),
                            ],
                            "shipping" => [
                                "currency_code" => $this->_currency,
                                "value" => number_format($shipping, 2, '.', ''),
                            ],
                            "tax_total" => [
                                "currency_code" => $this->_currency,
                                "value" => number_format($amt_vat, 2, '.', ''),
                            ],
                        ]
                    ],
                    "items" => $this->itemlist_from_array($item_list),
                ]
            ]
        ];

        $request = new PaypalServerSdkLib\Orders\OrdersCreateRequest();
        $request->body = $body;

        try {
            $response = $this->_client->execute($request);
            if ($response->statusCode === 201) {
                foreach ($response->result->links as $link) {
                    if ($link->rel === "approve") {
                        return $link->href;
                    }
                }
            }
            return false;
        } catch (Exception $e) {
            dwf_exception::print_exception($e, "PayPal: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retourne le paiement retourné par PayPal
     * @param string $paymentId $_GET["token"]
     * @return object Paiement retourné par PayPal (order result)
     */
    public function get_payment($paymentId) {
        $request = new PaypalServerSdkLib\Orders\OrdersGetRequest($paymentId);
        try {
            $response = $this->_client->execute($request);
            return $response->result;
        } catch (Exception $e) {
            dwf_exception::print_exception($e, "PayPal: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Execute un paiement
     * @param object $payment le paieent retourné par PayPal
     * @return boolean false en cas d'erreur ( true = OK )
     */
    public function execute_payment($payment) {
        $orderId = $payment->id;
        if ($payment->intent === 'CAPTURE') {
            $request = new PaypalServerSdkLib\Orders\OrdersCaptureRequest($orderId);
        } else {
            $request = new PaypalServerSdkLib\Orders\OrdersAuthorizeRequest($orderId);
        }
        $request->body = [];

        try {
            $response = $this->_client->execute($request);
            return $response->statusCode === 201;
        } catch (Exception $e) {
            dwf_exception::print_exception($e, "PayPal: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Convertit une liste d'articles "array" en array pour purchase_units items
     * @param array $item_list Liste d'articles
     * @return array Array of items 
     */
    private function itemlist_from_array($item_list) {
        $items = [];
        foreach ($item_list as $item) {
            $items[] = [
                "name" => $item["Name"],
                "quantity" => (string)$item["Quantity"],
                "unit_amount" => [
                    "currency_code" => $this->_currency,
                    "value" => number_format($item["Price"], 2, '.', '')
                ]
            ];
        }
        return $items;
    }

}