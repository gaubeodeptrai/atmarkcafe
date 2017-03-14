
Truy vấn theo đường dẫn query string trên url: list.php?directory=YOUR_DIRECTORY
VD : abc.com?directory=images/clipart_metadata
<?PHP

/**
 * Created by PhpStorm.
 * User: @anhvu
 * Date: 14/03/2017
 */
/********************* automatically initialized json file directory structure and files ************************/
function dir_to_array($dir)
{
    if (! is_dir($dir)) {
        //echo "Thư mục này chưa tồn tại!";
        // If the path is wrong is not done
        return null;
    }
    $data = [];

    foreach (new DirectoryIterator($dir) as $f) {
        if ($f->isDot()) {
            // Dot files like '.' and '..' must be skipped.
            continue;
        }

        $path = $f->getPathname();
        $name = $f->getFilename();

        if ($f->isFile()) {
            $data[] = [ 'file' => $name,'path'=>$path, ];
        } else {
            // Process the content of the directory.
            $files = dir_to_array($path);
            $data[] = ['name' => $name,'content'  => $files ];
            // A directory has a 'name' attribute
            // to be able to retrieve its name.

        }
    }

    // Sorts files and directories if they are not on your system.
    usort($data, function($a, $b) {
        $aa = isset($a['file']) ? $a['file'] : $a['name'];
        $bb = isset($b['file']) ? $b['file'] : $b['name'];

        return strcmp($aa, $bb);
    });

    return $data;
}

/*
 * Converts a filesystem tree to a JSON representation.
 */
function dir_to_json($dir)
{
    $data = dir_to_array($dir);

    $data = json_encode($data,JSON_PRETTY_PRINT);

    return $data;
}
if (isset($_GET['directory'])) {
    $directory = $_GET['directory'];
    $dir = new DirectoryIterator($directory);
    $counter = 0;
    // check the current folder with subfolders exist or not
    foreach ($dir as $fileinfo) {
        if ($fileinfo->isDir() && !$fileinfo->isDot()) {
            $counter++;
        }
    }

    if ($counter == 0) {
        $content_json = '{';

        $content_json .= '"name" : "' . $directory . '",';

        $content_json .= '"content":';

        $content_json .= dir_to_json($directory);
        $content_json .= '}';
    }else{
        $content_json = dir_to_json($directory);
    }

    echo "<pre>";
    print_r($content_json);
    echo "</pre>";
    //file_put_contents(''.$directory.'/info.json', $content_json);

}
else{
   echo "<p style='color: red'>Thư mục này chưa tồn tại, hãy kiểm tra lại đường dẫn</p>";
}

//***************************************************************



?>