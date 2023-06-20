<?php
  require_once "php/connect.php";
  if(!isset($_SESSION['email'])){
    header("location: login.php");
    exit;
  }    
  use MongoDB\BSON\ObjectID;
  $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
  $week_start = date("Y-m-d", strtotime('monday this week'));
  $week_end = date("Y-m-d", strtotime('sunday this week'));
  $filter = [];
  $options = [
    // 'limit' => 3
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $cursor = $manager->executeQuery('pds.komentar', $query);
  $dataKomen = [];
  foreach ($cursor as $document) {
    $id = (string)($document->artikel);
    $date = date("Y-m-d", strtotime($document->tanggal));
    if ($date <= $week_end and $date >= $week_start) {
      if (isset($dataKomen[$id])) {
        $dataKomen[$id] += 1;
      } else {
        $dataKomen[$id] = 1;
      }
    }
  }
  uasort($dataKomen, function($a, $b)
  {
      return $b - $a;
  });
  $dataKomen = array_slice($dataKomen, 0, 3);  
  $dataKasus = [];
  foreach ($dataKomen as $key=>$value) {
    $documentId = new MongoDB\BSON\ObjectId($key);
    $filter2 = ['_id' => $documentId];
    $query2 = new MongoDB\Driver\Query($filter2, $options);
    $cursor2 = $manager->executeQuery('pds.kasus', $query2);
    foreach ($cursor2 as $d) {
      $dataKasus[] = $d;
    }
  }
  $sql = "SELECT * FROM jenis_kejahatan";
  $stmt = $conn->query($sql);
  $jenis = $stmt->fetchAll(); 
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Home</title>

    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="css/page.css">
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
     <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
     <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@900&display=swap" rel="stylesheet">
     <style>
        body {
            font-family: sans-serif;
        }
        h1 {
          font-family: 'Inconsolata', monospace;
          padding-top:100px;
          margin-left:100px;
          margin-bottom:20px;
        }
        .container {
            display: flex;
            align-items: flex-end;
            border:1px solid black;
        }

        .podium__item {
            margin:20px;            
        }

        .podium__rank {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 35px;
            color: #fff;
        }

        .podium__city {
            text-align: center;
            padding: 0 .5rem;
            font-family: 'Inconsolata', monospace;
            font-size:120%;
        }

        .podium__number {
            width: 27px;
            height: 75px;
        }

        .podium .first {
            width: 300px;
            min-height: 300px;
            background-image: url('assets/<?php echo $jenis[$dataKasus[0]->OFFENSE-1]['gambar'] ?>');
            background-size: 300px 300px;
            /* background-color:white; */
            border: 1px black solid;
            box-shadow:10px 10px black;

        }

        .podium .second {
            width: 200px;
            min-height: 200px;
            background-image: url('assets/<?php echo $jenis[$dataKasus[1]->OFFENSE-1]['gambar'] ?>');
            /* background-color:white; */
            background-size: 200px 200px;
            border: 1px black solid;
            box-shadow:10px 10px black;
        }

        .podium .third {
            width: 150px;
            min-height: 150px;
            background-image: url('assets/<?php echo $jenis[$dataKasus[2]->OFFENSE-1]['gambar'] ?>');
            background-size: 150px 150px;
            /* background-color:white; */
            border: 1px black solid;
            box-shadow:10px 10px black;
        }

        .podium__item:hover {
          background-color:#F8F6F0;
          border-radius:50%;
          box-shadow:10px 10px black;
        }

     </style>
     <script>
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
          <a href="home.php" class="active">
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
        <span class="dashboard">Home</span>
      </div>

      <div class="profile-details" style="padding:10px; position:relative;">
      <i class='bx bx-user-circle' style="color:white; margin-right:5px;"></i>
      <?php echo $_SESSION['nama'];?>
        <i class='bx bx-chevron-down' style="color:white; position:absolute; right:10px;"></i>
      </div>
    </nav>
    <h1>TOP 3 KASUS MINGGU INI: </h1>
    <div class="container podium" style=" justify-content: center;">
        <div class="podium__item" onclick="window.location.href='kasus.php?id=<?php echo $dataKasus[1]->_id ?>'">
            <p class="podium__city" style="width:200px"><?php echo $jenis[$dataKasus[1]->OFFENSE-1]['nama'] ?> at <?php echo $dataKasus[1]->BLOCK ?></p>
            <div class="podium__rank second"></div>
        </div>
        <div class="podium__item" onclick="window.location.href='kasus.php?id=<?php echo $dataKasus[0]->_id ?>'">
            <p class="podium__city" style="width:300px"><?php echo $jenis[$dataKasus[0]->OFFENSE-1]['nama'] ?> at <?php echo $dataKasus[0]->BLOCK ?></p>
            <div class="podium__rank first">
            <!-- <svg class="podium__number" viewBox="0 0 27.476 75.03" xmlns="http://www.w3.org/2000/svg"> -->
            <g transform="matrix(1, 0, 0, 1, 214.957736, -43.117417)">
                <path class="st8" d="M -198.928 43.419 C -200.528 47.919 -203.528 51.819 -207.828 55.219 C -210.528 57.319 -213.028 58.819 -215.428 60.019 L -215.428 72.819 C -210.328 70.619 -205.628 67.819 -201.628 64.119 L -201.628 117.219 L -187.528 117.219 L -187.528 43.419 L -198.928 43.419 L -198.928 43.419 Z" style="fill: #000;"/>
            </g>
            </svg>
            </div>
        </div>
        <div class="podium__item" onclick="window.location.href='kasus.php?id=<?php echo $dataKasus[2]->_id ?>'">
            <p class="podium__city" style="width:150px"><?php echo $jenis[$dataKasus[2]->OFFENSE-1]['nama'] ?> at <?php echo $dataKasus[2]->BLOCK ?></p>
            <div class="podium__rank third"></div>
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

