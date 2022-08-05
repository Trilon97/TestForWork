<?php
$errors = [];

function expireDate($minute) //add Minutes to the current time for the expire day
{
  $now = new \DateTime('now');
  $start = $now->format('Y-m-d H:i');
  return date('Y-m-d H:i',strtotime('+'.$minute.' minutes',strtotime($start)));
}

function UrlAlreadyTaken($url)
{
  $strJsonFileContents = file_get_contents("database.json");
  $json = json_decode($strJsonFileContents,true);
  if ($json[$url] != "")
  {
    return true;
  }else
  {
    return false;
  }
}

function printOutWarning() //print out all the warning at the adding section
{
  global $errors;
  if (count($errors) > 0)
  {
    echo "<br>";
    for ($i = 0;$i<count($errors);$i++)
    {
      echo "<a class='warning'>".$errors[$i]."</a><br>";
    }
  }
}

function loadDataBase($fileName)
{
  $strJsonFileContents = file_get_contents($fileName);
  return json_decode($strJsonFileContents,true);
}

function getElementFromDatabase($url,$element)
{
  $json = loadDataBase("database.json");
  return $json[$url][$element];
}

function addToDataBase($currentJSON)
{
  global $errors;
  if (!empty($_POST["url"]) && count($errors) == 0)
  { 
    $obj = new stdClass(); //Creation of array for JSON
    $obj->secret = $_POST["secret"];
    $obj->limit = $_POST["limit"];
    $obj->expire = expireDate($_POST["expire"]);
    $currentJSON[$_POST["url"]] = $obj;
    file_put_contents("database.json", json_encode($currentJSON));
  }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["url"]))
{
   if (empty($_POST["secret"]))
   {
     array_push($errors,"You must add the secret!");
   }
  if (empty($_POST["limit"]))
   {
     array_push($errors,"You must add the limit!");
   }
  if (empty($_POST["expire"]))
   {
     array_push($errors,"You must add the expire!");
   }
}

function checkReadURL()
{
  if (!UrlAlreadyTaken($_POST["readURL"]) || empty($_POST["readURL"]))
  {
    return 1;
  }elseif (getElementFromDatabase($_POST["readURL"],"limit")==0)
  {
    return 2;
  }
  $now = new \DateTime('now');
  $start = $now->format('Y-m-d H:i');
  if ($start > getElementFromDatabase($_POST["readURL"],"expire"))
  {
      return 3;
  }
  
  return 0;
}

function getUrlSecret()
{
  $json = loadDataBase("database.json");
  $json[$_POST["readURL"]]["limit"]--;//change the limit by 1
  file_put_contents("database.json", json_encode($json));//Save the limit change
  return getElementFromDatabase($_POST["readURL"],"secret");
}
?>

<html>
  <head>
    <title>PHP Test</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h1>Secret server</h1>
    <label>This is an API of a secret service. You can save your secret by using the API. You can restrict the access of a secret after the certen number of views or after a certen period of time.</label><br><br>
    
    <?php
      if (!empty($_POST["url"]) && count($errors) == 0) //Successful secret creation
      {
        echo "Successful secret creation! Your secret code: ".$_POST["url"]."<br>";
      }
    ?>
    <button id="addSecretsVisibility" name="add">Add Secrets</button><br>
    <h2 class="add">Add Secret</h2><br>
    <form method="post"> <!-- Add option -->
      <label class="add">Secret: </label><br>
      <input type="text" class="add" id="addSecretText" name="secret"><a class="add">*required</a><br>
      <label class="add">Read number: </label><br>
      <input type="number" class="add" id="addSecretReadLimit" name="limit"><a class="add">*required and must be number and greater than 0</a><br>
      <label class="add">Expire date: </label><br>
      <input type="number" class="add" id="addSecretExpireDate" name="expire"><a class="add">*required and must be number but if it's 0 it will never expire</a><br>
      <button id="addSecret" class="add">Add</button>
      <?php
        $json = loadDataBase("database.json");
        do
        {
          $randomURL = rand(0,10**10);
        }while(UrlAlreadyTaken($randomURL));//URL generation until find free number
        echo '<input name="url" value="'.$randomURL.'" type="hidden">';
        addToDataBase($json);
      ?>
    </form>
    <button id="clearAddSecretsParameters" class="add">Clear</button><br>
    <?php
      printOutWarning();
    ?>
    <button id="readSecretsVisibility" name="read">Read Secrets</button><br>
    <h2 class="read">Read Secret</h2><br>
    <form method="post"><!-- Read option -->
      <label class="read">Read code: </label><br>
      <input type="text" class="read" id="readSecretText" name="readURL"><a class="add"></a><br>
      <button id="readSecret" class="read">Read</button>
      <?php
        $check = checkReadURL();
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
          echo "<br>";
          if ($check == 0)//Check if it have any error
          {
            echo "The secret is :".getUrlSecret()."<br>";
            echo "Remaining read: ".getElementFromDatabase($_POST["readURL"],"limit");
          }elseif($check == 1)//Non existing code
          {
            echo "<a class='warning'>This code is not exist!</a><br>";
          }elseif($check == 2)//Limit reached
          {
            echo "<a class='warning'>Reading limit is reached!</a><br>";
          }elseif($check == 3)//Time limit is expired
          {
            echo "<a class='warning'>The secrets time has expired!</a><br>";
          }
        }
      ?>
    </form>
</script>
    <script src="script.js"></script>
  </body>
</html>
