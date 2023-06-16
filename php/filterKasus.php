<?php
    require_once "connect.php";
    use MongoDB\BSON\ObjectID;
    // MongoDB connection
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [];
    $sql = "SELECT * FROM jenis_kejahatan";
    $stmt = $conn->query($sql);
    $jenis = $stmt->fetchAll(); 
    if (isset($_POST['tags'])) {
        $filter['tags'] = ['$all'=>$_POST['tags']];
    }
    if ($_POST['jenis'] != 0) {
        $filter['OFFENSE'] = intval($_POST['jenis']); 
    }
    if ($_POST['daerah'] != 0) {
        $filter['REGION'] = intval($_POST['daerah']); 
    } else {
        if ($_POST['negara'] != 0) {
            $sql = "SELECT * FROM daerah WHERE negara = ".$_POST['negara'];
            $stmt = $conn->query($sql);
            $daerah = $stmt->fetchAll(); 
            $daftarDaerah = [];
            foreach ($daerah as $data) {
                array_push($daftarDaerah, intval($data['iddaerah']));
            }
            if (count($daftarDaerah) != 0) {
                $filter['REGION'] = ['$in'=>$daftarDaerah];
            } else {
                echo "";
                exit;
            }
        }
    }
    if ($_POST['lokasi'] != "") {
        $filter['BLOCK'] = ['$regex' => $_POST['lokasi'], '$options' => 'i']; 
    }
    if ($_POST['tanggalKejadian'] != "") {
        $filter['date'] = [
            '$gte' => $_POST['tanggalKejadian'].' 00:00:00',
            '$lt' => $_POST['tanggalKejadian'].' 24:00:00'
        ];
    }
    if ($_POST['tanggalLapor'] != "") {
        $formattedDate = date("j/n/Y", strtotime($_POST['tanggalLapor']));
        $filter['REPORT_DATE'] = [
              '$regex' => '^'.preg_quote($formattedDate, '/')
        ];
    }
    $sort = 0;
    if ($_POST['sort'] == 1) {
        $sort = -1;
    } else {
        $sort = 1;
    }
    $options = [
        'sort' => ['REPORT_DATE' => $sort],
        'limit' => 500
    ];
    $query2 = new MongoDB\Driver\Query($filter, $options);
    $data = $manager->executeQuery("pds.kasus", $query2);
    $dataKasus = [];
    foreach ($data as $document) {
        $dataKasus[] = $document;
    }    
    $isi = '';
    $i = 1; 
    if (count($dataKasus)>0) {
        foreach ($dataKasus as $data) {
            $temp = $i;
            $link = 'window.location.href="kasus.php?id='.$data->_id.'"';
            $isi .= "<tr onmouseover='hoverRow(".$temp.")' onmouseout='unhoverRow(".$temp.")' onclick='".$link.
            "'>
            <td id='Angka".$temp."'>".$i++."</td>
            <td id='Nama".$temp."'>".$jenis[$data->OFFENSE-1]['nama']." at ".$data->BLOCK."</td>
            <td id='Tgl".$temp."'>".$data->REPORT_DATE."</td>
            </tr>
            ";
        }
    }
    echo $isi;
?>