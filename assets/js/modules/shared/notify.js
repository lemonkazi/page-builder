(function () {
	"use strict";
		
	$.notify.addStyle('custom', {
        html: "<div><span data-notify-html/></div>",
        classes: {
            base: {
                "white-space": "nowrap",
                "background-color": "#2D3E4F",
                "padding": "10px 20px",
                "border-radius": "5px",
                "z-index": "100000"
            },
            joy: {
                "color": "#ffffff",
                "background-color": "RGBA(25, 180, 145, .75)"
            },
            bummer: {
                "color": "#ffffff",
                "background-color": "RGBA(228, 66, 52, .75)"
            }
        }
    });

    module.exports.config = {
    	position:"bottom right",
        style: "custom"
    };

}())