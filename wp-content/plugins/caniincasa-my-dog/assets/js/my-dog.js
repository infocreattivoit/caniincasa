/**
 * Caniincasa My Dog - Frontend JavaScript
 */

(function($) {
	'use strict';

	/**
	 * Delete dog profile
	 */
	$('.delete-dog-btn').on('click', function(e) {
		e.preventDefault();

		if (!confirm(caniincasaMyDog.strings.confirm_delete)) {
			return;
		}

		var dogId = $(this).data('dog-id');

		$.ajax({
			url: caniincasaMyDog.ajaxurl,
			type: 'POST',
			data: {
				action: 'delete_dog_profile',
				nonce: caniincasaMyDog.nonce,
				dog_id: dogId
			},
			success: function(response) {
				if (response.success) {
					window.location.href = response.data.redirect;
				} else {
					alert(response.data.message || caniincasaMyDog.strings.error);
				}
			},
			error: function() {
				alert(caniincasaMyDog.strings.error);
			}
		});
	});

	/**
	 * Add vaccination
	 */
	$('#add-vaccination-form').on('submit', function(e) {
		e.preventDefault();

		var $form = $(this);
		var $submit = $form.find('button[type="submit"]');

		$submit.prop('disabled', true).text(caniincasaMyDog.uploading);

		$.ajax({
			url: caniincasaMyDog.ajaxurl,
			type: 'POST',
			data: $form.serialize() + '&action=add_vaccination&nonce=' + caniincasaMyDog.nonce,
			success: function(response) {
				if (response.success) {
					alert(response.data.message);
					location.reload();
				} else {
					alert(response.data.message || caniincasaMyDog.strings.error);
				}
			},
			error: function() {
				alert(caniincasaMyDog.strings.error);
			},
			complete: function() {
				$submit.prop('disabled', false).text('Aggiungi');
			}
		});
	});

	/**
	 * Delete vaccination
	 */
	$(document).on('click', '.delete-vaccination', function(e) {
		e.preventDefault();

		if (!confirm('Sei sicuro di voler eliminare questa vaccinazione?')) {
			return;
		}

		var vaccId = $(this).data('vacc-id');
		var $row = $(this).closest('tr');

		$.ajax({
			url: caniincasaMyDog.ajaxurl,
			type: 'POST',
			data: {
				action: 'delete_vaccination',
				nonce: caniincasaMyDog.nonce,
				vacc_id: vaccId
			},
			success: function(response) {
				if (response.success) {
					$row.fadeOut(function() {
						$(this).remove();
					});
				} else {
					alert(response.data.message || caniincasaMyDog.strings.error);
				}
			},
			error: function() {
				alert(caniincasaMyDog.strings.error);
			}
		});
	});

	/**
	 * Add weight entry
	 */
	$('#add-weight-form').on('submit', function(e) {
		e.preventDefault();

		var $form = $(this);
		var $submit = $form.find('button[type="submit"]');

		$submit.prop('disabled', true).text(caniincasaMyDog.uploading);

		$.ajax({
			url: caniincasaMyDog.ajaxurl,
			type: 'POST',
			data: $form.serialize() + '&action=add_weight_entry&nonce=' + caniincasaMyDog.nonce,
			success: function(response) {
				if (response.success) {
					alert(response.data.message);
					location.reload();
				} else {
					alert(response.data.message || caniincasaMyDog.strings.error);
				}
			},
			error: function() {
				alert(caniincasaMyDog.strings.error);
			},
			complete: function() {
				$submit.prop('disabled', false).text('Aggiungi');
			}
		});
	});

	/**
	 * Add dog note
	 */
	$('#add-note-form').on('submit', function(e) {
		e.preventDefault();

		var $form = $(this);
		var $submit = $form.find('button[type="submit"]');

		$submit.prop('disabled', true).text(caniincasaMyDog.uploading);

		$.ajax({
			url: caniincasaMyDog.ajaxurl,
			type: 'POST',
			data: $form.serialize() + '&action=add_dog_note&nonce=' + caniincasaMyDog.nonce,
			success: function(response) {
				if (response.success) {
					alert(response.data.message);
					location.reload();
				} else {
					alert(response.data.message || caniincasaMyDog.strings.error);
				}
			},
			error: function() {
				alert(caniincasaMyDog.strings.error);
			},
			complete: function() {
				$submit.prop('disabled', false).text('Aggiungi Nota');
			}
		});
	});

})(jQuery);
