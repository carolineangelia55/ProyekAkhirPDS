<?php
    require_once "connect.php";
    $sql = "SELECT * FROM daerah WHERE negara = ".$_POST['negara'];
    $stmt = $conn->query($sql);
    $daerah = $stmt->fetchAll();
    $isi = '<label for="daerah" style="margin-right:10px; margin-bottom:10px;">Daerah: </label>
    <select name="daerah" id="daerah">
    <option id="daerah0" value="0">--Pilih Daerah--</option>';
    foreach ($daerah as $data) {
        $isi .= '<option id="daerah'.$data['iddaerah'].'" value="'.$data['iddaerah'].'">'.$data['daerah'].'</option>';
    }
    $isi .= '</select>';
    echo $isi;
?>