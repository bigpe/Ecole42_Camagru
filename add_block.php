<?php
function add_block($page_name, $elem_id, $session)
{
    $page = file_get_contents("html/index.html");
    $doc = new DOMDocument();
    @$doc->loadHTML($page);


    if ($session == 1) {
        $profile = $doc->getElementById("profile_sht");
        $gallery = $doc->getElementById("gallery");
        $sign_out = $doc->getElementById("sign_out");

        $profile->setAttribute("style", "display: inherit");
        $gallery->setAttribute("style", "display: inherit");
        $sign_out->setAttribute("style", "display: inherit");
    }

    $main_block = $doc->getElementById($elem_id);
    $add_page = file_get_contents($page_name);

    $add_node = new DOMDocument();
    @$add_node->loadHTML($add_page);

    $add_node = $doc->importNode($add_node->documentElement, true);
    $main_block->appendChild($add_node);
    return($doc);
}
?>
