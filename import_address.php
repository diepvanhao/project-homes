<?php

include 'header.php';
$page = 'import_address';

include 'include/class_import.php';
if (!$user->user_exists) {
    header('Location: ./user_login.php');
    exit();
}
if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
}
$csv = array();
// check there are no errors
if (!empty($_POST) && $_POST['submit'] && $_FILES['csv']['error'] == 0) {
    $name = $_FILES['csv']['name'];
    $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
    $type = $_FILES['csv']['type'];
    $tmpName = $_FILES['csv']['tmp_name'];

    // check the file is a csv
    if ($ext === 'csv') {
        if (($handle = fopen($tmpName, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

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
                        if(@count($csv[$row]) == 3){
                            $row ++ ;
                        }
                        $csv[$row][] = $value;
                    }
                    if(count($arr)){
                        $csv[$row][] = $arr[0];
                        $row ++ ;
                        $csv[$row][] = $arr[1];
                        $arr = array();
                    }
                }
            }
            fclose($handle);
        }
    }
    if (count($csv) > 1) {
//        unset($csv[0]);
        $import = new HOMEImport();
        $import->importAddress($csv);
        header("Location: notify.php?content=Import Success!!!&url_return=import_address.php");
    }
}

include 'footer.php';
