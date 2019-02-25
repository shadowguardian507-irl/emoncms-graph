<?php
    // $domain = "messages";
    // bindtextdomain($domain, "Modules/graph/locale");
    // bind_textdomain_codeset($domain, 'UTF-8');

    $menu['sidebar']['setup'][] = array(
        'text' => _("Graphs"),
        'path' => 'graph',
        'icon' => 'show_chart',
        'order' => 2,
        'li_id' => 'graph-link'
    );

    $menu['sidebar']['includes']['setup'][] = view('Modules/graph/Views/sidebar.php');