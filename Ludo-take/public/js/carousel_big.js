
let img__slider = document.getElementsByClassName('img__slider');

let etape = 0;

let nbr__img = img__slider.length;

let previous = document.querySelector('.previous');
let next = document.querySelector('.next');

function removeActiveImages() {
    for( let i = 0 ; i < nbr__img ; i++ ) {
        img__slider[i].classList.remove('active');
    }
}

next.addEventListener('click', function() {
    etape++;

    if(etape >= nbr__img) {
        etape = 0;
    }

    removeActiveImages();
    img__slider[etape].classList.add('active');
})

previous.addEventListener('click', function() {
    etape--;

    if(etape < 0) {
        etape = nbr__img - 1;
    }
    removeActiveImages();
    img__slider[etape].classList.add('active');
})

setInterval(function() {
    etape++;

    if(etape >= nbr__img) {
        etape = 0;
    }
    removeActiveImages();
    img__slider[etape].classList.add('active');
}, 4000)
