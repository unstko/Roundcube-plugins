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
