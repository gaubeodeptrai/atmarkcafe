
<?php

function directoryToArray($directory, $recursive) {
    $array_items = array();
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($directory. "/" . $file)) {
                    if($recursive) {
                        $array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
                    }
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                } else {
                    $file = $directory . "/" . $file;
                    $array_items[] = ['name'=>preg_replace("/\/\//si", "/", $file),'conten'=>'sdfsdfsdf'];
                }
            }
        }
        closedir($handle);
    }
    return $array_items;
}
echo "<pre>";
print_r(json_encode(directoryToArray('images/clipart/01_BM-ZU',''),JSON_PRETTY_PRINT));
echo "</pre>";
?>
