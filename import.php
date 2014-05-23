<?php

include 'header.php';
$page = 'import';

include 'include/class_import.php';

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

            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // number of fields in the csv
                $num = count($data);
                foreach ($data as $key => $value) {
                    $value = mb_convert_encoding($value, "UTF-8", "Shift-JIS");
                     
                    if (strpos($value, "\r") !== false) {
                        $arr = explode("\r", $value);
                        if (ctype_digit($arr[1])) {
                            $csv[$row][] = $arr[0];
                            $row++;
                            $csv[$row][] = $arr[1];
                        } else {
                            if (ctype_digit($value) && count($csv[$row]) >= 16) {
                                $row++;
                            }
                            $csv[$row][] = $value;
                        }
                    } elseif (strpos($value, "\r\n") !== false) {
                        $arr = explode("\r\n", $value);
                        if (ctype_digit($arr[1])) {
                            $csv[$row][] = $arr[0];
                            $row++;
                            $csv[$row][] = $arr[1];
                        } else {
                            if (ctype_digit($value) && count($csv[$row]) >= 16) {
                                $row++;
                            }
                            $csv[$row][] = $value;
                        }
                    } elseif (strpos($value, "\n") !== false) {
                        $arr = explode("\n", $value);
                        if (ctype_digit($arr[1])) {
                            $csv[$row][] = $arr[0];
                            $row++;
                            $csv[$row][] = $arr[1];
                        } else {
                            if (ctype_digit($value) && count($csv[$row]) >= 16) {
                                $row++;
                            }
                            $csv[$row][] = $value;
                        }
                    } else {
                        if (ctype_digit($value) && count($csv[$row]) >= 16) {
                            $row++;
                        }
                       
                        $csv[$row][] = $value; // mb_convert_encoding($value, "UTF-8", "Shift-JIS");
                    }
                }
            }
            fclose($handle);
        }
    }
   
    if (count($csv) > 1) {
        unset($csv[0]);
        $import = new HOMEImport();
        $import->import($csv);
        header("Location: notify.php?content=Import Success!!!&url_return=import.php");
    }
}
include 'footer.php';
