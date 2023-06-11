<?php
    require_once "php/connect.php";
    // session_destroy();
    $_SESSION['user']=5;
    use MongoDB\BSON\ObjectID;
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $documentId = new MongoDB\BSON\ObjectId($_GET['id']);
    $filter = ['_id' => $documentId];
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery("pds.kasus", $query);
    $filter = ['artikel' => $documentId];
    $options = [
      'sort' => ['tanggal' => -1],
      'limit' => 10
    ];
    $query2 = new MongoDB\Driver\Query($filter, $options);
    $komen = $manager->executeQuery("pds.komentar", $query2);
    $resultArray = [];
    foreach ($komen as $document) {
        $resultArray[] = $document;
    }
    $resultArray = array_reverse($resultArray);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/page.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <style>
        h3, h6 {
          font-family: 'Inconsolata', monospace;
        }
        img {
          width: 100%;
        }
        .box {
          margin-top:100px;
          padding-top:20px;
          margin-bottom:20px;
          border: 1px black solid;
          box-shadow:10px 10px black;
          background-color:	rgb(255,250,250,0.5);
        }
        .container {
          padding-right:50px;
          padding-left:50px;
        }
        .bx-chevrons-down, .bx-chevrons-up {
          font-size:40px; 
          margin-left:46%;
          animation-name: movingArrow;
          animation-duration: 3s;
          animation-iteration-count: infinite;
        }
        @keyframes movingArrow {
          0% {color: black;}
          50% {color: #A9A9A9;}
        }
        .speech-bubble, .me-bubble {    
            border-radius: 2px;
            color: #fff;
            margin: 1em 0 3em;
            padding: 15px;
            position: relative;
            border: solid 2px #000;
            color: #000;
            width:75%;
            border-radius:20px;
        }
        .speech-bubble {
          background-color:white;
        }
        .me-bubble {
          background-color:rgb(30, 30, 47);
          color: white;
          margin-left: auto; 
          margin-right: 0;
        }
        .speech-bubble:after {
          
        }
        .speech-bubble:after, .speech-bubble:before {
          left: 10%;
        }
        .me-bubble:after, .me-bubble:before {
          left: 90%;
        }       
        .speech-bubble:after, .speech-bubble:before , .me-bubble:after, .me-bubble:before{
            top: 100%; 
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
        }
        .speech-bubble:after, .me-bubble:after {
            border-color: rgba(255, 255, 255, 0);
            /* border-top-color: #EEE2DE; */
            border-width: 20px 0px 0px 20px; 
            margin-left: -20px;
        } 
        .speech-bubble:after {
          border-top-color: white;
        }
        .me-bubble:after {
          border-top-color: rgb(30, 30, 47);
        }
        .speech-bubble:before, .me-bubble:before {
            border-color: rgba(0, 0, 0, 0);
            border-top-color: #000;
            border-width: 27px 0px 0px 27px;
            margin-left: -24px;
        }
        .tags {
          border: 1px solid black;
          padding: 2px 10px;
          border-radius: 10px;
          font-size:80%;
        }
        textarea {
          padding:20px;
          border-radius:20px;
        }
        .sendButton {
          background-color:transparent;
          border:1px solid black;
          padding: 10px 30px;
          border-radius:10px;
          size:150%;
          float:right;
        }
        .sendButton:hover {
          background-color:black;
          color:white;
        }
    </style>
    <script>
      isi = "";
      function klikArrow() {
        document.getElementById('isiArrow').innerHTML = isi + "<i class='bx bx-chevrons-up' onclick='tutupArrow()'></i>";
      }
      function tutupArrow() {
        document.getElementById('isiArrow').innerHTML = "<i class='bx bx-chevrons-down' onclick='klikArrow()'></i>";
      }
      function insertNewComment() {
        komen = document.getElementById('komentar').value;
        id = '<?php echo $_GET['id']?>';
        $.ajax({
            url: 'php/insertNewComment.php',
            type: 'post',
            data: {
                komentar: komen,
                artikel: id
            },
            success: function(result) {
                document.getElementById('komentarHalaman').innerHTML = result;
                document.getElementById('komentar').value = "";

            }  
        });
      }
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
        <span class="dashboard">Case Detail</span>
      </div>

      <div class="profile-details" style="padding:10px; position:relative;">
      <i class='bx bx-user-circle' style="color:white; margin-right:5px;"></i>
        NAMA
        <i class='bx bx-chevron-down' style="color:white; position:absolute; right:10px;"></i>
      </div>
    </nav>
    <div class="container">
      <?php
        foreach ($cursor as $data) {
          $offense = $data->OFFENSE;
          $sql = "SELECT * FROM jenis_kejahatan WHERE idjenis = ".$offense;
          $stmt = $conn->query($sql);
          $jenis = $stmt->fetch(); ?>
          <div class="row" style="line-height:100%">
            <div class="col-sm-5">
              <h3 style="padding-top:100px; text-align:center; margin-bottom:20px;"><?php echo $jenis['nama'];?> at <?php echo $data->BLOCK?></h3>
              <img src="assets/<?php echo $jenis['gambar'];?>" alt="tes">
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-6 box">
            <h3 style="text-align:center; margin-bottom:30px"> Case Detail </h3>
        <?php $count = 0; 
        foreach ($data as $key=>$value) {
          if ($key != "_id") {
            if ($key == "OFFENSE") {
              $sql = "SELECT * FROM jenis_kejahatan WHERE idjenis = ".$value;
              $stmt = $conn->query($sql);
              $jenis = $stmt->fetch();
              $value = $jenis['nama']; 
            }
            if ($key == "REGION") {
              $sql = "SELECT * FROM daerah d JOIN negara n ON d.negara=n.idnegara WHERE iddaerah = ".$value;
              $stmt = $conn->query($sql);
              $daerah = $stmt->fetch();
              $value = $daerah['daerah'].", ".$daerah['namanegara']; 
            }
            $jdl = str_replace("_", " ", $key);
            if ($count < 12) { 
        ?>
        <div class="row" style="margin-bottom:7px">
          <div class="col-4"><h6><?php echo $jdl ?></h6></div>
          <div class="col-1" style="text-align:right">:</div>
          <div class="col-6"><?php echo $value ?></div>
        </div>
          <?php } else {
            if ($count == 12) {
              echo "<div id='isiArrow'><i class='bx bx-chevrons-down' onclick='klikArrow()'></i></div>";
            } ?>
          <script>
            isi += `<div class="row" style="margin-bottom:7px">
              <div class="col-4"><h6><?php echo $jdl?></h6></div>
              <div class="col-1" style="text-align:right">:</div>
              <div class="col-6"><?php echo $value?></div>
            </div>`;
          </script>
          <?php }
          $count = $count + 1;?>
        <?php } } } ?>
          </div>
        </div>
        <hr><hr>
        <div id="komentarHalaman">
        <?php foreach ($resultArray as $data) { 
          $sql = "SELECT * FROM user WHERE iduser = ".$data->user; 
          $stmt = $conn->query($sql);
          $user = $stmt->fetch(); 
          $check = True;
          $komen = str_replace(array("\n"), array("<br>"), $data->komentar);
          if (isset($_SESSION['user'])) {
            if ($data->user == $_SESSION['user']) {?>
              <h3 style="text-align:right">You (<?php echo $user['nama']?>)</h3>
              <p style="font-size:80%; margin-top:-5px; margin-bottom:-5px; text-align:right">(<?php echo $data->tanggal?>)</p>
              <div class="me-bubble"><?php echo $komen?>
              <br><br>
              <?php $tags = $data->tags;
              foreach ($tags as $t) { ?>
              <span class="tags" style="border:1px solid white"><i class='bx bx-purchase-tag'></i> <?php echo $t?></span>
              <?php } ?>
              </div>
            <?php $check = False; 
            }
          } 
          if ($check) { ?>
            <h3><?php echo $user['nama']?></h3>
            <p style="font-size:80%; margin-top:-5px; margin-bottom:-5px;">(<?php echo $data->tanggal?>)</p>
            <div class="speech-bubble"><?php echo $komen?>
              <br><br>
              <?php $tags = $data->tags;
              foreach ($tags as $t) { ?>
              <span class="tags"><i class='bx bx-purchase-tag'></i> <?php echo $t?></span>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } ?>
        </div>
        <?php
          if (isset($_SESSION['user'])) {
            $sql = "SELECT * FROM user WHERE iduser = ".$_SESSION['user'];
            $stmt = $conn->query($sql);
            $user = $stmt->fetch();
            echo "<hr><h6 style='margin-left:10px;'>Nama: ".$user['nama'].'</h6>';
            echo "<h6 style='margin-left:10px;'>Email: ".$user['email'].' (not shared)</h6>';
            echo "<textarea id='komentar' rows='10' style='width:100%' placeholder='Write your comment here...'></textarea>
            <button class='sendButton' onclick='insertNewComment()'>Send</button><br><br><br>";
          } else {
            echo "<hr><h5 style='margin-left:20px;'><i class='bx bx-comment-dots'></i> <a href='tes.php' style='color:blue'>Log in</a> untuk menambahkan komentar</h5>";
          }
        ?>
        </div>
        <br>
    </section>
    </div>

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