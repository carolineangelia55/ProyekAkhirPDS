<?php

// Menggunakan MongoDB PHP Driver
require '../vendor/autoload.php';

use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;

// Create manager
$manager = new Manager("mongodb://localhost:27017");

// Memilih database dan koleksi
$databaseName = "pds";
$collectionName = "kasus";

// Membuat pipeline agregasi
$pipeline = [
    [
        '$group' => [
            '_id' => [
                'offense' => '$offense',
                'shift' => '$shift'
            ],
            'count' => ['$sum' => 1]
        ]
    ]
];

// Membuat query
$query = new Query($pipeline);

// Menjalankan query
$cursor = $manager->executeQuery("$databaseName.$collectionName", $query);

// Menampilkan hasil
foreach ($cursor as $document) {
    $offense = $document->_id->offense;
    $shift = $document->_id->shift;
    $count = $document->count;
    
    echo "Offense: $offense, Shift: $shift, Count: $count<br>";
}
