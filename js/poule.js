function penalties(match_id, row) {

	var phasename = getParameter('phase');
	if(phasename == null){
		phasename = "group";
	}
	if (phasename != "group") {
		var field1 = document.getElementById('score_' + match_id + '_1').value;
		var field2 = document.getElementById('score_' + match_id + '_2').value;
		
		if (field1 == field2 && field1 != "") {
			jQuery('#penalties_'+match_id).removeAttr("hidden");
		}else{
			jQuery ('#penalties_'+match_id).attr( "hidden", "hidden" );
		}
	}
}

/**
 * Function that return the value of a get in the url
 * 
 * @author http://stackoverflow.com/questions/1403888/get-url-parameter-with-javascript-or-jquery
 * @version 1
 * @param {type} paramName
 * @returns {unresolved}
 */
function getParameter(paramName) {
	var searchString = window.location.search.substring(1),
			i, val, params = searchString.split("&");

	for (i = 0; i < params.length; i++) {
		val = params[i].split("=");
		if (val[0] == paramName) {
			return unescape(val[1]);
		}
	}
	return null;
}

jQuery('input').datepicker({
    beforeShow:function(input) {
        jQuery(input).css({
            "position": "relative",
            "z-index": 999999
        });
    }
});