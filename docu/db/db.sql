USE BOOKNOVA;

INSERT INTO RUOLO (RUOLO) VALUES ('Admin'), ('Cliente');
INSERT INTO UTENTE (NOME, COGNOME, EMAIL, PASSWORD, ID_RUOLO)
VALUES ('Mario', 'Rossi', 'mr@gmai.it', '2a92b3cc0e62ab5b07847f715da224f8b0bce2a0b3f5492f2deec6077dca258a', 2);
 -- Inserimento Categorie
INSERT INTO CATEGORIA (NOME) VALUES ('Romanzo'), ('Saggistica'), ('Fantascienza');

-- Inserimento Autori
INSERT INTO AUTORE (NOME, BIOGRAFIA) VALUES 
('Alessandro Manzoni', 'Scrittore e poeta italiano del XIX secolo.'),
('Yuval Noah Harari', 'Storico e saggista israeliano contemporaneo.'),
('Isaac Asimov', 'Biochimico e prolifico autore di fantascienza.'),
('Umberto Eco', 'Semiotico e filosofo di fama mondiale.'),
('Frank Herbert', 'Scrittore statunitense autore del ciclo di Dune.');

-- Inserimento Editori
INSERT INTO EDITORE (NOME) VALUES ('Mondadori'), ('Adelphi'), ('Apogeo');

-- Inserimento Formati
INSERT INTO FORMATO (FORMATO) VALUES ('Cartaceo'), ('Ebook');

-- Inserimento Condizioni
INSERT INTO CONDIZIONE (DESCRIZIONE) VALUES ('Nuovo'), ('Ottimo stato');
-- CATEGORIA: Romanzo (ID_CATEGORIA = 1)
INSERT INTO LIBRO (TITOLO, PREZZO, DESCRIZIONE, ID_AUTORE, ID_EDITORE, ID_CATEGORIA, ID_FORMATO, ID_CONDIZIONE) VALUES 
('I Promessi Sposi', 15.50, 'Capolavoro del romanticismo italiano.', 1, 1, 1, 1, 1),
('Il Nome della Rosa', 18.00, 'Giallo storico ambientato in un monastero.', 4, 3, 1, 1, 1),
('Il Pendolo di Foucault', 16.50, 'Un viaggio tra esoterismo e complotti.', 4, 3, 1, 2, 1),
('Fermo e Lucia', 12.00, 'Prima stesura del romanzo manzoniano.', 1, 1, 1, 1, 2),
('Baudolino', 14.00, 'Le avventure di un leggendario mentitore.', 4, 3, 1, 2, 1);

-- CATEGORIA: Saggistica (ID_CATEGORIA = 2)
INSERT INTO LIBRO (TITOLO, PREZZO, DESCRIZIONE, ID_AUTORE, ID_EDITORE, ID_CATEGORIA, ID_FORMATO, ID_CONDIZIONE) VALUES 
('Sapiens: Da animali a dèi', 22.00, 'Breve storia dell''umanità.', 2, 3, 2, 1, 1),
('Homo Deus', 20.00, 'Breve storia del futuro.', 2, 3, 2, 1, 1),
('21 Lezioni per il XXI Secolo', 19.50, 'Sfide globali del presente.', 2, 3, 2, 2, 1),
('Storia della Bellezza', 25.00, 'Saggio sull''estetica curato da Eco.', 4, 3, 2, 1, 1),
('Storia della Bruttezza', 25.00, 'L''altra faccia dell''estetica.', 4, 3, 2, 1, 1);

-- CATEGORIA: Fantascienza (ID_CATEGORIA = 3)
INSERT INTO LIBRO (TITOLO, PREZZO, DESCRIZIONE, ID_AUTORE, ID_EDITORE, ID_CATEGORIA, ID_FORMATO, ID_CONDIZIONE) VALUES 
('Io, Robot', 13.00, 'Le tre leggi della robotica.', 3, 1, 3, 1, 1),
('Fondazione', 15.00, 'Il primo capitolo della saga galattica.', 3, 1, 3, 2, 1),
('Dune', 21.00, 'Il messia di Arrakis.', 5, 2, 3, 1, 1),
('Messia di Dune', 14.50, 'Il seguito del capolavoro di Herbert.', 5, 2, 3, 1, 1),
('La Fine dell''Eternità', 12.50, 'Viaggi nel tempo e paradossi.', 3, 1, 3, 2, 2);

INSERT INTO CARRELLO (ID_UTENTE, ID_LIBRO) VALUES (1,1);