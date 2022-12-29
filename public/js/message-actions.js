let modifyButtons = document.getElementsByClassName('message-modify');
let deleteButtons = document.getElementsByClassName('message-delete');

for (let i = 0; i < modifyButtons.length; i++) {
    modifyButtons[i].addEventListener('click', prepareModifyMessage(i));
}

for (let i = 0; i < deleteButtons.length; i++) {
    deleteButtons[i].addEventListener('click', prepareDeleteMessage(i));
}

function prepareModifyMessage(nb) {
    return function() { modifyMessage(nb); };
}

function modifyMessage(nb) {
    if (modalOpen) {
        return;
    }
    let div = document.createElement('div');
    div.id = 'message-modify-modal';
    div.classList.add('centered-modal');
    let h6 = document.createElement('h6');
    h6.innerHTML = 'Modifier le message';
    let form = document.createElement('form');
    form.id = 'form-modify-message';
    if (nb == 0) {
        let title = document.createElement('textarea');
        title.innerHTML = document.getElementById('topic-title').innerHTML;
        title.name = 'title';
        form.appendChild(title);
    }
    let textarea = document.createElement('textarea');
    textarea.name = 'content';
    textarea.innerHTML = document.getElementsByClassName('content')[nb];
    let modifyConfirmationButtons = document.createElement('div');
    modifyConfirmationButtons.id = 'confirmation-buttons';
    let buttonNo = document.createElement('button');
    let buttonYes = document.createElement('button');
    buttonNo.id = 'button-no';
    buttonYes.id = 'button-yes';
    buttonNo.innerHTML = 'Annuler';
    buttonYes.innerHTML = 'Modifier';
    modifyConfirmationButtons.appendChild(buttonNo);
    modifyConfirmationButtons.appendChild(buttonYes);
    form.appendChild(textarea);
    form.appendChild(modifyConfirmationButtons);
    div.appendChild(h6);
    div.appendChild(form);
    document.body.appendChild(div);
    createModal('message-modify-modal');
    
    buttonNo.addEventListener('click', () => {
        removeModal('delete-confirmation');
    });
    
    document.getElementById('form-modify-message').addEventListener('submit', (e) => {
        e.preventDefault();
        fetch(host + '/api/message/modify/' + document.getElementsByClassName('message')[nb].id, {
            method: 'POST',
            body: new FormData(e.target)
        }).then((response) => {
            response.text().then((html) => {
                if (html == 'true') {
                    location.reload();
                }
            });
        });
    });
}

function prepareDeleteMessage(nb) {
    return function() { deleteMessage(nb); };
}

function deleteMessage(nb) {
    if (modalOpen) {
        return;
    }
    let div = document.createElement('div');
    div.id = 'delete-confirmation';
    div.classList.add('centered-modal');
    let p = document.createElement('p');
    p.innerHTML = nb == 0 ? 'Etes-vous sûr de vouloir supprimer le sujet ?'
        : 'Etes-vous sûr de vouloir supprimer ce message ?';
    let deleteConfirmationButtons = document.createElement('div');
    deleteConfirmationButtons.id = 'confirmation-buttons';
    let buttonNo = document.createElement('button');
    let buttonYes = document.createElement('button');
    buttonNo.id = 'button-no';
    buttonYes.id = 'button-yes';
    buttonNo.innerHTML = 'Non';
    buttonYes.innerHTML = 'Oui';
    deleteConfirmationButtons.appendChild(buttonNo);
    deleteConfirmationButtons.appendChild(buttonYes);
    div.appendChild(p);
    div.appendChild(deleteConfirmationButtons);
    document.body.appendChild(div);
    createModal('delete-confirmation');
    
    buttonNo.addEventListener('click', () => {
        removeModal('delete-confirmation');
    });

    document.getElementById('delete-button-yes').addEventListener('click', () => {
        fetch(host + '/api/message/delete' + (nb == 0 ? '-topic/' : '/') + document.getElementsByClassName('message')[nb].id, {
            method: 'POST'
        }).then((response) => {
            response.text().then((html) => {
                if (html == 'true') {
                    location.reload();
                }
            });
        });
    });
}
