<div style="padding-left: .7rem">
    <h3 style="margin-bottom: 0"><?php echo _('Feeds') ?>:</h3>
</div>

<table class="table table-condensed" id="feeds"></table>

<div id="mygraphs" style="padding-left: .7rem;">
    <h4><?php echo _('My Graphs') ?></h4>

    <select id="graph-select"></select>

    <h5><?php echo _('Graph Name') ?>:</h5>
    <input id="graph-name" type="text">
    <small id="selected-graph-id"><?php echo _('Selected graph id') ?>: <span id="graph-id"><?php echo _('None selected') ?></span></small>
    <button id="graph-delete" class="btn" style="display:none"><?php echo _('Delete') ?></button>
    <button id="graph-save" class="btn"><?php echo _('Save') ?></button>
</div>