
//--------------------------------------------------
//Gestion de l'événement sur le select (nationalite)
//--------------------------------------------------
var select_nationatlite = document.getElementById("select_nationalite");
select_nationatlite.addEventListener('change', function(e){

    document.location.href = e.target.value;

});


