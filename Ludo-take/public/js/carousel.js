let carousel = {
    numberCarouselActive : 1,
    // howMuchActiveCarrouselEllement : 3,
    arrayClassCarousel : document.getElementsByClassName('carousel__window__card'),
    comme: true,
    transition: false,
    howMuchCarouselElement1 : document.getElementsByClassName('carousel__window__card'),
    howMuchCarouselElement2 : document.getElementsByClassName('carousel__window__card').length,
    init: function() {
        console.log(carousel.howMuchCarouselElement1);
        console.log(carousel.howMuchCarouselElement2);
        console.log(carousel.howMuchCarouselElement1.length);

        document.querySelector('.carousel__arrow--left').addEventListener('click', carousel.handleCarouselleLeft);
        document.querySelector('.carousel__arrow--right').addEventListener('click', carousel.handleCarouselleRight);

        document.querySelector('.carousel__window__cards').style.webkitAnimationPlayState = "paused";
        carousel.animationTime
        // const carouselAnimation = setInterval(carousel.animationAuto, 1400);
    },

    animationAuto: function() {
        let howMuchCarouselElement = carousel.arrayClassCarousel.length;
        if(carousel.comme === true && carousel.numberCarouselActive < howMuchCarouselElement) {
            document.querySelector('.carousel__window__cards').style.webkitAnimationPlayState = "running";
            var intervalCarousel = setInterval(function() {
                carousel.numberCarouselActive++;
                document.querySelector('.carousel__window__cards').style.webkitAnimationPlayState = "paused";
                console.log(carousel.numberCarouselActive);
                clearInterval(intervalCarousel);
            }, 700)
            console.log(carousel.numberCarouselActive);
        } else if (carousel.comme === true && carousel.numberCarouselActive === howMuchCarouselElement) {
           carousel.comme = false;
           carousel.transition = true;
           carousel.numberCarouselActive--;
           document.querySelector('.carousel__window__cards').style.webkitAnimationPlayState = "running";
           console.log(carousel.numberCarouselActive);
        }

        if(carousel.comme === false && carousel.numberCarouselActive > 1 && carousel.transition === false) {
            carousel.numberCarouselActive--;
            console.log(carousel.numberCarouselActive);
        } else if (carousel.comme === false && carousel.numberCarouselActive === 1) {
           carousel.comme = true;
           carousel.numberCarouselActive++;
           console.log(carousel.numberCarouselActive);   
        }
        carousel.transition = false;
    },

    /**
     * method to run the animation of carousel to the next state
     */
    handleCarouselleRight: function(evt) {
        
        let howMuchCarouselElement = carousel.arrayClassCarousel.length;
        console.log(howMuchCarouselElement);
      

        if (carousel.numberCarouselActive < howMuchCarouselElement) {
            document.querySelector('.carousel__window__cards').style.webkitAnimationPlayState = "running";
            var intervalCarousel = setInterval(function() {
                
                carousel.numberCarouselActive++;
                document.querySelector('.carousel__window__cards').style.webkitAnimationPlayState = "paused";
                console.log(carousel.numberCarouselActive);
                clearInterval(intervalCarousel);
            }, 700)
            
        } 
    },
    /**
     * method to run the animation of carousel to the previous state
     */
    handleCarouselleLeft: function(evt) {
        evt.preventDefault();
        console.log('clique on left arrow detected');
        // TODO
    },
   
};
document.addEventListener('DOMContentLoaded', carousel.init);