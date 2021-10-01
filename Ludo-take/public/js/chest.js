let chest = {
    // Put on array every element who have the class .button__chest__back
    buttons : document.getElementsByClassName('button__chest--on-table'),
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
        let gameName = evt.path[1].previousElementSibling.previousElementSibling.previousElementSibling.textContent;
        // select the status text
        let gameStatus = evt.path[1].previousElementSibling;
        // select the slug in dataset
        let slug = evt.target.dataset.slug;
        // we checked the stat of button whith is text ("Retourner" or other)
        if (element.textContent === "Retourner") {
            // if it's "Dans le coffre" change for "En attente de retour"
            element.textContent = "Garder";
            // change the status
            gameStatus.textContent = 'Dans le coffre (en attente de retour)';
            // create and insert the row in the return table
            chest.createRow(gameName, slug);
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
        }
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
        // change the button text
        document.querySelector(indexString).textContent = "Dans le coffre";
        // remove the 3 time parent
        evt.path[2].remove();
    },

    /**
     * Method to create the row for return list
     * @param {string} gameName
     * @param {string} slug
     */
    createRow : function(gameName, slug) {
        // create the <tr> whith class="table__head" the dataset number to link whith the back button
        let tr = document.createElement("tr");
        tr.classList.add("table__head");
        tr.dataset.index = chest.index;

        // create <td class="table__head__col"> whith the game name
        let tdName = document.createElement("td");
        tdName.classList.add("table__head__col");
        tdName.textContent = gameName;

        // create <td class="table__head__col"> and the button <div class="button button__chest button__chest--on-table">
        let tdButton = document.createElement("td");
        tdButton.classList.add("table__head__col");
        let button = document.createElement("div");
        button.classList.add("button", "button__chest", "button__chest--on-table");
        button.textContent = "retour dans le coffre";

        let checkbox = chest.createChecbox(slug);

        // create a listener for the new buton
        button.addEventListener('click', chest.handleClickReturn);

        // put the button on the tdButton and after put the bothe <td> in the <tr>
        tdButton.appendChild(button);
        tr.appendChild(checkbox);
        tr.appendChild(tdName);
        tr.appendChild(tdButton);

        // put the <tr> ont the table return
        document.querySelector('.table__content').appendChild(tr);
    },

    createChecbox : function(slug) {
        // create <input type="checkbox" name="" id="" class="checkbox--hiden" checked>
        let checkbox = document.createElement('input');
        checkbox.setAttribute("type", "checkbox");
        checkbox.setAttribute("name", slug);
        checkbox.id = slug;
        checkbox.classList.add('checkbox--hiden');
        checkbox.checked = true;
        return checkbox;
    }

};
document.addEventListener('DOMContentLoaded', chest.init);