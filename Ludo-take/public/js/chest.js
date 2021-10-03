let chest = {
    // Put on array every element who have the class .button__chest__back
    buttons : document.getElementsByClassName('chest-table__row__col__button'),
    index : 1,
    init: function() {
        // use loop to handle every button
        for (let index = 0; index < chest.buttons.length; index++) {
            const element = chest.buttons[index];
            element.addEventListener('click', chest.handleClick);
        } 
    },

    /**
     * Méthode who the status of command and clone it into the retusn list
     * @param {click} evt 
     */
    handleClick: function(evt) {

        // select the element 
        let element = evt.target;
        // select the text on the balise 3 element before (previousElementSibling) the parent parent's(path[1])
        let gameImageAndNameOrigin = evt.path[1].previousElementSibling.previousElementSibling.previousElementSibling;
        let gameImageAndName = gameImageAndNameOrigin.cloneNode(true);
        // select the status text
        let gameStatus = evt.path[1].previousElementSibling;
        // select the slug in dataset
        let gameId = evt.target.dataset.gameId;
        // we checked the stat of button whith is text ("Retourner" or other)
        if (element.textContent === "Retourner") {
            // if it's "Dans le coffre" change for "En attente de retour"
            element.textContent = "Garder";
            // change the status
            gameStatus.textContent = 'Dans le coffre (en attente de retour)';
            // create and insert the row in the return table
            chest.createRow(gameImageAndName, gameId);
            // use the actual chest.index to link the row whit the button and increment the index for the next link was different
            element.dataset.indexLink = chest.index;
            chest.index++;
        } else {
            // change the text for "Garder"
            element.textContent = "Retourner";
            // change the status
            gameStatus.textContent = 'Dans le coffre';
            // stock the data-index-link of the button 
            let dataToDelete = element.dataset.indexLink;
            // prepare the string for selector
            let indexString = '[data-index~="' + dataToDelete + '"]';
            // select the element with the same number in data-index than data-index-link
            let elementToDelete = document.querySelector(indexString);
            // delete withe remove()
            elementToDelete.remove();
            // remove the number
            chest.numberInReturnList--;
        }
        chest.checkTheReturnRow();
    },

    /**
     * Method to delete the game from return list and change the status of chest list
     * @param {click} evt 
     */
    handleClickReturn : function(evt) {
        // select the 3 time parent data-index
        let dataToChange = evt.path[2].dataset.index;
        // select the element (it's a button) with the same number in data-index-link than data-index
        let indexString = '[data-index-link~="' + dataToChange + '"]';
        // select the button and the status
        let button = document.querySelector(indexString);
        let status = button.parentElement.previousElementSibling;
        // change the button and status text
        button.textContent = "Retourner";
        status.textContent = "Dans le coffre";
        // remove the 3 time parent
        evt.path[2].remove();
        chest.checkTheReturnRow();
    },

    /**
     * Method to create the row for return list
     * @param {string} gameImageAndName
     * @param {string} slug
     */
    createRow : function(gameImageAndName, gameId) {
        // create the <tr> whith class="return-table__row" the dataset number to link whith the back button
        let divHead = document.createElement("div");
        divHead.classList.add("return-table__row");
        divHead.dataset.index = chest.index;

        // create <td class="return-table__row__col"> whith the game name
        let divGame = gameImageAndName;
        divGame.classList.remove("chest-table__row__col");
        divGame.classList.add("return-table__row__col");
        

        // create <td class="return-table__row__col"> and the button <div class="button button__chest button__chest--on-return-table">
        let divButton = document.createElement("div");
        divButton.classList.add("return-table__row__col");
        let button = document.createElement("div");
        button.classList.add("button", "return-table__row__col__button");
        button.textContent = "retour dans le coffre";

        let checkbox = chest.createCheckbox(gameId);

        // create a listener for the new buton
        button.addEventListener('click', chest.handleClickReturn);

        // put the button on thedivButton and after put the bothe <td> in the <tr>
        divButton.appendChild(button);
        divHead.appendChild(checkbox);
        divHead.appendChild(divGame);
        divHead.appendChild(divButton);

        // put the <tr> ont the table return
        document.querySelector('.return-table__content').appendChild(divHead);
    },

    createCheckbox : function(gameId) {
        // create <input type="checkbox" name="" id="" class="checkbox--hiden" checked>
        let checkbox = document.createElement('input');
        checkbox.setAttribute("type", "checkbox");
        checkbox.setAttribute("name", gameId);
        checkbox.id = gameId;
        checkbox.classList.add('checkbox--hiden');
        checkbox.checked = true;
        return checkbox;
    },

    checkTheReturnRow : function() {
        let rowArray = document.getElementsByClassName('return-table__row');
        for (let row = 0; row < rowArray.length; row++) {
            const element = rowArray[row];
            if (row % 2 === 0) {
                element.classList.add("return-table__row--pair");
                element.classList.remove("return-table__row--odd");
            } else {
                element.classList.add("return-table__row--odd");
                element.classList.remove("return-table__row--pair");
            }
        }
    },

};
document.addEventListener('DOMContentLoaded', chest.init);