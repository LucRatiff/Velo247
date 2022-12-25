let modalOpen = false;

/**
 * @param {String} id : id du div déjà créé et placé
 * @returns {undefined}
 */
function createModal(id) {
    modalOpen = true;
    let layer = document.createElement('div');
    layer.id = 'layer';
    document.body.appendChild(layer);
    document.getElementById('layer').addEventListener('click', (e) => {
        document.getElementById(id).remove();
        document.getElementById('layer').remove();
        modalOpen = false;
    });
}
