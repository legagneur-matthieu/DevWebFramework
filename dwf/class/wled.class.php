<?php

/**
 * Cette classe permet d'exploiter l'API HTTP de WLED
 * https://github.com/Aircoookie/WLED
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class wled {

    private $_url = "";
    private $params = [];

    public function __construct($ip) {
        $this->_url = "http://{$ip}/win";
    }

    /**
     * Exécute les paramètres définis
     */
    public function exec() {
        $params = "";
        foreach ($this->params as $key => $value) {
            $params .= ($value === "ON" ? "&{$key}" : "&{$key}={$value}");
        }
        $curl = curl_init($this->_url . $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($curl);
        curl_close($curl);
        $this->params = [];
    }

    /**
     * Définit les paramètres directement (attention pas de vérifications !)
     * @param array $params parametres
     * @return $this
     */
    public function set_params($params) {
        $this->params = $params;
        return $this;
    }

    /* LED Cntrol */

    /**
     * Définit la luminosité maitre
     * @param int $A entre 0 et 255
     * @return $this
     */
    public function set_master_brightness($A) {
        if ($A >= 0 and $A <= 255) {
            $this->params["A"] = $A;
        }
        return $this;
    }

    /**
     * Définit si le stripled est
     * 0 : Off
     * 1 : On
     * 2 : switch (si le stripled est Off il devient On et inversement)
     * @param int $T 0, 1 ou 2
     * @return $this
     */
    public function set_master($T) {
        if (in_array($T, [0, 1, 2])) {
            $this->params["T"] = $T;
        }
        return $this;
    }

    /**
     * Définit le rouge
     * @param int $R entre 0 et 255 
     * @return $this
     */
    public function set_red($R) {
        if ($R >= 0 and $R <= 255) {
            $this->params["R"] = $R;
        }
        return $this;
    }

    /**
     * Définit le vert
     * @param int $G entre 0 et 255
     * @return $this
     */
    public function set_green($G) {
        if ($G >= 0 and $G <= 255) {
            $this->params["G"] = $G;
        }
        return $this;
    }

    /**
     * Définit le bleu
     * @param int $B entre 0 et 255
     * @return $this
     */
    public function set_blue($B) {
        if ($B >= 0 and $B <= 255) {
            $this->params["B"] = $B;
        }
        return $this;
    }

    /**
     * Définit le blanc (stripled RGBW)
     * @param int $W entre 0 et 255
     * @return $this
     */
    public function set_white($W) {
        if ($W >= 0 and $W <= 255) {
            $this->params["W"] = $W;
        }
        return $this;
    }

    /**
     * Applique un effet depuis son index
     * @param int $FX entre 0 et 101
     * @return $this
     */
    public function set_effect_index($FX) {
        if ($FX >= 0 and $FX <= 101) {
            $this->params["FX"] = $FX;
        }
        return $this;
    }

    /**
     * Définit la vitesse de l'effet
     * @param int $SX entre 0 et 255
     * @return $this
     */
    public function set_effect_speed($SX) {
        if ($SX >= 0 and $SX <= 255) {
            $this->params["SX"] = $SX;
        }
        return $this;
    }

    /**
     * Définit l'intensité de l'effet
     * @param int $IX entre 0 et 255
     * @return $this
     */
    public function set_effect_intensity($IX) {
        if ($IX >= 0 and $IX <= 255) {
            $this->params["IX"] = $IX;
        }
        return $this;
    }

    /**
     * Définit le FastLED palette
     * @param int $FP entre 0 et 46
     * @return $this
     */
    public function set_FastLED_palette($FP) {
        if ($FP >= 0 and $FP <= 46) {
            $this->params["FP"] = $FP;
        }
        return $this;
    }

    /**
     * Active le mode veilleuse avec la durée par defaut
     * @return $this
     */
    public function set_nightlight() {
        $this->params["ND"] = "ON";
        return $this;
    }

    /**
     * Active le mode veilleuse avec la durée définie en minute ( entre 0 et 255)
     * @param int $NL entre 0 et 255
     * @return $this
     */
    public function set_nightlight_duration($NL) {
        if ($NL >= 0 and $NL <= 255) {
            $this->params["NL"] = $NL;
        }
        return $this;
    }

    /**
     * Définit la luminosité cible du mode veileuse
     * @param int $NT entre 0 et 255
     * @return $this
     */
    public function set_nightlight_target_brightness($NT) {
        if ($NT >= 0 and $NT <= 255) {
            $this->params["NT"] = $NT;
        }
        return $this;
    }

    /**
     * Définit le fondu du mode veilleuse
     * 0 : pas de fondu
     * 1 : fondu de luminosité
     * 2 : fondu de la couleur primaire à la secondaire
     * @param int $NF 0, 1 ou 2
     * @return $this
     */
    public function set_nightlight_fade($NF) {
        if (in_array($NF, [0, 1, 2])) {
            $this->params["NF"] = $NF;
        }
        return $this;
    }

    /* Advanced */

    /**
     * Définit la couleur primaire (HEX ou DEC)
     * pour HEX, précédèz d'un "H" exemple : H0000FF
     * @param string $CL HEX ou DEC
     * @return $this
     */
    public function set_primary_color($CL) {
        $this->params["CL"] = $CL;
        return $this;
    }

    /**
     * Définit la couleur secondaire (HEX ou DEC)
     * pour HEX, précèdez d'un "H" exemple : H0000FF
     * @param string $C2 HEX ou DEC
     * @return $this
     */
    public function set_secondary_color($C2) {
        $this->params["C2"] = $C2;
        return $this;
    }

    /**
     * Définit la couleur tertiaire (HEX ou DEC)
     * pour HEX, précèdez d'un "H" exemple : H0000FF
     * @param string $C3 HEX ou DEC
     * @return $this
     */
    public function set_third_color($C3) {
        $this->params["C3"] = $C3;
        return $this;
    }

    /**
     * Définit le rouge de la couleur secondaire
     * @param int $R2 entre 0 et 255 
     * @return $this
     */
    public function set_red_secondary($R2) {
        if ($R2 >= 0 and $R2 <= 255) {
            $this->params["R2"] = $R2;
        }
        return $this;
    }

    /**
     * Définit le vert de la couleur secondaire
     * @param int $G2 entre 0 et 255 
     * @return $this
     */
    public function set_green_secondary($G2) {
        if ($G2 >= 0 and $G2 <= 255) {
            $this->params["G2"] = $G2;
        }
        return $this;
    }

    /**
     * Définit le bleu de la couleur secondaire
     * @param int $B2 entre 0 et 255 
     * @return $this
     */
    public function set_blue_secondary($B2) {
        if ($B2 >= 0 and $B2 <= 255) {
            $this->params["B2"] = $B2;
        }
        return $this;
    }

    /**
     * Définit le blanc de la couleur secondaire (Stripled RGBW)
     * @param int $W2 entre 0 et 255 
     * @return $this
     */
    public function set_white_secondary($W2) {
        if ($W2 >= 0 and $W2 <= 255) {
            $this->params["W2"] = $W2;
        }
        return $this;
    }

    /**
     * Définit la teinte
     * @param int $HU entre 0 et 65535
     * @return $this
     */
    public function set_hue($HU) {
        if ($HU >= 0 and $HU <= 65535) {
            $this->params["HU"] = $HU;
        }
        return $this;
    }

    /**
     * Définit la saturation et la teinte de la couleur primaire
     * @param int $SA entre 0 et 255
     * @param int $HU entre 0 et 65535
     * @return $this
     */
    public function set_saturation_hue($SA, $HU) {
        if ($SA >= 0 and $SA <= 255) {
            $this->params["SA"] = $SA;
            $this->set_hue($HU);
        }
        return $this;
    }

    /**
     * Définit la saturation et la teinte de la couleur secondaire
     * /!\ A ne pas utiliser dans la même requete de la définition de la couleur primaire !
     * @param int $SA entre 0 et 255
     * @param int $HU entre 0 et 65535
     * @param type $SA
     * @param type $HU
     * @return $this
     */
    public function set_saturation_hue_secondary($SA, $HU) {
        $this->params["H2"] = "ON";
        $this->set_saturation_hue($SA, $HU);
        return $this;
    }

    /**
     * Définit une teinte aléatoire
     * @param bool $SR true/false
     * @return $this
     */
    public function set_random_hue($SR) {
        $this->params["SR"] = ($SR ? 1 : 0);
        return $this;
    }

    /**
     * Inverse les couleurs primaires et secondaires
     * @return $this
     */
    public function set_swap_primary_secondary() {
        $this->params["SC"] = "ON";
        return $this;
    }

    /* Loxone commands */

    /**
     * Définit la couleur primaire RGB
     * @param int $LX exemple : 100100100
     * @return $this
     */
    public function set_loxone_rgb($LX) {
        $this->params["LX"] = $LX;
        return $this;
    }

    /**
     * Définit la luminosité primaire
     * @param int $LX exemple : 201006500
     * @return $this
     */
    public function set_loxone_brightness($LX) {
        $this->params["LX"] = $LX;
        return $this;
    }

    /**
     * Définit la couleur secondaire RGB
     * @param int $LY exemple : 100100100
     * @return $this
     */
    public function set_loxone_rgb_seconary($LY) {
        $this->params["LY"] = $LY;
        return $this;
    }

    /**
     * Définit la luminosité secondaire
     * @param int $LY exemple : 201006500
     * @return $this
     */
    public function set_loxone_brightness_secondary($LY) {
        $this->params["LY"] = $LY;
        return $this;
    }

    /* Notifications */

    /**
     * Reçoit les notifications UDP
     * @param bool $RN true/false
     * @return $this
     */
    public function set_receive_notification($RN) {
        $this->params["RN"] = ($RN ? 1 : 0);
        return $this;
    }

    /**
     * Envoit les notifications UDP
     * @param bool $SN true/false
     * @return $this
     */
    public function set_send_notification($SN) {
        $this->params["SN"] = ($SN ? 1 : 0);
        return $this;
    }

    /**
     * Aucune notification pour cette requête 
     * @return $this
     */
    public function set_no_notification() {
        $this->params["NN"] = "ON";
        return $this;
    }

    /**
     * Définit la teinte de la notification
     * @param int $HP entre 0 et 99
     * @return $this
     */
    public function set_hue_notification($HP) {
        if ($HP >= 0 and $HP <= 99) {
            $this->params["HP"] = $HP;
        }
        return $this;
    }

    /* Presets */

    /**
     * Sauvegarde le preset actuel dans un des 16 emplacements disponibles
     * @param int $PS entre 1 et 16
     * @return $this
     */
    public function set_save_preset($PS) {
        if ($PS >= 1 and $PS <= 16) {
            $this->params["PS"] = $PS;
        }
        return $this;
    }

    /**
     * Charge un preset (0=preset par defaut)
     * @param int $PL entre 0 et 16
     * @return $this
     */
    public function set_load_preset($PL) {
        if ($PL >= 0 and $PL <= 16) {
            $this->params["PL"] = $PL;
        }
        return $this;
    }

    /**
     * Cycle des preset
     * 0 : non
     * 1 : oui
     * 2 : switch
     * @param type $CY
     * @return $this
     */
    public function set_preset_cycle($CY) {
        if (in_array($CY, [0, 1, 2])) {
            $this->params["CY"] = $CY;
        }
        return $this;
    }

    /**
     * Applique la luminosité des presets
     * @param bool $PA true/false
     * @return $this
     */
    public function set_preset_apply_brightness($PA) {
        $this->params["PA"] = ($PA ? 1 : 0);
        return $this;
    }

    /**
     * Définit le premier preset du cycle
     * @param int $P1 entre 1 et 16
     * @return $this
     */
    public function set_preset_first_cycle($P1) {
        if ($P1 >= 1 and $P1 <= 16) {
            $this->params["P1"] = $P1;
        }
        return $this;
    }

    /**
     * Définit le dernier preset du cycle
     * @param int $P2 entre 1 et 16
     * @return $this
     */
    public function set_preset_last_cycle($P2) {
        if ($P2 >= 1 and $P2 <= 16) {
            $this->params["P2"] = $P2;
        }
        return $this;
    }

    /**
     * Définit le temps de chaque preset
     * @param int $PT entre 50 et 65000
     * @return $this
     */
    public function set_preset_cycle_time($PT) {
        if ($PT >= 50 and $PT <= 65000) {
            $this->params["PT"] = $PT;
        }
        return $this;
    }

    /**
     * Définit le temps entre chaque preset
     * @param int $TT entre 50 et 65000
     * @return $this
     */
    public function set_preset_cycle_transition_time($TT) {
        if ($TT >= 0 and $TT <= 65000) {
            $this->params["PT"] = $TT;
        }
        return $this;
    }

    /* Macros */

    /**
     * Applique une macro
     * @param int $M entre 0 et 16
     * @return $this
     */
    public function set_macro($M) {
        if ($M >= 0 and $M <= 16) {
            $this->params["M"] = $M;
        }
        return $this;
    }

    /* Segments */

    /**
     * Définit le segment principal
     * @param int $SM entre 0 et 15
     * @return $this
     */
    public function set_main_segment($SM) {
        if ($SM >= 0) {
            $this->params["SM"] = $SM;
        }
        return $this;
    }

    /**
     * Définit quel segment à selectionner
     * @param int $SS entre 0 et 15
     * @return $this
     */
    public function set_select_segment($SS) {
        if ($SS >= 0) {
            $this->params["SS"] = $SS;
        }
        return $this;
    }

    /**
     * Définit si le segment doit être selectionné 
     * 0 : non
     * 1 : oui
     * 2 : switch
     * @param type $SV
     * @return $this
     */
    public function set_segment_selected($SV) {
        if (in_array($SV, [0, 1, 2])) {
            $this->params["SV"] = $SV;
        }
        return $this;
    }

    /**
     * Définit le debut du segment
     * @param int $S entre 0 et le nombre de leds -1
     * @return $this
     */
    public function set_segment_start($S) {
        $this->params["S"] = $S;
        return $this;
    }

    /**
     * Définit la fin du segment
     * @param int $S2 entre 0 et le nombre de leds
     * @return $this
     */
    public function set_segment_stop($S2) {
        $this->params["S2"] = $S2;
        return $this;
    }

    /**
     * Définit le regroupement du segment 
     * @param int $GP entre 1 et 255
     * @return $this
     */
    public function set_segment_grouping($GP) {
        if ($GP >= 1 and $GP <= 255) {
            $this->params["GP"] = $GP;
        }
        return $this;
    }

    /**
     * Définit l'espacement du segment
     * @param int $SP entre 1 et 255
     * @return $this
     */
    public function set_segment_spacing($SP) {
        if ($SP >= 0 and $SP <= 255) {
            $this->params["SP"] = $SP;
        }
        return $this;
    }

    /**
     * Inverse l'ordre du segment
     * @param bool $RV true/false
     * @return $this
     */
    public function set_segment_reverse($RV) {
        $this->params["RV"] = ($RV ? 1 : 0);
        return $this;
    }

    /**
     * Définit la luminosité du segment
     * @param int $SB entre 0 et 255
     * @return $this
     */
    public function set_segment_brightness($SB) {
        if ($SB >= 0 and $SB <= 255) {
            $this->params["SB"] = $SB;
        }
        return $this;
    }

    /* General and Experimental */

    /**
     * Redémarre WLED
     * @return $this
     */
    public function reboot() {
        $this->params["RB"] = "ON";
        return $this;
    }

}
