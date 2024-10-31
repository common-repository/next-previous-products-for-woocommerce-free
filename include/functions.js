//functions.js
////version: 1.1

jQuery(document).ready(function($){
    $('.colorpicker').wpColorPicker();
});

function nssw_star_arrow_options(element)
{
        if (element.value=='2') {
                (function ($) {
                        $('#selectimage1').show();
                        $('#selectimage2').show();
			$('#selectimage3').show();
                }(jQuery));
        } else {
                (function ($) {
                        $('#selectimage1').hide();
                        $('#selectimage2').hide();
			$('#selectimage3').hide();
                }(jQuery));
        }

}

function nssw_star_position_options(element)
{
        if (element.value=='2') {
                (function ($) {
                        $('#floatoption1').show();
                        $('#floatoption2').show();
                }(jQuery));
        } else {
                (function ($) {
                        $('#floatoption1').hide();
                        $('#floatoption2').hide();
                }(jQuery));
        }

}


