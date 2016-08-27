<?php
/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

 ---------------------------------------------------------------------
 Emoncms - open source energy visualisation
 Part of the OpenEnergyMonitor project:
 http://openenergymonitor.org
 */

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

class Graph
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function set($userid,$savedgraphs)
    {
        $userid = intval($userid);
        $savedgraphs = preg_replace('/[^\w\s-.",:#{}\[\]]/','',$savedgraphs);
        
        $result = $this->mysqli->query("SELECT savedgraphs FROM graph WHERE `userid`='$userid'");
        
        if ($result->num_rows==0) {
            $stmt = $this->mysqli->prepare("INSERT INTO graph ( userid, savedgraphs ) VALUES (?,?)");
            $stmt->bind_param("is", $userid, $savedgraphs);
            $stmt->execute();
            return array("success"=>true, "message"=>"created");
        } else {
            $stmt = $this->mysqli->prepare("UPDATE graph SET `savedgraphs`=? WHERE `userid`=?");
            $stmt->bind_param("si", $savedgraphs, $userid);
            $stmt->execute();
            return array("success"=>true, "message"=>"updated");
        }
    }
    
    public function get($userid)
    {
        $userid = intval($userid);
        $result = $this->mysqli->query("SELECT savedgraphs FROM graph WHERE `userid`='$userid'");
        $row = $result->fetch_array();
        $savedgraphs = json_decode($row['savedgraphs']);
        if ($savedgraphs==null) $savedgraphs = array();
        return $savedgraphs;
    }
}
