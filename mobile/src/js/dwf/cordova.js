function dwf_cordova() {
    window.addEventListener("batterystatus", function (baterie) {
        sessionStorage.cordova.batetie.level = baterie.level;
        sessionStorage.cordova.batetie.isPlugged = baterie.isPlugged;
    }, false);

    document.addEventListener("deviceready", function () {
        sessionStorage.cordova.device = device;
    }, false);


    /**
     * Retourne l'état de la baterie ("level" le niveau de la baterie et "isPlugged" sur secteur ou non.
     * exemple de retour : {level : 100, isPlugged : true}
     * @returns {dwf_cordova.get_baterie}
     */
    this.get_baterie = function () {
        return {level: sessionStorage.cordova.batetie.level, isPlugged: sessionStorage.cordova.batetie.isPlugged};
    };

    /**
     * Retourne la base64 de l'image capté par la camera (data:image/jpeg;base64,)
     * @param {string} direction direction de la camnera ( BACK/FRONT , BACK par defaut
     * @returns {sessionStorage.cordova.camera|Storage.cordova.camera}
     */
    this.get_camera = function (direction) {
        navigator.camera.getPicture(function (camera) {
            sessionStorage.cordova.camera = camera;
        }, function () {
            return false;
        }, {destinationType: DATA_URL, cameraDirection: (!isset(direction) ? "BACK" : direction)});
        return sessionStorage.cordova.camera;
    };

    /**
     * Retourne la liste de contact
     * doc: https://www.npmjs.com/package/cordova-plugin-contacts
     * @returns {Storage.cordova.contacts|sessionStorage.cordova.contacts}
     */
    this.get_contacts = function () {
        navigator.contacts.find("*", function (contacts) {
            sessionStorage.cordova.contacts = contacts;
        }, function () {
            return false;
        }, {multiple: true});
        return sessionStorage.cordova.contacts;
    };

    /**
     * retourne les information du téléphone
     * {cordova,model,platform,uuid,version,manufacturer,isVirtual,serial}     
     * @returns {Storage.cordova.device|device}
     */
    this.get_device = function () {
        return sessionStorage.cordova.device;
    };

    /**
     * Retourne l'acceleration courante du gyroscope 
     * {x,y,z,timestamp}
     * @returns {sessionStorage.cordova.gyroscope|Storage.cordova.gyroscope}
     */
    this.get_gyroscope = function () {
        navigator.accelerometer.getCurrentAcceleration(function (gyroscope) {
            sessionStorage.cordova.gyroscope = {x: gyroscope.x, y: gyroscope.y, z: gyroscope.z, timestamp: gyroscope.timestamp};
        }, function () {
            return false;
        });
        return sessionStorage.cordova.gyroscope;
    };

    /**
     * Retourne l'orientation de l'appareil de 0 a 359.99 degré ( 0 est le nord )
     * @returns {Storage.cordova.boussole|boussole.magneticHeading}
     */
    this.get_boussole = function () {
        navigator.compass.getCurrentHeading(function (boussole) {
            sessionStorage.cordova.boussole = boussole.magneticHeading;
        }, function () {
            return false;
        });
        return sessionStorage.cordova.boussole;
    };

    /**
     * retourne true si l'appareil est connecté a internet ( false si non )
     * @returns {Boolean}
     */
    this.is_connected_web = function () {
        return (navigator.connection.type != Connection.NONE);
    };
    /**
     * Fait vibrer l'apareille durant une sertaine duré (en ms) ou a un rithme défini
     * @param {type} data exemple : 1000 ou [1000,1000,3000,1000,5000] (1 seconde de vibration, 1 seconde d'attente puis 3 secondes de vibrations ...)
     * @returns {undefined}
     */
    this.vibration = function (data) {
        navigator.vibrate(data);
    };
}