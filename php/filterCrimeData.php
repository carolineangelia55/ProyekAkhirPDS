<?php
    require_once "connect.php";
    header('Content-Type: application/json');
    use MongoDB\BSON\ObjectID;
    // MongoDB connection
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [];
    $sql = "SELECT * FROM daerah WHERE daerah = '".$_POST['daerah']."'";
    $stmt = $conn->query($sql);
    $daerah = $stmt->fetch();
    $filter['REGION'] = intval($daerah['iddaerah']); 
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery("pds.kasus", $query);
    $sql = "SELECT * FROM jenis_kejahatan";
    $stmt = $conn->query($sql);
    $jenis = $stmt->fetchAll();
    $jumlahKejahatan = [];
    foreach ($cursor as $document) {
        if (isset($jumlahKejahatan[$document->OFFENSE])) {
            $jumlahKejahatan[$document->OFFENSE]['jumlah'] += 1;
        } else {
            $jumlahKejahatan[$document->OFFENSE]['nama'] = $jenis[$document->OFFENSE-1]['nama'];
            $jumlahKejahatan[$document->OFFENSE]['jumlah'] = 1;
        }
    }
    echo json_encode($jumlahKejahatan);
?>