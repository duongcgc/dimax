(function ($) {
    'use strict';

    var dimax = dimax || {};
    dimax.init = function () {
        dimax.$body = $(document.body),
            dimax.$window = $(window),
            dimax.$header = $('#site-header');

        // Catalog
        this.catalogBanner();
        this.catalogToolBarV1();
        this.catalogToolBarV2();
        this.catalogToolBarV3();
        this.productsTools();
        this.productCategoriesCarousel();
        this.catalogCollapseWidget();
        this.productsLoading();
        this.productsInfinite();
        this.productsFilterActivated();

        this.scrollFilterSidebar();
        this.changeCatalogElementsFiltered();

        this.stickySidebar();
    };

    dimax.catalogBanner = function () {

        var $selector = $('#catalog-header-banners');

        if ($selector.length < 1) {
            return;
        }

        $selector.append('<div class="swiper-pagination"></div>');

        var options = {
            loop: false,
            watchOverflow: true,
            pagination: {
                el: '#catalog-header-banners .swiper-pagination',
                type: 'bullets',
                clickable: true
            },
        };

        new Swiper('#catalog-header-banners', options);
    };

    dimax.productCategoriesCarousel = function () {
        var $selector = $('#rz-catalog-top-categories'),
            $wrapper = $selector.find('.swiper-container'),
            columns = $selector.data('columns');

        if ($selector.length < 1) {
            return;
        }

        $wrapper.after('<div class="swiper-pagination"></div>');
        $wrapper.after('<span class="dimax-svg-icon dimax-top-cats-button-prev rz-swiper-button"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></span>');
        $wrapper.after('<span class="dimax-svg-icon dimax-top-cats-button-next rz-swiper-button"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></span>');


        var options = {
            loop: false,
            spaceBetween: 30,
            watchOverflow: true,
            on: {
                init: function () {
                    this.$el.css('opacity', 1);
                }
            },
            navigation: {
                nextEl: '.dimax-top-cats-button-next',
                prevEl: '.dimax-top-cats-button-prev',
            },
            pagination: {
                el: '#rz-catalog-top-categories .swiper-pagination',
                type: 'bullets',
                clickable: true
            },
            breakpoints: {
                300: {
                    slidesPerView: 2,
                    slidesPerGroup: 2,
                    spaceBetween: 20,
                },
                500: {
                    slidesPerView: 3,
                    slidesPerGroup: 3,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: columns > 4 ? 4 : columns,
                    slidesPerGroup: columns > 4 ? 4 : columns,
                },
                1200: {
                    slidesPerView: columns > 5 ? 5 : columns,
                    slidesPerGroup: columns > 5 ? 5 : columns
                },
                1366: {
                    slidesPerView: columns > 6 ? 6 : columns,
                    slidesPerGroup: columns > 6 ? 6 : columns
                }
            }
        };

        new Swiper('#rz-catalog-top-categories .swiper-container', options);

        if ($selector.find('.rz-swiper-button').hasClass('swiper-button-lock')) {
            $selector.addClass('rz-not-navigation');
        }

    };

    /**
     * Handle products tools events.
     */
    dimax.productsTools = function () {
        // Toggle products filter.
        $(document.body).on('click', '.catalog-toolbar .toggle-filters[data-toggle="dropdown"]', function (event) {
            event.preventDefault();

            var toolbar = $(this).closest('.catalog-toolbar');

            if (toolbar.hasClass('on-mobile')) {
                return;
            }
            ;

            $($(this).attr('href')).slideToggle(300, function () {
                $(this).toggleClass('open');
            });
        });

    };

    dimax.catalogToolBarV1 = function () {
        var $selector = $('.dimax-catalog-page').find('#primary-sidebar');

        if ($selector.length < 1) {
            return;
        }


        dimax.$window.on('resize', function () {
            if (dimax.$window.width() > 991) {
                if ($selector.hasClass('rz-modal')) {
                    $selector.removeClass('rz-modal').removeAttr('style');
                    dimax.$body.removeClass('modal-opened');
                    dimax.$body.removeClass('primary-sidebar-opened');
                    $selector.find('.modal-content').removeClass('dimax-scrollbar')
                }
            } else {
                $selector.addClass('rz-modal');
                $selector.find('.modal-content').addClass('dimax-scrollbar')
            }

        }).trigger('resize');

    };

    dimax.catalogCollapseWidget = function () {
        if (typeof dimaxCatalogData.catalog_widget_collapse_content === 'undefined') {
            return;
        }

        if (dimaxCatalogData.catalog_widget_collapse_content !== '1') {
            return;
        }

        dimax.collapseWidget($('#primary-sidebar, .elementor-widget-wp-widget-dimax-products-filter'));

    };


    dimax.catalogToolBarV3 = function () {
        if (typeof dimaxCatalogData.catalog_filters_sidebar_collapse_content !== 'undefined' && dimaxCatalogData.catalog_filters_sidebar_collapse_content === '1') {
            dimax.collapseWidget($('#catalog-filters-modal'));
        }

        this.showFilterMobile();

    };

    dimax.collapseWidget = function ($this) {
        $this.on('click', '.widget-title', function (e) {
            e.preventDefault();

            var $el = $(this),
                $wrapper = $el.closest('.widget');

            if ($el.closest('.widget').hasClass('.products-filter-widget')) {
                return;
            }

            $wrapper.find('.widget-content').slideToggle();
            $el.toggleClass('rz-active');
        });

        $this.on('click', '.products-filter__filter-name', function (e) {
            e.preventDefault();
            $(this).next().slideToggle();
            $(this).closest('.products-filter__filter').toggleClass('rz-active');
        });
    };

    dimax.catalogToolBarV2 = function () {
        var $selector = $('#catalog-filters');

        if ($selector.length < 1) {
            return;
        }

        if (!$selector.hasClass('catalog-toolbar-filters__v2')) {
            return;
        }

        var display = 'fade';

        dimax.$window.on('resize', function () {
            if (dimax.$window.width() < 992) {
                display = 'slide';
            } else {
                display = 'fade';
            }
        }).trigger('resize');


        // Toggle Widgets
        $selector.find('.widget').on('click', '.widget-title', function (e) {
            e.preventDefault();

            var $this = $(this),
                filter = $this.closest('.widget'),
                siblings = filter.siblings().find('.widget-title');

            if (display === 'fade') {
                $this.next().fadeToggle('fast');
                siblings.next().hide();
            } else {
                siblings.next().slideUp(200);
                setTimeout(function () {
                    $this.next().slideToggle(200);
                }, 200);
            }


            $this.toggleClass('rz-active');
            siblings.removeClass('rz-active');
        });

        // Toggle Widgets
        $('.catalog-toolbar').on('click', '.products-filter__filter-name', function (e) {
            e.preventDefault();

            var $this = $(this),
                filter = $this.closest('.products-filter__filter'),
                siblings = filter.siblings().find('.products-filter__filter-name');

            if (display === 'fade') {
                $this.next().fadeToggle('fast');
                siblings.next().hide();
            } else {
                siblings.next().slideUp(200);
                setTimeout(function () {
                    $this.next().slideToggle(200);
                }, 200);
            }


            $this.toggleClass('rz-active');
            siblings.removeClass('rz-active');
        });

        dimax.$body.on('dimax_products_filter_before_send_request', function () {
            $selector.find('.products-filter-widget .products-filter__filter-control').hide();
            $selector.find('.products-filter-widget .products-filter__filter-name').removeClass('rz-active');
        });

        dimax.$body.on('click', function (evt) {
            if (evt.target.id == "catalog-filters") {
                return;
            }

            if ($(evt.target).closest('.catalog-toolbar-filters__v2 .widget').length > 0) {
                return;
            }

            $selector.find('.widget .widget-title').next().hide();
            $selector.find('.widget .widget-title').removeClass('rz-active');

            $selector.find('.products-filter-widget .products-filter__filter-control').hide();
            $selector.find('.products-filter-widget .products-filter__filter-name').removeClass('rz-active');

        });

    };

    dimax.showFilterMobile = function () {
        var $shopToolbar = $('.catalog-toolbar'),
            $shopContentFilter = $('.products-filter-dropdown');

        if (!$shopToolbar.hasClass('layout-v3')) {
            return;
        }

        dimax.$window.on('resize', function () {
            if (dimax.$window.width() < 992) {
                $shopToolbar.addClass('on-mobile');
                $shopContentFilter.addClass('on-mobile');

            } else {
                $shopToolbar.removeClass('on-mobile');
                $shopContentFilter.removeClass('on-mobile');
                $shopToolbar.find('.catalog-toolbar-tabs__content').removeAttr('style');
            }
        }).trigger('resize');

        dimax.$body.on('click', '.catalog-toolbar-tabs__title', function (e) {
            e.preventDefault();

            var el = $(this),
                $toolbar = el.closest('.catalog-toolbar');

            if ($toolbar.hasClass('on-mobile')) {

                if ($toolbar.siblings('.products-filter-dropdown').hasClass('open')) {
                    $toolbar.siblings('.products-filter-dropdown').slideUp(200).removeClass('open');
                    $toolbar.find('.toggle-filters').removeClass('active');
                }

                setTimeout(function () {
                    el.siblings('.catalog-toolbar-tabs__content').slideToggle(200);
                    el.siblings('.catalog-toolbar-tabs__content').toggleClass('open');
                    el.toggleClass('active');
                }, 200);

            }
        });

        dimax.$body.on('click', '[data-target="catalog-filters-dropdown"]', function (e) {
            e.preventDefault();

            var el = $(this);
            toolbar = el.closest('.catalog-toolbar');

            el.toggleClass('active');

            if (toolbar.hasClass('on-mobile')) {

                if (!$shopContentFilter.hasClass('open')) {
                    toolbar.find('.catalog-toolbar-tabs__content').slideUp(200);
                    toolbar.find('.catalog-toolbar-tabs__content').removeClass('open');
                    toolbar.find('.catalog-toolbar-tabs__title').removeClass('active');
                }

                setTimeout(function () {
                    $shopContentFilter.slideToggle(200).toggleClass('open');
                    el.children().toggleClass('active');
                }, 200);
            }
        });

        $shopContentFilter.on('click', '.widget-title', function (e) {
            if ($(this).closest('.products-filter-dropdown').hasClass('on-mobile')) {
                e.preventDefault();

                var el = $(this),
                    widget = el.closest('.widget'),
                    siblings = widget.siblings();

                if (siblings.hasClass('products-filter-widget')) {
                    siblings.find('.filter-header').next().slideUp(200);
                    siblings.find('.filter').removeClass('active');
                }

                siblings.not('.products-filter-widget').find('.widget-title').next().slideUp(200);
                siblings.not('.products-filter-widget').removeClass('active');

                el.next().slideToggle(200);
                widget.toggleClass('active');
            }
        });

        $shopContentFilter.on('click', '.products-filter__filter-name', function (e) {
            if ($(this).closest('.products-filter-dropdown').hasClass('on-mobile')) {
                e.preventDefault();

                var el = $(this),
                    widget = el.closest('.products-filter-widget'),
                    filter = el.closest('.products-filter__filter'),
                    filterSiblings = el.closest('.products-filter__filter').siblings(),
                    siblings = widget.siblings();

                filterSiblings.find('.products-filter__filter-control').slideUp(200);
                siblings.not('.products-filter-widget').removeClass('active');
                filterSiblings.removeClass('active');

                el.next().slideToggle(200);
                filter.toggleClass('active');
            }
        });
    };

    dimax.productsLoading = function () {
        dimax.$body.on('click', '#dimax-catalog-previous-ajax > a', function (e) {
            e.preventDefault();

            var $this = $(this);
            if ($this.data('requestRunning')) {
                return;
            }

            $this.data('requestRunning', true);

            var $wrapper = $this.closest('.rz-shop-content'),
                $products = $wrapper.find('ul.products'),
                $pagination = $wrapper.find('.next-posts-navigation'),
                numberPosts = $products.children('li.product').length,
                href = $this.attr('href');

            $pagination.addClass('loading');

            $.get(
                href,
                function (response) {
                    var content = $(response).find('#rz-shop-content').find('ul.products').children('li.product');

                    // Add animation class
                    for (var index = 0; index < content.length; index++) {
                        $(content[index]).css('animation-delay', index * 100 + 'ms');
                    }
                    content.addClass('dimaxFadeInUp');
                    if ($(response).find('.next-posts-navigation').length > 0) {
                        $pagination.html($(response).find('.next-posts-navigation').html());
                    } else {
                        $pagination.fadeOut();
                    }
                    $products.append(content);
                    $pagination.find('.nav-previous-ajax > a').data('requestRunning', false);

                    numberPosts += content.length;
                    $wrapper.find('.dimax-posts__found .current-post').html(' ' + numberPosts);
                    dimax.postsFound();
                    $pagination.removeClass('loading');
                    $(document.body).trigger('dimax_products_loaded', [content, true]);
                }
            );
        });
    };

    dimax.postsFound = function () {
        var $found = $('.dimax-posts__found-inner'),
            $foundEls = $found.find('.count-bar'),
            $current = $found.find('.current-post').html(),
            $total = $found.find('.found-post').html(),
            pecent = ($current / $total) * 100;

        $foundEls.css('width', pecent + '%');
    };

    dimax.productsInfinite = function () {
        if (!$('.woocommerce-navigation').hasClass('ajax-infinite')) {
            return;
        }
        dimax.$window.on('scroll', function () {
            if (dimax.$body.find('#dimax-catalog-previous-ajax').is(':in-viewport')) {
                dimax.$body.find('#dimax-catalog-previous-ajax > a').trigger('click');
            }
        }).trigger('scroll');
    };

    dimax.productsFilterActivated = function () {
        var $primaryFilter = $('#rz-products-filter__activated'),
            $widgetFilter = $('.products-filter-widget').find('.products-filter__activated');

        $primaryFilter.html($widgetFilter.html());

        dimax.$body.on('dimax_products_filter_widget_updated', function (e, form) {
            var $widgetNewFilter = $(form).closest('.products-filter-widget').find('.products-filter__activated');
            $primaryFilter.html($widgetNewFilter.html());
        });

        $primaryFilter.on('click', '.remove-filtered', function (e) {
            var currentIndex = $(this).index();

            if (currentIndex !== 'undefined') {
                $(this).remove();
                $widgetFilter.find('.remove-filtered:eq(' + currentIndex + ')').trigger('click');
            }

            return false;

        });


    };

    dimax.scrollFilterSidebar = function () {
        dimax.$body.on('dimax_products_filter_before_send_request', function () {
            var $offset = 0,
                $heightSticky = dimax.$body.find('.site-header').height();

            if( ! $("#rz-shop-content").length ) {
                return;
            }

            if (dimax.$body.hasClass('header-sticky')) {
                $offset = 200;
            } else {
                $offset = $heightSticky + 200;
            }

            if( $('.products-filter__activated').length ) {
                $offset += $('.products-filter__activated').height();
            }

            $('html,body').stop().animate({
                    scrollTop: $("#rz-shop-content").offset().top - $offset
                },
                'slow');

            $('#rz-shop-content').find('.dimax-posts__found').hide();
        });
    };

    dimax.changeCatalogElementsFiltered = function () {
        dimax.$body.on('dimax_products_filter_request_success', function (e, response) {
            var $html = $(response),
                $page_header = dimax.$body.find('#page-header'),
                $catalog_banner = dimax.$body.find('#catalog-header-banners'),
                $top_category = dimax.$body.find('#rz-catalog-top-categories'),
                $toolbar = dimax.$body.find('.catalog-toolbar'),
                $toolbar_title = dimax.$body.find('.catalog-toolbar .catalog-toolbar-tabs__title'),
                $posts_found = dimax.$body.find('.rz-shop-content .dimax-posts__found'),
                $widget_categories = dimax.$body.find('.widget_product_categories'),
                $navigation = dimax.$body.find('.woocommerce-navigation'),
                $sidebar = dimax.$body.find('.catalog-sidebar');

            if( ! $page_header.hasClass('catalog-page-header--template') ) {
                $page_header.replaceWith($html.find('#page-header'));
            }

            if ($html.find('#catalog-header-banners').length) {
                $catalog_banner.replaceWith($html.find('#catalog-header-banners'));
                dimax.catalogBanner();
            }

            if ($html.find('#rz-catalog-top-categories').length) {
                $top_category.replaceWith($html.find('#rz-catalog-top-categories'));
                dimax.productCategoriesCarousel();
            }

            if ($html.find('.catalog-toolbar').length && ! $toolbar.hasClass('layout-v2')) {
                $toolbar.replaceWith($html.find('.catalog-toolbar'));
            }

            $toolbar_title.removeClass('active');

            if ($html.find('.rz-shop-content .dimax-posts__found').length) {
                $posts_found.replaceWith($html.find('.rz-shop-content .dimax-posts__found'));
                dimax.postsFound();
            }

            if ($html.find('.widget_product_categories').length) {
                $widget_categories.replaceWith($html.find('.widget_product_categories'));
            }

            if ($navigation.length) {
                $navigation.replaceWith($html.find('.woocommerce-navigation'));
            } else {
                $('#rz-shop-content').append($html.find('.woocommerce-navigation'));
            }

            if($sidebar.length && $sidebar.hasClass('has-collapse-hide')) {
                if(window.location.href.indexOf('?') == -1) {
                    return;
                };

                var hash,
                    hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for(var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');

                    if( hash ) {
                        var $filter = $sidebar.find('.products-filter-widget').find('[name=' + hash[0] + ']');
                        $filter.closest('.products-filter__filter').addClass('rz-active');
                    }
                }
            }

            dimax.stickySidebar();

        });

        dimax.$body.on('dimax_products_filter_before_send_request', function (e, response) {
            if( dimax.$body.hasClass('dimax-filter-sidebar-off') ) {
                $('.catalog-filters-modal-opened #catalog-filters-modal').removeClass('open').fadeOut();
                $('.primary-sidebar-opened #primary-sidebar').removeClass('open').fadeOut();
                if(dimax.$body.hasClass('catalog-filters-modal-opened')) {
                    dimax.$body.removeClass('catalog-filters-modal-opened modal-opened').removeAttr('style');
                }

                if(dimax.$body.hasClass('primary-sidebar-opened')) {
                    dimax.$body.removeClass('primary-sidebar-opened modal-opened').removeAttr('style');
                }
            }
        } );

    };

    dimax.stickySidebar = function() {
        if( ! $.fn.stick_in_parent ) {
            return;
        }

        var offset_top = 30;

        if( dimax.$body.hasClass('admin-bar') ) {
            offset_top += 32;
        }

        if( dimax.$body.hasClass('header-sticky') ) {
            if( dimax.$header.hasClass('header-bottom-no-sticky') && dimax.$header.find('.header-main').length ) {
                offset_top +=dimax.$header.find('.header-main').height();

            } else if(dimax.$header.hasClass('header-main-no-sticky') && dimax.$header.find('.header-bottom').length ) {
                offset_top +=dimax.$header.find('.header-bottom').height();
            } else {
                offset_top += dimax.$header.height();
            }
        }

        dimax.$window.on('resize', function () {
            if (dimax.$window.width() < 992) {
                $( '#primary-sidebar.dimax-sticky-sidebar' ).trigger("sticky_kit:detach");
            } else {
                $( '#primary-sidebar.dimax-sticky-sidebar' ).stick_in_parent({
                    offset_top: offset_top
                });
            }
        }).trigger('resize');
    }

    /**
     * Document ready
     */
    $(function () {
        dimax.init();
    });

})(jQuery);