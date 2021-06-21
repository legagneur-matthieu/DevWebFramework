/**
 * Cette classe permet d'exploiter l'API HTTP de WLED
 * https://github.com/Aircoookie/WLED
 * Requière JQUERY
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 * @param {string} ip IP de WLED
 * @returns {wled}
 */
wled = function (ip) {

    this.url = "http://" + ip + "/win";
    this.params = [];

    /**
     * Exécute les paramètres définis
     * @param {function} callback
     */
    this.exec = function (callback = function() {}) {
        params = "";
        this.params.forEach(function (v, k) {
            params += (v == "ON" ? "&" + k : "&" + k + "=" + v);
        });
        $.post(this.url + params, {}, callback);
        this.params = [];
    };

    /**
     * Définit les paramètres directement (attention pas de vérifications !)
     * @param {array} params parametres
     * @return this
     */
    this.set_params = function (params) {
        this.params = params;
        return this;
    };

    /* LED Cntrol */

    /**
     * Définit la luminosité maitre
     * @param {int} A entre 0 et 255
     * @return this
     */
    this.set_master_brightness = function (A) {
        if (A >= 0 && A <= 255) {
            this.params["A"] = A;
        }
        return this;
    };

    /**
     * Définit si le stripled est
     * 0 : Off
     * 1 : On
     * 2 : switch (si le stripled est Off il devient On et inversement)
     * @param {int} T 0, 1 ou 2
     * @return this
     */
    this.set_master = function (T) {
        if (in_array(T, [0, 1, 2])) {
            this.params["T"] = T;
        }
        return this;
    };

    /**
     * Définit le rouge
     * @param {int} R entre 0 et 255 
     * @return this
     */
    this.set_red = function (R) {
        if (R >= 0 && R <= 255) {
            this.params["R"] = R;
        }
        return this;
    };

    /**
     * Définit le vert
     * @param {int} G entre 0 et 255
     * @return this
     */
    this.set_green = function (G) {
        if (G >= 0 && G <= 255) {
            this.params["G"] = G;
        }
        return this;
    };

    /**
     * Définit le bleu
     * @param {int} B entre 0 et 255
     * @return this
     */
    this.set_blue = function (B) {
        if (B >= 0 && B <= 255) {
            this.params["B"] = B;
        }
        return this;
    };

    /**
     * Définit le blanc (stripled RGBW)
     * @param {int} W entre 0 et 255
     * @return this
     */
    this.set_white = function (W) {
        if (W >= 0 && W <= 255) {
            this.params["W"] = W;
        }
        return this;
    };

    /**
     * Applique un effet depuis son index
     * @param {int} FX entre 0 et 101
     * @return this
     */
    this.set_effect_index = function (FX) {
        if (FX >= 0 && FX <= 101) {
            this.params["FX"] = FX;
        }
        return this;
    };

    /**
     * Définit la vitesse de l'effet
     * @param {int} SX entre 0 et 255
     * @return this
     */
    this.set_effect_speed = function (SX) {
        if (SX >= 0 && SX <= 255) {
            this.params["SX"] = SX;
        }
        return this;
    };

    /**
     * Définit l'intensité de l'effet
     * @param {int} IX entre 0 et 255
     * @return this
     */
    this.set_effect_intensity = function (IX) {
        if (IX >= 0 && IX <= 255) {
            this.params["IX"] = IX;
        }
        return this;
    };

    /**
     * Définit le FastLED palette
     * @param {int} FP entre 0 et 46
     * @return this
     */
    this.set_FastLED_palette = function (FP) {
        if (FP >= 0 && FP <= 46) {
            this.params["FP"] = FP;
        }
        return this;
    };

    /**
     * Active le mode veilleuse avec la durée par defaut
     * @return this
     */
    this.set_nightlight = function () {
        this.params["ND"] = "ON";
        return this;
    };

    /**
     * Active le mode veilleuse avec la durée définie en minute ( entre 0 et 255)
     * @param {int} NL entre 0 et 255
     * @return this
     */
    this.set_nightlight_duration = function (NL) {
        if (NL >= 0 && NL <= 255) {
            this.params["NL"] = NL;
        }
        return this;
    };

    /**
     * Définit la luminosité cible du mode veileuse
     * @param {int} NT entre 0 et 255
     * @return this
     */
    this.set_nightlight_target_brightness = function (NT) {
        if (NT >= 0 && NT <= 255) {
            this.params["NT"] = NT;
        }
        return this;
    };

    /**
     * Définit le fondu du mode veilleuse
     * 0 : pas de fondu
     * 1 : fondu de luminosité
     * 2 : fondu de la couleur primaire à la secondaire
     * @param {int} NF 0, 1 ou 2
     * @return this
     */
    this.set_nightlight_fade = function (NF) {
        if (in_array(NF, [0, 1, 2])) {
            this.params["NF"] = NF;
        }
        return this;
    };

    /* Advanced*/

    /**
     * Définit la couleur primaire (HEX ou DEC)
     * pour HEX, précédèz d'un "H" exemple : H0000FF
     * @param {string} CL HEX ou DEC
     * @return this
     */
    this.set_primary_color = function (CL) {
        this.params["CL"] = CL;
        return this;
    };

    /**
     * Définit la couleur secondaire (HEX ou DEC)
     * pour HEX, précèdez d'un "H" exemple : H0000FF
     * @param {string} C2 HEX ou DEC
     * @return this
     */
    this.set_secondary_color = function (C2) {
        this.params["C2"] = C2;
        return this;
    };

    /**
     * Définit la couleur tertiaire (HEX ou DEC)
     * pour HEX, précèdez d'un "H" exemple : H0000FF
     * @param {string} C3 HEX ou DEC
     * @return this
     */
    this.set_third_color = function (C3) {
        this.params["C3"] = C3;
        return this;
    };

    /**
     * Définit le rouge de la couleur secondaire
     * @param {int} R2 entre 0 et 255 
     * @return this
     */
    this.set_red_secondary = function (R2) {
        if (R2 >= 0 && R2 <= 255) {
            this.params["R2"] = R2;
        }
        return this;
    };

    /**
     * Définit le vert de la couleur secondaire
     * @param {int} G2 entre 0 et 255 
     * @return this
     */
    this.set_green_secondary = function (G2) {
        if (G2 >= 0 && G2 <= 255) {
            this.params["G2"] = G2;
        }
        return this;
    };

    /**
     * Définit le bleu de la couleur secondaire
     * @param {int} B2 entre 0 et 255 
     * @return this
     */
    this.set_blue_secondary = function (B2) {
        if (B2 >= 0 && B2 <= 255) {
            this.params["B2"] = B2;
        }
        return this;
    };

    /**
     * Définit le blanc de la couleur secondaire (Stripled RGBW)
     * @param {int} W2 entre 0 et 255 
     * @return this
     */
    this.set_white_secondary = function (W2) {
        if (W2 >= 0 && W2 <= 255) {
            this.params["W2"] = W2;
        }
        return this;
    };

    /**
     * Définit la teinte
     * @param {int} HU entre 0 et 65535
     * @return this
     */
    this.set_hue = function (HU) {
        if (HU >= 0 && HU <= 65535) {
            this.params["HU"] = HU;
        }
        return this;
    };

    /**
     * Définit la saturation et la teinte de la couleur primaire
     * @param {int} SA entre 0 et 255
     * @param {int} HU entre 0 et 65535
     * @return this
     */
    this.set_saturation_hue = function (SA, HU) {
        if (SA >= 0 && SA <= 255) {
            this.params["SA"] = SA;
            this.set_hue(HU);
        }
        return this;
    };

    /**
     * Définit la saturation et la teinte de la couleur secondaire
     * /!\ A ne pas utiliser dans la même requete de la définition de la couleur primaire !
     * @param {int} SA entre 0 et 255
     * @param {int} HU entre 0 et 65535
     * @return this
     */
    this.set_saturation_hue_secondary = function (SA, HU) {
        this.params["H2"] = "ON";
        this.set_saturation_hue(SA, HU);
        return this;
    };

    /**
     * Définit une teinte aléatoire
     * @param {bool} SR true/false
     * @return this
     */
    this.set_random_hue = function (SR) {
        this.params["SR"] = (SR ? 1 : 0);
        return this;
    };

    /**
     * Inverse les couleurs primaires et secondaires
     * @return this
     */
    this.set_swap_primary_secondary = function () {
        this.params["SC"] = "ON";
        return this;
    };

    /* Loxone commands*/

    /**
     * Définit la couleur primaire RGB
     * @param {int} LX exemple : 100100100
     * @return this
     */
    this.set_loxone_rgb = function (LX) {
        this.params["LX"] = LX;
        return this;
    };

    /**
     * Définit la luminosité primaire
     * @param {int} LX exemple : 201006500
     * @return this
     */
    this.set_loxone_brightness = function (LX) {
        this.params["LX"] = LX;
        return this;
    };

    /**
     * Définit la couleur secondaire RGB
     * @param {int} LY exemple : 100100100
     * @return this
     */
    this.set_loxone_rgb_seconary = function (LY) {
        this.params["LY"] = LY;
        return this;
    };

    /**
     * Définit la luminosité secondaire
     * @param {int} LY exemple : 201006500
     * @return this
     */
    this.set_loxone_brightness_secondary = function (LY) {
        this.params["LY"] = LY;
        return this;
    };

    /* Notifications*/

    /**
     * Reçoit les notifications UDP
     * @param {bool} RN true/false
     * @return this
     */
    this.set_receive_notification = function (RN) {
        this.params["RN"] = (RN ? 1 : 0);
        return this;
    };

    /**
     * Envoit les notifications UDP
     * @param {bool} SN true/false
     * @return this
     */
    this.set_send_notification = function (SN) {
        this.params["SN"] = (SN ? 1 : 0);
        return this;
    };

    /**
     * Aucune notification pour cette requête 
     * @return this
     */
    this.set_no_notification = function () {
        this.params["NN"] = "ON";
        return this;
    };

    /**
     * Définit la teinte de la notification
     * @param {int} HP entre 0 et 99
     * @return this
     */
    this.set_hue_notification = function (HP) {
        if (HP >= 0 && HP <= 99) {
            this.params["HP"] = HP;
        }
        return this;
    };

    /* Presets*/

    /**
     * Sauvegarde le preset actuel dans un des 16 emplacements disponibles
     * @param {int} PS entre 1 et 16
     * @return this
     */
    this.set_save_preset = function (PS) {
        if (PS >= 1 && PS <= 16) {
            this.params["PS"] = PS;
        }
        return this;
    };

    /**
     * Charge un preset (0=preset par defaut)
     * @param {int} PL entre 0 et 16
     * @return this
     */
    this.set_load_preset = function (PL) {
        if (PL >= 0 && PL <= 16) {
            this.params["PL"] = PL;
        }
        return this;
    };

    /**
     * Cycle des preset
     * 0 : non
     * 1 : oui
     * 2 : switch
     * @param {int} CY
     * @return this
     */
    this.set_preset_cycle = function (CY) {
        if (in_array(CY, [0, 1, 2])) {
            this.params["CY"] = CY;
        }
        return this;
    };

    /**
     * Applique la luminosité des presets
     * @param {bool} PA true/false
     * @return this
     */
    this.set_preset_apply_brightness = function (PA) {
        this.params["PA"] = (PA ? 1 : 0);
        return this;
    };

    /**
     * Définit le premier preset du cycle
     * @param {int} P1 entre 1 et 16
     * @return this
     */
    this.set_preset_first_cycle = function (P1) {
        if (P1 >= 1 && P1 <= 16) {
            this.params["P1"] = P1;
        }
        return this;
    };

    /**
     * Définit le dernier preset du cycle
     * @param {int} P2 entre 1 et 16
     * @return this
     */
    this.set_preset_last_cycle = function (P2) {
        if (P2 >= 1 && P2 <= 16) {
            this.params["P2"] = P2;
        }
        return this;
    };

    /**
     * Définit le temps de chaque preset
     * @param {int} PT entre 50 et 65000
     * @return this
     */
    this.set_preset_cycle_time = function (PT) {
        if (PT >= 50 && PT <= 65000) {
            this.params["PT"] = PT;
        }
        return this;
    };

    /**
     * Définit le temps entre chaque preset
     * @param {int} TT entre 50 et 65000
     * @return this
     */
    this.set_preset_cycle_transition_time = function (TT) {
        if (TT >= 0 && TT <= 65000) {
            this.params["PT"] = TT;
        }
        return this;
    };

    /* Macros*/

    /**
     * Applique une macro
     * @param {int} M entre 0 et 16
     * @return this
     */
    this.set_macro = function (M) {
        if (M >= 0 && M <= 16) {
            this.params["M"] = M;
        }
        return this;
    };

    /* Segments*/

    /**
     * Définit le segment principal
     * @param {int} SM entre 1 et 9
     * @return this
     */
    this.set_main_segment = function (SM) {
        if (SM >= 0 && SM <= 9) {
            this.params["SM"] = SM;
        }
        return this;
    };

    /**
     * Définit quel segment à selectionner
     * @param {int} SS entre 1 et 9
     * @return this
     */
    this.set_select_segment = function (SS) {
        if (SS >= 0 && SS <= 9) {
            this.params["SS"] = SS;
        }
        return this;
    };

    /**
     * Définit si le segment doit être selectionné 
     * @param {int} SV
     * @return this
     */
    this.set_segment_selected = function (SV) {
        if (in_array(SV, [0, 1, 2])) {
            this.params["SV"] = SV;
        }
        return this;
    };

    /**
     * Définit le debut du segment
     * @param {int} S entre 0 et le nombre de leds -1
     * @return this
     */
    this.set_segment_start = function (S) {
        this.params["S"] = S;
        return this;
    };

    /**
     * Définit la fin du segment
     * @param {int} S2 entre 0 et le nombre de leds
     * @return this
     */
    this.set_segment_stop = function (S2) {
        this.params["S2"] = S2;
        return this;
    };

    /**
     * Définit le regroupement du segment 
     * @param {int} GP entre 1 et 255
     * @return this
     */
    this.set_segment_grouping = function (GP) {
        if (GP >= 1 && GP <= 255) {
            this.params["GP"] = GP;
        }
        return this;
    };

    /**
     * Définit l'espacement du segment
     * @param {int} SP entre 1 et 255
     * @return this
     */
    this.set_segment_spacing = function (SP) {
        if (SP >= 0 && SP <= 255) {
            this.params["SP"] = SP;
        }
        return this;
    };

    /**
     * Inverse l'ordre du segment
     * @param {bool} RV true/false
     * @return this
     */
    this.set_segment_reverse = function (RV) {
        this.params["RV"] = (RV ? 1 : 0);
        return this;
    };

    /**
     * Définit la luminosité du segment
     * @param {int} SB entre 0 et 255
     * @return this
     */
    this.set_segment_brightness = function (SB) {
        if (SB >= 0 && SB <= 255) {
            this.params["SB"] = SB;
        }
        return this;
    };

    /* General && Experimental*/

    /**
     * Redémarre WLED
     * @return this
     */
    this.reboot = function () {
        this.params["RB"] = "ON";
        return this;
    };

}