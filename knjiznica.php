<!DOCTYPE html>
<html lang="en">
<style>
<?php  include'css/style.css'; ?>
</style>
<?php

session_start();
ini_set('memory_limit', '-1');
 ?>

 <?php
if(!isset($_SESSION["id"])){
  header("Location: index.php");
}
if(time()-$_SESSION["timer"]>300){
  header("Location: odjava.php");
}
else{
  $_SESSION["timer"]=time();
}

  ?>
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Knjigomat</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="css/landing-page.min.css" rel="stylesheet">

</head>

<body>
<?php


$_SERVER['REQUEST_METHOD']
?>




  <!-- Navigation -->
  <nav class="navbar navbar-light  static-top" >
    <div class="container" id="navbar">
      <a class="navbar-brand" href="knjiznica.php">Knjigomat</a>
        <a class="navbar-brand" href="profil.php">Profil</a>
        <a class="navbar-brand" href="odjava.php">Odjava</a>

    </div>
  </nav>

  <!-- Masthead -->
  <header class="masthead text-white text-center">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-xl-9 mx-auto">
          <h1 class="mb-5">Pozdravljen/a <?php echo str_replace('"', '',  $_SESSION["ime"])." ".str_replace('"', '',  $_SESSION["priimek"]);   ?>.</h1>
        </div>
        <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
          <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
              <div class="form-group">
                <select name="kategorija" class="form-control">
                  <option value="naslov">Naslov</option>
                  <option value="vrsta">Žanr</option>
                  <option value="avtor">Avtor</option>

                </select>

              </div>
              <div class="form-group">

                <input type="text" class="form-control" placeholder="Ključna beseda ..." name="kljucnaBeseda" id="pwd">
              </div>

              <button type="submit" name="isciKnj" class="btn btn-block btn-lg btn-primary">Išči</button>
            </form>
        </div>
      </div>
    </div>
  </header>
  <?php


  $fields = array("method" => "mymethod", "email" => "myemail");
  //echo $isci." ".$cat;ž
  $url1="http://localhost:8880/projekt/rest/knjige/iskanje/vrsta&knjiga";
  $url = "http://localhost:8880/projekt/rest/knjige/iskanje/";
    $fields = json_encode($fields);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "ziga2:zigaziga1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result= curl_exec($ch);

    curl_close($ch);
    $obj = json_decode($result,true);




?>
  <?php

    $url="http://localhost:8880/projekt/rest/knjige/iskanje/vrsta&knjiga";
    if(array_key_exists('isciKnj',$_POST)){
      if(($_POST["kljucnaBeseda"]!="")&&isset($_POST["kategorija"])!=""){

        $isci=$_POST["kljucnaBeseda"];
        $cat=$_POST["kategorija"];

        $url = "http://localhost:8880/projekt/rest/knjige/iskanje/".$cat."&".$isci;
        $url= str_replace(" ","%20",$url);


      }
      else{
        $url = "http://localhost:8880/projekt/rest/knjige/iskanje/";
      }
      $fields = array("method" => "mymethod", "email" => "myemail");

      //echo $isci." ".$cat;ž


        $fields = json_encode($fields);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result= curl_exec($ch);

        curl_close($ch);

        $obj = json_decode($result,true);
        

}

?>
  <!-- Icons Grid -->


  <?php  ?>
  <section class="features-icons bg-light text-center" >
    <div class="container">
      <div class="row" style="width:100%">
        <?php

        if(isset($obj)){

        foreach($obj as $i) { //foreach element in $arr

          if(isset($i['slika'])) {
          $bytes=$i['slika'];
          //echo '<img src="data:image/jpeg;base64,'.base64_encode($str->load()) .'" />';
          $string = implode(array_map("chr", $bytes)); //Convert it to string

          $base64 = base64_encode($string); //Encode to base64
          if($i["stanje"]!="navoljo"){
            $img = "<img src= 'data:image/jpeg;base64, $base64' style='height:250px; opacity:0.5'/>"; //Create the image
          }
          else{
            $img = "<img src= 'data:image/jpeg;base64, $base64' style='height:250px'/>";
          }
          }
          else{
            $img = "<img src= 'img/noimg.jpg' style='height:250px'/>";
          }

          //$stra=implode(" ",$i['slika']);

          //$j=base64_encode($stra);

          //echo $str;

          ?>
        <div style="disply:inline-block" class="col-lg-4">
        <div class="features-icons-icon " style="height:300px; display:inline-block">

            <div>
              <a href="knjiga.php?id=<?php echo $i['id'] ?>">

              <?php echo $img ?>

              </a>
            </div>


        </div>
        <?php
        if($i["stanje"]!="navoljo"){
          ?>
          <h3 style="opacity:0.5"> <?php echo $i["naslov"] ?></h3>
          <?php
        }
        else{
          ?>
          <h3> <?php echo $i["naslov"] ?></h3>
          <?php
        }

         ?>

      </div>
    <?php } } ?>


      </div>
    </div>
  </section>




  <!-- Bootstrap core JavaScript -->
  <script src="javascript/basicjs.js"></script>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
