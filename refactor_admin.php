<?php
$dir = new RecursiveDirectoryIterator('resources/views/admin');
$ite = new RecursiveIteratorIterator($dir);
foreach($ite as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        $new = str_replace("@extends('layouts.app')", "@extends('layouts.admin')", $content);
        if ($new !== $content) {
            file_put_contents($path, $new);
            echo "Updated: $path\n";
        }
    }
}
// Also update admin-votes.blade.php
$path = 'resources/views/admin-votes.blade.php';
if (file_exists($path)) {
    $content = file_get_contents($path);
    $new = str_replace("@extends('layouts.app')", "@extends('layouts.admin')", $content);
    if ($new !== $content) {
        file_put_contents($path, $new);
        echo "Updated: $path\n";
    }
}
?>
