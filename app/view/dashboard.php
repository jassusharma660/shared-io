<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'].'/app/core/config.php';

if(!isset($_SESSION['loggedin']) || !isset($_SESSION['email']) || $_SESSION['loggedin']!==true)
  header('location: '.$protocol.$_SERVER['HTTP_HOST']);

$DB_HOST = DB_HOST;
$DB_NAME = DB_NAME;
$DB_USER = DB_USER;
$DB_PASS = DB_PASS;

if(isset($_POST['action'])) {

    //Create Document action
    if(htmlspecialchars($_POST['action'])=="creatDocument" && isset($_POST['doc_name'])) {
        include_once '../core/file.helper.php';
        $doc = new Document();
        if(isset($_POST['doc_name']))
            $doc->set(htmlspecialchars($_POST['doc_name']));
        else
            $doc->set();

        $result = $doc->checkFileName();

        if($result['valid'] === false) {
            $error = $result['msg'];
        }
        else
            $doc->createDocument();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=WEBSITE_NAME?> | Home</title>
    <script src="/app/assets/scripts/jquery-3.5.1.min.js"></script>
    <script src="/app/assets/scripts/dashboard.js"></script>
    <link rel="stylesheet" href="/app/assets/style/master.css">
    <link rel="stylesheet" href="/app/assets/style/dashboard.css">
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
        <button id="createDocument" class="accent_button">Create a document</button>
        <section id="createDocumentDialog">
            <section class="container">
              <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="doc_name">Document Name<b>*Optional</b></label><br/>
                <input type="text" name="doc_name" placeholder="Untitled Document">
                <button type="submit" name="action" value="creatDocument" class="accent_button">Create</button>

                <button class="accent_button cancel" onclick="return false;">Cancel</button>
            </form>
          </section>
        </section>
      </div>
      <section id="existingDocuments">
        <h2>Available files</h2>
          <table>
          <?php
              try {
                  $con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME",$DB_USER,$DB_PASS);
                  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                  $stmt = $con->prepare("SELECT doc_id, doc_name, owner, created FROM documentdetails WHERE owner=? ORDER BY created DESC");
                  $stmt->execute([$_SESSION['email']]);

                  $dataEmpty = true;

                  while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      $dataEmpty = false;
                      echo "<tr>
                              <td><img class='circle' src='/app/assets/images/icons/file-96.png'/></td>
                              <td style='font-weight:bold;' onclick='openDocument(\"{$data['doc_id']}\")'>{$data['doc_name']}</td>
                              <td>{$data['owner']}</td>
                              <td>{$data['created']}</td>
                              <td onclick='openDocument(\"{$data['doc_id']}\")'>
                                <img src='/app/assets/images/icons/eye-96.png' alt='View' title='View'/>
                              </td>
                              <td onclick='removeDocument(\"{$data['doc_id']}\")'>
                                <img src='/app/assets/images/icons/delete-file-96.png' alt='Remove' title='Remove'/>
                              </td>
                            </tr>";
                  }

                  if($dataEmpty) {
                      echo "No files created!";
                  }

                } catch(PDOException $e) {
                  echo "Some error occurred!";
                }

                $con = null;
          ?>
          </table>
      </section>
    </section>
  </main>
</body>
</html>
