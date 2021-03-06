<?php
  session_start();
  include_once $_SERVER['DOCUMENT_ROOT'].'/app/core/config.php';
  include_once $_SERVER['DOCUMENT_ROOT'].'/app/core/colors.php';

  if(!isset($_SESSION['loggedin']) || !isset($_SESSION['email']) || $_SESSION['loggedin']!==true)
    header('location: '.$protocol.$_SERVER['HTTP_HOST']);

  $DB_HOST = DB_HOST;
  $DB_NAME = DB_NAME;
  $DB_USER = DB_USER;
  $DB_PASS = DB_PASS;

  if($_SERVER['REQUEST_METHOD']=='POST') {

      if(isset($_POST['action']) && !empty($_POST['action'])) {
          $cmd = htmlspecialchars($_POST['action']);

          if($cmd === "save" && isset($_POST['file']) && !empty($_POST['file'])) {
              $doc_id = htmlspecialchars($_POST['file']);
              $content = isset($_POST['content']) ? htmlspecialchars($_POST['content']):null;

              $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME",$DB_USER,$DB_PASS);
              $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              $stmt = $con->prepare("SELECT * FROM documentdetails WHERE doc_id=?");
              $stmt->execute([$doc_id]);
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              if($row && $content!==null) {
                  $filepath = $_SERVER['DOCUMENT_ROOT']."/app/storage/{$row['doc_id']}.txt";

                  $handle = fopen($filepath, 'w') or die();
                  fwrite($handle, $content);
                  fclose($handle);

                  $handle = fopen($filepath, 'r');
                  echo fread($handle,filesize($filepath));
                  fclose($handle);

              }
              $con = null;
          }
          else if($cmd === "search" && isset($_POST['q']) && !empty($_POST['q'])) {

              $q = htmlspecialchars($_POST['q']);
              $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME",$DB_USER,$DB_PASS);
              $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              $stmt = $con->prepare("SELECT email FROM masterlogin WHERE email LIKE ? ORDER BY email LIMIT 5");
              $stmt->execute(["%$q%"]);

              $dataEmpty = true;

              while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  if($data['email']==$_SESSION['email']) continue;
                  $dataEmpty = false;
              ?>
                  <span onclick="$('#liveSearch').val('<?=$data['email']?>')"><?=$data['email']?></span>
              <?php
              }

              if($dataEmpty) {
                  echo "No user found!";
              }

              $con = null;
          }
          else if($cmd === "share" && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['doc_id']) && !empty($_POST['doc_id'])) {

              try{
                  $to_email = htmlspecialchars($_POST['email']);
                  $doc_id = htmlspecialchars($_POST['doc_id']);

                  $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME",$DB_USER,$DB_PASS);
                  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                  $stmt = $con->prepare("SELECT * FROM documentdetails WHERE doc_id=?");
                  $stmt->execute([$doc_id]);
                  $row = $stmt->fetch(PDO::FETCH_ASSOC);
                  if($row) {

                      $stmt = $con->prepare("SELECT * FROM sharedetails WHERE doc_id=? AND email=?");
                      $stmt->execute([$doc_id,$to_email]);
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);
                      if(!$row) {
                          $share_id = bin2hex(random_bytes(30));

                          $stmt = $con->prepare("UPDATE documentdetails SET mode=? WHERE doc_id=?");
                          $stmt->execute(["deny",$doc_id]);
                          $stmt = $con->prepare("INSERT INTO sharedetails(share_id, email, doc_id) VALUES(?,?,?)");
                          $stmt->execute([$share_id, $to_email, $doc_id]);
                      }
                      echo "success";
                  }
              }catch(Exception $e){}
                  $con = null;
          }
          else if($cmd === "viewers" && isset($_POST['doc_id']) && !empty($_POST['doc_id'])) {

              try{
                  $doc_id = htmlspecialchars($_POST['doc_id']);

                  $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME",$DB_USER,$DB_PASS);
                  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                  $stmt = $con->prepare("SELECT * FROM documentdetails WHERE doc_id=?");
                  $stmt->execute([$doc_id]);
                  $data = $stmt->fetch(PDO::FETCH_ASSOC);
                  $date = date('Y-m-d H:i:s');

                  if($data['mode']=="deny" && $data['owner']!=$_SESSION['email']) {
                      $stmt = $con->prepare("SELECT * FROM sharedetails WHERE email=? AND doc_id=?");
                      $stmt->execute([$_SESSION['email'], $doc_id]);
                      $check = $stmt->fetch(PDO::FETCH_ASSOC);

                      if(!$check)
                          echo "home";

                      if($data['owner']!=$_SESSION['email']) {
                          $stmt = $con->prepare("UPDATE sharedetails SET last_opened=? WHERE doc_id=? AND email=?");
                          $stmt->execute([$date,$doc_id,$_SESSION['email']]);
                      }
                  }
                  $stmt = $con->prepare("SELECT share_id,sharedetails.email,fullname,doc_id,last_opened FROM sharedetails,masterlogin WHERE doc_id=? AND sharedetails.email=masterlogin.email");
                  $stmt->execute([$doc_id]);
                  while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      $diff = strtotime($date) - strtotime($data['last_opened']);

                      if($diff<=120 && $data['email']!=$_SESSION['email'])
                      {
                        $color = getColorForWord($data['fullname']);
                      ?>
                      <div class="online_user">
                        <span style="background-color: <?=$color?>;" class="profile_image" onmouseover="$(this).siblings('div').show()" onmouseout="$(this).siblings('div').hide()">
                          <?=$data['fullname'][0]?>
                        </span>
                        <div style="display:none;">
                            <span><?=$data['fullname']?></span>
                            <span>(<?=$data['email']?>)</span>
                        </div>
                      </div>

                      <?php
                      }
                  }
              }catch(Exception $e){}
                  $con = null;
          }
      }
  }
  else {
      try {
          if( $_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['action']) &&
              !empty($_GET['action']) && isset($_GET['file']) && !empty($_GET['file'])) {

              $cmd = htmlspecialchars($_GET['action']);
              $doc_id = htmlspecialchars($_GET['file']);

              if($cmd ==="view") {
                  include_once 'documentView.php';
              }
              else if($cmd === "remove") {

                  try {
                      $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME",$DB_USER,$DB_PASS);
                      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                      $stmt = $con->prepare("SELECT * FROM documentdetails WHERE doc_id=?");
                      $stmt->execute([$doc_id]);
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);
                      if($row && ($row['owner']==$_SESSION['email'])) {
                          $stmt = $con->prepare("DELETE FROM documentdetails WHERE doc_id=? and owner=?");
                          $stmt->execute([$doc_id,$_SESSION['email']]);
                          $filepath = $_SERVER['DOCUMENT_ROOT']."/app/storage/{$row['doc_id']}.txt";
                          if(file_exists($filepath))
                            unlink($filepath);
                      }
                      header('location: '.$protocol.$_SERVER['HTTP_HOST']."/app/view/dashboard.php");

                  } catch(PDOException $e) {
                      return "Some error occurred!";
                  }

                  $con = null;

                  throw new Exception();
              }
              else
                  throw new Exception();
          }
          else
              throw new Exception();
      }
      catch(Exception $e) {
          header('location: '.$protocol.$_SERVER['HTTP_HOST']."/app/view/dashboard.php");
      }
  }
