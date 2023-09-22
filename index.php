<?php

// Utilisation de cURL pour effectuer une requête HTTP
function get_page_content($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

// URL à crawler
$url = "https://example.com";

// Récupération du contenu de la page
$html_content = get_page_content($url);

// Traitement du contenu (par exemple, recherche de liens)
$matches = [];
preg_match_all('/<a href="([^"]+)">/', $html_content, $matches);

// Affichage des liens trouvés
foreach ($matches[1] as $link) {
    echo $link . "\n";
}

?>