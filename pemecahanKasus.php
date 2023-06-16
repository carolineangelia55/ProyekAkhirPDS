<?php
    require_once 'php/connect.php';
    if(!isset($_SESSION['email'])){
      header("location: login.php");
      exit;
    }
    use MongoDB\BSON\ObjectID;
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = [];
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('pds.kasus', $query);
    $jenisData = [];
    foreach ($cursor as $document) {
      if (isset($document->END_DATE)) {
        $startDate = DateTime::createFromFormat('d/m/Y h:i:s A', $document->START_DATE);
        $endDate = DateTime::createFromFormat('d/m/Y h:i:s A',  $document->END_DATE);
        $interval = $startDate->diff($endDate);
        $days = $interval->days;
        $hours = $interval->h;
        $minutes = $interval->i;
        if (!isset($jenisData[$document->OFFENSE])) {
            $jenisData[$document->OFFENSE]['days'] = 0; // Inisialisasi elemen array jika belum ada
            $jenisData[$document->OFFENSE]['hours'] = 0;
            $jenisData[$document->OFFENSE]['minutes'] = 0;
            $jenisData[$document->OFFENSE]['count'] = 0;
        } else {
            $jenisData[$document->OFFENSE]['days'] += intval($days);
            $jenisData[$document->OFFENSE]['hours'] += intval($hours);
            $jenisData[$document->OFFENSE]['minutes'] += intval($minutes);
            $jenisData[$document->OFFENSE]['count'] += 1;
        }
      }
    }
    $sql = "SELECT * FROM jenis_kejahatan";
    $stmt = $conn->query($sql);
    $jenis = $stmt->fetchAll(); 
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Admin Home</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <style>
        .nav-links {
            margin-left:-32px;
        }
     </style>
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
        <span class="dashboard">Home</span>
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
    <!-- <button class="buttonSort" data-tooltip="tooltip" title="Filter & Sort" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-filter-alt"></i></button> -->
    <div class="table-responsive"  id="isiTabel">
        <div style="overflow-x: auto;">
            <table id="example" class="table table-striped" style="width:100%; text-align: center;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Case Name</th>
                        <th>Average of Case-Solving Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                      foreach ($jenisData as $key => $data) {
                        $temp = $i;
                        $total = $data['days']*24*60 + $data['hours']*60 + $data['minutes'];
                        $average = $total/$data['count'];
                        $days = intval($average/(24*60));
                        $hours = intval(($average%(24*60))/60);
                        $minutes = intval(($average%(24*60))%60);
                    ?>
                    <tr>
                        <td id="Angka<?php echo $temp; ?>"><?= $i++; ?></td>
                        <td id="Nama<?php echo $temp; ?>"><?php echo $jenis[$key-1]['nama'];?></td>
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

