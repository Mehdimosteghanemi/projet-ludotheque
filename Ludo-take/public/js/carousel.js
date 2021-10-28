let carousel0 = {
    // This JS files allow the carousel0 run automaticaly or by clicking on the arrow previous or next, is responsive and adapted automaticaly
    // by counting the card on the carousel0 and looking the screen width. It almost stop when the mouse is under him.

        // stock the interval of autoscroll, we're using it to stop autoscroll after clicking arrow
        autoScroll : '',
        howMuchCarousel0Element : document.getElementsByClassName('carousel0__window__card').length,
        // stock 'previous' or 'next' according to the last clicking action, we're using it to know if we run the handleTransition
        lastDirection : '',
        mouseClickArrow : false,
        mouseIsOver : false,
        next : document.querySelector('.carousel0__arrow--next'),
        previous : document.querySelector('.carousel0__arrow--previous'),
        slider : document.querySelector('.carousel0__window__slider'),
        translatePerCent : 11,

        init: function() {
            carousel0.selectSlidePerCent();
            carousel0.selectTranslatePerCent();
            carousel0.lastElementBeforeReverse = carousel0.howMuchCarousel0Element-carousel0.howMuchActiveCarrouselEllement+1;
            carousel0.previous.addEventListener('click', carousel0.handleCarousel0Previous);
            carousel0.next.addEventListener('click', carousel0.handleCarousel0Next);
            carousel0.slider.addEventListener('transitionend', carousel0.handleTransition);
            carousel0.slider.addEventListener('mouseover', carousel0.handleMouseOver);
            carousel0.slider.addEventListener('mouseleave', carousel0.handleMouseLeave);
            carousel0.autoScroll= setInterval(carousel0.autoScroll, 2000);
            // this interval for cheking every 5 secound if the screen have always the same width and update the carousel0 if needing.
            setInterval(carousel0.selectSlidePerCent, 5000);
        },

        /**
         * Method who adapt the width of the slider depending of the screen and the number of card inside.
         */
        selectSlidePerCent: function(){
            if (window.screen.availWidth <= 600) {
                carousel0.slider.style["width"] = carousel0.howMuchCarousel0Element*100 + '%';
            }else if (window.screen.availWidth <= 1000) {
                carousel0.slider.style["width"] = carousel0.howMuchCarousel0Element*50 + '%';
            }else {
                carousel0.slider.style["width"] = carousel0.howMuchCarousel0Element*33 + '%';
            }
        },

        /**
         * Method who adapt the transition movement dependin on the number of card
         */
        selectTranslatePerCent: function(){
            carousel0.translatePerCent = Math.round(100/carousel0.howMuchCarousel0Element);
        },

        /**
         * Method who scroll to the next card while mouse doesnt click arrow or isn't under it
         */
        autoScroll: function() {
            console.log(carousel0.mouseClickArrow);
            // condition for stopping durring the time mouse is under the carousel0
            if (carousel0.mouseIsOver === false) {
                // condition to prevent the last loop of setInterval
                if (carousel0.mouseClickArrow === false) {
                    carousel0.carousel0Next();
                    console.log('mouse click is enter');
                }  
            }

            if (carousel0.mouseClickArrow === true){
                clearInterval(carousel0.autoScroll);
            }
        },

        /**
         * Method who save the state of mouse 
         * when it's under the carousel0
         */
        handleMouseOver : function(){
            carousel0.mouseIsOver = true;
        },

        /**
         * Method who save the state of mouse 
         * when it leave the carousel0
         */
        handleMouseLeave : function(){
            carousel0.mouseIsOver = false;
        },

        /**
         * Method who detect the carousel0 turn because of the user click 
         * it disable the autoScroll
         */
        handleCarousel0Next: function(evt) {
            carousel0.mouseClickArrow = true;
            carousel0.carousel0Next();
        },

        /**
         * method to run the animation of carousel0 to the next state
         */
        carousel0Next: function() {
            // using to animate the slide
            carousel0.slider.style.transform = 'translate(-' + carousel0.translatePerCent +'%)';
            carousel0.lastDirection = 'next';
        },
        /**
         * method to run the animation of carousel0 to the previous state
         */
        handleCarousel0Previous: function(evt) {
            carousel0.mouseClickArrow = true;
            // delete the transition time 
            carousel0.slider.style.transition = 'none';
            // the transition and the adding element start at the same time we cant see a movement because of the transition time 
            carousel0.slider.style.transform = 'translate(-' + carousel0.translatePerCent + '%)';
            // We're taking the last child and move it before the actual first child (this action create a infinite loop because everytimes the last become the first)
            carousel0.slider.prepend(carousel0.slider.lastElementChild);
            // setTimeout allow the both action on it run after the previous action. It can almost take a delay in milisecound but we don't need it.
            setTimeout(function() {
                carousel0.slider.style.transition = 'all 0.5s';
                carousel0.slider.style.transform = 'translate(0%)';    
                },0
            );
            carousel0.lastDirection = 'previous';
        },

        /**
         * Method who place the first card into the last position
         * It's calling automaticaly after a transition.
         */
        handleTransition: function(evt) {
            console.log(evt.target.className);
            // Checking after what transition it's running
            if (carousel0.lastDirection === 'next' && evt.target.className !== "button button__principal") {
                // We're taking the first child and move it after the actual last child (this action create a infinite loop because everytimes the first become the last)
                carousel0.slider.appendChild(carousel0.slider.firstElementChild);
                // the transition and the movement start at the same time we cant see a movement because the transition none make it instant
                carousel0.slider.style.transition = 'none';
                carousel0.slider.style.transform = 'translate(0)';
                // setTimeout allow the action run after the previous action. It can almost take a delay in milisecound but we don't need it.
                setTimeout(function() {
                        carousel0.slider.style.transition = 'all 0.5s';
                    }
                );
            } else if (carousel0.lastDirection === 'previous') {
                // The element movement is before the transition so we don't need to do something after
            }
            
        },
        

    };
    document.addEventListener('DOMContentLoaded', carousel0.init);

let carousel1 = {
    // This JS files allow the carousel1 run automaticaly or by clicking on the arrow previous or next, is responsive and adapted automaticaly
    // by counting the card on the carousel1 and looking the screen width. It almost stop when the mouse is under him.

        // stock the interval of autoscroll, we're using it to stop autoscroll after clicking arrow
        autoScroll : '',
        howMuchCarousel1Element : document.getElementsByClassName('carousel1__window__card').length,
        // stock 'previous' or 'next' according to the last clicking action, we're using it to know if we run the handleTransition
        lastDirection : '',
        mouseClickArrow : false,
        mouseIsOver : false,
        next : document.querySelector('.carousel1__arrow--next'),
        previous : document.querySelector('.carousel1__arrow--previous'),
        slider : document.querySelector('.carousel1__window__slider'),
        translatePerCent : 11,

        init: function() {
            carousel1.selectSlidePerCent();
            carousel1.selectTranslatePerCent();
            carousel1.lastElementBeforeReverse = carousel1.howMuchCarousel1Element-carousel1.howMuchActiveCarrouselEllement+1;
            carousel1.previous.addEventListener('click', carousel1.handleCarousel1Previous);
            carousel1.next.addEventListener('click', carousel1.handleCarousel1Next);
            carousel1.slider.addEventListener('transitionend', carousel1.handleTransition);
            carousel1.slider.addEventListener('mouseover', carousel1.handleMouseOver);
            carousel1.slider.addEventListener('mouseleave', carousel1.handleMouseLeave);
            carousel1.autoScroll= setInterval(carousel1.autoScroll, 2000);
            // this interval for cheking every 5 secound if the screen have always the same width and update the carousel1 if needing.
            setInterval(carousel1.selectSlidePerCent, 5000);
        },

        /**
         * Method who adapt the width of the slider depending of the screen and the number of card inside.
         */
        selectSlidePerCent: function(){
            if (window.screen.availWidth <= 600) {
                carousel1.slider.style["width"] = carousel1.howMuchCarousel1Element*100 + '%';
            }else if (window.screen.availWidth <= 1000) {
                carousel1.slider.style["width"] = carousel1.howMuchCarousel1Element*50 + '%';
            }else {
                carousel1.slider.style["width"] = carousel1.howMuchCarousel1Element*33 + '%';
            }
        },

        /**
         * Method who adapt the transition movement dependin on the number of card
         */
        selectTranslatePerCent: function(){
            carousel1.translatePerCent = Math.round(100/carousel1.howMuchCarousel1Element);
        },

        /**
         * Method who scroll to the next card while mouse doesnt click arrow or isn't under it
         */
        autoScroll: function() {
            console.log(carousel1.mouseClickArrow);
            // condition for stopping durring the time mouse is under the carousel1
            if (carousel1.mouseIsOver === false) {
                // condition to prevent the last loop of setInterval
                if (carousel1.mouseClickArrow === false) {
                    carousel1.carousel1Next();
                    console.log('mouse click is enter');
                }  
            }

            if (carousel1.mouseClickArrow === true){
                clearInterval(carousel1.autoScroll);
            }
        },

        /**
         * Method who save the state of mouse 
         * when it's under the carousel1
         */
        handleMouseOver : function(){
            carousel1.mouseIsOver = true;
        },

        /**
         * Method who save the state of mouse 
         * when it leave the carousel1
         */
        handleMouseLeave : function(){
            carousel1.mouseIsOver = false;
        },

        /**
         * Method who detect the carousel1 turn because of the user click 
         * it disable the autoScroll
         */
        handleCarousel1Next: function(evt) {
            carousel1.mouseClickArrow = true;
            carousel1.carousel1Next();
        },

        /**
         * method to run the animation of carousel1 to the next state
         */
        carousel1Next: function() {
            // using to animate the slide
            carousel1.slider.style.transform = 'translate(-' + carousel1.translatePerCent +'%)';
            carousel1.lastDirection = 'next';
        },
        /**
         * method to run the animation of carousel1 to the previous state
         */
        handleCarousel1Previous: function(evt) {
            carousel1.mouseClickArrow = true;
            // delete the transition time 
            carousel1.slider.style.transition = 'none';
            // the transition and the adding element start at the same time we cant see a movement because of the transition time 
            carousel1.slider.style.transform = 'translate(-' + carousel1.translatePerCent + '%)';
            // We're taking the last child and move it before the actual first child (this action create a infinite loop because everytimes the last become the first)
            carousel1.slider.prepend(carousel1.slider.lastElementChild);
            // setTimeout allow the both action on it run after the previous action. It can almost take a delay in milisecound but we don't need it.
            setTimeout(function() {
                carousel1.slider.style.transition = 'all 0.5s';
                carousel1.slider.style.transform = 'translate(0%)';    
                },0
            );
            carousel1.lastDirection = 'previous';
        },

        /**
         * Method who place the first card into the last position
         * It's calling automaticaly after a transition.
         */
        handleTransition: function(evt) {
            console.log(evt.target.className);
            // Checking after what transition it's running
            if (carousel1.lastDirection === 'next' && evt.target.className !== "button button__principal") {
                // We're taking the first child and move it after the actual last child (this action create a infinite loop because everytimes the first become the last)
                carousel1.slider.appendChild(carousel1.slider.firstElementChild);
                // the transition and the movement start at the same time we cant see a movement because the transition none make it instant
                carousel1.slider.style.transition = 'none';
                carousel1.slider.style.transform = 'translate(0)';
                // setTimeout allow the action run after the previous action. It can almost take a delay in milisecound but we don't need it.
                setTimeout(function() {
                        carousel1.slider.style.transition = 'all 0.5s';
                    }
                );
            } else if (carousel1.lastDirection === 'previous') {
                // The element movement is before the transition so we don't need to do something after
            }
            
        },
        

    };
    document.addEventListener('DOMContentLoaded', carousel1.init);

    let carousel2 = {
        // This JS files allow the carousel2 run automaticaly or by clicking on the arrow previous or next, is responsive and adapted automaticaly
        // by counting the card on the carousel2 and looking the screen width. It almost stop when the mouse is under him.
    
            // stock the interval of autoscroll, we're using it to stop autoscroll after clicking arrow
            autoScroll : '',
            howMuchCarousel2Element : document.getElementsByClassName('carousel2__window__card').length,
            // stock 'previous' or 'next' according to the last clicking action, we're using it to know if we run the handleTransition
            lastDirection : '',
            mouseClickArrow : false,
            mouseIsOver : false,
            next : document.querySelector('.carousel2__arrow--next'),
            previous : document.querySelector('.carousel2__arrow--previous'),
            slider : document.querySelector('.carousel2__window__slider'),
            translatePerCent : 11,
    
            init: function() {
                carousel2.selectSlidePerCent();
                carousel2.selectTranslatePerCent();
                carousel2.lastElementBeforeReverse = carousel2.howMuchCarousel2Element-carousel2.howMuchActiveCarrouselEllement+1;
                carousel2.previous.addEventListener('click', carousel2.handleCarousel2Previous);
                carousel2.next.addEventListener('click', carousel2.handleCarousel2Next);
                carousel2.slider.addEventListener('transitionend', carousel2.handleTransition);
                carousel2.slider.addEventListener('mouseover', carousel2.handleMouseOver);
                carousel2.slider.addEventListener('mouseleave', carousel2.handleMouseLeave);
                carousel2.autoScroll= setInterval(carousel2.autoScroll, 2000);
                // this interval for cheking every 5 secound if the screen have always the same width and update the carousel2 if needing.
                setInterval(carousel2.selectSlidePerCent, 5000);
            },
    
            /**
             * Method who adapt the width of the slider depending of the screen and the number of card inside.
             */
            selectSlidePerCent: function(){
                if (window.screen.availWidth <= 600) {
                    carousel2.slider.style["width"] = carousel2.howMuchCarousel2Element*100 + '%';
                }else if (window.screen.availWidth <= 1000) {
                    carousel2.slider.style["width"] = carousel2.howMuchCarousel2Element*50 + '%';
                }else {
                    carousel2.slider.style["width"] = carousel2.howMuchCarousel2Element*33 + '%';
                }
            },
    
            /**
             * Method who adapt the transition movement dependin on the number of card
             */
            selectTranslatePerCent: function(){
                carousel2.translatePerCent = Math.round(100/carousel2.howMuchCarousel2Element);
            },
    
            /**
             * Method who scroll to the next card while mouse doesnt click arrow or isn't under it
             */
            autoScroll: function() {
                console.log(carousel2.mouseClickArrow);
                // condition for stopping durring the time mouse is under the carousel2
                if (carousel2.mouseIsOver === false) {
                    // condition to prevent the last loop of setInterval
                    if (carousel2.mouseClickArrow === false) {
                        carousel2.carousel2Next();
                        console.log('mouse click is enter');
                    }  
                }
    
                if (carousel2.mouseClickArrow === true){
                    clearInterval(carousel2.autoScroll);
                }
            },
    
            /**
             * Method who save the state of mouse 
             * when it's under the carousel2
             */
            handleMouseOver : function(){
                carousel2.mouseIsOver = true;
            },
    
            /**
             * Method who save the state of mouse 
             * when it leave the carousel2
             */
            handleMouseLeave : function(){
                carousel2.mouseIsOver = false;
            },
    
            /**
             * Method who detect the carousel2 turn because of the user click 
             * it disable the autoScroll
             */
            handleCarousel2Next: function(evt) {
                carousel2.mouseClickArrow = true;
                carousel2.carousel2Next();
            },
    
            /**
             * method to run the animation of carousel2 to the next state
             */
            carousel2Next: function() {
                // using to animate the slide
                carousel2.slider.style.transform = 'translate(-' + carousel2.translatePerCent +'%)';
                carousel2.lastDirection = 'next';
            },
            /**
             * method to run the animation of carousel2 to the previous state
             */
            handleCarousel2Previous: function(evt) {
                carousel2.mouseClickArrow = true;
                // delete the transition time 
                carousel2.slider.style.transition = 'none';
                // the transition and the adding element start at the same time we cant see a movement because of the transition time 
                carousel2.slider.style.transform = 'translate(-' + carousel2.translatePerCent + '%)';
                // We're taking the last child and move it before the actual first child (this action create a infinite loop because everytimes the last become the first)
                carousel2.slider.prepend(carousel2.slider.lastElementChild);
                // setTimeout allow the both action on it run after the previous action. It can almost take a delay in milisecound but we don't need it.
                setTimeout(function() {
                    carousel2.slider.style.transition = 'all 0.5s';
                    carousel2.slider.style.transform = 'translate(0%)';    
                    },0
                );
                carousel2.lastDirection = 'previous';
            },
    
            /**
             * Method who place the first card into the last position
             * It's calling automaticaly after a transition.
             */
            handleTransition: function(evt) {
                console.log(evt.target.className);
                // Checking after what transition it's running
                if (carousel2.lastDirection === 'next' && evt.target.className !== "button button__principal") {
                    // We're taking the first child and move it after the actual last child (this action create a infinite loop because everytimes the first become the last)
                    carousel2.slider.appendChild(carousel2.slider.firstElementChild);
                    // the transition and the movement start at the same time we cant see a movement because the transition none make it instant
                    carousel2.slider.style.transition = 'none';
                    carousel2.slider.style.transform = 'translate(0)';
                    // setTimeout allow the action run after the previous action. It can almost take a delay in milisecound but we don't need it.
                    setTimeout(function() {
                            carousel2.slider.style.transition = 'all 0.5s';
                        }
                    );
                } else if (carousel2.lastDirection === 'previous') {
                    // The element movement is before the transition so we don't need to do something after
                }
                
            },
            
    
        };
        document.addEventListener('DOMContentLoaded', carousel2.init);

        let carousel3 = {
            // This JS files allow the carousel3 run automaticaly or by clicking on the arrow previous or next, is responsive and adapted automaticaly
            // by counting the card on the carousel3 and looking the screen width. It almost stop when the mouse is under him.
        
                // stock the interval of autoscroll, we're using it to stop autoscroll after clicking arrow
                autoScroll : '',
                howMuchCarousel3Element : document.getElementsByClassName('carousel3__window__card').length,
                // stock 'previous' or 'next' according to the last clicking action, we're using it to know if we run the handleTransition
                lastDirection : '',
                mouseClickArrow : false,
                mouseIsOver : false,
                next : document.querySelector('.carousel3__arrow--next'),
                previous : document.querySelector('.carousel3__arrow--previous'),
                slider : document.querySelector('.carousel3__window__slider'),
                translatePerCent : 11,
        
                init: function() {
                    carousel3.selectSlidePerCent();
                    carousel3.selectTranslatePerCent();
                    carousel3.lastElementBeforeReverse = carousel3.howMuchCarousel3Element-carousel3.howMuchActiveCarrouselEllement+1;
                    carousel3.previous.addEventListener('click', carousel3.handleCarousel3Previous);
                    carousel3.next.addEventListener('click', carousel3.handleCarousel3Next);
                    carousel3.slider.addEventListener('transitionend', carousel3.handleTransition);
                    carousel3.slider.addEventListener('mouseover', carousel3.handleMouseOver);
                    carousel3.slider.addEventListener('mouseleave', carousel3.handleMouseLeave);
                    carousel3.autoScroll= setInterval(carousel3.autoScroll, 2000);
                    // this interval for cheking every 5 secound if the screen have always the same width and update the carousel3 if needing.
                    setInterval(carousel3.selectSlidePerCent, 5000);
                },
        
                /**
                 * Method who adapt the width of the slider depending of the screen and the number of card inside.
                 */
                selectSlidePerCent: function(){
                    if (window.screen.availWidth <= 600) {
                        carousel3.slider.style["width"] = carousel3.howMuchCarousel3Element*100 + '%';
                    }else if (window.screen.availWidth <= 1000) {
                        carousel3.slider.style["width"] = carousel3.howMuchCarousel3Element*50 + '%';
                    }else {
                        carousel3.slider.style["width"] = carousel3.howMuchCarousel3Element*33 + '%';
                    }
                },
        
                /**
                 * Method who adapt the transition movement dependin on the number of card
                 */
                selectTranslatePerCent: function(){
                    carousel3.translatePerCent = Math.round(100/carousel3.howMuchCarousel3Element);
                },
        
                /**
                 * Method who scroll to the next card while mouse doesnt click arrow or isn't under it
                 */
                autoScroll: function() {
                    console.log(carousel3.mouseClickArrow);
                    // condition for stopping durring the time mouse is under the carousel3
                    if (carousel3.mouseIsOver === false) {
                        // condition to prevent the last loop of setInterval
                        if (carousel3.mouseClickArrow === false) {
                            carousel3.carousel3Next();
                            console.log('mouse click is enter');
                        }  
                    }
        
                    if (carousel3.mouseClickArrow === true){
                        clearInterval(carousel3.autoScroll);
                    }
                },
        
                /**
                 * Method who save the state of mouse 
                 * when it's under the carousel3
                 */
                handleMouseOver : function(){
                    carousel3.mouseIsOver = true;
                },
        
                /**
                 * Method who save the state of mouse 
                 * when it leave the carousel3
                 */
                handleMouseLeave : function(){
                    carousel3.mouseIsOver = false;
                },
        
                /**
                 * Method who detect the carousel3 turn because of the user click 
                 * it disable the autoScroll
                 */
                handleCarousel3Next: function(evt) {
                    carousel3.mouseClickArrow = true;
                    carousel3.carousel3Next();
                },
        
                /**
                 * method to run the animation of carousel3 to the next state
                 */
                carousel3Next: function() {
                    // using to animate the slide
                    carousel3.slider.style.transform = 'translate(-' + carousel3.translatePerCent +'%)';
                    carousel3.lastDirection = 'next';
                },
                /**
                 * method to run the animation of carousel3 to the previous state
                 */
                handleCarousel3Previous: function(evt) {
                    carousel3.mouseClickArrow = true;
                    // delete the transition time 
                    carousel3.slider.style.transition = 'none';
                    // the transition and the adding element start at the same time we cant see a movement because of the transition time 
                    carousel3.slider.style.transform = 'translate(-' + carousel3.translatePerCent + '%)';
                    // We're taking the last child and move it before the actual first child (this action create a infinite loop because everytimes the last become the first)
                    carousel3.slider.prepend(carousel3.slider.lastElementChild);
                    // setTimeout allow the both action on it run after the previous action. It can almost take a delay in milisecound but we don't need it.
                    setTimeout(function() {
                        carousel3.slider.style.transition = 'all 0.5s';
                        carousel3.slider.style.transform = 'translate(0%)';    
                        },0
                    );
                    carousel3.lastDirection = 'previous';
                },
        
                /**
                 * Method who place the first card into the last position
                 * It's calling automaticaly after a transition.
                 */
                handleTransition: function(evt) {
                    console.log(evt.target.className);
                    // Checking after what transition it's running
                    if (carousel3.lastDirection === 'next' && evt.target.className !== "button button__principal") {
                        // We're taking the first child and move it after the actual last child (this action create a infinite loop because everytimes the first become the last)
                        carousel3.slider.appendChild(carousel3.slider.firstElementChild);
                        // the transition and the movement start at the same time we cant see a movement because the transition none make it instant
                        carousel3.slider.style.transition = 'none';
                        carousel3.slider.style.transform = 'translate(0)';
                        // setTimeout allow the action run after the previous action. It can almost take a delay in milisecound but we don't need it.
                        setTimeout(function() {
                                carousel3.slider.style.transition = 'all 0.5s';
                            }
                        );
                    } else if (carousel3.lastDirection === 'previous') {
                        // The element movement is before the transition so we don't need to do something after
                    }
                    
                },
                
        
            };
            document.addEventListener('DOMContentLoaded', carousel3.init);