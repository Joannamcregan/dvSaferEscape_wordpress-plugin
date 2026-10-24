jQuery(document).ready(function($) {
    // Target the input field by its assigned CSS class
    $('.dvSaferEscape_color-field').wpColorPicker({
        clear: (e)=> {
            const defaultColor = $(e.target).closest('td').data('default')
            $(e.target).closest('span.wp-picker-input-wrap').find('input.dvSaferEscape_color-field').iris('color', defaultColor);
        }
    });
});