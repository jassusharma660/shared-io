<?php
session_start();
include_once '../core/config.php';

if(!isset($_SESSION['loggedin']) || !isset($_SESSION['email']) || $_SESSION['loggedin']!==true)
  header('location: '.DOCUMENT_ROOT);

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
    <script src="../assets/scripts/jquery-3.5.1.min.js"></script>
    <script src="../assets/scripts/dashboard.js"></script>
    <link rel="stylesheet" href="../assets/style/master.css">
    <link rel="stylesheet" href="../assets/style/dashboard.css">
</head>
<body>
    <?php
        require_once './header.php';
    ?>
    <section id="existingDocuments">
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
                            <td onclick='openDocument(\"{$data['doc_id']}\")'>{$data['doc_name']}</td>
                            <td>{$data['owner']}</td>
                            <td>{$data['created']}</td>
                            <td>
                            <div class='menu'>
                                <button class='menubtn'>Menu</button>
                                <div class='menu-content'>
                                    <a onclick='openDocument(\"{$data['doc_id']}\")'>View</a>
                                    <a onclick='removeDocument(\"{$data['doc_id']}\")'>Remove</a>
                                </div>
                            </div> </td>
                          </tr>";
                }
                
                if($dataEmpty) {
                    echo "No files created!";
                }
                
              } catch(PDOException $e) {
                echo "E:303,Some error occurred!";
              }
          
              $con = null;
        ?>
        </table>
    </section>
    <?php if(isset($error)) print $error;?>

    <button id="createDocument">Add document</button>

    <section id="createDocumentDialog">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="doc_name">Document Name</label><br/>
            <input type="text" name="doc_name" placeholder="Untitled Document"><br/>
            <button type="submit" name="action" value="creatDocument">Create</button>
        </form>
        <button class="cancel">Cancel</button>
    </section>
</body>
</html>