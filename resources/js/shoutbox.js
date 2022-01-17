/**
 * Runs on page load.
 */
jQuery(document).ready(function() {
	
	// Don't cache AJAX calls.
	jQuery.ajaxSetup({ cache: false });
	
	// Only on the shoutbox page
	if (jQuery('#shoutbox_list').length === 1)
	{
		// Load the existing answers.
		shoutbox_load_answers();
		
		// Add form submit handler for the answer form
		jQuery('#shoutbox_form').on('submit', function() {
			let nickname = jQuery('#shoutbox_form input[name=nickname]').val();
			let answer   = jQuery('#shoutbox_form textarea[name=answer]').val();
			let token    = jQuery('meta[name="csrf-token"]').attr('content');
			
			jQuery.ajax({
				url: '/api/answers',
				type: "POST",
				data: {
					nickname: nickname,
					answer: answer,
					token: token
				},
				success: function(data) {
					// Output the success message above the form.
					let infoMessageContainer = jQuery('#shoutbox_form .info-message p');
					if (infoMessageContainer.length === 0)
					{
						jQuery('<div class="info-message bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-8"><p></p></div>').insertAfter('#shoutbox_form h2');
						infoMessageContainer = jQuery('#shoutbox_form .info-message p');
					}
					infoMessageContainer.text(data);
					
					// Hide any error messages.
					let errorMessageContainer = jQuery('#shoutbox_form .error-message');
					if (errorMessageContainer.length !== 0)
					{
						errorMessageContainer.remove();
					}
					
					// Trigger reload of answers.
					shoutbox_load_answers();
					
					// Reset the form.
					jQuery('#shoutbox_form')[0].reset();
				},
				error: function(response) {
					// Output the error message above the form.
					let errorMessageContainer = jQuery('#shoutbox_form .error-message p');
					if (errorMessageContainer.length === 0)
					{
						jQuery('<div class="error-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8"><p></p></div>').insertAfter('#shoutbox_form h2');
						errorMessageContainer = jQuery('#shoutbox_form .error-message p');
					}
					errorMessageContainer.text(response.responseJSON.message);
				}
			});
			
			return false;
		});
	}
});

/**
 * Load and display the shoutbox answers.
 * 
 * @return void
 */
function shoutbox_load_answers()
{
	jQuery.ajax({
		url: '/api/answers',
		type: "GET",
		success: function(data) {
			// Remove the currently displayed answers.
			let shoutboxAppContainer = jQuery('#shoutbox_list');
			shoutboxAppContainer.find('li.answer').remove();
			
			// Output the newly loaded answers.
			if (data.data.length === 0)
			{
				shoutboxAppContainer.append('<li class="answer">Es wurden noch keine Antworten gespeichert. Sei der erste!</li>');
			}
			else
			{
				let counter = data.data.length;
				let liTemplate = shoutboxAppContainer.find('li.template').first();
				jQuery.each(data.data, function(k, v) {
					// copy the li element template and fill the answer details in
					let newLiElement = liTemplate.clone();
					newLiElement.removeClass('hidden').removeClass('template').addClass('answer');
					newLiElement.find('strong').text('#' + counter + '. ' + v.nickname + ' sagt:');
					newLiElement.find('.answerText').text(v.answer);
					newLiElement.data('answer-id', v.id);
					
					// handle moderation buttons (if present, only for logged-in users)
					if (v.status === 'published')
					{
						newLiElement.find('.release-button').hide();
					}
					else
					{
						newLiElement.find('.block-button').hide();
					}
					
					// add the li element to the DOM
					shoutboxAppContainer.append(newLiElement);
					counter--;
				});
				
				// Add handlers to the moderation buttons.
				shoutbox_add_moderation_handlers();
			}
		},
		error: function(response) {
			// Output the error message above the form.
			let errorMessageContainer = jQuery('#shoutbox_form .error-message p');
			if (errorMessageContainer.length === 0)
			{
				jQuery('<div class="error-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8"><p></p></div>').insertAfter('#shoutbox_form h2');
				errorMessageContainer = jQuery('#shoutbox_form .error-message p');
			}
			errorMessageContainer.text(response.responseJSON.error_message ?? 'Es ist ein Fehler aufgetreten!');
		}
	});
	
	return false;
}

/**
 * Adds handlers to the moderation buttons, if present.
 * 
 * @return void
 */
function shoutbox_add_moderation_handlers()
{
	jQuery('.answer .block-button').on('click', function() {
		let liElement = jQuery(this).parents('li');
		let answerId = liElement.data('answer-id');
		let token    = jQuery('meta[name="csrf-token"]').attr('content');
		
		jQuery.ajax({
			url: '/api/answers/' + answerId + '/block',
			type: "POST",
			data: {
				token: token
			},
			success: function(data) {
				alert(data);
				
				// switch the buttons
				liElement.find('.release-button').show();
				liElement.find('.block-button').hide();
			},
			error: function(response) {
				// Output the error message above the form.
				alert( response.responseJSON.message );
			}
		});
		
		return false;
	});
	
	jQuery('.answer .release-button').on('click', function() {
		let liElement = jQuery(this).parents('li');
		let answerId = liElement.data('answer-id');
		let token    = jQuery('meta[name="csrf-token"]').attr('content');
		
		jQuery.ajax({
			url: '/api/answers/' + answerId + '/publish',
			type: "POST",
			data: {
				token: token
			},
			success: function(data) {
				alert(data);
				
				
				// switch the buttons
				liElement.find('.block-button').show();
				liElement.find('.release-button').hide();
			},
			error: function(response) {
				// Output the error message above the form.
				alert( response.responseJSON.message );
			}
		});
		
		return false;
	});
}
