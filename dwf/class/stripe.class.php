<?php

/**
 * Cette classe permet de metre en place un système de payement par stripe
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class stripe {

    /**
     * Clé API
     * @var string Clé API
     */
    private $_secret_key;

    /**
     * Devise
     * @var string Devise
     */
    private $_currency;

    /**
     * Cette classe permet de metre en place un système de payement par stripe
     * @param string $secret_key Clé API
     * @param string $currency Devise ("EUR" par defaut)
     */
    public function __construct($secret_key, $currency = "EUR") {
        require_once '../../dwf/class/stripe/init.php';
        $this->_secret_key = $secret_key;
        $this->_currency = strtolower($currency);
        \Stripe\Stripe::setApiKey($this->_secret_key);
    }

    /**
     * Crée une session Stripe Checkout
     * @param array $item_list Liste d'articles (prix HT) au format :
     *    [
     *      ["Name"=>"Produit", "Price"=>10.50, "Quantity"=>1]
     *    ]
     * @param string $success_url URL de retour après paiement réussi
     * @param string $cancel_url URL de retour si paiement annulé
     * @param float $vat Taux de TVA (ex: 20 pour 20%)
     * @param float $shipping Frais de port (optionnel)
     * @return string|false URL de redirection Stripe ou false si erreur
     */
    public function create_checkout_session($item_list, $success_url, $cancel_url, $vat = 0, $shipping = 0.0) {
        try {
            $line_items = [];
            foreach ($item_list as $item) {
                $price_ht = floatval($item["Price"]);
                $price_ttc = $price_ht * (1 + $vat / 100);
                $line_items[] = [
                    'price_data' => [
                        'currency' => $this->_currency,
                        'product_data' => ['name' => $item["Name"]],
                        'unit_amount' => intval(round($price_ttc * 100)), // Stripe en centimes
                    ],
                    'quantity' => intval($item["Quantity"]),
                ];
            }

            if ($shipping > 0) {
                $line_items[] = [
                    'price_data' => [
                        'currency' => $this->_currency,
                        'product_data' => ['name' => 'Frais de livraison'],
                        'unit_amount' => intval(round($shipping * 100)),
                    ],
                    'quantity' => 1,
                ];
            }

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => $success_url . '&stripe_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancel_url,
            ]);

            return $session->url;
        } catch (Exception $e) {
            error_log("Stripe Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les infos d'une session Checkout
     * @param string $stripe_id ID Stripe retourné en GET (success)
     * @param float $total Montant total du payement pour verrification
     * @return \Stripe\Checkout\Session|null
     */
    public function get_session($stripe_id, $total) {
        try {
            if ($session = \Stripe\Checkout\Session::retrieve($stripe_id)) {

                if ($session->payment_status !== 'paid') {
                    error_log("Stripe Error: Not paid !");
                    return false;
                }
                if ((int) ($session->amount_total) != (int) ($total * 100)) {
                    error_log("Stripe Error: Bad amount !");
                    return false;
                }
                return $session;
            }
            return false;
        } catch (Exception $e) {
            error_log("Stripe Error: " . $e->getMessage());
            return false;
        }
    }
}
