CREATE DATABASE IF NOT EXISTS lv4 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lv4;

-- Korisnici
CREATE TABLE IF NOT EXISTS korisnici (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    lozinka VARCHAR(255) NOT NULL,
    uloga ENUM('korisnik','admin') DEFAULT 'korisnik',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Filmovi
CREATE TABLE IF NOT EXISTS filmovi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naslov VARCHAR(255) NOT NULL,
    zanr VARCHAR(100) NOT NULL,
    godina INT NOT NULL,
    trajanje_min INT NOT NULL,
    ocjena DECIMAL(3,1) NOT NULL,
    reziser VARCHAR(100),
    zemlja VARCHAR(100),
    opis TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Osobna videoteka (košarica za filmove)
CREATE TABLE IF NOT EXISTS zeljeni_filmovi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    korisnik_id INT NOT NULL,
    film_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_film (korisnik_id, film_id),
    FOREIGN KEY (korisnik_id) REFERENCES korisnici(id) ON DELETE CASCADE,
    FOREIGN KEY (film_id) REFERENCES filmovi(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Slike za galeriju
CREATE TABLE IF NOT EXISTS slike (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naziv_datoteke VARCHAR(255) NOT NULL,
    opis VARCHAR(255),
    putanja VARCHAR(500) NOT NULL,
    izvor ENUM('lokalno','api') DEFAULT 'lokalno',
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES korisnici(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ocjene slika
CREATE TABLE IF NOT EXISTS ocjene (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_korisnik INT NOT NULL,
    id_slika INT NOT NULL,
    ocjena TINYINT NOT NULL CHECK (ocjena BETWEEN 1 AND 5),
    komentar TEXT,
    vrijeme_ocjene TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_slika (id_korisnik, id_slika),
    FOREIGN KEY (id_korisnik) REFERENCES korisnici(id) ON DELETE CASCADE,
    FOREIGN KEY (id_slika) REFERENCES slike(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin korisnik ()
INSERT INTO `korisnici` (`id`, `username`, `email`, `lozinka`, `uloga`, `created_at`) VALUES
(1, 'admin', 'admin@lv4.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-05-23 14:00:26'),
(2, 'test', 'test@test.t', '$2y$10$qGAfhffWxlwOoqwPSMlhwuxN6pouYO/9EW4BSmRaMNkl3XoLCRkZG', 'korisnik', '2026-05-23 14:23:49'),
(3, 'test2', 'test2@test.hr', '$2y$10$iE2vcmI5.8iKpv2ZcsYPbe1Q3gS2KQBh99bosIpqGFJwRl5KANkpa', 'korisnik', '2026-05-23 15:21:50');

INSERT INTO filmovi (naslov, zanr, godina, trajanje_min, ocjena, reziser, zemlja) VALUES
('The Shawshank Redemption','Drama',1994,142,9.3,'Frank Darabont','USA'),
('The Godfather','Crime, Drama',1972,175,9.2,'Francis Ford Coppola','USA'),
('The Dark Knight','Action, Crime',2008,152,9.0,'Christopher Nolan','UK/USA'),
('Schindler\'s List','Biography, Drama',1993,195,9.0,'Steven Spielberg','USA'),
('12 Angry Men','Crime, Drama',1957,96,9.0,'Sidney Lumet','USA'),
('Pulp Fiction','Crime, Drama',1994,154,8.9,'Quentin Tarantino','USA'),
('The Lord of the Rings: The Return of the King','Action, Adventure',2003,201,9.0,'Peter Jackson','NZ/USA'),
('Fight Club','Drama',1999,139,8.8,'David Fincher','USA'),
('Inception','Action, Adventure',2010,148,8.8,'Christopher Nolan','USA/UK'),
('The Matrix','Action, Sci-Fi',1999,136,8.7,'Lana Wachowski','USA'),
('Goodfellas','Biography, Crime',1990,145,8.7,'Martin Scorsese','USA'),
('One Flew Over the Cuckoo\'s Nest','Drama',1975,133,8.7,'Milos Forman','USA'),
('Seven Samurai','Action, Drama',1954,207,8.6,'Akira Kurosawa','Japan'),
('Se7en','Crime, Drama',1995,127,8.6,'David Fincher','USA'),
('The Silence of the Lambs','Crime, Drama',1991,118,8.6,'Jonathan Demme','USA'),
('City of God','Crime, Drama',2002,130,8.6,'Fernando Meirelles','Brazil'),
('Life Is Beautiful','Comedy, Drama',1997,116,8.6,'Roberto Benigni','Italy'),
('Interstellar','Adventure, Drama',2014,169,8.7,'Christopher Nolan','USA/UK'),
('Saving Private Ryan','Drama, War',1998,169,8.6,'Steven Spielberg','USA'),
('Parasite','Drama, Thriller',2019,132,8.5,'Bong Joon Ho','South Korea'),
('The Green Mile','Crime, Drama',1999,189,8.6,'Frank Darabont','USA'),
('Star Wars: Episode IV - A New Hope','Action, Adventure',1977,121,8.6,'George Lucas','USA'),
('Terminator 2: Judgment Day','Action, Sci-Fi',1991,137,8.6,'James Cameron','USA'),
('Back to the Future','Adventure, Comedy',1985,116,8.5,'Robert Zemeckis','USA'),
('The Pianist','Biography, Drama',2002,150,8.5,'Roman Polanski','France/Poland'),
('Psycho','Horror, Mystery',1960,109,8.5,'Alfred Hitchcock','USA'),
('Gladiator','Action, Adventure',2000,155,8.5,'Ridley Scott','USA/UK'),
('The Lion King','Animation, Adventure',1994,88,8.5,'Roger Allers','USA'),
('The Departed','Crime, Drama',2006,151,8.5,'Martin Scorsese','USA');

INSERT INTO `ocjene` (`id`, `id_korisnik`, `id_slika`, `ocjena`, `komentar`, `vrijeme_ocjene`) VALUES
(1, 2, 2, 4, '', '2026-05-23 14:46:18'),
(6, 2, 1, 5, '', '2026-05-23 15:01:18');

INSERT INTO `slike` (`id`, `naziv_datoteke`, `opis`, `putanja`, `izvor`, `uploaded_by`, `created_at`) VALUES
(1, 'photo1.jpg', 'Meowtain', 'public/images/photo1.jpg', 'lokalno', 1, '2026-05-23 14:22:48'),
(2, 'photo2.jpg', 'Fake RNG', 'public/images/photo2.jpg', 'lokalno', 1, '2026-05-23 14:23:05'),
(3, 'api_slika_1.jpg', 'Slika 1', 'https://picsum.photos/900/600?random=1', 'api', 1, '2026-05-23 15:30:16'),
(4, 'api_slika_2.jpg', 'Slika 2', 'https://picsum.photos/900/600?random=2', 'api', 1, '2026-05-23 15:30:16'),
(5, 'api_slika_3.jpg', 'Slika 3', 'https://picsum.photos/900/600?random=3', 'api', 1, '2026-05-23 15:30:16'),
(6, 'api_slika_4.jpg', 'Slika 4', 'https://picsum.photos/900/600?random=4', 'api', 1, '2026-05-23 15:30:16'),
(7, 'api_slika_5.jpg', 'Slika 5', 'https://picsum.photos/900/600?random=5', 'api', 1, '2026-05-23 15:30:16'),
(8, 'api_slika_6.jpg', 'Slika 6', 'https://picsum.photos/900/600?random=6', 'api', 1, '2026-05-23 15:30:16'),
(9, 'api_slika_7.jpg', 'Slika 7', 'https://picsum.photos/900/600?random=7', 'api', 1, '2026-05-23 15:30:16'),
(10, 'api_slika_8.jpg', 'Slika 8', 'https://picsum.photos/900/600?random=8', 'api', 1, '2026-05-23 15:30:16'),
(11, 'api_slika_9.jpg', 'Slika 9', 'https://picsum.photos/900/600?random=9', 'api', 1, '2026-05-23 15:30:16'),
(12, 'api_slika_10.jpg', 'Slika 10', 'https://picsum.photos/900/600?random=10', 'api', 1, '2026-05-23 15:30:16'),
(13, 'api_slika_11.jpg', 'Slika 11', 'https://picsum.photos/900/600?random=11', 'api', 1, '2026-05-23 15:30:16'),
(14, 'api_slika_12.jpg', 'Slika 12', 'https://picsum.photos/900/600?random=12', 'api', 1, '2026-05-23 15:30:16');

