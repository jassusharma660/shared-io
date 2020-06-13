<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/app/core/config.php';

if(isset($_SESSION['loggedin']) && isset($_SESSION['email']) && $_SESSION['loggedin']==true)
  header('location: '.$protocol.$_SERVER['HTTP_HOST']."/app/view/dashboard.php");

include_once $CORE_PATH.'helper.php';

$hide_view = "signup";

if($_SERVER['REQUEST_METHOD']=="POST") {

  //Signup form submission
  if(isset($_POST['action']) && $_POST['action']=="signup") {
    $hide_view = "login";
    if(isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['password'])) {

      $reg = new Signup($_POST['fullname'], $_POST['email'], $_POST['password']);

      if(!($result = $reg->validName($reg->fullname))['valid'] ||
         !($result = $reg->validEmail($reg->email))['valid'] ||
         !($result = $reg->validPassword($reg->password))['valid'])
        $error = $result['msg'];
      else {
        //Check if user exist
        if($reg->checkUserExist($reg->email)==false) {
          $reg->addUserToDatabase();
          $reg = null;
          $success = "Successfully registered! Login Now";
          $hide_view = "signup";
        }
        else
          $error = "User already exist! Plese login.";
      }
    }
    else
      $error = "Reload the page or try later!";
  }
  else if(isset($_POST['action']) && $_POST['action']=="login") {
    $login = new Login($_POST['email'], $_POST['password']);

    if(!($result = $login->validEmail($login->email))['valid'] ||
       !($result = $login->validPassword($login->password))['valid'])
        $error = $result['msg'];
      else {
        //Check if user exist
        if(($row = $login->checkUserExist($login->email))!==false) {
          $error = $login->checkLogin($row);
        }
        else
          $error = "User does not exist! Please signup first.";
      }
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=WEBSITE_NAME?> | Home</title>
    <script src="/app/assets/scripts/jquery-3.5.1.min.js"></script>
    <script src="/app/assets/scripts/index.js"></script>
    <link rel="stylesheet" href="/app/assets/style/index.css">
    <style>
      <?php
        $selector = ($hide_view=="signup")? "#toggleLoginDiv": "#toggleSignupDiv";echo $selector; ?>{
          display: none;
        }
      <?='#'.$hide_view?>Section {
        display: none;
      }
    </style>
  </head>
  <body>
    <main>
      <section id="left_pane">

        <section class="header_area">
          <?php
          require_once $VIEW_PATH.'header.php';
          ?>
          <div id="header_option_container">
            <span class="line_button" id="toggleLoginDiv">Login</span>
            <span class="line_button" id="toggleSignupDiv">Create Account</span>
          </div>
        </section>

        <?php if(isset($error))
                echo "<div id='error'>".$error."</div>";
              else if(isset($success))
                echo "<div id='success'>".$success."</div>";
        ?>
        <section id="action_area">
          <article id="loginSection">
            <h2>Login to shared-io</h2>
            <form class="login" name="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
              <input type="email" name="email" placeholder="Email" value="<?php if(isset($login)) echo $login->email; ?>"><br/>
              <input type="password" name="password" placeholder="Password"><br/>
              <button type="submit" name="action" value="login">Login</button>
            </form>
          </article>
          <article id="signupSection">
            <h2>Create a shared-io account</h2>
            <form class="signup" name="signup" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
              <input type="text" name="fullname" placeholder="Full Name" value="<?php if(isset($reg)) echo $reg->fullname; ?>" required><br/>
              <input type="email" name="email" placeholder="Email" value="<?php if(isset($reg)) echo $reg->email; ?>" required><br/>
              <input type="password" name="password" placeholder="Password" required><br/>
              <button type="submit" name="action" value="signup">Create Account</button>
            </form>
         </article>
        </section>
      </section>
      <section id="right_pane"></section>
    </main>
    <?php
      require_once $VIEW_PATH.'footer.php';
    ?>
  </body>
</html>
