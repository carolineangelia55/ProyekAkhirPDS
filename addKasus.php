<?php
    require_once 'koneksi.php';
    session_start();
    if(!isset($_SESSION['email'])){
      header("location: login.php");
      exit;
    }

    $sql = "SELECT * FROM jenis_kejahatan";
    $stmt = $sambung->query($sql);
    
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Add New Case</title>

    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="css/page.css">
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
     <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
     <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
     <style>
        body {
            font-family: sans-serif;
        }

        .container {
            display: flex;
            align-items: flex-end;
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
        }

        .podium__number {
            width: 27px;
            height: 75px;
        }

        
        input[type=text], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            }
          
            /* input[type=checkbox], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            } */

            input[type=textarea], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            }

            input[type=date], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            }

            input[type=submit] {
            width: 100%;
            background-color: #847d7d;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            }

            input[type=submit]:hover {
            background-color: #9c8c8c;
            }

            .isi {
            background-color: #f2f2f2;
            padding: 20px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 85%;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            }

            .row {
                display: flex;
                justify-content: space-between;
            }

            #buttonAdd {
              margin-top:15px;
              padding: 5px 15px;
              background-color:transparent;
              color:black;
              border: 1px solid black;
              border-radius: 10px;
            }
            #buttonAdd:hover {
              background-color:black;
              color:white;
            }
            .removeButton {
              margin-top:10px;
              margin-right:10px;
            }
     </style>
     <script>
          document.addEventListener("DOMContentLoaded", function() {
          var checkbox = document.getElementById("showDateForm");
          var form = document.getElementById("dateForm");

          checkbox.addEventListener("change", function() {
            if (checkbox.checked) {
              form.style.display = "block";
            } else {
              form.style.display = "none";
            }
          });
        });

        function removeInput(button) {
            var inputRow = button.parentNode.parentNode;
            var inputContainer = inputRow.parentNode;
            var inputRows = inputContainer.getElementsByClassName("inputRow");
            inputContainer.removeChild(inputRow);
        }

        function addInput() {
            var inputContainer = document.getElementById("inputContainer");
            var inputRow = document.createElement("div");
            inputRow.className = "inputRow";
            inputRow.innerHTML = `
            <div style="position: relative;">
                <div class="row">
                    <input type="textarea" name="inputJudul[]" class="textInput" placeholder="Judul Informasi Lainnya" required style="padding-right: 37px; margin-right: 10px; flex: 1;">
                    <input type="textarea" name="inputField[]" class="textInput" placeholder="Isi Informasi Lain" required style="padding-right: 37px; flex: 2;">
                </div>
                <span style="position: absolute; top: 0; right: 0; padding-left: 5px; padding-top: 5px; font-size: 20px; color: black; cursor: pointer;" class="removeButton" onclick="removeInput(this)">x</span>
            </div>
            `;
            inputContainer.appendChild(inputRow);
            }
          
            document.getElementById("showDateForm").addEventListener("change", function() {
            if (this.checked) {
              this.value = "true";
            } else {
              this.value = "false";
              this.removeAttribute('value');
            }
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
          <a href="addKasus.php"  class="active">
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

    <div class="home-content">
    <div class="isi">
                <form id="formulir" action="addKasusProses.php" enctype="multipart/form-data" method="post">

                    <label for="exampleFormControlInput1">Jenis Kejahatan</label>
                    <select id="jenis_kejahatan" class="form-control" name="jenis_kejahatan" style="padding-left:20px;">
                      <?php foreach ($stmt as $data) {
                        echo '<option value="'.$data['idjenis'].'">'.$data['nama'].'</option>';
                      } ?>
                    </select>

                    <label for="exampleFormControlInput1">Negara Kejadian</label>
                    <select id="negara" class="form-control" name="negara" style="padding-left:20px;">
                      <?php 
                      $sql2 = "SELECT * FROM negara";
                      $stmt2 = $sambung->query($sql2);
                      foreach ($stmt2 as $data2) {
                        echo '<option value="'.$data2['idnegara'].'">'.$data2['namanegara'].'</option>';
                      } ?>
                    </select>

                    <label for="exampleFormControlInput1">Daerah</label>
                    <input type="text" id="daerah" class="form-control" name="daerah" placeholder="Daerah Kejadian">

            
                    <label for="exampleFormControlInput1">Lokasi</label>
                    <input type="text" id="alamat" class="form-control" name="alamat" placeholder="Alamat Kejadian">

                    <label for="exampleFormControlInput1">Tanggal Kejadian</label>
                    <input type="date" id="tanggalkejadian" class="form-control" name="tanggalkejadian">

                    <label for="exampleFormControlInput1">Waktu Kejadian</label>
                    <input type="time" style="padding:5px 20px; border: 1px solid grey; border-radius: 10px;" id="waktukejadian" class="form-control" name="waktukejadian">
                    <br>
                    
                    <label for="exampleFormControlInput1">Shift Kejadian</label>
                    <select id="shift" name="shift">
                      <option value="DAY">DAY (6 AM - 3 PM)</option>
                      <option value="EVENING">EVENING (3:01 PM - 11.59 PM)</option>
                      <option value="MIDNIGHT">MIDNIGHT (12 AM - 5.59 AM)</option>
                    </select>

                    <input type="checkbox" id="showDateForm" name="kasusSelesai"> Apakah kasus ini sudah selesai diatasi?

                    <div id="dateForm" style="display:none;">
                      <label for="tanggalselesai">Tanggal Selesai</label>
                      <input type="date" id="tanggalselesai" class="form-control" name="tanggalselesai">
                      <label for="exampleFormControlInput1">Waktu Selesai</label>
                      <input type="time" style="padding:5px 20px; border: 1px solid grey; border-radius: 10px;" id="waktuselesai" class="form-control" name="waktuselesai">
                    </div>
                    
                    <br>
                    <div id="inputContainer">
                    <div class="inputRow">
                    </div>
                    </div>
                    <button type="submit" id="buttonAdd" onclick="addInput()">Add Informasi Lainnya</button><br><br> 
                    
                    <input type="submit" value="Submit"></input>
                  </form>
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

