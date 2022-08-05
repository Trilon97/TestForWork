function getClassById(id)
{
   return document.querySelectorAll(id);
}

function $ (id) //return ElementById but shorter
{
  return document.getElementById(id);
}

function SecretsVisibility(elem)//swich between the visibility of the sections
{
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
