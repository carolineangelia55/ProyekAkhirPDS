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
    $negara = $_POST["negara"];
    $daerah = $_POST["daerah"];
    $alamat = $_POST["alamat"];
    $tanggal_kejadian = $_POST["tanggalkejadian"];
    $shift = $_POST["shift"];
    $tanggal_selesai = $_POST["tanggalselesai"];
    $kasusSelesai = isset($_POST["kasusSelesai"]) ? $_POST["kasusSelesai"] : "false";
    $tanggal_report = date("Y-m-d H:i:s");
    $start_date = date("Y-m-d H:i:s");


    require_once 'koneksi.php';
    session_start();   
    mysqli_query($sambung, "INSERT INTO daerah (`daerah`, `negara`) 
    VALUES ('$daerah','$negara')");
    $iddaerah = mysqli_insert_id($sambung);
    $sambung->close();

    // Prepare the document to be inserted
    $document = [
        'REPORT_DATE' => $tanggal_report,
        'OFFENSE' => $jenis_kejahatan,
        'REGION' => $iddaerah,
        'BLOCK' => $alamat,
        'DATE' => $tanggal_kejadian,
        'SHIFT' => $shift,
        'IS_SOLVED' => $kasusSelesai,
        'START_DATE' => $start_date,
        'DATE_SOLVED' => $tanggal_selesai,
    ];


    if (isset($_POST["inputJudul"])) {
        $inputJudul = $_POST["inputJudul"];

        if (isset($_POST["inputField"])) {
            $inputField = $_POST["inputField"];

               // Add each inputJudul and inputField as a sub-document in the informasi_lainnya array
                for ($i = 0; $i < count($inputJudul); $i++) {
                    $fieldName = $inputJudul[$i];
                    $fieldValue = $inputField[$i];

                    // Add the field name and value directly to the document
                    $document[$fieldName] = $fieldValue;
                }

        } 

    } 

    // Insert the document into the collection
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($document);
    $mongo->executeBulkWrite("$databaseName.$collectionName", $bulk);

    echo "<script>alert('Kasus berhasil diinput!'); window.location.href = 'addKasus.php';</script>";
}
?>
