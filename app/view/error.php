<html>
<head>
  <title><?=WEBSITE_NAME?> | Oh No!</title>
  <style>
    body {
      text-align:center;
      background:#eee;
      font-family:Arial;
    }
    main {
      position:relative;
      top:50%;
      transform:translateY(-50%);
    }
    .bigger {
      font-size: 5em;
      font-weight: bold;
    }
    .big {
      font-weight: bold;
      color:#777;
      font-size: 2em;
    }
    a {
      color: #000;
    }
  </style>
</head>
<body>
  <main>
    <div class="bigger">
      &#9869; <br/>
      <?=$data['error_code']?>
    </div>
    <div class="big"><?=$data['error_msg']?></div>
    <a href="./">Take me back to HOME</a>
  </main>
</body>
</html>
