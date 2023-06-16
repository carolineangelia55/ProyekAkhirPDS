<?php
  require_once "php/connect.php";
  use MongoDB\BSON\ObjectID;
  $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
  $filter = [];
  $options = [
    'sort' => ['REPORT_DATE' => -1],
    'limit' => 500
  ];
  $query = new MongoDB\Driver\Query($filter, $options);
  $cursor = $manager->executeQuery('pds.kasus', $query);
  $dataKasus = [];
  foreach ($cursor as $document) {
    $dataKasus[] = $document;
  }
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
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        tags2 = [];
        sort = 1;
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
            $("#tags2").autocomplete({
              source: availableTags
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
            $('#sort').on('change', function() {
                sort = $(this).find(":checked").val();
            });
            $('#negara').on('change', function() {
                negara = $(this).find(":checked").val();
                $.ajax({
                  url: 'php/showDaerah.php',
                  type: 'post',
                  data: {
                      negara: negara
                  },
                  success: function(result) {
                    document.getElementById("pilihDaerah").innerHTML = result; 
                  }  
                });
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
          jenis = document.getElementById('jenis').value;
          negara = document.getElementById('negara').value;
          daerah = document.getElementById('daerah').value;
          lokasi = document.getElementById('lokasi').value;
          tanggalKejadian = document.getElementById('tanggalKejadian').value;
          tanggalLapor = document.getElementById('tanggalLapor').value;
          $.ajax({
            url: 'php/filterKasus.php',
            type: 'post',
            data: {
                sort: sort,
                jenis: jenis,
                negara: negara,
                daerah: daerah,
                lokasi: lokasi,
                tanggalKejadian: tanggalKejadian,
                tanggalLapor: tanggalLapor,
                tags: tags2
            },
            success: function(result) {
              // Destroy the existing DataTable
              $('#example').DataTable().destroy();
              // Replace the content of the table body with the updated data
              $('#example tbody').html(result);
              // Reinitialize the DataTable with the updated data
              $('#example').DataTable({
                dom: "B<'row'<'col-sm-6'l><'col-sm-6'f>>tipr",
                buttons: [
                  'copy', 'csv', 'excel'
                ],
                buttons: {
                  dom: {
                    button: {
                      tag: "button",
                      className: "btn btn-outline-dark mb-3 mx-1 rounded p-2"
                    },
                    buttonLiner: {
                      tag: null
                    }
                  }
                }
              });
            }  
          });
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
        .buttonSort {
          background-color: transparent;
          padding: 5px 15px;
          border:1px solid black;
          border-radius:10px;
          margin-left:-100px;
          float: right;
          margin-top:100px;
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
        #buttonFilter {
          background-color:transparent;
          border: 1px solid black;
          border-radius:10px;
          padding:2px 5px;
          padding-top:5px;
        }
        .sendButton:hover, .buttonSort:hover, #buttonFilter:hover {
          background-color:black;
          color:white;
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
        .tags {
          border: 1px solid black;
          padding: 2px 10px;
          border-radius: 10px;
          font-size:80%;
        }
    </style>
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
          <option id="sort1" value="1">Newest to Oldest</option>
          <option id="sort2" value="2">Oldest to Newest</option>
        </select>
        <br>
        <label for="jenis" style='margin-right:10px; margin-bottom:10px;'>Jenis: </label>
        <select name="jenis" id="jenis">
          <option id="jenis0" value="0">--Pilih Jenis--</option>
          <?php foreach ($jenis as $data) { ?>
            <option id="jenis<?php echo $data['idjenis']; ?>" value="<?php echo $data['idjenis']; ?>"><?php echo $data['nama']; ?></option>
          <?php } ?>
        </select>
        <br>
        <label for="negara" style='margin-right:10px; margin-bottom:10px;'>Negara: </label>
        <select name="negara" id="negara">
          <option id="negara0" value="0">--Pilih Negara--</option>
          <?php $sql = "SELECT * FROM negara";
          $stmt = $conn->query($sql);
          $negara = $stmt->fetchAll();
          foreach ($negara as $data) { ?>
            <option id="negara<?php echo $data['idnegara']; ?>" value="<?php echo $data['idnegara']; ?>"><?php echo $data['namanegara']; ?></option>
          <?php } ?>
        </select>
        <br>
        <span id="pilihDaerah">
          <label for="daerah" style='margin-right:10px; margin-bottom:10px;'>Daerah: </label>
          <select name="daerah" id="daerah">
            <option id="daerah0" value="0">--Pilih Daerah--</option>
          </select>
        </span>
        <br>
        <label for="lokasi" style='margin-right:10px; margin-bottom:10px;'>Lokasi: </label>
        <input type='text' class='inputTag' style="width:30%" id='lokasi'>
        <br>
        <label for="tanggalKejadian" style='margin-right:10px; margin-bottom:10px;'>Tanggal Kejadian: </label>
        <input type='date' class='inputTag' style="width:30%" id='tanggalKejadian'>
        <br>
        <label for="tanggalLapor" style='margin-right:10px; margin-bottom:10px;'>Tanggal Pelaporan: </label>
        <input type='date' class='inputTag' style="width:30%" id='tanggalLapor'>
        <br>
        <label for='tags2' style='margin-right:10px'>Tags: </label><span id='daftarTags2'></span>
        <input type='text' class='inputTag' id='tags2'>
        <button onclick='addTags2()' id='buttonFilter'>+</button>
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
          <a href="daftarkasus.php" class="active">
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
        <span class="dashboard">Cases List</span>
      </div>

      <div class="profile-details" style="padding:10px; position:relative;">
      <i class='bx bx-user-circle' style="color:white; margin-right:5px;"></i>
      <?php echo $_SESSION['nama'];?>
        <i class='bx bx-chevron-down' style="color:white; position:absolute; right:10px;"></i>
      </div>
    </nav>
    <div class="container">
    <button class="buttonSort" data-tooltip="tooltip" title="Filter & Sort" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-filter-alt"></i></button>
    <div class="table-responsive" style="padding-top:100px;"  id="isiTabel">
        <div style="overflow-x: auto;">
            <table id="example" class="table table-striped" style="width:100%; text-align: center;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Case Name</th>
                        <th>Report Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                      foreach ($dataKasus as $data) {
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