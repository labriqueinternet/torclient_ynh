<h2><?= T_("Tor client configuration") ?></h2>
<?php if($status): ?>
  <span class="label label-success" data-toggle="tooltip" data-title="<?= T_('This is a fast status. Click on More details to show the complete status.') ?>"><?= T_('Running') ?></span>
<?php else: ?>
  <span class="label label-danger" data-toggle="tooltip" data-title="<?= T_('This is a fast status. Click on More details to show the complete status.') ?>"><?= T_('Not Running') ?></span>
<?php endif; ?>

 &nbsp; <img src="public/img/loading.gif" id="status-loading" alt="Loading..." /><a href="#" id="statusbtn" data-toggle="tooltip" data-title="<?= T_('Loading complete status may take a few minutes. Be patient.') ?>"><?= T_('More details') ?></a>

<div id="status" class="alert alert-dismissible alert-info fade in" style="margin-top: 10px" role="alert">
  <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <div id="status-text"></div>
</div>

<hr />

<div class="row">
  <div class="col-sm-offset-2 col-sm-8">
    <form method="post" enctype="multipart/form-data" action="?/settings" class="form-horizontal" role="form" id="form">
      <input type="hidden" name="_method" value="put" />

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?= T_("Wifi") ?></h3>
        </div>

        <div style="padding: 14px 14px 0 10px">
  
          <div class="form-group">
            <label for="tro_active" class="col-sm-3 control-label"><?= T_('Active') ?></label>
            <div class="col-sm-9 input-group-btn" data-toggle="tooltip" data-title="<?= T_('set tor enable or disable') ?>">
              <div class="input-group">
                <input type="checkbox" class="form-control switch" name="status" id="status" value="1" <?= $status == 1 ? 'checked="checked"' : '' ?> />
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="wifi_ssid" class="col-sm-3 control-label"><?= T_('Associated Hotspot') ?></label>
            <div class="col-sm-9 input-group-btn">
              <div class="input-group">
                  <input type="text" name="wifi_ssid" id="wifi_ssid" value="<?= $wifi_ssid ?>" style="display: none" />
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?= $wifi_ssid ?> <span class="caret"></span></button>
                  <ul class="dropdown-menu dropdown-menu-left" role="menu">
                    <?= $wifi_ssid_list ?>
                  </ul>
              </div>
            </div>
          </div>
	</div>
      </div>
      <div class="form-group">
        <div style="text-align: center">
          <button type="submit" class="btn btn-default" data-toggle="tooltip" id="save" data-title="<?= T_('Reloading may take a few minutes. Be patient.') ?>"><?= T_('Save and reload') ?></button> <img src="public/img/loading.gif" id="save-loading" alt="Loading..." />
        </div>
      </div>
    </form>
  </div>
</div>
