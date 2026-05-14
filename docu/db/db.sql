USE BOOKNOVA;

INSERT INTO RUOLO (RUOLO) VALUES ('Admin'), ('Cliente');
INSERT INTO UTENTE (NOME, COGNOME, EMAIL, PASSWORD, ID_RUOLO)
VALUES ('Mario', 'Rossi', 'mr@gmai.it', '2a92b3cc0e62ab5b07847f715da224f8b0bce2a0b3f5492f2deec6077dca258a', 2);
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

INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO) VALUES (1,1);
INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO) VALUES (1,2);