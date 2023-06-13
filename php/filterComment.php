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
      'sort' => ['tanggal' => $sort],
      'limit' => $_POST['jumlahBatas']
    ];
    $query2 = new MongoDB\Driver\Query($filter, $options);
    $komen = $manager->executeQuery("pds.komentar", $query2);
    $resultArray = [];
    foreach ($komen as $document) {
        $resultArray[] = $document;
    }    
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