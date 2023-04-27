$(document).ready(function() {
    // Récupérer les éléments HTML
    var navMenu = $('#nav-menu');
    var menuButton = $('#menu-button');

    // Cacher la navigation initialement
    navMenu.hide();

    // Ajouter un écouteur d'événement sur le bouton de menu
    menuButton.click(function() {
    // Afficher ou cacher la navigation selon l'état actuel
    if (navMenu.is(':hidden')) {
    navMenu.slideDown('fast');
} else {
    navMenu.slideUp('fast');
}
});
});

