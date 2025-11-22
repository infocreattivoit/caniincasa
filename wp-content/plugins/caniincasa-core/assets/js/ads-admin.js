/**
 * Admin JS - Banner Pubblicitari
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        /**
         * Tab Navigation
         */
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();

            var target = $(this).attr('href');

            // Update active tab
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');

            // Show target content
            $('.tab-content').removeClass('active').hide();
            $(target).addClass('active').fadeIn(200);
        });

        /**
         * Toggle Position Accordion
         */
        $('.ad-position-title').on('click', function() {
            var $content = $(this).next('.ad-position-content');
            var $button = $(this).find('.toggle-position');

            if ($content.is(':visible')) {
                $content.slideUp(200);
                $button.text('Espandi');
            } else {
                $content.slideDown(200);
                $button.text('Chiudi');
            }
        });

        /**
         * Copy Shortcode
         */
        $('.copy-shortcode').on('click', function() {
            var shortcode = $(this).data('shortcode');
            var $button = $(this);

            // Create temp input
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val(shortcode).select();
            document.execCommand('copy');
            $temp.remove();

            // Feedback
            $button.addClass('copied').text('Copiato!');

            setTimeout(function() {
                $button.removeClass('copied').text('Copia');
            }, 2000);
        });

        /**
         * Initialize CodeMirror for textareas
         */
        if (typeof wp !== 'undefined' && wp.codeEditor) {
            $('.code-editor').each(function() {
                var $textarea = $(this);

                // Skip if already initialized
                if ($textarea.next('.CodeMirror').length) {
                    return;
                }

                wp.codeEditor.initialize($textarea, {
                    codemirror: {
                        mode: 'htmlmixed',
                        lineNumbers: true,
                        lineWrapping: true,
                        indentUnit: 2,
                        tabSize: 2,
                        extraKeys: {
                            'Ctrl-Space': 'autocomplete'
                        }
                    }
                });
            });
        }

        /**
         * Expand All / Collapse All
         */
        $('<div class="bulk-actions" style="margin: 20px 0;">' +
          '<button type="button" class="button expand-all">Espandi Tutto</button> ' +
          '<button type="button" class="button collapse-all">Chiudi Tutto</button>' +
          '</div>').insertAfter('.ads-intro');

        $('.expand-all').on('click', function() {
            $('.ad-position-content').slideDown(200);
            $('.toggle-position').text('Chiudi');
        });

        $('.collapse-all').on('click', function() {
            $('.ad-position-content').slideUp(200);
            $('.toggle-position').text('Espandi');
        });

        /**
         * Unsaved changes warning
         */
        var formChanged = false;

        $('.ads-form').on('change', 'input, textarea', function() {
            formChanged = true;
        });

        $('.ads-form').on('submit', function() {
            formChanged = false;
        });

        $(window).on('beforeunload', function() {
            if (formChanged) {
                return 'Hai modifiche non salvate. Sei sicuro di voler uscire?';
            }
        });

        /**
         * Search filter
         */
        $('<div class="search-ads" style="margin: 20px 0;">' +
          '<input type="text" class="search-ads-input" placeholder="Cerca posizione..." style="width: 300px; padding: 8px;">' +
          '</div>').insertAfter('.bulk-actions');

        $('.search-ads-input').on('input', function() {
            var search = $(this).val().toLowerCase();

            if (search === '') {
                $('.ad-position-block').show();
                return;
            }

            $('.ad-position-block').each(function() {
                var $block = $(this);
                var title = $block.find('.ad-position-title').text().toLowerCase();

                if (title.indexOf(search) !== -1) {
                    $block.show();
                } else {
                    $block.hide();
                }
            });
        });

    });

})(jQuery);
