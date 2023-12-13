jQuery(document).ready(function($) {

	/**
	 * Ticket attaches.
	 */
	$('#mlm-attach-ticket-image').click(function(e){
		e.preventDefault();
		var meta_attaches_frame;
		if( meta_attaches_frame ) {
			meta_attaches_frame.open();
			return;
		}
		meta_attaches_frame = wp.media.frames.meta_attaches_frame = wp.media({
			title: mlm_local_object.upload_image,
			multiple: false,
			button: { text: mlm_local_object.choose },
			library: { type: 'image' }
		});
		meta_attaches_frame.on('select', function(){
			var media_attachment = meta_attaches_frame.state().get('selection').first().toJSON();
			var html_img = '<a href="#mlm-delete-image"><img src="' + media_attachment.url + '" alt="image" /><span class="remove">X</span><input type="hidden" name="mlm_attaches[]" value="' + media_attachment.url + '" /></a>';
			$( "#mlm-attach-ticket-wrap .thumb-box" ).append( html_img );
		});
		meta_attaches_frame.open();
	});

	$(document).on('click', 'a[href="#mlm-delete-image"]', function (e) {
		e.preventDefault();
		$(this).remove();
	});

	// SELECT2
	$('.mlm-select').select2();

	// UPLOAD IMAGE
	$(document).on("click", '.upload_image_button', function (e) {
		e.preventDefault();
		var $button = $(this), file_frame;

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: mlm_local_object.upload_image,
			library: {
				type: 'image'
			},
			button: {
				text: mlm_local_object.choose
			},
			multiple: false
		});

		// When an image is selected, run a callback.
		file_frame.on('select', function () {
			var attachment = file_frame.state().get('selection').first().toJSON();
			$button.parent().find('.image_id').val( attachment.id );
			$button.parent().find('.image').val( attachment.url );
			$button.parent().find('.mlm-image > img').attr( 'src', attachment.url );
		});

		// Finally, open the modal
		file_frame.open();
	});

	// UPLOAD FILE
	$(document).on('click', '.mlm-upload-file-btn', function (e) {
		e.preventDefault();
		var $button = $(this), file_frame;
		file_frame = wp.media.frames.file_frame = wp.media({
			title: mlm_local_object.upload_file,
			button: {
				text: mlm_local_object.choose
			},
			multiple: false
		});
		file_frame.on('select', function () {
			var attachment = file_frame.state().get('selection').first().toJSON();
			$button.parent().find('.file').val( attachment.url );
			$button.closest('.mlm-file-template').find('.name').val( mlm_local_object.dl_file );
			$button.closest('.mlm-file-template').find('.file').val( attachment.url );
		});
		file_frame.open();
	});

	// GET EDITOR CONTENT
	function tmce_getContent( editor_id, textarea_id ) {
		if( typeof editor_id == 'undefined' ) {
			editor_id = wpActiveEditor;
		}

		if( typeof textarea_id == 'undefined' ) {
			textarea_id = editor_id;
		}

		if( $('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
			return tinyMCE.get(editor_id).getContent();
		} else {
			return $('#'+textarea_id).val();
		}
	}

	// SET EDITOR CONTENT
	function tmce_setContent( content, editor_id, textarea_id ) {
		if( typeof editor_id == 'undefined' ) {
			editor_id = wpActiveEditor;
		}

		if( typeof textarea_id == 'undefined' ) {
			textarea_id = editor_id;
		}

		if( $('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
			return tinyMCE.get(editor_id).setContent(content);
		} else {
			return $('#'+textarea_id).val(content);
		}
	}

	// TOASTR OPTIONS
	toastr.options = {
		'preventDuplicates'	: true,
		'positionClass'		: 'toast-bottom-center',
		'rtl'				: mlm_local_object.rtl
	};

	// WALLET CHARGE
	$('#mlm_charge_wallet_form').on('submit', function(e) {
		e.preventDefault();
		toastr.clear();
		$form = $(this);
		var data = {
			'user'		: $form.find('[name="mlm_user"]').val(),
			'type'		: $form.find('[name="mlm_type"]').val(),
			'amount'	: $form.find('[name="mlm_amount"]').val(),
			'text'		: $form.find('[name="mlm_desc"]').val()
		};
		if( $form.hasClass('loading') ) {
			toastr.info( mlm_local_object.wait );
		} else if( data['user'].length == 0 || data['type'].length == 0 || data['amount'].length == 0 || data['text'].length == 0 ) {
			toastr.error( mlm_local_object.all_fields );
		} else {
			toastr.info( mlm_local_object.wait );
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'		: 'mlm_charge_wallet',
					'charge_data'	: data,
					'security'		: $form.find('[name="mlm_verify"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data) {
					toastr.clear();
					if( data.submited == true ) {
						toastr.success( data.response );
						$form.find('[name="mlm_amount"]').val('');
						$form.find('[name="mlm_desc"]').val('');
					} else {
						toastr.error( data.response );
					}
				},
				error: function(){
					toastr.clear();
					toastr.error( mlm_local_object.no_response );
				}
			});
		}
	});

	// NEW TICKET
	$('#mlm_new_ticket_form').on('submit', function(e) {
		e.preventDefault();
		toastr.clear();
		$form = $(this);
		var ticket = {
			'recipient'	: $form.find('[name="mlm_user"]').val(),
			'title'		: $form.find('[name="mlm_title"]').val(),
			'content'	: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'attaches'	: $form.find('[name="mlm_attaches[]"]').map(function(){return $(this).val();}).get()
		};
		if( $form.hasClass('loading') ) {
			toastr.info( mlm_local_object.wait );
		} else if( ticket['title'].length == 0 || ticket['content'].length == 0 ) {
			toastr.error( mlm_local_object.ticket_req );
		} else {
			toastr.info( mlm_local_object.wait );
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'		: 'mlm_record_ticket',
					'ticket_data'	: ticket,
					'security'		: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data) {
					toastr.clear();
					if( data.submited == true ) {
						toastr.success( data.response );
						$form.find('[name="mlm_user"]').val('');
						$form.find('[name="mlm_title"]').val('');
						tmce_setContent( '', 'mlm_content', 'mlm_content' );
						setTimeout(function() {
							window.location = data.redirect;
						}, 1000 );
					} else {
						toastr.error( data.response );
					}
				},
				error: function(){
					toastr.clear();
					toastr.error( mlm_local_object.no_response );
				}
			});
		}
	});

	// REPLY TICKET
	$('#mlm_reply_ticket_form').on('submit', function(e) {
		e.preventDefault();
		toastr.clear();
		$form = $(this);
		var ticket = {
			'content'	: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'status'	: $form.find('[name="mlm_status"]').val(),
			'parent'	: $form.find('[name="mlm_parent"]').val(),
			'attaches'	: $form.find('[name="mlm_attaches[]"]').map(function(){return $(this).val();}).get()
		};
		if( $form.hasClass('loading') ) {
			toastr.info( mlm_local_object.wait );
		} else if( ticket['content'].length == 0 ) {
			toastr.error( mlm_local_object.ticket_reply );
		} else if( ticket['parent'].length == 0 ) {
			toastr.error( mlm_local_object.invalid_ticket );
		} else {
			toastr.info( mlm_local_object.wait );
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'		: 'mlm_reply_ticket',
					'ticket_data'	: ticket,
					'security'		: $form.find('[name="mlm_verify"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					toastr.clear();
					if( data.submited == true ) {
						toastr.success( data.response );
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						toastr.error( data.response );
					}
				},
				error: function(){
					toastr.clear();
					toastr.error( mlm_local_object.no_response );
				}
			});
		}
	});

	// DELETE TICKET
	$('a[href="#mlm-delete-ticket"]').on('click', function(e) {
		e.preventDefault();
		toastr.clear();
		$btn = $(this);
		if( $btn.hasClass('loading') ) {
			toastr.info( mlm_local_object.wait );
		} else {
			var ticket_id = $(this).attr('data-id'), verify = $(this).attr('data-verify');
			var r = confirm( mlm_local_object.delete_ticket );
			if ( r == true ) {
				if( ticket_id.length == 0 ) {
					toastr.error( mlm_local_object.invalid_ticket );
				} else {
					$.ajax({
						type	: 'POST',
						dataType: 'json',
						url		: mlm_local_object.ajax_url,
						data	: {
							'action'	: 'mlm_delete_ticket',
							'ticket_id'	: ticket_id,
							'security'	: verify,
						},
						beforeSend: function (response) {
							$btn.addClass('loading');
						},
						complete: function (response) {
							$btn.removeClass('loading');
						},
						success: function(data){
							toastr.clear();
							if( data.deleted == true ) {
								toastr.success( data.response );
								$( '#ticket_item_' + ticket_id ).addClass('deleting');
								setTimeout(function() {
									$( '#ticket_item_' + ticket_id ).remove();
								}, 1000);
							} else {
								toastr.error( data.response );
							}
						},
						error: function(){
							toastr.clear();
							toastr.error( mlm_local_object.no_response );
						}
					});
				}
			}
		}
	});

	// CHANGE STATUS
	$('#mlm_change_ticket_status').on('click', function(e) {
		e.preventDefault();
		toastr.clear();
		$btn = $(this), $form = $btn.closest('form');

		if( $btn.hasClass('loading') ) {
			toastr.info( mlm_local_object.wait );
			return;
		}

		var ticket = {
			'status'	: $form.find('[name="mlm_status"]').val(),
			'parent'	: $form.find('[name="mlm_parent"]').val()
		};
		if( ticket['parent'].length == 0 ) {
			toastr.error( mlm_local_object.invalid_ticket );
		} else {
			toastr.info( mlm_local_object.wait );
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'		: 'mlm_ticket_status',
					'ticket_data'	: ticket,
					'security'		: $form.find('[name="mlm_verify"]').val()
				},
				beforeSend: function (response) {
					$btn.addClass('loading');
				},
				complete: function (response) {
					$btn.removeClass('loading');
				},
				success: function(data){
					toastr.clear();
					if( data.submited == true ) {
						toastr.success( data.response );
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						toastr.error( data.response );
					}
				},
				error: function(){
					toastr.clear();
					toastr.error( mlm_local_object.no_response );
				}
			});
		}
	});

	// NEW SUBSCRIBE
	$('#mlm_new_subscribe_form').on('submit', function(e) {
		e.preventDefault();
		toastr.clear();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			toastr.info( mlm_local_object.wait );
			return;
		}

		var data = {
			'user'	: $form.find('[name="mlm_user"]').val(),
			'plan'	: $form.find('[name="mlm_plan"]').val()
		}

		if( data['user'].length == 0 || data['plan'].length == 0 ) {
			toastr.error( mlm_local_object.plan_req );
		} else {
			toastr.info( mlm_local_object.wait );
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_new_subscribe',
					'plan_data'	: data,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data) {
					toastr.clear();
					if( data.submited == true ) {
						toastr.success( data.response );
						$form.find('[name="mlm_user"]').val('');
						$form.find('[name="mlm_plan"]').val('');

						setTimeout(function() {
							window.location = data.redirect;
						}, 1000);

					} else {
						toastr.error( data.response );
					}
				},
				error: function(){
					toastr.clear();
					toastr.error( mlm_local_object.no_response );
				}
			});
		}
	});

	// ACTIVATE LICENSE
	$(document).on('submit', 'form[name="mlm-license-form"]', function (e) {
		e.preventDefault();
		toastr.clear();
		$form = $(this);
		if( $form.hasClass('loading') ) {
			toastr.warning(mlm_local_object.wait);
		} else if( $form.find('[name="mlm_license"]').val().length == 0 ) {
			toastr.error(mlm_local_object.license_req);
		} else {
			toastr.warning(mlm_local_object.license_check);
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_activate',
					'token'		: $form.find('[name="mlm_license"]').val(),
					'security'	: $form.find('[name="mlm_verify"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					toastr.clear();
					if( data.submited == true ) {
						toastr.success( data.response );
						setTimeout(function() {
							location.reload();
						}, 1000);
					} else {
						toastr.error( data.response );
					}
				},
				error: function(){
					toastr.clear();
					toastr.error(mlm_local_object.no_response);
				}
			});
		}
	});

	// EXPIRE PICKER
	if( typeof $.datepicker === 'object' ) {
		$('.mlm-datepicker').datepicker({
			dateFormat	: 'yy-mm-dd',
			changeMonth	: false,
			changeYear	: false
		});
	}

	// NEW CUSTOM FIELD
	$(document).on('click', '.mlm-custom-fields .mlm-submit-btn', function (e) {
		e.preventDefault();
		var $tmpl = $('.mlm-fields-template').last();
		var $copy = $tmpl.clone();
		$copy.find('input.id').val('');
		$copy.find('input.text').val('');
		$copy.find('input.place').val('');
		$copy.find('input.req').val('no');
		$copy.find('.top .title').text(mlm_local_object.text_field);
		//increment input array index ( e.g [1] to [2] ) to have clean arrays in php to work with
        $copy.find('[name]').each(function(){
            this.name = this.name.replace(/\[(\d+)\]/,function(str,p1){return '[' + (parseInt(p1,10)+1) + ']'});
        });
		$tmpl.after($copy);
	});

	// REMOVE CUSTOM FIELD
	$(document).on('click', '.mlm-custom-fields .mlm-delete-btn', function (e) {
		e.preventDefault();
		if( $(this).closest('form').find('.mlm-fields-template').length > 1 ) {
			$(this).closest('.mlm-fields-template').fadeOut(200).remove();
		} else {
			$(this).closest('.mlm-fields-template').find('.top .title').text(mlm_local_object.text_field);
			$(this).closest('.mlm-fields-template').find('.id').val('');
			$(this).closest('.mlm-fields-template').find('.text').val('');
			$(this).closest('.mlm-fields-template').find('.place').val('');
		}
	});

	// CUSTOM FIELD TOGGLE
	$(document).on('click', '.mlm-custom-fields a[href="#mlm-toggle-field"]', function (e) {
		e.preventDefault();
		$btn = $(this), $parent = $btn.closest('.mlm-fields-template');
		if( $parent.hasClass('open') ) {
			$parent.find('.bottom').slideUp(200);
			$parent.removeClass('open');
		} else {
			$parent.find('.bottom').slideDown(150);
			$parent.addClass('open');
		}
	});

	// CHANGE TITLE ON INPUT CHANGE
	$(document).on('change paste input', '.mlm-custom-fields .text', function (e) {
		$(this).closest('.mlm-fields-template').find('.top .title').text( $(this).val() );
	});

	// SAVE CUSTOM FIELDS
	$(document).on('submit', 'form[name="mlm-custom-fields-form"]', function (e) {
		e.preventDefault();
		toastr.clear();
		var $form = $(this), form_data = {};

		$form.find('.mlm-fields-wrapper :input').each(function(){
			var el = $(this);
			form_data[el.attr('name')] = $.trim( el.val() );
		});

		toastr.warning(mlm_local_object.wait);

		$.ajax({
			type	: 'POST',
			dataType: 'json',
			url		: mlm_local_object.ajax_url,
			data	: {
				'action'	: 'mlm_save_fields',
				'form_data'	: form_data,
				'security'	: $form.find('[name="mlm_security"]').val()
			},
			success: function(data){
				toastr.clear();
				if( data.submited == true ) {
					toastr.success( data.response );
				} else {
					toastr.error( data.response );
				}
			},
			error: function(){
				toastr.clear();
				toastr.error(mlm_local_object.no_response);
			}
		});
	});

	// DELETE TRANSACTION
	$(document).on('click', 'a[href="#mlm-delete-transaction"]', function (e) {
		e.preventDefault();
		toastr.clear();
		$btn = $(this);
		if( $btn.hasClass('loading') ) {
			toastr.info( mlm_local_object.wait );
		} else {
			var trans_id = $(this).attr('data-id'), verify = $(this).attr('data-verify');
			var r = confirm( mlm_local_object.delete_trans );
			if ( r == true ) {
				if( trans_id.length == 0 ) {
					toastr.error( mlm_local_object.invalid_trans );
				} else {
					$.ajax({
						type	: 'POST',
						dataType: 'json',
						url		: mlm_local_object.ajax_url,
						data	: {
							'action'	: 'mlm_delete_trans',
							'trans_id'	: trans_id,
							'security'	: verify,
						},
						beforeSend: function (response) {
							$btn.addClass('loading');
						},
						complete: function (response) {
							$btn.removeClass('loading');
						},
						success: function(data){
							toastr.clear();
							if( data.deleted == true ) {
								toastr.success( data.response );
								$( '#trans_item_' + trans_id ).addClass('deleting');
								setTimeout(function() {
									$( '#trans_item_' + trans_id ).remove();
								}, 1000);
							} else {
								toastr.error( data.response );
							}
						},
						error: function(){
							toastr.clear();
							toastr.error( mlm_local_object.no_response );
						}
					});
				}
			}
		}
	});

	// SAVE SMS TEXTS
	$(document).on('submit', 'form[name="mlm-sms-texts-form"]', function (e) {
		e.preventDefault();
		toastr.clear();
		var $form = $(this), form_data = {};

		$form.find('.form-table :input').each(function(){
			var el = $(this);
			form_data[el.attr('name')] = $.trim( el.val() );
		});

		toastr.warning(mlm_local_object.wait);

		$.ajax({
			type	: 'POST',
			dataType: 'json',
			url		: mlm_local_object.ajax_url,
			data	: {
				'action'	: 'mlm_save_sms_texts',
				'form_data'	: form_data,
				'security'	: $form.find('[name="mlm_security"]').val()
			},
			success: function(data){
				toastr.clear();
				if( data.submited == true ) {
					toastr.success( data.response );
				} else {
					toastr.error( data.response );
				}
			},
			error: function(){
				toastr.clear();
				toastr.error(mlm_local_object.no_response);
			}
		});
	});

	// SAVE SMS PATTERNS
	$(document).on('submit', 'form[name="mlm-sms-patterns-form"]', function (e) {
		e.preventDefault();
		toastr.clear();
		var $form = $(this), form_data = {};

		$form.find('.form-table :input').each(function(){
			var el = $(this);
			form_data[el.attr('name')] = $.trim( el.val() );
		});

		toastr.warning(mlm_local_object.wait);

		$.ajax({
			type	: 'POST',
			dataType: 'json',
			url		: mlm_local_object.ajax_url,
			data	: {
				'action'	: 'mlm_save_patterns',
				'form_data'	: form_data,
				'security'	: $form.find('[name="mlm_security"]').val()
			},
			success: function(data){
				toastr.clear();
				if( data.submited == true ) {
					toastr.success( data.response );
				} else {
					toastr.error( data.response );
				}
			},
			error: function(){
				toastr.clear();
				toastr.error(mlm_local_object.no_response);
			}
		});
	});

	// SAVE MAIL TEXTS
	$(document).on('submit', 'form[name="mlm-mail-texts-form"]', function (e) {
		e.preventDefault();
		toastr.clear();
		var $form = $(this), form_data = {
			'register' : tmce_getContent( 'mlm_mail_register', 'mlm_mail_register' ),
			'lost_code' : tmce_getContent( 'mlm_mail_lost_code', 'mlm_mail_lost_code' ),
			'verify_code' : tmce_getContent( 'mlm_mail_verify_code', 'mlm_mail_verify_code' ),
			'product_moderation' : tmce_getContent( 'mlm_mail_product_moderation', 'mlm_mail_product_moderation' ),
			'post_moderation' : tmce_getContent( 'mlm_mail_post_moderation', 'mlm_mail_post_moderation' ),
			'withdrawal_paid' : tmce_getContent( 'mlm_mail_withdrawal_paid', 'mlm_mail_withdrawal_paid' ),
			'withdrawal_request' : tmce_getContent( 'mlm_mail_withdrawal_request', 'mlm_mail_withdrawal_request' ),
			'upgrade_request' : tmce_getContent( 'mlm_mail_upgrade_request', 'mlm_mail_upgrade_request' ),
			'upgraded' : tmce_getContent( 'mlm_mail_upgraded', 'mlm_mail_upgraded' ),
			'comment_replied' : tmce_getContent( 'mlm_mail_comment_replied', 'mlm_mail_comment_replied' ),
			'new_comment' : tmce_getContent( 'mlm_mail_new_comment', 'mlm_mail_new_comment' ),
			'new_ticket' : tmce_getContent( 'mlm_mail_new_ticket', 'mlm_mail_new_ticket' ),
			'follower_new_product' : tmce_getContent( 'mlm_mail_follower_new_product', 'mlm_mail_follower_new_product' ),
		};

		toastr.warning(mlm_local_object.wait);

		$.ajax({
			type	: 'POST',
			dataType: 'json',
			url		: mlm_local_object.ajax_url,
			data	: {
				'action'	: 'mlm_save_mail_texts',
				'form_data'	: form_data,
				'security'	: $form.find('[name="mlm_security"]').val()
			},
			success: function(data){
				toastr.clear();
				if( data.submited == true ) {
					toastr.success( data.response );
				} else {
					toastr.error( data.response );
				}
			},
			error: function(){
				toastr.clear();
				toastr.error(mlm_local_object.no_response);
			}
		});
	});

	// COURSE DETAILS
	$(document).on('change', '#mlm_course_metabox #mlm_is_course', function (e) {
		if( $(this).val() == 'no' ) {
			$(this).closest('.form-table').find('.ac').removeClass('ac').addClass('kapa');
		} else {
			$(this).closest('.form-table').find('.kapa').removeClass('kapa').addClass('ac');
		}
	});

	// OPEN MODAL
	$(document).on('click', 'a[href="#mlm-modal"]', function (e) {
		e.preventDefault();
		if( $(this).data('target').length ) {
			$( $(this).data('target') ).iziModal('open');
		}
	});

	// OPEN LESSON MODAL
	$(document).on('click', 'a[href="#mlm-lesson-modal"]', function (e) {
		e.preventDefault();
		$('.mlm-course-lesson-box-wrap').removeClass('show');
		var $form = $('form[name="mlm_new_lesson_form"]');
		$form.find('[name="mlm_number"]').val('');
		$form.find('[name="mlm_title"]').val('');
		$form.find('[name="mlm_desc"]').val('');
		$form.find('[name="mlm_status"]').val('');
		$form.find('[name="mlm_chapter"]').val($(this).data('chapter'));
		$form.find('[name="mlm_id"]').val('');

		$form.find('.mlm-file-template:not(:first-child)').remove();
		$form.find('.mlm-file-template:first-child').find('.file').val('');
		$form.find('.mlm-file-template:first-child').find('.name').val('');

		tmce_setContent( '', 'mlm_content', 'mlm_content' );

		$('.mlm-course-lesson-box-wrap').addClass('show');
	});

	// CLEAR VALUES ON MODAL CLOSE
	$(document).on('closed', '#mlm-new-chapter-modal', function (e) {
		var $form = $('form[name="mlm_new_chapter_form"]');
		$form.find('.mlm-image img').attr( 'src', $form.find('.mlm-image').data('default') );
		$form.find('[name="mlm_image"]').val('');
		$form.find('[name="mlm_number"]').val('');
		$form.find('[name="mlm_title"]').val('');
		$form.find('[name="mlm_desc"]').val('');
		$form.find('[name="mlm_id"]').val('');
	});

	// CLEAR VALUES ON MODAL CLOSE
	$(document).on('closed', '#mlm-new-lesson-modal', function (e) {
		var $form = $('form[name="mlm_new_lesson_form"]');
		$form.find('[name="mlm_number"]').val('');
		$form.find('[name="mlm_title"]').val('');
		$form.find('[name="mlm_desc"]').val('');
		$form.find('[name="mlm_status"]').val('');
		$form.find('[name="mlm_chapter"]').val('');
		$form.find('[name="mlm_id"]').val('');

		$form.find('.mlm-file-template:not(:first-child)').remove();
		$form.find('.mlm-file-template:first-child').find('.file').val('');
		$form.find('.mlm-file-template:first-child').find('.name').val('');

		tmce_setContent( '', 'mlm_content', 'mlm_content' );
	});

	// SET VALUES ON MODAL OPEN
	$(document).on('click', 'a[href="#mlm-edit-chapter"]', function (e) {
		e.preventDefault();
		var $parent = $(this).closest('.chapter-item'),
		$form = $('form[name="mlm_new_chapter_form"]');
		$form.find('.mlm-image img').attr( 'src', $parent.find('.chapter-image').attr('src') );
		$form.find('[name="mlm_image"]').val( $parent.data('image') );
		$form.find('[name="mlm_number"]').val( $parent.data('priority') );
		$form.find('[name="mlm_title"]').val( $parent.find('.chapter-title').text() );
		$form.find('[name="mlm_desc"]').val( $parent.find('.chapter-text').text() );
		$form.find('[name="mlm_id"]').val( $parent.data('id') );
		$('#mlm-new-chapter-modal').iziModal('open');
	});

	// SET VALUES ON MODAL OPEN
	$(document).on('click', 'a[href="#mlm-edit-lesson"]', function (e) {
		e.preventDefault();
		var $parent = $(this).closest('.lesson-item'),
		$content = $parent.find('.lesson-content').text(),
		$form = $('form[name="mlm_new_lesson_form"]');
		$form.find('[name="mlm_number"]').val( $parent.data('priority') );
		$form.find('[name="mlm_title"]').val( $parent.find('.lesson-title').text() );
		$form.find('[name="mlm_desc"]').val( $parent.find('.lesson-text').text() );
		$form.find('[name="mlm_status"]').val( $parent.data('status') );
		$form.find('[name="mlm_chapter"]').val( $parent.data('chapter') );
		$form.find('[name="mlm_id"]').val( $parent.data('id') );

		tmce_setContent( $content, 'mlm_content', 'mlm_content' );

		// UPDATE LINKS
		let $links = $.parseJSON( $parent.find('.lesson-links').val() );

		if( $links.length )
		{
			$.each( $links,function(key,value) {
				let $tmpl = $('.mlm-file-template').last();
				$tmpl.find('input.file').val( value.file );
				$tmpl.find('input.name').val( value.name );
				let $copy = $tmpl.clone();
				$copy.find('input.file').val('');
				$copy.find('input.name').val('');
				//increment input array index ( e.g [1] to [2] ) to have clean arrays in php to work with
		        $copy.find('[name]').each(function(){
		            this.name = this.name.replace(/\[(\d+)\]/,function(str,p1){return '[' + (parseInt(p1,10)+1) + ']'});
		        });
				$tmpl.after($copy);
			});

			$('.mlm-file-template').last().remove();
		}

		$('.mlm-course-lesson-box-wrap').addClass('show');
	});

	// SAVE CHAPTER
	$(document).on('submit', 'form[name="mlm_new_chapter_form"]', function (e) {
		e.preventDefault();
		toastr.clear();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return;
		}

		var data = {
			'image'		: $form.find('[name="mlm_image"]').val(),
			'number'	: $form.find('[name="mlm_number"]').val(),
			'title'		: $form.find('[name="mlm_title"]').val(),
			'desc'		: $form.find('[name="mlm_desc"]').val(),
			'chapter'	: $form.find('[name="mlm_id"]').val(),
			'post_id'	: $form.find('[name="mlm_post"]').val()
		}

		if( data['number'].length == 0 || data['title'].length == 0 || data['desc'].length == 0 ) {
			toastr.error( mlm_local_object.marked_fields );
		} else {
			toastr.info( mlm_local_object.wait );
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_save_chapter',
					'form_data'	: data,
					'security'	: $form.find('[name="mlm_nonce"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data) {
					toastr.clear();
					if( data.submited == true ) {
						toastr.success( data.response );
						setTimeout(function() {
							location.reload();
						}, 500 );
					} else {
						toastr.error( data.response );
					}
				},
				error: function(){
					toastr.clear();
					toastr.error( mlm_local_object.no_response );
				}
			});
		}
	});

	// DELETE CHAPTER
	$('a[href="#mlm-delete-chapter"]').on('click', function(e) {
		e.preventDefault();
		toastr.clear();
		var $btn = $(this), $parent = $btn.closest('.chapter-item');

		if( $btn.hasClass('loading') ) {
			toastr.info( mlm_local_object.wait );
		} else {
			var r = confirm( mlm_local_object.delete_article );
			if ( r == true ) {
				if( $parent.data('id').length == 0 ) {
					toastr.error( mlm_local_object.invalid_article );
				} else {
					toastr.info( mlm_local_object.wait );
					$.ajax({
						type	: 'POST',
						dataType: 'json',
						url		: mlm_local_object.ajax_url,
						data	: {
							'action'	: 'mlm_delete_chapter',
							'chapter_id': $parent.data('id'),
							'security'	: $btn.data('verify'),
						},
						beforeSend: function (response) {
							$btn.addClass('loading');
						},
						complete: function (response) {
							$btn.removeClass('loading');
						},
						success: function(data){
							toastr.clear();
							if( data.deleted == true ) {
								toastr.success( data.response );
								setTimeout(function() {
									location.reload();
								}, 500 );
							} else {
								toastr.error( data.response );
							}
						},
						error: function(){
							toastr.clear();
							toastr.error( mlm_local_object.no_response );
						}
					});
				}
			}
		}
	});

	// SAVE LESSON
	$(document).on('submit', 'form[name="mlm_new_lesson_form"]', function (e) {
		e.preventDefault();
		toastr.clear();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return;
		}

		var data = {
			'content'	: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'number'	: $form.find('[name="mlm_number"]').val(),
			'title'		: $form.find('[name="mlm_title"]').val(),
			'desc'		: $form.find('[name="mlm_desc"]').val(),
			'status'	: $form.find('[name="mlm_status"]').val(),
			'lesson'	: $form.find('[name="mlm_id"]').val(),
			'chapter'	: $form.find('[name="mlm_chapter"]').val(),
			'post_id'	: $form.find('[name="mlm_post"]').val()
		}

		$form.find('.mlm-upload-group :input').each(function(){
			var el = $(this);
			data[el.attr('name')] = $.trim( el.val() );
		});

		if( data['content'].length == 0 || data['number'].length == 0 || data['title'].length == 0 || data['desc'].length == 0 || data['status'].length == 0 ) {
			toastr.error( mlm_local_object.marked_fields );
		} else {
			toastr.info( mlm_local_object.wait );
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_save_lesson',
					'form_data'	: data,
					'security'	: $form.find('[name="mlm_nonce"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data) {
					toastr.clear();
					if( data.submited == true ) {
						toastr.success( data.response );
						setTimeout(function() {
							location.reload();
						}, 500 );
					} else {
						toastr.error( data.response );
					}
				},
				error: function(){
					toastr.clear();
					toastr.error( mlm_local_object.no_response );
				}
			});
		}
	});

	// DELETE LESSON
	$('a[href="#mlm-delete-lesson"]').on('click', function(e) {
		e.preventDefault();
		toastr.clear();
		var $btn = $(this), $parent = $btn.closest('.lesson-item');

		if( $btn.hasClass('loading') ) {
			toastr.info( mlm_local_object.wait );
		} else {
			var r = confirm( mlm_local_object.delete_lesson );
			if ( r == true ) {
				if( $parent.data('id').length == 0 ) {
					toastr.error( mlm_local_object.invalid_lesson );
				} else {
					toastr.info( mlm_local_object.wait );
					$.ajax({
						type	: 'POST',
						dataType: 'json',
						url		: mlm_local_object.ajax_url,
						data	: {
							'action'	: 'mlm_delete_lesson',
							'lesson_id'	: $parent.data('id'),
							'security'	: $btn.data('verify'),
						},
						beforeSend: function (response) {
							$btn.addClass('loading');
						},
						complete: function (response) {
							$btn.removeClass('loading');
						},
						success: function(data){
							toastr.clear();
							if( data.deleted == true ) {
								toastr.success( data.response );
								setTimeout(function() {
									location.reload();
								}, 500 );
							} else {
								toastr.error( data.response );
							}
						},
						error: function(){
							toastr.clear();
							toastr.error( mlm_local_object.no_response );
						}
					});
				}
			}
		}
	});

	// MODAL OPTIONS
	if($('.mlm-modal').length) {
		$('.mlm-modal').iziModal({
			subtitle		: '',
			headerColor		: '#757575',
			background		: null,
			rtl				: mlm_local_object.rtl,
			padding			: 15,
			zindex			: 99,
			radius			: 0,
			top				: 50,
			bottom			: 20,
			closeOnEscape	: true,
			closeButton		: true,
			timeout			: false,
		});
	}

	// NEW PRODUCT UPLOAD FIELD
	$(document).on('click', '.mlm-new-upload-field', function (e) {
		e.preventDefault();
		var $tmpl = $('.mlm-file-template').last();
		var $copy = $tmpl.clone();
		$copy.find('input.file').val('');
		$copy.find('input.name').val('');
		//increment input array index ( e.g [1] to [2] ) to have clean arrays in php to work with
        $copy.find('[name]').each(function(){
            this.name = this.name.replace(/\[(\d+)\]/,function(str,p1){return '[' + (parseInt(p1,10)+1) + ']'});
        });
		$tmpl.after($copy);
	});

	// REMOVE PRODUCT UPLOAD FIELD
	$(document).on('click', '.mlm-remove-upload-btn', function (e) {
		e.preventDefault();
		if( $(this).closest('form').find('.mlm-file-template').length > 1 ) {
			$(this).closest('.mlm-file-template').fadeOut(200).remove();
		} else {
			$(this).closest('.mlm-file-template').find('.file').val('');
			$(this).closest('.mlm-file-template').find('.name').val('');
		}
	});
});