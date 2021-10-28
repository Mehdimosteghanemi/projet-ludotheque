let roll = {
    // Put on array every element who have the class .button__roll--back
    dice : document.querySelector('.dice__img'),
    init: function() {
        roll.dice.addEventListener('click', roll.handleClick);
    },

    handleClick: function(evt) {
        let number = roll.number();
        roll.numberChange(number);
    },

    number: function() {
        let number = Math.floor(Math.random() * (21 - 1) + 1)
        return number;
    },

    numberChange: function(number) {
        document.querySelector('.dice__number').textContent = number;
    },

};
document.addEventListener('DOMContentLoaded', roll.init);