<?php

$album = !empty($_GET['album']) ? $_GET['album']  : "";

$mediaDirectory = __DIR__ . '/media/' . $album;

$mediaFiles = array_filter(scandir($mediaDirectory), function ($file) use ($mediaDirectory) {
    return strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'mp3' && is_file($mediaDirectory . '/' . $file);
});

header('Content-Type: application/json');
echo json_encode(array_values($mediaFiles));
