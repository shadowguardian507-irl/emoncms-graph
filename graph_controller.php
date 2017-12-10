<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function graph_controller() {
    global $session, $route, $mysqli, $redis;

    if (group_module_installed()) {
        require_once "Modules/group/group_model.php";
        $group = new Group($mysqli, $redis, null, null, null);
    }
    else
        $group = null;

    require_once "Modules/graph/graph_model.php";
    $graph = new Graph($mysqli, $group);

    $result = "";

    if ($route->action == "embed") {
        global $embed;
        $embed = true;
        $result = view("Modules/graph/embed.php", array());
    }
    else if ($session['write'] && $route->action == "create" && isset($_POST['data'])) {
        $route->format = "json";
        $result = $graph->create($session['userid'], post('data'));
    }
    else if ($session['write'] && $route->action == "creategroupgraph" && isset($_POST['data']) && isset($_POST['groupid']) && group_module_installed()) {
        $route->format = "json";
        $result = $graph->creategroupgraph($session['userid'], post('data'), post('groupid'));
    }
    else if ($session['write'] && $route->action == "update" && isset($_POST['id']) && isset($_POST['data'])) {
        $route->format = "json";
        $result = $graph->update($session['userid'], post('id'), post('data'));
    }
    else if ($session['write'] && $route->action == "updategroupgraph" && isset($_POST['id']) && isset($_POST['data'])) {
        $route->format = "json";
        $result = $graph->updategroupgraph($session['userid'], post('id'), post('data'),post('groupid'));
    }
    else if ($session['write'] && $route->action == "delete" && isset($_POST['id'])) {
        $route->format = "json";
        $result = $graph->delete($session['userid'], post('id'));
    }
    else if ($session['write'] && $route->action == "deletegroupgraph" && isset($_POST['id'])) {
        $route->format = "json";
        $result = $graph->deletegroupgraph($session['userid'], post('id'));
    }
    else if ($route->action == "get" && isset($_GET['id'])) {
        $route->format = "json";
        if (isset($session['userid']))
            $userid = $session['userid'];
        else
            $userid = 0;
        $result = $graph->get($userid, get('id'));
    }

    else if ($session['read'] && $route->action == "getall") {
        $route->format = "json";
        $result = $graph->getall($session['userid']);
    }
    else {
        if (group_module_installed())
            $result = view("Modules/graph/view.php", array("session" => $session["write"], 'group_support' => 1));
        else
            $result = view("Modules/graph/view.php", array("session" => $session["write"], 'group_support' => 0));
    }

    return array('content' => $result, 'fullwidth' => true);
}

function group_module_installed() {
    global $mysqli;
    $result = $mysqli->query("SHOW TABLES LIKE 'groups'");
    if ($result->num_rows > 0)
        return true;
    else
        false;
}
