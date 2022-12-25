document.getElementsByClassName('notifs-container')[0].addEventListener('click', () => {
    if (modalOpen) {
        return;
    }
    let notifModal = document.createElement('div');
    let notifModalArrow = document.createElement('div');
    let notifModalContentWrapper = document.createElement('div');
    let notifModalContent = document.createElement('div');
    let notifModalContentTitle = document.createElement('div');
    let notifModalContentContent = document.createElement('div');
    notifModal.id = 'notif-modal';
    notifModalArrow.id = 'notif-modal-arrow';
    notifModalContentWrapper.id = 'notif-modal-content-wrapper';
    notifModalContent.id = 'notif-modal-content';
    notifModalContentTitle.id = 'notif-modal-content-title';
    notifModalContentContent.id = 'notif-modal-content-content';
    notifModalContentTitle.innerHTML = 'Notifications';
    notifModalContent.appendChild(notifModalContentContent);
    notifModalContentWrapper.appendChild(notifModalArrow);
    notifModalContentWrapper.appendChild(notifModalContentTitle);
    notifModalContentWrapper.appendChild(notifModalContent);
    notifModal.appendChild(notifModalContentWrapper);
    notifModalContentContent.innerHTML = '<img id="notif-loading" src="' + host + '/images/loading.gif">';
    document.getElementsByClassName('notifs')[0].appendChild(notifModal);
    createModal('notif-modal');
    notifModalContentWriter();
});

function notifModalContentWriter() {
    fetch(host + '/notification', {
        method: 'POST'
    }).then((response) => {
        response.text().then((html => {
            if (typeof document.getElementById('notif-modal') !== 'undefined') {
                document.getElementById('notif-loading').remove();
                document.getElementById('notif-modal-content-content').innerHTML = html;
            }
        }));
    });
}

//créer un timer pour vérifier le nombre de notifs
