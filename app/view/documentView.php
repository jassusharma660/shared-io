<?php
  try {
    $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME",$DB_USER,$DB_PASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $con->prepare("SELECT * FROM documentdetails WHERE doc_id=?");
    $stmt->execute([$doc_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($data['mode']=="deny" && $data['owner']!=$_SESSION['email']) {
      $stmt = $con->prepare("SELECT * FROM sharedetails WHERE email=? AND doc_id=?");
      $stmt->execute([$_SESSION['email'], $doc_id]);
      $check = $stmt->fetch(PDO::FETCH_ASSOC);
      if(!$check)
      header('location: '.$GLOBALS['protocol'].$_SERVER['HTTP_HOST']."/app/view/dashboard.php");

    }
    $filepath = $_SERVER['DOCUMENT_ROOT']."/app/storage/{$doc_id}.txt";
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
  <script src="/app/assets/scripts/jquery-3.5.1.min.js"></script>
  <script src="/app/assets/scripts/master.js"></script>
  <script src="/app/assets/scripts/documentView.js"></script>
  <link rel="stylesheet" href="/app/assets/style/master.css">
  <link rel="stylesheet" href="/app/assets/style/document.css">
</head>
<body>
  <main>
    <?php if(isset($error)) print $error;?>
    <section id="left_pane">
      <?php
        require_once $VIEW_PATH.'header.php';
      ?>
    </section>
    <section id="right_pane">
      <div id="action_bar">

        <div id="viewers"></div>

        <button onclick="$('#shareDialog').show();" class="accent_button" id="shareDocument">Share</button>
        <section id="shareDialog">
          <section class="container">
            <label for="share">Share</label>
            <input type="hidden" value="<?=$data['doc_id']?>" id="doc_id">
            <input type="text" id="liveSearch" onkeyup="liveSearchNow(this.value)" placeholder="Enter an email">
            <div id="liveSearchResults">
            </div>
            <button onclick="shareWith($('#liveSearch').val())" class="accent_button">Share</button>
            <button onclick="closeShareDialog()" class="accent_button cancel">Cancel</button>
          </section>
        </section>
      </div>
      <section>
        <textarea id="docEditor"><?=$data['file_contents'];?></textarea>
        <button onclick="saveDocument('<?=$data['doc_id']?>')">Save</button>
      </section>
    </section>
</body>
</html>
