<?php
  try {
    $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME",$DB_USER,$DB_PASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $con->prepare("SELECT * FROM documentdetails WHERE doc_id=?");
    $stmt->execute([$doc_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $filepath = "../storage/{$data['doc_id']}.txt";
    $fp = fopen($filepath, "r");

    $data['file_contents'] = htmlspecialchars(filesize($filepath)>0 ? fread($fp, filesize($filepath)) : "");
    fclose($fp);
    
  } catch(PDOException $e) {
    return "Some error occurred!";
  }

  $con = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?=WEBSITE_NAME." | ".$data['doc_name']?></title>
  <script src="../assets/scripts/jquery-3.5.1.min.js"></script>
  <script src="../assets/scripts/master.js"></script>
  <script src="../assets/scripts/documentView.js"></script>
  <link rel="stylesheet" href="../assets/style/master.css">
  <link rel="stylesheet" href="../assets/style/document.css">
</head>
<body>
  <?php require_once 'header.php';?>
  <button onclick="$('#shareDialog').show();">Share</button>
  <br/>
  <section id="shareDialog">
    <label for="share">Share</label>
    <ul id="selectedShareList"></ul>
    <input type="text" id="liveSearch" onkeyup="liveSearchNow(this.value)" placeholder="Enter an email..">
    <div id="liveSearchResults">
    </div>
    <button onclick="">Share</button>
    <button onclick="closeShareDialog()">Cancel</button>
  </section>
  <textarea id="docEditor"><?=$data['file_contents'];?></textarea>
  <button onclick="saveDocument('<?=$data['doc_id']?>')">Save</button>
  
</body>
</html>