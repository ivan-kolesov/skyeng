<?php declare(strict_types = 1);

$filename = 'counter.txt';

if (!file_exists($filename)) {
    touch($filename);
}

$handle = fopen($filename, 'r+');
if (flock($handle, LOCK_EX)) {
    $fileSize = filesize($filename);
    $counter = $fileSize > 0 ? fread($handle, $fileSize) : 0;
    $counter++;
    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, $counter);
    flock($handle, LOCK_UN);
}

fclose($handle);