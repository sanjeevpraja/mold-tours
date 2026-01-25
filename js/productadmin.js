(function($){
	"use strict";
	$(document).ready(function(){
		wp.data.dispatch( 'core/editor' ).removeEditorPanel( 'taxonomy-panel-grade' );
	});
})(jQuery);