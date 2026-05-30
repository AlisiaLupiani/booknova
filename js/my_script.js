document.addEventListener('DOMContentLoaded', function () {
    console.log('my_script.js loaded');

    // EVENT DELEGATION: intercetta tutti i click
    document.addEventListener('click', function (event) {

        // ============================
        //  ADD TO CART
        // ============================
        const addBtn = event.target.closest('.add-to-cart, #add_to_cart_btn');
        if (addBtn) {
            event.preventDefault();
            const bookId = addBtn.getAttribute('data-book-id') || addBtn.dataset.bookId;

            if (!bookId) {
                alert('ID libro non trovato.');
                return;
            }

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json'
                },
                body: 'book_id=' + encodeURIComponent(bookId)
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Errore durante l\'aggiunta al carrello.');
                    if (data.redirect) window.location.href = data.redirect;
                    return;
                }
                alert(data.message || 'Libro aggiunto al carrello.');
                if (typeof data.cart_count !== 'undefined') updateHeaderCartCount(data.cart_count);
            })
            .catch(err => console.error('Errore add_to_cart:', err));

            return;
        }

        // ============================
        //  REMOVE FROM CART
        // ============================
        const remBtn = event.target.closest('.btn-remove');
        if (remBtn) {
            event.preventDefault();

            const cartItemId = remBtn.getAttribute('data-cart-item-id') || remBtn.dataset.cartItemId;
            const bookId = remBtn.getAttribute('data-book-id') || remBtn.dataset.bookId;
            const row = remBtn.closest('tr');

            const oldRowTotal = parseFloat(row.dataset.itemTotal) ||
                parseFloat((row.querySelector('td:nth-child(4)') || {}).textContent.replace(/[^0-9.,]/g, '').replace(',', '.')) || 0;

            const payload = cartItemId
                ? ('cart_item_id=' + encodeURIComponent(cartItemId))
                : ('book_id=' + encodeURIComponent(bookId));

            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json'
                },
                body: payload
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Errore durante la rimozione.');
                    return;
                }

                if (row) row.remove();
                updateCartTotals(-oldRowTotal);

                if (typeof data.cart_count !== 'undefined') updateHeaderCartCount(data.cart_count);
            })
            .catch(err => console.error('Errore remove:', err));

            return;
        }

        // ============================
        //  ADD TO WISHLIST (catalogo / dettagli)
        // ============================
        let t = event.target;
        if (t.nodeType !== 1) t = t.parentElement;

        const wishBtn = t.closest('.add-to-wishlist, #add_to_wishlist_btn');

        if (wishBtn) {
            event.preventDefault();

            const bookId = wishBtn.getAttribute('data-book-id') || wishBtn.dataset.bookId;
            if (!bookId) {
                alert('ID libro non trovato.');
                return;
            }

            fetch('add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'book_id=' + encodeURIComponent(bookId)
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Errore durante l\'aggiornamento della wishlist.');
                    if (data.redirect) window.location.href = data.redirect;
                    return;
                }

                alert(data.message || 'Wishlist aggiornata.');

                // Toggle grafico
                if (data.in_wishlist) {
                    wishBtn.textContent = '♥ In wishlist';
                    wishBtn.classList.remove('btn-outline-secondary');
                    wishBtn.classList.add('btn-secondary');
                } else {
                    wishBtn.textContent = '♡ Wishlist';
                    wishBtn.classList.remove('btn-secondary');
                    wishBtn.classList.add('btn-outline-secondary');
                }
            })
            .catch(err => console.error('Errore wishlist:', err));

            return;
        }

        // ============================
        //  REMOVE FROM WISHLIST (pagina wishlist)
        // ============================
        const removeWishBtn = t.closest('.remove-from-wishlist');

        if (removeWishBtn) {
            event.preventDefault();

            const bookId = removeWishBtn.getAttribute('data-book-id') || removeWishBtn.dataset.bookId;

            if (!bookId) {
                alert('ID libro non trovato.');
                return;
            }

            fetch('add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'book_id=' + encodeURIComponent(bookId)
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Errore durante la rimozione dalla wishlist.');
                    return;
                }

                // Rimuove il libro dalla pagina
                const card = removeWishBtn.closest('.col-md-3');
                if (card) card.remove();

                alert(data.message || 'Libro rimosso dalla wishlist.');
            })
            .catch(err => console.error('Errore remove wishlist:', err));

            return;
        }
    });

    // ============================
    //  QUANTITY CHANGE HANDLER
    // ============================
    document.addEventListener('change', function (event) {
        const el = event.target;
        if (!el.classList.contains('cart-qty')) return;

        let qty = parseInt(el.value, 10);
        if (isNaN(qty) || qty < 0) qty = 0;

        const cartItemId = el.dataset.cartItemId || null;
        const bookId = el.dataset.bookId || null;

        const row = el.closest('tr');
        const price = parseFloat(row.dataset.price) ||
            parseFloat((row.querySelector('td:nth-child(2)') || {}).textContent.replace(/[^0-9.,]/g, '').replace(',', '.')) || 0;

        const oldRowTotal = parseFloat(row.dataset.itemTotal) || 0;
        const newRowTotal = +(price * qty).toFixed(2);

        // Aggiorna UI immediatamente
        const totalCell = row.querySelector('td:nth-child(4)');
        if (totalCell) totalCell.textContent = '€ ' + newRowTotal.toFixed(2);
        row.dataset.itemTotal = newRowTotal;

        const payload = cartItemId
            ? ('cart_item_id=' + encodeURIComponent(cartItemId) + '&quantity=' + encodeURIComponent(qty))
            : ('book_id=' + encodeURIComponent(bookId) + '&quantity=' + encodeURIComponent(qty));

        fetch('update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'Accept': 'application/json'
            },
            body: payload
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert(data.message || 'Errore aggiornamento carrello');
                return;
            }

            if (data.removed) {
                if (row) row.remove();
                updateCartTotals(-oldRowTotal);
                return;
            }

            if (typeof data.subtotal !== 'undefined') {
                const subtotalEl = document.getElementById('cart-subtotal');
                const totalEl = document.getElementById('cart-total');
                if (subtotalEl) subtotalEl.textContent = '€ ' + parseFloat(data.subtotal).toFixed(2);
                if (totalEl) totalEl.textContent = '€ ' + parseFloat(data.subtotal).toFixed(2);
                if (typeof data.cart_count !== 'undefined') updateHeaderCartCount(data.cart_count);
            } else {
                const delta = newRowTotal - oldRowTotal;
                updateCartTotals(delta);
            }
        })
        .catch(err => console.error('Errore update_cart:', err));
    });

    // ============================
    //  FUNZIONI DI SUPPORTO
    // ============================
    function updateCartTotals(delta) {
        const subtotalEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        if (!subtotalEl || !totalEl) return;

        function parseMoney(el) {
            return parseFloat(el.textContent.replace(/[^0-9.,]/g, '').replace(',', '.')) || 0;
        }

        let subtotal = parseMoney(subtotalEl);
        let total = parseMoney(totalEl);

        subtotal = +(subtotal + delta).toFixed(2);
        total = +(total + delta).toFixed(2);

        subtotalEl.textContent = '€ ' + subtotal.toFixed(2);
        totalEl.textContent = '€ ' + total.toFixed(2);
    }

    function updateHeaderCartCount(count) {
        const el = document.getElementById('cart-count');
        if (el) el.textContent = '(' + parseInt(count, 10) + ')';
    }
});
