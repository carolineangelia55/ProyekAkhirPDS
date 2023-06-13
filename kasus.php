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
      'limit' => $_SESSION['jumlahBatas']
    ];
    $query2 = new MongoDB\Driver\Query($filter, $options);
    $komen = $manager->executeQuery("pds.komentar", $query2);
    $resultArray = [];
    foreach ($komen as $document) {
        $resultArray[] = $document;
    }
    $filter = ['artikel' => $documentId];
    $options = [];
    $query2 = new MongoDB\Driver\Query($filter, $options);
    $isi = $manager->executeQuery("pds.komentar", $query2);
    $jumlahIsi = [];
    foreach ($isi as $document) {
      $jumlahIsi[] = $document;
    }
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
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>    
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
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
          left: 15%;
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
          margin-right:3%;
        }
        .buttonTag {
          background-color: transparent;
          padding: 0px 15px;
          border:1px solid black;
          border-radius:10px;
        }
        .buttonSort {
          background-color: transparent;
          padding: 5px 15px;
          border:1px solid black;
          border-radius:10px;
          margin-right:10px;
          float: right;
        }
        #buttonFilter {
          background-color:transparent;
          border: 1px solid black;
          border-radius:10px;
          padding:2px 5px;
          padding-top:5px;
        }
        .sendButton:hover, .buttonTag:hover, .buttonSort:hover, #buttonFilter:hover{
          background-color:black;
          color:white;
        }
        .h5AddMore {
          color:blue;
        }
        .h5AddMore:hover {
          letter-spacing: 3px;
        }
        .inputTag {
          background-color: transparent;
          border: 0;
          border-bottom: 1px solid black;
          width: 20%;
          margin-left:5px;
        }
        .ui-autocomplete {
          z-index:2147483647;
        }
        select {
          border-radius:5px;
          padding:2px 5px;
        }
    </style>
    <script>
      isi = "";
      tags = [];
      sort = 1;
      tags2 = [];
      jumlahBatas = 10;
      $(document).ready(function(){
        var availableTags = [
          "Important to Know",
          "Real Case",
          "Fake Case",
          "Urgent Case",
          "Just FYI",
          "Need Validation",
          "Related to the Goverment",
          "Underage Children",
          "Violation of Human Rights",
          "Need Justice",
          "Structural Poverty",
          "Negative Effect of Technologies"
        ];
        $("#tags").autocomplete({
          source: availableTags
        });
        $("#tags2").autocomplete({
          source: availableTags
        });
        var input = document.getElementById("tags");
        input.addEventListener("keypress", function(event) {
          if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("buttonTag").click();
          }
        });
        var input2 = document.getElementById("tags2");
        input2.addEventListener("keypress", function(event) {
          if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("buttonFilter").click();
          }
        });
        $('body').tooltip({
            selector: "[data-tooltip=tooltip]",
            container: "body"
        });
        $('select').on('change', function() {
            sort = $(this).find(":checked").val();
        });
      });
      function klikArrow() {
        document.getElementById('isiArrow').innerHTML = isi + "<i class='bx bx-chevrons-up' onclick='tutupArrow()'></i>";
      }
      function tutupArrow() {
        document.getElementById('isiArrow').innerHTML = "<i class='bx bx-chevrons-down' onclick='klikArrow()'></i>";
      }
      function insertNewComment() {
        komen = document.getElementById('komentar').value;
        if (komen!="") {
          id = '<?php echo $_GET['id']?>';
          $.ajax({
              url: 'php/insertNewComment.php',
              type: 'post',
              data: {
                  komentar: komen,
                  artikel: id,
                  tags: tags
              }, success: function(result) {
                tags = [];
                document.getElementById('komentar').value = "";
                document.getElementById('daftarTags').innerHTML = "";
                klikFilter();
              }  
          });
        } else {
          alert("Komentar tidak boleh kosong");
        }
      }
      function tambahView() {
        jumlahBatas += 10;
        klikFilter();
      }
      function addTags() {
        tag = document.getElementById("tags").value;
        cek = true;
        for (let i = 0; i < tags.length; i++) {
          if (tags[i]==tag) {
            cek = false;
            alert("Tag dengan nama yang sama sudah ditambahkan")
          }
        }
        if (tag != "" && cek) {
          tags.push(tag);
          var isitag = "";
          for (let i = 0; i < tags.length; i++) {
            isitag += '<span class="tags" style="border:1px solid black"><i class="bx bx-purchase-tag"></i>'+tags[i]+` <span onclick='deleteTag("`+tags[i]+`")'>&times;</span></span>`;
          }
          document.getElementById('daftarTags').innerHTML = isitag;
        }
        document.getElementById("tags").value = "";
      }
      function deleteTag(tag) {
        for (let i = 0; i < tags.length; i++) {
          if (tags[i]==tag) {
            tags.splice(i, 1);
          }
        }
        var isitag = "";
        for (let i = 0; i < tags.length; i++) {
          isitag += '<span class="tags" style="border:1px solid black"><i class="bx bx-purchase-tag"></i>'+tags[i]+` <span onclick='deleteTag("`+tags[i]+`")'>&times;</span></span>`;
        }
        document.getElementById('daftarTags').innerHTML = isitag;
        document.getElementById("tags").value = "";
      }
      function addTags2() {
        tag = document.getElementById("tags2").value;
        cek = true;
        for (let i = 0; i < tags2.length; i++) {
          if (tags2[i]==tag) {
            cek = false;
            alert("Tag dengan nama yang sama sudah ditambahkan")
          }
        }
        if (tag != "" && cek) {
          tags2.push(tag);
          var isitag = "";
          for (let i = 0; i < tags2.length; i++) {
            isitag += '<span class="tags" style="border:1px solid black"><i class="bx bx-purchase-tag"></i>'+tags2[i]+` <span onclick='deleteTag2("`+tags2[i]+`")'>&times;</span></span>`;
          }
          document.getElementById('daftarTags2').innerHTML = isitag;
        }
        document.getElementById("tags2").value = "";
      }
      function deleteTag2(tag) {
        for (let i = 0; i < tags2.length; i++) {
          if (tags2[i]==tag) {
            tags2.splice(i, 1);
          }
        }
        var isitag = "";
        for (let i = 0; i < tags2.length; i++) {
          isitag += '<span class="tags" style="border:1px solid black"><i class="bx bx-purchase-tag"></i>'+tags2[i]+` <span onclick='deleteTag2("`+tags2[i]+`")'>&times;</span></span>`;
        }
        document.getElementById('daftarTags2').innerHTML = isitag;
        document.getElementById("tags2").value = "";
      }
      function klikFilter() {
        id = '<?php echo $_GET['id']?>';
        $.ajax({
            url: 'php/filterComment.php',
            type: 'post',
            data: {
                artikel: id,
                sort: sort,
                startDate: document.getElementById("startDate").value,
                endDate: document.getElementById("endDate").value,
                tags: tags2,
                jumlahBatas: jumlahBatas
            },
            success: function(result) {
              document.getElementById('komentarHalaman').innerHTML = result;
            }  
        });
        $.ajax({
            url: 'php/countComment.php',
            type: 'post',
            data: {
                artikel: id,
                sort: sort,
                startDate: document.getElementById("startDate").value,
                endDate: document.getElementById("endDate").value,
                tags: tags2,
                jumlahBatas: jumlahBatas
            },
            success: function(result) {
              if (result > jumlahBatas) {
                document.getElementById('viewMore').innerHTML = "<h5 class='h5AddMore' style='text-align:center;' onclick='tambahView(); color:blue'>See More Comments</h5>";
              } else {
                document.getElementById('viewMore').innerHTML = '';
              }
            }  
        });
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
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
        <label for="sort" style='margin-right:10px'>Sort: </label>
        <select name="cars" id="cars">
          <option id="sort1" value="1" selected>Newest to Oldest</option>
          <option id="sort2" value="2">Oldest to Newest</option>
        </select>
        <br>
        <label for="date" style='margin-right:10px'>Date: </label>
        <input type='date' class='inputTag' style="width:30%" id='startDate'> - 
        <input type='date' class='inputTag' style="width:30%" id='endDate'>
        <br>
        <label for='tags2' style='margin-right:10px'>Tags: </label><span id='daftarTags2'></span>
        <input type='text' class='inputTag' id='tags2'>
        <button onclick='addTags2()' id='buttonFilter'>+</button>
      </div>
      
      <!-- Modal footer -->
      <div class="modal-footer">
        <button class='sendButton' onclick='klikFilter()' data-dismiss="modal">Submit</button>
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
          if ($key != "_id" and $key != "tags") {
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
        <br>
        <span style="margin-right:5px; font-weight: bold;">Tags: </span>
        <?php if (isset($data->tags)) {
        foreach ($data->tags as $val) { ?>
          <span class="tags" style="border:1px solid black; font-size: 100%;"><i class="bx bx-purchase-tag"></i><?php echo $val ?></span>
        <?php } } ?>
        <hr><hr>
        <?php
          if (isset($_SESSION['user'])) {
            $sql = "SELECT * FROM user WHERE iduser = ".$_SESSION['user'];
            $stmt = $conn->query($sql);
            $user = $stmt->fetch();
            echo '<button class="buttonSort" data-tooltip="tooltip" title="Filter & Sort" data-toggle="modal" data-target="#myModal"><i class="bx bx-filter-alt"></i></button>';
            echo "<h6 style='margin-left:10px;'>Nama: ".$user['nama'].'</h6>';
            echo "<h6 style='margin-left:10px;'>Email: ".$user['email'].' (not shared)</h6>';
            echo "<textarea id='komentar' rows='5' style='width:100%' placeholder='Write your comment here...' required></textarea>";
            echo "<label for='tags' style='margin-right:10px'>Tags: </label><span id='daftarTags'></span><input type='text' class='inputTag' id='tags'> <button class='buttonTag' onclick='addTags()' id='buttonTag'><i class='bx bx-plus-circle'></i>Add New Tag</button>";
            echo "<button class='sendButton' onclick='insertNewComment()'>Send</button><br><br><br>";
          } else {
            echo '<button class="buttonSort" data-tooltip="tooltip" title="Filter & Sort" data-toggle="modal" data-target="#myModal"><i class="bx bx-filter-alt"></i></button>';
            echo "<br><h5 style='margin-left:20px;'><i class='bx bx-comment-dots'></i> <a href='tes.php' style='color:blue'>Log in</a> untuk menambahkan komentar</h5>";
          }
        ?>
        <br>

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
        </div>
        <div id="viewMore">
        <?php
        if (count($jumlahIsi) > $_SESSION['jumlahBatas']) {
          echo "<h5 class='h5AddMore' style='text-align:center;' onclick='tambahView(); color:blue'>See More Comments</h5>";
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