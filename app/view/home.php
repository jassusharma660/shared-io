<?php
include_once CORE.'helper.php';

$hide_view = "signup";

if($_SERVER['REQUEST_METHOD']=="POST") {

  //Signup form submission
  if(isset($_POST['action']) && $_POST['action']=="signup") {
    if(isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['password'])) {

      $reg = new Signup($_POST['fullname'], $_POST['email'], $_POST['password']);

      if(!($result = $reg->validName($reg->fullname))['valid'] ||
         !($result = $reg->validEmail($reg->email))['valid'] ||
         !($result = $reg->validPassword($reg->password))['valid'])
        $error = $result['msg'];
      else {
        //Check if user exist
        if(!$reg->checkUserExist($reg->email)===true) {
          $reg->addUserToDatabase();
        }
        else
          $error = "User already exist! Plese login.";
      }
    }
    else
      $error = "Reload the page or try later!";
    $hide_view = "login";
  }
  else if(isset($_POST['action']) && $_POST['action']=="login") {
    echo "login";
  }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=WEBSITE_NAME?> | Home</title>
    <script src="<?=ASSETS?>/scripts/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="<?=ASSETS?>/style/master.css">
    <link rel="stylesheet" href="<?=ASSETS?>/style/index.css">
    <style>
    #<?=$hide_view?>Section {
      display: none;
    }
    </style>
  </head>
  <body>
    <br/>
    <?php if(isset($error)) print $error;?>
    <div>
      <a style="cursor:pointer;" id="toggleLoginDiv">login</a>
      <a style="cursor:pointer;"  id="toggleSignupDiv">signup</a>
    </div>
    <section id="loginSection">
      <form class="login" name="login" action="" method="post">
        <input type="email" name="email" placeholder="Email Address"><br/>
        <input type="password" name="password" placeholder="Password"><br/>
        <button type="submit" name="action" value="login">login</button>
      </form>
    </section>
    <section id="signupSection">
      <form class="signup" name="signup" action="" method="post">
        <input type="text" name="fullname" placeholder="Full Name" value="<?php if(isset($reg)) echo $reg->fullname; ?>" required><br/>
        <input type="email" name="email" placeholder="Email Address" value="<?php if(isset($reg)) echo $reg->email; ?>" required><br/>
        <input type="password" name="password" placeholder="Password" required><br/>
        <button type="submit" name="action" value="signup">Signup</button>
      </form>
    </section>
    <script>
      $(function(){
        $('#toggleLoginDiv').click(function(){
          $('#loginSection').show();
          $('#signupSection').hide();
        });
        $('#toggleSignupDiv').click(function(){
          $('#loginSection').hide();
          $('#signupSection').show();
        });
      });
    </script>
  </body>
</html>
