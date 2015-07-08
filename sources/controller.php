<?php

function moulinette_hotspot_get($var) {
  return htmlspecialchars(exec('sudo yunohost app setting hotspot '.escapeshellarg($var)));
}

function moulinette_get($var) {
  return htmlspecialchars(exec('sudo yunohost app setting torclient '.escapeshellarg($var)));
}

function moulinette_set($var, $value) {
  return exec('sudo yunohost app setting torclient '.escapeshellarg($var).' -v '.escapeshellarg($value));
}

function stop_service() {
  exec('sudo systemctl stop ynh-torclient');
}

function start_service() {
  exec('sudo systemctl start ynh-torclient', $output, $retcode);

  return $retcode;
}

function service_status() {
  exec('sudo ynh-torclient status', $output);

  return $output;
}

function service_faststatus() {
  exec('sudo systemctl is-active ynh-torclient', $output, $retcode);

  return $retcode;
}

dispatch('/', function() {
  $ssids = explode('|', moulinette_hotspot_get('wifi_ssid'));
  $wifi_device_id = moulinette_get('wifi_device_id');
  $wifi_ssid_list = '';
  $wifi_ssid = '';

  for($i = 0; $i < count($ssids); $i++) {
    $active = '';

    if($i == $wifi_device_id) {
      $active = 'class="active"';
      $wifi_ssid = htmlentities($ssids[$i]);
    }

    $wifi_ssid_list .= "<li $active data-device-id='$i'><a href='javascript:;'>".htmlentities($ssids[$i]).'</a></li>';
  }

  set('faststatus', service_faststatus() == 0);
  set('service_enabled', moulinette_get('service_enabled'));
  set('wifi_device_id', $wifi_device_id);
  set('wifi_ssid', $wifi_ssid);
  set('wifi_ssid_list', $wifi_ssid_list);

  return render('settings.html.php');
});

dispatch_put('/settings', function() {
  $service_enabled = isset($_POST['service_enabled']) ? 1 : 0;

  if($service_enabled == 1) {
    try {
      if($_POST['wifi_device_id'] == -1) {
        throw new Exception(_('You need to select an associated hotspot'));
      }

    } catch(Exception $e) {
      flash('error', $e->getMessage().' ('._('configuration not updated').').');
      goto redirect;
    }
  }

  stop_service();

  moulinette_set('service_enabled', $service_enabled);

  if($service_enabled == 1) {
    moulinette_set('wifi_device_id', $_POST['wifi_device_id']);

     $retcode = start_service();

    if($retcode == 0) {
      flash('success', _('Configuration updated and service successfully reloaded'));
    } else {
      flash('error', _('Configuration updated but service reload failed'));
    }

  } else {
      flash('success', _('Service successfully disabled'));
  }

  redirect:
  redirect_to('/');
});

dispatch('/status', function() {
  $status_lines = service_status();
  $status_list = '';

  foreach($status_lines AS $status_line) {
    if(preg_match('/^\[INFO\]/', $status_line)) {
      $status_list .= '<li class="status-info">'.htmlspecialchars($status_line).'</li>';
    }
    elseif(preg_match('/^\[OK\]/', $status_line)) {
      $status_list .= '<li class="status-success">'.htmlspecialchars($status_line).'</li>';
    }
    elseif(preg_match('/^\[WARN\]/', $status_line)) {
      $status_list .= '<li class="status-warning">'.htmlspecialchars($status_line).'</li>';
    }
    elseif(preg_match('/^\[ERR\]/', $status_line)) {
      $status_list .= '<li class="status-danger">'.htmlspecialchars($status_line).'</li>';
    }
  }

  echo $status_list;
});

dispatch('/lang/:locale', function($locale = 'en') {
  switch($locale) {
    case 'fr':
      $_SESSION['locale'] = 'fr';
    break;

    default:
      $_SESSION['locale'] = 'en';
  }

  redirect_to('/');
});
