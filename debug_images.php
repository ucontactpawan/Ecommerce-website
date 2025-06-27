<?php
// Simple debug script to test image loading
echo "<h1>Debug: Image Loading Test</h1>";

// Test 1: Check if images directory exists
$imagesDir = 'public/images/headphones/';
echo "<h2>Test 1: Directory Check</h2>";
echo "Checking directory: " . $imagesDir . "<br>";
echo "Directory exists: " . (is_dir($imagesDir) ? "YES" : "NO") . "<br>";

// Test 2: List images in headphones directory
echo "<h2>Test 2: Files in headphones directory</h2>";
if (is_dir($imagesDir)) {
    $files = scandir($imagesDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "Found file: " . $file . "<br>";
        }
    }
}

// Test 3: Test loading JSON
echo "<h2>Test 3: JSON Data Test</h2>";
$jsonPath = 'app/Config/products.json';
if (file_exists($jsonPath)) {
    echo "JSON file exists: YES<br>";
    $jsonData = json_decode(file_get_contents($jsonPath), true);
    if (isset($jsonData['headphones'])) {
        echo "Headphones data found: " . count($jsonData['headphones']) . " items<br>";
        echo "First headphone image path: " . $jsonData['headphones'][0]['image'] . "<br>";
    } else {
        echo "No headphones data found in JSON<br>";
    }
} else {
    echo "JSON file not found<br>";
}

// Test 4: Display some images directly
echo "<h2>Test 4: Direct Image Display</h2>";
$testImages = [
    'public/images/headphones/headphone1.jpg',
    'public/images/headphones/headphone2.jpg',
    'public/images/headphones.jpg'
];

foreach ($testImages as $img) {
    echo "<div style='margin: 10px; border: 1px solid #ccc; padding: 10px;'>";
    echo "<p>Testing: " . $img . "</p>";
    echo "<p>File exists: " . (file_exists($img) ? "YES" : "NO") . "</p>";
    if (file_exists($img)) {
        echo "<img src='" . $img . "' style='max-width: 200px; max-height: 150px;' alt='Test Image'>";
    }
    echo "</div>";
}
