let flip = {
    // Put on array every element who have the class .button__flip__back
    back : document.getElementsByClassName('button__flip__back'),
    init: function() {
        // use loop to handle every button
        for (let index = 0; index < flip.back.length; index++) {
            const element = flip.back[index];
            element.addEventListener('click', flip.handleClick);
        } 
    },
    handleClick: function(evt) {
        // select 2 element before (is the checkbox)
        let element = evt.target.previousElementSibling.previousElementSibling;
        console.log(element);
        // uncheck the checkbox
        element.checked = true;
    }
};
document.addEventListener('DOMContentLoaded', flip.init);