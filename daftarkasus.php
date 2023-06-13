<?php
  require_once "php/connect.php";
  use MongoDB\BSON\ObjectID;
  $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
  $week_start = date("Y-m-d", strtotime('monday this week'));
  $week_end = date("Y-m-d", strtotime('monday next week'));
  $pipeline = [
    [
      '$lookup' => [
        'from' => 'komentar',
        'localField' => '_id',
        'foreignField' => 'artikel',
        'as' => 'collection2Data'
      ]
    ],
    [
      '$addFields' => [
        'collection2Count' => ['$size' => '$collection2Data']
      ]
    ],
    [
      '$match' => [
        '$or' => [
            ['collection2Data' => []],
            [
                'collection2Data' => [
                    '$elemMatch' => [
                        'tanggal' => ['$gte' => 10],
                        'field3' => ['$lte' => 20]
                    ]
                ]
            ]
        ]
      ]
    ],
    [
      '$sort' => [
        'collection2Count' => -1
      ]
    ],
    [
      '$limit' => 500
    ]
  ];

  $options = [
    'cursor' => new stdClass(),
    'allowDiskUse' => true
  ];

  $aggregateCommand = new MongoDB\Driver\Command([
    'aggregate' => 'kasus',
    'pipeline' => $pipeline,
    'cursor' => $options['cursor'],
    'allowDiskUse' => $options['allowDiskUse']
  ]);

  $cursor = $manager->executeCommand('pds', $aggregateCommand);
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
    <title>Cases List</title>
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
        function hoverRow(id) {
            document.getElementById("Angka"+id).style.color = 'white';
            document.getElementById("Nama"+id).style.color = 'white';
            document.getElementById("Nama"+id).style.letterSpacing = '3px';
            document.getElementById("Tgl"+id).style.color = 'white';
        }
        function unhoverRow(id) {
            document.getElementById("Angka"+id).style.color = 'black';
            document.getElementById("Nama"+id).style.color = 'black';
            document.getElementById("Nama"+id).style.letterSpacing = '0px';
            document.getElementById("Tgl"+id).style.color = 'black';
        }
    </script>
    <style>
        .nav-links {
            margin-left:-32px;
        }
        tbody tr:hover, .data:hover {
            background-color: #5a0303;
            /* color:white !important;  */
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
          <a href="#">
            <i class='bx bx-folder-plus'></i>
            <span class="links_name">Add New Case</span>
          </a>
        </li>
        <li>
          <a href="daftarkasus.php" class="active">
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
          <a href="kelolaKegiatan.php">
            <i class='bx bx-current-location' ></i>
            <span class="links_name">Region Data</span>
          </a>
        </li>
        <li>
          <a href="inputJadwal.php">
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
          <a href="logoutAdmin.php">
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
        <span class="dashboard">Cases List</span>
      </div>

      <div class="profile-details" style="padding:10px; position:relative;">
      <i class='bx bx-user-circle' style="color:white; margin-right:5px;"></i>
        NAMA
        <i class='bx bx-chevron-down' style="color:white; position:absolute; right:10px;"></i>
      </div>
    </nav>
    <div class="container">
    <div class="table-responsive" style="padding-top:100px;">
        <div style="overflow-x: auto;">
            <table id="example" class="table table-striped" style="width:100%; text-align: center;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Case Name</th>
                        <th>Report Date</th>
                    </tr>
                </thead>
                <tbody id="isiTabel">
                    <?php $i = 1; 
                      foreach ($cursor as $data) {
                        $temp = $i;
                    ?>
                    <tr onmouseover='hoverRow("<?php echo $temp ?>")' onmouseout='unhoverRow("<?php echo $temp ?>")' onclick="window.location.href='kasus.php?id=<?php echo $data->_id;?>'">
                        <td id="Angka<?php echo $temp; ?>"><?= $i++; ?></td>
                        <td id="Nama<?php echo $temp; ?>"><?php echo $jenis[$data->OFFENSE-1]['nama'];?> at <?php echo $data->BLOCK;?></td>
                        <td id="Tgl<?php echo $temp; ?>"><?php echo $data->REPORT_DATE;?></td>
                    </tr>
                    <?php } ?>
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