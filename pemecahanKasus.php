<?php
    require_once 'php/connect.php';
    if(!isset($_SESSION['email'])){
      header("location: login.php");
      exit;
    }
    use MongoDB\BSON\ObjectID;
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [];
    $judul = '';
    $maintitle = '';
    $cekJenis = FALSE;
    $cekNegara = FALSE;
    $exit = FALSE;
    $sql = "SELECT * FROM jenis_kejahatan";
    $stmt = $conn->query($sql);
    $jenis = $stmt->fetchAll();
    if (isset($_GET['jenis'])) {
        $filter['OFFENSE'] = intval($_GET['jenis']); 
        $cekJenis = TRUE;
    }
    if (isset($_GET['negara'])) {
        $sql = "SELECT * FROM daerah WHERE negara = ".$_GET['negara'];
        $stmt = $conn->query($sql);
        $daerah = $stmt->fetchAll(); 
        $daftarDaerah = [];
        foreach ($daerah as $data) {
            array_push($daftarDaerah, intval($data['iddaerah']));
        }
        if (count($daftarDaerah) != 0) {
            $filter['REGION'] = ['$in'=>$daftarDaerah];
        } else {
          $exit = TRUE;
        }
        $cekNegara = TRUE;
    }
    $isiData = [];
    $options = [];
    $chartData = [];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('pds.kasus', $query);
    $jenisData = [];
    if ($cursor->isDead()) {
      $exit = TRUE;
    }
    if (!$exit) {
      if (($cekJenis == FALSE and $cekNegara == FALSE) or ($cekJenis == FALSE and $cekNegara == TRUE)) {
          foreach ($cursor as $document) {
          if (isset($document->END_DATE)) {
              $startDate = DateTime::createFromFormat('d/m/Y h:i:s A', $document->START_DATE);
              $endDate = DateTime::createFromFormat('d/m/Y h:i:s A',  $document->END_DATE);
              $interval = $startDate->diff($endDate);
              $days = $interval->days;
              $hours = $interval->h;
              $minutes = $interval->i;
              $total = $days*24*60 + $hours*60 + $minutes;
              $date = date("Y", strtotime($document->date));
              if (!isset($chartData[$date])) {
                $chartData[$date]['total'] = intval($total);
                $chartData[$date]['count'] = 1;
              } else {
                $chartData[$date]['total'] += intval($total);
                $chartData[$date]['count'] += 1;
              }
              ksort($chartData);
              if (!isset($jenisData[$document->OFFENSE])) {
                  $jenisData[$document->OFFENSE]['days'] = intval($days); // Inisialisasi elemen array jika belum ada
                  $jenisData[$document->OFFENSE]['hours'] = intval($hours);
                  $jenisData[$document->OFFENSE]['minutes'] = intval($minutes);
                  $jenisData[$document->OFFENSE]['count'] = 1;
              } else {
                  $jenisData[$document->OFFENSE]['days'] += intval($days);
                  $jenisData[$document->OFFENSE]['hours'] += intval($hours);
                  $jenisData[$document->OFFENSE]['minutes'] += intval($minutes);
                  $jenisData[$document->OFFENSE]['count'] += 1;
              }
              if ($cekNegara == TRUE) {
                $sql = "SELECT * FROM negara WHERE idnegara = ".$_GET['negara'];
                $stmt = $conn->query($sql);
                $negara = $stmt->fetch(); 
                $maintitle = 'Cases in the '.$negara['namanegara'];
              } else {
                $maintitle = 'Cases in the World';
              }
          }
          }

          foreach ($jenisData as $key => $data) {
              $total = $data['days']*24*60 + $data['hours']*60 + $data['minutes'];
              $average = $total/$data['count'];
              $row['nama'] = $jenis[$key-1]['nama'];
              $row['average'] = $average;
              array_push($isiData, $row);
          }
          usort($isiData, function($a, $b)
          {
              return $a['average'] - $b['average'];
          });
          $judul = 'Case Name';
      } elseif ($cekJenis == TRUE and $cekNegara == FALSE) {
          foreach ($cursor as $document) {
              if (isset($document->END_DATE)) {
                  $startDate = DateTime::createFromFormat('d/m/Y h:i:s A', $document->START_DATE);
                  $endDate = DateTime::createFromFormat('d/m/Y h:i:s A',  $document->END_DATE);
                  $interval = $startDate->diff($endDate);
                  $days = $interval->days;
                  $hours = $interval->h;
                  $minutes = $interval->i;
                  $sql = "SELECT negara FROM daerah WHERE iddaerah = ".$document->REGION;
                  $stmt = $conn->query($sql);
                  $negara = $stmt->fetch(); 
                  $total = $days*24*60 + $hours*60 + $minutes;
                  $date = date("Y", strtotime($document->date));
                  if (!isset($chartData[$date])) {
                    $chartData[$date]['total'] = intval($total);
                    $chartData[$date]['count'] = 1;
                  } else {
                    $chartData[$date]['total'] += intval($total);
                    $chartData[$date]['count'] += 1;
                  }
                  ksort($chartData);
                  if (!isset($jenisData[$negara['negara']])) {
                      $jenisData[$negara['negara']]['days'] = intval($days); // Inisialisasi elemen array jika belum ada
                      $jenisData[$negara['negara']]['hours'] = intval($hours);
                      $jenisData[$negara['negara']]['minutes'] = intval($minutes);
                      $jenisData[$negara['negara']]['count'] = 1;
                  } else {
                      $jenisData[$negara['negara']]['days'] += intval($days);
                      $jenisData[$negara['negara']]['hours'] += intval($hours);
                      $jenisData[$negara['negara']]['minutes'] += intval($minutes);
                      $jenisData[$negara['negara']]['count'] += 1;
                  }
              }
          }
          foreach ($jenisData as $key => $data) {
              $total = $data['days']*24*60 + $data['hours']*60 + $data['minutes'];
              $average = $total/$data['count'];
              $sql = "SELECT namanegara FROM negara WHERE idnegara=".$key;
              $stmt = $conn->query($sql);
              $daerah = $stmt->fetch();
              $row['nama'] = $daerah['namanegara'];
              $row['average'] = $average;
              array_push($isiData, $row);
          }
          usort($isiData, function($a, $b)
          {
              return $a['average'] - $b['average'];
          });
          $judul = 'Country Name';
          $maintitle = 'Chart of '.$jenis[$_GET['jenis']-1]['nama'].' Cases';
      } elseif ($cekJenis == TRUE and $cekNegara == TRUE) {
        foreach ($cursor as $document) {
            if (isset($document->END_DATE)) {
                $startDate = DateTime::createFromFormat('d/m/Y h:i:s A', $document->START_DATE);
                $endDate = DateTime::createFromFormat('d/m/Y h:i:s A',  $document->END_DATE);
                $interval = $startDate->diff($endDate);
                $days = $interval->days;
                $hours = $interval->h;
                $minutes = $interval->i;
                $total = $days*24*60 + $hours*60 + $minutes;
                $date = date("Y", strtotime($document->date));
                if (!isset($chartData[$date])) {
                  $chartData[$date]['total'] = intval($total);
                  $chartData[$date]['count'] = 1;
                } else {
                  $chartData[$date]['total'] += intval($total);
                  $chartData[$date]['count'] += 1;
                }
                ksort($chartData);
                if (!isset($jenisData[$document->REGION])) {
                    $jenisData[$document->REGION]['days'] = intval($days); // Inisialisasi elemen array jika belum ada
                    $jenisData[$document->REGION]['hours'] = intval($hours);
                    $jenisData[$document->REGION]['minutes'] = intval($minutes);
                    $jenisData[$document->REGION]['count'] = 1;
                } else {
                    $jenisData[$document->REGION]['days'] += intval($days);
                    $jenisData[$document->REGION]['hours'] += intval($hours);
                    $jenisData[$document->REGION]['minutes'] += intval($minutes);
                    $jenisData[$document->REGION]['count'] += 1;
                }
            }
        }
        foreach ($jenisData as $key => $data) {
            $total = $data['days']*24*60 + $data['hours']*60 + $data['minutes'];
            $average = $total/$data['count'];
            $sql = "SELECT daerah, namanegara FROM daerah d JOIN negara n ON d.negara = n.idnegara WHERE iddaerah=".$key;
            $stmt = $conn->query($sql);
            $daerah = $stmt->fetch();
            $row['nama'] = $daerah['daerah'].', '.$daerah['namanegara'];
            $row['average'] = $average;
            array_push($isiData, $row);
        }
        usort($isiData, function($a, $b)
        {
            return $a['average'] - $b['average'];
        });
        $judul = 'Region Name';
        $sql = "SELECT * FROM negara WHERE idnegara = ".$_GET['negara'];
        $stmt = $conn->query($sql);
        $negara = $stmt->fetch(); 
        $maintitle = 'Chart of '.$jenis[$_GET['jenis']-1]['nama'].' Cases in '.$negara['namanegara'];
      }
      if (isset($_GET['sort'])) {
        $isiData = array_reverse($isiData);
      }
    }
    $sql = "SELECT * FROM negara";
    $stmt = $conn->query($sql);
    $negara = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Case-Solving Data</title>

    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="css/page.css">
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
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <style>
        .nav-links {
            margin-left:-32px;
        }
        .buttonSort {
          background-color: transparent;
          padding: 5px 15px;
          border:1px solid black;
          border-radius:10px;
          margin-left:-100px;
          float: right;
          position:relative;
          z-index: 1;
        }
        .sendButton {
          background-color:transparent;
          border:1px solid black;
          padding: 10px 30px;
          border-radius:10px;
          size:150%;
          float:right;
          margin-right:3%;
        }
        .buttonSort:hover, .sendButton:hover {
          background-color:black;
          color:white;
        }
        select {
          border-radius:5px;
          padding:2px 5px;
        }
     </style>
     <script>
        var sort = <?php if (isset($_GET['sort'])) { echo $_GET['sort']; } else { echo 1; }?>;
        window.onload = function () {
          var limit = 100000;
          var y = 100;    
          var data = [];
          var dataSeries = { type: "line" };
          var dataPoints = [];
          <?php foreach ($chartData as $key => $data) { ?>
            dataPoints.push({
              x: <?php echo $key ?>,
              y: <?php echo ($data['total']/$data['count']) ?>
            });
          <?php } ?>
          dataSeries.dataPoints = dataPoints;
          data.push(dataSeries);

          //Better to construct options first and then pass it as a parameter
          var options = {
            backgroundColor: 'transparent',          
            zoomEnabled: true,
            animationEnabled: true,
            title: {
              text: "<?php echo $maintitle ?>"
            },
            axisY: {
              lineThickness: 1
            },
            data: data  // random data
          };

          var chart = new CanvasJS.Chart("chartContainer", options);
          chart.render();

        }
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
        function klikFilter() {
          jenis = document.getElementById('jenis').value;
          negara = document.getElementById('negara').value;
          sort = document.getElementById('sort').value;
          link = 'pemecahanKasus.php';
          flag = 0;
          if (sort != 1) {
            link += '?sort='+sort;
            flag += 1;
          }
          if (jenis != 0) {
            if (flag == 0) {
              link += '?';   
            } else {
              link += '&';
            }
            link += 'jenis='+jenis;
            flag += 1;
          }
          if (negara != 0) {
            if (flag == 0) {
              link += "?";
            } else {
              link += '&';
            }
            link += "negara="+negara;
          }
          window.location = link;
        }
     </script>
   </head>

<body>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <!-- Modal Header -->
      <div class="modal-header">

        <h4 class="modal-title"><i class="bx bx-filter-alt"></i> Filter & Sort</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
        <label for="sort" style='margin-right:10px; margin-bottom:10px;'>Sort By: </label>
        <select name="sort" id="sort">
          <option id="sort1" value="1">Ascending</option>
          <option id="sort2" value="2" <?php if (isset($_GET['sort'])) { echo 'selected'; }?>>Descending</option>
        </select>
        <br>
        <label for="jenis" style='margin-right:10px; margin-bottom:10px;'>Jenis: </label>
        <select name="jenis" id="jenis">
          <option id="jenis0" value="0">--Pilih Jenis--</option>
          <?php foreach ($jenis as $data) { ?>
            <option id="jenis<?php echo $data['idjenis']; ?>" value="<?php echo $data['idjenis']; ?>" <?php if(isset($_GET['jenis'])) { if($_GET['jenis']==$data['idjenis']) { echo ' selected'; }}?>><?php echo $data['nama']; ?></option>
          <?php } ?>
        </select>
        <br>
        <label for="negara" style='margin-right:10px; margin-bottom:10px;'>Negara: </label>
        <select name="negara" id="negara">
          <option id="negara0" value="0">--Pilih Negara--</option>
          <?php foreach ($negara as $data) { ?>
            <option id="negara<?php echo $data['idnegara']; ?>" value="<?php echo $data['idnegara']; ?>" <?php if(isset($_GET['negara'])) { if($_GET['negara']==$data['idnegara']) { echo ' selected'; }}?>><?php echo $data['namanegara']; ?></option>
          <?php } ?>
        </select>
      </div>
      
      <!-- Modal footer -->
      <div class="modal-footer">
        <button class='sendButton' onclick='klikFilter()' data-bs-dismiss="modal">Submit</button>
      </div>
      
    </div>
  </div>
</div>
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
          <a href="lihatDaerah.php">
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
          <a href="pemecahanKasus.php" class="active">
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
        <span class="dashboard">Case-Solving Data</span>
      </div>

      <div class="profile-details" style="padding:10px; position:relative;">
      <i class='bx bx-user-circle' style="color:white; margin-right:5px;"></i>
      <?php echo $_SESSION['nama'];?>
        <i class='bx bx-chevron-down' style="color:white; position:absolute; right:10px;"></i>
      </div>
    </nav>
    </nav>

    <div class="home-content">
      <div class="overview-boxes">
      <div class="container">
    <button class="buttonSort" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-filter-alt"></i></button>
    <div id="chartContainer" style="height: 300px; width: 100%; margin-top:50px;margin-bottom:20px;"></div>
    <div class="table-responsive">
        <div style="overflow-x: auto;">
            <table id="example" class="table table-striped" style="width:100%; text-align: center;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><?php echo $judul; ?></th>
                        <th>Average of Case-Solving Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                      foreach ($isiData as $data) {
                        $temp = $i;
                        $days = intval($data['average']/(24*60));
                        $hours = intval(($data['average']%(24*60))/60);
                        $minutes = intval(($data['average']%(24*60))%60);
                    ?>
                    <tr>
                        <td id="Angka<?php echo $temp; ?>"><?= $i++; ?></td>
                        <td id="Nama<?php echo $temp; ?>"><?php echo $data['nama'];?></td>
                        <td id="Average<?php echo $temp; ?>"><?php echo $days;?> days <?php echo $hours;?> hours <?php echo $minutes;?> minutes</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
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

