<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$count = 0;

foreach($ite as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        $replaced = preg_replace('/\{\{ asset\(\'css\/estilos\.css\'\)\s*\}\}(?!\?v=)/', '{{ asset(\'css/estilos.css\') }}?v={{ time() }}', $content);
        
        if ($replaced !== null && $replaced !== $content) {
            file_put_contents($path, $replaced);
            $count++;
            echo "Updated: $path\n";
        }
    }
}

echo "Total files updated: $count\n";
?>
