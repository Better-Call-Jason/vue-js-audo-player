<?php
//engine to convert names of files for better handing
//pass argument 1 as album name
$album = !empty($argv[1]) ?$argv[1] : "" ;

$dir = '../media/' .$album .'/';
$files = glob($dir . '*');

foreach ($files as $file) {
// rename files
    if (strpos($file, "-") !== false) {
        $newName = str_replace("-", "", $file);
        echo "Renaming {$file} to {$newName}\n";
        rename($file, $newName);
    }

    if (strpos($file, " ") !== false) {
        $new_name = str_replace(" ", "-", $file);
        echo "Renaming {$file} to {$new_name}\n";
        rename($file, $new_name);
    }
}

// If the file is a .m4a file, delete it
//    if (pathinfo($file, PATHINFO_EXTENSION) === 'm4a') {
//        echo "Deleting {$file}\n";
//        unlink($file);
//    }