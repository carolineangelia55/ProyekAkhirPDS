<?php
require_once "connect.php";
use MongoDB\BSON\ObjectID;
// MongoDB connection
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$documentId = new MongoDB\BSON\ObjectId($_POST['artikel']);

// Data to be inserted
$data = array(
    'user' => $_SESSION['user'],
    'komentar' => $_POST['komentar'],
    'tanggal' => date("Y-m-d h:i:s"),
    'artikel' => $documentId,
    'tags' => ['Need Validation']
);

// Collection and insert options
$collectionName = "komentar";
$options = array(
    'w' => 1 // Write concern level
);

// Construct the insert command
$bulk = new MongoDB\Driver\BulkWrite();
$bulk->insert($data);

// Execute the insert command
$result = $manager->executeBulkWrite("pds.".$collectionName, $bulk, $options);

$filter = ['artikel' => $documentId];
$options = [
  'sort' => ['tanggal' => -1],
  'limit' => 10
];
$query2 = new MongoDB\Driver\Query($filter, $options);
$komen = $manager->executeQuery("pds.komentar", $query2);
$resultArray = [];
foreach ($komen as $document) {
    $resultArray[] = $document;
}
$resultArray = array_reverse($resultArray);

$isi = '';
foreach ($resultArray as $data) { 
    $sql = "SELECT * FROM user WHERE iduser = ".$data->user; 
    $stmt = $conn->query($sql);
    $user = $stmt->fetch(); 
    $check = True;
    $komen = str_replace(array("\n"), array("<br>"), $data->komentar);
    if (isset($_SESSION['user'])) {
      if ($data->user == $_SESSION['user']) {
        $isi .= '<h3 style="text-align:right">You ('.$user['nama'].')</h3>
        <p style="font-size:80%; margin-top:-5px; margin-bottom:-5px; text-align:right">('.$data->tanggal.')</p>
        <div class="me-bubble">'.$komen.'
        <br><br>';
        $tags = $data->tags;
        foreach ($tags as $t) { 
            $isi .= '<span class="tags" style="border:1px solid white"><i class="bx bx-purchase-tag"></i>'.$t.'</span>';
        }
        $isi .= '</div>';
        $check = False; 
      }
    } 
    if ($check) {
      $isi .= '<h3>'.$user['nama'].'</h3>
      <p style="font-size:80%; margin-top:-5px; margin-bottom:-5px;">('.$data->tanggal.')</p>
      <div class="speech-bubble">'.$komen.'
      <br><br>';
      $tags = $data->tags;
      foreach ($tags as $t) {
        $isi .= '<span class="tags"><i class="bx bx-purchase-tag"></i>'.$t.'</span>';
      }
      $isi .= '</div>';
    }
} 
echo $isi;
?>