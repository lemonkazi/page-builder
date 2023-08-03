/* globals siteUrl: false */
/*
    scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/jquery-ui.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");

(function () {
    "use strict";

    $.ajax({
        url: siteUrl + "autoupdate/update",
        dataType: 'json',
        method: 'get'
    }).done(function (ret) {

        $('#content').append($(ret.content));

    });

}());