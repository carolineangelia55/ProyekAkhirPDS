<?php
  require_once "php/connect.php";

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

  $sql = "SELECT * FROM jenis_kejahatan";
  $stmt = $conn->query($sql);
  $jenis = $stmt->fetchAll(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Pattern</title>
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
    <script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>  
    <script src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
    <script>
      $(document).ready(function() {
        $('select').on('change', function() {
          f = $(this).find(":checked").val();
          window.location = 'patternWaktu.php?filter='+f;
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
        img{
          pointer-events: none;
        }
        select {
          border-radius:5px;
          padding:2px 5px;
          float:right;
          background-color:transparent;
          margin-right:20px;
          position:relative;
          z-index:1;

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
          <a href="inputGaleri.php">
            <i class='bx bx-street-view' ></i>
            <span class="links_name">Crime Data</span>
          </a>
        </li>
        <li>
          <a href="lihatDaerah.php">
            <i class='bx bx-current-location' ></i>
            <span class="links_name">Region Data</span>
          </a>
        </li>
        <li>
          <a href="inputJadwal.php" class="active">
            <i class='bx bx-ghost' ></i>
            <span class="links_name">Find Crime Pattern</span>
          </a>
        </li>
        <li>
          <a href="biodataPendeta.php">
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
        <span class="dashboard">Crime Pattern</span>
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
        <h3>Pattern Based on Offence</h3>
        <select name="filter" id="filter">
          <option id="filter1" value="1">Percentage Chart</option>
          <option id="filter2" value="2" <?php if (isset($_GET['filter'])) { if ($_GET['filter']==2) { echo 'selected'; }}?>>Comparasion Chart</option>
        </select>
        <div id="chartContainer" style="height: 300px; width: 100%; position: relative;"></div>

            <table id="example" class="table table-striped" style="width:100%; text-align: center;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Crime Type</th>
                        <th>Total Cases</th>
                        <th>Mostly at Shift</th>
                    </tr>
                </thead>
                <tbody id="isiTabel" class="satu">
                  <?php 
                      // Daftar value offense yang akan dihitung
                      $valueOffenses = range(1, 9);

                      // Membuat array untuk menyimpan hasil count dan value shift terbanyak
                      $countByOffense = [];
                      $mostFrequentShiftByOffense = [];

                      // Melakukan perulangan untuk setiap value offense
                      $count = 0;
                      $hasil = [];
                      foreach ($valueOffenses as $offense) {
                          $count = $count + 1;
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

                          // Menyimpan jumlah total kasus pada shift tsb
                          $totalKasus[$offense] = $shiftCount[$mostFrequentShift]; 

                          // Menyimpan hasil count dan value shift terbanyak
                          $countByOffense[$offense] = count($resultArray);
                          $mostFrequentShiftByOffense[$offense] = $mostFrequentShift;
                          // Menampilkan baris tabel untuk setiap value offense
                          
                          include 'koneksi.php';
                          $sqlcrime = 'SELECT * FROM jenis_kejahatan WHERE idjenis='.$offense;
                          $stmtcrime = $sambung->query($sqlcrime);
                          while($data = mysqli_fetch_array($stmtcrime))
                          {
                            $row['nama'] = $data['nama'];
                            echo "<tr>";
                            echo "<td>$count</td>";
                            echo "<td>".$data['nama']."</td>";
                          }
                          $row['total'] = $countByOffense[$offense];
                          $row['shift'] = $mostFrequentShiftByOffense[$offense];
                          $row['jumlah'] = $totalKasus[$offense];
                          echo "<td>" . $countByOffense[$offense] . "</td>";
                          echo "<td>" . $mostFrequentShiftByOffense[$offense] . "</td>";
                          echo "</tr>";
                          array_push($hasil, $row);
                      }
                  ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php
          // Daftar shift yang akan dihitung
          $shifts = ['MIDNIGHT', 'DAY', 'EVENING'];

          // Membuat array untuk menyimpan top 3 value offense per shift
          $topOffenses = [];

          // Melakukan perulangan untuk setiap shift
          foreach ($shifts as $shift) {
            // Membuat objek Query untuk mencari jumlah dokumen dengan kombinasi shift dan offense
            $query = new Query([
              'SHIFT' => $shift
            ]);

            // Menjalankan query dan mengambil hasil
            $cursor = $manager->executeQuery("$database.$collection", $query);
            $resultArray = iterator_to_array($cursor);

            // Menghitung jumlah offense per shift
            $offenseCount = array_count_values(array_column($resultArray, 'OFFENSE'));

            // Mengurutkan offense berdasarkan jumlah
            arsort($offenseCount);

            // Mengambil top 3 value offense
            $topOffenses[$shift] = array_slice($offenseCount, 0, 3, true);
          }

          ?>


        <div class="table-responsive" style="padding-top:100px;">
            <div style="overflow-x: auto;">

            <h3>Pattern Based on Shift</h3>

          <h4>Top 3 Crime Types per Shift</h4>
          <?php foreach ($shifts as $shift) : ?>
            <h5>Shift: <?php echo $shift; ?></h5>
            <table id="example" class="table table-striped" style="width:100%; text-align: center;">
            <thead>
              <tr>
                <th>No</th>
                <th>Crime Type</th>
                <th>Total Cases (per Shift)</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($topOffenses[$shift] as $offense => $count) : ?>
                <?php 
                  include 'koneksi.php';
                  $sqloff = 'SELECT * FROM jenis_kejahatan WHERE idjenis='.$offense;
                  $stmtoff = $sambung->query($sqloff);
                  while($data = mysqli_fetch_array($stmtoff))
                  {
                ?>
                <tr>
                  <td><?php echo $no; $no = $no + 1; ?></td>
                  <td><?php echo $data['nama']; ?></td>
                  <?php } ?>
                  <td><?php echo $count; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            </table>
          <?php endforeach; ?>

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
<script>
      filter = $('#filter').find(":checked").val();
      if (filter == 1) {
        contentVar = '{name} </br> <strong>Percentage: </strong> </br> Total: {y[0]}%';
        arr = [];
        <?php foreach ($hasil as $data) { ?>
          y1 = (parseInt("<?php echo $data['jumlah'];?>")/parseInt("<?php echo $data['total'];?>")*100);
          y2 = 0;
          row = {};
          row['label'] = "<?php echo $data['nama'];?>";
          row['y'] = [y1, y2];
          row['name'] = "<?php echo $data['shift'];?>";
          arr.push(row)
        <?php } ?>
        
        formatName1 = "";
        formatName2 = "";
        ending = "%";
        max = 100;
      } else {
        contentVar = '{name} </br> <strong>Cases: </strong> </br> Total: {y[0]}, Jumlah: {y[1]}';
        arr = [];
        <?php foreach ($hasil as $data) { ?>
          y1 = parseInt("<?php echo $data['jumlah'];?>");
          y2 = parseInt("<?php echo $data['total'];?>");
          row = {};
          row['label'] = "<?php echo $data['nama'];?>";
          row['y'] = [y1, y2];
          row['name'] = "<?php echo $data['shift'];?>";
          arr.push(row)
        <?php } ?>
        formatName1 = "Total ";
        formatName2 = "Jumlah ";
        ending = "";
        max = 60000;
      }
      window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {  
          backgroundColor: 'transparent',          
          title:{
            text: "Crime Type Pattern"              
          },
          axisY: {
            maximum: max,
            gridThickness: 0
          },
          toolTip:{
            shared: true,
            content: contentVar
          },
          data: [{
            type: "rangeSplineArea",
            fillOpacity: 0.1,
            color: "#5a0303",
            indexLabelFormatter: formatter,
            dataPoints: arr
          }]
        });
        chart.render();

        var images = [];    

        addImages(chart);

        function addImages(chart) {
          for(var i = 0; i < chart.data[0].dataPoints.length; i++){
            var dpsName = chart.data[0].dataPoints[i].name;
            if(dpsName == "EVENING"){
              images.push($("<img>").attr("src", "assets/night.png"));
            } else if(dpsName == "DAY"){
            images.push($("<img>").attr("src", "assets/day.png"));
            } else if(dpsName == "MIDNIGHT"){
              images.push($("<img>").attr("src", "assets/midnight.png"));
            }

          images[i].attr("class", dpsName).appendTo($("#chartContainer>.canvasjs-chart-container"));
          positionImage(images[i], i);
          }
        }

        function positionImage(image, index) {
          var imageCenter = chart.axisX[0].convertValueToPixel(chart.data[0].dataPoints[index].x);
          var imageTop =  chart.axisY[0].convertValueToPixel(chart.axisY[0].maximum);

          image.width("40px")
          .css({ "left": imageCenter - 20 + "px",
          "position": "absolute","top":imageTop + "px",
          "position": "absolute"});
        }

        $( window ).resize(function() {
          var cloudyCounter = 0, rainyCounter = 0, sunnyCounter = 0;    
          var imageCenter = 0;
          for(var i=0;i<chart.data[0].dataPoints.length;i++) {
            imageCenter = chart.axisX[0].convertValueToPixel(chart.data[0].dataPoints[i].x) - 20;
            if(chart.data[0].dataPoints[i].name == "EVENING") {					
              $(".cloudy").eq(cloudyCounter++).css({ "left": imageCenter});
            } else if(chart.data[0].dataPoints[i].name == "DAY") {
              $(".rainy").eq(rainyCounter++).css({ "left": imageCenter});  
            } else if(chart.data[0].dataPoints[i].name == "MIDNIGHT") {
              $(".sunny").eq(sunnyCounter++).css({ "left": imageCenter});  
            }                
          }
        });

        function formatter(e) { 
          if(e.index === 0 && e.dataPoint.x === 0) {
            return formatName1 + e.dataPoint.y[e.index] + ending;
          } else if(e.index == 1 && e.dataPoint.x === 0) {
            return formatName2 + e.dataPoint.y[e.index];
          } else{
            return e.dataPoint.y[e.index];
          }
        } 

      }
    </script>	
</body>
</html>