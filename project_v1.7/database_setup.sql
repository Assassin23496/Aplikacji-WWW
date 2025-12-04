/* ============================================================
   BAZA DANYCH
   ============================================================ */

CREATE DATABASE IF NOT EXISTS moja_strona
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_polish_ci;

USE moja_strona;


/* ============================================================
   TABELA: page_list  (CMS – podstrony)
   ============================================================ */

CREATE TABLE IF NOT EXISTS page_list (
                                         id           INT AUTO_INCREMENT PRIMARY KEY,
                                         page_title   VARCHAR(255) NOT NULL,
    page_content TEXT,
    active       TINYINT DEFAULT 1,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );


/* ============================================================
   TABELA: heroes  (bohaterowie Dota 2)
   ============================================================ */

CREATE TABLE IF NOT EXISTS heroes (
                                      id          INT AUTO_INCREMENT PRIMARY KEY,
                                      name        VARCHAR(100) NOT NULL,
    role        ENUM('Carry', 'Support', 'Mid', 'Offlane', 'Jungle') NOT NULL,
    description TEXT,
    image_url   VARCHAR(255),
    abilities   TEXT,
    active      TINYINT DEFAULT 1
    );


/* ============================================================
   TABELA: items  (przedmioty Dota 2)
   ============================================================ */

CREATE TABLE IF NOT EXISTS items (
                                     id          INT AUTO_INCREMENT PRIMARY KEY,
                                     name        VARCHAR(100) NOT NULL,
    type        ENUM('Basic', 'Upgrade', 'Artifact', 'Secret', 'Consumable') NOT NULL,
    cost        INT,
    description TEXT,
    effects     TEXT,
    active      TINYINT DEFAULT 1
    );


/* ============================================================
   TABELA: gallery  (galeria obrazów)
   ============================================================ */

CREATE TABLE IF NOT EXISTS gallery (
                                       id          INT AUTO_INCREMENT PRIMARY KEY,
                                       title       VARCHAR(255) NOT NULL,
    image_url   VARCHAR(255) NOT NULL,
    description TEXT,
    category    ENUM('Heroes', 'Items', 'Screenshots', 'Artwork') DEFAULT 'Screenshots',
    active      TINYINT DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );


/* ============================================================
   TABELA: contact_messages  (formularz kontaktowy)
   ============================================================ */

CREATE TABLE IF NOT EXISTS contact_messages (
                                                id         INT AUTO_INCREMENT PRIMARY KEY,
                                                name       VARCHAR(100) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    subject    VARCHAR(255),
    message    TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status     ENUM('new', 'read', 'replied') DEFAULT 'new'
    );


/* ============================================================
   DANE POCZĄTKOWE: podstrony
   ============================================================ */

INSERT INTO page_list (page_title, page_content, active) VALUES(
    'O mnie',
'<h2>O mnie</h2><p>Witaj na mojej stronie o Dota 2! Jestem pasjonatem tej gry od wielu lat...</p>', 1),

        ('Moje projekty',
        '<h2>Moje projekty</h2><p>Tutaj znajdziesz informacje o moich projektach związanych z Dota 2...</p>', 1);




INSERT INTO heroes (name, role, description, image_url, abilities) VALUES
                                                                       ('Morphling', 'Carry',
                                                                        'Morphling to zwinny bohater typu agility, który może dostosowywać swoją siłę i zwinność.',
                                                                        'images/morphling.jpg',
                                                                        'Waveform, Adaptive Strike, Morph, Replicate'),

                                                                       ('Dazzle', 'Support',
                                                                        'Dazzle to bohater wspierający, który może leczyć sojuszników i osłabiać wrogów.',
                                                                        'images/dazzle.jpg',
                                                                        'Poison Touch, Shallow Grave, Shadow Wave, Bad Juju'),

                                                                       ('Invoker', 'Mid',
                                                                        'Invoker to magiczny bohater z ogromną liczbą zaklęć do kombinowania.',
                                                                        'images/baby-invoker.png',
                                                                        'Invoke, Quas, Wex, Exort');


/* ============================================================
   DANE: przedmioty
   ============================================================ */

INSERT INTO items (name, type, cost, description, effects) VALUES
                                                               ('Boots of Speed', 'Basic', 500,
                                                                'Podstawowe buty zwiększające prędkość ruchu.',
                                                                '+45 movement speed'),

                                                               ('Black King Bar', 'Artifact', 4050,
                                                                'Zapewnia magiczną odporność na krótki czas.',
                                                                '+10 strength, +24 damage, Active: Magic Immunity'),

                                                               ('Blink Dagger', 'Artifact', 2250,
                                                                'Pozwala teleportować się na krótki dystans.',
                                                                'Active: Blink');


/* ============================================================
   DANE: galeria obrazów
   ============================================================ */

INSERT INTO gallery (title, image_url, description, category) VALUES
                                                                  ('Windranger', 'images/windranger.avif', 'Windranger - bohaterka z łukiem', 'Heroes'),
                                                                  ('Drow Ranger', 'images/drow_ranger.jpg', 'Drow Ranger - łuczniczka', 'Heroes'),
                                                                  ('Queen of Pain', 'images/queen_of_pain.jpg', 'Queen of Pain - demoniczna czarodziejka', 'Heroes'),
                                                                  ('Legion Commander', 'images/lc.webp', 'Legion Commander - przywódczyni legionu', 'Heroes');
