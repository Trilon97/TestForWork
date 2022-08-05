function getClassById(id)
{
   return document.querySelectorAll(id);
}

function $ (id) //return ElementById but shorter
{
  return document.getElementById(id);
}

function SecretsVisibility(elem)
{
  console.log(elem.target.name)
  if (getClassById("."+elem.target.name)[0].style.display == "inline")
  {
    getClassById("."+elem.target.name).forEach(function(el) {
       el.style.display = 'none';
    });
  } else
  {
    getClassById("."+elem.target.name).forEach(function(el) {
       el.style.display = 'inline';
    });
  }
}
$("addSecretsVisibility").addEventListener("click",SecretsVisibility)
$("readSecretsVisibility").addEventListener("click",SecretsVisibility)

function checkReadLimit()
{
  if ($("addSecretReadLimit").value < 1)
  {
    $("addSecretReadLimit").value = "1"
  }
}
$("addSecretReadLimit").addEventListener("change",checkReadLimit)

function checkExpireDate()
{
  if ($("addSecretExpireDate").value < 0)
  {
    $("addSecretExpireDate").value = "0"
  }
}
$("addSecretExpireDate").addEventListener("change",checkExpireDate)

function clearAddParameters()
{
    $("addSecretText").value = ""
    $("addSecretReadLimit").value = ""
    $("addSecretExpireDate").value = ""
}
$("clearAddSecretsParameters").addEventListener("click",clearAddParameters)

/*elseif($check == 1)
        {
          echo "<a class='warning'>This code is not exist!</a><br>";
        }elseif($check == 2)
        {
          echo "<a class='warning'>Reading limit is out!</a><br>";
        }elseif($check == 3)
        {
          echo "<a class='warning'>The secrets time has expired!</a><br>";
        }*/
