<?php

class docPHP_natives {

    public static function get_methods() {
        return get_class_methods(__CLASS__);
    }

    public function __construct() {
        ?>
        <p>
            Voici quelques classes natives de DWF et quelques exemples d'utilisation, pour plus d'informations, chaque classe et fonction sont commentées (document technique) <br />
            si une classe/fonction a mal été commentée ( ou pas du tout commentée), merci de nous le signaler. <br />
            (il s'agit de quelques unes des classes les plus utiles du framework, le framework compte plus de <?= count(glob("../../dwf/class/*.class.php")); ?> classes natives)
        </p>
        <?php
        $functions = get_class_methods(__CLASS__);
        sort($functions);
        $ul = [];
        foreach ($functions as $n) {
            if (!in_array($n, ["get_methods", "__construct"])) {
                $ul[] = html_structures::a_link("index.php?page=web&doc=classes_natives&native=$n", strtr(ucfirst($n), array("_" => " ")));
            }
        }
        echo html_structures::ul($ul);
    }

    public static function admin_controle() {
        ?>
        <h3>admin_controle</h3>
        <p>admin_controle est une classe permettant de créer une interface d'administration "user-friendly", <br />
            Cette classe est destinée à être utilisée dans une partie "administration" du site. <br />
            Cette classe affiche l'administration d'une table de la base de données, <br />
            elle a besoin de l'entité de cette table pour fonctionner et sait gérer les relations entre les tables.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//affiche l\'interface d\'administration de la table "rang"\n'
                . 'new admin_controle("rang");\n'
                . 'echo "<hr />"\n'
                . '//affiche l\'interface d\'administration de la table "user" avec la relation user.rang = rang.nom\n'
                . 'new admin_controle("user", array("rang"=>"nom"));\n'
                . '?>');
        ?>
        <p>
            Si l'entité contient un champ "array", ce champ n’apparaitra pas dans les datatables. <br />
            Dans les formulaires, les données de ce champ seront accessible en JSON dans un input de type hidden. <br />
            Vous devrez créer une interface en JavaScript pour administrer ce champ à votre convenance (en manipulant la chaine JSON). 
        </p>
        <?php
    }

    public static function application() {
        ?>
        <p>Cette classe fait office de contrôleur et layout pour l'application, <br />
            elle fait la liaison entre les routes et les pages (cf configuration et pages) <br />
            et met à disposition quelques outils :</p>
        <ul>
            <li>Instancie la connexion à la base de données par un objet bdd accessible via application::$_bdd (cf bdd)</li>
            <li>Permet d'utiliser des méthodes évènementielles à partir de application::event() (cf : méthodes évènementielles)</li>
            <li>application::get_url() permet de recuperer l'url courrante</li>
            <li>application::get_url(["var_get"]) permet de recuperer l'url courrante en supprimant les verrables GET renseigné</li>
        </ul>
        <?php
    }

    public static function audio() {
        ?>
        <p>Cette classe permet de générer un lecteur audio.</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Créé un lecteur avec une seule source\n'
                . 'new audio($src="./files/musiques/GM-The Search.mp3", $id="player");\n\n'
                . '//Créé un lecteur avec une playlist\n'
                . '(new audio("", "player2"))->playlit([\n'
                . '    ["src"=>"./files/musiques/GM-The Search.mp3", "titre"=>"InYourDreams - The Search"],\n'
                . '    ["src"=>"./files/musiques/IYD-New World.mp3", "titre"=>"InYourDreams - New World"],\n'
                . ']);\n'
                . '?>');
        ?>
        <div class="row">
            <div class="col-sm-6">
                <?php
                new audio("./files/musiques/GM-The Search.mp3");
                ?>
            </div>
            <div class="col-sm-6">
                <?php
                (new audio("", "player2"))->playlist([
                    ["src" => "./files/musiques/GM-The Search.mp3", "titre" => "InYourDreams - The Search"],
                    ["src" => "./files/musiques/IYD-New World.mp3", "titre" => "InYourDreams - New World"]
                ]);
                ?>
            </div>
        </div>
        <p>Crédits : <a href="https://inyourdreams.newgrounds.com/" target="_blank">InYourDreams (Newgrounds)</a></p>
        <?php
    }

    public static function auth() {
        ?>
        <p>
            Auth est la classe qui gère l'authentification des utilisateurs, <br />
            il prend en paramètres : le nom de la table/entité utilisateur ( dans cette documentation : 'user'), le nom du champ de login ('login'), le nom du champ de mot de passe ('psw'). <br />
            auth utilise deux variables de sessions accessibles via la classe "session" <br />
            (session::set_auth(),session::get_auth(),session::set_user() et session::get_user()) <br />
            lorsque l'utilisateur est authentifié, session::get_auth() retourne true et session::get_user() contient l'identifiant de l'utilisateur (id de la base de données) <br />
            sinon session::get_auth() et session::get_user() retourne false et auth affiche un formulaire d'authentification.

        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//affiche le formulaire d\'authentification si non authentifié\n'
                . 'new auth("user", "login", "psw");\n\n'
                . '//version avec une sécurité (token)\n'
                . 'new auth("user", "login", "psw", true);\n'
                . '?>');
    }

    /* public static function ban_ip() {

      }

      public static function bbParser() {

      }

      public static function bdd() {

      } */

    public static function bootstrap_theme() {
        ?>
        <p>Cette classe permet de gèrer les thèmes de bootswatch,<br />
            le thème par défaut peut être défini dans <em>config.class.php</em></p>        
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Affiche une interface (modal) permettant à l\'utilisateur de choisir un thème\n'
                . 'echo bootstrap_theme::user_custom();\n\n'
                . '//Permet à l\'utilisateur de passer du thème par defaut à un autre prédéfini et inversement.\n'
                . '//(Généralement utilisé pour proposer un thème clair et un thème sombre)\n'
                . 'echo bootstrap_theme::switch_theme($theme = "darkly", $labels = ["Thème clair", "Thème sombre"])\n'
                . '?>');
    }

    public static function cache() {
        ?>
        <p>Cette classe permet de gérer une mise en cache ( côté serveur ) <br />
            utilise session::set_val("cache",[ ]) et session::get_val("cache")</p>        
        <?php
        js::monaco_highlighter('<?php\n'
                . 'if ($contenu = cache::get("ma_cle")){\n'
                . '    echo $contenu;\n'
                . '}else{\n'
                . '    //Fonction longue à éxecuter\n'
                . '    echo ($contenu = fonction_longue_a_executer()); \n'
                . '    //Stocke le résultat de la fonction longue en cache pour 5 minutes\n'
                . '    cache::set("ma_cle", $contenu, 600);\n'
                . '}\n'
                . '//Supprimer une valeur dans le cache \n'
                . 'cache::del("ma_cle");\n'
                . '//Supprimer toutes les valeurs dans le cache \n'
                . 'cache::del();\n'
                . '?>'
        );
    }

    public static function captcha() {
        ?>
        <p>Cette classe génére un captcha, <br />
            Utilise la classe espeak pour l'accessibilité, <br />
            requiert donc espeak installé sur le serveur. <br />
            cette classe peut être appellée depuis form->captcha()
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$form = new form();\n'
                . '$form->captchat();\n\n'
                . '//une instance de espeak peut être passée en paramètre pour personnaliser la synthèse vocale\n'
                . '$form->captchat((new espeak("espeak-ng"))->set_voice("french-mbrola-4"));\n'
                . '$form->submit("btn-primary"));\n'
                . 'echo $form->render();\n'
                . 'if (isset($_POST["captcha"])){\n'
                . '    if(form::check_captcha()){\n'
                . '        //captcha success\n'
                . '    }else{\n'
                . '        //captcha fail !\n'
                . '    }\n'
                . '}\n'
                . '?>'
        );
        ?>Resultat : <br />
        <form class="" action="#" method="post" onsubmit="function (e) {
                            e.preventDefault();
                            return false;
                        }">
            <div id="captcha_a783458949484d21d2516108ba2e47b34ead4bef">
                <img src=
                     "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD//gA7Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBxdWFsaXR5ID0gOTAK/9sAQwADAgIDAgIDAwMDBAMDBAUIBQUEBAUKBwcGCAwKDAwLCgsLDQ4SEA0OEQ4LCxAWEBETFBUVFQwPFxgWFBgSFBUU/9sAQwEDBAQFBAUJBQUJFA0LDRQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQU/8AAEQgAKACWAwERAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh3uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh3iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A/KqgAoAKACgDe8L+Fn165j8wskEkgijC/flkJwqJ6kn/AArz8VivYrlgry/JH1OUZRSxUJYvH1PZYeO8m0rvsm9PV/L0+xYf+Cfr3ui6cPF3xC0zwRcTqDaaSbdZmBPZi0seW55C5+tcNCo6Xv1Pie99z854g8QoYyssLl1D/ZqWkel/72zevnr1erZ88fH/APZk8Xfs9anCutRx32i3blLPWLTmGYjnaw6o+Odp684JwcetSrKpo9GLK85w+aRfJpNbxf5rujyOug946HwLrkOh69byyeHdK8SSPIix2urecYt2cAERyJkHIznP4VhWXuNuTSXb+rnBjaTqUm/aSgkm242v+Kb+6x9F/G/wF8NPBni6Lwv448NnwD4lubCO9TWPBd5LeaYZpyQWuLW4BeOKNkJCQHO0tgN8oHPSnVlFta+p8tluKzDEUXXws/aQTa5Zq0rLa0lo209XLr8zwnx98JNa8Bafp+sO1vrHhbU2ZdN8Q6axe0uyuQygkBkcEMCkiq2VbggZrpp1VPTZ9j6XCZhSxbdOzjUjvF7r/Nea7o4mtj0woAKACgAoAKACgAoAKACgAoA1NG0Q3+65uH+z6fDzLM3f/ZX1P+fQHjr4j2fuQ1k9l/mfR5TlDxt8TiHyUIayk/yj3b/Dzdk/fP2MLG18d/tP+EYJrcLpmlia9gtz2eKJmjY+4fafw/GuSVH2MFzayk9X+P6HyXHudyxWAdLDx5KKtGMey6t+bt8ttdW+S/a98baj42/aI8bSX87yR6dqMumW0RYlIo4GMYCjtkqWPuxrtw8OWF+r/pHkZBhoYfL6XKtZK7876/ke0/se6pJ8c/hT8QPgx4hla8tYtOOp6NLN87WTqwU7c9g7RkDtlh0NcuIh7OalD1+a/wA/8+583n9L+zcXRzKgrNuz8/8Ah1e585eG/g9e+I/hv4s8XrrOlWMfhyWOKfTLu42XUxY4yi4554GTyQQOldMsQlOMUrp/r/WvkfV1szhRxVLDKDl7RXTW39dX2Ri/DLTW1j4j+FbBRua51W1hA9d0qj+taV9aUl5M68wly4Os/wC7L8mfVf7R1nB4z/4KEeH9JuoIr6yS90q3mt5kDxyRjZI6Mp4KkMwIPqa4YTcKM352++yPh8qqSw2QVqsdH71vnZFb9r/R9U/Ze+N7a34GWDSPD3i3TyLnTBAj2NztO24geAjaUYFCRj/lo2MVdGMakXCXTby/r/I1yJ084wfsMVdypPR31V9mnvdfdojzPwPcfDv44+KtO8PXPw41DRPE+rTiKG48IaoI7QyEhRm2njk8uJUy7kSMcoSNob5dZqdGLlGV12f+e56+KjjcroyrwxClCPSau/8AwJWbd9FdJa+R1vxc/Zz+EngH4pxfDqDxj4nsvEM0lqEu7zT4bqzTzgoEbGNkfcSwfcFwq/LhidwzVeo4uSSaX+VzhwecZliMLLGeyjKCvom09Nb63Vult7no/ir/AIJo6bey6jZ+CPiNbXeuacsf2nStVjUtEXXcnmNES0QYAlcxnI6Uo4md/es/6+Z5lDi6rG0sVR917NXXrvv96Pm74j/sm/FP4Xi9m1Xwpd3Wm2gZ5NS01Tc24QdXLLyq45ywGO9dMcRCWj09f89j6zCZ9gMY1GFS0n0ej/y/E8x8PeHdU8Waxb6Voun3OqalcEiK1tIjJI+FLMQB2CgsT0ABJ4FbykoJyex7VatTw9N1asrRW7ZoeNfCkPg7U4NOXWrDWbsQBrz+zXMkVrPvYGHzcbZSAFJeMsnzYDHBqKc/aR5rWRhhcQ8VB1ORxV9L7td7dPR69epz9anaFABQBPZWM+oTiG2iaWQjOF7D1PpWdSpClHmm7I7MJg8RjqnscNByl5fm+y9To49M07wynm6k63l7tylonKg+/wD9f34NeW61bFvlorlj3Pu4ZflvD8fa5lJVa1tKa2T8/wDN6b2TaRkaz4hutaKrKVjhT7sUfC/X3NdtDDU6GsdX3Pmc1zzF5taNW0YLaK29fN/0j2v9hPxLbeGf2nPCj3kywQXouLHexwN8kLiMfi4RR7kUsVG8E+z/AOB+p+acTUXWy2bj9lp/p+pU/bQ+HOp+Af2g/Fkt3bSJY6zeSapZ3JUhJlmO9sHuVYsp+lPDTThyt6ovh3FwxOApwT96Cs16bfgep/8ABMbRLuf4teKtXWJ/7OttBe2lmx8okknhZFJ9SInP/AajEyWkTyeL6sVhqdH7Tlf5JNfqed/Bz9nVP2lNb+J99p2urpb6NvvLK2Ft5v2syPMUXO4bV/dgE8n5xxxUupKjSgkun5I7sZmssnw+FhKF+ZJPW1rJX+ev4HI/sraU2sftGfDu3VdxXWYLgj2ibzD+iGt8S2qTt5fmj0s9n7PLazXa33u36nvtpKfEX/BTNmOJUg1l19QBFbEfzWuRW+r+r/X/AIB8q7U+GfVf+3HcftMa0n7RPwo+KlqkUZ1/4Y+JpDGsYO5rHJjLH/vmbIH/ADxFZ05ShOMmt/yvb9E2edk8JZVi8POXw14/i3p+n3mF+wv8PdL+Feg2XxT8Vx7dS8R38WgeGbR1G9jM+xpVB/vYIBxwiOeQ4rTEVOaVkrpfn/wNjr4kxk8bVeBoaxppyk/T/L83bdHF/GNV8V/8FG7e2+9F/wAJDpNuR6BI7cN+qtV3UcO33/zsehgmqHDjl3jL8W0elftAfs+/HdP2kdY+I3w3Vgt7LbGC4sdRhhdBHDFEFljlZQ6kpypDKQeRjNTCdNx5akduv9anl5bmeVPL44PHL4b7q+7b0a1T+4f4s8c6gPA99oHxa+Jn/Cq/E9yht76102/k1s6iso/ePJaxrILQAK6gQyKpLN8qrs3c8KfM2oa+u352+Wnoc2HwsZV/aZfR9tBPRyXKlbZJ3XNunqr2t528h1iy8I/2JJ4d+GXxN8HeE9DuwtpezaiLtdT1HecI8901oGjRgn7yGMrFHjncZCTtzScuarBvWy0+/p/wH+J70J4n2nt8ww06kk9LW5Y97RT1t0k7uXS1jzWT9l7xfqGjXeq+F7vQvHllZo8t1/wjGqR3M1vGPuu8DbZRvAYquzd8jZA79irx+0mvU92Od4aMlDERlSb254tJ/PVaddbankbo0TsjqUdTgqwwQfQ10Jpq6PeTUldbCUxmnoejHVpZWklWC0gAaaUnoPQe5wf88VyYiv7FJJXk9kfQZPlX9pznKpPkpU1ecuy7Lzdn5L7k9G68W/ZbZ7LSoRa244WX+M+p+p9f8jlhgueSqV3d9uh7uJ4m+r0ZYLKoezp7KX2n3fk331a8na3OMxdizEsxOST1NeolbRHwkpOTcpO7YlMkltLuewuobm2me3uYXWSKaJirowOQwI5BBGc0mk1ZkyjGcXGSumfXHhj/AIKGald6BbaN8RfBGjeO7aEAGe4jRXfHG5kZWQn3AFedPCv7LT9f8/8AgHwWI4USm6mDquHl2+a1J/Hf7f1q3gTUvDPw48B2ngddQRo5LuB0UxhhhmSONFAbGQDk4qYYWV7Tsl5dfwRnheFp+3VXG1edLprr6tif8Ew9TaH40eJdPL4iufD8kpUn7zJcQAfo7VviY3tL5ff/AMMbcYU08LTq9VK33p/5Hhnwv8cn9nT49wa9PpX9sP4dvLu3axabyfMOyWD7+1sEFs9D0rS0qtFX3aR9DisO82y5U+blc1F337M9P/ZM+IGm6/8AteP4x8T39locd6b698y7nWOJJHU7U3tgdGIGeuK5q9NU6UV0T/z/AFPCz3CSw+UQw1FOXK0tF66/eHwG+POi+Ff2pvGGq6/cRf8ACGeMbvULbUWmBaHyppnkjdgOozhSeyu1VUpudGLtrb9NS8zy2riMpoxpx/eU1FpddEk1/XY9P0/xte/tI/ta+CbXwZpso+GHgK9iFtLbwlLWIICwmc4wpkaIJGp5IUYAJasHTUKWujdrel7/AKfoeJPCwyvKKksQ/wB/WWz3tdafdq/ufQveB/Gfhqb9u690HQ/Dtjreo6hrl4+o+IdagE0sDQxySBLJOBBsKMjSEu0mARsHBapyVG8npfRfMmrha6yJVa1RqKS5YrRNNrWXe97pdDwL4mftKfEfwv8AHzxrqXh/xrrFrFBrN7Ba28lyZ7eOETuFQQybo9oHQbcDPFdVOjCUItrzPqcFk+Br4CjGtSTbim3s7td1Z/ieefGP40+I/jp4isdc8UGzfUrWxSwEtpAIfMRXdwzgHBbMh6YGAMAVvTpqmrJ3PWy/LqOWUnSoN2bvq/RfocHWp6hY0/ULrSL+2vrG5msr21lWeC5t5DHJFIpBV0YYKsCAQRyCKNyZRjOLhNXT3RHdXU17czXFxNJcXEzmSSWVizuxOSzE8kk8kmkkkrIIxUEoxVkiOmUSJcSRwSQq5WKQguo6NjOM/malxi5KTWqN416sKcqMZWjK113te1/S5HVGAUAFABQAUAFAHovwD+NF/wDAT4jW3iywsYtTeKCW3ktJpCiyI64+8ASMEKenasKtP2qSvax5GaZdHNMP7CUuXVO++xx/izxHceMPFWs69dxxxXWqXs19NHCCEV5XLsFBJOAWOOa1iuWKXY9ChRWHowoxekUl9ysb3w/n8BafHdXvjG11vV54nX7LpOlyR20U4wSfNuG3Mq5CqQiZwxIYECs5+0btDbucWLWOnKMMK4xi95O7a9Ft566dzq3+NXhHSCJfDfwh8N6feqJEW51a5utT2qQVjby5XEfmKpJLFNrMFYKuMHB0Jy+Kb6+W5wPK8TV0r4uTXaKUfXVdO3ZaGfb/ALSXxBs/FGj67ba0lncaO0rWFpaWkMFnbmRNshS3RRGC3JJ28k560/qlJRsvv7enRfcavI8C6UqTh8Vrtu7021f3ehyeg/EPxJ4X8Zjxbper3Fn4j82Wf+0UIMheQMJGORg7g7Z4710OnFx5GtD0qmDoVqH1apG8NFb028zCurqa+uprm4laa4mcySSOcs7E5JJ7kk1aSikkdUIxhFQirJEVMoKACgAoAKAP/9k="
                     alt="Captcha"> <a href="#captcha_a783458949484d21d2516108ba2e47b34ead4bef" class="btn btn-light"><span class="bi bi-volume-up-fill"><span class="visually-hidden">Lire le captcha&nbsp;</span></span></a> <audio src=
                     "data:audio/wav;base64,UklGRjCGAwBXQVZFZm10IBAAAAABAAEAIlYAAESsAAACABAAZGF0YQyGAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA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AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA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AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA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AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA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7P/x//H/7//u//H/+P8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEAAcACwARACwA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AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=="></audio>

                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#captcha_a783458949484d21d2516108ba2e47b34ead4bef a').click(function () {
                            document.querySelector('#captcha_a783458949484d21d2516108ba2e47b34ead4bef audio').play();
                        });
                        $('#captcha_a783458949484d21d2516108ba2e47b34ead4bef a').keypress(function (e) {
                            if (e.which == 13 || e.which == 32) {
                                document.querySelector('#captcha_a783458949484d21d2516108ba2e47b34ead4bef audio').play();
                            }
                        });
                    });
                </script>
            </div>
            <div class="form-group">
                <label for="a783458949484d21d2516108ba2e47b34ead4bef">Captcha</label> <input id="a783458949484d21d2516108ba2e47b34ead4bef" name="a783458949484d21d2516108ba2e47b34ead4bef" type="text" class="form-control form-control-sm" required="required">
            </div>
            <div class="form-group">
                <input type="hidden" name="captcha" id="captcha" value="a783458949484d21d2516108ba2e47b34ead4bef">
            </div>
        </form>
        <?php
    }

    public static function cards() {
        ?>
        <p>Cette classe permet de gérer un paquet de 32, 52, 54 ou 78 cartes</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Génère un paquet de 52 cartes (par défaut)\n'
                . '$paquet = new cards();\n\n'
                . '//Génère un paquet de 32 cartes\n'
                . '$paquet = new cards(32);\n\n'
                . '//Mélange le paquet\n'
                . '$paquet->shuffle_deck();\n\n'
                . '//Tire une carte du paquet, la carte est retirée du paquet.\n'
                . '//retourne false si le paquet est vide\n'
                . '$card = $paquet->drow_from_deck();\n'
                . '?>'
        );
        ?> <p>Contenu d'un paquet de 52 cartes :</p><p><?php
            $deck = (new cards())->get_deck();
            sort($deck);
            foreach ($deck as $cards) {
                echo $cards . " ";
            }
            ?></p><p>Contenu d'un paquet de 78 cartes :</p><p><?php
            $deck = (new cards(78))->get_deck();
            sort($deck);
            foreach ($deck as $cards) {
                echo $cards . " ";
            }
            ?>
        </p>
        <?php
    }

    public static function change_reload() {
        ?>
        <p>Cette classe permet de recharger automatiquement la page courante lorsqu'une classe metier est modifiée dans le dossier /class/ de votre projet <br />
            (Cette classe est déconsillée en production)</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'change_reload::get_instance();\n'
                . '?>');
    }

    public static function check_password() {
        ?>
        <p>Cette classe permet d'appliquer une politique de mot de passe</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'if (isset($_POST["psw"])) {\n\n'
                . '    // Vérifie le mot de passe avec la politique suivante ( par défaut) :\n'
                . '    // - Le mot de passe doit faire au minimum 8 caractères\n'
                . '    // - Contenir au moins un nombre, une majuscule et une minuscule\n'
                . '    // - L\'utilisation de caractères spéciaux est ici facultatif\n'
                . '    $check = new check_password($_POST["psw"], $minlen = 8, $special = false, $number = true, $upper = true, $lower = true);\n\n'
                . '    // On vérifie si le mot de passe est conforme à la politique de mot de passe\n'
                . '    if ($check->is_valid()) {\n'
                . '        //mot de passe ok \n'
                . '    } else {\n\n'
                . '        //si le mot de passe n\'est pas conforme, on affiche un message d\'erreur\n'
                . '        $check->print_errormsg();\n'
                . '    }\n'
                . '}\n'
                . 'form::new_form();\n'
                . 'form::input("Mot de passe", "psw", "password");\n'
                . 'form::submit("btn-primary");\n'
                . 'form::close_form();\n'
                . '?>');
        ?>
        <p>Démonstration du message d'erreur (avec au moins un caractère special obligatoire)</p>
        <?php
        (new check_password("", 8, true))->print_errormsg();
        ?>
        <p>Il est possible de modifier les messages avant d'appeler print_errormsg() :</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$check = new check_password(\'\');\n'
                . '//Modifie le message d\'erreur lié à la longueur du mot de passe\n'
                . '$check->set_errormsg_minlen($msg);\n\n'
                . '//Modifie le message d\'erreur lié au manque de caractère spécial dans le mot de passe\n'
                . '$check->set_errormsg_special($msg);\n\n'
                . '//Modifie le message d\'erreur lié au manque de chiffre dans le mot de passe\n'
                . '$check->set_errormsg_number($msg);\n\n'
                . '//Modifie le message d\'erreur lié au manque de majuscule dans le mot de passe\n'
                . '$check->set_errormsg_upper($msg);\n\n'
                . '//Modifie le message d\'erreur lié au manque de minuscule dans le mot de passe\n'
                . '$check->set_errormsg_lower($msg);\n\n'
                . '//Modifie le message "Erreur ! votre mot de passe :"n'
                . '$check->print_errormsg($msg);\n'
                . '?>'
        );
    }

    public static function citations() {
        ?>
        <p>Cette classe affiche une citation célèbre à chaque chargement de page. <br />
            Les citations se trouvent dans <em>dwf/class/citations/citations.json</em> <br />
            Vos contributions sont les bienvenues.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'new citations();\n'
                . '?>');
        ?>Résultat :<?php
        new citations();
    }

    public static function ckeditor() {
        ?>
        <p class="alert alert-warning">
            <span><del>Deprecié depuis la verssion 21.24.10, utilisez plutot TinyMCE</del></span><br>
            <span>Deprecié depuis la verssion 21.25.02, utilisez plutot Summernote</span>
        </p>
        <hr>
        <h3 class="text-center">js::summernote</h3>
        <hr>
        <?php
        docPHP_natives_js::summernote();
    }

    public static function cli() {
        ?>
        <p>Cette classe est utile pour les CLI (cf. CLI)</p>
        <hr>
        <h3 class="text-center">CLI</h3>
        <hr>
        <?php
        docPHP::CLI();
    }

    public static function compact_css() {
        ?>
        <p>Compresse des scripts CSS en deux fichiers minifié</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//ajoute un fichier CSS\n'
                . 'compact_css::get_instance()->add_css_file($href);\n\n'
                . '//ajoute un script CSS (string ou CF "CSS")\n'
                . 'compact_css::get_instance()->add_style($style);\n\n'
                . '//fluent peut-etre utilisé :\n'
                . 'compact_css::get_instance()->add_css_file($href)\n'
                . '        ->add_style($style)\n'
                . '?>'
        );
        ?>
        <p>La méthode <em>"render()"</em> est utilisée automatiquement dans <em>html5.class.php</em>, il n'est pas utile de l'appeler</p>
        <?php
    }

    public static function cookieaccept() {
        ?>
        <p>Cette classe permet d'afficher un message d'informations sur l'utilisation de cookies ou autre technologies similaires. <br />
            Cette ligne est à placer dans <em>pages->header()</em>
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'new cookieaccept();\n'
                . '?>');
    }

    public static function css() {
        ?>
        <p>Cette classe permet de génerer des feuilles de style personalisées. <br />            
            A utiliser avec <em>"compact_src::get_instance()->add_style()"</em></p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Definit une regle CSS\n'
                . '((new css())->add_rule($selector, $rules));\n'
                . '//exemple (avec un echo. Fluent peut être utilisé)\n'
                . 'echo (new css())->add_rule("p", ["padding" => "5px"])->\n'
                . '                add_rule("div", [\n'
                . '                    "padding" => "5px",\n'
                . '                    "margin" => "0 auto"\n'
                . '                ])->\n'
                . '                add_rule("#mon_id", ["background-color" => "lightblue"]);\n'
                . '?>');
        ?>
        <p>La feuille de style retournée sera optimisée et minifiée,<br />
            dans l'exemple nous voyons que "p" et "div" ont la même règle de padding, elles seront donc fusionnées. <br />
            Voici le resultat :</p>
        <?php
        debug::print_r((string) (new css())->add_rule("p", ["padding" => "5px"])
                        ->add_rule("div", [
                            "padding" => "5px",
                            "margin" => "0 auto"
                        ])
                        ->add_rule("#mon_id", ["background-color" => "lightblue"])
        );
    }

    public static function cytoscape() {
        ?>
        <p>Il est préférable d'appeler cette classe via <em>(new graphique())->cytoscape()</em> (cf. graphique)
            Cette classe affiche un graphe d'analyse et de visualisation (utilise la librairie jquery cytoscape) <br />
            (Requiert une règle CSS sur l'ID CSS)
        </p>
        <?php
        js::monaco_highlighter('<style type="text/css">\n' .
                '    #cytoscape{\n' .
                '        width: 300px;\n' .
                '        height: 300px;\n' .
                '    }\n' .
                '</style>\n' .
                '<?php\n' .
                'graphique::cytoscape("cytoscape",array(\n' .
                '    \'A\'=>array(\'B\',\'C\'),\n' .
                '    \'B\'=>array(\'C\'),\n' .
                '    \'C\'=>array(\'A\')\n' .
                '));\n' .
                '?>');
        ob_start();
        ?>
        <style type="text/css">
            #cytoscape{
                width: 300px;
                height: 300px;
            }
        </style>
        <?php
        graphique::cytoscape("cytoscape", array(
            'A' => array('B', 'C'),
            'B' => array('C'),
            'C' => array('A')
        ));
        new vpage(ob_get_clean());
    }

    public static function datatable() {
        ?>
        <p>Cette classe permet de convertir un tableau HTML en datatable (jquery) <br />
            il est préférable de l'appeler via <em>js::datatable()</em> (cf js)
        </p>
        <hr>
        <h3 class="text-center">js::datatable</h3>
        <hr>
        <?php
        docPHP_natives_js::datatable();
    }

    public static function ddg__ddg_api() {
        ?>
        <p>La classe ddg permet d'exploiter le moteur de recherche DuckDuckGO</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Affiche une searchbar de DuckDuckGO\n' .
                '(new ddg())->print_searchbar();\n\n' .
                '//retourne un objet ddg_api qui contient les résultats d\'une recherche\n' .
                '$ddg_api=(new ddg())->api("DuckDuckGO");\n' .
                '?>');
        ?>
        <p>Résultat :</p>
        <div class="row" style="overflow: visible; height: 300px;">
            <div class="col-sm-5">
                <?php
                (new ddg())->print_searchbar();
                ?>
            </div>
            <div class="col-sm-7"> 
                <style type="text/css">
                    #ddg_api{
                        border-left: black solid 1px;
                        padding-left: 10px;
                    }
                    #ddg_api>pre{
                        overflow-y: visible;
                        height: 300px;
                    }
                </style>
                <div id="ddg_api">
                    <?php
                    debug::print_r((new ddg())->api("DuckDuckGO"));
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    public static function debug() {
        ?>
        <p>Cette classe est une boîte à outils de débogage</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Affiche la structure d\'une variable ( optimisée pour les arrays et objets )\n' .
                'debug::print_r($var);\n\n' .
                '//Affiche le contenu et le type d\'une variable ( optimisée pour les type nombres, chaines de caractères et les booléans )\n' .
                'debug::var_dump($var);\n\n' .
                '//Affiche la trace de l\'application pour arriver au point de débug ( trace des fichiers et méthodes qui ont été appelés)\n' .
                'debug::get_trace();\n\n' .
                '//Affiche le rapport d\'activités de PHP en bas de page\n' .
                'debug::show_report();\n' .
                '?>');
    }

    public static function dictionary() {
        ?>
        <p>Cette classe permet de convertir et gérer une liste lourde comme étant un dictionnaire</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//créé un dictionnaire\n' .
                '$dictionnaire = new dictionary($words = ["All", "My", "Words"], $chunk_size = 100000);\n\n' .
                '//Ajoute des mots\n' .
                '$dictionnaire->add(["New", "Elements"]);\n\n' .
                '//Vérifie si un mot existe\n' .
                '$dictionnaire->word_exist("Words");\n\n' .
                '//Supprime des mots\n' .
                '$dictionnaire->remove(["Words", "Elements"]);\n\n' .
                '//Nombre de mots dans le dictionnaire\n' .
                '$dictionnaire->count_words();\n\n' .
                '//Gestion de la taille des sections du dictionnaire\n' .
                '$dictionnaire->set_chunk_size($chunk_size = 100000);' .
                '$dictionnaire->get_chunk_size();\n\n' .
                '//Accès au sections\n' .
                '$dictionnaire->count_sections();\n' .
                '$dictionnaire->get_section($key = 0);\n' .
                '?>');
    }

    public static function dlc() {
        ?><p>Cette classe permet de générer des fichiers :</p><?php
        echo html_structures::ul([
            "DLC (Download Link Container, recommandé)",
            "CCF (CryptLoad Container File)",
            "RSDF (RapidShare Download File)"
        ]);
        ?><p>Servant de librairie de téléchargement pour des logiciels comme <?= html_structures::a_link("http://jdownloader.org", "JDownloader") ?></p><?php
        js::monaco_highlighter('<?php\n' .
                '$data=[\n' .
                '    "http://url/image1.jpg",\n' .
                '    "http://url/image2.jpg",\n' .
                '    "http://url/image3.jpg"\n' .
                '];\n' .
                '//Génère un fichier DLC\n' .
                'dlc::generate_DLC("monDLC.dlc", $data);\n\n' .
                '//Génère un fichier CCF\n' .
                'dlc::generate_CCF("monCCF.ccf", $data);\n\n' .
                '//Génère un fichier RSDF\n' .
                'dlc::generate_RSDF("monRSDF.rsdf", $data);\n' .
                '?>');
    }

    public static function downloader() {
        ?><p>Cette classe permet à l'utilisateur de télécharger un fichier spécifique sur le serveur</p><?php
        js::monaco_highlighter('<?php\n' .
                '//Vide le cache des fichier téléchargeable\n' .
                'downloader::clear();\n\n' .
                '//rend un fichier du serveur téléchargeable \n' .
                '//et affiche un bouton de téléchargement\n' .
                'echo downloader::file($fullPathFile, $btn_txt = "Télécharger");\n' .
                '?>');
    }

    public static function dwf_exception() {
        ?> 
        <p>Cette classe gère les exceptions du framework <br />
            les codes d'erreurs des exceptions natives du framework sont compris entre 600 et 699 :</p>
        <ul>
            <li>les codes d'erreurs 60X concernent la base de données</li>
            <li>les codes d'erreurs 61X concernent les routes et méthodes</li>
            <li>les codes d'erreurs 62X concernent les services</li>
            <li>les codes d'erreurs 63X concernent le système</li>
        </ul>
        <p>Il est cependant possible pour vous de créer des exceptions personnalisées avec dwf_exeption :</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$code_erreur=700;\n' .
                '$text_erreur="un message d\'erreur";\n' .
                '//Affiche une exception qui n\'interrompt pas le script en cours\n' .
                'dwf_exception::warning_exception($code_erreur, array("msg"=>"$text_erreur"));\n\n' .
                '//Lance une exception qui interrompt le script en cours\n' .
                'dwf_exception::throw_exception($code_erreur, array("msg"=>"$text_erreur"));\n\n' .
                '//try catch pour gérer et afficher une exception\n' .
                'try {\n' .
                '    //conditions menant à une exception\n' .
                '} catch (Exception $e) {\n' .
                '    dwf_exception::print_exception($e);\n' .
                '}\n' .
                '?>');
        ?>
        <p>Les exceptions sont toujours retranscrites dans un log : <em>dwf/log/log_[votre_projet]_[date_format_us].txt</em></p>
        <p>Exemple de <em>dwf_exception::warning_exception()</em> :</p>
        <div class="alert alert-danger" role="alert">
            <p>DWF EXCEPTION ! Code 700 : "Exemple d'exception"</p>
            <pre class="border alert alert-light"><?= "#0 /var/www/html/doc/class/docPHP_natives.class.php(294): dwf_exception::warning_exception('700', Array)
#1 /var/www/html/doc/class/docPHP_natives.class.php(21): docPHP_natives->dwf_exception()
#2 /var/www/html/doc/class/docPHP.class.php(427): docPHP_natives->__construct()
#3 /var/www/html/doc/class/docPHP.class.php(38): docPHP->classes_natives()
#4 /var/www/html/doc/class/pages.class.php(91): docPHP->__construct()
#5 /var/www/dwf/class/application.class.php(126): pages->web()
#6 /var/www/dwf/class/application.class.php(44): application->contenu()
#7 /var/www/dwf/index.php(20): application->__construct()
#8 /var/www/html/doc/index.php(18): index->__construct()
#9 /var/www/html/doc/index.php(40): website->__construct()
#10 {main}"; ?></pre>
        </div>
        <?php
    }

    public static function easteregg() {
        ?><p>Cette classe permet d'afficher des "oeufs de Pâques" qui s'affichent à certaines dates de l'année. <br />
            Liste des dates : </p>
        <ul>
            <li>31/12 et 01/01 : Jour de l'an, affiche une guirlande interactive (les ampoules éclatent au survol de la souris)</li>
            <li>06/01 : Epiphanie, affiche une couronne des rois en pied de page</li>
            <li>14/02 : Saint Valentin, une pluie de coeurs sur le site</li>
            <li>21/03 : Printemps, des pétales de cerisiers tombent sur le site</li>
            <li>01/04 : Poisson d'Avril ! un poisson animé traverse l'écran</li>
            <li>01/05 : Fête du travail, une couronne de muguet en pied de page</li>
            <li>Entre le 25/05 et 31/05 : Fête des mères (dernier dimanche de mai), un collier de nouilles "bonne fête maman" en pied de page</li>
            <li>21/06 : Été, la lueur chaude du soleil apparait en pied de page</li>
            <li>Entre le 16/05 et 22/06 : Fête des pères (troisième dimanche de mai) un modal avec le diplôme du père de l'année apparait (qu'une fois par session)</li>
            <li>14/07 : Fête Nationale française, des feux d'artifices sont tirés sur le site</li>
            <li>23/09 : Automne, des feuilles mortes tombent sur le site</li>
            <li>25/11 : Sainte-Catherine, un chapeau de Sainte-Catherine en pied de page</li>
            <li>Du 22/12 aux 25/15 : Hiver et Noël, des flocons de neiges tombent sur le site</li>
        </ul>
        <p>Astuce : une interface pour activer les eastereggs peut être affichée en tapant le "Konami code" : ↑ ↑ ↓ ↓ ← → ← → B A</p>
        <p>Pour activer les eastereggs sur toutes les pages, placez cette ligne dans <em>pages->header()</em></p>
        <?php
        new easteregg();
        js::monaco_highlighter("<?php new easteregg(); ?>");
    }

    public static function entity_generator() {
        ?>
        <p class="alert alert-danger">
            Note de version 21.24.04 : <br>
            Comme expliqué plus en détail dans la section BDD la gestion des requetes a changé,<br>
            il est recomandé de regénérer vos entity si elles ont été généré avant cette verssion !
        </p>
        <p>
            Les entités font office d'ORM dans votre projet,<br />
            une classe entité vous permet de lire, ajouter, modifier ou supprimer des entrées de votre base de données sans avoir à saisir une requête SQL <br />
            (ormis une eventuelle condition "where" ). Les entités exploitent un objet bdd accessible via <em>application::$_bdd</em> <br />
            les entités seront capable de recréer la structure de leur base de données si celle-ci est perdue (mais ne permettent pas de sauvegarder les données ).
        </p>
        <h4>Créer des entités</h4>
        <p>
            La création d'une entité est simple, il est conseillé de mettre la création d'entité soit dans le constructeur de pages.class.php <br />
            soit dans le constructeur de la classe métier qui exploitera cette entité. Voici le code (consultez la documentation technique pour plus d'informations)
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$data=array(                         //$data est un tableau à deux dimensions qui definit\n'
                . '                                     //la structure de l\'entité et de sa table dans la base de données\n\n'
                . '    array("id","int",true),          //créé un champ/attribut nommé "id" de type entier,\n'
                . '                                     //le "true" indique une clé primaire, le setter de "id" sera en privé\n'
                . '                                     //Depuis la version 21.22.10 cette clé primaire est automatiquement renseigné,\n'
                . '                                     //il n\'est donc plus nessaicaire de la déclarer dans le code.\n'
                . '    array("login","string",false),\n'
                . '    array("psw","string",false),\n'
                . ');\n'
                . '$table="user";                       //nom de la table et de l\'entité\n\n'
                . 'new entity_generator($data, $table); //Créé l\'entité et sa table si elle n\'existe pas\n'
                . '                                     //Attention : si la structure de l\'entité est modifié \n'
                . '                                     //il faudra supprimer la classe et la table de l\'entité\n'
                . '?>');
        ?>
        <p>
            Créer des relations entres les entités et astuces pour créer plusieurs entités facilement
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$datas=array(\n'
                . '    "rang"=>array( //on créé une entité rang\n'
                . '        array("id","int",true),\n'
                . '        array("nom","string",false),\n'
                . '    ),\n'
                . '    "user"=>array( //on créé une entité user\n'
                . '        array("id","int",true),\n'
                . '        array("login","string",false),\n'
                . '        array("psw","string",false),\n'
                . '        array("rang","rang",false), //on met en relation le fait qu\'un user a un rang (de type "rang")\n'
                . '    ),\n'
                . ');\n'
                . 'foreach($datas as $table => $data){\n'
                . '    new entity_generator($data, $table);\n'
                . '}\n'
                . '?>'
        );
        ?>
        <h4>MAJ 21.18.02</h4>
        <p>Depuis la version 21.18.02, il est possible de créer l'ensemble de vos entités ainsi :</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'entity_generator::generate([\n'
                . '    "rang"=>[ //on créé une entité rang\n'
                . '        ["id","int",true],\n'
                . '        ["nom","string",false],\n'
                . '    ],\n'
                . '    "user"=>[ //on créé une entité user\n'
                . '        ["id","int",true],\n'
                . '        ["login","string",false],\n'
                . '        ["psw","psw",false], //password géré automatiquement depuis 21.25.02\n'
                . '        ["rang","rang",false], //on met en relation le fait qu\'un user a un rang (de type \'rang\')\n'
                . '    ],\n'
                . ']);\n'
                . '?>'
        );
        ?>
        <h4>Utilisation des entités</h4>
        <p>Une fois les entités créées, elles peuvent être utilisées (nous utiliserons l'exemple des 'user' et 'rang')</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//ajoute un utilisateur\n'
                . 'user::ajout($login, application::hash($psw)); //avant la 21.25.02\n\n'
                . 'user::ajout($login, $psw) //depuis a 21.25.02;\n\n'
                . '//récuperer tout les utilisateurs sous forme de tableaux de données\n'
                . '$users = user::get_table_array();\n'
                . 'echo $users[0]["login"]; //affiche le login du premier utilisateur de la table\n\n'
                . '//récuperer tout les utilisateurs du rang 1 (utilisation d\'une requete préparé cf. bdd)\n'
                . '$users = user::get_table_array("rang=:rang",[":rang"=>1]);\n\n'
                . '//astuce pour récuperer tout les utilisateurs par ordre alphabétique de login\n'
                . '$users = user::get_table_array("1=1 order by login");\n\n'
                . '//récuperer tout les rang sous forme de table ordonnée par leur ID (les id sont les clés du tableau)\n'
                . '$rangs = rang::get_table_ordored_array();\n'
                . 'echo $rangs[1]["nom"]; //affiche le nom du rang ayant l\'identifiant 1\n\n'
                . '//récuperer les utilisateurs sous forme de collection (tableau d\'objets)\n'
                . '//DECONSEILLÉ ! potentiellement lourd !\n'
                . '$users = user::get_collection();\n'
                . 'echo $users[0]->get_rang()->get_nom(); //affiche le nom du rang du premier utilisateur de la table\n\n'
                . '//récupere l\'objet d\'un utilisateur à partir de son id\n'
                . '$user = user::get_from_id(1);\n'
                . 'echo $user->get_login(); //affiche le login de l\'utilisateur 1\n'
                . '$user->set_login($nouveau_login); //redéfinit le login de l\'utilisateur 1,\n'
                . '                                  //la modification dans la base de données sera prise en compte à la fin du script\n\n'
                . '//supprimer un utilisateur : 2 solutions\n'
                . '//1 : supprimer un utilisateur non instancié depuis son id\n'
                . 'user::delete_by_id($id);\n'
                . '//2 : supprimer un utilisateur instancié\n'
                . '$user->delete();'
                . '?>');
        ?>
        <p class="alert alert-danger">
            ATTENTION : si vous utilisez des variables dans les paramètres $where : <br>
            utilisez le tableau $params afin d'utiliser les requêtes préparé et vous protéger des injections SQL <br />
            pensez également à la fonction <a href="https://secure.php.net/manual/fr/function.strip-tags.php" target="_blank">strip_tags</a> pour vous protéger des failles XSS.
        </p>
        <h4>Les types de champ/attribut</h4>
        <?php
        echo html_structures::table(["Type (code PHP)", "Type (SQL)", "Description"], [
            ["int, integer", "int(11)", "un champ de nombre entiers"],
            ["bool, boolean", "int(1)", "0 ou 1"],
            ["string", "text", "un champ de texte, peut contenir aussi du HTML, des dates, ou des nombres"],
            ["mail", "text", "un champ de texte pour les mail, une verification est faite en PHP par l'entité avant l'enregistrement en base de données"],
            ["array", "text", "(depuis la version 21.18.03) un champ de texte JSON, les conversions de array (coté PHP) en JSON (coté SQL) et inversement sont gérées en PHP par l'entité. <br />"
                . "Inutile donc d'utiliser json_encode() et json_decode()"],
            ["psw (ou password)", "text", "(depuis la version 21.25.02) un champ de texte pour les mot de passe, le hash se fait automatiquement lors de l'appel de set_psw()"],
        ]);
    }

    public static function entity_model() {
        ?><p>Cette classe permet d'afficher des pseudos MCD à partir de vos entités</p><?php
        js::monaco_highlighter('<?php\n' .
                '//Exemple avec la petite structure vue dans "Entity"\n\n' .
                '//Affiche un pseudo MCD sous forme de tableau HTML\n' .
                'echo entity_model::table("user");\n\n' .
                '//Affiche un pseudo MCD sous forme de div HTML\n' .
                'echo entity_model::div("user");\n' .
                '?>');
        ?><p>Résultats :</p>
        <div class="row">
            <div class="col-sm-6">
                <p>- entity_model::table("user") :</p>
                <?= entity_model::table("user"); ?>
            </div>
            <div class="col-sm-6">
                <p>- entity_model::div("user") :</p>
                <?= entity_model::div("user"); ?>
            </div>
        </div>
        <?php
    }

    public static function espeak() {
        ?><p>Cette classe convertit un texte en flux audio. <br />
            /!\ Nécessite que espeak soit installé sur le serveur. <br />
            http://espeak.sourceforge.net/</p><?php
        js::monaco_highlighter('<?php\n' .
                '//exemple avec les paramètres par défaut \n' .
                '$wav = (new espeak("espeak"))' .
                '    ->set_amplitude(100)' .
                '    ->set_pitch(50)' .
                '    ->set_speed(175)' .
                '    ->set_wordgap(0)' .
                '    ->set_voice("fr")' .
                '    ->set_variant("")' .
                '    ->set_output("base64")' .
                '    ->TTS("Bonjour le monde !");\n\n' .
                'echo tags::tag("audio", ["src" => $wav, "controls" => ""], " ");\n' .
                '?>');
        ?><p>Résultats :</p>
        <audio src="data:audio/wav;base64,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AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==" controls=""></audio>
        <?php
    }

    public static function event() {
        ?>
        <p>
            Cette classe permet de créer des événements (listener et emiter) <br />
            l'utilisation de cette classe différe des "méthodes événementielles" dans la mesure où ces dernières font appel à des methodes "statiques" d'autres classes.
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Définit un listener et une action associée\n' .
                'event::on("mon_listener",function(){\n' .
                '    //do somthings\n' .
                '}\n\n' .
                '//Ajoute une autre action au listener associée\n' .
                'event::on("mon_listener",function(){\n' .
                '    //do another somthings\n' .
                '}\n\n' .
                '//Déclenche l\'événement (emiter)\n' .
                'event::run("mon_listener");\n\n' .
                '//Autre exemple avec des paramètres\n' .
                'event::on("alert.warning",function($text){\n' .
                '    ?>\n' .
                '        <p class="alert alert-warning">\n' .
                '            <?= $text; ?>\n' .
                '        </p>\n' .
                '    <?php\n' .
                '}\n' .
                'event::run("alert.warning","Attention !");\n' .
                '?>'
        );
    }

    public static function export_dwf() {
        ?>
        <p>
            Cette classe permet de générer une archive ZIP contenant les fichiers nécessaires à un projet DWF,<br>
            en fonction des dépendances enregistrées dans un fichier JSON spécifique à chaque projet. <br>
            Ce fichier JSON est consultable dans <em>dwf/class/export_dwf/</em>. <br>
            export_dwf est normalement autonome et ajoute vos dépendances tout au long de votre développement. <br>
            L'export inclura tous les fichiers contenus dans votre dossier projet (à l'exception des CSS dans src/compact). <br>
            Cependant, si vous désirez inclure un fichier ou un dossier spécifique à votre projet lors de l'export :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//ajoute des fichier\n'
                . 'export_dwf::add_files([\n'
                . '    realpath("./un_ficher.php"),\n'
                . '    realpath("./un_ficher.js"),\n'
                . '    realpath("./un_ficher.css")\n'
                . ']);\n\n'
                . '//ajoute un dossier (recursif)\n'
                . 'export_dwf::add_files([realpath("./le_dossier")]);\n'
                . '?>');
        ?>
        <p>
            Il est déconseillé d'instancier export_dwf car son instanciation est réservée à l'export depuis html/index.php. <br>
            De même, les autres fonctions statiques sont utilisées en interne de la classe.
        </p>
        <?php
    }

    public static function fancybox() {
        ?>
        <h3 class="text-center">js::fancybox</h3>
        <hr>
        <?php
        docPHP_natives_js::fancybox();
    }

    public static function fb() {
        ?>
        <p>
            Cette classe permet de gérer une authentification via FaceBook. <br />
            Requiert la création d'une application sur https://developers.facebook.com/ <br />
            Exemple d'usage :
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Execution en cas de Logout\n' .
                'if(isset($_GET["fb"]) and $_GET["fb"]=="logout"){\n' .
                '    session_destroy();\n' .
                '    js::redir("index.php");\n' .
                '}\n\n' .
                '$fb = new fb($app_id, $app_secret);\n' .
                'if ($fb->getAccessToken_session()) {\n' .
                '    //Bouton logout\n' .
                '    echo html_structures::a_link($fb->getLogoutUrl("http://mon-site/index.php?fb=logout"), "logout");\n\n' .
                '    //Données de l\'utilisateur FB\n' .
                '    debug::print_r($fb->getGraphUser());\n\n' .
                '    //TODO : utilisez session::set_auth(true) et requetes SQL\n' .
                '} else {\n' .
                '    //Bouton login\n' .
                '    echo html_structures::a_link($fb->getLoginUrl("http://mon-site/index.php"), "login");\n' .
                '}\n' .
                '?>'
        );
    }

    public static function file_explorer() {
        ?><p>Cette classe permet d'afficher et explorer une arborescence</p><?php
        js::monaco_highlighter('<?php new file_explorer("./files"); ?>');
        ?> <p>Résultat :</p><?php
        new file_explorer("./files");
    }

    public static function fluent() {
        ?>
        <p>Cette classe permet de rendre une autre classe pseudo-fluent.</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                'class exemple extends fluent{\n' .
                '    public function metode_1($param){\n' .
                '        //do something;\n' .
                '    }\n' .
                '    public function metode_2(){\n' .
                '        return true;\n' .
                '    }\n' .
                '}\n\n' .
                '$exemple = new exemple();\n' .
                '//Exécute la méthode et retourne l\'instance si la fonction ne retourne rien ou null\n' .
                '$exemple->fluentOnNull("methode_1", "$params")->fluentOnNull("methode_2");\n' .
                '//Exécute la méthode et retourne l\'instance uniquement \n' .
                '//(tout éventuel retour de la méthode n\'est pas récupérable)\n' .
                '$exemple->fluentStrict("methode_1", "$params")->fluentStrict("methode_2");\n' .
                '//Exécute la méthode et retourne l\'instance uniquement,\n' .
                '//tout éventuel retour de la méthode est mis dans un cache récupérable via getFluentBuffer()\n' .
                '$exemple->fluentBuffered("methode_1", "$params")->fluentBuffered("methode_2");\n' .
                '$result = $exemple->getFluentBuffer();\n' .
                '?>');
    }

    public static function form() {
        ?>
        <p>La classe form permet de créer des formulaires en php stylisés par bootstrap, accessibles et respectant les normes W3C</p>
        <p>Première méthode : création et rendu</p>
        <?php
        $tmce = js::summernote("ta_2");
        js::monaco_highlighter('<?php\n' .
                '//création du formulaire\n' .
                '$form = new form();\n' .
                '$form->input("Input de type text", "input_1");\n' .
                '$form->input("Input de type password", "input_2", "password");\n' .
                '$form->input("Input avec valeur initiale", "input_3", "text", "valeur initiale");\n' .
                '$form->datepicker("Un datepicker", "datepicker_1");\n' .
                '$form->select("Un selecteur", "select_1", [\n' .
                '    [1, "Abricots"],\n' .
                '    [2, "Poires", true], //Poires est sélectionné par défaut\n' .
                '    [3, "Pommes"],\n' .
                ']);\n' .
                '$form->textarea("Un textarea", "ta_1");\n' .
                '//création d\'un Summernote\n' .
                '$form->textarea("Un Summernote", "ta_2");\n' .
                '$tmce = js::tinymce("ta_2");\n' .
                '//bouton de soumission\n' .
                '$form->submit("btn-primary");\n' .
                '//Rendu du formulaire\n' .
                'echo $form->render();\n' .
                '//Exécution du formulaire\n' .
                'if (isset($_POST["input_1"])) {\n' .
                '    //Récupère la date du datepicker au format US\n' .
                '    $date = form::get_datepicker_us("datepicker_1");\n' .
                '    //Filtre les balises utilisées dans Summernote, protection XSS\n' .
                '    $ta_2 = $tmce->parse();\n' .
                '    //Message de succès ou erreur\n' .
                '    js::alert("le formulaire a bien été soumis");\n' .
                '    //Redirection vers la page courante = rafraîchissement de la page\n' .
                '    js::redir("");\n' .
                '}\n' .
                '?>'
        );
        ?>
        <p>Seconde méthode : affichage direct</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//création du formulaire\n' .
                '$form = new form();\n' .
                '//affichage de la balise d\'ouverture\n' .
                'echo $form->get_open_form();\n' .
                'echo $form->input("Input de type text", "input_1");\n' .
                'echo $form->input("Input de type password", "input_2", "password");\n' .
                'echo $form->input("Input avec valeur initiale", "input_3", "text", "valeur initiale");\n' .
                'echo $form->datepicker("Un datepicker", "datepicker_1");\n' .
                'echo $form->select("Un selecteur", "select_1", [\n' .
                '    [1, "Abricots"],\n' .
                '    [2, "Poires", true], //Poires est sélectionné par défaut\n' .
                '    [3, "Pommes"],\n' .
                ']);\n' .
                'echo $form->textarea("Un textarea", "ta_1");\n' .
                '//création d\'un Summernote\n' .
                'echo $form->textarea("Un Summernote", "ta_2");\n' .
                '$tmce = js::tinymce("ta_2");\n' .
                '//bouton de soumission\n' .
                'echo $form->submit("btn-primary");\n' .
                '//affichage de la balise de fermeture\n' .
                'echo $form->get_close_form();\n' .
                '//execution du formulaire\n' .
                'if (isset($_POST["input_1"])) {\n' .
                '    //récupère la date du datepicker au format US\n' .
                '    $date = form::get_datepicker_us("datepicker_1");\n' .
                '    //filtre les balises utilisées dans Summernote, protection XSS\n' .
                '    $ta_2 = $tmce->parse();\n' .
                '    //message de succès ou erreur\n' .
                '    js::alert("le formulaire a bien été soumis");\n' .
                '    //redirection vers la page courante = rafraîchissement de la page\n' .
                '    js::redir("");\n' .
                '}\n' .
                '?>'
        );
        ?>
        <p>Résultat (visuel uniquement, exécution désactivée):</p>
        <div class="row" style="border: 1px solid #ccc; border-radius: 4px;">
            <div class="col-sm-3">
                <script type="text/javascript">
                    $(document).ready(function () {
                        $(".no-sub").submit(function (e) {
                            e.preventDefault();
                            return false;
                        });
                    });
                </script>
            </div>
            <div class="col-sm-6">
                <?php
                $form = new form("no-sub");
                $form->get_open_form();
                $form->input("Input de type text", "input_1");
                $form->input("Input de type password", "input_2", "password");
                $form->input("Input avec valeur initiale", "input_3", "text", "valeur initiale");
                $form->datepicker("Un datepicker", "datepicker_1");
                $form->select("Un sélecteur", "select_1", [
                    [1, "Abricots"],
                    [2, "Poires", true], //Poires est sélectioné par défaut
                    [3, "Pommes"],
                ]);
                $form->textarea("Un textarea", "ta_1");
                $form->textarea("Un Summernote", "ta_2");
                $form->submit("btn-primary");
                echo $form->render();
                ?>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <hr>
        <h4 class="text-center">Formulaire d'upload</h4>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$form=new form();\n' .
                '$form->hidden("upload", 1);//pour avoir une valeur a tester\n' .
                '$form->file("Votre fichier (.jpg)", "fichier");\n' .
                '$form->submit("btn-primary");\n' .
                'echo $form->render();\n' .
                'if(isset($_POST["upload"])){\n' .
                '    form::get_upload($name="fichier", $path="./files/uploads", $type=["image/jpeg"], $fname="nouveau nom.jpg");\n' .
                '}\n' .
                '?>'
        );
    }

    public static function freetile() {
        ?>
        <h3 class="text-center">js::freetile</h3>
        <hr>
        <?php
        docPHP_natives_js::freetile();
    }

    public static function ftp_explorer() {
        ?> 
        <p>Cette classe permet d'afficher et explorer une arborescence FTP <strong>PUBLIQUE</strong> <br />
            le compte FTP renseigné dans cette classe ne doit avoir que des droits de consultation ( aucun droit d'ajout/modification/suppression) <br />
            le rendu est le même que <em>file_explorer()</em>
        </p>
        <?php
        js::monaco_highlighter('<?php new ftp_explorer($host, $user, $psw, $ssl=false); ?>');
    }

    public static function fullcalendar() {
        ?>
        <p>Cette classe permet d'afficher un Fullcalendar <br />
            <a href="https://fullcalendar.io/">https://fullcalendar.io/</a>
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Affiche l\'administration du Fullcalendar\n' .
                'fullcalendar::admin();\n\n' .
                '//Affiche le fullcalendar avec les évènements enregistrés en base de données\n' .
                'new fullcalendar($id="fullcanlendar", fullcalendar_event::get_table_array());\n\n' .
                '//Affiche le fullcalendar avec l\'agenda d\'un compte Google\n' .
                'new fullcalendar($id="fullcanlendar", null, $api_key, "abcd1234@group.calendar.google.com");\n' .
                '?>');
        ?><p>Exemple</p><?php
        new fullcalendar("fullcalendar", [
            [
                "title" => "évènement de démonstation",
                "start" => date("Y-m-d") . " " . date("H:i:s"),
                "end" => date("Y-m-d") . " " . (date("H") + 1) . date(":i:s")
            ]
        ]);
    }

    public static function g_agenda() {
        ?>
        <p>Cette classe permet de gérer un agenda d'évènements minimalistes, <br />
            souvent utilisé pour avertir des visiteurs des prochains évenements<br />
            (salons, conventions, spectacles, ...)</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Affiche l\'interface d\'administration\n'
                . '(new g_agenda())->admin();\n\n'
                . '//Affiche la liste d\'évènements prévus (les 10 prochains par défaut)\n'
                . '(new g_agenda())->agenda_page($lim = 10);\n'
                . '?>');
    }

    public static function g_elFinder() {
        ?>
        <p>Cette classe affiche le gestionnaire de fichiers elFinder <br />
            Il n'est pas recommandé de mettre deux instances de cette classe dans une même page<br />
            Pour autoriser un utilisateur à utiliser elFinder, vous devrez utiliser session::set_val("elFinder", true);<br />
            Cf : le fichier connector.php (généré par cette classe) pour plus de détails et options)
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Sécurité via la varible de session\n'
                . 'session::set_val("elFinder", true);\n\n'
                . '//Affiche le gestionnaire de fichiers elFinder\n'
                . 'new g_elFinder();\n'
                . '?>');
    }

    public static function gestion_article() {
        ?>
        <p>Cette classe permet de gérer et afficher des articles</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Affiche l\'administration des articles\n'
                . '(new gestion_article())->admin();\n\n'
                . '//Affiche le parcours des articles (pour les utilisateurs)\n'
                . '(new gestion_article())->article();\n\n'
                . '//Affiche un module avec les dernières actualités\n'
                . '//Plusieurs modules peuvent être créés, chaque module est identifié par un nom,\n'
                . '//possède une limite d\'affichage et sont liés à une ou plusieurs catégories d\'articles\n'
                . '(new gestion_article())->module($name="default")\n'
                . '?>');
    }

    public static function giphy() {
        ?>
        <p>
            Cette classe permet de retourner des Gifs de l'API GIPHY
        </p>
        <p>
            Afin d'etre utilisatble, la clé api doit etre renseigné dans <strong>config.class.php</strong> ainsi :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'public static static $_giphy_key= "VOTRE CLE API";\n'
                . '?>');
        echo'<p>Usage :</p>';
        js::monaco_highlighter('<?php\n'
                . 'new giphy();\n'
                . '?>');
    }

    public static function git() {
        ?>
        <p>Utilisez GIT depuis PHP (requiert GIT sur le serveur) <a href="https://github.com/kbjr/Git.php">https://github.com/kbjr/Git.php</a></p>
        <?php
    }

    public static function google_oauth() {
        ?>
        <p>Cette classe permet de gérer une authentification via Google <br />
            Requiert la création d'une application sur https://console.cloud.google.com/apis/credentials</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$gOauth = new google_oauth($clientId, $clientSecret, $redirectUri);\n' .
                'if ($gOauth->getAccessToken_session()) {\n' .
                '    $userinfo = $gOauth->get_OpenId();\n' .
                '    //TODO : utilisez $userinfo, session::set_auth(true) et requetes SQL\n' .
                '} else {\n' .
                '    echo html_structures::a_link($gOauth->getLoginUrl(), "Google Oauth");\n' .
                '}\n' .
                '?>'
        );
    }

    public static function graphique() {
        ?>
        <p>Cette classe permet de créér des graphiques.</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$data = [\n' .
                '    [\n' .
                '        "label" => "Nombres heureux",\n' .
                '        "data" => [[1, 1],[2, 7],[3, 10],[4, 13],[5, 19]]\n' .
                '    ],\n' .
                '    [\n' .
                '        "label" => "Nombres premiers",\n' .
                '        "data" => [[1, 2],[2, 3],[3, 5],[4, 7],[5, 11]]\n' .
                '    ]\n' .
                '];\n' .
                '//Affiche un graphique en ligne/courbe\n' .
                '(new graphique("graph1", $size = ["width" => "600px", "height" => "300px"]))->line($data, $ticks = [], $show_points = true, $fill = false);\n' .
                '//Affiche un graphique en points\n' .
                '(new graphique("graph2"))->points($data);\n' .
                '//Affiche un graphique en bars\n' .
                '(new graphique("graph3"))->bars($data);\n\n' .
                '$data = [\n' .
                '    [\n' .
                '        "label" => "Allemagne",\n' .
                '        "data" => 3466.76\n' .
                '    ],\n' .
                '    [\n' .
                '        "label" => "Royaume uni",\n' .
                '        "data" => 2618.89\n' .
                '    ],\n' .
                '    [\n' .
                '        "label" => "France",\n' .
                '        "data" => 2465.45\n' .
                '    ]\n' .
                '];' .
                '//Affiche un graphique en "camembert"\n' .
                '(new graphique("graph4"))->pie($data);\n' .
                '//Affiche un graphique en anneau\n' .
                '(new graphique("graph5"))->ring($data);\n' .
                '?>'
        );
        $data = [
            [
                "label" => "Nombres heureux",
                "data" => [[1, 1], [2, 7], [3, 10], [4, 13], [5, 19]]
            ],
            [
                "label" => "Nombres premiers",
                "data" => [[1, 2], [2, 3], [3, 5], [4, 7], [5, 11]]
            ]
        ];
        $data2 = [
            [
                "label" => "Allemagne",
                "data" => 3466.76
            ],
            [
                "label" => "Royaume uni",
                "data" => 2618.89
            ],
            [
                "label" => "France",
                "data" => 2465.45
            ]
        ];
        ?>
        <p>Résultats :</p>
        <div class="row">
            <div class="col-sm-4">
                <?php
                (new graphique("graph1", $size = ["width" => "100%", "height" => "300px"]))->line($data);
                ?>
            </div>
            <div class="col-sm-4">
                <?php
                (new graphique("graph2", $size))->points($data);
                ?>
            </div>
            <div class="col-sm-4">
                <?php
                (new graphique("graph3", $size))->bars($data);
                ?>
            </div>
        </div>
        <div class="row">
            <p>PIB 2016</p>
            <div class="col-sm-5">
                <?php
                (new graphique("graph4", $size))->pie($data2);
                ?>
            </div>
            <div class="col-sm-5">
                <?php
                (new graphique("graph5", $size))->ring($data2);
                ?>
            </div>
        </div>
        <?php
    }

    public static function html5() {
        ?>
        <p>
            Cette classe gère l'en-tête HTML5 et son pied de page. <br />
            Cette classe est utilisée automatiquement par le framework dans <em>application.class.php</em> <br />
            Les balises : title, meta description et meta keywords peuvent être modifiées grâce aux fonctions suivantes :

        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Ajoute un préfixe au titre de la page en cours\n'
                . 'html5::before_title($text);\n'
                . '//Définit la description de la page en cours\n'
                . 'html5::set_description($description);\n'
                . '//Définit les mots clés de la page en cours\n'
                . 'html5::set_keywords($keywords);\n'
                . '//Ajoute des mots clés de la page en cours\n'
                . 'html5::add_keywords($keywords);\n'
                . '?>');
    }

    public static function html_structures() {
        ?>
        <p>html_structures est une classe qui permet de créer des structures HTML en PHP,<br />
            Ces structures respectent les normes W3C et répondent à quelques normes d'accessibilité <br />
            Exemple :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//affiche une liste de deux liens\n'
                . '//le premier affiche le glyphicon "home" et renvoie vers l\'acceuil\n'
                . '//le second affiche le glyphicon "search" et ouvre un onglet vers DuckDuckGo\n'
                . 'echo html_structures::ul(array(\n'
                . '    html_structures::a_link("index.php", html_structures::glyphicon("home","") . " Retour à l\'accueil"),\n'
                . '    html_structures::a_link("https://duckduckgo.com/", html_structures::glyphicon("search","") . " Rechercher sur le web","","(nouvel onglet)", true),\n'
                . '));\n'
                . '?>');
        ?>
        <p>Résultat :</p>
        <?php
        echo html_structures::ul(
                array(
                    html_structures::a_link('index.php', html_structures::glyphicon('home', '') . ' Retour à l\'accueil'),
                    html_structures::a_link('https://duckduckgo.com/', html_structures::glyphicon('search', '') . ' Rechercher sur le web', '', '(nouvel onglet)', true),
                )
        );
        ?>
        <p>Méthodes (toutes static) :</p>
        <?php
        echo html_structures::table(["Méthode", "Description"], [
            ["table", "Retourne un tableau à partir d'un array d'entête et d'un array à deux dimensions comprenant les données"],
            ["ul, ol, dl", "Retourne une liste au format HTML à partir d'un array ( prend en compte l'imbrication des arrays)"],
            ["a_link", "Retourne un lien"],
            ["Ancre", "Retourne une ancre a"],
            ["img", "Retourne une image img"],
            ["figure", "Retourne une figure ( illustration et légende )"],
            ["new_map, area et close_map", "Mapping d'image"],
            ["media", "Retourne les données passées en paramètres sous forme de média (bootstrap)"],
            ["glyphicon", "Retourne un glyphicon (avec un texte alternative)"],
            ["hr", "Retourne un séparateur horizontal"],
            ["time", "La balise time permet d'afficher une date avec une valeur SEO sémantique"],
            ["link_in_body", "Permet de faire appel à une balise LINK dans le body"],
            ["script_in_body", "Permet de faire appel à une balise SCRIPT dans le body"],
            ["script et link", "Sont utilisés par le framework (dans html5.class.php)"],
            ["popover", "Permet d'afficher un lien avec un popover"],
            ["parallax", "Permet d'afficher une DIV qui aura un effet de parallaxe"]
        ]);
    }

    public static function http2() {
        ?>
        <p>
            Cette classe permet de gérer le header LINK de http/2.0 <br />
            elle n'est active que si le protocole http/2 est actif <br />
            si c'est le cas sont renseignés d'office :
        </p>
        <ul>
            <li>Les liens du menu (prérender)</li>
            <li>Les fichiers css et js appelés depuis html_structure (preload)</li>
        </ul>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Ajoute le préchargement d\'une image\n'
                . 'http2::get_instance()->preload("./src/img.jpg");\n'
                . '?>');
    }

    public static function ip_access() {
        ?>
        <p>Cette classe sert à blacklister une liste de plages d'adresses IP en les redirigeant vers un autre site</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//exemple : on bloque tout les access depuis les ip de localhost et on redirige vers DuckDuckGO\n'
                . 'new ip_access(array(array("127.0.0.0", "127.255.255.255")), "http://duckduckgo.com");\n'
                . '?>');
    }

    public static function ip_api() {
        ?>
        <p>
            Cette classe permet de récupérer des informations sur une adresse IP (géolocalisation, opérateur...). <br>
            Attention, json() est limité à 45 requêtes par minute, <br>
            batch() est limité à 15 requêtes par minute et 100 IP max par requête.
            (Cf <a href="https://ip-api.com">https://ip-api.com</a>)
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Le serveur envois une requête a ip-api pour une IP\n'
                . '$ip_infos=ip_api::get_instance()->json($ip);\n\n'
                . '//Le serveur envois une requête a ip-api pour une liste IP (100 max)\n'
                . '$ip_infos=ip_api::get_instance()->batch($ips);\n\n'
                . '//Le client envois une requête a ip-api pour une liste IP (100 max)\n'
                . '$ip_infos=ip_api::get_instance()->json_browser($ip, function($ip_data){\n'
                . '    return $ip_data;\n'
                . '});\n\n'
                . '//Le client envois une requête a ip-api pour une liste IP (100 max)\n'
                . '$ip_infos=ip_api::get_instance()->batch_browser($ip, function($ip_data){\n'
                . '    return $ip_data;\n'
                . '});\n'
                . '?>');
        ?>
        <p>
            Attention pour json_browser() et batch_browser() : les informations transiteront par le navigateur du client ! <br>
            Les informations peuvent être altérées !
        </p>
        <?php
    }

    public static function js() {
        if (isset($_GET["js"]) and in_array($_GET["js"], docPHP_natives_js::get_methods()) and !in_array($_GET["js"], ["get_methods", "__construct"])) {
            $js = $_GET["js"];
            docPHP_natives_js::$js();
        } else {
            new docPHP_natives_js();
        }
    }

    public static function leaflet() {
        ?>
        <p>Cette classe permet d'afficher une carte exploitant OSM (OpenStreetMap)</p>
        <?php
        $leaflet = new leaflet(array("x" => 48.85341, "y" => 2.3488, "zoom" => 13));
        $leaflet->add_marker(48.85341, 2.3488, 'Paris');
        $leaflet->add_marker(51.50853, -0.12574, 'Londres');
        $leaflet->add_marker(50.85045, 4.34878, 'Bruxelles');
        $leaflet->add_circle(50.85045, 4.34878, 100000, 'Belgique');
        $leaflet->add_polygon(array(
            array("x" => "50.9519", "y" => "1.8689"),
            array("x" => "48.582325", "y" => "7.750871"),
            array("x" => "43.774483", "y" => "7.497540"),
            array("x" => " 43,3885129", "y" => "-1,6596374"),
            array("x" => "48,3905283", "y" => "-4,4860088"),
                ), 'Hexagone');
        js::monaco_highlighter('<?php\n' .
                '//Initialise le Leaflet\n' .
                '$leaflet=new leaflet(array("x" => 48.85341, "y" => 2.3488, "zoom" => 13));\n' .
                '//Ajoute des marqueurs\n' .
                '$leaflet->add_marker(48.85341, 2.3488, \'Paris\');\n' .
                '$leaflet->add_marker(51.50853,  -0.12574, \'Londres\');\n' .
                '$leaflet->add_marker(50.85045, 4.34878, \'Bruxelles\');\n' .
                '//Ajoute un cercle autour d\'un point\n' .
                '$leaflet->add_circle(50.85045, 4.34878, 100000, \'Belgique\');\n' .
                '//Ajoute un polygone sur la carte\n' .
                '$leaflet->add_polygon(array(\n' .
                '    array("x"=>"50.9519","y"=>"1.8689"),\n' .
                '    array("x"=>"48.582325","y"=>"7.750871"),\n' .
                '    array("x"=>"43.774483","y"=>"7.497540"),\n' .
                '    array("x"=>" 43,3885129","y"=>"-1,6596374"),\n' .
                '    array("x"=>"48,3905283","y"=>"-4,4860088"),\n' .
                '), \'Hexagone\');\n' .
                '//Affiche la carte\n' .
                '$leaflet->print_map();\n' .
                '//Trace l\'itinéraire sans activer la position de l\'utilisateur\n' .
                '$leaflet->tracer_itineraire($add_client_marker=false);\n' .
                '//Trace l\'itinéraire en ajoutant la position de l\'utilisateur par géolocalisation (droits demandés par le navigateur)\n' .
                '$leaflet->tracer_itineraire(true);\n' .
                '?>');
        echo html_structures::hr();
        $leaflet->print_map();
    }

    public static function log_file() {
        ?>
        <p>Cette classe permet de créer un log sous forme de fichiers. <br />
            Elle vous permet d'enregistrer les comportements anormaux de votre application <br />
            Selon vos paramètres, le log est écrit dans <em>dwf/log/log_[votre_projet]_[date_format_us].txt</em> ou <em>dwf/log/log_[votre_projet].txt</em>
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//instencie l\'objet de log\n' .
                '$log=new log_file($a_log_a_day=false);\n' .
                '//inscrit un message d\'informations dans le log\n' .
                '$log->info($message);\n' .
                '//inscrit un message d\'alerte dans le log\n' .
                '$log->warning($message);\n' .
                '//inscrit un message grave dans le log\n' .
                '$log->severe($message);\n' .
                '?>'
        );
    }

    public static function log_mail() {
        ?>
        <p>Cette classe permet de vous envoyer automatiquement un mail en cas de comportement anormal de votre application</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//instencie l\'objet de log\n' .
                '$log=new log_mail($from, $to);\n' .
                '//envoie un mail d\'informations\n' .
                '$log->info($message);\n' .
                '//envoie un mail d\'alerte\n' .
                '$log->warning($message);\n' .
                '//envoie un mail grave\n' .
                '$log->severe($message);\n' .
                '?>'
        );
    }

    public static function lorem_ipsum() {
        ?>
        <p>Cette classe permet de générer un faux texte (Lorem ipsum) <br />
            Le texte est généré depuis le vocabulaire du texte de Cicero : De finibus. 
            <br />Sources : <br />
            <a href="http://www.thelatinlibrary.com/cicero/fin1.shtml">Liber Primus</a> <br />
            <a href="http://www.thelatinlibrary.com/cicero/fin.shtml">Oeuvre complète</a></p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Lorem Ipsum de 100 mots ne commencant pas par "Lorem ipsum ..."\n' .
                'echo lorem_ipsum::generate(100);\n\n' .
                '//Lorem Ipsum de 100 mots commencant par \n' .
                '//"Lorem ipsum dolor sit amet, consectetur adipiscing elit."\n' .
                'echo lorem_ipsum::generate(100, true);\n\n' .
                '//Lorem Ipsum de 100 mots commencant par \n' .
                '//"Lorem ipsum dolor sit amet, consectetur adipiscing elit."\n' .
                '//et utilisant le vocalulaire de toute l\'oeuvre\n' .
                '//(10035 mots au lieu des 2732 mots du Liber Primus)\n' .
                'echo lorem_ipsum::generate(100, true, true);\n' .
                '?>');
        ?>
        <p>Resultat (<em>lorem_ipsum::generate(100, true)</em>) : </p>
        <p>
            <?php
            echo lorem_ipsum::generate(100, true);
            ?>
        </p>
        <?php
    }

    public static function lurl() {
        ?>
        <p class="alert alert-danger">
            Lurl.fr a férmé définitivement en juillet 2024
        </p>
        <p>
            Cette classe permet de générer des liens LURL ou un bouton LURL sponsorisé qui redirige vers la page courante
            <a href="https://lurl.fr/ref/legagneur">LURL</a>
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '(new lurl($api_key))->get_lurl($url);\n' .
                '(new lurl($api_key))->selfpage_support_btn();\n' .
                '?>'
        );
    }

    public static function mail() {
        ?>
        <p>Cette classe vous permet d'envoyer un mail depuis votre application en une ligne de code (utilise les paramètres SMTP de <em>config.class.php</em>)</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$from="your.mail@your-smtp.com";\n' .
                '$from_name="Your Name";\n' .
                '$to="target@mail.com";\n' .
                '$subject="The Subject";\n' .
                '$msg="Hello World";\n' .
                '(new mail())->send($from, $from_name, $to, $subject, $msg);\n' .
                '?>'
        );
    }

    public static function maskNumber() {
        ?>
        <p>Cette classe permet de formater l'affichage d'un nombre dans un INPUT de type text</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                'maskNumber::set("masknumber");\n' .
                '$form=new form();\n' .
                '$form->input("Nombre", "masknumber");\n' .
                '$form->submit("btn-primary");\n' .
                'echo $form->render();\n' .
                'if(isset($_POST["masknumber"])){\n' .
                '    maskNumber::get("masknumber"); //convertit les saisies dans $_POST\n' .
                '}\n' .
                '?>'
        );
        ?>
        <p>Attention ! maskNumber::set() doit être executé avant l'exécution du formulaire ! <br />
            Résultat :</p>
        <?php
        maskNumber::set("masknumber");
        $form = new form();
        $form->input("Nombre", "masknumber");
        $form->submit("btn-primary");
        echo $form->render();
    }

    public static function math() {
        ?>
        <p>Cette classe contient quelques méthodes statiques mathématiques de base ainsi que des fonctions pour vérifier le type de variables</p>
        <?php
    }

    public static function messageries() {
        ?>
        <p>Cette classe gère la messagerie, elle permet d'envoyer, de réceptionner ou de supprimer un message entre les utilisateurs de l'application.</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Purge les messages de plus de deux ans\n' .
                'messagerie::purge_msg($table_msg, $years = 2);\n' .
                '//Créé une interface de messagerie pour les utilisateurs\n' .
                'new messagerie($table_user, $tuple_user);\n' .
                '?>'
        );
    }

    public static function modal() {
        ?>
        <p>"Modal" est une classe permettant d'afficher des modals (appelées aussi layout ou pop-in). Une "modal" s'ouvre lors d'un clic sur un lien lui correspondant</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$a_text="Cliquez ici pour ouvrir la modal";\n' .
                '$id="demo_modal";\n' .
                '$title="(pop-up)";\n' .
                '$titre="Démonstration";\n' .
                '$data="<p class="text-center">Bienvenue sur la démonstration de modals</p>";\n' .
                '$class="";\n' .
                'echo (new modal())->link_open_modal($a_text, $id, $title, $titre, $data, $class);\n' .
                '?>'
        );
        ?>
        <p>Résultat :</p>
        <?php
        echo (new modal())->link_open_modal("Cliquez ici pour ouvrir la modal", "demo_modal", "(pop-up)", "Démonstration", "<p class=\"text-center\">Bienvenue sur la démonstration de modal</p>", "");
    }

    public static function monaco_editor() {
        ?>
        <p>Cette classe génère un monaco_editor afin d'editer le code du projet courant</p>
        <p class="alert alert-danger">/!\ cette classe est à placer précautionneusement dans une partie administration restreinte !</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'new monaco_editor();\n'
                . '?>');
    }

    public static function openweather() {
        ?>
        <p>Cette classe permet d'afficher la météo d'une ville en temps réel (utilise openweather et nécessite une clé API)</p>
        <?php
        js::monaco_highlighter('<?php new openweather($api_key, $city); ?>');
        ?>
        <p>Le résultat est le suivant (exemple fictif) :</p>        
        <div class = "openwether">
            <p>
                <span class = "dt">Météo de [$city] (<?php date("d/m H:i"); ?>)</span><br>
                <span class = "weather"><span><img src = "../commun/openweather/01d.png" alt = ""><span>
                        </span>Ciel clair</span></span><br><span class = "temp">Température : 19°C</span><br>
                <span class = "pressure">Pression : 1016 Hpa</span><br>
                <span class = "humidity">Humidité : 51%</span><br>
                <span class = "wind">Vent : 10km/h, 120.0° (Sud-Est)</span><br>
            </p>
        </div>
        <?php
    }

    public static function pagination() {
        ?>
        <p>Cette classe gère la pagination dans une page</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$get = "p"; //correspond a $_GEY["p"]\n'
                . '$per_page = 100;\n'
                . '$count_all = mon_entite::get_count();\n'
                . '$pagination = pagination::get_limits($get, $per_page, $count_all);\n'
                . 'foreach (mon_entite::get_table_array("1=1 limit :l1,:l2;",[":l1"=>$pagination[0], ":l2"=>$pagination[1]]) as $value) {\n'
                . '    //affichage des informations\n'
                . '}\n'
                . '//affiche le menu de pagination\n'
                . 'pagination::print_pagination($get, $pagination[3]);\n'
                . '?>');
    }

    public static function paypal() {
        ?>
        <p class="alert alert-warning">
            Le SDK PHP de Paypal est obselette, bien que fonctionant toujours, il sera supprimé a therme.
            Previlégiez d'utiliser ou migrer sur Stripe.
        </p>
        <p>Cette classe permet de créer, vérifier et exécuter des paiements via l'API REST de PayPal</p>
        <p>Exemple d'utilisation :</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//liste des produits que veut acheter l\'utilisateur\n' .
                '$item_list = [\n' .
                '    [\n' .
                '        "Name" => "produit1",\n' .
                '        "Price" => 10.50,\n' .
                '        "Quantity" => 1\n' .
                '    ],\n' .
                '    [\n' .
                '        "Name" => "produit2",\n' .
                '        "Price" => 1.99,\n' .
                '        "Quantity" => 5\n' .
                '    ]\n' .
                '];\n\n' .
                '//Id et Secret renseignés dans l\'application de l\'API REST de PayPal\n' .
                '$clientId="Votre-clientId";\n' .
                '$clientSecret="Votre-clientSecret";\n' .
                '$paypal = new paypal($clientId, $clientSecret);\n\n' .
                'if (!isset($_GET["paypal_action"])) {\n' .
                '    $_GET["paypal_action"] = "";\n' .
                '}\n' .
                'switch ($_GET["paypal_action"]) {\n' .
                '    case "return":\n' .
                '        $payment = $paypal->get_payment($_GET["paymentId"]);\n' .
                '        //TODO: vérifier les données du paiement\n' .
                '        //Exécute le paiement\n' .
                '        $paypal->execute_payment($payment);\n' .
                '        //TODO : envoyer une copie de la facture par mail\n' .
                '        js::alertify_alert_redir("Paiement accepté! retour à l\'accueil", "index.php");\n' .
                '        break;\n' .
                '    case "cancel":\n' .
                '        js::alertify_alert_redir("Vous avez annulé le paiement, retour à l\'accueil", "index.php");\n' .
                '        break;\n' .
                '    default:\n' .
                '        $url = "http://monsite.fr/" . strtr(application::get_url(["paypal_action"]), ["&amp;" => "&"]);\n' .
                '        //créé le paiement et retourne le lien de paiement pour l\'utilisateur ou false en cas d\'erreur\n' .
                '        if ($link = $paypal->create_payment(\n' .
                '                $item_list, 20, "Ventes de monsite.fr", $url . "paypal_action = return", $url . "paypal_action = cancel"\n' .
                '                )) {\n' .
                '            //affiche le lien pour le paiement ( à fournir à l\'utilisateur)\n' .
                '            //A adapter si vous voulez afficher un bouton PayPal "officiel"\n' .
                '            echo html_structures::a_link($link, "Payer");\n' .
                '        }\n' .
                '        break;\n' .
                '}\n' .
                '?>'
        );
        ?>
        <p>Plus de renseignements dans la documentation technique et sur <a href="https://developer.paypal.com" target="_blank">PayPal Developer</a></p>
        <hr>
        <?php
        self::stripe();
    }

    public static function printer() {
        ?>
        <p>Cette classe permet l'export de données aux formats PDF, CSV ou QRCode,<br />
            le script d'export est <i>html/commun/printer.php</i></p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Affiche un bouton d\'export PDF\n'
                . '//(le contenu doit être en HTML sans les balises html et head)\n'
                . 'echo printer::PDF($content, $filename);\n\n'
                . '//Affiche un bouton d\'export CSV\n'
                . '//(le contenu doit être un array a 2 dimentions)\n'
                . 'echo printer::CSV($content, $filename);\n\n'
                . '//Affiche un bouton d\'export QRCODE\n'
                . '//(le contenu doit être un text ou une URL)\n'
                . 'echo printer::QRCODE($content, $get_png_b64 = false);\n\n'
                . '//affiche directement le QRCode\n'
                . '//(l\'image est recupéré en PNG base64)\n'
                . 'echo html_structures::img(printer::QRCODE($content, $get_png_b64 = true));\n'
                . '?>');
    }

    public static function php_finediff() {
        ?>
        <p>Permet d'afficher les différences entre deux chaines de caractères</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'echo php_finediff::DiffToHTML("Texte de départ", "Texte final");'
                . '\n?>');
        debug::print_r(php_finediff::DiffToHTML("Texte de départ", "Texte final"));
    }

    public static function php_header() {
        ?>
        <p>Cette classe permet de modifier le header HTTP</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '// Renseigne le type (mime) du document. Renseignez juste l\'extension du fichier ( par exemple "json" ou "csv"),\n'
                . '// la fonction sera retrouvée le mime corespondant.\n'
                . '(new php_header())->content_type($type, $force_upload_file=false);\n'
                . '// Redirige l\'utilisateur (immédiatement ou avec un délai)\n'
                . '(new php_header())->redir($url, $second=0);\n'
                . '// Définit le statut code de la page, si le statut code est invalide, le code 200 est utilisé par défaut\n'
                . '(new php_header())->status_code($code);\n'
                . '?>');
    }

    public static function php_simple_formatter() {
        ?>
        <p>Cette classe permet de formater (réindenter) du code php/html/js</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$formated_code = (new php_simple_formatter())->format($code)\n'
                . '?>');
    }

    public static function phpini() {
        ?>
        <p>
            Cette classe permet de gérer des profils de configuration PHP (comme avoir plusieurs fichiers php.ini interchangeable à volonté)            
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '// Affiche l\'interface pour créer vos propres profils de configuration.\n'
                . '// Est accessible de base par le fichier html/index.php -> PHPini\n'
                . 'phpini::admin();\n\n'
                . '//Charge un profil standard\n'
                . 'phpini::set_mode(phpini::MODE_DEFAULT);\n\n'
                . '//Les différents profils standards sont :\n'
                . '//Configuration par défaut telle que décrite dans la documentation officielle de php.ini\n'
                . 'phpini::MODE_DEFAULT;\n\n'
                . '//Configuration de développement telle que décrite dans la documentation officiele de php.ini\n'
                . 'phpini::MODE_DEV;\n\n'
                . '//Configuration de production telle que décrite dans la documentation officiel de php.ini\n'
                . 'phpini::MODE_PROD;\n\n'
                . '//Configuration de développement conseillé pour DWF\n'
                . 'phpini::MODE_DWF_DEV;\n\n'
                . '//Configuration de production conseillée pour DWF\n'
                . 'phpini::MODE_DWF_PROD;\n\n'
                . '//Charge un profil de configuration de votre création\n'
                . 'phpini::set_mode(phpini::MODE_CUSTOM,"mon_profil");\n\n'
                . '?>');
    }

    public static function pollinanitons() {
        ?>
        <p>Cette classe permet d'utiliser des outils d'IA en passant par les endpoint de pollinations </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Affiche une immage généré par IA\n'
                . 'echo html_structures::img(pollinations::image_src("perfect realistic landscape of forest"));\n'
                . '//Affiche un texte généré par IA\n'
                . 'echo pollinations::text("ecris un commentaire positif sur un stylo 4 couleur");\n'
                . '//Génére un audio généré par IA\n'
                . 'echo tags::tag("audio", ["controls" => "true", "src" => pollinations::audio_src("Raconte moi une histoire pour enfant")]);\n'
                . '?>');
    }

    public static function pseudo_cron() {
        ?>
        <p>Cette classe permet de lancer des "pseudo cron", <br />
            contairement à des vrais cron qui s'executent à des heures fixes planifiées par le système, <br />
            ici les pseudos cron s'exécutent : lors d'une activité utilisateur et s'il n'a pas été exécuté depuis un certain temps défini.</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '// Instanciation du système de pseudo cron en utilisant un registre json (ou SQL par defaut)\n'
                . '//pseudo_cron est un singleton\n'
                . '$pcron = pseudo_cron::get_instance("json");\n\n'
                . '// Exécute la fonction lors d\'une activité utilisateur \n'
                . '// et si la fonction n\'a pas été appelée au cours des dernières 24 heures (86400 secondes) \n'
                . '$nom = "world";\n'
                . '$pcron->fn(86400, function($nom){\n'
                . '    echo "hello ".$nom;\n'
                . '},[$nom]);\n\n'
                . '// Ou même fonction en utilisant le "use" \n'
                . '$nom = "world";\n'
                . '$pcron->fn(86400, function() use ($nom){\n'
                . '    echo "hello ".$nom;\n'
                . '});\n\n'
                . '// si la fonction retourne un résultat il peut être récupéré,\n'
                . '// si la fonction n\'est pas exécuté fn() retourne null\n'
                . '$result = $pcron->fn(86400, function(){return "hello world";});\n'
                . 'if($result !== null){\n'
                . '    echo $result;\n'
                . '}\n\n'
                . '// Exécute un fichier (toujours dans les mêmes conditions)\n'
                . '// le fichier est exécuté dans une console !\n'
                . '$pcron->file(86400,"mon_chemin/mon_script.php");\n'
                . '?>');
        ?>
        <p>L'intéret des cron étant de pouvoir lancer des opérations lourdes à un rythme régulier sans ralentir l'utilisateur <br />
            il est possible de lancer un pseudo cron de façon "asynchrone" en utilisant un service et la methode service::HTTP_POST().
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'pseudo_cron::get_instance()->fn(86400,function(){\n'
                . '    service::HTTP_POST("http://localhost/mon_projet/services/index.php", ["service"=>"mon_service"]);\n'
                . '});\n'
                . '?>');
        ?>
        <p>Les pseudos cron sont renseignés dans un registe ( soit un fichier json soit une table en base de données ) <br />
            une entrée est supprimée si elle n'est pas mise à jour (exécutée) pendant 1 an, cette durée peut être modifiée via la méthode</p>
        <?php
        js::monaco_highlighter('<?php pseudo_cron::get_instance()->set_clear(31536000); ?>');
    }

    public static function ratioblocks() {
        ?>
        <p>Cette classe permet d'afficher un bloc css avec les proportions passées en paramètres.</p>
        <?php
        js::monaco_highlighter('<?php new ratioblocks($id, $width, $ratio, $contenu); ?>');
    }

    public static function reveal() {
        ?>
        <p>
            Cette classe permet de créer un diaporama avec la librairie reveal <br />
            Il n'est pas recommandé d'avoir plusieurs diaporamas sur la même page.
        </p>
        <?php
        $reveal = new reveal($width = "100%", $height = "300px", $theme = "white");
        js::monaco_highlighter('<?php\n'
                . '$reveal = new reveal($width = "100%", $height = "300px", $theme = "white");\n'
                . '$reveal->start_reveal();\n'
                . '?>\n'
                . '<section><p style="font-size: 48px;">Ceci est un Reveal</p></section>\n'
                . '<section>\n'
                . '    <section><p style="font-size: 48px;">Reveal est une librairie JS qui permet de faire des présentations en HTML</p></section>\n'
                . '    <section><p style="font-size: 48px;">Le PowerPoint est mort, vive le Reveal !</p></section>\n'
                . '</section>\n'
                . '<section><p style="font-size: 48px;">Site officiel de Reveal : <br /><a href="http://lab.hakim.se/reveal-js/">http://lab.hakim.se/reveal-js/</a></p></section>\n'
                . '<?php\n'
                . '$reveal->close_reveal();\n'
                . '?>');
        echo html_structures::hr();
        $reveal->start_reveal();
        ?>
        <section><p style="font-size: 48px;">Ceci est un Reveal</p></section>
        <section>
            <section><p style="font-size: 48px;">Reveal est une librairie JS qui permet de faire des présentations en HTML</p></section>
            <section><p style="font-size: 48px;">Le PowerPoint est mort, vive le Reveal !</p></section>
        </section>
        <section>
            <p style="font-size: 48px;">Site officiel de Reveal : <br />
                <a href="http://lab.hakim.se/reveal-js/">http://lab.hakim.se/reveal-js/</a>
            </p>
        </section>
        <?php
        $reveal->close_reveal();
    }

    public static function reversoLib() {
        ?>
        <p>Cette classe utilise l'API de Reverso pour corriger un texte et 
            vous affiche les corrections à appliquer au texte grâce à la librairie finediff.</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'echo (new reversoLib())->correctionText("Un texte avec une grosse fote");'
                . '\n'
                . '?>');
        debug::print_r("Un texte avec une grosse <del>fote</del><ins>faute</ins>");
    }

    public static function robotstxt() {
        ?>
        <p>
            Cette classe génére le robot.txt <br>
            elle est appelé par <em>html5.class.php</em> <br>
            inutile de l'instancier d'avantage.
        </p>
        <?php
    }

    public static function schoolbreak() {
        ?>
        <p>Affiche un tableau des vacances scolaires</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//exemple avec l\'academie de Amiens\n'
                . 'schoolbreak::fr("Amiens");\n'
                . '?>');
        schoolbreak::fr();
    }

    public static function scraperAPI() {
        ?>
        <p>Cette classe permet de faire appel à ScraperAPI <br />
            <a href="https://scraperapi.com">https://scraperapi.com</a>      
        </p>
        <p class="alert alert-info">
            Attention, le scraping est une pratique faisant l'objet de nombreux flous juridiques. <br />
            L'utilisation de méthodes de scraping à des fins malveillantes peuvent constituer une infraction pénale. <br />
            Les développeurs de DWF se dégagent de toutes responsabilités en cas d'utilisation frauduleuse de cette classe.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Par méthode instanciée\n'
                . '$html = (new scraperapi($api_key))->get($url);\n'
                . '//Par méthode static\n'
                . '$html = scraperapi::getHTML($api_key,$url);\n'
                . '?>');
    }

    public static function selectorDOM() {
        ?>
        <p>Source <a href="https://github.com/tj/php-selector">https://github.com/tj/php-selector</a> <br />
            "selectorDOM" permet de manipuler le DOM d'un document en PHP.
        </p>
        <div class="alert alert-danger" role="alert"><p>Attention à l'utilisation de cette classe sur des pages tiers.<br />
                La copie même partielle d'un site tiers sans autorisation préalable (et en dehors d'une utilisation strictement privée) est un délit.<br />
                (Article L.713-2 du Code de la propriété intellectuelle)</p></div>        

        <?php
        js::monaco_highlighter('<?php\n'
                . '//les selecteurs ont la même syntaxe que Jquery\n'
                . 'selectorDOM::select_elements("header", file_get_contents("http://{$_SERVER["HTTP_HOST"]}{$_SERVER["SCRIPT_NAME"]}?page=index"));\n'
                . '?>');
        ?><p>Résultat :</p><?php
        debug::print_r(json_decode('[{"name":"header","attributes":{"class":"page-header bg-info"},"text":"\r\n        DocumentationDocumentation de DWF\r\n      \r\n    ","children":[{"name":"h1","attributes":[],"text":"\r\n        DocumentationDocumentation de DWF\r\n      ","children":[{"name":"br","attributes":[],"text":"","children":[]},{"name":"small","attributes":[],"text":"Documentation de DWF","children":[]}]}]}]'));
    }

    public static function service() {
        ?>
        <p>
            La classe service est une classe qui permet de communiquer avec des services web d'un tiers ou que vous aurez vous même créé.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//envoie une requête GET à un service et en retourne la réponse \n'
                . 'service::HTTP_GET($url);\n\n'
                . '//envoie une requête POST à un service et en retourne la réponse\n'
                . 'service::HTTP_POST_REQUEST($url, $params, $ssl = false);\n\n'
                . '//envoie une requête POST à un service SANS en retourner la réponse\n'
                . 'service::HTTP_POST($url, $params, $ssl); //$ssl \n\n'
                . '//cette fonction est à utiliser pour filtrer les IP autorisées à acceder à votre script/service\n'
                . 'service::security_check($ip_allow=array("localhost", "127.0.0.1", "::1"));\n\n'
                . '//fonctions de conversion xml/csv -> json\n'
                . '$json=xml_to_json($xml_string);\n'
                . '$json=csv_to_json($csv_string);\n'
                . '?>');
    }

    public static function session() {
        ?>
        <p>La classe session permet de gérer les variables de sessions liées au projet courant,<br />
            cette classe créée vos variables en exploitant config::$_prefix</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'session::get_user(); \n'
                . '//équivaut à $_SESSION[config::$_prefix . "_user"];\n\n'
                . 'session::set_val($key, $value);\n'
                . '//plus simple que $_SESSION[config::$_prefix . "_" . $key] = $value;\n\n'
                . 'session::get_val($key);\n'
                . '//plus simple que $_SESSION[config::$_prefix . "_" . $key];\n'
                . '?>');
    }

    public static function shuffle_letters() {
        ?>
        <p>(cf js)</p>
        <?php
    }

    public static function singleton() {
        ?>
        <p>
            Cette classe sert de base pour créer des singleton. <br />
            la méthode "get_instance" retournera toujours la même instance.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'class ma_classe extends singleton{\n'
                . '    public function une_fonction(){\n'
                . '        return true;\n'
                . '    }\n\n'
                . '    public static function une_fonction_static(){\n'
                . '        //usage en interne\n'
                . '        return self::get_instance()->une_fonction();\n'
                . '    }\n'
                . '}\n'
                . '//usage en externe\n'
                . 'ma_class::get_instance()->une_fonction();'
                . '?>');
    }

    public static function sitemap() {
        ?>
        <p>
            Cette classe (singleton) gère les sitemaps de vos projets.<br>
            Elle permet d'ajouter, de supprimer et de générer des sitemaps au format XML.<br>
            Elle offre également des fonctionnalités pour afficher les URLs dans une liste HTML.<br>
            Les URLs peuvent étre supprimé via une interface d'administration.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//affiche le format HTML du sitemap\n'
                . 'sitemap::get_instance()->html();\n\n'
                . '//Afiche l\'interface d\'administration\n'
                . 'sitemap::get_instance()->admin();\n'
                . '?>');
        ?>
        <p>
            sitemap::get_instance()->add_url() est automatiquement appelé dans <em>html5.class.php</em>, inutile de l'utiliser. <br>
            Seules les URL "publiques" de votre projet sont référencées dans le sitemap, les pages nécessitant une authentification ne le seront pas. <br>
            <em>config::$_sitemap</em> doit être défini à true pour que la classe référence les liens publics.
        </p>

        <?php
    }

    public static function sms_gateway() {
        ?>
        <p>Cette classe permet de faciliter l'utilisation d'un gateway SMS afin de pouvoir envoyer des SMS depuis une application Web. <br />
            Cette classe a été conçue pour fonctionner par défaut avec le logiciel SMS Gateway installé sur un appareil Android.<br />
            <a href="https://dwf.sytes.net/smsgateway">SMS Gateway</a><br />
            Si vous utilisez un autre programme, veuillez à adapter les paramètres en conséquence.</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$sms_gateway = new sms_gateway($gateway_host, $gateway_port = 8080, $gateway_page_send = "sendmsg", $gateway_page_index = "run");\n'
                . '//Retourne si le service répond ou non (true/false)\n'
                . '$sms_gateway->is_runing();\n'
                . '//Envoi de SMS par URL\n'
                . '$sms_gateway->send_by_url(array("phone" => "0654321987", "text" => "le sms"), $methode = "post", $ssl = false);\n'
                . '//Envoi de SMS par URL avec password\n'
                . '$sms_gateway->send_by_url(array("phone" => "0654321987", "text" => "le sms", "psw"=>"motdepasse"), $methode = "post", $ssl = false);\n'
                . '?>');
    }

    public static function sql_backup() {
        ?>
        <p class="alert alert-warning">Une refonte de cette classe est prévu</p>
        <p>
            La classe sql_backup permet de créer des backup quotidiens de la base de données <br />
            les backup peuvent être stockés sur un disque dur (différent du disque de l'application) ou sur un (s)ftp distant <br />
            il est recommandé de placer la ligne de sql_backup dans le constructeur de <em>pages.class.php</em>
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//créé un backup quotidien dans un dossier\n'
                . '(new sql_backup())->backup_to_path($path);\n\n'
                . '//créé un backup quotidien sur un (s)ftp distant\n'
                . '(new sql_backup())->backup_to_ftp($dir, $host, $login, $psw, $ssl);\n'
                . '?>');
    }

    public static function sse_sender() {
        ?>
        <p>Cette classe permet l'envoi de SSE (Server-Sent Events) <br />
            Optimisé pour http/2 et supérieur</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Envoi un événement "info" à tous les utilisateurs\n' .
                'sse_sender::get_instance()->send("info", "It\'s an information");\n\n' .
                '//Envoi un événement "info" à un utilisateur particulier (identifaint de l\'utilisateur)\n' .
                'sse_sender::get_instance()->send("info", "It\'s an information for you", 1);\n' .
                '?>'
        );
        ?>
        <p>Côté client, JS</p>
        <?php
        js::monaco_highlighter('es = new EventSource("./services/index.php?service=sse");\n'
                . 'es.addEventListener("info", function (e) {\n'
                . '    //do somthing of content in e.data\n'
                . '});', "js");
    }

    public static function stalactite() {
        ?>
        <h3 class="text-center">js::stalactite</h3>
        <hr>
        <?php
        docPHP_natives_js::stalactite();
    }

    public static function statistiques() {
        ?>
        <p>
            Cette classe permet de recueillir et d'afficher des statistiques liées à l'activité des utilisateurs. <br>
            Les statistiques sont stoqué pour 5 ans maximum <br>
            la classe utilise <em>ip_api</em> pour déterminer le pays et l'operateur de l'utilisateur
            <em>config::$_statistiques</em> doit être définis a true pour que les statistiques soit enregistré
        </p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Désactive l\'enregistrement de statistiques pour la page courante \n' .
                'statistiques::get_instance()->disable();\n\n' .
                '//Affiche l\'interface pour consulter les statistiques (a placer dans l\'administration)\n' .
                'statistiques::get_instance()->html();\n' .
                '?>'
        );
    }

    public static function stripe() {
        ?>
        <p>Cette classe permet de metre en place un système de payement par stripe</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$item_list = [\n'
                . '    [\n'
                . '        "Name" => "votre produit ou service",\n'
                . '        "Price" => "10.00", //prix HT\n'
                . '        "Quantity" => 1\n'
                . '    ]\n'
                . '];\n'
                . '$stripe = new stripe("API_KEY");\n'
                . '$base_url = "https://votre-site.fr/" . application::get_url(["stripe_action"], false);\n'
                . '$success = $base_url . "stripe_action=return";\n'
                . '$cancel = $base_url . "stripe_action=cancel";\n'
                . 'if (!isset($_GET["stripe_action"])) {\n'
                . '    $_GET["stripe_action"] = "";\n'
                . '}\n'
                . 'switch ($_GET["stripe_action"]) {\n'
                . '    case "return":\n'
                . '        $session = $stripe->get_session($_GET["stripe_id"], $price);\n'
                . '        if ($session) {\n'
                . '            //Succes du payement, ajouter vos actions ici (maj de base de donnée, facture, envoi de mail...)\n'
                . '            js::alertify_alert_redir("Payement accepté !", application::get_url(["stripe_action", "stripe_id"]), "Félicitations !");\n'
                . '    } else {\n'
                . '        js::alertify_alert_redir("Erreur lors de la transaction", "index.php");\n'
                . '    }\n'
                . '    break;\n'
                . '    case "cancel":\n'
                . '        js::alertify_alert_redir("Vous avez annulé le paiement, retour à l\'accueil", "index.php");\n'
                . '    break;\n'
                . '    default:\n'
                . '        if ($url = $stripe->create_checkout_session($item_list, $success, $cancel, $tva = 20)) {\n'
                . '            //Bouton de payement a perssonaliser\n'
                . '            echo html_structures::a_link($url, "Payer", "btn btn-primary");\n'
                . '        } else {\n'
                . '            echo tags::tag("p", ["class" => "alert alert-warning"], "Notre système de paiement est momentanément indisponible.<br>Nous vous invitons à réessayer ultérieurement. Veuillez nous excuser pour la gêne occasionnée.");\n'
                . '        }\n'
                . '    break;\n'
                . '}\n?>');
    }

    public static function sub_menu() {
        ?>
        <p>La classe sub_menu vous permet de créer un sous-menu en utilisant un système de "sous-routes"</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '$key = "key"; //clé principale utilisée dans les sous-routes\n' .
                '$route = array(\n' .
                '    array("key" => "sous_route_1", "title" => "title 1", "text" => "texte 1"),\n' .
                '    array("key" => "sous_route_2", "title" => "title 2", "text" => "texte 2"),\n' .
                '    array("key" => "sous_route_masque", "title" => "titre de la route masquée"),\n' .
                ');\n' .
                '$route_default = "sous_route_1"; //route par défaut\n' .
                'new sub_menu($this, $route, $key, $route_default);\n' .
                '//$this est l\'acceseur de la classe courante,\n' .
                '//cette classe devra par la suite disposer de méthodes publiques ayant pour nom les valeurs des routes\n' .
                '//ici notre classe devra contenir les méthodes publiques : \n' .
                '//sous_route_1(), sous_route_2() et sous_route_masque()\n' .
                '?>'
        );
    }

    public static function monaco_highlighter() {
        ?>
        <h3 class="text-center">js::monaco_highlighter</h3>
        <hr>
        <?php
        docPHP_natives_js::monaco_highlighter();
    }

    public static function tags() {
        ?>
        <p>
            Cette classe permet de créer et manipuler des balises HTML avant de les afficher.
        </p>
        <p class="alert alert-info">
            Note 21.18.08 : dès sa création, cette classe est devenue centrale dans la génération de HTML dans les classes natives du framework. <br />
            Utiliser cette classe permet d'obtenir un code 100% PHP plus lisible et plus facile à maintenir qu'un code PHP "entrecoupé" de codes HTML. <br />
            Les impacts négatifs de cette classe sur l'utilisation de la mémoire et le temps d'exécution de PHP sont très faibles. <br />
            Bien entendu l'utilisation de cette classe dans vos projets et classes métiers reste facultative 
        </p>
        <p>Usage :</p>
        <?php
        js::monaco_highlighter('<?php\n' .
                '//Créé une balise p.maClasse et la retourne sous forme d\'une chaine de caractère\n' .
                'echo tags::tag("p", ["class"=>"maClasse"], "mon contenu");\n\n' .
                '//Créé une balise auto-fermée\n' .
                'echo tags::tag("input", ["name"=>"monInput"], false);\n\n' .
                '//Créé une balise ul.maListe sous forme d\'objet\n' .
                '$ul = tags::ul(["class"=>"maListe"], "");\n\n' .
                '//Ajout/modification d\'un attribut \n' .
                '$ul->set_attr("id", "maListe");\n\n' .
                '//Suppression d\'un attribut \n' .
                '$ul->del_attr("class");\n\n' .
                '//Retourne la valeur d\'un attribut\n' .
                '$ul->get_attr("class"); //retournera null si l\'attribut n\'existe pas\n\n' .
                '//Retourne le tag de la balise\n' .
                '$ul->get_tag(); //retourne "ul"\n\n' .
                '//Redéfinit le tag de la balise \n' .
                '$ul->set_tag("ol");\n\n' .
                '//Retourne le contenu de la balise\n' .
                '$ul->get_content();\n\n' .
                '//Redefinit le contenu de la balise, (ici une balise li)\n' .
                '$ul->set_content(tags::tag("li", [], "1e item"));\n\n' .
                '//Ajoute du contenu à la balise\n' .
                '$ul->append_content(tags::tag("li", [], "2e item"));\n\n' .
                '//Affiche la balise et son contenu\n' .
                'echo $ul;\n' .
                '?>'
        );
        ?>
        <p>Exemple :</p>
        <div class="row">
            <div class="col-sm-6">
                <p>Code :</p>
                <?php
                js::monaco_highlighter('<?php\n' .
                        '$ul = tags::ul();\n' .
                        'foreach (["Pomme", "Pêche", "Poire", "Abricot"] as $fruit) {\n' .
                        '    $ul->append_content(tags::tag("li", [], $fruit));\n' .
                        '}\n' .
                        'echo tags::tag("div", [], tags::tag(\n' .
                        '     "p", [], "Ma liste de " . tags::tag(\n' .
                        '         "strong", [], "fruit")\n' .
                        '     ) . $ul\n' .
                        ');\n\n' .
                        '// ou plus simplement avec html_structures\n' .
                        'echo tags::tag("div", [], tags::tag(\n' .
                        '     "p", [], "Ma liste de " . tags::tag(\n' .
                        '         "strong", [], "fruit")\n' .
                        '     ) . html_structures::ul(["Pomme", "Pêche", "Poire", "Abricot"])\n' .
                        ');\n' .
                        '?>'
                );
                ?>
            </div>
            <div class="col-sm-6">
                <p>Résultat :</p>
                <?php
                echo tags::tag('div', [], tags::tag(
                                'p', [], 'Ma liste de ' . tags::tag(
                                        'strong', [], 'fruit')
                        ) . html_structures::ul(['Pomme', 'Pêche', 'Poire', 'Abricot'])
                );
                ?>
            </div>
        </div>
        <?php
    }

    public static function task_manager() {
        ?>
        <p>
            Cette classe permet de gérer les tâches planifiées. <br />
            Contrairement à un pseudo cron qui dépend de l'activité des utilisateurs, <br />
            les tâches planifiées sont lancées à une heure précise, mais cela nécessite l'exécution permanente d'un script en CLI.
        </p>
        <h5>Initialisation</h5>
        <p>
            La méthode statique suivante doit être appelée dans votre projet,<br />
            cela initialisera une table et une entité "task" ainsi qu'un dossier "task_worker" dans votre projet.
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'task_manager::init();\n'
                . '?>');
        ?>
        <h5>Workers</h5>
        <p>Un worker est une classe qui sera utilisée pour effectuer une tâche précise, <br />
            Exemple : <br />
        </p>
        <ul>
            <li>Envoyer des mails</li>
            <li>Nettoyer/sauvegarder la base de données</li>
            <li>Lancer des audits et créer des rapports</li>
        </ul>
        <p>Il doit être placé dans le dossier "task_worker" et se nommer "[nom_du_worker].worker.php"</p>
        <h6>Exemple de worker (affiche un hello world dans la console)</h6>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'class hello {\n'
                . '    public static function run($param = []) {\n'
                . '        cli::write("Hello world !");\n'
                . '    }\n'
                . '}\n'
                . '?>');
        ?>
        <p>A noter que les classes du framework sont accessibles depuis les workers, un worker peut donc faire appel au task_manager pour programmer une nouvelle tâche (en cas d'erreur par exemple). <br />
            Exemple : votre worker doit envoyer un mail, l'envoi échoue car le SMTP est indisponible, vous pouvez capturer l'erreur pour programmer une nouvelle tentative d'envoi dans 1 heure.</p>
        <h5>Utilisation (dans le projet)</h5>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Programme une tâche utilisant le worker "send_mail" dans 30 secondes,\n'
                . '//on indique le mail dans le tableau de paramètres\n'
                . 'task_manager::add(time() + 30, "send_mail", ["mail" => "someone@host.com"]);\n\n'
                . '//Affiche les tâches en attente et terminées (à placer dans une administration\n'
                . 'task_manager::print_tasks();'
                . '?>'
        );
        ?>
        <h5>Execution</h5>
        <p>Pour lancer le script d'exécution des tâches, il faut ouvrir un terminal et exécuter la commande suivante :</p>
        <?php
        js::monaco_highlighter("php [votre-projet]/task_worker/run.php");
    }

    public static function template() {
        ?><p>Cette classe permet d'utiliser des templates en utilisant la librairie  
            <?= html_structures::a_link("https://www.smarty.net/docsv2/fr/index.tpl", "Smarty") ?></p>
        <p>Les templates doivent étre créés dans le dossier <em>html/[votre-projet]/class/tpl</em> <br /> 
            ce dossier peut être créé par la classe template si vous ne le créez pas au préalable <br />
            le ficher de template doit être un fichier .tpl ( exemple <em>mon_template.tpl</em>) <br />
            les droits en écriture sur le dossier <em>html/[votre-projet]/class/tpl.compile</em> doivent être donnés au service web
        </p>
        <p>exemple, ficher <em>mon_template.tpl</em></p>
        <?php
        js::monaco_highlighter(''
                . '<p>Bienvenue { $name}</p>\n'
                . '<div class="row">\n'
                . '    <div class="col-sm-6">\n'
                . '        <ul>\n'
                . '            {foreach from=$list item=value}\n'
                . '                <li>{ $value}</li>\n'
                . '            {/foreach}\n'
                . '        </ul>\n'
                . '    </div>\n'
                . '    <div class="col-sm-6">\n'
                . '        <dl class="dl-horizontal">\n'
                . '            {foreach from=$list_asso key=key item=value}\n'
                . '                <dt>{ $key}</dt> <dd>{ $value}</dd>\n'
                . '            {/foreach}\n'
                . '        </dl>\n'
                . '    </div>\n'
                . '</div>'
                . '');
        ?>
        <p>Appel du template dans le code php (pages.class.php par exemple)</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'new template("mon_template", [\n'
                . '    "name" => "Matthieu",\n'
                . '    "list" => ["une","simple","liste"],\n'
                . '    "list_asso" => [\n'
                . '        "une liste"=>"associatif",\n'
                . '        "tel"=>"0123456789",\n'
                . '        "mail"=>"mon.mail@monfai.fr"\n'
                . '        ]\n'
                . ']);\n'
                . '?>'
        );
    }

    public static function tenor() {
        ?>
        <p>
            Cette classe permet de retourner des Gifs de l'API TENOR
        </p>
        <p>
            Afin d'etre utilisable, la clé api doit etre renseignée dans <strong>config.class.php</strong> ainsi :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'public static static $_tenor_key= "VOTRE CLE API";\n'
                . '?>');
        echo'<p>Usage :</p>';
        js::monaco_highlighter('<?php\n'
                . 'new tenor();\n'
                . '?>');
    }

    public static function thread_manager() {
        ?>
        <p>Cette classe permet de multi-thread une fonction static avec un tableau de données</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'maclass{\n'
                . '    public static function ma_fonction_static($param1,$param2){\n'
                . '        usleep(500000); //simule un temps d\'execution de 0.5s\n'
                . '        return "something";\n'
                . '    }\n'
                . '}\n\n'
                . '$data=[\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ["param1","param2",...],\n'
                . '    ...\n'
                . '];\n'
                . '$static_function="maclass::ma_fonction_static";\n'
                . '$maxthread=4;\n'
                . '$manager = new thread_manager($data, $static_function, $maxthead);\n'
                . '$results = $manager->get_results();\n'
                . '//temps d\'execution théorique (pour 20 lignes dans $data):\n'
                . '?>');
        ?>
        <p>Notes sur les temps théoriques et rééls d'execution :</p>
        <ul>
            <li>Temps téhorique et réél monothread classique : 10s</li>
            <li>Temps téhorique multi-thread (4) : 2.5s</li>
            <li>temps réel multi-thread (4) : 3.5s</li>
        </ul>
        <p>L'ecart entre le temps réel et théorique est du aux requetes SQL et HTTP au service de threads mais reste avantageux face au monothread</p>
        <p>Si vous utilisez cette classe gardez en tête que PHP n'est pas conçu pour le multi-threading et que cette classe est juste un outil pour paralleliser des traitements de données</p>
        <?php
    }

    public static function time() {
        ?>
        <p>La classe "time" permet d'effectuer des calculs sur les dates et des conversions de format de dates US <=> FR</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Démarre un chronomètre pour chronometrer la durée d\'éxécution d\'un bout de code,\n'
                . '//il est possible d\'utiliser plusieurs chronomètres en leurs spécifiant un identifiant \n'
                . '//l\'identifiant peut être un nombre ou une chaine de caractères\n'
                . 'time::chronometer_start($id = 0);\n\n'
                . '//Retourne le temps mesuré par un chronomètre depuis son lancement\n'
                . 'time::chronometer_get($id = 0);\n\n'
                . '//retourne si une année est bissextile ou non.\n'
                . 'time::anne_bisextile($an);\n\n'
                . '//Retourne le mois "en lettres" du numéro de mois passé en paramètre\n'
                . 'time::convert_mois($num_mois);\n\n'
                . '//Convertit une date au format FR (dd/mm/yyyy) au format US (yyyy-mm-dd) \n'
                . 'time::date_fr_to_us($dateFR);\n\n'
                . '//Convertit une date au format US (yyyy-mm-dd) au format FR (dd/mm/yyyy)\n'
                . 'time::date_us_to_fr($dateUS);\n\n'
                . '//Cette fonction permet d\'additioner ou de soustraire un nombre de mois à une date initiale\n'
                . 'time::date_plus_ou_moins_mois($date, $mois);\n\n'
                . '//Affiche un élément de formulaire pour renseigner une date (jour/mois/année)\n'
                . '//(il est plus commun d\'utiliser un datepicker (cf form)\n'
                . 'time::form_date($label, $post, $value = null);\n\n'
                . '//Retourne la date saisie dans l\'élément de formulaire time::form_date()\n'
                . 'time::get_form_date($post);\n\n'
                . '//Retourne un tableau d\'informations sur la date passée en paramètre\n'
                . 'time::get_info_from_date($date_us);\n\n'
                . '//Retourne le nombre de jours dans un mois \n'
                . '//(l\'année doit être renseignée pour gérer les années bissextiles)\n'
                . 'time::get_nb_jour($num_mois, $an);\n\n'
                . '//Retourne l\'âge actuel en fonction d\'une date de naissance\n'
                . 'time::get_yers_old($d, $m, $y);\n\n'
                . '//Parse un temps en secondes en jours/heures/minutes/secondes \n'
                . '//pour les temps inférieurs à 1 seconde, le parse peut se faire en millisecondes ou microsecondes\n'
                . 'time::parse_time($secondes);\n\n'
                . '//astuce pour afficher un chronomètre bien présenté\n'
                . 'echo time::parse_time(time::chronometer_get($id));\n'
                . '?>');
    }

    public static function tinymce() {
        ?>
        <p class="alert alert-warning">
            <span>Deprecié depuis la verssion 21.25.02, utilisez plutot Summernote</span>
        </p>
        <hr>
        <h3 class="text-center">js::summernote</h3>
        <hr>
        <?php
        docPHP_natives_js::summernote();
    }

    public static function tor() {
        ?>
        <p>Cette classe permet de recupérer une ressource en passant par tor</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$data = (new tor())->wget($url);\n'
                . '?>');
    }

    public static function trad() {
        ?>
        <p>Cette classe permet de créer des traductions à partir de clés, <br />
            l'administration de clés=>traductions se fait par une interface à placer dans la partie administration de l'application. <br />
            le langage de l'utilisateur est défini dans session::get_lang() (peut être modifié par session::set_lang()) <br />
            Les traductions peuvent être gérées en base de données (par défaut) ou par des fichier JSON (CF : paramètres du constructeur)
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//affiche l\'interface d\'administration\n'
                . '(new trad())->admin();\n\n'
                . '//affiche les traductions liées au clés \'CLE_1\' et \'CLE_2\'\n'
                . '$trad=new trad();\n'
                . 'echo $trad->t("CLE_1");\n'
                . 'echo $trad->t("CLE_2");\n'
                . '?>'
        );
    }

    public static function update_dwf() {
        ?>
        <p>Cette classe permet de gérer les mises à jour de DWF (à placer dans une interface d'administration) <br />
            ATTENTION ! GIT doit être installé sur la machine hôte !
        </p>
        <?php
        js::monaco_highlighter('<?php new update_dwf(); ?>');
        $vers = "21.25.02";
        $versm1 = "21.24.10";
        $vgit = "2.34.1";
        echo html_structures::table(["Version GIT courante", "Version DWF courante", "Dernière version DWF disponible", "Statut / Mise à jour"], [
            ["git version " . $vgit, $vers, $vers, "Already up-to-date."],
            ["OU", "", "", ""],
            ["git version " . $vgit, $versm1, $vers, '<input type="submit" class="btn btn-block btn-primary" value="Update from ' . $versm1 . ' to ' . $vers . '" />']
        ]);
    }

    public static function video() {
        ?>
        <p>Cette classe permet d'afficher une vidéo avec un player accessible</p>
        <?php
        js::monaco_highlighter('<?php new video("./files/videos/nuagesMusicman921.webm",$id="video-js"); ?>');
        ?>
        <div style='width:600px' class="mx-auto">
            <?php
            new video('./files/videos/nuagesMusicman921.webm');
            ?>
        </div>
        <p>Credit : <br />
            Vidéo : Nuages - Libre de Droits <a href="https://www.youtube.com/watch?v=NqIw5wHvGYQ">https://www.youtube.com/watch?v=NqIw5wHvGYQ</a> <br />
            Musique  : Dread (v2) - musicman921 <a href="https://musicman921.newgrounds.com/">https://musicman921.newgrounds.com/</a>
        </p><?php
    }

    public static function vpage() {
        ?>
        <p>Affiche une page virtuelle (iframe) à partir du contenu fourni</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'ob_start();\n'
                . '//contenu'
                . 'new vpage(ob_get_clean(),$title="vpage",$ttl = 86400);\n'
                . '?>');
    }

    public static function vticker() {
        ?>
        <h3 class="text-center">js::vTicker</h3>
        <hr>
        <?php
        docPHP_natives_js::vTicker();
    }

    public static function w3c_validate() {
        ?>
        <p>Inscrit les erreurs HTML du site dans le log. <br />
            requiert que le sitemap soit actif, et que le site soit en ligne </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//vérifie si les pages du site sont conformes W3C\n'
                . 'new w3c_validate();\n'
                . '//Retourne le statut de la page passée en paramètre\n'
                . '//(si la page est conforme W3C)\n'
                . 'w3c_validate::validate_from_url($url);\n'
                . '?>');
    }

    public static function websocket() {
        ?>
        <p> 
            Les WebSocket permettent de faire de la communication en temps réel (Real Time Connexion, RTC). <br />
            L'utilisation première des WebSocket est pour les tchats entre utilisateurs 
            mais ils peuvent aussi être utilisé pour notifier un utilisateur <br />
            ou afficher une donnée très variable dans le temps en temps réel (exemple : un stock dans une application de gestion)
        </p>
        <p>
            Un serveur de WebSocket tourne indépendament du serveur web et écoute son propre port (9000 par défaut dans DWF, parametrable dans la config du projet). <br />
            il est possible de lancer le serveur en mode console (CLI), notamment pour débugger :
        </p>
        <?php
        js::monaco_highlighter('php [chemin]/html/[votre-projet]/websocket/index.php');
        ?>
        <p>En production, il est possible de laisser l'application lancer elle même le serveur de websocket en utilisant la classe services pour lancer une requête qui lancera le serveur :</p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'service::HTTP_POST("http://localhost/[votre-projet]/websocket/index.php");\n'
                . '?>');
        ?>
        <p>L'application ne lancera le serveur qu'une seule fois.</p>
        <p>Coté client, la connexion peut être géré avec l'objet <?= html_structures::a_link("https://javascript.info/websocket", "JS natif WebSocket", "", "", true) ?> </p>
        <?php
        js::monaco_highlighter(''
                . '    var socket = null;\n'
                . '    (function start_websocket() {\n'
                . '        socket = new WebSocket("ws://localhost:9000/");\n'
                . '        socket.addEventListener("open", function (e) {\n'
                . '            //cette ligne permet d\'authentifier un utilisateur qui serait authentifié sur l\'aplication\n'
                . '            socket.send(\'{"action": "auth", "token": "<?= websocket_server::auth() ?>"}\');\n'
                . '        });\n'
                . '        socket.addEventListener("close", function (e) {\n'
                . '            //retente une connexion toutes les 10 secondes en cas de coupure\n'
                . '            socket = null;\n'
                . '            let si_ws_reco = setInterval(function () {\n'
                . '                if (socket) {\n'
                . '                    start_websocket();\n'
                . '                    clearInterval(si_ws_reco);\n'
                . '                }\n'
                . '            }, 10000);\n'
                . '        });\n'
                . '        socket.addEventListener("message", function (e) {\n'
                . '            data = JSON.parse(e.data);\n'
                . '            //verifie si l\'utilisateur est authentifié\n'
                . '            if (undefined != data.auth) {\n'
                . '                if (data.auth) {\n'
                . '                    //l\'utilisateur est authentifié\n'
                . '                } else {\n'
                . '                    //l\'utilisateur n\'est pas authentifié, affiche l\'erreur\n'
                . '                    alert(data.message);\n'
                . '                }\n'
                . '            }\n'
                . '        });\n'
                . '    })();\n');
        ?>
        <p>
            ATTENTION ! Actuellement DWF ne gère pas le tunnel de chiffreement (SSL/TLS), <br />
            c'est à vous de le mettre en place via le système de proxy de votre serveur web.
        </p>
        <p>
            Les websockets de DWF fonctionnent avec l'envoi et la reception de chaines JSON. <br />
            dans les chaines d'envoi vers le serveur une clé "action" est obligatoire afin d'indiquer au websocket quel traitement appliquer. <br />
            le reste des clés sont libre. <br />
            la seule action définie par défaut et l'action d'authentification qui prend en seconde clé un token d'authentification (qui peut être vide) :
        </p>
        <?php
        js::monaco_highlighter('{"action":"auth","token":""}');
        ?>
        <p>Le retour est une des ses possibilité :</p>
        <?php
        js::monaco_highlighter('{"auth":false,"message":"Token empty"}\n' .
                '{"auth":false,"message":"Invalid token"}\n' .
                '{"auth":false,"message":"Token conflict"}\n' .
                '{"auth":true,"message":"OK"}');
        ?>
        <p>Pour rajouter une action, il suffit de rajouter une classe dans le dossier "websocket" du projet qui sera nommé : <br />
            <strong>[nomDeLAction].ws.php</strong> <br />
            et qui doit avoir la forme suivante :
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . 'class nomDeLAction {\n'
                . '\n'
                . '    /**\n'
                . '     * \n'
                . '     * @param websocket_client $client le client qui a emit la requête\n'
                . '     * @param array $message La chaine JSON déja convertie en tableau\n'
                . '     */\n'
                . '    public function __construct(&$client, &$message) {\n'
                . '        //traitement à faire\n'
                . '        //Les classes de DWF et les Entity du projet sont utilisables\n'
                . '\n'
                . '        //envoi une réponse à l\'émetteur\n'
                . '        $client->write("réponse");\n'
                . '    }\n'
                . '}\n'
                . '?>\n');
        ?>
        <p>La classe <strong>websocket_client</strong> permet de gérer les utilisateurs connectés, <br />
            elle possède aussi des méthodes statiques qui permettent de sélectionner d'autres utilisateurs connectés. <br />
            Gardez en tête qu'un utilisateur peut avoir des connexions multiples (s'il ouvre plusieurs onglets par exemple).
        </p>
        <p>
            La classe <strong>websocket_request</strong> permet de lancer des requetes au serveur websocket depuis PHP
        </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '$wr = new websocket_request($host = "127.0.0.1", $port = 9000);\n'
                . '//envoie un message sans attendre de réponse\n'
                . '$wr->send("message");\n'
                . '//envoie un message en attendant une réponse\n'
                . '$reponse = $wr->request("message");\n'
                . '//Ferme la connexion\n'
                . '$wr->close();\n'
                . '?>\n');
    }

    public static function wled() {
        ?>
        <p>Cette classe permet d'exploiter l'API HTTP de <a href="https://github.com/Aircoookie/WLED" target="_blank">WLED</a></p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//créé un objet WLED\n'
                . '$wled = new wled("192.168.1.10");\n'
                . '//change les couleur rouge, vert et bleu et envoie la requête a WLED\n'
                . '$wled->set_red(255)->set_green(255)->set_blue(255)->exec();\n'
                . '//redémarre WLED\n'
                . '$wled->sreboot()->exec();\n'
                . '?>');
    }

    public static function writer() {
        ?>
        <p>Cette classe permet de gérer un buffer à l'écriture de fichiers </p>
        <?php
        js::monaco_highlighter('<?php\n'
                . '//Ajoute un fichier au buffer\n'
                . 'writer::get_instance()->add($file, $content);' . '\n\n'
                . '//Vérifie si un ficher est dans le buffer\n'
                . 'writer::get_instance()->exist($file);' . '\n\n'
                . '//Retourne le contenu d\'un fichier du buffer (chaine vide si non)\n'
                . 'writer::get_instance()->content($file);' . '\n\n'
                . '//Retourne le nombre de fichiers dans le buffer\n'
                . 'writer::get_instance()->count();' . '\n\n'
                . '//Supprime un fichier du buffer\n'
                . 'writer::get_instance()->clear($file);' . '\n\n'
                . '//Supprime tout les fichiers du buffer\n'
                . 'writer::get_instance()->clear();' . '\n\n'
                . '//Ecrit les fichiers du buffer sur le disque dur (et vide le buffer)\n'
                . 'writer::get_instance()->write();' . '\n\n'
                . '//Ecrit les fichiers du buffer dans une archive (et vide le buffer)\n'
                . 'writer::get_instance()->write_zip($zipname);\n'
                . '?>'
        );
    }
}
