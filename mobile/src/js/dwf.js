/**
 * Librairie JS de dwf
 * 
 * @returns {dwf_js}
 */
$dwf = new (
        function dwf_js() {

            /**
             * Inclut un fichier JS
             * @param {string} src Source du JS
             * @returns {undefined}
             */
            this.include_script = function (src) {
                $("head").append('<script type="text/javascript" src="' + src + '"></script>');
            };

            /**
             * Inclut un fichier CSS
             * @param {string} href Lien du CSS
             * @returns {undefined}
             */
            this.include_link = function (href) {
                $("head").append('<link rel="stylesheet" href="' + href + '" />');
            };

            /**
             * Affiche un message à l'écran de l'utilisateur
             * 
             * @param {string} msg Message à afficher
             * @returns {undefined}
             */
            this.alertify_alert = function (msg) {
                alertify.alert(msg);
            };

            /**
             * Affiche un message à l'écran de l'utilisateur avant redirection
             * 
             * @param {string} msg Message à afficher
             * @param {string} url URL de redirection
             * @returns {undefined}
             */
            this.alertify_alert_redir = function (msg, callback) {
                alertify.alert(msg, callback);
            };

            /**
             * Affiche un message de log à l'écran de l'utilisateur
             * 
             * @param {string} msg Log à afficher
             * @returns {undefined}
             */
            this.log_std = function (msg) {
                alertify.log(msg);
            };

            /**
             * Affiche un message de log à l'écran de l'utilisateur
             * 
             * @param {string} msg Log à afficher
             * @returns {undefined}
             */
            this.log_success = function (msg) {
                alertify.success(msg);
            };

            /**
             * Affiche un message de log à l'écran de l'utilisateur
             * 
             * @param {string} msg Log à afficher
             * @returns {undefined}
             */
            this.log_error = function (msg) {
                alertify.error(msg);
            };

            var fancybox_called = false;
            this.fancybox = function (id, data) {
                if (!fancybox_called) {
                    this.include_link("src/js/fancybox/jquery.fancybox.min.css")
                    this.include_script("src/js/fancybox/jquery.fancybox.min.js")
                    fancybox_called = true;
                }
                $.each(data, function (key, value) {
                    data_caption = (isset(value["caption"]) ? 'data-caption="' + (caption = value["caption"]) + '"' : caption = "")
                    $("#" + id).append('<a data-fancybox="' + id + '" ' + data_caption +
                            ' href="' + value["big"] + '"><img src="' + value["small"] + '" alt="' + caption + '" /></a>');
                });
            }

            var freetile_called = false;

            /**
             * Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "freetile"
             * 
             * @param {string} id id CSS du conteneur freetile
             * @returns {undefined}
             */
            this.freetile = function (id) {
                if (!freetile_called) {
                    this.include_script("src/js/freetile/jquery.freetile.min.js");
                    freetile_called = true;
                }
                $("#" + id).freetile();
            };

            var stalactite_called = false;

            /**
             * Organise dynamiquement les sous éléments d'un conteneur avec la librairie jquery "stalactite"
             * 
             * @param {string} id id CSS du conteneur stalactite
             * @param {int} duration durrée de l'annimation 
             * @returns {undefined}
             */
            this.stalactite = function (id, duration) {
                if (!stalactite_called) {
                    this.include_script("src/js/stalactite/stalactite.js");
                    stalactite_called = true;
                }
                $("#" + id).stalactite({
                    duration: (duration === undefined ? 25 : duration)
                });
            };

            var shuffleLetters_called = false;

            /**
             * Applique l'effet "shuffleLetters" a un élément au chargement de la page
             * 
             * @param {string} id id CSS de l'élément
             * @returns {undefined}
             */
            this.shuffleLetters = function (id) {
                if (!shuffleLetters_called) {
                    this.include_script("src/js/shuffleletters/jquery.shuffleLetters.js");
                    shuffleLetters_called = true;
                }
                $("#" + id).shuffleLetters();
            };


            var datatable_called = false;

            /**
             * Transforme un tableau HTML en Datatable
             * @param {string} id Id CSS du datatable
             * @returns {undefined}
             */
            this.datatable = function (id) {
                if (!datatable_called) {
                    this.include_script("src/js/DataTables/media/js/jquery.dataTables.min.js");
                    this.include_script("src/js/DataTables/media/js/dataTables.bootstrap.js");
                    this.include_link("src/js/DataTables/media/css/dataTables.bootstrap.css");
                    datatable_called = true;
                }
                id = (id === undefined ? "datatable" : id);
                $("#" + id).addClass("display");
                $("#" + id).DataTable({responsive: true, language: {url: 'src/js/DataTables/lang/French.lang.json'}});
            };

            var audio_called = false;

            /**
             * Cette fonction génère le lecteur audio dans une div
             * @param {string} div_id Id CSS de la div qui contiendras le lecteur
             * @param {string} player_id Id CSS du lecteur ("player par default")
             * @param {string} src Source de l'audio (vide par defaut)
             * @returns {undefined}
             */
            this.audio = function (div_id, player_id, src) {
                if (!audio_called) {
                    this.include_script("src/js/audio/audio.js");
                    this.include_link("src/js/audio/audio.css");
                    audio_called = true;
                }
                player_id = (player_id === undefined ? "player" : player_id);
                $("#" + div_id).append('<div class="player_ctrl">' +
                        '<audio id="' + player_id + '" src="' + (src === undefined ? "" : src) + '" ></audio> ' +
                        '<input title="Ligne de temps de la musique" id="' + player_id + '_player_ctrl_timeline" type="range" min="0" max="0" value="0" step="1"/>' +
                        '<button class="btn btn-xs btn-default" id="' + player_id + '_player_ctrl_play" type="button"><span class="glyphicon glyphicon-play"><span class="sr-only">Lecture</span></span></button>' +
                        '<button class="btn btn-xs btn-default" id="' + player_id + '_player_ctrl_stop" type="button"><span class="glyphicon glyphicon-pause"><span class="sr-only">Pause</span></span></button>' +
                        '<button class="btn btn-xs btn-default" id="' + player_id + '_player_ctrl_mute" type="button"><span class="glyphicon glyphicon-volume-off"><span class="sr-only">Muet</span></span></button>' +
                        '<button class="btn btn-xs btn-default" id="' + player_id + '_player_ctrl_volume_down" type="range"><span class="glyphicon glyphicon-volume-down"><span class="sr-only">Diminuer le volume</span></span></button>' +
                        '<button class="btn btn-xs btn-default" id="' + player_id + '_player_ctrl_volume_up" type="range"><span class="glyphicon glyphicon-volume-up"><span class="sr-only">Augmenter le volume</span></span></button>' +
                        '<div class="player_ctrl_volume_div">' +
                        '<input id="' + player_id + '_player_ctrl_volume" class="player_ctrl_volume" type="range" title="volume" min="0" max="10" value="10" oninput="$(\'#' + player_id + '_player_ctrl_volume_affichage\').text($(\'#' + player_id + '_player_ctrl_volume\').val());" />' +
                        '<p class="player_ctrl_volume_affichage" id="' + player_id + '_player_ctrl_volume_affichage">10</p>' +
                        '</div>' +
                        '</div>');
                audio(player_id);
            };

            /**
             * Cette fonction permet de gérer la liste des musiques.
             * @param {string} div_id Id CSS de la div qui contiendras la liste
             * @param {string} player_id Id CSS du lecteur associé
             * @param {array} playlist Tableau à deux dimensions contenant le titre et la source de la musique.
             * Forme du tableau : [{"src":"source", "titre:"titre"},...];
             * @returns {undefined}
             */
            this.playlist = function (div_id, player_id, playlist) {
                var html = '<ul class="playlist">';
                for (var i = 0; i < playlist.length; i++) {
                    html += '<li><a href="#" data-id="' + player_id + '" data-src="' + playlist[i]["src"] + '"><span class="glyphicon glyphicon-music"></span>' + playlist[i]["titre"] + '</a></li>';
                }
                html += '</ul>';
                $("#" + div_id).append(html);

            };

            var ckeditor_called = false;

            /**
             * Applique un editeur CKEditor (WYSIWYG) à un textarea
             * @param {string} id Id du textarea
             * @returns {undefined}
             */
            this.ckeditor = function (id) {
                if (!ckeditor_called) {
                    this.include_script("src/js/ckeditor/ckeditor.js");
                    ckeditor_called = true;
                }
                CKEDITOR.replace(id);
            };

            /**
             * Filtre de sécurité à utiliser lors de l'execution d'un formulaire pour filter les balises utilisées dans CKEditor ( protection XSS )
             * @param {string} str Retour du CKEditor
             * @returns {string} HTML filtré
             */
            this.ckeditor_parse = function (str) {
                var tags = "<h1></h1><h2></h2><h3></h3><h4></h4><h5></h5><h6></h6><p></p><a></a><span></span><small></small><big></big><strong><em></em><u></u><s></s></strong><quote></quote><img><img/><sup></sup><sub></sub><div></div><ul></ul><ol></ol><li></li><dl></dl><dt></dt><dd></dd><time></time><br /><hr /><table></table><thead></thead><tbody></tbody><tfoot></tfoot><tr></tr><th></th><td></td><caption></caption><figure></figure><figcaption></figcaption>";
                var _str;
                while (str != (_str = strip_tags(str, tags))) {
                    str = _str;
                }
                return _str;
            };

            var flot_called = false;

            /**
             * Affiche un graphique
             * @param {string} id Identifiant CSS de la div qui deviendra le graphique
             * @param {array} data [{"label": "test", "data": [[0, 0], [1, 1], [2, 2], [3, 3]]}]
             * @param {array} tricks [[x,"subtitution"],[x2,"subtitution2"]]
             * @returns {undefined}
             */
            this.flot = function (id, data, tricks) {
                if (!flot_called) {
                    this.include_script("src/js/flot/jquery.flot.js");
                    flot_called = true;
                }
                var lim = {
                    "xmin": 2147483647,
                    "xmax": -2147483647,
                    "ymin": 2147483647,
                    "ymax": -2147483647
                };
                for (var i = 0; i < data.length; i++) {
                    for (j = 0; j < data[i]["data"].length; j++) {
                        if (data[i]["data"][j][0] > lim["xmax"]) {
                            lim["xmax"] = data[i]["data"][j][0];
                        }
                        if (data[i]["data"][j][0] < lim["xmin"]) {
                            lim["xmin"] = data[i]["data"][j][0];
                        }
                        if (data[i]["data"][j][1] > lim["ymax"]) {
                            lim["ymax"] = data[i]["data"][j][1];
                        }
                        if (data[i]["data"][j][1] < lim["ymin"]) {
                            lim["ymin"] = data[i]["data"][j][1];
                        }
                    }
                }
                var options = {
                    series: {
                        lines: {show: true},
                        points: {show: true}
                    },
                    xaxis: {
                        show: true,
                        min: lim["xmin"],
                        max: lim["xmax"]
                    },
                    yaxis: {
                        show: true,
                        min: lim["ymin"],
                        max: lim["ymax"]}
                };
                if (tricks.length > 0) {
                    options["ticks"] = tricks;
                }
                $("#" + id).addClass("plot");
                $("#" + id).plot(data, options);
            };

            var cytoscape_called = false;

            /**
             * Affiche un graphe d'analyse et de visualisation (jquery cytoscape)
             * Requirer une régle CSS sur l'ID CSS
             * @param {string} id ID CSS
             * @param {array} data Données du graphe exemple : {"A":["B","C"], "B":["C"], "C":["A"]};
             */
            this.cytoscape = function (id, data) {
                if (!cytoscape_called) {
                    this.include_script("src/js/cytoscape/cytoscape.min.js");
                    cytoscape_called = true;
                }
                cytoscape({
                    container: $("#" + id),
                    elements: json_decode("[" + strtr($dwf.mk_graf(data) + "___", {",___": " "}) + "]"),
                    style: [
                        {selector: 'node', css: {'content': 'data(id)', 'width': '25px', 'background-color': 'lightblue'}},
                        {selector: 'edge', css: {'target-arrow-shape': 'triangle', 'line-color': 'lightgray', 'target-arrow-color': 'lightgray', 'curve-style': 'bezier'}}
                    ],
                    layout: {name: 'circle'}
                });
            };

            /**
             * Fonction recurcive qui formate les données pour le graphe (jquery cytoscape)
             * @param {array} data Données du graphe exemple : {"A":["B","C"], "B":["C"], "C":["A"]};
             * @param {string} kp clé parente
             * @return {string} cytoscape.elements
             */
            this.mk_graf = function (data, kp) {
                var json = "";
                $.each(data, function (key, value) {
                    if (is_array(value)) {
                        json += '{"data": { "id": "' + addslashes(key) + '" }},';
                        json += $dwf.mk_graf(value, key);
                    } else {
                        json += '{"data": { "id": "' + addslashes((is_int(key) == false ? key + " : " + value : value)) + '" }},';
                    }
                    if (!empty(kp) && !is_int(kp)) {
                        json += '{"data": { "source": "' + addslashes(kp) + '", "target": "' + addslashes((is_array(value) ? key : value)) + '" }},';
                    }
                });
                return json;
            };

            var leaflet_called = false;

            /**
             * Affiche une carte exploitant OSM (OpenStreetMap)
             * @param {string} id Identifiant CSS de la div qui deviendra le leaflet
             * @param {array} view Tableau indiqant la vue initiale de la carte : ["x" : 0, "y" : 0, "zoom" : 13]
             * @param {array} markers Marqueurs de la cartes : [["x":"","y":"","desc":""],["x":"","y":"","desc":""]]
             * @param {array} circles Cercles de la cartes : [["x" : x, "y" : y, "r" : rayon, "desc" : desc, "color" : color, "opacity" : opacity]]
             * @param {array} polygons Polygones de la carte : ["data" : [["x":coordX,"y":coordY], ["x":coordX,"y":coordY]], "desc":desc]
             * @returns {undefined}
             */
            this.leaflet = function (id, view, markers, circles, polygons) {
                if (!leaflet_called) {
                    this.include_link("src/js/leaflet/leaflet.css");
                    this.include_link("src/js/leaflet/leaflet-routing/leaflet-routing-machine.css");
                    this.include_script("src/js/leaflet/leaflet.js");
                    this.include_script("src/js/leaflet/leaflet-routing/leaflet-routing-machine.js");
                    leaflet_called = true;
                }
                map[id] = L.map(id).setView([view["x"], view["y"]], view["zoom"]);
                L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: ''}).addTo(map[id]);

                for (i = 0; i < markers.lenght; i++) {
                    L.marker([markers[i]["x"], markers[i]["y"]]).bindPopup(markers[i]["desc"]).addTo(map[id]);
                }
                for (i = 0; i < circles.lenght; i++) {
                    L.circle([circles[i]["x"], circles[i]["y"], circles[i]["r"]], {color: circles[i]["color"], fillColor: circles[i]["color"], fillOpacity: circles[i]["opacity"]}).bindPopup(circles[i]["desc"]).addTo(map[id]);
                }
                for (i = 0; i < circles.lenght; i++) {
                    L.polygon(polygons[i]["data"]).bindPopup(polygons["desc"]).addTo(map[id]);
                }
            };

            var modal_called = false;

            /**
             * Applique l'ouverture d'une modal a un élément
             * @param {string} id Identifiant CSS
             * @param {string} titre Titre de la modal
             * @param {string} data Contenu de la modale (encodé en base64 !)
             * @returns {undefined}
             */
            this.modal = function (id, titre, data) {
                if (!modal_called) {
                    this.include_link("src/js/modal/modal-window.css");
                    this.include_script("src/js/modal/modal-window.js");
                    $("body").append('<div role="dialog" aria-hidden="true" id="modal" class="modal-content" style="display: none;"><div></div><button id="modalCloseButton" class="modalCloseButton btn btn-default" title="Fermer la fenêtre"><span class="glyphicon glyphicon-remove"></span></button ></div><div tabindex="-1" id="modalOverlay" style="display: none;"></div>');
                    modal_called = true;
                }
                $("#" + id).attr("data-titre", titre);
                $("#" + id).attr("data-data", data);
                $("#" + id).click(function () {
                    $("#modal>div").html(stripslashes("<h1>" + $(this).attr("data-titre")) + "</h1><hr />" + base64_decode($(this).attr("data-data")));
                    showModal($('#modal'));
                });
            };

            /**
             * Affiche le carousel/slide de bootstrap
             * @param {string} id ID CSS de la DIV conteneur
             * @param {type} data donées du slide sous la forme : [{"img":"chamain/img.png","alt":"alternative","caption":"HTML"},{...}]
             * @returns {undefined}
             */
            this.slide = function (id, data) {
                $("#" + id).addClass("carousel").addClass("slide").attr("data-ride", "carousel")
                        .append('<ol class="carousel-indicators"></ol><div class="carousel-inner" role="listbox"></div><a class="left carousel-control" href="#' + k + '" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Précédent</span></a><a class="right carousel-control" href="#' + k + '" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Suivant</span></a><a class="center carousel-pause" href="#' + k + '" role="button" data-slide="pause"><span class="glyphicon glyphicon-pause" aria-hidden="true"></span><span class="sr-only">Pause</span></a>');
                $.each(data, function (k, v) {
                    $("#" + id + " > ol.carousel-indicators").append('<li data-target="#' + id + '" data-slide-to="' + k + '><a href="#"><span class="sr-only">slide ' + k + '</span></a></li>');
                    $("#" + id + " > .carousel-inner").append('<div class="item"><img src="' + v.src + '" alt="' + v.alt + '" /><div class="carousel-caption">' + v.caption + '</div></div>');
                });
                $("#" + id + " > ol.carousel-indicators > li:first").addClass("active");
                $("#" + id + " > .carousel-inner > .item:first").addClass("active");
            };

            /**
             * Requiere div.reveal>div.slide, permet de crer un diaporama avec la librairie reveal, Il n'est pas recomendé d'avoir plusieurs diaporamas sur la même page !
             * @returns {undefined}
             */
            this.reveal = function () {
                this.include_link("src/js/reveal/css/reveal.css");
                this.include_link("src/js/reveal/css/reveal_correctif.css");
                this.include_link("src/js/reveal/css/theme/white.css");
                this.include_script("src/js/reveal/lib/js/classList.js");
                this.include_script("src/js/reveal/lib/js/head.min.js");
                this.include_script("src/js/reveal/js/reveal.js");
                Reveal.initialize({
                    controls: true,
                    progress: false,
                    history: true,
                    center: true,
                    transition: 'slide',
                    dependencies: [
                        {src: 'src/js/reveal/lib/js/classList.js', condition: function () {
                                return !document.body.classList;
                            }},
                        {src: 'src/js/reveal/plugin/markdown/marked.js', condition: function () {
                                return !!document.querySelector('[data-markdown]');
                            }},
                        {src: 'src/js/reveal/plugin/markdown/markdown.js', condition: function () {
                                return !!document.querySelector('[data-markdown]');
                            }},
                        {src: 'src/js/reveal/plugin/highlight/highlight.js', async: true, callback: function () {
                                hljs.initHighlightingOnLoad();
                            }},
                        {src: 'src/js/reveal/plugin/zoom-js/zoom.js', async: true},
                        {src: 'src/js/reveal/plugin/notes/notes.js', async: true}
                    ]
                });
            };

            var syntaxhighlighter_called = false;

            /**
             * Cette fonction agis sur les balises "code" de la page
             * Affiche du code formaté et stylisé par la librairie SyntaxHightlighter
             * @returns {undefined}
             */
            this.syntaxhighlighter = function (bruch = "js") {
                if (!syntaxhighlighter_called) {
                    this.include_script("src/js/syntaxhighlighter/scripts/shCore.js");
                    this.include_script("src/js/syntaxhighlighter/scripts/shBrushJscript.js");
                    this.include_link("src/js/syntaxhighlighter/styles/shCoreDefault.css");
                    this.include_link("src/js/syntaxhighlighter/styles/shThemeDefault.css");
                    this.include_link("src/css/syntaxhighlighter.css");
                    SyntaxHighlighter.config.tagName = "code";
                    SyntaxHighlighter.defaults['toolbar'] = false;
                    SyntaxHighlighter.all();
                    syntaxhighlighter_called = true;
                }
                $("code").addClass("bruch: " + bruch);
            };

            var mime_content_type_called = false;

            /**
             * Retourne le mime du fichier passé en parametre
             * @param {string} file Chemain d'accés au fichier
             * @returns {string} Mine du fichier
             */
            this.mime_content_type = function (file) {
                if (!mime_content_type_called) {
                    this.include_script("src/js/filetypes/filetypes.min.js");
                    mime_content_type_called = true;
                }
                return Stretchr.Filetypes.mimeFor(file);
            };

            var videojs_called = false;

            /**
             * Affiche une vidéo avec un player accessible
             * @param {string} id Id CSS de la balise "video"
             * @param {string} src Src de la vidéo
             * @param {int} width largeur de la vidéo (600 par defaut)
             * @returns {undefined}
             */
            this.video_js = function (id, src, width) {
                if (!videojs_called) {
                    this.include_link("src/js/videojs/video-js.min.css");
                    this.include_script("src/js/videojs/video.min.js");
                    videojs_called = true;
                }
                videojs(id, {}, function () { });
                $("#" + id).addClass("video-js vjs-default-skin");
                $("#" + id).attr("controls", "true");
                $("#" + id).attr("width", (!isset(width) || empty(width) ? "600" : width));
                $("#" + id).html('<source src="' + src + '" type="' + this.mime_content_type(src) + '">');
            };

            var vticker_called = false;

            /**
             * Affiche un vTicker dans une div
             * @param {string} id Id CSS de la div qui contiendra le vTicker
             * @param {array} data liste des textes a afficher. format : ["hello","world","are you fine today ?"]
             * @returns {undefined}
             */
            this.vticker = function (id, data) {
                if (!videojs_called) {
                    this.include_script("src/js/vticker/vticker.min.js");
                    videojs_called = true;
                }
                $("#" + id).html("<ul></ul>");
                $.each(data, function (key, value) {
                    $("#" + id + ">ul").append("<li>" + value + "</li>");
                });
                $("#" + id).vTicker({speed: 700, pause: 4000, showItems: 1, mousePause: true, height: 0, animate: true, animation: "slide", margin: 0, padding: 0, startPaused: false, direction: "up"});

            };

            var dwf_form_called = false;
            /**
             * Retourne un objet de gestion de formulaire (require une balise form avec un id)
             * @param {string} id Id CSS de la balise form
             * @returns {dwf_form}
             */
            this.form = function (id) {
                if (!dwf_form_called) {
                    this.include_script("src/js/dwf/form.js");
                    dwf_form_called = true;
                }
                return new dwf_form(id);
            };

            var dwf_time_called = false;

            /**
             * Retourne un objet contenant des fonctions basiques qui gère le temps
             * 
             * @returns {dwf_time}
             */
            this.time = function () {
                if (dwf_time_called === false) {
                    this.include_script("src/js/dwf/time.js");
                    dwf_time_called = new dwf_time();
                }
                return dwf_time_called;
            };

            var dwf_math_called = false;

            /**
             * Retourne un objet contenant des fonctions mathématiques
             * @returns {dwf_math}
             */
            this.math = function () {
                if (dwf_math_called === false) {
                    this.include_script("src/js/dwf/math.js");
                    dwf_math_called = new dwf_math();
                }
                return dwf_math_called;
            };

            var dwf_trad_called = false;

            /**
             * Retourne objet de traduction
             * @param {string} lang Langue de traduction
             * @returns {dwf_trad}
             */
            this.trad = function (lang) {
                if (dwf_trad_called === false) {
                    this.include_script("src/js/dwf/trad.js");
                    dwf_trad_called = new dwf_trad(lang);
                }
                return dwf_trad_called;
            };

            var dwf_cordova_called = false;

            /**
             * Retourne un objet simplifiant l'utilisation de l'API de cordova
             * @returns {dwf_cordova}
             */
            this.cordova = function () {
                if (dwf_cordova_called === false) {
                    this.include_script("src/js/dwf/cordova.js");
                    dwf_cordova_called = new dwf_cordova();
                }
                return dwf_cordova_called;
            };

            $_get = {};
            window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
                $_get[key] = value;
            });

        }
);
