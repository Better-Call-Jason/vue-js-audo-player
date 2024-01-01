<?php
$dir = '../media/';
$files = glob($dir . '*.m4a');

foreach ($files as $file) {
    $path_parts = pathinfo($file);
    $output = $path_parts['dirname'] . '/' . $path_parts['filename'] . '.mp3';
    echo "Converting {$file} to {$output}\n";
    shell_exec('ffmpeg -i ' . escapeshellarg($file) . ' -acodec libmp3lame -ab 320k ' . escapeshellarg($output));
    echo "Conversion completed for {$file}\n";
}
?>
