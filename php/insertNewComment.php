<?php
require_once "connect.php";
use MongoDB\BSON\ObjectID;
// MongoDB connection
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$documentId = new MongoDB\BSON\ObjectId($_POST['artikel']);
$tags = [];
if (isset($_POST['tags'])) {
  if (count($_POST['tags'])) {
    $tags = $_POST['tags'];
  }
} 
// Data to be inserted
$data = array(
    'user' => $_SESSION['user'],
    'komentar' => $_POST['komentar'],
    'tanggal' => date("Y-m-d H:i:s"),
    'artikel' => $documentId,
    'tags' => $tags
);

// Collection and insert options
$collectionName = "komentar";
$options = array(
    'w' => 1 // Write concern level
);

// Construct the insert command
$bulk = new MongoDB\Driver\BulkWrite();
$bulk->insert($data);

$jumlahTags = [];
$result = $manager->executeBulkWrite("pds.".$collectionName, $bulk, $options);
$filter = ['artikel' => $documentId];
$options = [];
$query2 = new MongoDB\Driver\Query($filter, $options);
$isi = $manager->executeQuery("pds.komentar", $query2);
$data = [];
foreach ($isi as $document) {
  $data[] = $document;
}
foreach ($data as $d) {
  foreach ($d->tags as $tag) {
    if (!isset($jumlahTags[$tag])) {
      $jumlahTags[$tag] = 0;
    }
    $jumlahTags[$tag] += 1;  
  }
}
$hasil = [];
arsort($jumlahTags);
$count = 0;
foreach ($jumlahTags as $key=>$value) {
  array_push($hasil, $key);
  if ($count > 1) {
    break;
  }
  $count++;
}
// Specify the filter to match the document(s) you want to update
$filter = ['_id' => $documentId];

// Specify the update operation using the $set operator and $exists operator
$update = [
    '$set' => [
        'tags' => $hasil
    ]
];

// Specify any additional options, if needed
$options = ['multi' => false];

// Create an instance of the MongoDB\Driver\BulkWrite class
$bulk = new MongoDB\Driver\BulkWrite;

// Add the update operation to the BulkWrite
$bulk->update($filter, $update, $options);

// Execute the BulkWrite using the Manager
$result = $manager->executeBulkWrite("pds.kasus", $bulk);
?>