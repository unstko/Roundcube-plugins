/**
 * JavaScript file of Roundcube plugin shortmail_character_limiter.
 * See PHP file shortmail_character_limiter.php for more information.
 */

// Test for Roundcube application object
if (window.rcmail) {
    /**
     * Roundcube event listener to initialize plugin
     */
    rcmail.addEventListener('init', function(evt) {
        // Character limit
        var limit = rcmail.env.character_limit;

        // Character left message
        var character_left_message = rcmail.gettext('character_left_message', 'shortmail_character_limiter');

        /**
         * Append input field to show left characters
         */
        $('#compose-buttons').append
        (
            $('<span>')
                .attr('id', 'shortmail_character_limiter_span_left')
                .html
                (
                    $('<input>')
                        .attr('readonly', 'readonly')
                        .attr('type', 'text')
                        .attr('name', 'shortmail_character_limiter_input_left')
                        .attr('size', '3')
                        .attr('maxlength', '3')
                        .attr('value', limit)
                ), ' ' + message
        );

        /**
         * Bind keyup and keydown event handler to message text area
         *
         * Original character limiting function written by Nannette Thacker.
         * http://www.shiningstar.net/articles/articles/javascript/dynamictextareacounter.asp
         */
        $('#compose-body').bind('keyup keydown click', function() {
            if (document.form._message.value.length > limit) {
                // If too long, then trim it
                document.form._message.value = document.form._message.value.substring(0, limit);
            }
            else {
                // Otherwise update characters left counter
                document.form.shortmail_character_limiter_input_left.value = limit - document.form._message.value.length;
            }
        });
    });
}

/**
 * Limit the number of characters per textarea.
 * Use one function for multiple text areas on a page.
 * Dynamic version by Nannette Thacker.
 * http://www.shiningstar.net
 * http://www.shiningstar.net/articles/articles/javascript/dynamictextareacounter.asp
 * Original by Ronnie T. Moore.
 * http://www.javascript.com
 */
function character_limiter(field, cntfield, maxlimit)
{
    if (field.value.length > maxlimit) {
        // If too long, then trim it
        field.value = field.value.substring(0, maxlimit);
    }
    else {
        // Otherwise update characters left counter
        cntfield.value = maxlimit - field.value.length;
    }
}
