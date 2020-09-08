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

//met sous écoute le champ de recherche
const searchInput = document.getElementById("keyword");
const searchForm = document.getElementById("search-form");
const resultsDiv = document.getElementById("results");
searchInput.addEventListener("input", searchByKeyword);

//appelée chaque fois que le mot-clef recherché change
function searchByKeyword(event){
    let keyword = event.currentTarget.value;

    //si le mot-clef fait moins de 2 caractères, on vide les réponses et on arrête tout
    if (keyword.length < 2){
        resultsDiv.innerHTML = "";
        return;
    }

    let url = searchForm.action;
    axios.get(url, {params: {keyword: keyword}})
        .then(function(response){
            //vide d'abord la div de résultats
            resultsDiv.innerHTML = "";
            response.data.cats.forEach(cat => {
                //crée un lien pour chaque chat
                let link = document.createElement('a');
                link.href = cat.url;
                link.innerHTML = cat.name;

                //ajoute le lien dans la div
                resultsDiv.appendChild(link);
            });
        });
}