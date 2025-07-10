<?php
// watch_assets.php
$paths = [
    'css' => ['src' => __DIR__ . '/../resources/css', 'dest' => __DIR__ . '/../public/css'],
    'js'  => ['src' => __DIR__ . '/../resources/js',  'dest' => __DIR__ . '/../public/js'],
];

// Track modification times
$mtimes = [];
foreach ($paths as $type => $cfg) {
    $files = glob("{$cfg['src']}/*.{$type}");
    foreach ($files as $file) {
        $mtimes[$file] = filemtime($file);
    }
}

echo "Watching resources for changes...
";
while (true) {
    foreach ($paths as $cfg) {
        $files = glob("{$cfg['src']}/*");
        foreach ($files as $file) {
            $mtime = filemtime($file);
            if (!isset($mtimes[$file]) || $mtime > $mtimes[$file]) {
                $dest = $cfg['dest'] . '/' . basename($file);
                if (!is_dir($cfg['dest'])) mkdir($cfg['dest'], 0755, true);
                copy($file, $dest);
                echo "Synced: " . basename($file) . PHP_EOL;
                $mtimes[$file] = $mtime;
            }
        }
    }
    usleep(500000); // Poll twice per second
}