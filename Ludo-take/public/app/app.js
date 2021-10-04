// Définition de toutes les variables dont on aura besoin pour dynamiser le formulaire d'inscription.
const slidePage = document.querySelector(".slide-page");
const nextBtnFirst = document.querySelector(".firstNext");
const prevBtnSec = document.querySelector(".prev-1");
const submitBtn = document.getElementById("user_form_submit");
const progressText = document.querySelectorAll(".step p");
const progressCheck = document.querySelectorAll(".step .check");
const bullet = document.querySelectorAll(".step .bullet");
const checkMail = document.getElementById("user_form_mail");
const checkPass = document.getElementById("user_form_plainPassword");
let current = 1;

submitBtn.textContent = "Valider";


// Script permettant au bouton "suivant" de la première partie du formulaire d'inscription, de faire disparaître cette dernière et apparaître la deuxième partie.
nextBtnFirst.addEventListener("click", function(event){
  event.preventDefault()
      slidePage.style.display = "none",
      bullet[current - 1].classList.add("active"),
      progressCheck[current - 1].classList.add("active"),
      progressText[current - 1].classList.add("active"),
      current += 1
});


// Script permettant au bouton "valider" de soummettre le formulaire et de renvoyer à l'accueil si ce dernier est correct.
submitBtn.addEventListener("click", function(){
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");
  current += 1;
}
);


// Script permettant au bouton "précedent" de la deuxième partie du formulaire d'inscription, de faire disparaître cette dernière et apparaître la premère partie.
prevBtnSec.addEventListener("click", function(event){
  event.preventDefault();
  slidePage.style.display = "block";
  bullet[current - 2].classList.remove("active");
  progressCheck[current - 2].classList.remove("active");
  progressText[current - 2].classList.remove("active");
  current -= 1;
});
