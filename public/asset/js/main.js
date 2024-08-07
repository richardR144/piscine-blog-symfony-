console.log('hello');

//je créé une variable deleteArticleButtons et j'appelle tous les sélecteurs qui ont la class .js-admin-article-delete
//pour les stocker à l'intérieur
const deleteArticleButtons = document.querySelectorAll('.js-admin-article-delete');


deleteArticleButtons.forEach((deleteArticleButton) => {
    //pour chaque bouton de suppression trouvé je leur applique un addEventlistener click et quand il est cliqué ça renvoie un callback
    // callback , çà permet aussi une règle de validation, et permet aussi d'afficher des messages d'erreur dans les champs de mon objet
    deleteArticleButton.addEventListener('click', () => {
        //pour le popup, ??
        const popup = deleteArticleButton.nextElementSibling;
        popup.style.display = "block";
    });
});
console.log(deleteArticleButtons);