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

    // ============================
    // REGISTRAZIONE - STEP 1: Verifica email
    // ============================
    const registrationForm = document.getElementById('registration-form');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const name = document.getElementById('name').value.trim();
            const surname = document.getElementById('surname').value.trim();
            const shipping_address = document.getElementById('shipping_address').value.trim();
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('submit-btn');
            
            // Validazione client-side
            if (!email || !name || !surname || !shipping_address || !password) {
                showRegistrationStatus('✗ Compila tutti i campi', 'error');
                return;
            }
            
            if (password.length < 6) {
                showRegistrationStatus('✗ La password deve avere almeno 6 caratteri', 'error');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Registrazione in corso...';
            
            // Invia i dati al server
            fetch('register_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json'
                },
                body: 'email=' + encodeURIComponent(email) + 
                      '&name=' + encodeURIComponent(name) + 
                      '&surname=' + encodeURIComponent(surname) + 
                      '&shipping_address=' + encodeURIComponent(shipping_address) + 
                      '&password=' + encodeURIComponent(password)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showRegistrationStatus('✓ ' + data.message, 'success');
                    registrationForm.reset();
                    setTimeout(() => {
                        window.location.href = data.redirect || 'login.php';
                    }, 2000);
                } else {
                    showRegistrationStatus('✗ ' + (data.message || 'Errore durante la registrazione'), 'error');
                }
            })
            .catch(err => {
                console.error('Errore:', err);
                showRegistrationStatus('✗ Errore di connessione. Riprova.', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Registrati';
            });
        });
    }

    // ============================
    // AGGIUNGI RECENSIONE - SUBMIT VIA AJAX
    // Cerca il form con id 'reviewForm' e gestisci l'invio in modo coerente con gli altri endpoint
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const msg = document.getElementById('review-message');
            if (msg) { msg.className = ''; msg.textContent = ''; }

            const submitBtn = reviewForm.querySelector('button[type="submit"]');
            if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Invio...'; }

            const formData = new FormData(reviewForm);

            fetch('submit_review.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (msg) {
                    if (data.success) {
                        msg.className = 'success';
                        msg.textContent = data.message || 'Recensione inviata con successo.';
                    } else {
                        msg.className = 'error';
                        msg.textContent = data.message || 'Errore nell\'invio della recensione.';
                    }
                }

                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(err => {
                if (msg) { msg.className = 'error'; msg.textContent = 'Errore di rete durante l\'invio.'; }
                console.error('Errore submit review:', err);
            })
            .finally(() => {
                if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Invia Recensione'; }
            });
        });
    }

    // ============================
    // CAMBIO PASSWORD - STEP 1: Verifica email e nuova password
    // ============================
    const passwordFormStep1 = document.getElementById('form-step1');
    if (passwordFormStep1) {
        passwordFormStep1.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const newPassword = document.getElementById('new-password').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            
            if (!email || !newPassword) {
                showPasswordStatus('✗ Compila tutti i campi', 'error');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Verifica in corso...';
            
            fetch('change_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json'
                },
                body: 'action=verify_email&email=' + encodeURIComponent(email) + '&new_password=' + encodeURIComponent(newPassword)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showPasswordStatus('✓ ' + data.message, 'success');
                    document.getElementById('step1').style.display = 'none';
                    document.getElementById('step2').style.display = 'block';
                    document.getElementById('old-password').focus();
                } else {
                    showPasswordStatus('✗ ' + (data.message || 'Errore durante la verifica'), 'error');
                }
            })
            .catch(err => {
                console.error('Errore:', err);
                showPasswordStatus('✗ Errore di connessione. Riprova.', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Recupera Password';
            });
        });
    }

    // ============================
    // CAMBIO PASSWORD - STEP 2: Verifica vecchia password e aggiorna
    // ============================
    const passwordFormStep2 = document.getElementById('form-step2');
    if (passwordFormStep2) {
        passwordFormStep2.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const oldPassword = document.getElementById('old-password').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            
            if (!oldPassword) {
                showPasswordStatus('✗ Inserisci la vecchia password', 'error');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Aggiornamento in corso...';
            
            fetch('change_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json'
                },
                body: 'action=verify_old_password&old_password=' + encodeURIComponent(oldPassword)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showPasswordStatus('✓ ' + data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || 'login.php';
                    }, 2000);
                } else {
                    showPasswordStatus('✗ ' + (data.message || 'Errore durante l\'aggiornamento'), 'error');
                }
            })
            .catch(err => {
                console.error('Errore:', err);
                showPasswordStatus('✗ Errore di connessione. Riprova.', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Conferma Cambio Password';
            });
        });
    }

    // ============================
    // FUNZIONI HELPER PER STATUS
    // ============================
    function showRegistrationStatus(message, type = 'info') {
        const statusEl = document.getElementById('status-message');
        if (statusEl) {
            statusEl.textContent = message;
            statusEl.className = type;
            statusEl.style.display = 'block';
            window.scrollTo(0, 0);
        }
    }

    function showPasswordStatus(message, type = 'info') {
        const statusEl = document.getElementById('status-message');
        if (statusEl) {
            statusEl.textContent = message;
            statusEl.className = type;
            statusEl.style.display = 'block';
            window.scrollTo(0, 0);
        }
    }

    function resetPasswordForm() {
        const form1 = document.getElementById('form-step1');
        const form2 = document.getElementById('form-step2');
        if (form1) form1.reset();
        if (form2) form2.reset();
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        if (step1) step1.style.display = 'block';
        if (step2) step2.style.display = 'none';
        const statusEl = document.getElementById('status-message');
        if (statusEl) statusEl.style.display = 'none';
    }

    // Esponi la funzione nel global scope per il pulsante HTML
    window.resetPasswordForm = resetPasswordForm;
});
