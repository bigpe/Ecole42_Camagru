<?php
function page_append($doc, $elem_id, $id, $text)
{
    $main_block = $doc->getElementById($elem_id);

    $elem = $doc->createElement('li', $text);


    $main_block->appendChild($elem);

    $elem->setAttribute("id", $id);

    return($doc);
}
?>
