document.addEventListener('DOMContentLoaded', function () {
    console.log('my_script.js loaded');

    // ============================
    // CHECKOUT PAYMENT FORM
    // ============================
  const checkoutPaymentForm = document.getElementById('checkoutPaymentForm');

if (checkoutPaymentForm) {
    checkoutPaymentForm.addEventListener('submit', function (e) {
        e.preventDefault(); // blocca il submit normale

        const submitBtn = this.querySelector('button[type="submit"]');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Pagamento in corso...';
        }

        const formData = new FormData(this);

        fetch('pay.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (res) {
            return res.json();
        })
        .then(function (data) {
            alert(data.message || 'Pagamento completato.');

            if (data.success) {
                window.location.href =
                    'cart.php?user_id=' + encodeURIComponent(formData.get('user_id'));
            }
        })
        .catch(function (err) {
            console.error('Errore pagamento:', err);
            alert('Errore di connessione. Riprova.');
        })
        .finally(function () {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Paga Ora';
            }
        });

        return false;   
    });
}


    // ============================
    // EVENT DELEGATION CLICK
    // ============================
    document.addEventListener('click', function (event) {
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
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (!data.success) {
                    alert(data.message || 'Errore durante l\'aggiunta al carrello.');
                    if (data.redirect) window.location.href = data.redirect;
                    return;
                }

                alert(data.message || 'Libro aggiunto al carrello.');

                if (typeof data.cart_count !== 'undefined') {
                    updateHeaderCartCount(data.cart_count);
                }
            })
            .catch(function (err) {
                console.error('Errore add_to_cart:', err);
            });

            return;
        }

        const remBtn = event.target.closest('.btn-remove');

        if (remBtn) {
            event.preventDefault();

            const cartItemId = remBtn.getAttribute('data-cart-item-id') || remBtn.dataset.cartItemId;
            const bookId = remBtn.getAttribute('data-book-id') || remBtn.dataset.bookId;
            const row = remBtn.closest('tr');

            const oldRowTotal = row
                ? parseFloat(row.dataset.itemTotal) ||
                  parseFloat((row.querySelector('td:nth-child(4)') || {}).textContent.replace(/[^0-9.,]/g, '').replace(',', '.')) ||
                  0
                : 0;

            const payload = cartItemId
                ? 'cart_item_id=' + encodeURIComponent(cartItemId)
                : 'book_id=' + encodeURIComponent(bookId);

            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json'
                },
                body: payload
            })
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (!data.success) {
                    alert(data.message || 'Errore durante la rimozione.');
                    return;
                }

                if (row) row.remove();

                updateCartTotals(-oldRowTotal);

                if (typeof data.cart_count !== 'undefined') {
                    updateHeaderCartCount(data.cart_count);
                }
            })
            .catch(function (err) {
                console.error('Errore remove:', err);
            });

            return;
        }

        let target = event.target;
        if (target.nodeType !== 1) target = target.parentElement;

        const wishBtn = target.closest('.add-to-wishlist, #add_to_wishlist_btn');

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
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (!data.success) {
                    alert(data.message || 'Errore durante l\'aggiornamento della wishlist.');
                    if (data.redirect) window.location.href = data.redirect;
                    return;
                }

                alert(data.message || 'Wishlist aggiornata.');

                if (data.in_wishlist) {
                    wishBtn.textContent = 'In wishlist';
                    wishBtn.classList.remove('btn-outline-secondary');
                    wishBtn.classList.add('btn-secondary');
                } else {
                    wishBtn.textContent = 'Wishlist';
                    wishBtn.classList.remove('btn-secondary');
                    wishBtn.classList.add('btn-outline-secondary');
                }
            })
            .catch(function (err) {
                console.error('Errore wishlist:', err);
            });

            return;
        }

        const removeWishBtn = target.closest('.remove-from-wishlist');

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
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (!data.success) {
                    alert(data.message || 'Errore durante la rimozione dalla wishlist.');
                    return;
                }

                const card = removeWishBtn.closest('.col-md-3');
                if (card) card.remove();

                alert(data.message || 'Libro rimosso dalla wishlist.');
            })
            .catch(function (err) {
                console.error('Errore remove wishlist:', err);
            });
        }
    });

    // ============================
    // QUANTITY CHANGE HANDLER
    // ============================
    document.addEventListener('change', function (event) {
        const el = event.target;

        if (!el.classList.contains('cart-qty')) return;

        let qty = parseInt(el.value, 10);
        if (isNaN(qty) || qty < 0) qty = 0;

        const cartItemId = el.dataset.cartItemId || null;
        const bookId = el.dataset.bookId || null;
        const row = el.closest('tr');

        if (!row) return;

        const price = parseFloat(row.dataset.price) ||
            parseFloat((row.querySelector('td:nth-child(2)') || {}).textContent.replace(/[^0-9.,]/g, '').replace(',', '.')) ||
            0;

        const oldRowTotal = parseFloat(row.dataset.itemTotal) || 0;
        const newRowTotal = +(price * qty).toFixed(2);

        const totalCell = row.querySelector('td:nth-child(4)');
        if (totalCell) totalCell.textContent = '\u20ac ' + newRowTotal.toFixed(2);

        row.dataset.itemTotal = newRowTotal;

        const payload = cartItemId
            ? 'cart_item_id=' + encodeURIComponent(cartItemId) + '&quantity=' + encodeURIComponent(qty)
            : 'book_id=' + encodeURIComponent(bookId) + '&quantity=' + encodeURIComponent(qty);

        fetch('update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'Accept': 'application/json'
            },
            body: payload
        })
        .then(function (res) {
            return res.json();
        })
        .then(function (data) {
            if (!data.success) {
                alert(data.message || 'Errore aggiornamento carrello');
                return;
            }

            if (data.removed) {
                row.remove();
                updateCartTotals(-oldRowTotal);
                return;
            }

            if (typeof data.subtotal !== 'undefined') {
                const subtotalEl = document.getElementById('cart-subtotal');
                const totalEl = document.getElementById('cart-total');

                if (subtotalEl) subtotalEl.textContent = '\u20ac ' + parseFloat(data.subtotal).toFixed(2);
                if (totalEl) totalEl.textContent = '\u20ac ' + parseFloat(data.subtotal).toFixed(2);

                if (typeof data.cart_count !== 'undefined') {
                    updateHeaderCartCount(data.cart_count);
                }
            } else {
                updateCartTotals(newRowTotal - oldRowTotal);
            }
        })
        .catch(function (err) {
            console.error('Errore update_cart:', err);
        });
    });

    // ============================
    // REGISTRAZIONE
    // ============================
    const registrationForm = document.getElementById('registration-form');

    if (registrationForm) {
        registrationForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const name = document.getElementById('name').value.trim();
            const surname = document.getElementById('surname').value.trim();
            const shippingAddress = document.getElementById('shipping_address').value.trim();
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('submit-btn');

            if (!email || !name || !surname || !shippingAddress || !password) {
                showRegistrationStatus('Compila tutti i campi', 'error');
                return;
            }

            if (password.length < 6) {
                showRegistrationStatus('La password deve avere almeno 6 caratteri', 'error');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Registrazione in corso...';

            fetch('register_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept': 'application/json'
                },
                body:
                    'email=' + encodeURIComponent(email) +
                    '&name=' + encodeURIComponent(name) +
                    '&surname=' + encodeURIComponent(surname) +
                    '&shipping_address=' + encodeURIComponent(shippingAddress) +
                    '&password=' + encodeURIComponent(password)
            })
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (data.success) {
                    showRegistrationStatus(data.message || 'Registrazione completata.', 'success');
                    registrationForm.reset();

                    setTimeout(function () {
                        window.location.href = data.redirect || 'login.php';
                    }, 2000);
                } else {
                    showRegistrationStatus(data.message || 'Errore durante la registrazione', 'error');
                }
            })
            .catch(function (err) {
                console.error('Errore registrazione:', err);
                showRegistrationStatus('Errore di connessione. Riprova.', 'error');
            })
            .finally(function () {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Registrati';
            });
        });
    }





// ============================
// AGGIUNTA LIBRO (ADMIN)
// ============================
const addBookForm = document.getElementById('addBookForm');

if (addBookForm) {
    addBookForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = addBookForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Salvataggio...';
        }

        let formData = new FormData(addBookForm);

        fetch('save_book.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || (data.success ? 'Libro aggiunto!' : 'Errore durante il salvataggio.'));

            if (data.success) {
                addBookForm.reset();
            }
        })
        .catch(err => {
            console.error('Errore AJAX aggiunta libro:', err);
            alert('Errore di connessione.');
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Salva Libro nel Database';
            }
        });
    });
} 
// ============================
// AGGIUNTA ADMIN (ADMIN)
// ============================

    const addAdminForm = document.getElementById('addAdminForm');

    if (addAdminForm) {
        addAdminForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = addAdminForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Salvataggio...';

            let formData = new FormData(addAdminForm);

            fetch('save_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);

                if (data.success) {
                    addAdminForm.reset();
                }
            })
            .catch(err => {
                console.error('Errore AJAX aggiunta admin:', err);
                alert('Errore di connessione.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Salva';
            });
        });
    }

// ============================
// AGGIUNTA AUTORE (ADMIN)
// ============================

    const addAuthorForm = document.getElementById('addAuthorForm');

    if (addAuthorForm) {
        addAuthorForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = addAuthorForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Salvataggio...';

            let formData = new FormData(addAuthorForm);

            fetch('save_author.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);

                if (data.success) {
                    addAuthorForm.reset();
                }
            })
            .catch(err => {
                console.error('Errore AJAX aggiunta autore:', err);
                alert('Errore di connessione.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Salva';
            });
        });
    }

// ============================
// AGGIUNTA CATEGORIA (ADMIN)
// ============================

    const addCategoryForm = document.getElementById('addCategoryForm');

    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = addCategoryForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Salvataggio...';

            let formData = new FormData(addCategoryForm);

            fetch('save_category.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);

                if (data.success) {
                    addCategoryForm.reset();
                }
            })
            .catch(err => {
                console.error('Errore AJAX aggiunta categoria:', err);
                alert('Errore di connessione.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Salva';
            });
        });
    }



// ============================
// MODIFICA LIBRO (ADMIN) - AJAX
// ============================

const editBookForm = document.getElementById('editBookForm');

if (editBookForm) {
    editBookForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = editBookForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Salvataggio...';
        }

        // INVIA TUTTI I CAMPI DEL FORM, COMPRESO bookId
        const formData = new FormData(editBookForm);

        fetch('modifica_libro_process.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || (data.success ? 'Libro aggiornato!' : 'Errore durante il salvataggio.'));

            if (data.success) {
                window.location.href = 'visualizza_libri.php';
            }
        })
        .catch(err => {
            console.error('Errore AJAX modifica libro:', err);
            alert('Errore di connessione.');
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Salva Modifiche';
            }
        });
    });
}
// ============================
// ELIMINA LIBRO (ADMIN) 
// ============================

document.addEventListener("DOMContentLoaded", function () {

    // ELIMINAZIONE LIBRO AJAX
    document.querySelectorAll(".delete-book").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();

            if (!confirm("Sei sicura di voler eliminare questo libro?")) return;

            const bookId = this.dataset.id;
            const row = this.closest("tr");

            fetch("php/delete.php", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "book_id=" + bookId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    row.style.backgroundColor = "#ffdddd";
                    setTimeout(() => row.remove(), 300);
                } else {
                    alert("Errore: " + data.message);
                }
            })
            .catch(err => alert("Errore di rete"));
        });
    });

});
 // ============================
// ELIMINA AURORE CON LIBRI A CASCATA  (ADMIN) 
// ============================


  // Delegation: intercetta click su qualsiasi bottone con classe .btn-delete-author
  document.body.addEventListener('click', async function (e) {
    const btn = e.target.closest('.btn-delete-author');
    if (!btn) return;

    e.preventDefault();

    const authorId = btn.dataset.authorId;
    if (!authorId) {
      alert('ID autore mancante');
      return;
    }

    if (!confirm('Sei sicuro di voler eliminare questo autore e tutti i suoi libri?')) return;

    // Protezione doppio click
    if (btn.dataset.sending === '1') return;
    btn.dataset.sending = '1';
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Eliminazione...';

    try {
      const formData = new FormData();
      formData.append('author_id', authorId);

      const res = await fetch('elimina_autore.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      });

      // Se la risposta non è JSON, gestiamo l'errore
      const contentType = res.headers.get('content-type') || '';
      if (!contentType.includes('application/json')) {
        const text = await res.text();
        console.error('Risposta non JSON:', text);
        alert('Errore server: risposta non valida.');
        return;
      }

      const data = await res.json();

      if (data.success) {
        alert(data.message || 'Autore eliminato con successo');
        // Rimuovi la riga dalla tabella
        const row = btn.closest('tr');
        if (row) row.remove();
      } else {
        alert(data.message || 'Errore durante l\'eliminazione');
        btn.disabled = false;
        btn.textContent = originalText;
      }
    } catch (err) {
      console.error('Errore fetch elimina autore:', err);
      alert('Errore di connessione.');
      btn.disabled = false;
      btn.textContent = originalText;
    } finally {
      btn.dataset.sending = '0';
    }
  });
  
   // ============================
// ELIMINA CATEGORIA  (ADMIN) 
// ============================
  

  

  document.body.addEventListener('click', async function (e) {
    const btn = e.target.closest('.btn-delete-category');
    if (!btn) return;

    e.preventDefault();

    const categoryId = btn.dataset.categoryId;
    if (!categoryId) {
      alert('ID categoria mancante');
      return;
    }

    if (!confirm('Sei sicuro di voler eliminare questa categoria e tutti i libri associati?')) return;

    // Protezione doppio click
    if (btn.dataset.sending === '1') return;
    btn.dataset.sending = '1';
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Eliminazione...';

    try {
      const formData = new FormData();
      formData.append('category_id', categoryId);

      const res = await fetch('elimina_categoria.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      });

      // Se la risposta non è JSON, gestiamo l'errore
      const contentType = res.headers.get('content-type') || '';
      if (!contentType.includes('application/json')) {
        const text = await res.text();
        console.error('Risposta non JSON:', text);
        alert('Errore server: risposta non valida.');
        return;
      }

      const data = await res.json();

      if (data.success) {
        alert(data.message || 'Categoria eliminata con successo');
        // Rimuovi la riga dalla tabella
        const row = btn.closest('tr');
        if (row) row.remove();
      } else {
        alert(data.message || 'Errore durante l\'eliminazione');
        btn.disabled = false;
        btn.textContent = originalText;
      }
    } catch (err) {
      console.error('Errore fetch elimina categorie:', err);
      alert('Errore di connessione.');
      btn.disabled = false;
      btn.textContent = originalText;
    } finally {
      btn.dataset.sending = '0';
    }
  });

   // ============================
// ELIMINA ORDINE  (ADMIN) 
// ============================
  

  

  document.body.addEventListener('click', async function (e) {
    const btn = e.target.closest('.btn-delete-order');
    if (!btn) return;

    e.preventDefault();

    const orderId = btn.dataset.orderId;
    if (!orderId) {
      alert('ID ordine mancante');
      return;
    }

    if (!confirm('Sei sicuro di voler eliminare questo ordine?')) return;

    // Protezione doppio click
    if (btn.dataset.sending === '1') return;
    btn.dataset.sending = '1';
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Eliminazione...';

    try {
      const formData = new FormData();
      formData.append('order_id', orderId);

      const res = await fetch('elimina_ordine.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      });

      // Se la risposta non è JSON, gestiamo l'errore
      const contentType = res.headers.get('content-type') || '';
      if (!contentType.includes('application/json')) {
        const text = await res.text();
        console.error('Risposta non JSON:', text);
        alert('Errore server: risposta non valida.');
        return;
      }

      const data = await res.json();

      if (data.success) {
        alert(data.message || 'Ordine eliminato con successo');
        // Rimuovi la riga dalla tabella
        const row = btn.closest('tr');
        if (row) row.remove();
      } else {
        alert(data.message || 'Errore durante l\'eliminazione');
        btn.disabled = false;
        btn.textContent = originalText;
      }
    } catch (err) {
      console.error('Errore fetch elimina ordini:', err);
      alert('Errore di connessione.');
      btn.disabled = false;
      btn.textContent = originalText;
    } finally {
      btn.dataset.sending = '0';
    }
  });

 // ============================
// ELIMINA UTENTE (ADMIN) 
// ============================


  document.body.addEventListener('click', async function (e) {
    const btn = e.target.closest('.btn-delete-user');
    if (!btn) return;

    e.preventDefault();

    const userId = btn.dataset.userId;
    if (!userId) {
      alert('ID utente mancante');
      return;
    }

    if (!confirm('Sei sicuro di voler eliminare questo utente?')) return;

    // Protezione doppio click
    if (btn.dataset.sending === '1') return;
    btn.dataset.sending = '1';
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Eliminazione...';

    try {
      const formData = new FormData();
      formData.append('user_id', userId);

      const res = await fetch('elimina_utente.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      });

      // Se la risposta non è JSON, gestiamo l'errore
      const contentType = res.headers.get('content-type') || '';
      if (!contentType.includes('application/json')) {
        const text = await res.text();
        console.error('Risposta non JSON:', text);
        alert('Errore server: risposta non valida.');
        return;
      }

      const data = await res.json();

      if (data.success) {
        alert(data.message || 'Utente eliminato con successo');
        // Rimuovi la riga dalla tabella
        const row = btn.closest('tr');
        if (row) row.remove();
      } else {
        alert(data.message || 'Errore durante l\'eliminazione');
        btn.disabled = false;
        btn.textContent = originalText;
      }
    } catch (err) {
      console.error('Errore fetch elimina utentes:', err);
      alert('Errore di connessione.');
      btn.disabled = false;
      btn.textContent = originalText;
    } finally {
      btn.dataset.sending = '0';
    }
  });


// ============================
// ELIMINA LIBRO (ADMIN) 
// ============================

document.addEventListener("DOMContentLoaded", function () {

    // ELIMINAZIONE LIBRO AJAX
    document.querySelectorAll(".delete-book").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();

            if (!confirm("Sei sicura di voler eliminare questo libro?")) return;

            const bookId = this.dataset.id;
            const row = this.closest("tr");

            fetch("php/delete.php", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "book_id=" + bookId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    row.style.backgroundColor = "#ffdddd";
                    setTimeout(() => row.remove(), 300);
                } else {
                    alert("Errore: " + data.message);
                }
            })
            .catch(err => alert("Errore di rete"));
        });
    });

});

    // ============================
    // RECENSIONE
    // ============================
    const reviewForm = document.getElementById('reviewForm');

    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const msg = document.getElementById('review-message');
            const submitBtn = reviewForm.querySelector('button[type="submit"]');

            if (msg) {
                msg.className = '';
                msg.textContent = '';
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Invio...';
            }

            fetch('submit_review.php', {
                method: 'POST',
                body: new FormData(reviewForm),
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (msg) {
                    msg.className = data.success ? 'success' : 'error';
                    msg.textContent = data.message || (data.success ? 'Recensione inviata con successo.' : 'Errore nell\'invio della recensione.');
                }

                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(function (err) {
                if (msg) {
                    msg.className = 'error';
                    msg.textContent = 'Errore di rete durante l\'invio.';
                }

                console.error('Errore submit review:', err);
            })
            .finally(function () {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Invia Recensione';
                }
            });
        });
    }

    // ============================
    // CAMBIO PASSWORD - STEP 1
    // ============================
    const passwordFormStep1 = document.getElementById('form-step1');

    if (passwordFormStep1) {
        passwordFormStep1.addEventListener('submit', function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const newPassword = document.getElementById('new-password').value;
            const submitBtn = this.querySelector('button[type="submit"]');

            if (!email || !newPassword) {
                showPasswordStatus('Compila tutti i campi', 'error');
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
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (data.success) {
                    showPasswordStatus(data.message || 'Email verificata.', 'success');
                    document.getElementById('step1').style.display = 'none';
                    document.getElementById('step2').style.display = 'block';
                    document.getElementById('old-password').focus();
                } else {
                    showPasswordStatus(data.message || 'Errore durante la verifica', 'error');
                }
            })
            .catch(function (err) {
                console.error('Errore cambio password:', err);
                showPasswordStatus('Errore di connessione. Riprova.', 'error');
            })
            .finally(function () {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Recupera Password';
            });
        });
    }

    // ============================
    // CAMBIO PASSWORD - STEP 2
    // ============================
    const passwordFormStep2 = document.getElementById('form-step2');

    if (passwordFormStep2) {
        passwordFormStep2.addEventListener('submit', function (e) {
            e.preventDefault();

            const oldPassword = document.getElementById('old-password').value;
            const submitBtn = this.querySelector('button[type="submit"]');

            if (!oldPassword) {
                showPasswordStatus('Inserisci la vecchia password', 'error');
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
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (data.success) {
                    showPasswordStatus(data.message || 'Password aggiornata.', 'success');

                    setTimeout(function () {
                        window.location.href = data.redirect || 'login.php';
                    }, 2000);
                } else {
                    showPasswordStatus(data.message || 'Errore durante l\'aggiornamento', 'error');
                }
            })
            .catch(function (err) {
                console.error('Errore cambio password:', err);
                showPasswordStatus('Errore di connessione. Riprova.', 'error');
            })
            .finally(function () {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Conferma Cambio Password';
            });
        });
    }

    // ============================
    // PROFILE FORM
    // ============================
    const profileSection = document.querySelector('#profile');

    if (profileSection) {
        const profileForm = profileSection.querySelector('form');

        if (profileForm) {
            profileForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const submitBtn = this.querySelector('button[type="submit"]');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Salvataggio...';
                }

                const payload = new URLSearchParams();
                payload.append('name', this.querySelector('#name') ? this.querySelector('#name').value.trim() : '');
                payload.append('email', this.querySelector('#email') ? this.querySelector('#email').value.trim() : '');
                payload.append('phone', this.querySelector('#phone') ? this.querySelector('#phone').value.trim() : '');

                fetch('update_profile.php', {
                    method: 'POST',
                    body: payload,
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(function (res) {
                    return res.json();
                })
                .then(function (data) {
                    alert(data.message || (data.success ? 'Profilo aggiornato.' : 'Errore durante il salvataggio.'));
                })
                .catch(function (err) {
                    console.error('Errore update profile:', err);
                    alert('Errore di connessione. Riprova.');
                })
                .finally(function () {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Salva Modifiche';
                    }
                });
            });
        }
    }

    // ============================
    // PAYMENT METHOD / SHIPPING FORM
    // ============================
    const globalPaymentForm = document.getElementById('paymentForm');

    if (globalPaymentForm) {
        globalPaymentForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.textContent : 'Modifica';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Invio...';
            }

            const isPaymentEditPage = this.querySelector('#cardNumber');

            if (isPaymentEditPage) {
                fetch('update_payment.php', {
                    method: 'POST',
                    body: new FormData(this),
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function (res) {
                    return res.json();
                })
                .then(function (data) {
                    alert(data.message || (data.success ? 'Metodo di pagamento aggiornato.' : 'Errore durante il salvataggio.'));
                })
                .catch(function (err) {
                    console.error('Errore modifica payment:', err);
                    alert('Errore di connessione. Riprova.');
                })
                .finally(function () {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                });

                return;
            }

            const payload = new URLSearchParams();
            payload.append('cityName', this.querySelector('#cityName') ? this.querySelector('#cityName').value.trim() : '');
            payload.append('provincia', this.querySelector('#provincia') ? this.querySelector('#provincia').value.trim() : '');
            payload.append('cap', this.querySelector('#cap') ? this.querySelector('#cap').value.trim() : '');
            payload.append('via', this.querySelector('#via') ? this.querySelector('#via').value.trim() : '');

            fetch('update_shipping.php', {
                method: 'POST',
                body: payload,
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                alert(data.message || (data.success ? 'Indirizzo aggiornato.' : 'Errore durante il salvataggio.'));
            })
            .catch(function (err) {
                console.error('Errore update shipping:', err);
                alert('Errore di connessione. Riprova.');
            })
            .finally(function () {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            });
        });
    }

    // ============================
    // FUNZIONI DI SUPPORTO
    // ============================
    function updateCartTotals(delta) {
        const subtotalEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');

        if (!subtotalEl || !totalEl) return;

        function parseMoney(el) {
            return parseFloat(el.textContent.replace(/[^0-9.,]/g, '').replace(',', '.')) || 0;
        }

        const subtotal = +(parseMoney(subtotalEl) + delta).toFixed(2);
        const total = +(parseMoney(totalEl) + delta).toFixed(2);

        subtotalEl.textContent = '\u20ac ' + subtotal.toFixed(2);
        totalEl.textContent = '\u20ac ' + total.toFixed(2);
    }

    function updateHeaderCartCount(count) {
        const el = document.getElementById('cart-count');

        if (el) {
            el.textContent = '(' + parseInt(count, 10) + ')';
        }
    }

    function showRegistrationStatus(message, type) {
        const statusEl = document.getElementById('status-message');

        if (statusEl) {
            statusEl.textContent = message;
            statusEl.className = type || 'info';
            statusEl.style.display = 'block';
            window.scrollTo(0, 0);
        }
    }

    function showPasswordStatus(message, type) {
        const statusEl = document.getElementById('status-message');

        if (statusEl) {
            statusEl.textContent = message;
            statusEl.className = type || 'info';
            statusEl.style.display = 'block';
            window.scrollTo(0, 0);
        }
    }

    function resetPasswordForm() {
        const form1 = document.getElementById('form-step1');
        const form2 = document.getElementById('form-step2');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const statusEl = document.getElementById('status-message');

        if (form1) form1.reset();
        if (form2) form2.reset();
        if (step1) step1.style.display = 'block';
        if (step2) step2.style.display = 'none';
        if (statusEl) statusEl.style.display = 'none';
    }

    window.resetPasswordForm = resetPasswordForm;
});