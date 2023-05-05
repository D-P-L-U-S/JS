function date(){
    var jours = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
    var mois = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
    var heure = new Date().getHours();
    if(heure<10) heure = "0"+heure;
    var min = new Date().getMinutes();
    if(min<10) min = "0"+min;
    var sec = new Date().getSeconds();
    if(sec<10) sec = "0"+sec;
    var jour = new Date().getDay();
    var cemois = new Date().getMonth();
    var annee = new Date().getFullYear();
    var dat = new Date().getDate();
    if(dat<10) dat = "0"+dat;
    var valuedate = jours[jour] + ", " + dat + " " + mois[cemois] + " " + annee;
    var valueheure = heure + " : " + min + " : " + sec;

    var element1 = document.querySelectorAll('.date').forEach( element =>{
        element.innerHTML = valuedate;
    });
    var element2 = document.querySelectorAll('.heure').forEach( element =>{
        element.innerHTML = valueheure;
    });
}

setInterval(date,1000);

function menuu(){
    var monmenu = document.querySelector('.monmenu');
    monmenu.classList.toggle('active');
}

function theme(){
    document.querySelector('.choixthemes').classList.toggle('active2');
}

function compte(){
    document.querySelector('.infoscompte').classList.toggle('active3');
}