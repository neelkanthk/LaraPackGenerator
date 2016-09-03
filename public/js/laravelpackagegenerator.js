/*
 * Custom JS
 */

$(function () {
    $('.form-control').on("keydown", function (e) {
        //console.log(e.keyCode);
        return e.which != 32;
    });
});