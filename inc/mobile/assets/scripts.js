(function ($) {
    'use strict';

    var dimax = dimax || {};
    dimax.init = function () {
        dimax.$body = $(document.body),
            dimax.$window = $(window),
            dimax.$header = $('#site-header');

        this.navigationBarDropdown();
        this.navigationBarClick();
        this.navigationBarOrderClick();

        this.updateCartTotal();

    };

    /**
     * Navigation Bar Dropdown
     */
    dimax.navigationBarDropdown = function () {
        if ( ! dimax.$body.hasClass( 'single-product' ) ) {
            return;
        }

        if ( ! $( '.rz-navigation-bar' ).hasClass( 'rz-navigation-bar__type-variable' ) ) {
            return;
        }

        var $wrapper = $('.rz-navigation-bar__type-variable');

        $wrapper.on("click", ".add_to_cart_button", function (e) {
            e.preventDefault();

            $wrapper.find(".add_to_cart_button").removeClass("active");
            $wrapper.find(".rz-navigation-bar__type-variable--content").slideUp();
            $(this).addClass("active");
            $(this).closest(".rz-navigation-bar__type-variable").find(".rz-navigation-bar__type-variable--content").stop().slideDown(300);
            dimax.$body.css({'overflow' : 'hidden'});
            dimax.$body.find('.rz-navigation-bar__off-layer').addClass("active");
        });

        dimax.$body.on("click", ".rz-navigation-bar__off-layer, .rz-navigation-bar__btn-close", function (e) {
            e.preventDefault();

            $wrapper.find(".rz-navigation-bar__type-variable--header .add_to_cart_button").removeClass("active");
            $wrapper.find(".rz-navigation-bar__type-variable--content").stop().slideUp(300);
            dimax.$body.find('.rz-navigation-bar__off-layer').removeClass("active");
            dimax.$body.removeAttr( 'style' );
        });
    };


    /**
     * Navigation Bar Dropdown
     */
    dimax.navigationBarClick = function () {
        var $btn = $( '.rz-navigation-bar__sticky-atc .rz-loop_button' ),
            $offset = 0,
            $heightSticky = dimax.$body.find( '.site-header' ).height();

        if ( $btn.hasClass( 'ajax_add_to_cart' ) ) {
            return;
        }

        if ( dimax.$body.hasClass( 'header-sticky' ) ) {
            $offset = $heightSticky;
        }

        $btn.on('click', function (event) {
            event.preventDefault();

            $('html,body').stop().animate({
                    scrollTop: $("form.cart").offset().top - $offset
                },
                'slow');
        });
    };

    /**
     * Navigation Bar Order Click
     */
    dimax.navigationBarOrderClick = function () {
        if ( ! dimax.$body.hasClass( 'woocommerce-checkout' ) ) {
            return;
        }

        dimax.$body.on( 'click', '.rz-navigation-bar__btn-place-order', function(e) {
            e.preventDefault();
            $('.woocommerce-checkout .woocommerce-checkout-payment #place_order').trigger('click');
        })
    };

    /**
     * Update cart total
     */
    dimax.updateCartTotal = function () {
        if ( ! dimax.$body.hasClass( 'woocommerce-cart' ) ) {
            return;
        }

        dimax.$body.on( 'updated_cart_totals', function() {
            var $order_total = $( '.cart_totals .order-total > td' ).html();

            if($( '.cart_totals .order-total').length) {}

            $('#rz-navigation-bar .order-total').html($order_total);
        })

        dimax.$body.on( 'wc_cart_emptied', function() {
            $('#rz-navigation-bar').hide();
        })
    };

    /**
     * Document ready
     */
    $(function () {
        dimax.init();
    });

})(jQuery);
