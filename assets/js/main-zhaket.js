jQuery(document).ready(function($) {

	function mlm_toast(text,type) {
		var readyStateCheckInterval = setInterval(function () {
		if (document.readyState === "complete") {
			clearInterval(readyStateCheckInterval);
			var description = text;
			if (type == undefined || type == 'success') {
				var icon = mlm_local_object.notifier_icon_success;
			} else {
				var icon = mlm_local_object.notifier_icon_error;
			}
			var timeout = 3000;
			var position = "bottom-left";
			var color = {
			border: "#aaa",
			background: "#fff",
			color: "#000",
			radius: "4",
			};
			var sound = "";
			mlm_notifier.show(description,icon,timeout,position,color,sound);
		}
		}, 10);
	}

	// TOOLTIPS
	$('[data-toggle="tooltip"]').tooltip({
		trigger : 'hover'
	});

	// SLIM SCROLL
	$('.slimscroll').slimScroll({
		position		: ( mlm_local_object.rtl == 'true' ) ? 'left' : 'right',
		height			: '100%',
		alwaysVisible	: true
	});

	// TOGGLE NOTIFICATION BAR
	$(document).on('click', '.app-notification .close-notification-btn', function (e) {
        e.preventDefault();
		$('.app-notification').addClass('d-none');
		$('body').addClass('nnf');
		$.ajax({
			type	: 'POST',
			dataType: 'json',
			url		: mlm_local_object.ajax_url,
			data	: {
				action	: 'mlm_hide_notif'
			},
			beforeSend: function (response) {

			},
			complete: function (response) {

			},
			success: function(response){

			},
			error: function(){

			}
		});
    });

	// TOGGLE MOBILE MENU
	$(document).on('click', '#mlm-toggle-mobile-menu', function (e) {
        e.preventDefault();
        $('.app-mobile-menu').toggleClass('open');
        $('.app-panel-content .dashboard-menu').toggleClass('open');
		$('#mlm-toggle-mobile-menu').toggleClass('is-active');
    });

	// MOBILE MENU CLOSE BUTTON
	$(document).on('click', '.app-close-mobile-btn', function (e) {
        e.preventDefault();
        $('.app-mobile-menu').removeClass('open');
        $('.app-panel-content .dashboard-menu').removeClass('open');
		$('#mlm-toggle-mobile-menu').removeClass('is-active');
    });

	// OPEN SEARCH
	$(document).on('click', '.app-search-btn', function (e) {
        e.preventDefault();
        $('.app-search-popup').removeClass('hide');
		$('body').addClass('search-open');
    });

	// CLOSE SEARCH
	$(document).on('click', '.app-close-search-btn', function (e) {
        e.preventDefault();
        $('.app-search-popup').addClass('hide');
		$('body').removeClass('search-open');
    });

	// OPEN CART
	$(document).on('click', '.app-basket-btn', function (e) {
        e.preventDefault();
        $('.app-cart-popup').removeClass('hide');
        $('body').addClass('card-open');
    });

	// CLOSE CART
	$(document).on('click', '.app-close-cart-btn', function (e) {
        e.preventDefault();
        $('.app-cart-popup').addClass('hide');
		$('body').removeClass('card-open');
    });

	// TOGGLE MOBILE NAVIGATION
	$(document).on('click', '.app-mobile-menu .menu-item-has-children > .nav-link', function (e) {
        e.preventDefault();
		$('.app-mobile-menu .sub-menu').fadeOut(100);
		if( $(this).next().hasClass('ack') ) {
			$(this).next().fadeOut(100);
			$(this).next().removeClass('ack');
		} else {
			$(this).next().fadeIn(300);
			$(this).next().addClass('ack');
		}
    });

	// CATEGORIES TOGGLE
	$(document).on('click', 'a[href="#mlm-toggle-category-btn"]', function (e) {
        e.preventDefault();
        $parent = $(this).closest('.mlm-category-widget');
		if( $(this).hasClass('acik') ) {
			$(this).removeClass('acik').text(mlm_local_object.show_more);
			$parent.find('.col-auto.d-flex').removeClass('d-flex').addClass('d-none');
		} else {
			$(this).addClass('acik').text(mlm_local_object.show_less);
			$parent.find('.col-auto.d-none').removeClass('d-none').addClass('d-flex');
		}
    });

	// FIXED HEADER
	var lastScrollTop = 0, delta = 5;

	$(window).bind('scroll', function(event) {

		if( $('#app-main-content').length === 0 || $('.app-fixed-header').length === 0 ) {
			return;
		}

		var nowScrollTop = $(this).scrollTop();
		var main = $('#app-main-content').offset().top;

		if(Math.abs(lastScrollTop - nowScrollTop) >= delta){
			if (nowScrollTop <= lastScrollTop){
				$('.app-fixed-header').removeClass('hide');

				if( $('.app-fixed-header').hasClass('home-page') && window.scrollY < ( main - 220 ) ) {
					$('.app-fixed-header').addClass('home-header');
				}
			} else if( window.scrollY >= ( main - 220 ) ) {
				$('.app-fixed-header').addClass('hide');

				if( $('.app-fixed-header').hasClass('home-page') ) {
					setTimeout(function() {
						$('.app-fixed-header').removeClass('home-header');
					}, 500 );
				}
			}

			lastScrollTop = nowScrollTop;
		}
	});

	// TOGGLE PANEL NAV
	$(document).on('click', '.app-panel-content .multi > a', function (e) {
        e.preventDefault();
		$li = $(this).closest('.multi');
		if( $li.hasClass('acik') ) {
			$li.find('.children').slideUp(200);
			$li.removeClass('acik');
		} else {
			$('.app-panel-content .multi').find('.children').slideUp(200);
			$('.app-panel-content .multi').removeClass('acik');
			$li.find('.children').slideDown(200);
			$li.addClass('acik');
		}
    });

	// TOGGLE SECURITY TIPS
	$(document).on('click', 'a[href="#security-tips-toggle"]', function (e) {
        e.preventDefault();
        $('.login-page-wrapper .security-tips').toggleClass('open');
    });

	// DASHBOARD ACTIVE TAB
	if( $('.app-panel-content li.acik').length !== 0 ) {
		var $id = $('.app-panel-content li.acik').closest('.tab-pane').attr('id');
		$('.dashboard-menu .nav-tabs a[href="#'+$id+'"]').tab('show');
	}

	// POPUP ENTER
	$(document).on('mouseenter', '.app-home-tabs .product-item', function (e) {
		var $a = $(this);

		$('.app-fixed-popup-box .item-image img').attr( 'src', $a.data('image') );
		$('.app-fixed-popup-box .item-avatar img').attr( 'src', $a.data('avatar') );
		$('.app-fixed-popup-box .item-vendor').text( $a.data('vendor') );
		$('.app-fixed-popup-box .item-bio').text( $a.data('bio') );
		$('.app-fixed-popup-box .item-title').text( $a.data('title') );
		$('.app-fixed-popup-box .item-text').text( $a.data('text') );
		$('.app-fixed-popup-box .item-sale').text( $a.data('sale') );
		$('.app-fixed-popup-box .item-rate').text( $a.data('rate') );
		$('.app-fixed-popup-box .item-price .v').text( $a.data('price') );

		if( $(this).offset().left + $(this).width() + 350 < window.innerWidth ) {
			var $left = $(this).offset().left + $(this).width() + 15;
		} else if( $(this).offset().left < 350 ) {
			var $left = 15;
		} else {
			var $left = $(this).offset().left - 350;
		}

		$('.app-fixed-popup-box').css({top: ($(this).offset().top - 150), left: $left});


		$('.app-fixed-popup-box').addClass('active');
	});

	// POPUP LEAVE
	$(document).on('mouseleave', '.app-home-tabs .product-item', function (e) {
		$('.app-fixed-popup-box').removeClass('active');
	});

	// TOP VENDOR SLIDER
	var topVendor = new Swiper('.top-vendor-slider', {
		effect			: 'coverflow',
		grabCursor		: true,
		centeredSlides	: true,
		slidesPerView	: 'auto',
		coverflowEffect	: {
			rotate			: 0,
			stretch			: 0,
			depth			: 500,
			modifier		: 1,
			slideShadows	: false,
		},
		pagination		: {
			el				: '.swiper-pagination',
			clickable		: true,
		},
    });

	// PRODUCTS SLIDER
	var products = new Swiper('.mlm-products-slider', {
		rtl				: mlm_local_object.rtl,
		autoplay		: {
			delay					: 10000,
			disableOnInteraction	: false,
		},
		// Responsive breakpoints
		breakpoints: {
			381: {
				slidesPerView: 1,
				spaceBetween: 30
			},
			767: {
				slidesPerView: 2,
				spaceBetween: 30
			},
			991: {
				slidesPerView: 3,
				spaceBetween: 30
			},
			1023: {
				slidesPerView: 4,
				spaceBetween: 30
			}
		},
    });

	// VENDOR PRODUCTS SLIDER
	var vendor = new Swiper('.mlm-vendor-products-slider', {
		rtl				: mlm_local_object.rtl,
		slidesPerView	: 'auto',
		spaceBetween	: 15,
		autoplay		: {
			delay					: 10000,
			disableOnInteraction	: false,
		}
    });

	// VENDOR TOP PRODUCTS SLIDER
	var vendor = new Swiper('.mlm-vendor-top-slider', {
		rtl							: mlm_local_object.rtl,
		slidesPerView				: 'auto',
		spaceBetween				: 15,
		autoplay					: {
			delay					: 10000,
			disableOnInteraction	: false,
		},
		centerInsufficientSlides	: true
    });

	// NAMAD SLIDER
	var namad = new Swiper('.mlm-namad-slider', {
		rtl				: mlm_local_object.rtl,
		slidesPerView	: 1,
		spaceBetween	: 0,
		navigation		: false,
		pagination		: {
			el						: '.swiper-pagination',
			clickable				: true,
		},
		autoplay		: {
			delay					: 5000,
			disableOnInteraction	: false,
		}
    });

	// OFFERS SLIDER
	var mlm = new Swiper('.mlm-offers-slider', {
		rtl				: mlm_local_object.rtl,
		slidesPerView	: 1,
		spaceBetween	: 0,
		navigation		: {
			nextEl					: '.swiper-button-next',
			prevEl					: '.swiper-button-prev',
		},
		/*autoplay		: {
			delay					: 6000,
			disableOnInteraction	: false,
		}*/
    });

	// FIX MODAL SCROLLBAR DISAPPEAR
	$('#mlm_product_images').on('show.bs.modal', function (e) {
        $('html').addClass('modal-open');
    });

	// FIX MODAL SCROLLBAR DISAPPEAR
    $('#mlm_product_images').on('hide.bs.modal', function (e) {
        $('html').removeClass('modal-open');
    });

	// CLIPBOARD
	var clipboard = new ClipboardJS('.mlm-clipboard');
	clipboard.on('success', function(e) {
		swal({
			title	: mlm_local_object.copied,
			text	: mlm_local_object.shortcode_copy,
			icon	: 'success',
			button	: mlm_local_object.ok,
		});
	});

	// TOASTR OPTIONS
	toastr.options = {
		'preventDuplicates'	: true,
		'positionClass'		: 'toast-bottom-center',
		'rtl'				: mlm_local_object.rtl
	};

	// TOGGLE LOGIN FORM
	$(document).on('click', 'a[href="#mlm-toggle-login-form"]', function (e) {
        e.preventDefault();
		$(this).closest('.mlm-popup-form').fadeOut(10);
		$('.mlm-popup-login-form').fadeIn(600);
    });

	// TOGGLE REGISTER FORM
	$(document).on('click', 'a[href="#mlm-toggle-register-form"]', function (e) {
        e.preventDefault();
		$(this).closest('.mlm-popup-form').fadeOut(10);
		$('.mlm-popup-register-form').fadeIn(600);
    });

	// TOGGLE PASSWORD FORM
	$(document).on('click', 'a[href="#mlm-toggle-password-form"]', function (e) {
        e.preventDefault();
		$(this).closest('.mlm-popup-form').fadeOut(10);
		$('.mlm-popup-password-form').fadeIn(600);
    });

	// TOGGLE PASSWORD FORM
	$(document).on('click', 'a[href="#mlm-toggle-tags"]', function (e) {
        e.preventDefault();
		$parent = $(this).closest('.mlm-post-tags');
		if( $parent.hasClass('kapali') ) {
			$parent.removeClass('kapali');
			$(this).text(mlm_local_object.show_less);
		} else {
			$parent.addClass('kapali');
			$(this).text(mlm_local_object.show_more);
		}
    });

	// CATS SELECT
	var mlm_cats = $('#mlm_cat').select2({
		multiple		: true,
		placeholder		: mlm_local_object.select_cat,
		tags			: false,
		tokenSeparators	: [','],
		selectOnClose	: true
	});

	// TAGS SELECT
	var mlm_tags = $('#mlm_tag').select2({
		multiple		: true,
		placeholder		: mlm_local_object.select_tag,
		tags			: true,
		tokenSeparators	: [','],
		selectOnClose	: true
	});

	// Search tags
	var mlm_tags = $('#mlm_search_tags').select2({
		multiple		: true,
		placeholder		: mlm_local_object.select_tag,
		tags			: false,
		tokenSeparators	: [','],
		selectOnClose	: true
	});

	// PRODUCTS SELECT
	var mlm_cats = $('#mlm_products').select2({
		multiple		: true,
		placeholder		: mlm_local_object.select_product,
		tags			: false,
		tokenSeparators	: [','],
		selectOnClose	: true
	});

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

	// UPLOAD IMAGE
	$(document).on('click', '.mlm-upload-image-btn', function (e) {
		e.preventDefault();
		var $button = $(this), file_frame;
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
		file_frame.on('select', function () {
			var attachment = file_frame.state().get('selection').first().toJSON();
			$button.parent().find('.image_id').val( attachment.id );
			$button.parent().find('.image').val( attachment.url );
			$button.parent().find('.mlm-image-preview > img').attr( 'src', attachment.url );

			if( $button.hasClass('dynamic-btn') ) {
				$button.removeClass('btn-light bg-white').addClass('btn-success');
			}
		});
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

	// LOGIN REQUIRED ERROR
	$(document).on('click', '.mlm-login-error', function (e) {
        swal({
			title	: mlm_local_object.error,
			text	: mlm_local_object.login_to_dl,
			icon	: 'error',
			button	: mlm_local_object.ok,
		});
    });

	// LOGIN REQUIRED ERROR
	$(document).on('click', '.mlm-need-to-purchase-plan-btn', function (e) {
		e.preventDefault();
        swal({
			title	: mlm_local_object.error,
			text	: mlm_local_object.plan_to_dl,
			icon	: 'error',
			button	: mlm_local_object.ok,
		});
    });

	// LOADING BUTTON
	function mlm_loading_button( elem ) {
		elem.attr('disabled', 'disabled').addClass('disabled').empty().append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + mlm_local_object.loading );
	}

	// UNLOADING BUTTON
	function mlm_unloading_button( elem, txt ) {
		elem.removeAttr('disabled').removeClass('disabled').empty().append(txt);
	}

	// ARCHIVE ADD PRODUCT TO CART
	$(document).on('click', 'a[href="#mlm-add-to-cart"]', function (e) {
		e.preventDefault();
		toastr.clear();

		var elem = $(this), txt = elem.text();
		if( elem.hasClass('disabled') ) {
			return false;
		} else if( elem.data('id').length == 0 ) {
			toastr.error( mlm_local_object.invalid_id );
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					action			: 'mlm_add_to_cart',
					product_id		: elem.data('id'),
					product_sku		: '',
				},
				beforeSend: function (response) {
					mlm_loading_button( elem );
				},
				complete: function (response) {
					mlm_unloading_button( elem, txt );
				},
				success: function(response){
					// toastr.clear();
					mlm_toast(mlm_local_object.product_added);
					$(".app-cart-popup").removeClass("hide");
          			$("body").addClass("card-open");
					// toastr.success( mlm_local_object.product_added );
					if(response.error & response.product_url) {
						window.location = response.product_url;
						return;
					} else {
						if( mlm_local_object.redirect == 'yes' )
						{
							setTimeout(function() {
								window.location = mlm_local_object.checkout;
							}, 100 );
						}
						$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, elem]);
					}
				},
				error: function(){
					// toastr.clear();
					// toastr.error( mlm_local_object.no_response );
					mlm_toast(mlm_local_object.no_response, "error");
				}
			});
		}
	});

	// SINGLE ADD PRODUCT TO CART
	$(document).on('click', '.single_add_to_cart_button', function (e) {
		if( $(this).hasClass('disabled') ) {
			return false;
			/* wc-variation-selection-needed */
		}
		e.preventDefault();
		var $btn = $(this), txt = $btn.text(),
		$form = $btn.closest('form.cart'),
		id = $btn.val(),
		product_qty = $form.find('input[name=quantity]').val() || 1,
		product_id = $form.find('input[name=product_id]').val() || id,
		variation_id = $form.find('input[name=variation_id]').val() || 0;
		// $(document.body).trigger('adding_to_cart', [$btn, data]);
        $.ajax({
			type	: 'POST',
			dataType: 'json',
			url		: mlm_local_object.ajax_url,
			data	: {
				action			: 'mlm_add_to_cart',
				product_id		: product_id,
				product_sku		: '',
				quantity		: product_qty,
				variation_id	: variation_id,
			},
			beforeSend: function (response) {
				$btn.removeClass('added');
				mlm_loading_button( $btn );
            },
			complete: function (response) {
				$btn.addClass('added');
				mlm_unloading_button( $btn, txt );
            },
			success: function (response) {
				// toastr.clear();
				// toastr.success( mlm_local_object.product_added );
				mlm_toast(mlm_local_object.product_added);
				$(".app-cart-popup").removeClass("hide");
				$("body").addClass("card-open");

				if (response.error & response.product_url) {
					window.location = response.product_url;
					return;
				} else {
					if( mlm_local_object.redirect == 'yes' )
					{
						setTimeout(function() {
							window.location = mlm_local_object.checkout;
						}, 100 );
					}
					$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);
				}
            },
			error: function(){
				mlm_toast(mlm_local_object.no_response,'error');
				// toastr.clear();
				// toastr.error( mlm_local_object.no_response );
			}
        });

        return false;
	});

	// AJAX POST RATING
	$(document).on('change', '.mlm-rating-box input[type="radio"]', function (e) {
		e.preventDefault();
		$input = $(this);
		if( $input.closest('.mlm-rating-box').hasClass('loading') ) {
			return false;
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_rating',
					'rating'	: $input.val(),
					'post_id'	: $input.closest('.mlm-rating-box').data('id'),
					'security'	: $input.closest('.mlm-rating-box').data('verify')
				},
				beforeSend: function (response) {
					$input.closest('.mlm-rating-box').addClass('loading');
				},
				complete: function (response) {
					$input.closest('.mlm-rating-box').removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						$input.closest('.mlm-rating-box').find('[itemprop="ratingValue"]').text( data.average );
						$input.closest('.mlm-rating-box').find('[itemprop="ratingCount"]').text( data.total );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
					if( data.popup == true ) {
						$('#mlm-login-register-popup').modal('show');
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX FOLLOW VENDOR
	$(document).on('click', 'a[href="#mlm-follow-btn"]', function (e) {
		e.preventDefault();
		var elem = $(this);

		if( elem.hasClass('disabled') ) {
			return false;
		}

		$.ajax({
			type	: 'POST',
			dataType: 'json',
			url		: mlm_local_object.ajax_url,
			data	: {
				'action'	: 'mlm_follow',
				'vendor_id'	: elem.data('vendor'),
				'security'	: elem.data('verify')
			},
			beforeSend: function (response) {
				elem.attr('disabled', 'disabled').addClass('disabled');
			},
			complete: function (response) {
				elem.removeAttr('disabled', 'disabled').removeClass('disabled');
			},
			success: function(data){
				if( data.submited == true ) {
					swal({
						title	: mlm_local_object.ok,
						text	: data.response,
						icon	: 'success',
						button	: mlm_local_object.ok,
					});
					if( data.mode == 'follow' ) {
						elem.removeClass('btn-primary').addClass('followed bg-white border text-dark').text(mlm_local_object.unfollow);
					} else {
						elem.removeClass('followed bg-white border text-dark').addClass('btn-primary').text(mlm_local_object.follow);
					}
				} else {
					swal({
						title	: mlm_local_object.error,
						text	: data.response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
				if( data.popup == true ) {
					$('#mlm-login-register-popup').modal('show');
				}
			},
			error: function(){
				swal({
					title	: mlm_local_object.error,
					text	: mlm_local_object.no_response,
					icon	: 'error',
					button	: mlm_local_object.ok,
				});
			}
		});
	});

	// AJAX LOGIN CALLBACK
	function mlm_ajax_login( $form ) {

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user	= {
			'login'		: $form.find('[name="log"]').val(),
			'pass'		: $form.find('[name="pwd"]').val(),
			'remember'	: $form.find('[name="rememberme"]:checked').val()
		};

		if( typeof grecaptcha === 'object' && $form.find('[name="mlm_recaptcha"]').val().length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.re_token,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else if( user['login'].length == 0 || user['pass'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.user_pass,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'		: 'mlm_login',
					'user_data'		: user,
					'mlm_recaptcha'	: $form.find('[name="mlm_recaptcha"]').val(),
					'security'		: $form.find('[name="wp-submit"]').data('verify')
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.registered == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
							//location.reload();
						}, 2000 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	}

	// AJAX REGISTER CALLBACK
	function mlm_ajax_register( $form ) {

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user	= {
			'uname'		: $form.find('[name="mlm_uname"]').val(),
			'email'		: $form.find('[name="mlm_email"]').val(),
			'mobile'	: $form.find('[name="mlm_mobile"]').val(),
            'country_code'	: $form.find('[name="mlm_country_code"]').val(),
			'pass'		: $form.find('[name="mlm_pass"]').val(),
			'code'		: $form.find('[name="mlm_code"]').val()
		};

		if( typeof grecaptcha === 'object' && $form.find('[name="mlm_recaptcha"]').val().length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.re_token,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else if( user['uname'].length == 0 || user['mobile'].length == 0 || user['pass'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.all_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'		: 'mlm_register',
					'user_data'		: user,
					'mlm_recaptcha'	: $form.find('[name="mlm_recaptcha"]').val(),
					'security'		: $form.find('.btn-primary').attr( 'data-verify' )
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.registered == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 2000 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	}

	// AJAX LOGIN
	$(document).on('submit', '#mlm-login-form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		if( typeof grecaptcha === 'object' ) {
			swal({
				title	: mlm_local_object.get_token,
				text	: mlm_local_object.we_token,
				icon	: 'info',
				button	: mlm_local_object.ok,
			});
			grecaptcha.ready(function() {
				grecaptcha.execute(mlm_local_object.site_key, {action: 'login'}).then(
					function(token) {
						swal.close();
						$form.find('[name="mlm_recaptcha"]').val(token);
						mlm_ajax_login( $form );
					},
					function (error) {
						console.log(error);
						swal.close();
						swal({
							title	: mlm_local_object.error,
							text	: mlm_local_object.no_token,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				);
			});
		} else {
			mlm_ajax_login( $form );
		}
	});

	// AJAX REGISTER
	$(document).on('submit', '#mlm_signup_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		if( typeof grecaptcha === 'object' ) {
			swal({
				title	: mlm_local_object.get_token,
				text	: mlm_local_object.we_token,
				icon	: 'info',
				button	: mlm_local_object.ok,
			});
			grecaptcha.ready(function() {
				grecaptcha.execute(mlm_local_object.site_key, {action: 'register'}).then(
					function(token) {
						swal.close();
						$form.find('[name="mlm_recaptcha"]').val(token);
						mlm_ajax_register( $form );
					},
					function (error) {
						console.log(error);
						swal.close();
						swal({
							title	: mlm_local_object.error,
							text	: mlm_local_object.no_token,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				);
			});
		} else {
			mlm_ajax_register( $form );
		}
	});

	// AJAX PASSWORD LOST - SEND CODE
	$(document).on('click', '#mlm_lost_password_form .mlm-send-code-btn', function (e) {
		e.preventDefault();
		var elem = $(this), txt = elem.text(), $form = elem.closest('form');

		if( elem.hasClass('disabled') ) {
			return false;
		}

		if( $form.find('[name="mlm_login"]').val().length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.lum_req,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_lost_code',
					'login'		: $form.find('[name="mlm_login"]').val(),
					'security'	: $form.find('.mlm-submit-btn').data('verify')
				},
				beforeSend: function (response) {
					mlm_loading_button( elem );
				},
				complete: function (response) {
					mlm_unloading_button( elem, txt );
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						$form.find('.d-none').each(function(){
							$(this).removeClass('d-none');
						});
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX PASSWORD LOST - SUBMIT CODE
	$(document).on('submit', '#mlm_lost_password_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user	= {
			'code'	: $form.find('[name="mlm_code"]').val(),
			'login'	: $form.find('[name="mlm_login"]').val()
		};

		if( user['login'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.lum_req,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else if( user['code'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.code_req,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_pass_code',
					'user_data'	: user,
					'security'	: $form.find('.mlm-submit-btn').data('verify')
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 1000 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX PASSWORD LOST - NEW PASSWORD
	$(document).on('submit', '#mlm_new_password_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user	= {
			'login'		: $form.find('[name="mlm_login"]').val(),
			'pass'		: $form.find('[name="mlm_pass"]').val(),
			'repeat'	: $form.find('[name="mlm_repeat"]').val()
		};
		if( user['pass'].length == 0 || user['repeat'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.pass_req,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_new_pass',
					'user_data'	: user,
					'security'	: $form.find('.mlm-submit-btn').data('verify')
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.registered == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 2000 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SUBMIT PROFILE
	$(document).on('submit', '#mlm_profile_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user_data = {
			'avatar'	: $form.find('[name="mlm_avatar"]').val(),
			'cover'		: $form.find('[name="mlm_cover"]').val(),
			'fname'		: $form.find('[name="mlm_fname"]').val(),
			'lname'		: $form.find('[name="mlm_lname"]').val(),
			'email'		: $form.find('[name="mlm_email"]').val(),
			'mobile'	: $form.find('[name="mlm_mobile"]').val(),
            'country_code'	: $form.find('[name="mlm_country_code"]').val(),
			'state'		: $form.find('[name="mlm_state"]').val(),
			'bio'		: $form.find('[name="mlm_bio"]').val(),
			'twitter'	: $form.find('[name="mlm_twitter"]').val(),
			'aparat'	: $form.find('[name="mlm_aparat"]').val(),
			'instagram'	: $form.find('[name="mlm_instagram"]').val(),
			'telegram'	: $form.find('[name="mlm_telegram"]').val(),
			'youtube'	: $form.find('[name="mlm_youtube"]').val()
		};

		if( user_data['fname'].length == 0 || user_data['lname'].length == 0 || user_data['email'].length == 0 || user_data['mobile'].length == 0 || user_data['state'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_profile',
					'user_data'	: user_data,
					'security'	: $form.find('[name="mlm_security"]').val(),
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.updated,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						if( data.avatar ) {
							$('.mlm-image-preview img.avatar').attr( "src", data.avatar );
							$('.mlm-user-panel-widget img.avatar').attr( "src", data.avatar );
						}
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
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

	// SELECT TICKET RECEPIENT CALLBACK
	$(document).on('change', '#mlm_new_ticket_form [name="mlm_subject"]', function (e) {
		var $value = $(this).val(), $form = $(this).closest('form');
		$form.find('.form-group').each(function(){
			if( $(this).hasClass('state-'+$value) ) {
				$(this).slideDown(200).removeClass('gzl');
			} else {
				$(this).slideUp(100).addClass('gzl');
			}
		});
	});

	// AJAX SUBMIT TICKET.
	$(document).on('submit', '#mlm_new_ticket_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var ticket = {
			'subject'	: $form.find('[name="mlm_subject"]').val(),
			'unit'		: $form.find('[name="mlm_unit"]').val(),
			'recipient'	: $form.find('[name="mlm_user"]').val(),
			'post_id'	: $form.find('[name="mlm_user"] option:selected').data('post'),
			'title'		: $form.find('[name="mlm_title"]').val(),
			'content'	: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'attaches'	: $form.find('[name="mlm_attaches[]"]').map(function(){return $(this).val();}).get()
		};

		if( ticket['subject'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.tick_subject,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else if( ticket['subject'] == 1 && ticket['recipient'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.tick_product,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else if( ticket['subject'] == 2 && ticket['unit'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.tick_department,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else if( ticket['title'].length == 0 || ticket['content'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.tick_req,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
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
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						$form.find('[name="mlm_user"]').val('');
						$form.find('[name="mlm_title"]').val('');
						tmce_setContent( '', 'mlm_content', 'mlm_content' );
						setTimeout(function() {
							window.location = data.redirect;
						}, 1000 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX REPLY TICKET.
	$(document).on('submit', '#mlm_reply_ticket_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var ticket = {
			'content'	: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'status'	: $form.find('[name="mlm_status"]').val(),
			'parent'	: $form.find('[name="mlm_parent"]').val(),
			'attaches'	: $form.find('[name="mlm_attaches[]"]').map(function(){return $(this).val();}).get()
		};
		if( ticket['content'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.tick_rep,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else if( ticket['parent'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.invalid_ticket,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'		: 'mlm_reply_ticket',
					'ticket_data'	: ticket,
					'security'		: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							location.reload();
						}, 2000);
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SUBMIT POST
	$(document).on('submit', '#mlm_submit_post_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var post_data = {
			'title'				: $form.find('[name="mlm_title"]').val(),
			'content'			: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'thumb'				: $form.find('[name="mlm_thumb"]').val(),
			'cats'				: $form.find('[name="mlm_cat"]').val(),
			'tags'				: $form.find('[name="mlm_tag"]').val(),
			'post_id'			: $form.find('[name="mlm_id"]').val()
		};
		if( post_data['title'].length == 0 || post_data['content'].length == 0 || post_data['cats'].length == 0 || post_data['tags'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_submit_post',
					'post_data'	: post_data,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SUBMIT PRODUCT
	$(document).on('submit', '#mlm_submit_product_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var post_data = {
			'title'			: $form.find('[name="mlm_title"]').val(),
			'content'		: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'thumb'			: $form.find('[name="mlm_thumb"]').val(),
			'cats'			: $form.find('[name="mlm_cat"]').val(),
			'tags'			: $form.find('[name="mlm_tag"]').val(),
			'percent'		: $form.find('[name="mlm_percent"]').val(),
			'price'			: $form.find('[name="mlm_price"]').val(),
			'sale_price'	: $form.find('[name="mlm_sale_price"]').val(),
			'button_text'	: $form.find('[name="mlm_button_text"]').val(),
			'button_link'	: $form.find('[name="mlm_button_link"]').val(),
            'button_2_text'	: $form.find('[name="mlm_button_2_text"]').val(),
            'button_2_link'	: $form.find('[name="mlm_button_2_link"]').val(),
			'post_id'		: $form.find('[name="mlm_id"]').val(),
			'thumb_image'	: $form.find('[name="mlm_thumb_image"]').val(),
			'image_one'		: $form.find('[name="mlm_image_one"]').val(),
			'image_two'		: $form.find('[name="mlm_image_two"]').val(),
			'stock'			: $form.find('[name="mlm_stock"]').val(),
		};

		if( $('#mlm-custom-fields-wrap').length !== 0 ) {
			$('#mlm-custom-fields-wrap').find(':input').each(function(){
				var el = $(this);
				post_data[el.attr('name')] = $.trim( el.val() );
			});
		} else {
			post_data['type']		= $form.find('[name="mlm_type"]').val();
			post_data['count']		= $form.find('[name="mlm_count"]').val();
			post_data['part']		= $form.find('[name="mlm_part"]').val();
			post_data['author']		= $form.find('[name="mlm_author"]').val();
			post_data['size']		= $form.find('[name="mlm_size"]').val();
			post_data['format']		= $form.find('[name="mlm_format"]').val();
			post_data['language']	= $form.find('[name="mlm_language"]').val();
			post_data['step']		= $form.find('[name="mlm_step"]').val();
		}

		$form.find('.mlm-upload-group :input').each(function(){
			var el = $(this);
			post_data[el.attr('name')] = $.trim( el.val() );
		});

		if( post_data['title'].length == 0 || post_data['content'].length == 0 || post_data['cats'].length == 0 || post_data['tags'].length == 0 || post_data['price'].length == 0 || post_data['percent'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_submit_product',
					'post_data'	: post_data,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SAVE PRODUCT DRAFT
	$(document).on('click', '#mlm_submit_product_form .mlm-save-draft-btn', function (e) {
		e.preventDefault();
		$form = $(this).closest('form');

		if( $form.hasClass('loading') ) {
			return false;
		}

		var post_data = {
			'title'			: $form.find('[name="mlm_title"]').val(),
			'content'		: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'thumb'			: $form.find('[name="mlm_thumb"]').val(),
			'cats'			: $form.find('[name="mlm_cat"]').val(),
			'tags'			: $form.find('[name="mlm_tag"]').val(),
			'percent'		: $form.find('[name="mlm_percent"]').val(),
			'price'			: $form.find('[name="mlm_price"]').val(),
			'sale_price'	: $form.find('[name="mlm_sale_price"]').val(),
			'button_text'	: $form.find('[name="mlm_button_text"]').val(),
			'button_link'	: $form.find('[name="mlm_button_link"]').val(),
            'button_2_text'	: $form.find('[name="mlm_button_2_text"]').val(),
            'button_2_link'	: $form.find('[name="mlm_button_2_link"]').val(),
			'post_id'		: $form.find('[name="mlm_id"]').val(),
			'thumb_image'	: $form.find('[name="mlm_thumb_image"]').val(),
			'image_one'		: $form.find('[name="mlm_image_one"]').val(),
			'image_two'		: $form.find('[name="mlm_image_two"]').val(),
			'stock'			: $form.find('[name="mlm_stock"]').val(),
		};

		if( $('#mlm-custom-fields-wrap').length !== 0 ) {
			$('#mlm-custom-fields-wrap').find(':input').each(function(){
				var el = $(this);
				post_data[el.attr('name')] = $.trim( el.val() );
			});
		} else {
			post_data['type']		= $form.find('[name="mlm_type"]').val();
			post_data['count']		= $form.find('[name="mlm_count"]').val();
			post_data['part']		= $form.find('[name="mlm_part"]').val();
			post_data['author']		= $form.find('[name="mlm_author"]').val();
			post_data['size']		= $form.find('[name="mlm_size"]').val();
			post_data['format']		= $form.find('[name="mlm_format"]').val();
			post_data['language']	= $form.find('[name="mlm_language"]').val();
			post_data['step']		= $form.find('[name="mlm_step"]').val();
		}

		$form.find('.mlm-upload-group :input').each(function(){
			var el = $(this);
			post_data[el.attr('name')] = $.trim( el.val() );
		});

		if( post_data['title'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.draft_title,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_draft_product',
					'post_data'	: post_data,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						if( data.post_id != false ) {
							$form.find('[name="mlm_id"]').val( data.post_id );
						}
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SUBMIT COURSE
	$(document).on('submit', '#mlm_submit_course_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var post_data = {
			'title'			: $form.find('[name="mlm_title"]').val(),
			'content'		: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'thumb'			: $form.find('[name="mlm_thumb"]').val(),
			'cats'			: $form.find('[name="mlm_cat"]').val(),
			'tags'			: $form.find('[name="mlm_tag"]').val(),
			'percent'		: $form.find('[name="mlm_percent"]').val(),
			'price'			: $form.find('[name="mlm_price"]').val(),
			'sale_price'	: $form.find('[name="mlm_sale_price"]').val(),
			'button_text'	: $form.find('[name="mlm_button_text"]').val(),
			'button_link'	: $form.find('[name="mlm_button_link"]').val(),
            'button_2_text'	: $form.find('[name="mlm_button_2_text"]').val(),
            'button_2_link'	: $form.find('[name="mlm_button_2_link"]').val(),
			'teacher_image'	: $form.find('[name="mlm_teacher_image"]').val(),
			'teacher_name'	: $form.find('[name="mlm_teacher_name"]').val(),
			'course_fill'	: $form.find('[name="mlm_course_fill"]').val(),
			'teacher_bio'	: $form.find('[name="mlm_teacher_bio"]').val(),
			'course_video'	: $form.find('[name="mlm_course_video"]').val(),
			'post_id'		: $form.find('[name="mlm_id"]').val(),
			'thumb_image'	: $form.find('[name="mlm_thumb_image"]').val(),
			'image_one'		: $form.find('[name="mlm_image_one"]').val(),
			'image_two'		: $form.find('[name="mlm_image_two"]').val(),
			'stock'			: $form.find('[name="mlm_stock"]').val(),
		};

		if( $('#mlm-custom-fields-wrap').length !== 0 ) {
			$('#mlm-custom-fields-wrap').find(':input').each(function(){
				var el = $(this);
				post_data[el.attr('name')] = $.trim( el.val() );
			});
		} else {
			post_data['type']		= $form.find('[name="mlm_type"]').val();
			post_data['count']		= $form.find('[name="mlm_count"]').val();
			post_data['part']		= $form.find('[name="mlm_part"]').val();
			post_data['author']		= $form.find('[name="mlm_author"]').val();
			post_data['size']		= $form.find('[name="mlm_size"]').val();
			post_data['format']		= $form.find('[name="mlm_format"]').val();
			post_data['language']	= $form.find('[name="mlm_language"]').val();
			post_data['step']		= $form.find('[name="mlm_step"]').val();
		}

		if( post_data['title'].length == 0 || post_data['content'].length == 0 || post_data['cats'].length == 0 || post_data['tags'].length == 0 || post_data['price'].length == 0 || post_data['percent'].length == 0 || post_data['teacher_name'].length == 0 || post_data['course_fill'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_submit_course',
					'post_data'	: post_data,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SAVE COURSE DRAFT
	$(document).on('click', '#mlm_submit_course_form .mlm-save-draft-btn', function (e) {
		e.preventDefault();
		$form = $(this).closest('form');

		if( $form.hasClass('loading') ) {
			return false;
		}

		var post_data = {
			'title'			: $form.find('[name="mlm_title"]').val(),
			'content'		: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'thumb'			: $form.find('[name="mlm_thumb"]').val(),
			'cats'			: $form.find('[name="mlm_cat"]').val(),
			'tags'			: $form.find('[name="mlm_tag"]').val(),
			'percent'		: $form.find('[name="mlm_percent"]').val(),
			'price'			: $form.find('[name="mlm_price"]').val(),
			'sale_price'	: $form.find('[name="mlm_sale_price"]').val(),
			'button_text'	: $form.find('[name="mlm_button_text"]').val(),
			'button_link'	: $form.find('[name="mlm_button_link"]').val(),
            'button_2_text'	: $form.find('[name="mlm_button_2_text"]').val(),
            'button_2_link'	: $form.find('[name="mlm_button_2_link"]').val(),
			'teacher_image'	: $form.find('[name="mlm_teacher_image"]').val(),
			'teacher_name'	: $form.find('[name="mlm_teacher_name"]').val(),
			'course_fill'	: $form.find('[name="mlm_course_fill"]').val(),
			'teacher_bio'	: $form.find('[name="mlm_teacher_bio"]').val(),
			'course_video'	: $form.find('[name="mlm_course_video"]').val(),
			'post_id'		: $form.find('[name="mlm_id"]').val(),
			'thumb_image'	: $form.find('[name="mlm_thumb_image"]').val(),
			'image_one'		: $form.find('[name="mlm_image_one"]').val(),
			'image_two'		: $form.find('[name="mlm_image_two"]').val(),
			'stock'			: $form.find('[name="mlm_stock"]').val(),
		};

		if( $('#mlm-custom-fields-wrap').length !== 0 ) {
			$('#mlm-custom-fields-wrap').find(':input').each(function(){
				var el = $(this);
				post_data[el.attr('name')] = $.trim( el.val() );
			});
		} else {
			post_data['type']		= $form.find('[name="mlm_type"]').val();
			post_data['count']		= $form.find('[name="mlm_count"]').val();
			post_data['part']		= $form.find('[name="mlm_part"]').val();
			post_data['author']		= $form.find('[name="mlm_author"]').val();
			post_data['size']		= $form.find('[name="mlm_size"]').val();
			post_data['format']		= $form.find('[name="mlm_format"]').val();
			post_data['language']	= $form.find('[name="mlm_language"]').val();
			post_data['step']		= $form.find('[name="mlm_step"]').val();
		}

		if( post_data['title'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.draft_title,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_draft_course',
					'post_data'	: post_data,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						if( data.post_id != false ) {
							$form.find('[name="mlm_id"]').val( data.post_id );
						}
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SUBMIT PHYSICAL PRODUCT
	$(document).on('submit', '#mlm_submit_physical_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var post_data = {
			'title'			: $form.find('[name="mlm_title"]').val(),
			'content'		: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'thumb'			: $form.find('[name="mlm_thumb"]').val(),
			'cats'			: $form.find('[name="mlm_cat"]').val(),
			'tags'			: $form.find('[name="mlm_tag"]').val(),
			'percent'		: $form.find('[name="mlm_percent"]').val(),
			'price'			: $form.find('[name="mlm_price"]').val(),
			'sale_price'	: $form.find('[name="mlm_sale_price"]').val(),
			'button_text'	: $form.find('[name="mlm_button_text"]').val(),
			'button_link'	: $form.find('[name="mlm_button_link"]').val(),
            'button_2_text'	: $form.find('[name="mlm_button_2_text"]').val(),
            'button_2_link'	: $form.find('[name="mlm_button_2_link"]').val(),
			'post_id'		: $form.find('[name="mlm_id"]').val(),
			'weight'		: $form.find('[name="mlm_weight"]').val(),
			'quantity'		: $form.find('[name="mlm_quantity"]').val(),
			'thumb_image'	: $form.find('[name="mlm_thumb_image"]').val(),
			'image_one'		: $form.find('[name="mlm_image_one"]').val(),
			'image_two'		: $form.find('[name="mlm_image_two"]').val(),
			'stock'			: $form.find('[name="mlm_stock"]').val(),
		};

		if( $('#mlm-custom-fields-wrap').length !== 0 ) {
			$('#mlm-custom-fields-wrap').find(':input').each(function(){
				var el = $(this);
				post_data[el.attr('name')] = $.trim( el.val() );
			});
		}

		if( post_data['title'].length == 0 || post_data['content'].length == 0 || post_data['cats'].length == 0 || post_data['tags'].length == 0 || post_data['price'].length == 0 || post_data['percent'].length == 0 || post_data['weight'].length == 0 || post_data['quantity'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_submit_physical',
					'post_data'	: post_data,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SAVE PHYSICAL PRODUCT DRAFT
	$(document).on('click', '#mlm_submit_physical_form .mlm-save-draft-btn', function (e) {
		e.preventDefault();
		$form = $(this).closest('form');

		if( $form.hasClass('loading') ) {
			return false;
		}

		var post_data = {
			'title'			: $form.find('[name="mlm_title"]').val(),
			'content'		: tmce_getContent( 'mlm_content', 'mlm_content' ),
			'thumb'			: $form.find('[name="mlm_thumb"]').val(),
			'cats'			: $form.find('[name="mlm_cat"]').val(),
			'tags'			: $form.find('[name="mlm_tag"]').val(),
			'percent'		: $form.find('[name="mlm_percent"]').val(),
			'price'			: $form.find('[name="mlm_price"]').val(),
			'sale_price'	: $form.find('[name="mlm_sale_price"]').val(),
			'button_text'	: $form.find('[name="mlm_button_text"]').val(),
			'button_link'	: $form.find('[name="mlm_button_link"]').val(),
            'button_2_text'	: $form.find('[name="mlm_button_2_text"]').val(),
            'button_2_link'	: $form.find('[name="mlm_button_2_link"]').val(),
			'post_id'		: $form.find('[name="mlm_id"]').val(),
			'weight'		: $form.find('[name="mlm_weight"]').val(),
			'quantity'		: $form.find('[name="mlm_quantity"]').val(),
			'thumb_image'	: $form.find('[name="mlm_thumb_image"]').val(),
			'image_one'		: $form.find('[name="mlm_image_one"]').val(),
			'image_two'		: $form.find('[name="mlm_image_two"]').val(),
			'stock'			: $form.find('[name="mlm_stock"]').val(),
		};

		if( $('#mlm-custom-fields-wrap').length !== 0 ) {
			$('#mlm-custom-fields-wrap').find(':input').each(function(){
				var el = $(this);
				post_data[el.attr('name')] = $.trim( el.val() );
			});
		}

		if( post_data['title'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.draft_title,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_draft_physical',
					'post_data'	: post_data,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						if( data.post_id != false ) {
							$form.find('[name="mlm_id"]').val( data.post_id );
						}
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SUBMIT COUPON
	$(document).on('submit', '#mlm_submit_coupon_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var post_data = {
			'code'		: $form.find('[name="mlm_code"]').val(),
			'amount'	: $form.find('[name="mlm_amount"]').val(),
			'type'		: $form.find('[name="mlm_type"]').val(),
			'expire'	: $form.find('[name="mlm_expire"]').val(),
			'products'	: $form.find('[name="mlm_products"]').val(),
			'post_id'	: $form.find('[name="mlm_id"]').val()
		};
		if( post_data['code'].length == 0 || post_data['amount'].length == 0 || post_data['products'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_submit_coupon',
					'post_data'	: post_data,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX DELETE COUPON
	$(document).on('click', 'a[href="#mlm-delete-discount"]', function (e) {
		e.preventDefault();
		var elem = $(this), txt = elem.text();

		if( elem.hasClass('disabled') ) {
			return false;
		}

		swal({
			title		: mlm_local_object.you_sure,
			text		: mlm_local_object.delete_code_txt,
			icon		: 'warning',
			buttons		: {
				cancel	: mlm_local_object.cancel,
				catch	: {
					text	: mlm_local_object.delete_code,
					value	: 'delete',
				}
			},
			dangerMode	: true
		}).then((value) => {
			switch (value) {
				case 'delete':
					$.ajax({
						type	: 'POST',
						dataType: 'json',
						url		: mlm_local_object.ajax_url,
						data	: {
							'action'	: 'mlm_delete_coupon',
							'post_id'	: elem.data('id'),
							'security'	: elem.data('verify')
						},
						beforeSend: function (response) {
							mlm_loading_button( elem );
						},
						complete: function (response) {
							mlm_unloading_button( elem, txt );
						},
						success: function(data){
							if( data.submited == true ) {
								swal({
									title	: mlm_local_object.ok,
									text	: data.response,
									icon	: 'success',
									button	: mlm_local_object.ok,
								});
								setTimeout(function() {
									elem.closest('tr').slideUp(200).remove();
								}, 1000 );
							} else {
								swal({
									title	: mlm_local_object.error,
									text	: data.response,
									icon	: 'error',
									button	: mlm_local_object.ok,
								});
							}
						},
						error: function(){
							swal({
								title	: mlm_local_object.error,
								text	: mlm_local_object.no_response,
								icon	: 'error',
								button	: mlm_local_object.ok,
							});
						}
					});
					break;

				default:
					break;
			}
		});
	});

	// AJAX CHANGE PASSWORD
	$(document).on('submit', '#mlm_change_pass_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user	= {
			'pass'	: $form.find('[name="mlm_pass"]').val(),
			'new'	: $form.find('[name="mlm_new_pass"]').val()
		};
		if( user['pass'].length == 0 || user['new'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.change_pass,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_change_pass',
					'user_data'	: user,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.registered == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 2000 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// BOOKMARK POST
	$(document).on('click', 'a[href="#mlm-bookmark-post"]', function (e) {
		e.preventDefault();
		var elem = $(this), txt = elem.text();

		if( elem.hasClass('disabled') ) {
			return false;
		}

		if( elem.data('id').length == 0 || elem.data('verify').length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.invalid_id,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_bookmark',
					'post_id'	: elem.data('id'),
					'security'	: elem.data('verify')
				},
				beforeSend: function (response) {
					//mlm_loading_button( elem );
				},
				complete: function (response) {
					//mlm_unloading_button( elem, txt );
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							if( data.action == 'book' ) {
								elem.removeClass('icon-heart1').addClass('icon-heart');
							} else {
								elem.removeClass('icon-heart').addClass('icon-heart1');
								if( elem.attr('data-remove') == 'yes' ) {
									elem.closest('tr').remove();
								}
							}
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX INCREASE BALANCE
	$(document).on('submit', '#mlm_increase_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		if( $form.find('[name="mlm_amount"]').val().length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.amount_req,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_increase_balance',
					'amount'	: $form.find('[name="mlm_amount"]').val(),
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 2000);
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});


	// AJAX SUBMIT WITHDRAWAL.
	$(document).on('submit', '#mlm_withdraw_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user_input = {
			'amount': $form.find('[name="mlm_amount"]').val(),
			'card'	: $form.find('[name="mlm_card"]').val(),
			'sheba'	: $form.find('[name="mlm_sheba"]').val(),
			'owner'	: $form.find('[name="mlm_owner"]').val()
		}

		if( user_input['amount'].length == 0 || user_input['card'].length == 0 || user_input['sheba'].length == 0 || user_input['owner'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_withdrawal',
					'user_input': user_input,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							location.reload();
						}, 2000);
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SUBMIT PARENT.
	$(document).on('submit', '#mlm_submit_parent_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user_input = {
			'parent': $form.find('[name="mlm_parent"]').val()
		}

		if( user_input['parent'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.reagent_req,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_parent',
					'user_input': user_input,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							location.reload();
						}, 2000);
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX UPGRADE REQUEST STEP 1
	$(document).on('submit', '#mlm_upgrade_account_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user_input = {
			'gender'	: $form.find('[name="mlm_gender"]:checked').val(),
			'fname'		: $form.find('[name="mlm_fname"]').val(),
			'lname'		: $form.find('[name="mlm_lname"]').val(),
			'birth'		: $form.find('[name="mlm_birth"]').val(),
			'melli'		: $form.find('[name="mlm_melli"]').val(),
			'address'	: $form.find('[name="mlm_address"]').val(),
			'phone'		: $form.find('[name="mlm_phone"]').val(),
			'postal'	: $form.find('[name="mlm_postal"]').val(),
			'role'		: $form.find('[name="mlm_role"]').val()
		}

		if( user_input['gender'].length == 0 || user_input['fname'].length == 0 || user_input['lname'].length == 0 || user_input['birth'].length == 0 || user_input['melli'].length == 0 || user_input['address'].length == 0 || user_input['phone'].length == 0 || user_input['postal'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_upgrade_one',
					'user_input': user_input,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 2000);
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX UPGRADE REQUEST STEP 2
	$(document).on('submit', '#mlm_upload_account_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return false;
		}

		var user_input = {
			'melli'		: $form.find('[name="mlm_melli_file"]').val(),
			'shena'		: $form.find('[name="mlm_shena_file"]').val()
		}

		if( user_input['melli'].length == 0 || user_input['shena'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.upgrade_req,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_upgrade_two',
					'user_input': user_input,
					'security'	: $form.find('[name="mlm_security"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.submitted,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							window.location = data.redirect;
						}, 2000);
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// DATE PICKER
	if( typeof $.datepicker === 'object' ) {
		$('.mlm-datepicker').datepicker({
			dateFormat	: 'yy-mm-dd',
			maxDate		: 'today',
			changeMonth	: true,
			changeYear	: true,
			//yearRange	: "1300:1404"
		});
	}

	// EXPIRE PICKER
	if( typeof $.datepicker === 'object' ) {
		$('.mlm-expire').datepicker({
			dateFormat	: 'yy-mm-dd',
			minDate		: 'today',
			changeMonth	: false,
			changeYear	: false
		});
	}

	// ReCaptcha
	function get_recaptcha_token( reason ) {
		if( typeof grecaptcha === 'object' ) {
			grecaptcha.ready(function() {
				grecaptcha.execute(mlm_local_object.site_key, {action: reason}).then(
					function(token) {
						$(document).find('[name="mlm_recaptcha"]').each(function(){
							if( $(this).data('reason') == reason ) {
								$(this).val(token);
							}
						});
					},
					function (error) {
						console.log(error);
					}
				);
			});
		}
		return;
	}

	// COMMENTS FORM RECAPTCHA TOKEN
	$(document).on('change', '#commentform #comment', function (e) {
		if( typeof grecaptcha === 'object' ) {
			grecaptcha.ready(function() {
				grecaptcha.execute(mlm_local_object.site_key, {action: 'comment'}).then(
					function(token) {
						$('#commentform').find('[name="mlm_recaptcha"]').val(token);
					},
					function (error) {
						console.log(error);
					}
				);
			});
		}
	});

	// AJAX SEARCH
	function mlm_ajax_search( $form ) {
		var  $res = $form.find('.mlm-search-results'),
		s = $form.find('[name="s"]').val();
		if( s.length < 3 ) {
			$res.removeClass('open').slideUp(200);
		} else {
			$res.empty().append('<div class="d-flex align-items-center p-2"><div class="spinner-border spinner-border-sm ml-2 text-primary" role="status"></div><span class="font-12 tet-secondary bold-600">' + mlm_local_object.searching + '</span></div>').addClass('open').slideDown(200);
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_search',
					'query'		: s,
					'security'	: $form.find('[name="s"]').data('verify')
				},
				beforeSend: function (response) {
					$form.addClass('searching');
				},
				complete: function (response) {
					$form.removeClass('searching');
				},
				success: function(data){
					$res.empty().append(data.html);
					$('.slimscroll').slimScroll({
						position		: 'left',
						height			: '100%',
						alwaysVisible	: true
					});
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	}

	// TRIGGER AJAX SEARCH
	$('form.mlm-ajax-search [name="s"]').attr('autocomplete','off');
	$('form.mlm-ajax-search [name="s"]').on( 'keyup paste', function(e) {
		mlm_ajax_search( $(this).closest('form') );
	});
	/*
	$('form.mlm-ajax-search').on( 'submit', function(e) {
		e.preventDefault();
		mlm_ajax_search( $(this) );
	});
	*/

	// HIDE SEARCH RESULTS IF CLICKED OUTSIDE
	$(document).mouseup(function(e){
		var $form = $('form.mlm-ajax-search');
		if (!$form.is(e.target) && $form.has(e.target).length === 0) {
			$(document).find('.mlm-search-results').each(function(){
				if( $(this).hasClass('open') ) {
					$(this).removeClass('open').slideUp(200);
				}
			});
		}
	});

	// AJAX PURCHASE PLAN
	$(document).on('click', 'a[href="#mlm-purchase-plan"]', function (e) {
		e.preventDefault();
		var elem = $(this), txt = elem.text();

		if( elem.hasClass('disabled') ) {
			return false;
		}

		swal({
			title		: mlm_local_object.warning,
			text		: mlm_local_object.charge_for_plan,
			icon		: 'warning',
			buttons		: {
				defeat	: {
					text	: mlm_local_object.cancel_charge,
					value	: 'defeat',
				},
				catch	: {
					text	: mlm_local_object.continue_shopping,
					value	: 'buy',
				}
			},
			dangerMode	: true
		}).then((value) => {
			switch (value) {
				case 'buy':
					$.ajax({
						type	: 'POST',
						dataType: 'json',
						url		: mlm_local_object.ajax_url,
						data	: {
							'action'	: 'mlm_purchase_plan',
							'plan_id'	: elem.data('id'),
							'security'	: elem.data('verify')
						},
						beforeSend: function (response) {
							mlm_loading_button( elem );
						},
						complete: function (response) {
							mlm_unloading_button( elem, txt );
						},
						success: function(data){
							if( data.submited == true ) {
								swal({
									title	: mlm_local_object.ok,
									text	: data.response,
									icon	: 'success',
									button	: mlm_local_object.ok,
								});
								setTimeout(function() {
									if( data.redirect.length > 2 ) {
										window.location = data.redirect;
									} else {
										location.reload();
									}
								}, 2000);
							} else {
								swal({
									title	: mlm_local_object.error,
									text	: data.response,
									icon	: 'error',
									button	: mlm_local_object.ok,
								});
							}
						},
						error: function(){
							swal({
								title	: mlm_local_object.error,
								text	: mlm_local_object.no_response,
								icon	: 'error',
								button	: mlm_local_object.ok,
							});
						}
					});
					break;

				case 'defeat':
					window.location = mlm_local_object.wallet_url;
					break;

				default:
					break;
			}
		});
	});

	// COUNTDOWN
	$('.mlm-countdown').each(function(){
		var deadline = new Date( $(this).data('time') );
		$(this).countdown({until:deadline});
		$(this).closest('.counter-wrapper').removeClass('d-none');
	});

	// SCROLL TO COURSE FILES
	$(document).on('click', 'a[href="#mlm-scroll-to-course"]', function (e) {
        e.preventDefault();
        $('html, body').animate({
			scrollTop: $('#mlm-scroll-to-course').offset().top
		}, 2000);
    });

	// OPEN LESSON MODAL
	$(document).on('click', 'a[href="#mlm-lesson-modal"]', function (e) {
		e.preventDefault();
		$('form[name="mlm_new_lesson_form"]').find('[name="mlm_chapter"]').val( $(this).data('chapter') );
		$('#mlm_new_lesson_modal').modal('show');
	});

	// CLEAR VALUES ON MODAL CLOSE
	$(document).on('hidden.bs.modal', '#mlm_new_chapter_modal', function (e) {
		var $form = $('form[name="mlm_new_chapter_form"]');
		$form.find('.mlm-image-preview img').attr( 'src', $form.find('.mlm-image-preview').data('default') );
		$form.find('[name="mlm_image"]').val('');
		$form.find('[name="mlm_number"]').val('');
		$form.find('[name="mlm_title"]').val('');
		$form.find('[name="mlm_desc"]').val('');
		$form.find('[name="mlm_id"]').val('');
	});

	// CLEAR VALUES ON MODAL CLOSE
	$(document).on('hidden.bs.modal', '#mlm_new_lesson_modal', function (e) {
		var $form = $('form[name="mlm_new_lesson_form"]');
		$form.find('[name="mlm_number"]').val('');
		$form.find('[name="mlm_title"]').val('');
		$form.find('[name="mlm_desc"]').val('');
		$form.find('[name="mlm_status"]').val('');
		$form.find('[name="mlm_chapter"]').val('');
		$form.find('[name="mlm_id"]').val('');

		tmce_setContent( '', 'mlm_content', 'mlm_content' );

		// CLEAR LINKS
		$form.find('.mlm-file-template:not(:first-child)').remove();
		$form.find('.mlm-file-template:first-child').find('.file').val('');
		$form.find('.mlm-file-template:first-child').find('.name').val('');
	});

	// SET VALUES ON MODAL OPEN
	$(document).on('click', 'a[href="#mlm-edit-chapter"]', function (e) {
		e.preventDefault();
		var $parent = $(this).closest('.chapter-item'),
		$form = $('form[name="mlm_new_chapter_form"]');
		$form.find('.mlm-image-preview img').attr( 'src', $parent.find('.chapter-image').attr('src') );
		$form.find('[name="mlm_image"]').val( $parent.data('image') );
		$form.find('[name="mlm_number"]').val( $parent.data('priority') );
		$form.find('[name="mlm_title"]').val( $parent.find('.chapter-title').text() );
		$form.find('[name="mlm_desc"]').val( $parent.find('.chapter-text').text() );
		$form.find('[name="mlm_id"]').val( $parent.data('id') );
		$('#mlm_new_chapter_modal').modal('show');
	});

	// SET VALUES ON MODAL OPEN
	$(document).on('click', 'a[href="#mlm-edit-lesson"]', function (e) {
		e.preventDefault();
		var $parent = $(this).closest('.lesson-item'),
		$form = $('form[name="mlm_new_lesson_form"]');
		$form.find('[name="mlm_number"]').val( $parent.data('priority') );
		$form.find('[name="mlm_title"]').val( $parent.find('.lesson-title').text() );
		$form.find('[name="mlm_desc"]').val( $parent.find('.lesson-text').text() );
		$form.find('[name="mlm_status"]').val( $parent.data('status') );
		$form.find('[name="mlm_chapter"]').val( $parent.data('chapter') );
		$form.find('[name="mlm_id"]').val( $parent.data('id') );

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

		$('#mlm_new_lesson_modal').modal('show');
		$('form[name="mlm_new_lesson_form"]').addClass('loading');

		setTimeout(function() {
			var $content = $parent.find('.lesson-content').text();
			tmce_setContent( $content, 'mlm_content', 'mlm_content' );
			$('form[name="mlm_new_lesson_form"]').removeClass('loading');
		}, 2000 );
	});

	// SAVE CHAPTER
	$(document).on('submit', 'form[name="mlm_new_chapter_form"]', function (e) {
		e.preventDefault();
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
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
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
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							location.reload();
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// DELETE CHAPTER
	$(document).on('click', 'a[href="#mlm-delete-chapter"]', function (e) {
		e.preventDefault();
		var elem = $(this), txt = elem.text(), $parent = elem.closest('.chapter-item');

		if( elem.hasClass('disabled') ) {
			return false;
		}

		swal({
			title		: mlm_local_object.you_sure,
			text		: mlm_local_object.delete_article_txt,
			icon		: 'warning',
			buttons		: {
				cancel	: mlm_local_object.cancel,
				catch	: {
					text	: mlm_local_object.delete_article,
					value	: 'delete',
				}
			},
			dangerMode	: true
		}).then((value) => {
			switch (value) {
				case 'delete':
					if( $parent.data('id').length == 0 ) {
						swal({
							title	: mlm_local_object.error,
							text	: mlm_local_object.invalid_article,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					} else {
						$.ajax({
							type	: 'POST',
							dataType: 'json',
							url		: mlm_local_object.ajax_url,
							data	: {
								'action'	: 'mlm_delete_chapter',
								'chapter_id': $parent.data('id'),
								'security'	: elem.data('verify'),
							},
							beforeSend: function (response) {
								mlm_loading_button( elem );
							},
							complete: function (response) {
								mlm_unloading_button( elem, txt );
							},
							success: function(data){
								if( data.deleted == true ) {
									swal({
										title	: mlm_local_object.ok,
										text	: data.response,
										icon	: 'success',
										button	: mlm_local_object.ok,
									});
									setTimeout(function() {
										location.reload();
									}, 500 );
								} else {
									swal({
										title	: mlm_local_object.error,
										text	: data.response,
										icon	: 'error',
										button	: mlm_local_object.ok,
									});
								}
							},
							error: function(){
								swal({
									title	: mlm_local_object.error,
									text	: mlm_local_object.no_response,
									icon	: 'error',
									button	: mlm_local_object.ok,
								});
							}
						});
					}
					break;

				default:
					break;
			}
		});
	});

	// SAVE LESSON
	$(document).on('submit', 'form[name="mlm_new_lesson_form"]', function (e) {
		e.preventDefault();
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
			'post_id'	: $form.find('[name="mlm_post"]').val(),
		}

		$form.find('.mlm-upload-group :input').each(function(){
			var el = $(this);
			data[el.attr('name')] = $.trim( el.val() );
		});

		if( data['number'].length == 0 || data['title'].length == 0 || data['desc'].length == 0 || data['status'].length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
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
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							location.reload();
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// DELETE LESSON
	$(document).on('click', 'a[href="#mlm-delete-lesson"]', function (e) {
		e.preventDefault();
		var elem = $(this), txt = elem.text(), $parent = elem.closest('.lesson-item');

		if( elem.hasClass('disabled') ) {
			return false;
		}

		swal({
			title		: mlm_local_object.you_sure,
			text		: mlm_local_object.delete_lesson_txt,
			icon		: 'warning',
			buttons		: {
				cancel	: mlm_local_object.cancel,
				catch	: {
					text	: mlm_local_object.delete_lesson,
					value	: 'delete',
				}
			},
			dangerMode	: true
		}).then((value) => {
			switch (value) {
				case 'delete':
					if( $parent.data('id').length == 0 ) {
						swal({
							title	: mlm_local_object.error,
							text	: mlm_local_object.invalid_lesson,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					} else {
						$.ajax({
							type	: 'POST',
							dataType: 'json',
							url		: mlm_local_object.ajax_url,
							data	: {
								'action'	: 'mlm_delete_lesson',
								'lesson_id'	: $parent.data('id'),
								'security'	: elem.data('verify'),
							},
							beforeSend: function (response) {
								mlm_loading_button( elem );
							},
							complete: function (response) {
								mlm_unloading_button( elem, txt );
							},
							success: function(data){
								if( data.deleted == true ) {
									swal({
										title	: mlm_local_object.ok,
										text	: data.response,
										icon	: 'success',
										button	: mlm_local_object.ok,
									});
									setTimeout(function() {
										location.reload();
									}, 500 );
								} else {
									swal({
										title	: mlm_local_object.error,
										text	: data.response,
										icon	: 'error',
										button	: mlm_local_object.ok,
									});
								}
							},
							error: function(){
								swal({
									title	: mlm_local_object.error,
									text	: mlm_local_object.no_response,
									icon	: 'error',
									button	: mlm_local_object.ok,
								});
							}
						});
					}
					break;

				default:
					break;
			}
		});
	});

	// UPLOAD FTP FILE
	$(document).on('change', '.mlm-ftp-upload-holder input.upload-toggle', function (e) {
		var $btn = $(this), form_data = new FormData();

		if( $('.mlm-ftp-upload-wrap').hasClass('uploading') ) {
			return false;
		}

		form_data.append( 'file', $btn.prop('files')[0] );
		form_data.append( 'action', 'mlm_upload' );
		form_data.append( 'security', $btn.attr('data-verify') );

		$.ajax({
			xhr			: function() {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener('progress', function(evt) {
					if( evt.lengthComputable ) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						$('.mlm-ftp-upload-wrap').find('.progress-bar').css( 'width', percentComplete + '%' );
					}
				}, false);
				return xhr;
			},
			type		: 'POST',
			dataType	: 'json',
			enctype		: 'multipart/form-data',
			processData	: false,
            contentType	: false,
            cache		: false,
			url			: mlm_local_object.ajax_url,
			data		: form_data,
			beforeSend	: function (response) {
				$('.mlm-ftp-upload-wrap').addClass('uploading');
			},
			complete	: function (response) {
				$('.mlm-ftp-upload-wrap').removeClass('uploading');
			},
			success		: function(data) {
				if( data.uploaded == true ) {
					swal({
						title	: mlm_local_object.ok,
						text	: data.response,
						icon	: 'success',
						button	: mlm_local_object.ok,
					});
					$('.mlm-ftp-upload-wrap').find('.progress-bar').css( 'width', '0%' );
					$btn.val('');
					$btn.closest('.mlm-file-template').find('.name').val( mlm_local_object.dl_file );
					$btn.closest('.mlm-file-template').find('.file').val( data.url );
				} else {
					swal({
						title	: mlm_local_object.error,
						text	: data.response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			},
			error: function(){
				swal({
					title	: mlm_local_object.error,
					text	: mlm_local_object.no_response,
					icon	: 'error',
					button	: mlm_local_object.ok,
				});
			}
		});
	});

	// TOGGLE RESPONSIVE MENU
	$(document).on('click', '#app-toggle-search-menu', function (e) {
        e.preventDefault();
		$('.app-search-page-wrapper').toggleClass('open');
    });

	// MOBILE MENU CLOSE ON BODY CLICK
	$(document).on('click', function (e) {
		if( $(e.target).closest('.app-search-page-wrapper').length === 0 && $(e.target).attr('id') != 'app-toggle-search-menu'  ) {
			$('.app-search-page-wrapper').removeClass('open');
		}
	});

	// SEARCH SORT CHANGE
	$(document).on('change', '.mlm-search-page-header input[type="radio"]', function (e) {
		$('form[name="mlm-advanced-search-form"]').submit();
    });

	// SEARCH FILTERS CHANGE
	$(document).on('change', '.mlm-filters-widget input[type="checkbox"]', function (e) {
		$('form[name="mlm-advanced-search-form"]').submit();
    });

	// SEARCH FILTERS CHANGE
	$(document).on('change', '.mlm-filters-widget select', function (e) {
		$('form[name="mlm-advanced-search-form"]').submit();
    });

	/*
	// SORT DROPDOWN
	$(document).on('click', '.app-sort-dropdown .dropdown-item', function (e) {
        e.preventDefault();
		$('.app-sort-dropdown .dropdown-item').removeClass('active');
		$(this).addClass('active');
        $('.app-sort-dropdown .btn').text( $(this).text() );
        $('.app-sort-dropdown input[name="kham_sort"]').val( $(this).data('value') );
		$('form[name="mlm-advanced-search-form"]').submit();
    });

	// PRICE FILTER BUTTON
	$(document).on('click', '.app-price-filter-widget .btn', function (e) {
        e.preventDefault();
		$('.app-price-filter-widget .btn').removeClass('active');
		$(this).addClass('active');
        $('.app-price-filter-widget input[name="kham_price"]').val( $(this).data('value') );
		$('form[name="mlm-advanced-search-form"]').submit();
    });

	*/

	// TOGGLE CATEGORIES
	$(document).on('click', '.mlm-filters-widget .category-item .toggle', function (e) {
		e.preventDefault();
		var elem = $(this).closest('.category-item');

		if( elem.hasClass('open') ) {
			$(this).text('+');
			elem.removeClass('open');
		} else {
			$(this).text('-');
			elem.addClass('open');
		}
    });

	// AJAX RATE COMMENT
	$(document).on('click', '.mlm-like-comment', function (e) {
		e.preventDefault();
		toastr.clear();
		var $btn = $(this), $parent = $btn.closest('.mlm-interaction');

		if( $btn.hasClass('loading') ) {
			return false;
		}

		$.ajax({
			type	: 'POST',
			dataType: 'json',
			url		: mlm_local_object.ajax_url,
			data	: {
				'action'	: 'mlm_like_comment',
				'form_data'	: {
					'comment_id': $btn.data('id'),
					'reaction'	: $btn.data('type')
				},
				'security'	: $btn.data('verify')
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
					$parent.find('[data-type="like"]').text(data.likes);
					$parent.find('[data-type="dislike"]').text(data.dislikes);
				} else {
					toastr.error( data.response );
				}
			},
			error: function(){
				toastr.clear();
				toastr.error( mlm_local_object.no_response );
			}
		});
	});

	// AJAX SEND MOBILE VERIFY CODE
	$(document).on('click', '#mlm_verify_mobile_form .send-code-btn', function (e) {
		e.preventDefault();
		var elem = $(this), $form = $(this).closest('form');

		if( elem.hasClass('disabled') ) {
			return false;
		}

		var mobile = $form.find('[name="mobile"]').val();

		if( mobile.length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.mobile_required,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_send_mobile_code',
					'mobile'	: mobile,
					'security'	: $form.find('input[name="nonce"]').val()
				},
				beforeSend: function (response) {
					elem.attr('disabled', 'disabled').addClass('disabled');
				},
				complete: function (response) {
					elem.removeAttr('disabled', 'disabled').removeClass('disabled');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// AJAX SEND EMAIL VERIFY CODE
	$(document).on('click', '#mlm_verify_email_form .send-code-btn', function (e) {
		e.preventDefault();
		var elem = $(this), $form = $(this).closest('form');

		if( elem.hasClass('disabled') ) {
			return false;
		}

		var email = $form.find('[name="email"]').val();

		if( email.length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.email_required,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_send_email_code',
					'email'		: email,
					'security'	: $form.find('input[name="nonce"]').val()
				},
				beforeSend: function (response) {
					elem.attr('disabled', 'disabled').addClass('disabled');
				},
				complete: function (response) {
					elem.removeAttr('disabled', 'disabled').removeClass('disabled');
				},
				success: function(data){
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.ok,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// VERIFY MOBILE
	$(document).on('submit', '#mlm_verify_mobile_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return;
		}

		var code = $form.find('[name="code"]').val();
		var mobile = $form.find('[name="mobile"]').val();

		if( code.length == 0 || mobile.length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_verify_mobile',
					'mobile'	: mobile,
					'code'		: code,
					'security'	: $form.find('input[name="nonce"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data) {
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.saved,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							location.reload();
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	// VERIFY EMAIL
	$(document).on('submit', '#mlm_verify_email_form', function (e) {
		e.preventDefault();
		$form = $(this);

		if( $form.hasClass('loading') ) {
			return;
		}

		var code = $form.find('[name="code"]').val();
		var email = $form.find('[name="email"]').val();

		if( code.length == 0 || email.length == 0 ) {
			swal({
				title	: mlm_local_object.error,
				text	: mlm_local_object.marked_fields,
				icon	: 'error',
				button	: mlm_local_object.ok,
			});
		} else {
			$.ajax({
				type	: 'POST',
				dataType: 'json',
				url		: mlm_local_object.ajax_url,
				data	: {
					'action'	: 'mlm_verify_email',
					'email'		: email,
					'code'		: code,
					'security'	: $form.find('input[name="nonce"]').val()
				},
				beforeSend: function (response) {
					$form.addClass('loading');
				},
				complete: function (response) {
					$form.removeClass('loading');
				},
				success: function(data) {
					if( data.submited == true ) {
						swal({
							title	: mlm_local_object.saved,
							text	: data.response,
							icon	: 'success',
							button	: mlm_local_object.ok,
						});
						setTimeout(function() {
							location.reload();
						}, 500 );
					} else {
						swal({
							title	: mlm_local_object.error,
							text	: data.response,
							icon	: 'error',
							button	: mlm_local_object.ok,
						});
					}
				},
				error: function(){
					swal({
						title	: mlm_local_object.error,
						text	: mlm_local_object.no_response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			});
		}
	});

	//open download box in single proruct
	$(document).on('click', '.mlm-show-history-download', function (e) {
		if ($(this).find(".mlm-btn-dl").css("display") == 'none') {
			$(this).find(".mlm-btn-dl").css("display", "block");
		} else {
			$(this).find(".mlm-btn-dl").css("display", "none");
		}
		// $(this).find(".mlm-btn-dl").slideToggle(200);
	});

	// UPLOAD FTP FILE
	$(document).on('change', '.mlm-attach-field-wrap input.upload-toggle', function (e) {
		var $btn = $(this), form_data = new FormData();

		if( $('.mlm-attach-upload-progress').hasClass('uploading') ) {
			return false;
		}

		form_data.append( 'file', $btn.prop('files')[0] );
		form_data.append( 'action', 'mlm_attach' );
		form_data.append( 'security', $btn.attr('data-verify') );

		$.ajax({
			xhr			: function() {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener('progress', function(evt) {
					if( evt.lengthComputable ) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						$('.mlm-attach-upload-progress').find('.progress-bar').css( 'width', percentComplete + '%' );
					}
				}, false);
				return xhr;
			},
			type		: 'POST',
			dataType	: 'json',
			enctype		: 'multipart/form-data',
			processData	: false,
            contentType	: false,
            cache		: false,
			url			: mlm_local_object.ajax_url,
			data		: form_data,
			beforeSend	: function (response) {
				$('.mlm-attach-upload-progress').addClass('uploading');
			},
			complete	: function (response) {
				$('.mlm-attach-upload-progress').removeClass('uploading');
			},
			success		: function(data) {
				if( data.uploaded == true ) {
					$('.mlm-attach-upload-progress').find('.progress-bar').css( 'width', '0%' );
					$btn.val('');

					var html_img = '<a href="#mlm-delete-attach-image"><img src="' + data.url + '" alt="image" /><span class="fas">X</span><input type="hidden" name="mlm_attaches[]" value="' + data.url + '" /></a>';
					$( ".ticket-attaches-placeholder" ).append( html_img );
				} else {
					swal({
						title	: mlm_local_object.error,
						text	: data.response,
						icon	: 'error',
						button	: mlm_local_object.ok,
					});
				}
			},
			error: function(){
				swal({
					title	: mlm_local_object.error,
					text	: mlm_local_object.no_response,
					icon	: 'error',
					button	: mlm_local_object.ok,
				});
			}
		});
	});

	// DELETE ATTACH IMAGE
	$(document).on('click', 'a[href="#mlm-delete-attach-image"]', function (e) {
		e.preventDefault();
		$(this).remove();
	});
})