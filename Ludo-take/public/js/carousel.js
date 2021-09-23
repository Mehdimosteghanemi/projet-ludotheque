let carousel = {
    numberCarouselActive : 1,
    howMuchActiveCarrouselEllement : 0,
    lastElementBeforeReverse : 0,
    howMuchCarouselElement : document.getElementsByClassName('carousel__window__card').length,
    arrayClassCarousel : document.getElementsByClassName('carousel__window__card'),
    card : document.querySelector('.carousel__window__cards'),
    mouseIsOver : false,
    mouseClickArrow : false,
    animation : '',
    // roundTrip switch into animation normal or reverse
    roundTrip: 'normal',
    // transition past to true  when the animation switch into normal to reverse.
    // for the number carousel not change during the switch
    transition: false,
    init: function() {
        carousel.selectNumberElement();
        carousel.lastElementBeforeReverse = carousel.howMuchCarouselElement-carousel.howMuchActiveCarrouselEllement+1;
        document.querySelector('.carousel__arrow--left').addEventListener('click', carousel.handleCarouselleLeft);
        document.querySelector('.carousel__arrow--right').addEventListener('click', carousel.handleCarouselleRight);
        document.querySelector('.carousel__window__cards').addEventListener('mouseover', carousel.handleMouseOver);
        document.querySelector('.carousel__window__cards').addEventListener('mouseleave', carousel.handleMouseLeave);
        // document.querySelector('.carousel__window__cards').style.webkitAnimationPlayState = "paused";
        carousel.animation = setInterval(carousel.animationAuto, 5600);
    },

    selectNumberElement : function(){
        if (window.screen.availWidth <= 1000) {
            carousel.howMuchActiveCarrouselEllement = 2;
        } if (window.screen.availWidth <= 600) {
            carousel.howMuchActiveCarrouselEllement = 1;
        }else {
            carousel.howMuchActiveCarrouselEllement = 3;
        }
    },

    handleMouseOver : function(){
        carousel.mouseIsOver = true;
    },

    handleMouseLeave : function(){
        carousel.mouseIsOver = false;
    },

    /**
     * method to run automaticaly the carousel while the user doesn't click on the arrow
     */
    animationAuto: function() {
        // The animation stop to run when the user mouse is on the card or after using arrox
        if (carousel.mouseIsOver === false || carousel.mouseClickArrow === false) {

            // While the roundTrip is normal and the last card is not show, it can continu runing and increment to know where it is.
            if(carousel.roundTrip === 'normal' && carousel.numberCarouselActive < carousel.lastElementBeforeReverse) {
                if (carousel.mouseClickArrow === false){
                    carousel.card.style.webkitAnimationPlayState = "running";
                    carousel.numberCarouselActive++;

                }else{

                    clearInterval(carousel.animation);
                    console.log('corousel animation is clear');
                }
                
                var intervalCarousel = setInterval(function() {
                    
                    carousel.card.style.webkitAnimationPlayState = "paused";
                    console.log(carousel.numberCarouselActive);
                    clearInterval(intervalCarousel);
                }, 925)

            } else if (carousel.roundTrip === 'normal' && carousel.numberCarouselActive === carousel.lastElementBeforeReverse) {
                if (carousel.mouseClickArrow === false){
                    carousel.card.style.webkitAnimationPlayState = "running";
                    carousel.numberCarouselActive--;
                    carousel.roundTrip = 'reverse';
                    carousel.transition = true;

                }else{
                    clearInterval(carousel.animation);
                    console.log('corousel animation is clear');
                }
                
                var intervalCarousel = setInterval(function() {
                    
                    carousel.card.style.webkitAnimationPlayState = "paused";
                    console.log(carousel.numberCarouselActive);
                    clearInterval(intervalCarousel);
                }, 925)
            }

            if(carousel.roundTrip === 'reverse' && carousel.numberCarouselActive > 1 && carousel.transition === false) {
                if (carousel.mouseClickArrow === false){
                    carousel.card.style.webkitAnimationPlayState = "running";
                    carousel.numberCarouselActive--;

                }else{
                    clearInterval(carousel.animation);
                    console.log('corousel animation is clear');
                }
                
                var intervalCarousel = setInterval(function() {
                    
                    carousel.card.style.webkitAnimationPlayState = "paused";
                    console.log(carousel.numberCarouselActive);
                    clearInterval(intervalCarousel);
                }, 925)
                
            } else if (carousel.roundTrip === 'reverse' && carousel.numberCarouselActive === 1) {
                if (carousel.mouseClickArrow === false){
                    carousel.card.style.webkitAnimationPlayState = "running";
                    carousel.numberCarouselActive++;
                    carousel.roundTrip = 'normal';

                }else{
                    clearInterval(carousel.animation);
                    console.log('corousel animation is clear');
                }
                
                var intervalCarousel = setInterval(function() {
                    
                    carousel.card.style.webkitAnimationPlayState = "paused";
                    console.log(carousel.numberCarouselActive);
                    clearInterval(intervalCarousel);
                }, 925)  
            }
            carousel.transition = false;
        }
    },

    /**
     * method to run the animation of carousel to the next state
     */
    handleCarouselleRight: function(evt) {
        console.log('clique on right arrow detected');
        carousel.mouseClickArrow = true;
        if (carousel.numberCarouselActive < carousel.howMuchCarouselElement) {
            carousel.card.style.webkitAnimationPlayState = "running";
            var intervalCarousel = setInterval(function() {
                if (carousel.numberCarouselActive < carousel.howMuchCarouselElement) {
                    carousel.numberCarouselActive++;
                }
                carousel.card.style.webkitAnimationPlayState = "paused";
                console.log(carousel.numberCarouselActive);
                clearInterval(intervalCarousel);
            }, 925) 
        } else { 
            console.log('carousel is already number 9');
        }
    },
    /**
     * method to run the animation of carousel to the previous state
     */
    handleCarouselleLeft: function(evt) {
        console.log('clique on left arrow detected');
        carousel.mouseClickArrow = true;

        // if (carousel.numberCarouselActive > 1) {
            carousel.card.style = "animation-direction: reverse;";
            carousel.card.style.webkitAnimationPlayState = "running";
            var intervalCarousel = setInterval(function() {
                if (carousel.numberCarouselActive > 1) {
                    carousel.numberCarouselActive--;
                }
                carousel.card.style.webkitAnimationPlayState = "paused";
                carousel.card.style = "";
                console.log(carousel.numberCarouselActive);
                clearInterval(intervalCarousel);
            }, 925) 
        // } else { 
        //     console.log('carousel is already number 1');
        // }
    },
   
};
document.addEventListener('DOMContentLoaded', carousel.init);