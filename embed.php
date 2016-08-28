<?php
    /*
    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
    */

    global $path, $embed;
    global $fullwidth;
    $fullwidth = true;
    
    $name = get("name");
?>

<!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/jquery.flot.time.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path;?>Lib/flot/jquery.flot.selection.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path;?>Modules/graph/vis.helper.js"></script>

<div id="navigation" style="padding-bottom:5px;">
    <button class='btn graph_time' type='button' time='1'>D</button>
    <button class='btn graph_time' type='button' time='7'>W</button>
    <button class='btn graph_time' type='button' time='30'>M</button>
    <button class='btn graph_time' type='button' time='365'>Y</button>
    <button id='graph_zoomin' class='btn'>+</button>
    <button id='graph_zoomout' class='btn'>-</button>
    <button id='graph_left' class='btn'><</button>
    <button id='graph_right' class='btn'>></button>
</div>

<div id="placeholder_bound" style="width:100%; height:100%">
    <div id="placeholder"></div>
</div>

<script language="javascript" type="text/javascript" src="<?php echo $path;?>Modules/graph/graph.js"></script>

<script>
    $("body").css("background","none");
    embed = true;
    
    var path = "<?php echo $path; ?>";
    
    var name = "<?php echo $name; ?>";
    
    $.ajax({                                      
        url: path+"/graph/list",
        async: true,
        dataType: "json",
        success: function(result) {
            savedgraphs = result;
            
            var index = graph_index_from_name(name);
            
            view.start = savedgraphs[index].start;
            view.end = savedgraphs[index].end;
            view.interval = savedgraphs[index].interval;
            view.limitinterval = savedgraphs[index].limitinterval;
            view.fixinterval = savedgraphs[index].fixinterval;
            floatingtime = savedgraphs[index].floatingtime,
            yaxismin = savedgraphs[index].yaxismin;
            yaxismax = savedgraphs[index].yaxismax;
            feedlist = savedgraphs[index].feedlist;
            
            if (floatingtime) {
                var timewindow = view.end - view.start;
                var now = Math.round(+new Date * 0.001)*1000;
                view.end = now;
                view.start = view.end - timewindow;
            }

            graph_resize();
            graph_reloaddraw();
        }
    });
    

</script>

