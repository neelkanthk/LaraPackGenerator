/*
 * Custom JS
 */

$(function () {
    $('.no_special_chars').on("keydown", function (e) {
        //console.log(e.keyCode);
        return e.which != 32;
    });
});