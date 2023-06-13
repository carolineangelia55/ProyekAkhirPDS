<?php
// Include the MongoDB PHP library
require '../vendor/autoload.php';

use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\BSON\Regex;

// Create a MongoDB client
$mongo = new MongoDB\Driver\Manager('mongodb://localhost:27017');

// Select the database and collection
$databaseName = 'pds';
$collectionName = 'kasus';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $jenis_kejahatan = $_POST["jenis_kejahatan"];
    $alamat = $_POST["alamat"];
    $tanggal_kejadian = $_POST["tanggalkejadian"];
    $shift = $_POST["shift"];
    $is_selesai = isset($_POST["tanggalselesai"]) ? true : false;
    $tanggal_selesai = $_POST["tanggalselesai"];
    $inputJudul = $_POST["inputJudul"];
    $inputField = $_POST["inputField"];

    // Prepare the document to be inserted
    $document = [
        'offense' => $jenis_kejahatan,
        'block' => $alamat,
        'report_date' => $tanggal_kejadian,
        'shift' => $shift,
        'is_selesai' => $is_selesai,
        'tanggal_selesai' => $tanggal_selesai,
        'informasi_lainnya' => []
    ];

   // Add each inputJudul and inputField as a sub-document in the informasi_lainnya array
    for ($i = 0; $i < count($inputJudul); $i++) {
        $fieldName = $inputJudul[$i];
        $fieldValue = $inputField[$i];

        // Add the field name and value directly to the document
        $document[$fieldName] = $fieldValue;
    }

    // Insert the document into the collection
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($document);
    $mongo->executeBulkWrite("$databaseName.$collectionName", $bulk);

    echo "<script>alert('Kasus berhasil diinput!'); window.location.href = 'addKasus.php';</script>";
}
?>
