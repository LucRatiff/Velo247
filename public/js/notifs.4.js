let notifsNb = 0;

setInterval(checkNotifNb, 60000);

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
    removeNotifNb();
});

function notifModalContentWriter() {
    fetch(host + '/api/notification', {
        method: 'POST'
    }).then((response) => {
        response.text().then((html) => {
            if (document.getElementById('notif-modal') !== null) {
                document.getElementById('notif-loading').remove();
                document.getElementById('notif-modal-content-content').innerHTML = html;
            }
        });
    });
}

function checkNotifNb() {
    fetch(host + '/api/notification_nb', {
        method: 'POST'
    }).then((response) => {
        response.text().then((number => {
            if (number != notifsNb) {
                if (notifsNb == 0) {
                    displayNotifNb();
                    updateNotifNb(notifsNb);
                } else if (number == 0) {
                    removeNotifNb();
                } else {
                    updateNotifNb(notifsNb);
                }
                notifsNb = number;
            }
        }));
    });
}

function displayNotifNb() {
    if (document.getElementById('notifs-nb') === null) {
        let div = document.createElement('div');
        div.id = 'notifs-nb';
        document.getElementsByClassName('notifs-container')[0].appendChild(div);
    }
}

function removeNotifNb() {
    let element = document.getElementById('notifs-nb');
    if (element !== null) {
        element.remove();
    }
}

function updateNotifNb(nb) {
    document.getElementById('notifs-nb').innerHTML = nb;
}
