<?php
    $domain = "messages";
    bindtextdomain($domain, "Modules/graph/locale");
    bind_textdomain_codeset($domain, 'UTF-8');
    // $menu_dropdown_config[] = array('name'=> dgettext($domain,"Graphs"), 'icon'=>'icon-retweet', 'path'=>"graph" , 'session'=>"write", 'order' => 25 );

    $menu['setup'][] = array(
        'text' => _("Graphs"),
        'path' => 'graph',
        'icon' => 'show_chart',
        'sort' => 2,
        'li_id' => 'graph-link'
    );

    // @todo: use os specific directory separators
    // implode(DIRECTORY_SEPARATOR, explode(',','Modules,graph,Views,sidebar.php'));
    $menu['includes']['graph'][] = view('Modules/graph/Views/sidebar.php');