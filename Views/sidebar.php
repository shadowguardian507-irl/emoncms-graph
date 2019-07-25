<table class="table table-condensed mx-3" id="feeds" style="width: 90%"></table>

<div id="mygraphs" class="px-3">
    <h4><a href="#" class="collapsed" data-toggle="collapse" data-target="#graph-list">
        <?php echo _('My Graphs') ?> <span class="arrow arrow-down pull-right"></span>
    </a></h4>

    <div id="graph-list" class="collapse">
        <select id="graph-select"></select>
        <h5><?php echo _('Graph Name') ?>:</h5>
        <input id="graph-name" type="text" class="mb-0">
        <small id="selected-graph-id"><?php echo _('Selected graph id') ?>: <span id="graph-id"><?php echo _('None selected') ?></span></small>
        <div class="mt-2">
            <button id="graph-delete" class="btn" style="display:none"><?php echo _('Delete') ?></button>
            <button id="graph-save" class="btn"><?php echo _('Save') ?></button>
        </div>
    </div>
</div>