<?php
    require_once 'koneksi.php';
    session_start();

    // Mengimpor kelas-kelas yang diperlukan
    use MongoDB\BSON\ObjectID;
    use MongoDB\Driver\Manager;
    use MongoDB\Driver\Query;

    // Mendefinisikan konfigurasi koneksi MongoDB
    $server = "mongodb://localhost:27017";
    $database = "pds";
    $collection = "kasus";

    // Membuat objek Manager untuk koneksi MongoDB
    $manager = new Manager($server);


    // Fetch jenis_kejahatan values from the database
    $query = "SELECT * FROM jenis_kejahatan";
    $result = mysqli_query($sambung, $query);
            
    // Create an array to store the jenis_kejahatan values
    $jenisKejahatanValues = array();
            
    // Fetch and store the jenis_kejahatan values in the array
    while ($row = mysqli_fetch_assoc($result)) {
                $jenisKejahatanValues[] = $row['nama'];
    }

    // // Fetch region values from the database
    // $query12 = "SELECT * FROM daerah";
    // $result12 = mysqli_query($sambung, $query12);
    // $daerahValues = array();
    // // Fetch and store 
    // while ($row = mysqli_fetch_assoc($result12)) {
    //             $daerahValues[] = $row['daerah'];
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Region Data</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/page.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable( {
            dom: "B<'row'<'col-sm-6'l><'col-sm-6'f>>tipr",
                buttons: [
                'copy','csv','excel'
                ],
                buttons: {
                dom: {
                    button:{
                    tag: "button",
                    className: "btn btn-outline-dark mb-3 mx-1 rounded p-2"
                    },
                    buttonLiner: {
                    tag: null
                    }
                }
                },
            });
        });
    </script>
        
    <style>
        .nav-links {
            margin-left:-32px;
        }

        table {
          border-collapse: collapse;
          width: 400px;
        }

        th, td {
          border: 1px solid black;
          padding: 8px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="logo-details">
      <i class='bx bx-target-lock'></i>
      <span class="logo_name">Criminal Report</span>
    </div>
      <ul class="nav-links">
        <li>
          <a href="home.php">
            <i class='bx bx-home-alt' ></i>
            <span class="links_name">Home</span>
          </a>
        </li>
        <li>
          <a href="addKasus.php">
            <i class='bx bx-folder-plus'></i>
            <span class="links_name">Add New Case</span>
          </a>
        </li>
        <li>
          <a href="daftarkasus.php">
            <i class='bx bx-list-ul' ></i>
            <span class="links_name">Cases List</span>
          </a>
        </li>
        <li>
          <a href="lihatKriminalitas.php">
            <i class='bx bx-street-view' ></i>
            <span class="links_name">Crime Data</span>
          </a>
        </li>
        <li>
          <a href="lihatDaerah.php" class="active">
            <i class='bx bx-current-location' ></i>
            <span class="links_name">Region Data</span>
          </a>
        </li>
        <li>
          <a href="patternWaktu.php">
            <i class='bx bx-ghost' ></i>
            <span class="links_name">Find Crime Pattern</span>
          </a>
        </li>
        <li>
          <a href="pemecahanKasus.php">
            <i class='bx bx-edit-alt' ></i>
            <span class="links_name">Case-Solving Data</span>
          </a>
        </li>
        <br>
        <li class="log_out">
          <a href="logout.php">
            <i class='bx bx-log-out'></i>
            <span class="links_name">Log out</span>
          </a>
        </li>
      </ul>
  </div>
  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Region Data</span>
      </div>

      <div class="profile-details" style="padding:10px; position:relative;">
      <i class='bx bx-user-circle' style="color:white; margin-right:5px;"></i>
      <?php echo $_SESSION['nama'];?>
        <i class='bx bx-chevron-down' style="color:white; position:absolute; right:10px;"></i>
      </div>
    </nav>
    <div class="container">
    <div class="table-responsive" style="padding-top:100px;">
        <div style="overflow-x: auto;">

        <h5>Filter</h5>
            <!-- HTML form with the select element -->
            <form method="post" action="#">

                <label for="jenis_kejahatan">Criminal Types: </label>
                <select name="jenis_kejahatan" id="jenis_kejahatan">
                    <option value="">All</option>
                    <?php
                    // Populate the select options from the jenisKejahatanValues array
                    foreach ($jenisKejahatanValues as $jenisKejahatan) {
                        echo '<option value="' . $jenisKejahatan . '">' . $jenisKejahatan . '</option>';
                    }
                    ?>
                </select>

                <label for="waktu">Date : </label>
                <input type="date" name="waktu" id="waktu">

                <input type="submit" value="Filter">
            </form>    

            <br>
        
            <table id="example" class="table table-striped" style="width:100%; text-align: center;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Region</th>
                        <th>Criminal Types</th>
                        <th>Total Cases</th>
                    </tr>
                </thead>
                <tbody id="isiTabel">
                <?php
                    include 'koneksi.php';

                    $hitung = 0;
                    $query = new Query([]);
                    $cursor = $manager->executeQuery("$database.$collection", $query);
                    $resultArray = iterator_to_array($cursor);

                    $offenseValues = array_unique(array_column($resultArray, 'OFFENSE'));
                    $regionValues = array_unique(array_column($resultArray, 'REGION'));

                    $countArray = array();

                    foreach ($offenseValues as $offense) {
                        foreach ($regionValues as $region) {
                            $query = new Query(['OFFENSE' => $offense, 'REGION' => $region]);
                            $cursor = $manager->executeQuery("$database.$collection", $query);
                            $resultArray = iterator_to_array($cursor);
                            $count = count($resultArray);

                            $countArray["$offense-$region"] = $count;
                        }
                    }

                    arsort($countArray);

                    foreach ($countArray as $key => $count) {
                        list($offense, $region) = explode("-", $key);

                        $query = new Query(['OFFENSE' => $offense, 'REGION' => $region]);
                        $cursor = $manager->executeQuery("$database.$collection", $query);
                        $resultArray = iterator_to_array($cursor);

                        $sqldaerah = 'SELECT * FROM daerah WHERE iddaerah=' . $region;
                        $stmtdaerah = $sambung->query($sqldaerah);

                        $sqljenis = 'SELECT * FROM jenis_kejahatan WHERE idjenis=' . $offense;
                        $stmtjenis = $sambung->query($sqljenis);

                        while (($datadaerah = mysqli_fetch_array($stmtdaerah)) && ($datajenis = mysqli_fetch_array($stmtjenis))) {
                            $hitung = $hitung + 1;
                            echo "<tr>";
                            echo "<td>" . $hitung . "</td>";
                            echo "<td>" . $datadaerah['daerah'] . "</td>";
                            echo "<td>" . $datajenis['nama'] . "</td>";
                            echo "<td>" . $count . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>


    </div>
    </section>

<script>
      let sidebar = document.querySelector(".sidebar");
      let sidebarBtn = document.querySelector(".sidebarBtn");
      sidebarBtn.onclick = function() {
      sidebar.classList.toggle("active");
      if(sidebar.classList.contains("active")){
      sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
      }else
      sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      }
</script>
</body>
</html>