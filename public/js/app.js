//cible mon bouton d'ajout au panier
const addToCartButton = document.getElementById("cart-add-button");

//pour le mettre sous écoute du clic
//au clic, déclencher la requête ajax
addToCartButton.addEventListener("click", addToCart);

//ajoute un chaton au panier, en ajax
function addToCart(event) {
    //récupère l'id du chaton et l'URL de la requête ajax
    let catId = event.currentTarget.dataset.catId;
    let url = event.currentTarget.dataset.ajaxUrl;
    //déclenche la requête POST
    axios.post(url, {
        id: catId
    })
        //fait quelque chose avec la réponse
        .then(function (response) {
            console.log(response);
        });
}