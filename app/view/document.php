<?php
    session_start();
    include_once '../core/config.php';
   
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
                    $filepath = "../storage/{$row['doc_id']}.txt";                        
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
                    $append = "<li>{$data['email']}<button onclick=$(this).closest('li').remove()>x</button></li>";
                    echo "<div onclick='$(\"#selectedShareList\").append(\"<li>{$data['email']}<a onclick=$(this).closest(\"li\").remove()>x</a></li>\");'>{$data['email']}</div>";
                    //echo "<div onclick='$('#selectedShareList').append('');'>{$data['email']}</div>";
                }
                
                if($dataEmpty) {
                    echo "No user found!";
                }
        
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
                        if($row) {
                            $stmt = $con->prepare("DELETE FROM documentdetails WHERE doc_id=?");
                            $stmt->execute([$doc_id]);
                            $filepath = "../storage/{$row['doc_id']}.txt";
                            unlink($filepath);
                        }
                        //Redirect to the edit page.
                
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
            header('location:'.DOCUMENT_ROOT);
        }
    }