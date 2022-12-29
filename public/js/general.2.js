const host = 'https://' + window.location.hostname + (window.location.port.length === 0 ? "" : ":" + window.location.port);
let modalOpen = false;

/**
 * @param {String} id : id du div déjà créé et placé
 * @returns {undefined}
 */
function createModal(id) {
    modalOpen = true;
    document.body.style.overflow = 'hidden';
    let layer = document.createElement('div');
    layer.id = 'layer';
    document.body.appendChild(layer);
    layer.style.height = document.body.clientHeight + 'px';
    document.getElementById('layer').addEventListener('click', () => {
        removeModal(id);
    });
}

function removeModal(id) {
    document.body.style.overflow = 'visible';
    document.getElementById(id).remove();
    document.getElementById('layer').remove();
    modalOpen = false;
}