//cible mon bouton
const addToCartButton = document.getElementById("cart-add-button");

//pour le mettre sous écoute du clic
//au clic, déclencher la requête ajax
addToCartButton.addEventListener("click", addToCart);

function addToCart(event) {
    let catId = event.currentTarget.dataset.catId;
    let url = event.currentTarget.dataset.ajaxUrl;
    axios.post(url, {
        id: catId
    })
        .then(function (response) {
            console.log(response);
        });
}