#!/usr/bin/php

<?php

require_once("config.php");

if($path == "") {
  echo '{ "version": 1, "title": "Wordpress", "type":"commands", "values" : [ {"name": "Please configure the script", "value": "", "command": ""} ] }';
}

define('ABSPATH', $path);
//error_reporting(0);

if(!is_dir("$backup_path")) {
  mkdir("$backup_path");
}

$command = $argv[1];
echo $version;

switch($argv[1]) {
  case "backup":
    backup($backup_path);
    break;
  case "restoredb":
    restoreDB($argv[2]);
    break;
  case "restore":
    restoreFiles($argv[2]);
    break;
  case "upgrade":
    upgrade();
    break;
  case "info":
  default:
    info();
    break;
}

function latestVersion() {
  $check = file_get_contents("http://api.wordpress.org/core/version-check/1.1/");
  $lines = explode("\n", $check);
  return $lines[2];
}

function checkWPVersion($wp_path) {
  require_once($wp_path."wp-includes/version.php");
  return $wp_version;
}

function info() {
  global $path;
  $wp_version = checkWPVersion($path);
  $db_count = countDBBackups();
  $files_count = countFilesBackups();
echo '{ "version": 1, "title": "Wordpress", "type":"commands", "values" : [ {"name": "coincoin.com", "value": "v'.$wp_version.'", "command": ""}, {"name" : "Backup database", "value" : "", "command" : ".pilotssh/wordpress/wordpress.php backup" }, {"name" : "Restore database", "value" : "'.$db_count.' backups", "command" : ".pilotssh/wordpress/wordpress.php restoredb" }, {"name" : "Restore files", "value" : "'.$files_count.' backups", "command" : ".pilotssh/wordpress/wordpress.php restore" }, {"name": "Upgrade to '.latestVersion().'", "value": "", "command": ".pilotssh/wordpress/wordpress.php upgrade"} ] }';
}

function backup($backup_path) {
  global $path;
  require_once($path."wp-config.php");
  $conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  mysql_select_db(DB_NAME, $conn);
  if (! mysql_set_charset (DB_CHARSET, $conn)) {
    mysql_query('SET NAMES '.DB_CHARSET);
  }

  $tables = "*";

  //get all of the tables
  if($tables == '*') {
    $tables = array();
    $result = mysql_query('SHOW TABLES');
    while($row = mysql_fetch_row($result)) {
      $tables[] = $row[0];
    }
  } else {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }

  //cycle through
  foreach($tables as $table) {
    $result = mysql_query('SELECT * FROM '.$table);
    $num_fields = mysql_num_fields($result);

    $return.= 'DROP TABLE '.$table.';';
    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";

    for ($i = 0; $i < $num_fields; $i++)  {
      while($row = mysql_fetch_row($result)) {
        $return.= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$num_fields; $j++) {
          $row[$j] = addslashes($row[$j]);
          $row[$j] = ereg_replace("\n","\\n",$row[$j]);
          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j<($num_fields-1)) { $return.= ','; }
        }
        $return.= ");\n";
      }
    }
    $return.="\n\n\n";
  }

  if(!is_dir("$backup_path/sql/")) {
    mkdir("$backup_path/sql/");
  }

  //save file
  $handle = fopen($backup_path.'sql/'.time().'.sql','w+');
  fwrite($handle,$return);
  fclose($handle);

  msgSuccess("Backup database", "Done");
}

function restoreDB($version) {
  global $backup_path;
  global $path;
  require_once($path."wp-config.php");
  if(!is_dir("$backup_path/sql/")) {
    mkdir("$backup_path/sql/");
  }

  if($version == "") {
    $files = scandir("$backup_path/sql/");
    echo '{ "version": 1, "title": "Restore database", "type":"commands", "values" : [ ';
    if(count($files) >= 3) {
      $arr = explode(".", $files[2]);
      $time = $arr[0];
      $date = date('Y-m-d G:I:s', $time);
      echo '{"name" : "'.$date.'", "value":"", "command":".pilotssh/wordpress/wordpress.php restore '.$backup_path.'sql/'.$files[2].'"}';
    }
    for($i = 3; $i < count($files); $i++) {
      $arr = explode(".", $files[$i]);
      $time = $arr[0];
      $date = date('Y-m-d G:I:s', $time);
      echo ', {"name" : "'.$date.'", "value":"", "command":".pilotssh/wordpress/wordpress.php restore '.$backup_path.'sql/'.$files[$i].'"}';
    }
    echo '] }';
  }else {
    $all_query = file_get_contents($version);
    $conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysql_select_db(DB_NAME, $conn);
    if (! mysql_set_charset (DB_CHARSET, $conn)) {
      mysql_query('SET NAMES '.DB_CHARSET);
    }
 
    mysql_query(all_query);
    mysql_close($conn);
    msgSuccess("Restore database", "Done restoring $version");
  }
}

function countDBBackups() {
  global $backup_path;
  $files = scandir("$backup_path/sql/");
  if($files) {
    return count($files) -2;
  } else {
    return 0;
  }
}

function restoreFiles($version) {
  global $backup_path;
  if(!is_dir("$backup_path/files/")) {
    mkdir("$backup_path/files/");
  }

  if($version == "") {
    $files = scandir("$backup_path/files/");
    echo '{ "version": 1, "title": "Restore files", "type":"commands", "values" : [ ';
    if(count($files) >= 3) {
      $date = date('Y-m-d G:I:s', $files[2]);
      $v = checkWPVersion("$backup_path/files/".$files[2]."/");
      echo '{"name" : "'.$date.'", "value":"v'.$v.'", "command":".pilotssh/wordpress/wordpress.php restore '.$backup_path.'files/'.$files[2].'"}';
    }
    for($i = 3; $i < count($files); $i++) {
      $date = date('Y-m-d G:I:s', $files[$i]);
      $v = checkWPVersion("$backup_path/files/".$files[$i]."/");
      echo ', {"name" : "'.$date.'", "value":"v'.$v.'", "command":".pilotssh/wordpress/wordpress.php restore '.$backup_path.'files/'.$files[$i].'"}';
    }
    echo '] }';
  }
}

function countFilesBackups() {
  global $backup_path;
  $files = scandir("$backup_path/files/");
  if($files) {
    return count($files) -2;
  } else {
    return 0;
  }
}


function upgrade() {
  global $path;
  global $backup_path;
  $hash = file_get_contents("http://wordpress.org/latest.tar.gz.sha1");

  exec('wget http://wordpress.org/latest.tar.gz', $array, $return);

  if($return != 0) {
    msgFail("Upgrade", "could not download the archive");
  }

  if(!is_dir("$backup_path/files/")) {
    mkdir("$backup_path/files/");
  }

  $time = time();
  $current = "$backup_path/files/$time";
  mkdir($current);
  exec("cp -r $path/* $current");

  exec("rm -rf $path/*", $array, $return);
  exec("tar zxvf latest.tar.gz --strip-components 1 --directory $path", $array, $return);
  if($return != 0) {
    msgFail("Upgrade", "could not extract the archive");
  }
  exec("touch $path/.maintenance");

  exec("cp    $current/wp-config.php $path", $array, $return);
  //exec("cp -r $current/wp-content $path", $array, $return);
  exec("cp -r $current/wp-images $path", $array, $return);
  exec("cp -r $current/wp-include/languages $path/wp-include", $array, $return);
  exec("cp    $current/.htaccess $path", $array, $return);
  exec("cp    $current/robots.txt $path", $array, $return);

  unlink("latest.tar.gz");

  rename("$path/wp-content/plugins", "$path/wp-content/plugins.hold");

  chdir($path);
  define( 'WP_INSTALLING', true );
  require "{$path}wp-load.php";
  @ini_set( 'memory_limit', -1 );
  require "{$path}wp-admin/includes/admin.php";
  require "{$path}/wp-admin/includes/upgrade.php";
  wp_upgrade();

  rename("$path/wp-content/plugins.hold", "$path/wp-content/plugins");
  unlink("$path/.maintenance");
  msgSuccess("Upgrade", "Done");
}

function msgSuccess($title, $msg) {
  echo '{ "version": 1, "title": "'.$title.'", "type":"status", "status":"ok", "message":"'.$msg.'."}';
  exit(0);
}

function msgFail($title, $msg) {
  echo '{ "version": 1, "title": "'.$title.'", "type":"status", "status":"failed", "message":"'.$msg.'."}';
  exit(1);
}
?>
