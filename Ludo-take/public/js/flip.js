let switcher = {
    // Put on array every element who have the class .button__switcher--back
    button : document.getElementsByClassName('button__checkbox'),
    gameOnChest : document.querySelector('.command-table__info__chest'),
    init: function() {
        // use loop to handle every button
        for (let index = 0; index < switcher.button.length; index++) {
            const element = switcher.button[index];
            element.addEventListener('change', switcher.handleCheck);
        } 
    },

    handleCheck: function(evt) {
        if (evt.target.checked) {
            switcher.gameOnChest.textContent++;
        } else {
            switcher.gameOnChest.textContent--;
        }
        switcher.numberChange();
},

    numberChange: function() {
        if (switcher.gameOnChest.textContent <= 7) {
            switcher.gameOnChest.classList.remove('command-table__info__chest--unvalid');
            switcher.gameOnChest.classList.add('command-table__info__chest--valid');
        } else {
            switcher.gameOnChest.classList.remove('command-table__info__chest--valid');
            switcher.gameOnChest.classList.add('command-table__info__chest--unvalid');
        }
    },
};
document.addEventListener('DOMContentLoaded', switcher.init);