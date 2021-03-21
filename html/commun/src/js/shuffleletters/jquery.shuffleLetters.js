/**
 * @name		Shuffle Letters
 * @author		Martin Angelov
 * @version 	        1.0
 * @url			http://tutorialzine.com/2011/09/shuffle-letters-effect-jquery/
 * @license		MIT License
 * @constructor         LEGAGNEUR Matthieu
 */

(function ($) {
    $.fn.shuffleLetters = function (prop) {
        var options = $.extend({
            "step": 8, // How many times should the letters be changed
            "fps": 25, // Frames Per Second
            "text": "", // Use this text instead of the contents
            "callback": function () {}	// Run once the animation is complete
        }, prop);
        return this.each(function () {
            var el = $(this);
            // Preventing parallel animations using a flag;
            if (el.data('animated')) {
                return true;
            }
            el.data('animated', true);
            var str = (options.text ? options.text.split('') : el.html().split(''));
            // The types array holds the type for each character;
            // Letters holds the positions of non-space characters;
            var types = [];
            var letters = [];
            // Looping through all the chars of the string
            $.each(str, function (i, ch) {
                if (ch == " ") {
                    types[i] = "space";
                } else if (/[a-z]/.test(ch)) {
                    types[i] = "lowerLetter";
                } else if (/[A-Z]/.test(ch)) {
                    types[i] = "upperLetter";
                } else {
                    types[i] = "symbol";
                }
                letters.push(i);
            });
            el.html("");
            // Self executing named function expression:
            (function shuffle(start) {
                // This code is run options.fps times per second
                // and updates the contents of the page element
                var len = letters.length;
                var strCopy = str.slice(0);	// Fresh copy of the string
                if (start > len) {
                    // The animation is complete. Updating the
                    // flag and triggering the callback;
                    el.data('animated', false);
                    options.callback(el);
                    return;
                }
                // All the work gets done here
                for (var i = Math.max(start, 0); i < len; i++) {
                    // The start argument and options.step limit
                    // the characters we will be working on at once
                    // Gener ate a random character at thsi position
                    strCopy[letters[i]] = (i < start + options.step ? randomChar(types[letters[i]]) : "");
                }
                el.html(strCopy.join(""));
                setTimeout(function () {
                    shuffle(start + 1);
                }, 1000 / options.fps);
            })(-options.step);
        });
    };
    function randomChar(type) {
        switch (type) {
            case "lowerLetter":
                var arr = "abcdefghijklmnopqrstuvwxyz0123456789".split('');
                break;
            case "upperLetter":
                var arr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789".split('');
                break;
            case "symbol":
            default :
                var arr = ",.?/\\(^)![]{}*&^%$#'\"".split('');
                break;
        }
        return arr[Math.floor(Math.random() * arr.length)];
    }
})(jQuery);