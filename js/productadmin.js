(function($){
	"use strict";
	$(document).ready(function(){
		wp.data.dispatch("core/edit-post").removeEditorPanel("taxonomy-panel-grade");
	});
})(jQuery);