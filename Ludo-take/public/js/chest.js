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
    handleClick: function(evt) {
        // select 2 element before (is the checkbox)
        let element = evt.target;
        let gameName = evt.path[1].previousElementSibling.previousElementSibling.previousElementSibling.textContent;
        if (element.textContent === "Dans le coffre") {
            element.textContent = "En attente de retour";
            let tr = document.createElement("tr");
            tr.classList.add("table__head");
            tr.dataset.index = chest.index;
            element.dataset.indexLink = chest.index;
            chest.index++;
            let tdName = document.createElement("td");
            tdName.classList.add("table__head__col");
            tdName.textContent = gameName;
            let tdButton = document.createElement("td");
            tdButton.classList.add("table__head__col");
            let button = document.createElement("div");
            button.classList.add("button", "button__chest", "button__chest--on-table");
            button.textContent = "retour dans le coffre";
            button.addEventListener('click', chest.handleClickReturn);
            tdButton.appendChild(button);
            tr.appendChild(tdName);
            tr.appendChild(tdButton);
            document.querySelector('.table__content').appendChild(tr);
        } else {
            element.textContent = "Dans le coffre";
            let dataToDelete = element.dataset.indexLink;
            let indexString = '[data-index~="' + dataToDelete + '"]';
            let elementToDelete = document.querySelector(indexString);
            elementToDelete.remove();
        }
        // uncheck the checkbox
        element.checked = true;
    },

    handleClickReturn : function(evt) {
        let dataToChange = evt.path[2].dataset.index;
        let indexString = '[data-index-link~="' + dataToChange + '"]';
        document.querySelector(indexString).textContent = "Dans le coffre";
        evt.path[2].remove();
    },
};
document.addEventListener('DOMContentLoaded', chest.init);