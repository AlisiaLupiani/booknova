
document.addEventListener('DOMContentLoaded', function () {
document.addEventListener('DOMContentLoaded', function () {
    console.log('wishlist script loaded');

    const wishBtn = document.querySelector('.add-to-wishlist');

    if (!wishBtn) {
        console.log('Bottone wishlist non trovato');
        return;
    }

    wishBtn.addEventListener('click', function (event) {
        event.preventDefault();

        const bookId = wishBtn.getAttribute('data-book-id');

        if (!bookId) {
            alert('ID libro non trovato.');
            return;
        }

        fetch('add_to_wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'Accept': 'application/json'
            },
            body: 'book_id=' + encodeURIComponent(bookId)
        })
        .then(function (res) {
            return res.text();
        })
        .then(function (text) {
            console.log('Risposta PHP wishlist:', text);

            let data;

            try {
                data = JSON.parse(text);
            } catch (e) {
                alert('Risposta PHP non valida. Controlla la console.');
                return;
            }

            if (!data.success) {
                alert(data.message || 'Errore wishlist.');

                if (data.redirect) {
                    window.location.href = data.redirect;
                }

                return;
            }

            alert(data.message || 'Wishlist aggiornata.');

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
        .catch(function (err) {
            console.error('Errore wishlist:', err);
            alert('Errore durante la richiesta wishlist.');
        });
    });
});
    console.log('my_script.js loaded');

    // Delegate clicks: add to cart and remove
    document.addEventListener('click', function (event) {
        // Add to cart
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
            .catch(err => { console.error('Errore add_to_cart:', err); alert('Errore durante l\'aggiunta al carrello. Controlla la console.'); });

            return;
        }

        // Remove from cart
        const remBtn = event.target.closest('.btn-remove');
        if (remBtn) {
            event.preventDefault();
            const cartItemId = remBtn.getAttribute('data-cart-item-id') || remBtn.dataset.cartItemId;
            const bookId = remBtn.getAttribute('data-book-id') || remBtn.dataset.bookId;
            const row = remBtn.closest('tr');
            const oldRowTotal = parseFloat(row.dataset.itemTotal) || (parseFloat((row.querySelector('td:nth-child(4)')||{}).textContent.replace(/[^0-9.,]/g,'').replace(',','.')) || 0);

            const payload = cartItemId ? ('cart_item_id=' + encodeURIComponent(cartItemId)) : ('book_id=' + encodeURIComponent(bookId));

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
                // remove row and update totals (delta = -oldRowTotal)
                if (row) row.remove();
                updateCartTotals(-oldRowTotal);
                if (typeof data.cart_count !== 'undefined') updateHeaderCartCount(data.cart_count);
            })
            .catch(err => { console.error('Errore remove:', err); alert('Errore durante la rimozione. Controlla la console.'); });

            return;
        }
    });
 const wishBtn = event.target.closest('.add-to-wishlist');
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
        .catch(err => { console.error('Errore wishlist:', err); alert('Errore durante l\'aggiornamento della wishlist. Controlla la console.'); });

        return;
    }
   

    // Quantity change handler (debounce not implemented; change event on input triggers)
    document.addEventListener('change', function (event) {
        const el = event.target;
        if (!el || !el.classList || !el.classList.contains('cart-qty')) return;

        let qty = parseInt(el.value, 10);
        if (isNaN(qty) || qty < 0) qty = 0;

        const cartItemId = el.getAttribute('data-cart-item-id') || el.dataset.cartItemId || null;
        const bookId = el.getAttribute('data-book-id') || el.dataset.bookId || null;
        const row = el.closest('tr');
        const price = parseFloat(row.dataset.price) || parseFloat((row.querySelector('td:nth-child(2)')||{}).textContent.replace(/[^0-9.,]/g,'').replace(',','.')) || 0;
        const oldRowTotal = parseFloat(row.dataset.itemTotal) || 0;
        const newRowTotal = +(price * qty).toFixed(2);

        // Update UI immediately
        const totalCell = row.querySelector('td:nth-child(4)');
        if (totalCell) totalCell.textContent = '€ ' + newRowTotal.toFixed(2);
        row.dataset.itemTotal = newRowTotal;

        const payload = cartItemId ? ('cart_item_id=' + encodeURIComponent(cartItemId) + '&quantity=' + encodeURIComponent(qty)) : ('book_id=' + encodeURIComponent(bookId) + '&quantity=' + encodeURIComponent(qty));

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
                // server removed the item
                if (row) row.remove();
                updateCartTotals(-oldRowTotal);
                return;
            }

            // Update subtotal/total using server authoritative subtotal when provided
            if (typeof data.subtotal !== 'undefined') {
                const subtotalEl = document.getElementById('cart-subtotal');
                const totalEl = document.getElementById('cart-total');
                if (subtotalEl) subtotalEl.textContent = '€ ' + parseFloat(data.subtotal).toFixed(2);
                if (totalEl) totalEl.textContent = '€ ' + parseFloat(data.subtotal).toFixed(2);
                if (typeof data.cart_count !== 'undefined') updateHeaderCartCount(data.cart_count);
            } else {
                // Fallback: adjust by delta
                const delta = newRowTotal - oldRowTotal;
                updateCartTotals(delta);
            }
        })
        .catch(err => { console.error('Errore update_cart:', err); alert('Errore durante l\'aggiornamento della quantità.'); });
    });

    // delta: positive => add to totals, negative => subtract
    function updateCartTotals(delta) {
        const subtotalEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        if (!subtotalEl || !totalEl) return;

        function parseMoney(el) {
            if (!el) return 0;
            return parseFloat(el.textContent.replace(/[^0-9.,]/g,'').replace(',','.')) || 0;
        }

        let subtotal = parseMoney(subtotalEl);
        let total = parseMoney(totalEl);
        subtotal = +(subtotal + delta).toFixed(2);
        total = +(total + delta).toFixed(2);

        subtotalEl.textContent = '€ ' + subtotal.toFixed(2);
        totalEl.textContent = '€ ' + total.toFixed(2);
    }
    
    function updateHeaderCartCount(count) {
        try {
            const el = document.getElementById('cart-count');
            if (el) {
                el.textContent = '(' + parseInt(count, 10) + ')';
            }
        } catch (e) { console.error('updateHeaderCartCount error', e); }
    }
});