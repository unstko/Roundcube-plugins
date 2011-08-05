if (window.rcmail) {
    rcmail.addEventListener('init', function(evt) {
        // Character limit
        var limit = 5;

        // Append field to show left characters
        $('#compose-buttons').append
        (
            $('<span>')
                .attr('id', 'shortmail_character_limiter_field_left')
                .html
                (
                    $('<input>')
                        .attr('readonly', 'readonly')
                        .attr('type', 'text')
                        .attr('name', 'shortmail_character_limiter_field_left')
                        .attr('size', '3')
                        .attr('maxlength', '3')
                        .attr('value', limit)
                )
        );

        // Bind keyup and keydown event handler to message text area
        $('#compose-body').bind('keyup keydown', function() {
            if (document.form._message.value.length > limit) {
                // If too long, then trim it
                document.form._message.value = document.form._message.value.substring(0, limit);
            }
            else {
                // Otherwise update characters left counter
                document.form.shortmail_character_limiter_field_left.value = limit - document.form._message.value.length;
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
