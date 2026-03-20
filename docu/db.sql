USE BOOKNOVA;



-- Ruoli e Metodi
INSERT INTO RUOLO (RUOLO) VALUES ('Admin'), ('Cliente');
INSERT INTO METODO_PAGAMENTO (NOME) VALUES ('Carta di Credito'), ('PayPal'), ('Contanti');
INSERT INTO METODO_SPEDIZIONE (nome, costo) VALUES ('Standard', 5.00), ('Express', 10.00);

-- Info Libri
INSERT INTO AUTORE (NOME, BIOGRAFIA) VALUES ('J.R.R. Tolkien', 'Scrittore e filologo britannico.');
INSERT INTO EDITORE (NOME) VALUES ('Bompiani'), ('Mondadori');
INSERT INTO CATEGORIA (NOME) VALUES ('Fantasy'), ('Classici'), ('Thriller');
INSERT INTO FORMATO (FORMATO) VALUES ('Cartaceo'), ('E-book');
INSERT INTO CONDIZIONE (DESCRIZIONE) VALUES ('Nuovo'), ('Usato come nuovo');

-- Inserimento libri
INSERT INTO LIBRO (TITOLO, PREZZO, DESCRIZIONE, IMMAGINE, ID_AUTORE, ID_EDITORE, ID_CATEGORIA, ID_FORMATO, ID_CONDIZIONE) 
VALUES 
('Balaclava', 35.00, 'Sotto la maschera', 'booknova/static/img/product8.jpeg', 1, 1, 1, 1, 1),
('Ti ritroverò Adeline', 15.00, 'L\'avventura di Bilbo.', 1, 2, 1, 1, 1),
('Harry Potter', 16.90, 'E il calice di fuoco.', 1, 2, 1, 1, 1)