<?php
    require_once "connect.php";
    use MongoDB\BSON\ObjectID;
    // MongoDB connection
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $documentId = new MongoDB\BSON\ObjectId($_POST['artikel']);
    $filter = [
        'artikel' => $documentId
    ];
    if (isset($_POST['tags'])) {
        $filter['tags'] = ['$all'=>$_POST['tags']];
    }
    if ($_POST['startDate'] != "") {
        $filter['tanggal']['$gte'] = $_POST['startDate']; 
    }
    if ($_POST['endDate'] != "") {
        $filter['tanggal']['$lte'] = $_POST['endDate']; 
    }
    $sort = 0;
    if ($_POST['sort']==1) {
        $sort = -1;
    } else {
        $sort = 1;
    }
    $options = [
      'sort' => ['tanggal' => $sort]
    ];
    $query2 = new MongoDB\Driver\Query($filter, $options);
    $komen = $manager->executeQuery("pds.komentar", $query2);
    $resultArray = [];
    foreach ($komen as $document) {
        $resultArray[] = $document;
    }    
    echo count($resultArray);
?>