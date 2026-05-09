# Dota 2 Shop / Fan Website

Dota 2 Shop to edukacyjny projekt strony internetowej napisany w PHP.  
Projekt łączy prostą stronę tematyczną o Dota 2 ze sklepem internetowym, koszykiem, panelem administratora oraz formularzem kontaktowym.

Aplikacja została przygotowana w celu nauki PHP, pracy z bazą danych, obsługi formularzy, zarządzania produktami oraz podstawowej organizacji kodu w stylu obiektowym.

---

## Spis treści

- [Opis projektu](#opis-projektu)
- [Funkcjonalności](#funkcjonalności)
- [Technologie](#technologie)
- [Struktura projektu](#struktura-projektu)
- [Uruchomienie projektu](#uruchomienie-projektu)
- [Konfiguracja bazy danych](#konfiguracja-bazy-danych)
- [Panel administratora](#panel-administratora)
- [Sklep i koszyk](#sklep-i-koszyk)
- [Formularz kontaktowy](#formularz-kontaktowy)
- [Uwagi](#uwagi)
- [Autor](#autor)

---

## Opis projektu

Projekt jest stroną internetową poświęconą tematyce Dota 2.  
Oprócz statycznych podstron informacyjnych aplikacja zawiera prosty moduł sklepu, w którym można przeglądać produkty, dodawać je do koszyka oraz zarządzać nimi z poziomu panelu administratora.

Projekt zawiera również formularz kontaktowy z obsługą wysyłania wiadomości e-mail za pomocą biblioteki PHPMailer.

Główne elementy projektu:

- strona główna,
- podstrony informacyjne,
- galeria,
- sklep z produktami,
- koszyk,
- panel administratora,
- zarządzanie produktami i kategoriami,
- formularz kontaktowy,
- obsługa logowania administratora.

---

## Funkcjonalności

Projekt posiada następujące funkcje:

- wyświetlanie strony głównej i podstron tematycznych,
- dynamiczne ładowanie treści stron,
- sklep z produktami,
- wyświetlanie kategorii produktów,
- dodawanie produktów do koszyka,
- usuwanie produktów z koszyka,
- panel administratora,
- logowanie administratora,
- dodawanie, edycja i usuwanie produktów,
- zarządzanie kategoriami,
- przesyłanie zdjęć produktów,
- formularz kontaktowy,
- wysyłanie wiadomości e-mail przez PHPMailer,
- obsługa strony błędu 404,
- podział kodu na klasy i pliki odpowiedzialne za konkretne funkcje.

---

## Technologie

W projekcie wykorzystano:

- PHP,
- HTML,
- CSS,
- JavaScript,
- MySQL / MariaDB,
- PHPMailer,
- XAMPP / Apache,
- podstawy programowania obiektowego w PHP.

---

## Struktura projektu

```text
project_v1.91++_Poprawione/
│
├── admin/
│   ├── admin.php
│   ├── admin.css
│   ├── categories.php
│   ├── edit.php
│   ├── login.php
│   ├── logout.php
│   ├── products.php
│   └── reset.php
│
├── app/
│   ├── autoload.php
│   ├── bootstrap.php
│   └── classes/
│       ├── Auth.php
│       ├── Cart.php
│       ├── CategoryRepository.php
│       ├── Database.php
│       ├── MailService.php
│       ├── PageRepository.php
│       └── ProductRepository.php
│
├── CSS/
│   ├── shop.css
│   ├── style.css
│   └── style1.css
│
├── html/
│   ├── 404.html
│   ├── bohaterowie.html
│   ├── filmy.html
│   ├── galeria.html
│   ├── glowna.html
│   ├── kontact.html
│   ├── ohobby.html
│   └── przedmioty.html
│
├── images/
│   ├── products_upload/
│   └── pliki graficzne projektu
│
├── JS/
│   ├── kolorujtlo.js
│   └── timadate.js
│
├── PHPMailer-master/
│   └── biblioteka PHPMailer
│
├── cart.php
├── cfg.php
├── contact.php
├── index.php
├── shop.php
├── showpage.php
└── 404.html
```

---

## Uruchomienie projektu

### 1. Skopiowanie projektu do XAMPP

Projekt najlepiej uruchomić lokalnie za pomocą XAMPP.

Skopiuj folder projektu do katalogu:

```text
C:/xampp/htdocs/
```

Przykład:

```text
C:/xampp/htdocs/project_v1.91++_Poprawione/
```

---

### 2. Uruchomienie Apache i MySQL

W panelu XAMPP uruchom:

```text
Apache
MySQL
```

---

### 3. Wejście na stronę w przeglądarce

Po uruchomieniu serwera projekt będzie dostępny pod adresem:

```text
http://localhost/project_v1.91++_Poprawione/
```

---

## Konfiguracja bazy danych

Połączenie z bazą danych znajduje się w pliku:

```text
cfg.php
```

oraz w klasie:

```text
app/classes/Database.php
```

Przykładowe dane konfiguracyjne mogą wyglądać tak:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "nazwa_bazy";
```

Przed uruchomieniem sklepu należy:

1. utworzyć bazę danych w phpMyAdmin,
2. dodać tabele dla produktów, kategorii oraz użytkownika administratora,
3. sprawdzić dane połączenia w pliku `cfg.php`.

Przykładowy adres phpMyAdmin:

```text
http://localhost/phpmyadmin
```

---

## Panel administratora

Panel administratora znajduje się w katalogu:

```text
admin/
```

Przykładowy adres:

```text
http://localhost/project_v1.91++_Poprawione/admin/login.php
```

Panel administratora umożliwia:

- logowanie administratora,
- zarządzanie produktami,
- dodawanie nowych produktów,
- edycję istniejących produktów,
- usuwanie produktów,
- zarządzanie kategoriami,
- przesyłanie zdjęć produktów.

Za obsługę logowania odpowiada klasa:

```text
app/classes/Auth.php
```

Za produkty odpowiada klasa:

```text
app/classes/ProductRepository.php
```

Za kategorie odpowiada klasa:

```text
app/classes/CategoryRepository.php
```

---

## Sklep i koszyk

Moduł sklepu znajduje się w pliku:

```text
shop.php
```

Koszyk znajduje się w pliku:

```text
cart.php
```

Za logikę koszyka odpowiada klasa:

```text
app/classes/Cart.php
```

Użytkownik może przeglądać produkty, dodawać je do koszyka oraz usuwać produkty z koszyka.

Zdjęcia produktów są przechowywane w katalogu:

```text
images/products_upload/
```

---

## Formularz kontaktowy

Formularz kontaktowy znajduje się w pliku:

```text
contact.php
```

Do wysyłania wiadomości e-mail używana jest biblioteka:

```text
PHPMailer-master/
```

Za obsługę wiadomości odpowiada klasa:

```text
app/classes/MailService.php
```

Formularz kontaktowy może służyć do wysyłania wiadomości od użytkownika do właściciela strony.

---

## Pliki statyczne

Projekt zawiera pliki HTML z treściami stron:

```text
html/
```

Style CSS znajdują się w katalogu:

```text
CSS/
```

Skrypty JavaScript znajdują się w katalogu:

```text
JS/
```

Grafiki używane w projekcie znajdują się w katalogu:

```text
images/
```

---

## Najważniejsze pliki projektu

| Plik / katalog | Opis |
|---|---|
| `index.php` | Główny plik strony |
| `showpage.php` | Obsługa wyświetlania podstron |
| `shop.php` | Strona sklepu |
| `cart.php` | Obsługa koszyka |
| `contact.php` | Formularz kontaktowy |
| `cfg.php` | Konfiguracja projektu |
| `admin/` | Panel administratora |
| `app/classes/Database.php` | Połączenie z bazą danych |
| `app/classes/Auth.php` | Logowanie administratora |
| `app/classes/Cart.php` | Logika koszyka |
| `app/classes/ProductRepository.php` | Operacje na produktach |
| `app/classes/CategoryRepository.php` | Operacje na kategoriach |
| `app/classes/MailService.php` | Wysyłanie wiadomości e-mail |
| `PHPMailer-master/` | Biblioteka do obsługi e-maili |

---

## Charakter edukacyjny projektu

Projekt został wykonany w celach edukacyjnych.  
Jego głównym celem było przećwiczenie:

- podstaw PHP,
- pracy z formularzami,
- połączenia z bazą danych,
- tworzenia prostego panelu administratora,
- zarządzania produktami,
- pracy z plikami graficznymi,
- organizacji kodu w osobnych klasach,
- integracji biblioteki PHPMailer.

---

## Ograniczenia projektu

Projekt nie jest gotowym systemem produkcyjnym.  
Niektóre elementy są uproszczone, ponieważ projekt ma charakter akademicki.

Możliwe ograniczenia:

- brak zaawansowanego systemu płatności,
- brak pełnej walidacji wszystkich formularzy,
- brak rejestracji zwykłych użytkowników,
- prosta obsługa koszyka,
- podstawowy system logowania administratora,
- konfiguracja bazy danych znajduje się lokalnie w plikach projektu,
- projekt wymaga ręcznej konfiguracji bazy danych w phpMyAdmin.

---

## Możliwe dalsze rozszerzenia

Projekt można rozbudować o:

- rejestrację i logowanie użytkowników,
- system zamówień,
- historię zakupów,
- płatności online,
- panel użytkownika,
- filtrowanie i sortowanie produktów,
- wyszukiwarkę produktów,
- lepszą walidację formularzy,
- zabezpieczenie panelu administratora,
- responsywny interfejs mobilny,
- migracje bazy danych,
- instalację PHPMailer przez Composer,
- oddzielenie logiki aplikacji od widoków.

---

## Informacja o sposobie pracy

Projekt został przygotowany jako projekt edukacyjny.  
Podczas pracy korzystano z dokumentacji technicznej, materiałów edukacyjnych oraz narzędzi AI jako wsparcia przy analizie błędów, generowaniu pomysłów i dopracowywaniu kodu.

Najważniejsze elementy projektu, takie jak uruchomienie aplikacji, konfiguracja plików, testowanie działania strony, sprawdzanie formularzy oraz poprawianie błędów, były wykonywane i analizowane samodzielnie.

---

## Autor

Raman Vaitsiuk  
Student informatyki  
Uniwersytet Warmińsko-Mazurski w Olsztynie
