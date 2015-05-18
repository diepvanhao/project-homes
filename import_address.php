<?php

include 'header.php';
$page = 'import_address';
ini_set('max_execution_time', 3000);
include 'include/class_import.php';
if (!$user->user_exists) {
    header('Location: ./user_login.php');
    exit();
}
if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
$csv = array();
//Hao customize
mb_language('Japanese');
mb_detect_order('auto');
mb_internal_encoding("Shift_JIS");
//End customize
// check there are no errors
if (!empty($_POST) && $_POST['submit'] && $_FILES['csv']['error'] == 0) {
    $name = $_FILES['csv']['name'];
    $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
    $type = $_FILES['csv']['type'];
    $tmpName = $_FILES['csv']['tmp_name'];
//Hao customize
    setlocale(LC_ALL, 'ja_JP.EUCJP');
//End customize
    // check the file is a csv
    if ($ext === 'csv') {
        //Hao customize

        $content = file_get_contents($tmpName);

        $content = mb_convert_encoding($content, 'UTF-8', 'Shift-JIS');
        // $content = explode(PHP_EOL, $content);
        $content = preg_split("/\r\n|\n|\r/", $content);
        $content = str_replace('"', "", $content);

        foreach ($content as $key => $value) {
            $value = explode(',', $value);
            $csv[(int) $key]['city'] = isset($value[0]) ? $value[0] : "";
            $csv[(int) $key]['district'] = isset($value[1]) ? $value[1] : "";
            $csv[(int) $key]['street'] = isset($value[2]) ? $value[2] : "";
            $csv[(int) $key]['ward'] = isset($value[3]) ? $value[3] : "";
        }
        //end customize
        /* if (($handle = fopen($tmpName, 'r')) !== FALSE) {
          // necessary if a large csv file
          // set_time_limit(0);

          $row = 0;
          $arr = array();
          while (($data = fgetcsv($handle, 0, ',')) !== FALSE) {
          // number of fields in the csv
          $num = count($data);
          foreach ($data as $key => $value) {
          $value = mb_convert_encoding($value, "UTF-8", "Shift-JIS");
          if (strpos($value, "\r") !== false) {
          $arr = explode("\r", $value);
          } elseif (strpos($value, "\r\n") !== false) {
          $arr = explode("\r\n", $value);
          } elseif (strpos($value, "\n") !== false) {
          $arr = explode("\n", $value);
          } else {
          if (@count($csv[$row]) == 3) {
          $row ++;
          }
          $csv[$row][] = $value;
          }
          if (count($arr)) {
          $csv[$row][] = $arr[0];
          $row ++;
          $csv[$row][] = $arr[1];
          $arr = array();
          }
          }
          }
          fclose($handle);
          } */
    }
    
    if (count($csv) > 1) {
        unset($csv[0]);
        $import = new HOMEImport();
        $import->importAddress($csv);
        header("Location: notify.php?content=Import Success!!!&url_return=import_address.php");
    }
}

include 'footer.php';
