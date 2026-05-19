USE BOOKNOVA;

INSERT INTO RUOLO (RUOLO) VALUES ('Admin'), ('Cliente');
INSERT INTO UTENTE (NOME, COGNOME, EMAIL, PASSWORD, ID_RUOLO, INDIRIZZO)
VALUES ('Mario', 'Rossi', 'mr@gmai.it', '6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b', 2, 'via_dio_22'),
		('Luca', 'Rossi', 'l@xx.com', '6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b', 1, 'via_gesu_89'),
        ('Giada', 'Verdi', 'g@xx.com', '6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b', 2, 'via_u_9');
 -- Inserimento Categorie
INSERT INTO CATEGORIA (NOME) VALUES ('Romanzo rosa'), ('Dark romance'), ('Office romance');

-- Inserimento Autori
INSERT INTO AUTORE (NOME, BIOGRAFIA) VALUES
('Martha T. Mille', 'Scrittrice italiana contemporanea nota per romanzi romance e new adult dalle tematiche emotive.'),
('Felicia Kingsley', 'Autrice italiana di romanzi romance contemporanei, famosa per il suo stile ironico e coinvolgente.'),
('H. D. Carlton', 'Scrittrice statunitense specializzata nel genere dark romance e psychological romance.'),
('Catelyn Wilson', 'Autrice emergente conosciuta sulle piattaforme digitali per storie romance e young adult.'),
('Nicole Teso', 'Scrittrice italiana apprezzata per romanzi romance contemporanei e personaggi intensi.'),
('Penelope Douglas', 'Autrice americana famosa per romanzi romance e dark romance dal tono passionale.'),
('Fannie Heather', 'Autrice digitale che pubblica prevalentemente storie romance e teen fiction online.'),
('Arya Beker', 'Scrittrice contemporanea di romanzi romance diffusi soprattutto tramite piattaforme digitali.'),
('Alisia Lupiani', 'Giovane autrice italiana che si dedica alla narrativa romance e new adult.'),
('Joana M.', 'Autrice online conosciuta per storie sentimentali rivolte a un pubblico giovane.'),
('Doire Oak', 'Autrice contemporanea che pubblica racconti e romanzi su piattaforme digitali.'),
('Esme Pavard', 'Autrice emergente legata alla narrativa romance e alle pubblicazioni online.'),
('Aura B. Lyn', 'Scrittrice di romanzi romance caratterizzati da relazioni intense e atmosfere emotive.'),
('Tricel Blossom', 'Autrice Wattpad conosciuta per storie romance e young adult.'),
('Astyls', 'Scrittrice emergente attiva sulle piattaforme di narrativa online.'),
('Elaya Lin', 'Autrice contemporanea che pubblica opere romance rivolte soprattutto a giovani lettori.');

-- Inserimento Editori
INSERT INTO EDITORE (NOME) VALUES
('Sperling & Kupfer'),
('Newton Compton Editori'),
('Salani'),
('Mondadori'),
('Rizzoli'),
('Garzanti'),
('HarperCollins Italia'),
('Always Publishing'),
('Magazzini Salani'),
('Self Publishing'),
('Wattpad'),
('Amazon KDP');

-- Inserimento Formati
INSERT INTO FORMATO (FORMATO) VALUES ('Cartaceo'), ('Ebook');

-- Inserimento Condizioni
INSERT INTO CONDIZIONE (DESCRIZIONE) VALUES ('Nuovo'), ('Ottimo stato');
-- CATEGORIA: Dark romance (ID_CATEGORIA = 2)
INSERT INTO LIBRO (TITOLO, PREZZO, DESCRIZIONE, ID_AUTORE, ID_EDITORE, ID_CATEGORIA, ID_FORMATO, ID_CONDIZIONE, PAGINE, ANNO_PUBBLICAZIONE, IMMAGINE) VALUES
('Balaclava', 12.90, 'Dark romance intenso e psicologico.', 1, 1, 2, 1, 1, 320, 2019, 'product8.jpeg'),
('Ti ritroverò Adeline', 14.90, 'Storia dark romance di ossessione e destino.', 3, 2, 2, 1, 1, 280, 2020, 'product10.png'),
('All the devils', 13.90, 'Dark romance con toni oscuri e tormentati.', 4, 3, 2, 2, 1, 300, 2021, 'product11.png'),
('Corrupt', 15.50, 'Dark romance tra attrazione e pericolo.', 6, 6, 2, 1, 1, 260, 2022, 'product13.png'),
('Violent Life', 11.90, 'Storia intensa e violenta dai toni dark.', 7, 8, 2, 2, 2, 350, 2023, 'product15.png'),
('Apocalypse', 14.20, 'Romance distopico e oscuro.', 14, 2, 2, 1, 1, 300, 2023, 'product6.jpeg'),
('Poisoned Love', 12.50, 'Amore tossico e pericoloso.', 16, 4, 2, 2, 1, 280, 2023, 'product1.jpeg'),
('Dimmi chi sei', 9.90, 'Storia d’amore e identità nel mondo del lavoro.', 5, 5, 2, 2, 2, 240, 2021, 'product12.png');


-- CATEGORIA: Romanzo rosa (ID_CATEGORIA = 1)
INSERT INTO LIBRO (TITOLO, PREZZO, DESCRIZIONE, ID_AUTORE, ID_EDITORE, ID_CATEGORIA, ID_FORMATO, ID_CONDIZIONE, PAGINE, ANNO_PUBBLICAZIONE, IMMAGINE) VALUES
('Scandalo a Hollywood', 12.90, 'Romance tra fama e scandali.', 2, 7, 1, 1, 1, 280, 2022, 'product14.png'),
('Cardigan', 10.50, 'Romance emotivo e introspettivo.', 10, 11, 1, 2, 2, 240, 2023, 'seconda.jpeg'),
('Cercami dove finisce il rumore', 12.00, 'Storia d’amore poetica e intensa.', 11, 12, 1, 1, 1, 300, 2021, 'product2.jpeg'),
('Sweat turns sweet', 9.90, 'Romance giovane e passionale.', 12, 1, 1, 2, 2, 260, 2019, 'product4.jpeg'),
('Opposite', 10.90, 'Gli opposti che si attraggono.', 15, 3, 1, 1, 1, 280, 2022, 'product7.jpeg'),
('Matrimonio a Beverly Hills', 14.90, 'Romance ambientato nel mondo del lusso e del matrimonio.', 13, 5, 1, 1, 1, 320, 2023, 'product5.jpeg');


-- CATEGORIA: Office romance (ID_CATEGORIA = 3)
INSERT INTO LIBRO (TITOLO, PREZZO, DESCRIZIONE, ID_AUTORE, ID_EDITORE, ID_CATEGORIA, ID_FORMATO, ID_CONDIZIONE, PAGINE, ANNO_PUBBLICAZIONE, IMMAGINE) VALUES
('Ti aspetto a Central Park', 10.90, 'Romance ambientato a New York.', 2, 4, 3, 1, 1, 280, 2023, 'product9.png'),
('Lilium', 11.50, 'Romanzo romantico ambientato in un contesto lavorativo emotivo.', 8, 9, 3, 2, 1, 300, 2023, 'product3.jpeg'),
('Due cuori, un’anima', 13.90, 'Storia d’amore intensa nata in ambiente professionale.', 9, 10, 3, 1, 1, 260, 2023, 'main-banner2.jpg');

INSERT INTO METODO_PAGAMENTO (NOME) VALUES ('Carta di Credito');
INSERT INTO METODO_PAGAMENTO (NOME) VALUES ('PayPal');
INSERT INTO METODO_PAGAMENTO (NOME) VALUES ('Bonifico Bancario');
INSERT INTO METODO_PAGAMENTO (NOME) VALUES ('Contrassegno');
INSERT INTO METODO_SPEDIZIONE (nome, costo) VALUES ('Posta Pieghi di Libri', 1.28);
INSERT INTO METODO_SPEDIZIONE (nome, costo) VALUES ('Corriere Espresso (SDA/BRT)', 5.90);
INSERT INTO METODO_SPEDIZIONE (nome, costo) VALUES ('Spedizione Assicurata', 9.50);
INSERT INTO METODO_SPEDIZIONE (nome, costo) VALUES ('Ritiro in Sede', 0.00);


-- Ordine per l'utente 1, pagato con metodo 1, spedito con metodo 2
INSERT INTO ORDINE (ID_UTENTE, ID_METODO_PAGAMENTO, ID_METODO_SPEDIZIONE, DATA_ORDINE, TOTALE)
VALUES (2, 1, 2, '2026-05-10', 45.50);

-- Ordine per l'utente 2, pagato con metodo 2, spedito con metodo 1
INSERT INTO ORDINE (ID_UTENTE, ID_METODO_PAGAMENTO, ID_METODO_SPEDIZIONE, DATA_ORDINE, TOTALE)
VALUES (2, 2, 1, '2026-05-12', 120.00);

-- Ordine recente (oggi) per l'utente 3
INSERT INTO ORDINE (ID_UTENTE, ID_METODO_PAGAMENTO, ID_METODO_SPEDIZIONE, DATA_ORDINE, TOTALE)
VALUES (2, 1, 1, CURDATE(), 89.99);

-- Ordine di valore elevato
INSERT INTO ORDINE (ID_UTENTE, ID_METODO_PAGAMENTO, ID_METODO_SPEDIZIONE, DATA_ORDINE, TOTALE)
VALUES (2, 2, 2, '2026-04-20', 540.00);

-- Recensione dell'utente 2 per il libro 5
INSERT INTO RECENSIONE (ID_UTENTE, ID_LIBRO, TESTO, DATA) 
VALUES (2, 5, 'Un libro davvero avvincente, la trama mi ha tenuto col fiato sospeso fino alla fine!', '2026-05-01');

-- Recensione dell'utente 2 per il libro 1
INSERT INTO RECENSIONE (ID_UTENTE, ID_LIBRO, TESTO, DATA) 
VALUES (2, 1, 'Bella storia, ma la parte centrale è un po lenta. Comunque consigliato.', '2026-05-03');

-- Recensione dell'utente 2 per il libro 7
INSERT INTO RECENSIONE (ID_UTENTE, ID_LIBRO, TESTO, DATA) 
VALUES (2, 7, 'Non mi è piaciuto molto, mi aspettavo qualcosa di più profondo.', '2026-05-10');

-- Recensione recente dell'utente 2 per il libro 10
INSERT INTO RECENSIONE (ID_UTENTE, ID_LIBRO, TESTO, DATA) 
VALUES (2, 10, 'Capolavoro assoluto! Uno dei migliori libri che io abbia mai letto.', CURDATE());

INSERT INTO RECENSIONE (ID_UTENTE, ID_LIBRO, TESTO, DATA) VALUES
(1, 1, 'Un inizio di saga incredibile, non riuscivo a smettere di leggere.', '2026-01-15'),
(3, 1, 'Ben scritto, anche se alcune parti sono un po descrittive.', '2026-02-10'),
(3, 2, 'Il mio preferito di questa collezione. Consigliatissimo!', '2026-03-05'),
(1, 3, 'Trama originale e personaggi ben strutturati.', '2026-01-20'),
(3, 4, 'Un classico intramontabile, ogni biblioteca dovrebbe averlo.', '2026-04-12'),
(1, 5, 'Non mi ha convinto del tutto, ma lo stile è ottimo.', '2026-02-28'),
(3, 6, 'Emozionante e commovente. Mi ha fatto riflettere molto.', '2026-05-01'),
(1, 7, 'Ideale per una lettura leggera sotto l ombrellone.', '2026-05-14'),
(3, 8, 'Un po scontato nel finale, ma comunque piacevole.', '2026-03-22'),
(1, 9, 'Una sorpresa inaspettata! Autore da tenere d occhio.', '2026-04-05'),
(3, 10, 'Ottima edizione, carta di qualità e traduzione curata.', '2026-01-30'),
(2, 11, 'Un thriller psicologico che ti lascia senza fiato.', '2026-05-10'),
(3, 11, 'Angosciante al punto giusto, davvero un bel libro.', '2026-05-12'),
(1, 12, 'La copertina è bellissima e il contenuto non è da meno.', '2026-04-18'),
(3, 13, 'Un saggio illuminante su temi molto attuali.', '2026-02-15'),
(1, 14, 'Divertente e ironico, ho riso dall inizio alla fine.', '2026-03-10'),
(3, 15, 'Un po pesante la prima parte, ma poi decolla.', '2026-04-25'),
(1, 16, 'Le illustrazioni all interno sono meravigliose.', '2026-05-05'),
(3, 17, 'Finale mozzafiato. Aspetto con ansia il seguito!', CURDATE());

INSERT INTO OFFERTA (ID, VALORE, DATA_INIZIO, DATA_FINE) VALUES 
(1, 10.00, '2026-05-01', '2026-05-31'), -- Sconto 10%
(2, 20.00, '2026-05-01', '2026-05-31'), -- Sconto 20%
(3, 50.00, '2026-01-01', '2026-12-31'); -- Sconto 50%

INSERT INTO LIBRO_OFFERTA (ID_LIBRO, ID_OFFERTA) VALUES 
(1, 1),  -- Al libro 1 diamo l'offerta 1 (10%)
(3, 1),  -- Al libro 3 diamo l'offerta 1 (10%)
(7, 2),  -- Al libro 7 diamo l'offerta 2 (20%)
(12, 2), -- Al libro 12 diamo l'offerta 2 (20%)
(17, 3); -- Al libro 17 diamo l'offerta 3 (50%)

-- Collegamento Libri all'Ordine #1 (Totale Ordine: 45.50)
INSERT INTO ORDINE_LIBRO (ID_ORDINE, ID_LIBRO, QUANTITA, PREZZO_UNITARIO) VALUES 
(1, 1, 1, 12.90), -- Balaclava
(1, 2, 1, 14.90), -- Ti ritroverò Adeline
(1, 15, 1, 11.50); -- Lilium
-- Nota: 12.90 + 14.90 + 11.50 + 5.90 (Spedizione Corriere Espresso) = 45.20 (approssimato a 45.50)

-- Collegamento Libri all'Ordine #2 (Totale Ordine: 120.00)
INSERT INTO ORDINE_LIBRO (ID_ORDINE, ID_LIBRO, QUANTITA, PREZZO_UNITARIO) VALUES 
(2, 4, 3, 15.50), -- Corrupt (3 copie = 46.50)
(2, 9, 2, 12.90), -- Scandalo a Hollywood (2 copie = 25.80)
(2, 14, 3, 14.90); -- Matrimonio a Beverly Hills (3 copie = 44.70)
-- Nota: 46.50 + 25.80 + 44.70 + 1.28 (Spedizione Pieghi di Libri) = ~118.28 (approssimato a 120.00)

-- Collegamento Libri all'Ordine #3 (Totale Ordine: 89.99)
INSERT INTO ORDINE_LIBRO (ID_ORDINE, ID_LIBRO, QUANTITA, PREZZO_UNITARIO) VALUES 
(3, 3, 2, 13.90), -- All the devils (2 copie = 27.80)
(3, 6, 2, 14.20), -- Apocalypse (2 copie = 28.40)
(3, 13, 3, 10.90); -- Opposite (3 copie = 32.70)
-- Nota: 27.80 + 28.40 + 32.70 + 1.28 (Spedizione Pieghi di Libri) = ~90.18 (approssimato a 89.99)

-- Collegamento Libri all'Ordine #4 (Totale Ordine: 540.00 - Valore Elevato)
INSERT INTO ORDINE_LIBRO (ID_ORDINE, ID_LIBRO, QUANTITA, PREZZO_UNITARIO) VALUES 
(4, 5, 15, 11.90), -- Violent Life (15 copie = 178.50)
(4, 11, 15, 12.00), -- Cercami dove finisce il rumore (15 copie = 180.00)
(4, 17, 12, 13.90); -- Due cuori, un’anima (12 copie = 166.80)
-- Nota: 178.50 + 180.00 + 166.80 + 5.90 (Spedizione Corriere Espresso) = ~531.20 (approssimato a 540.00)

INSERT INTO RECENSIONE (ID_UTENTE, ID_LIBRO, TESTO, DATA) VALUES
-- Libro 1
(2, 1, 'Un inizio di saga pazzesco, i personaggi sono scritti benissimo.', '2026-01-10'),
(3, 1, 'Carino, ma secondo me un po troppo sopravvalutato.', '2026-01-12'),

-- Libro 2
(3, 2, 'Il secondo volume non delude le aspettative, ritmo incalzante!', '2026-01-25'),

-- Libro 3
(2, 3, 'Non mi ha convinto per niente. La trama è lenta e noiosa.', '2026-02-02'),
(3, 3, 'Concordo con la critica, si fa fatica ad arrivare alla fine.', '2026-02-05'),

-- Libro 4
(2, 4, 'Un saggio davvero illuminante, apre la mente su molti temi.', '2026-02-14'),

-- Libro 5
(3, 5, 'Il colpo di scena a metà libro mi ha lasciato a bocca aperta.', '2026-02-28'),
(2, 5, 'Buona idea ma l\'esecuzione non mi è piaciuta granché.', '2026-03-01'),

-- Libro 6
(2, 6, 'Un classico intramontabile che tutti dovrebbero leggere.', '2026-03-10'),

-- Libro 7
(3, 7, 'Scrittura fluida ed elegante, si legge in un weekend.', '2026-03-15'),
(2, 7, 'Un thriller psicologico fantastico, ti tiene incollato alle pagine.', '2026-03-18'),

-- Libro 8
(2, 8, 'Deludente. Rispetto ai lavori precedenti dell\'autore è un passo indietro.', '2026-03-22'),

-- Libro 9
(3, 9, 'Una storia d\'amore toccante e mai banale. Ho pianto sul finale.', '2026-04-02'),
(2, 9, 'Un po\' troppo sdolcinato per i miei gusti, ma scritto bene.', '2026-04-05'),

-- Libro 10
(3, 10, 'Ottima l\'ambientazione storica, si vede che c\'è tanta ricerca dietro.', '2026-04-12'),

-- Libro 11
(2, 11, 'Consigliato a chi cerca una lettura leggera e disimpegnata.', '2026-04-18'),
(3, 11, 'Perfetto da leggere sotto l\'ombrellone, divertente e fresco.', '2026-04-20'),

-- Libro 12
(3, 12, 'I dialoghi sono un po\' forzati, non mi è sembrato molto naturale.', '2026-04-27'),

-- Libro 13
(2, 13, 'Un capolavoro della fantascienza moderna, assolutamente consigliato.', '2026-05-01'),
(3, 13, 'Worldbuilding incredibile, non vedo l\'ora che esca il seguito.', '2026-05-03'),

-- Libro 14
(2, 14, 'Poetico e malinconico. Uno di quei libri che ti restano dentro.', '2026-05-08'),

-- Libro 15
(3, 15, 'Finale troppo sbrigativo, sembra che l\'autore avesse fretta di chiudere.', '2026-05-12'),
(2, 15, 'A me il finale è piaciuto, lascia spazio all\'immaginazione.', '2026-05-14'),

-- Libro 16
(2, 16, 'Biografia scritta magistralmente, sembra quasi un romanzo.', '2026-05-17'),

-- Libro 17
(3, 17, 'Un ottimo giallo, fino all\'ultimo non avevo capito chi fosse il colpevole.', '2026-05-19');







-- ========================================================
-- POPOLAMENTO COMPLETO TABELLA VOTO
-- (4 voti per ogni libro: 2 dall'utente 2 e 2 dall'utente 3)
-- ========================================================

INSERT INTO VOTO (ID_UTENTE, ID_LIBRO, VALORE, DATA) VALUES
-- Libro 1
(2, 1, 5, '2026-05-01'), (2, 1, 4, '2026-05-02'), (3, 1, 4, '2026-05-02'), (3, 1, 3, '2026-05-03'),
-- Libro 2
(2, 2, 4, '2026-05-02'), (2, 2, 5, '2026-05-03'), (3, 2, 3, '2026-05-03'), (3, 2, 4, '2026-05-04'),
-- Libro 3
(2, 3, 3, '2026-05-03'), (2, 3, 4, '2026-05-04'), (3, 3, 5, '2026-05-04'), (3, 3, 5, '2026-05-05'),
-- Libro 4
(2, 4, 5, '2026-05-04'), (2, 4, 5, '2026-05-05'), (3, 4, 4, '2026-05-05'), (3, 4, 5, '2026-05-06'),
-- Libro 5
(2, 5, 2, '2026-05-05'), (2, 5, 3, '2026-05-06'), (3, 5, 4, '2026-05-06'), (3, 5, 3, '2026-05-07'),
-- Libro 6
(2, 6, 4, '2026-05-06'), (2, 6, 4, '2026-05-07'), (3, 6, 4, '2026-05-07'), (3, 6, 5, '2026-05-08'),
-- Libro 7
(2, 7, 5, '2026-05-07'), (2, 7, 4, '2026-05-08'), (3, 7, 3, '2026-05-08'), (3, 7, 4, '2026-05-09'),
-- Libro 8
(2, 8, 3, '2026-05-08'), (2, 8, 2, '2026-05-09'), (3, 8, 2, '2026-05-09'), (3, 8, 3, '2026-05-10'),
-- Libro 9
(2, 9, 4, '2026-05-09'), (2, 9, 5, '2026-05-10'), (3, 9, 5, '2026-05-10'), (3, 9, 4, '2026-05-11'),
-- Libro 10
(2, 10, 5, '2026-05-10'), (2, 10, 5, '2026-05-11'), (3, 10, 4, '2026-05-11'), (3, 10, 4, '2026-05-12'),
-- Libro 11
(2, 11, 4, '2026-05-11'), (2, 11, 3, '2026-05-12'), (3, 11, 4, '2026-05-12'), (3, 11, 5, '2026-05-13'),
-- Libro 12
(2, 12, 3, '2026-05-12'), (2, 12, 3, '2026-05-13'), (3, 12, 3, '2026-05-13'), (3, 12, 2, '2026-05-14'),
-- Libro 13
(2, 13, 5, '2026-05-13'), (2, 13, 4, '2026-05-14'), (3, 13, 5, '2026-05-14'), (3, 13, 5, '2026-05-15'),
-- Libro 14
(2, 14, 4, '2026-05-14'), (2, 14, 3, '2026-05-15'), (3, 14, 2, '2026-05-15'), (3, 14, 3, '2026-05-16'),
-- Libro 15
(2, 15, 2, '2026-05-15'), (2, 15, 2, '2026-05-16'), (3, 15, 3, '2026-05-16'), (3, 15, 4, '2026-05-17'),
-- Libro 16
(2, 16, 5, '2026-05-16'), (2, 16, 5, '2026-05-17'), (3, 16, 4, '2026-05-17'), (3, 16, 5, '2026-05-18'),
-- Libro 17
(2, 17, 4, '2026-05-17'), (2, 17, 5, '2026-05-18'), (3, 17, 5, '2026-05-18'), (3, 17, 4, '2026-05-19');
-- Wishlist per l'Utente 1 (Ha inserito 4 libri)
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-10', 1, 3);
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-12', 1, 7);
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-15', 1, 12);
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-18', 1, 15);

-- Wishlist per l'Utente 2 (Ha inserito 5 libri)
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-04-20', 2, 1);
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-04-22', 2, 5);
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-01', 2, 9);
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-04', 2, 14);
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-19', 2, 17);

-- Wishlist per l'Utente 3 (Ha inserito 3 libri)
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-02', 3, 2);
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-03', 3, 6);
INSERT INTO WISHLIST (DATA_INSERIMENTO, ID_UTENTE, ID_LIBRO) VALUES ('2026-05-14', 3, 11);

-- Carrello dell'Utente 1
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (1, 4, 1);
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (1, 8, 2); -- Testa la quantità multipla (2 copie)
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (1, 15, 1);

-- Carrello dell'Utente 2
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (2, 3, 1);
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (2, 7, 3); -- Testa la quantità multipla (3 copie)
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (2, 12, 1);
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (2, 16, 1);

-- Carrello dell'Utente 3
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (3, 2, 2); -- Testa la quantità multipla (2 copie)
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO, QUANTITA) VALUES (3, 10, 1);
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO) VALUES (1,1);
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO) VALUES (1,2);