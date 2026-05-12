
/**
 * BLOCKEAN MODULE: HEADER SYSTEM 001
 * Source: uniwers theme prototype
 */

<!DOCTYPE html>
<html <?php language_attributes(); ?>> <!-- Dynamiczne pobieranie języka strony z WordPressa -->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"> <!-- Dynamiczne kodowanie znaków -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Pobranie nazwy strony z WordPressa -->
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (ikony) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Ten hook jest absolutnie wymagany przez WordPressa - ładuje style wtyczek itp. -->
    <?php wp_head(); ?>

    <style>
        /* 
         * ---------------------------------------------------------------------------
         * WYMUSZENIE ROZMIARU DYNAMICZNEGO LOGOTYPU Z WORDPRESSA
         * ---------------------------------------------------------------------------
         * WordPress dodaje do wgranego logotypu klasę '.custom-logo'.
         * Zabezpieczamy również ogólny znacznik 'img' wewnątrz '.navbar-brand'.
         */
        .navbar-brand img,
        .custom-logo {
            max-height: 60px; /* Wymusza zmniejszenie obrazka do wysokości 60px (z oryginalnych 80px) */
            width: auto;      /* Szerokość dopasuje się automatycznie, zachowując proporcje logo */
            object-fit: contain; /* Upewnia się, że grafika nie zostanie przycięta ani zniekształcona */
        }
        /* ZMIENNE KOLORYSTYCZNE - Możesz przenieść ten blok do pliku style.css motywu */
        :root {
            --brand-red: #cf2e2e; /* Główny kolor czerwony */
            --brand-red-light: #fae8e5; /* Jasne tło dla przycisków */
            --text-dark: #212529; /* Ciemny tekst */
            --text-gray: #6c757d; /* Szary tekst */
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* --- STYLIZACJA NAVBARU (NAGŁÓWKA) --- */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05); /* Delikatny cień pod menu */
            padding-top: 15px;
            padding-bottom: 15px;
            transition: all 0.3s ease;
        }

        /* --- STYLIZACJA PRZYCISKÓW --- */
        .btn-contact {
            background-color: var(--brand-red-light);
            color: var(--brand-red);
            font-weight: 600;
            border-radius: 6px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        .btn-contact:hover {
            background-color: var(--brand-red);
            color: #fff;
        }
        
        .phone-desktop {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-dark);
        }
        .phone-desktop i {
            color: var(--brand-red);
        }

        /* --- STYLIZACJA LINKÓW W MENU --- */
        .navbar-nav .nav-item {
            position: relative; /* Niezbędne do absolutnego pozycjonowania kreski pod spodem */
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            color: var(--text-dark);
            padding: 10px 15px !important; /* Padding wymusza szerokość klikalnego obszaru większą niż sam tekst */
            margin: 0 5px;
            transition: color 0.3s;
        }
        .navbar-nav .nav-link:hover {
            color: var(--brand-red);
        }
        
        /* 
         * ---------------------------------------------------------------------------
         * PODŚWIETLENIE AKTYWNEJ STRONY (CZERWONA KRESKA)
         * ---------------------------------------------------------------------------
         * Zmieniliśmy pseudoelement z ::after na ::before!
         * Dzięki temu nie nadpisujemy domyślnej strzałki Bootstrapa (careta),
         * która używa ::after do wyświetlania ikony rozwijania na elementach z podmenu.
         */
        @media (min-width: 992px) {
            .navbar-nav > .nav-item > .nav-link.active::before,
            .navbar-nav > .current-menu-item > a::before,
            .navbar-nav > .current-menu-ancestor > a::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 5px;  /* Margines lewy - kreska rozciąga się poza tekst */
                right: 5px; /* Margines prawy */
                height: 1px; /* Grubość czerwonej linii */
                background-color: var(--brand-red);
            }
        }

        /* 
         * ---------------------------------------------------------------------------
         * ROZWIJANIE PODMENU PO NAJECHANIU MYSZKĄ (HOVER) TYLKO NA DESKTOPIE
         * ---------------------------------------------------------------------------
         */
        @media (min-width: 992px) {
            /* Gdy myszka najedzie na element rodzica (.dropdown)... */
            .navbar-nav .dropdown:hover .dropdown-menu {
                display: block; /* ...pokaż okienko z podmenu */
                margin-top: 0;  /* Usuwa przerwę, żeby myszka nie "zgubiła" menu przy zjeżdżaniu w dół */
            }
        }

        /* --- ELEMENTY MOBILNE --- */
        .navbar-toggler {
            border: none;
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-dark);
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                background: #fff;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                padding: 20px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            }
            .navbar-nav {
                text-align: center; /* Wyśrodkowanie linków na urządzeniach mobilnych */
            }
            .nav-item {
                border-bottom: 1px solid #f0f0f0;
            }
            .nav-item:last-child {
                border-bottom: none;
            }
        }

        /* 
         * ---------------------------------------------------------------------------
         * MEGA MENU - POZYCJONOWANIE (DUŻA KARTA NA DESKTOPIE)
         * ---------------------------------------------------------------------------
         */
      @media (min-width: 992px) {
            /* 1. UWALNIAMY MENU: Kontener rodzica nie może być relatywny */
            .navbar-nav .dropdown-mega {
                position: static !important; 
            }

            /* 2. ROZCIĄGAMY I CENTRUJEMY: */
            .navbar-nav .dropdown-mega .dropdown-menu {
                width: 100% !important;
                /* Ustawiamy 1320px, co odpowiada kontenerowi .container-xl w Bootstrapie 5 */
                max-width: 1320px !important; 
                left: 50% !important;
                right: auto !important;
                transform: translateX(-50%) !important;
                /* Pozostałe parametry pozostają bez zmian, aby nie zepsuć Twojej pracy */
                top: 100%;
                margin-top: 0 !important; 
            }
        }

        /* 
         * ---------------------------------------------------------------------------
         * POPRAWKA 1: ZATRZYMANIE CZERWONEJ KRESKI POD TEKSTEM
         * ---------------------------------------------------------------------------
         * Nadajemy linkom pozycję relatywną, aby kreska (position: absolute) 
         * wiedziała, że ma się trzymać krawędzi tekstu, a nie całej belki.
         */
        .navbar-nav .nav-link {
            position: relative;
            z-index: 1050;
        }

       /* * ---------------------------------------------------------------------------
         * POPRAWKA 3 (OSTATECZNA): IDEALNE WYRÓWNANIE WYSOKOŚCI OBU MENU
         * ---------------------------------------------------------------------------
         * Mega Menu liczy swoją pozycję od głównego paska (który ma ukryty dolny padding),
         * a zwykłe menu od samego słowa. Dlatego dodajemy 8px do zwykłego menu,
         * aby oba okienka zaczynały się co do piksela na tej samej poziomej osi.
         */
        @media (min-width: 992px) {
            .navbar-nav .dropdown-menu {
                top: 100% !important;
                margin-top: 10px !important; /* Opuszcza zwykłe menu o 8px w dół */
            }
            
            .navbar-nav .dropdown-mega .dropdown-menu {
                margin-top: 0 !important; /* Mega Menu zostaje na swoim miejscu */
            }
        }

        /* 
         * ---------------------------------------------------------------------------
         * POPRAWKA 2: NIEWIDZIALNY MOSTEK DLA KARTY (HOVER GAP FIX)
         * ---------------------------------------------------------------------------
         * Tworzymy przezroczysty, niewidzialny blok, który wystaje 25px w górę 
         * z naszej rozwijanej karty. Łączy on kartę z tekstem, dzięki czemu 
         * myszka nie "spada w przepaść" podczas zjeżdżania w dół.
         */
        @media (min-width: 992px) {
            .navbar-nav .dropdown-menu::before {
                content: '';
                position: absolute;
                top: -25px; /* Wysuwa się w górę, pokrywając pustą przerwę */
                left: 0;
                width: 100%;
                height: 25px; /* Wysokość mostka */
                background-color: transparent; /* Jest całkowicie niewidzialny */
            }
        }

/* * ---------------------------------------------------------------------------
         * WIZUALNE STYLOWANIE MEGA MENU I BLOKÓW GUTENBERGA
         * ---------------------------------------------------------------------------
         */
        
        /* 1. Szerokość i główny kontener Mega Menu */
        @media (min-width: 992px) {
            .navbar-nav .dropdown-mega .dropdown-menu {
                width: 100vw;
                max-width: 1140px;
                padding: 0 !important; /* Wymusza usunięcie domyślnej białej ramki Bootstrapa */
                border-radius: 8px !important; /* Główne zaokrąglenie karty */
            }
        }

        /* 2. Formatowanie ogólnego kontenera kolumn Gutenberga */
        .dropdown-mega .dropdown-menu .wp-block-columns {
            margin: 0 !important; /* Wyzerowanie ukrytych marginesów z zewnątrz */
            gap: 0 !important;    /* Usunięcie przerw między kolumnami */
            border-radius: 8px;
            overflow: hidden;
        }

        /* 3. Wewnętrzne marginesy pojedynczych kolumn */
        .dropdown-mega .dropdown-menu .wp-block-column {
            padding: 30px; 
            margin: 0;
        }

        /* 4. Tła, ramki i precyzyjne zaokrąglenia skrajnych kolumn */
        
        /* Pierwsza kolumna od lewej (biała) */
        .dropdown-mega .dropdown-menu .wp-block-column:first-child {
            background-color: #ffffff;
            border-right: 1px solid #eaeaea;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        
        /* Środkowa kolumna (jasnoszara) */
        .dropdown-mega .dropdown-menu .wp-block-column:nth-child(2) {
            background-color: #f8f9fa;
            border-right: 1px solid #eaeaea;
        }
        
        /* Ostatnia kolumna od prawej (jasnoszara) */
        .dropdown-mega .dropdown-menu .wp-block-column:last-child {
            background-color: #f8f9fa;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            border-right: none; /* Upewniamy się, że na samym końcu nie ma kreski */
        }

        /* 5. Zmniejszenie i stylizacja tekstów (nagłówki, akapity, linki) */
        .dropdown-mega .dropdown-menu h1,
        .dropdown-mega .dropdown-menu h2,
        .dropdown-mega .dropdown-menu h3,
        .dropdown-mega .dropdown-menu h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            margin-top: 0;
            color: #333;
        }

        .dropdown-mega .dropdown-menu p,
        .dropdown-mega .dropdown-menu a {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #666;
            text-decoration: none;
        }

        /* Subtelne podkreślenie linków w Mega Menu po najechaniu */
        .dropdown-mega .dropdown-menu a:hover {
            color: var(--brand-red); 
        }

        /* 6. Stylizacja przycisku Gutenberga (zmniejszona wersja na wzór .btn-sm) */
        .dropdown-mega .dropdown-menu .wp-block-button__link {
            display: inline-block;
            background-color: var(--brand-red, #cc0000); 
            color: #ffffff !important; 
            padding: 0.25rem 0.5rem; /* Znacznie mniejszy margines wewnętrzny */
            border-radius: 0.25rem; /* Odrobinę mniejsze zaokrąglenie rogów */
            font-weight: 500; 
            font-size: 0.875rem; /* Mniejsza czcionka (odpowiednik ok. 14px) */
            text-decoration: none;
            border: 1px solid transparent;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
            text-align: center;
        }

        /* Hover (najechanie myszką) */
        .dropdown-mega .dropdown-menu .wp-block-button__link:hover {
            background-color: #b30000; 
            color: #ffffff !important;
        }

       /* 1. Kontener grupy jako Flexbox */
        .mega-menu-item-group {
            display: flex !important;
            flex-wrap: wrap !important; /* Pozwala elementom układać się obok siebie */
            flex-direction: row !important;
            align-items: flex-start !important;
            gap: 0 20px !important; /* 20px odstępu w poziomie, 0 w pionie */
            margin-bottom: 25px !important;
            position: relative;
        }

        /* 2. Obrazek - wymuszamy, by był po lewej i zajmował stałe miejsce */
        .mega-menu-item-group .wp-block-image,
        .mega-menu-item-group figure,
        .mega-menu-item-group img {
            width: 45px !important;
            min-width: 45px !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
        }

        /* 3. Magia dla tekstów (Nagłówek i Akapit) */
        /* Zmuszamy wszystkie elementy poza obrazkiem, by zajęły prawą stronę */
        .mega-menu-item-group h4,
        .mega-menu-item-group h5,
        .mega-menu-item-group p {
            flex: 1 1 calc(100% - 70px) !important; /* Zajmij całą resztę szerokości (100% minus obrazek z marginesem) */
            margin-left: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 5px !important;
            text-align: left !important;
        }

        /* Dodatkowo: upewniamy się, że akapit nie próbuje wskoczyć pod obrazek */
        .mega-menu-item-group p {
            margin-left: 65px !important; /* Odsuwamy akapit o szerokość obrazka + gap */
        }
        
        /* Jeśli nagłówek jest pierwszy, też go odsuwamy (jeśli flex-basis nie zadziała) */
        .mega-menu-item-group h4, .mega-menu-item-group h5 {
            margin-left: 0 !important; /* Nagłówek jest obok obrazka dzięki flex-row */
        }
    </style>
</head>
<body <?php body_class(); ?>> <!-- Klasy body generowane przez WP, ułatwiają stylowanie konkretnych podstron -->

<!-- POCZĄTEK NAVBARU -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-xl">
        
        <!-- LOGO (Zmodyfikowane pod dynamiczne logo z WordPressa) -->
        <a class="navbar-brand d-flex align-items-center" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <?php 
            // Sprawdzamy czy użytkownik ustawił własne logo w Wygląd -> Dostosuj -> Tożsamość witryny
            if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
                the_custom_logo(); // Wyświetla obrazek logo z WP
            } else {
                // Miejsce na statyczny plik SVG lub JPG, jeśli logo w panelu WP nie jest ustawione.
                // Używamy get_template_directory_uri() aby poprawnie zbudować ścieżkę do pliku w folderze motywu.
                echo '<img src="' . get_template_directory_uri() . '/assets/img/logo.svg" alt="Logo Uniwers" height="40">';
            }
            ?>
        </a>

        <!-- ELEMENTY PO PRAWEJ (Kontakt i telefon) - WIDOCZNE TEŻ NA MOBILE -->
        <div class="d-flex align-items-center order-lg-3 ms-auto ms-lg-0">
            
            <?php 
            // 1. Obsługa telefonu
            $phone = get_theme_mod('uniwers_phone_number', '713 073 073');
            $phone_clean = str_replace(' ', '', $phone);
            
            // 2. Obsługa linku (Strona + Kotwica)
            $contact_page_id = get_theme_mod('uniwers_contact_page_id', 0); // Pobiera ID wybranej strony
            $contact_anchor  = get_theme_mod('uniwers_contact_anchor', ''); // Pobiera wpisaną kotwicę
            
            // Budowanie końcowego linku
            if ( $contact_page_id ) {
                // Jeśli użytkownik wybrał stronę, zamieniamy jej ID na pełen adres URL i doklejamy kotwicę
                $final_contact_link = get_permalink( $contact_page_id ) . $contact_anchor;
            } else {
                // Zabezpieczenie (fallback), jeśli nic nie jest wybrane
                $final_contact_link = '#' . $contact_anchor; 
            }
            ?>

            <!-- Telefon (Tylko Desktop) -->
            <div class="phone-desktop d-none d-lg-flex align-items-center me-4">
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="text-decoration-none text-dark">
                    <i class="fa-solid fa-phone me-2"></i> <?php echo esc_html($phone); ?>
                </a>
            </div>
            
            <!-- Przycisk Kontakt -->
            <a href="<?php echo esc_url($final_contact_link); ?>" class="btn btn-contact me-3 me-lg-0">Kontakt</a>
            
            <!-- Przycisk Hamburgera (Mobile) -->
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Przełącz nawigację">
                Menu <i class="fa-solid fa-bars ms-1 toggle-icon"></i>
            </button>
        </div>

        <!-- DYNAMICZNE MENU Z WORDPRESSA -->
        <div class="collapse navbar-collapse order-lg-2" id="mainMenu">
            
            <?php
            // Ta funkcja pobiera strukturę menu prosto z panelu WP (Wygląd -> Menu).
            // Użytkownik może z panelu zmieniać nazwy, kolejność i dodawać podstrony.
            wp_nav_menu( array(
                'theme_location' => 'primary_menu', // Unikalny identyfikator rejestracji menu
                'container'      => false,          // Wyłącza domyślny kontener <div> dodawany przez WP
                'menu_class'     => 'navbar-nav mx-auto mb-2 mb-lg-0', // Klasy Bootstrapa dodane bezpośrednio do tagu <ul>
                'fallback_cb'    => false,          // Co zrobić, gdy menu nie jest przypisane (zostawiamy puste)
                'depth'          => 2,              // Zezwala na menu rozwijane jednego poziomu (dropdowny)
                
                // UWAGA: Aby menu WordPressa idealnie rozumiało dropdowny z Bootstrap 5, 
                // zaleca się użycie tzw. NavWalkera (zobacz instrukcję pod kodem).
                // Jeśli dodasz go do motywu, należy odkomentować linijkę poniżej:
                // 'walker' => new Bootstrap_5_WP_Nav_Menu_Walker()
            ) );
            ?>
            
        </div>
        
    </div>
</nav>

<!-- Skrypty Bootstrap (Najlepiej przenieść do wp_enqueue_script w functions.php) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Skrypt obsługujący nawigację i ikonę hamburgera na mobile -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // -----------------------------------------------------------------------
        // 1. OBSŁUGA IKONY HAMBURGERA NA TELEFONACH
        // -----------------------------------------------------------------------
        const toggler = document.querySelector('.navbar-toggler');
        const icon = toggler ? toggler.querySelector('.toggle-icon') : null;
        const myCollapse = document.getElementById('mainMenu');
        
        if(myCollapse && icon) {
            myCollapse.addEventListener('show.bs.collapse', function () {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            });
            
            myCollapse.addEventListener('hide.bs.collapse', function () {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        }

        // -----------------------------------------------------------------------
        // 2. PRZYWRÓCENIE KLIKALNOŚCI GŁÓWNYCH LINKÓW Z PODMENU NA DESKTOPIE
        // -----------------------------------------------------------------------
        // Pobieramy wszystkie linki, które mają klasę rozwijającą menu
        let dropdownLinks = document.querySelectorAll('.dropdown-toggle');
        
        dropdownLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Sprawdzamy, czy użytkownik korzysta z dużego ekranu (komputera)
                if (window.innerWidth >= 992) {
                    // Pobieramy adres URL, do którego prowadzi link
                    let url = this.getAttribute('href');
                    
                    // Jeśli adres istnieje i nie jest pustym odnośnikiem ("#" lub "")
                    if (url && url !== '#') {
                        // Wymuszamy przejście pod wskazany adres
                        window.location.href = url;
                    }
                }
                // Na mniejszych ekranach (telefonach) nic nie zmieniamy - 
                // kliknięcie po prostu otworzy rozwijane menu (bo nie ma hovera).
            });
        });
    });
</script>
