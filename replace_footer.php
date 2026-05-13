<?php
$dir = new RecursiveDirectoryIterator('c:\laragon\www\megabonkguide\resources\views');
$ite = new RecursiveIteratorIterator($dir);
foreach($ite as $file) {
    if($file->isFile() && $file->getExtension() === 'php' && strpos($file->getPathname(), 'partials') === false && strpos($file->getPathname(), 'components') === false) {
        $content = file_get_contents($file->getPathname());
        $pattern = '/<footer class=\"main-footer\">[\s\S]*?<\/footer>/';
        if(preg_match($pattern, $content)) {
            $new_content = preg_replace($pattern, '@include(\'partials.footer\')', $content);
            file_put_contents($file->getPathname(), $new_content);
            echo 'Updated: ' . $file->getPathname() . "\n";
        }
    }
}
