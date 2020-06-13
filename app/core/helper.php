<?php

/****************************
 * Validator functions
 ****************************/
class Validator
{
  protected $DB_HOST = DB_HOST;
  protected $DB_NAME = DB_NAME;
  protected $DB_USER = DB_USER;
  protected $DB_PASS = DB_PASS;

  function validName($fullname)
  {
    $result = array('valid'=>true,'msg'=>"Some error occurred!");
    if(empty($fullname)) {
      $result['msg'] = "Name can't be empty!";
    }
    else {
      if(strlen($fullname)>30)
        $result['msg'] = "Name should be atmost 30 characters long.";
      else {
        if(!preg_match("/^[a-zA-Z ]+$/", $fullname))
          $result['msg'] = "Name can only have alphabets and space.";
        else $result['valid'] = true;
      }
    }
    return $result;
  }

  function validEmail($email)
  {
    $result = array('valid'=>false,'msg'=>"Some error occurred!");
    if(empty($email)) {
       $result['msg'] = "Email should not be empty!";
    }
    else {
      if(strlen($email)<7 || strlen($email)>100)
        $result['msg'] = "Email should be 7-100 characters long.";
      else {
        $regex_email = "/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/";
        if(!preg_match($regex_email, $email))
          $result['msg'] = "Email is not valid!";
        else
          $result['valid'] = true;
       }
     }
    return $result;
  }

  function validPassword($password)
  {
    $result = array('valid'=>false,'msg'=>"Some error occurred!");
    if(empty($password)) {
      $result['msg'] = "Password can't be empty!<br/>";
    }
    else {
      if(strlen($password)<6 || strlen($password)>20)
        $result['msg'] = "Password should be 6-15 characters long.";
      else {
        $regex_password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/";

        if(!preg_match($regex_password,$password))
          $result['msg'] = "Password should contain atleast an uppercase, a lowercase, a special character, and a number!";
        else
          $result['valid'] = true;
      }//End-StringLen
    }
    return $result;
  }

  function checkUserExist($email) {
    try {
      $con = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_NAME",$this->DB_USER,$this->DB_PASS);
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $con->prepare("SELECT fullname, pass, email FROM masterlogin WHERE email=?");
      $stmt->execute([$email]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if(!$row)
        return false;
      else
        return $row;
      
    } catch(PDOException $e) {
      echo "Some error occurred!";
    }

    $con = null;
  }
}

/*************************
 * Signup process handler
 *************************/
class Signup extends Validator
{
  public $fullname;
  public $email;
  public $password;

  function __construct($fullname, $email, $password)
  {
    $this->fullname = htmlspecialchars(trim($fullname));
    $this->email = htmlspecialchars(trim($email));
    $this->password = htmlspecialchars(trim($password));
  }
  
  function addUserToDatabase() {
    $this->password = password_hash($this->password, PASSWORD_DEFAULT);

    try {
      $con = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_NAME",$this->DB_USER,$this->DB_PASS);
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $con->prepare("INSERT INTO masterlogin (fullname, email, pass) VALUES (?,?,?)");
      $stmt->execute([$this->fullname, $this->email, $this->password]);

    } catch(PDOException $e) {
      return "Some error occurred!".$e->getMessage();
    }

    $con = null;
  }
}


/*************************
 * Login process handler
 *************************/
class Login extends Validator {
  public $email;
  public $password;

  function __construct($email, $password) {
    $this->email = htmlspecialchars(trim($email));
    $this->password = htmlspecialchars(trim($password));
  }

  function checkLogin($result) {
    if(password_verify($this->password, $result['pass'])) {
      session_destroy();
      session_start();
      $_SESSION['fullname'] = $result['fullname'];
      $_SESSION['email'] = $result['email'];
      $_SESSION['loggedin'] = true;
      header('location:./');
    }
  }
}