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
    // while ($row = mysqli_fetch_assoc($result)) {
    //             $jenisKejahatanValues[] = $row['nama'];
    // }

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
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
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
        function submitFilter() {
          jenis = document.getElementById('jenis_kejahatan').value;
          waktu = document.getElementById('waktu').value;
          link = "lihatDaerah.php";
          cek = 0;
          if (jenis != 0) {
            link += "?jenis="+jenis;
            cek += 1;
          } 
          if (waktu != "") {
            if (cek==0) {
              link += "?";
            } else {
              link += "&";
            }
            link += "date="+waktu;
          }
          window.location = link;
        }
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
        select, input {
          border-radius:5px;
          padding:2px 5px;
        }
        .buttonSubmit {
          padding: 5px 15px;
          background-color:transparent;
          color:black;
          border: 1px solid black;
          border-radius:20px;
          margin-left:10px;
        }
        .buttonSubmit:hover {
          color:white;
          background-color:black;
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

                <label for="jenis_kejahatan">Criminal Types: </label>
                <select name="jenis_kejahatan" id="jenis_kejahatan">
                    <option value="0">All</option>
                    <?php
                    // Populate the select options from the jenisKejahatanValues array
                    foreach ($result as $jenisKejahatan) {
                        echo '<option value="' . $jenisKejahatan['idjenis'] . '"';
                        if(isset($_GET['jenis'])) { 
                          if($_GET['jenis']==$jenisKejahatan['idjenis']) { 
                            echo ' selected'; 
                          }
                        }
                        echo '>' . $jenisKejahatan['nama'] . '</option>';
                    }
                    ?>
                </select>

                <label for="waktu">Date : </label>
                <input type="date" name="waktu" id="waktu" value="<?php if(isset($_GET['date'])) { echo $_GET['date']; } ?>">

                <button class="buttonSubmit" value="Filter" onclick="submitFilter()">Submit</button>

            <br><br>
            <div id="chartContainer" style="height: 300px; width: 100%;"></div>
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
                    $filter = [];
                    if (isset($_GET['jenis'])) {
                      $filter['OFFENSE'] = intval($_GET['jenis']);
                    }
                    $options = [];
                    $query = new MongoDB\Driver\Query($filter, $options);
                    $cursor = $manager->executeQuery("$database.$collection", $query);
                    $resultArray = iterator_to_array($cursor);

                    $offenseValues = array_unique(array_column($resultArray, 'OFFENSE'));
                    $regionValues = array_unique(array_column($resultArray, 'REGION'));

                    $countArray = array();

                    foreach ($offenseValues as $offense) {
                        foreach ($regionValues as $region) {
                            if (isset($_GET['date'])) {
                              $startDate = $_GET['date'] . ' 00:00:00';
                              $endDate = $_GET['date'] . ' 23:59:59';
                              $filter['date'] = [
                                '$gte' => $startDate,
                                '$lte' => $endDate
                              ];
                            }
                            $filter['REGION'] = $region;
                            $filter['OFFENSE'] = $offense;
                            $query = new MongoDB\Driver\Query($filter, $options);
                            $cursor = $manager->executeQuery("$database.$collection", $query);
                            $resultArray = iterator_to_array($cursor);
                            $count = count($resultArray);

                            $countArray["$offense-$region"] = $count;
                        }
                    }

                    arsort($countArray);
                    $chartData = [];
                    foreach ($countArray as $key => $count) {
                        list($offense, $region) = explode("-", $key);

                        $query = new Query(['OFFENSE' => $offense, 'REGION' => $region]);
                        $cursor = $manager->executeQuery("$database.$collection", $query);
                        $resultArray = iterator_to_array($cursor);

                        $sqldaerah = 'SELECT * FROM daerah d JOIN negara n ON d.negara=n.idnegara WHERE iddaerah=' . $region;
                        $stmtdaerah = $sambung->query($sqldaerah);

                        $sqljenis = 'SELECT * FROM jenis_kejahatan WHERE idjenis=' . $offense;
                        $stmtjenis = $sambung->query($sqljenis);

                        while (($datadaerah = mysqli_fetch_array($stmtdaerah)) && ($datajenis = mysqli_fetch_array($stmtjenis))) {
                            $hitung = $hitung + 1;
                            $namaDaerah = $datadaerah['daerah'] . ", ".$datadaerah['namanegara'];
                            if (isset($chartData[$namaDaerah])) {
                              $chartData[$namaDaerah] += $count;
                            } else {
                              $chartData[$namaDaerah] = $count;
                            }
                            echo "<tr>";
                            echo "<td>" . $hitung . "</td>";
                            echo "<td>" . $namaDaerah ."</td>";
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
      window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
  backgroundColor: 'transparent',
	animationEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title: {
		text: "Comparasion Chart"
	},
	axisY: {
		title: "Total Cases"
	},
	axisX: {
		title: "Region"
	},
	data: [{
		type: "column",
		dataPoints: [
      <?php 
      $count = 0;
      foreach ($chartData as $key => $data) { 
        if ($count != 0) {
          echo ", ";
        }
			  echo '{ label: "'.$key.'", y: '.$data.' }';	
        $count++;
      } ?>
			
		]
	}]
});
chart.render();

}
</script>
</body>
</html>