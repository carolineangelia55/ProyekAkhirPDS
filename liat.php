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

// Daftar shift yang akan dihitung
$shifts = ['MIDNIGHT', 'DAY', 'EVENING'];

// Melakukan perulangan untuk setiap shift
foreach ($shifts as $shift) {
    echo "Shift: $shift\n";
    
    // Melakukan perulangan untuk setiap offense
    for ($offense = 1; $offense <= 9; $offense++) {
        // Membuat objek Query untuk mencari jumlah dokumen dengan kombinasi shift dan offense
        $query = new Query([
            'SHIFT' => $shift,
            'OFFENSE' => $offense
        ]);
        
        // Menjalankan query dan mengambil hasil
        $cursor = $manager->executeQuery("$database.$collection", $query);
        $count = count(iterator_to_array($cursor));
        
        // Menampilkan jumlah dokumen dengan kombinasi shift dan offense
        echo "OFFENSE = $offense: $count\n";
    }
    
    echo "\n"; // Cetak baris kosong setelah setiap shift
}
?>
