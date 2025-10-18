/**
 * Admin Panel JavaScript
 */

(function($) {
    'use strict';

    // Sidebar Toggle
    $(document).on('click', '.JS__sidebar_toggler', function(e) {
        e.preventDefault();
        
        var $sidebar = $('.sidebar');
        var $wrapper = $('.wrapper');
        
        if ($sidebar.hasClass('sidebar-mini')) {
            $sidebar.removeClass('sidebar-mini');
            $wrapper.removeClass('wrapper-sidebar_mini');
            // Save state
            $.post('/maintenance/set-session', { 
                key: 'sidebar_mini', 
                value: '0',
                _token: $('meta[name="csrf-token"]').attr('content')
            });
        } else {
            $sidebar.addClass('sidebar-mini');
            $wrapper.addClass('wrapper-sidebar_mini');
            // Save state
            $.post('/maintenance/set-session', { 
                key: 'sidebar_mini', 
                value: '1',
                _token: $('meta[name="csrf-token"]').attr('content')
            });
        }
    });

    // Sidebar Menu Toggle
    $(document).on('click', '.sidebar-menu > li > button', function(e) {
        e.preventDefault();
        
        var $li = $(this).parent();
        var $childMenu = $li.find('> .child_menu');
        
        if ($li.hasClass('active')) {
            $li.removeClass('active');
            $childMenu.slideUp(300);
        } else {
            // Close other menus
            $('.sidebar-menu > li.active').removeClass('active').find('.child_menu').slideUp(300);
            // Open this menu
            $li.addClass('active');
            $childMenu.slideDown(300);
        }
    });

    // Nested Menu Toggle
    $(document).on('click', '.child_menu > li > button', function(e) {
        e.preventDefault();
        
        var $li = $(this).parent();
        var $childMenu = $li.find('> .child_menu');
        
        if ($li.hasClass('active')) {
            $li.removeClass('active');
            $childMenu.slideUp(300);
        } else {
            $li.addClass('active');
            $childMenu.slideDown(300);
        }
    });

    // Modal Loading
    $(document).on('click', '.JS__load_in_modal', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var $modal = $('#JS__modal');
        
        $modal.find('.modal-body').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');
        $modal.modal('show');
        
        $.get(url, function(data) {
            $modal.find('.modal-body').html(data);
        }).fail(function() {
            $modal.find('.modal-body').html('<div class="alert alert-danger">Failed to load content.</div>');
        });
    });

    // Confirm Delete
    $(document).on('submit', 'form[onsubmit*="confirm"]', function(e) {
        var message = 'Are you sure you want to delete this item?';
        if (!confirm(message)) {
            e.preventDefault();
            return false;
        }
    });

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert-destination .alert').fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000);

    // Table sorting
    $(document).on('click', 'th a', function(e) {
        if ($(this).attr('href') && $(this).attr('href').indexOf('sort=') !== -1) {
            // Add loading indicator
            $('#loader').fadeIn(200);
        }
    });

    // Form validation helper
    $('.form-control').on('invalid', function() {
        $(this).addClass('is-invalid');
    }).on('input', function() {
        $(this).removeClass('is-invalid');
    });

    // Initialize tooltips
    if (typeof $.fn.tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Initialize popovers
    if (typeof $.fn.popover !== 'undefined') {
        $('[data-toggle="popover"]').popover();
    }

    // Checkbox "select all" functionality
    $(document).on('change', 'input[type="checkbox"].select-all', function() {
        var checked = $(this).prop('checked');
        $(this).closest('table').find('tbody input[type="checkbox"]').prop('checked', checked);
    });

    // Number input validation
    $('input[type="number"]').on('keypress', function(e) {
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
            e.preventDefault();
            return false;
        }
    });

    // AJAX form submission
    $(document).on('submit', 'form.ajax-form', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var url = $form.attr('action');
        var method = $form.attr('method') || 'POST';
        var data = $form.serialize();
        
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function(response) {
                if (response.success) {
                    if (response.message) {
                        showAlert('success', response.message);
                    }
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } else {
                    showAlert('danger', response.message || 'An error occurred.');
                }
            },
            error: function() {
                showAlert('danger', 'An error occurred. Please try again.');
            }
        });
    });

    // Helper function to show alerts
    function showAlert(type, message) {
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '</div>';
        
        $('.alert-destination').html(alertHtml);
        
        setTimeout(function() {
            $('.alert-destination .alert').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Page load complete
    $(window).on('load', function() {
        $('#loader').fadeOut(300);
    });

})(jQuery);
