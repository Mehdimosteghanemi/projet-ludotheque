const slidePage = document.querySelector(".slide-page");
const nextBtnFirst = document.querySelector(".firstNext");
const prevBtnSec = document.querySelector(".prev-1");
const submitBtn = document.getElementById("user_form_type_user_submit");
const progressText = document.querySelectorAll(".step p");
const progressCheck = document.querySelectorAll(".step .check");
const bullet = document.querySelectorAll(".step .bullet");

const checkMail = document.querySelector(".formMail");
const checkPass = document.querySelector(".formPassword");
let current = 1;

submitBtn.textContent = "Valider";

nextBtnFirst.addEventListener("click", function(event){
  event.preventDefault();
    // if(checkMail === '' && checkPass === '') {
        slidePage.style.display = "none";
        bullet[current - 1].classList.add("active");
        progressCheck[current - 1].classList.add("active");
        progressText[current - 1].classList.add("active");
        current += 1;
    // } else (alert("Veuillez renseignez les champs ce-dessous."))
});

submitBtn.addEventListener("click", function(){
  sendForm.submit;
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");
  current += 1;
  setTimeout(function(){
    alert("L'inscription est terminée");
  },800);
});

prevBtnSec.addEventListener("click", function(event){
  event.preventDefault();
  slidePage.style.display = "block";
  bullet[current - 2].classList.remove("active");
  progressCheck[current - 2].classList.remove("active");
  progressText[current - 2].classList.remove("active");
  current -= 1;
});
