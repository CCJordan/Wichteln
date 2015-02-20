var infotimer, errortimer;

$(document).ready( function() {
	// Register Event Handlers

	// Delete knob
	$('.ui-icon-trash').click( deleteRow );

	// Save knob
	$('.ui-icon-disk').click( saveRow );

	// Zuordnen knob
	$('#zuordnen').click( zuordnen );

	// Text Box enter
	$('.textbox').focus( enterTextBox );

	// Text Box leave
	$('.textbox').blur( leaveTextBox );

	$('.disabled').unbind();
});

function saveRow() {
	var row = $( this ).parent();
	makeAjaxCall( { action: "save",
					id: row.attr('data-id'), 
					name: row.find('.name').val(),
					mail: row.find('.mail').val() },
					saveComplete );
	if ( $('#mails .row:last').find('input.name').val() != "Name" 
	  && $('#mails .row:last').find('input.mail').val() != "E-Mail" ) {
	  	var lastID = $('#mails .row:last').attr('data-id');
	  	$('#mails .row .disabled').removeClass( 'disabled' );
		$('#mails').append("<div class='row' data-id='" + lastID + "'><span class='ui-icon disabled ui-icon-trash'></span><span class='ui-icon ui-icon-disk'></span><input type='text' class='textbox ui-corner-all name' data-default-val='Name' value='Name'><input type='text' class='textbox ui-corner-all mail' data-default-val='E-Mail' value='E-Mail'></div>")
		$('.textbox').unbind();
		$('.ui-icon').unbind();

		// Text Box enter
		$('.textbox').focus( enterTextBox );

		// Text Box leave
		$('.textbox').blur( leaveTextBox );

		// Delete knob
		$('.ui-icon-trash').click( deleteRow );

		// Save knob
		$('.ui-icon-disk').click( saveRow );

		$('.disabled').unbind();
	}
}

function deleteRow() {
	var id = $( this ).parent().attr('data-id');
	makeAjaxCall( { action: "delete", id: id } );
	$( this ).parent().slideUp( 400, function () { $( this ).remove(); } );
}

function enterTextBox() {
	if ( $(this).val() == $(this).attr('data-default-val')) {
		$(this).val("");
	}
}

function leaveTextBox() {
	if ( $(this).val() == "" ) {
		$(this).val( $(this).attr('data-default-val') );
	}
}

function makeAjaxCall( data, callback ) {
	$.ajax({
		url: "ajax-responder.php",
		data: data,
		async: true,
		contentType: "application/x-www-form-urlencoded",
		type: "POST",
		success: callback,
		error: function() {
			$('#content').prepend("<div class='error ui-state-error'>Es ist ein Fehler beim Ajax-Request aufgetreten.</div>");
			setTimeout(function () { $('.error').slideUp(); }, 5000);
		}
	});
}

function saveComplete( data, status ) {
	clearTimeout( infotimer );
	$('.info').slideUp();
	$('#content').prepend("<div class='info ui-state-highlight'>Die Änderungen wurden gespeichert.</div>");
	infotimer = setTimeout(function () { $('.info').slideUp(); }, 5000);
}

function zuordnen() {
	makeAjaxCall( { action: "zuordnen" }, ZuordnenComplete );
}

function ZuordnenComplete(data, status) {
	alert(data);
}