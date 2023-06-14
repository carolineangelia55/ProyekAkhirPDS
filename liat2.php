<!DOCTYPE html>
<html>
<head>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      text-align: left;
      padding: 8px;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>
  <table>
    <tr>
      <th>Value Offense</th>
      <th>Count</th>
      <th>Most Frequent Shift</th>
    </tr>
    <?php
    // Mengimpor kelas-kelas yang diperlukan
    use MongoDB\Driver\Manager;
    use MongoDB\Driver\Query;

    // Mendefinisikan konfigurasi koneksi MongoDB
    $server = "mongodb://localhost:27017";
    $database = "pds";
    $collection = "kasus";

    // Membuat objek Manager untuk koneksi MongoDB
    $manager = new Manager($server);

    // Daftar value offense yang akan dihitung
    $valueOffenses = range(1, 9);

    // Membuat array untuk menyimpan hasil count dan value shift terbanyak
    $countByOffense = [];
    $mostFrequentShiftByOffense = [];

    // Melakukan perulangan untuk setiap value offense
    foreach ($valueOffenses as $offense) {
        // Membuat objek Query untuk mencari jumlah dokumen dengan kombinasi offense dan shift
        $query = new Query(['OFFENSE' => $offense]);

        // Menjalankan query dan mengambil hasil
        $cursor = $manager->executeQuery("$database.$collection", $query);
        $resultArray = iterator_to_array($cursor);

        // Menghitung jumlah offense per shift
        $shiftCount = array_count_values(array_column($resultArray, 'SHIFT'));

        // Mengurutkan shift berdasarkan jumlah
        arsort($shiftCount);

        // Mengambil shift dengan jumlah terbanyak
        $mostFrequentShift = array_key_first($shiftCount);

        // Menyimpan hasil count dan value shift terbanyak
        $countByOffense[$offense] = count($resultArray);
        $mostFrequentShiftByOffense[$offense] = $mostFrequentShift;
        
        // Menampilkan baris tabel untuk setiap value offense
        echo "<tr>";
        echo "<td>$offense</td>";
        echo "<td>" . $countByOffense[$offense] . "</td>";
        echo "<td>" . $mostFrequentShiftByOffense[$offense] . "</td>";
        echo "</tr>";
    }
    ?>
  </table>
</body>
</html>
