let carousel = {
// This JS files allow the carousel run automaticaly or by clicking on the arrow previous or next, is responsive and adapted automaticaly
// by counting the card on the carousel and looking the screen width. It almost stop when the mouse is under him.

    // stock the interval of autoscroll, we're using it to stop autoscroll after clicking arrow
    autoScroll : '',
    howMuchCarouselElement : document.getElementsByClassName('carousel__window__card').length,
    // stock 'previous' or 'next' according to the last clicking action, we're using it to know if we run the handleTransition
    lastDirection : '',
    mouseClickArrow : false,
    mouseIsOver : false,
    next : document.querySelector('.carousel__arrow--next'),
    previous : document.querySelector('.carousel__arrow--previous'),
    slider : document.querySelector('.carousel__window__slider'),
    translatePerCent : 11,

    init: function() {
        carousel.selectSlidePerCent();
        carousel.selectTranslatePerCent();
        carousel.lastElementBeforeReverse = carousel.howMuchCarouselElement-carousel.howMuchActiveCarrouselEllement+1;
        carousel.previous.addEventListener('click', carousel.handleCarouselPrevious);
        carousel.next.addEventListener('click', carousel.handleCarouselNext);
        carousel.slider.addEventListener('transitionend', carousel.handleTransition);
        carousel.slider.addEventListener('mouseover', carousel.handleMouseOver);
        carousel.slider.addEventListener('mouseleave', carousel.handleMouseLeave);
        carousel.autoScroll= setInterval(carousel.autoScroll, 2000);
        // this interval for cheking every 5 secound if the screen have always the same width and update the carousel if needing.
        setInterval(carousel.selectSlidePerCent, 5000);
    },

    /**
     * Method who adapt the width of the slider depending of the screen and the number of card inside.
     */
    selectSlidePerCent: function(){
        if (window.screen.availWidth <= 600) {
            carousel.slider.style["width"] = carousel.howMuchCarouselElement*100 + '%';
        }else if (window.screen.availWidth <= 1000) {
            carousel.slider.style["width"] = carousel.howMuchCarouselElement*50 + '%';
        }else {
            carousel.slider.style["width"] = carousel.howMuchCarouselElement*33 + '%';
        }
    },

    /**
     * Method who adapt the transition movement dependin on the number of card
     */
    selectTranslatePerCent: function(){
        carousel.translatePerCent = Math.round(100/carousel.howMuchCarouselElement);
    },

    /**
     * Method who scroll to the next card while mouse doesnt click arrow or isn't under it
     */
    autoScroll: function() {
        console.log(carousel.mouseClickArrow);
        // condition for stopping durring the time mouse is under the carousel
        if (carousel.mouseIsOver === false) {
            // condition to prevent the last loop of setInterval
            if (carousel.mouseClickArrow === false) {
                carousel.carouselNext();
                console.log('mouse click is enter');
            }  
        }

        if (carousel.mouseClickArrow === true){
            clearInterval(carousel.autoScroll);
        }
    },

    /**
     * Method who save the state of mouse 
     * when it's under the carousel
     */
    handleMouseOver : function(){
        carousel.mouseIsOver = true;
    },

    /**
     * Method who save the state of mouse 
     * when it leave the carousel
     */
    handleMouseLeave : function(){
        carousel.mouseIsOver = false;
    },

    /**
     * Method who detect the carousel turn because of the user click 
     * it disable the autoScroll
     */
    handleCarouselNext: function(evt) {
        carousel.mouseClickArrow = true;
        carousel.carouselNext();
    },

    /**
     * method to run the animation of carousel to the next state
     */
    carouselNext: function() {
        // using to animate the slide
        carousel.slider.style.transform = 'translate(-' + carousel.translatePerCent +'%)';
        carousel.lastDirection = 'next';
    },
    /**
     * method to run the animation of carousel to the previous state
     */
    handleCarouselPrevious: function(evt) {
        carousel.mouseClickArrow = true;
        // delete the transition time 
        carousel.slider.style.transition = 'none';
        // the transition and the adding element start at the same time we cant see a movement because of the transition time 
        carousel.slider.style.transform = 'translate(-' + carousel.translatePerCent + '%)';
        // We're taking the last child and move it before the actual first child (this action create a infinite loop because everytimes the last become the first)
        carousel.slider.prepend(carousel.slider.lastElementChild);
        // setTimeout allow the both action on it run after the previous action. It can almost take a delay in milisecound but we don't need it.
        setTimeout(function() {
            carousel.slider.style.transition = 'all 0.5s';
            carousel.slider.style.transform = 'translate(0%)';    
            },0
        );
        carousel.lastDirection = 'previous';
    },

    /**
     * Method who place the first card into the last position
     * It's calling automaticaly after a transition.
     */
    handleTransition: function(evt) {
        // Checking after what transition it's running
        if (carousel.lastDirection === 'next') {
            // We're taking the first child and move it after the actual last child (this action create a infinite loop because everytimes the first become the last)
            carousel.slider.appendChild(carousel.slider.firstElementChild);
            // the transition and the movement start at the same time we cant see a movement because the transition none make it instant
            carousel.slider.style.transition = 'none';
            carousel.slider.style.transform = 'translate(0)';
            // setTimeout allow the action run after the previous action. It can almost take a delay in milisecound but we don't need it.
            setTimeout(function() {
                    carousel.slider.style.transition = 'all 0.5s';
                }
            );
        } else if (carousel.lastDirection === 'previous') {
            // The element movement is before the transition so we don't need to do something after
        }
        
    },
    

};
document.addEventListener('DOMContentLoaded', carousel.init);