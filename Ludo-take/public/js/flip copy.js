let flip = {
    // Put on array every element who have the class .button__flip--back
    front : document.getElementsByClassName('button__flip--front'),
    back : document.getElementsByClassName('button__flip--back'),
    gameOnChest : document.querySelector('.command-table__info__chest'),
    init: function() {
        // use loop to handle every button
        for (let index = 0; index < flip.front.length; index++) {
            const element = flip.front[index];
            element.addEventListener('click', flip.handleClickFront);
        } 
        for (let index = 0; index < flip.back.length; index++) {
            const element = flip.back[index];
            element.addEventListener('click', flip.handleClickBack);
        } 
    },

    handleClickFront: function(evt) {
        flip.gameOnChest.textContent--;
        flip.numberChange();
    },

    numberChange: function() {
        if (flip.gameOnChest.textContent <= 7) {
            flip.gameOnChest.classList.remove('command-table__info__chest--unvalid');
            flip.gameOnChest.classList.add('command-table__info__chest--valid');
        } else {
            flip.gameOnChest.classList.remove('command-table__info__chest--valid');
            flip.gameOnChest.classList.add('command-table__info__chest--unvalid');
        }
    },

    handleClickBack: function(evt) {
        // select 2 element before (is the checkbox)
        let element = evt.target.previousElementSibling.previousElementSibling;
        // uncheck the checkbox
        element.checked = true;
        flip.gameOnChest.textContent++;
        flip.numberChange();
    },
};
document.addEventListener('DOMContentLoaded', flip.init);