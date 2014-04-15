jQuery(document).ready(function($){

	if ( $('#ace-editor').length ) {
		var editor = ace.edit("ace-editor");
		editor.setTheme("ace/theme/github");
		editor.getSession().setMode("ace/mode/php");
		var textarea = $('textarea[name="content"]').hide();
		editor.getSession().setValue( textarea.val() );
		$('#ace-editor').css('min-height', '300px' );
		if ( $('textarea[name="content"]').is(':disabled') ) {
			editor.setReadOnly(true);
		}
		editor.getSession().on('change', function(){
			textarea.val( editor.getSession().getValue() );
		});
	}

});