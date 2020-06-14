<?php

class Document {
  protected $DB_HOST = DB_HOST;
  protected $DB_NAME = DB_NAME;
  protected $DB_USER = DB_USER;
  protected $DB_PASS = DB_PASS;

  public $doc_name = null;

  function set($doc_name=null) {
    if(isset($doc_name))
      $this->doc_name = $doc_name;
    else
      $this->doc_name = "Untitled Document_".bin2hex(random_bytes(3));
  }

  function checkFileName() {
    $result = array('valid'=>false, 'msg'=>null);
    if(empty($this->doc_name))
      $this->set();

    if(strlen($this->doc_name)>30) {
      $result['msg'] = "File name can be maximum 30 characters long.";
    }
    else {
      if(!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9-_ ]+$/", $this->doc_name))
        $result['msg'] = "File name can only have alphabet, number, space, hyphen and underscore.";
        else $result['valid'] = true;
    }
    return $result;
  }

  function createDocument() {
    $result = array('valid'=>false, 'msg'=>null);
    $doc_id = bin2hex(random_bytes(30));

    $result = array('valid'=>false, 'msg'=>null);

    try {
      $con = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_NAME",$this->DB_USER,$this->DB_PASS);
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $con->prepare("INSERT INTO documentdetails (doc_id, doc_name, owner) VALUES (?,?,?)");
      $stmt->execute([$doc_id, $this->doc_name, $_SESSION['email']]);
      $filepath = $_SERVER['DOCUMENT_ROOT']."/app/storage/{$doc_id}.txt";
      $fp = fopen($filepath, "w");
      fwrite($fp,"");
      fclose($fp);

      //Redirect to the edit page.
      header('location: '.$GLOBALS['protocol'].$_SERVER['HTTP_HOST']."/app/view/document.php?action=view&file=$doc_id");

    } catch(PDOException $e) {
      return "Some error occurred!";
    }

    $con = null;
  }
}
