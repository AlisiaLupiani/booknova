window.addEventListener("load", function() {
    
    // Gestisce l'aggiunta di prodotti all'interno del carrello
    document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('add_to_cart_btn');

    if (btn) {
        btn.addEventListener('click', function() {
            const bookId = this.dataset.btn-book-id;
            alert("Hai cliccato! Il dato recuperato è: " + bookId);
        });
    }
});

});
