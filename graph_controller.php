<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function graph_controller()
{
    global $session,$route,$mysqli,$redis;
    
    require_once "Modules/graph/graph_model.php";
    $graph = new Graph($mysqli);

    if (!$session['write']) return array('content'=>"", 'fullwidth'=>false);
    
    if ($route->action=="embed") {
        global $embed; $embed = true;
        $result = view("Modules/graph/embed.php",array());
    }   
    else if ($route->action=="list") {
        $route->format="json";
        $result = $graph->get($session['userid']);
    }
    else if ($route->action=="save") {
        $route->format="json";
        $result = $graph->set($session['userid'],post("mygraphs"));
    }
    
    else $result = view("Modules/graph/view.php",array());
    
    return array('content'=>$result, 'fullwidth'=>true);
}
