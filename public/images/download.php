<?php

$images = [
    'bmw_320i.jpg' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&q=80&w=800',
    'honda_vario.jpg' => 'https://images.unsplash.com/photo-1625047509168-a7026f36de04?auto=format&fit=crop&q=80&w=800',
    'box_truck_real.jpg' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&q=80&w=800',
    'pickup_truck_real.jpg' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800',
    'courier_motorcycle_real.jpg' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&q=80&w=800'
];

$options = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
    ]
];

$context = stream_context_create($options);

foreach ($images as $filename => $url) {
    echo "Downloading {$filename}... ";
    $data = @file_get_contents($url, false, $context);
    if ($data === false) {
        echo "FAILED\n";
    } else {
        file_put_contents(__DIR__ . '/' . $filename, $data);
        echo "SUCCESS (" . strlen($data) . " bytes)\n";
    }
}
echo "Done!\n";
