document.getElementsByClassName('notifs-container')[0].addEventListener('click', () => {
    if (modalOpen) {
        return;
    }
    let notifModal = document.createElement('div');
    let notifModalArrow = document.createElement('div');
    let notifModalContent = document.createElement('div');
    notifModal.id = 'notif-modal';
    notifModalArrow.id = 'notif-modal-arrow';
    notifModalContent.id = 'notif-modal-content';
    notifModalContent.appendChild(notifModalArrow);
    notifModal.appendChild(notifModalContent);
    notifModalContent.innerHTML += '<img id="notif-loading" src="https://' + window.location.hostname + '/images/loading.png">';
    document.getElementsByClassName('notifs')[0].appendChild(notifModal);
    createModal('notif-modal');
    notifModalContentWriter();
});

function notifModalContentWriter() {
    fetch('https://' + window.location.hostname + ':8000/notification', {
        method: 'POST'
    }).then((response) => {
        response.text().then((html => {
            if (typeof document.getElementById('notif-modal') !== 'undefined') {
                document.getElementById('notif-loading').remove();
                document.getElementById('notif-modal-content').innerHTML += html;
            }
        }));
    });
}

//créer un timer pour vérifier le nombre de notifs
