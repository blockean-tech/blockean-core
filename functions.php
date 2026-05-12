<?php
// Ten kod dodajemy do pliku functions.php naszego motywu

function uniwers_theme_setup() {
    // 1. Dodaje wsparcie motywu dla dynamicznego wgrywania własnego logotypu z poziomu WP (Wygląd -> Dostosuj)
    add_theme_support( 'custom-logo', array(
        'height'      => 40,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // 2. Rejestruje nową pozycję menu (pojawi się w zakładce Wygląd -> Menu)
    register_nav_menus( array(
        'primary_menu' => __( 'Menu Główne Nagłówka', 'uniwers' ),
    ) );
}
// Uruchamia powyższą funkcję podczas inicjalizacji motywu
add_action( 'after_setup_theme', 'uniwers_theme_setup' );

/*
 * ---------------------------------------------------------------------------
 * 1. ZAKTUALIZOWANE: DODAWANIE KLAS DO <li> (Z OBSŁUGĄ MEGA MENU)
 * ---------------------------------------------------------------------------
 */
function uniwers_add_li_class( $classes, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && $args->theme_location === 'primary_menu' ) {
        if ( $depth === 0 ) {
            $classes[] = 'nav-item';
        }
        
        // Pobieramy ID Mega Menu przypisane do tego konkretnego linku
        $mega_menu_id = get_post_meta( $item->ID, '_mega_menu_card_id', true );
        
        // Jeśli element ma klasyczne podmenu LUB podpiętą kartę Mega Menu, dajemy mu klasę rozwijaną
        if ( in_array( 'menu-item-has-children', $item->classes ) || ! empty( $mega_menu_id ) ) {
            $classes[] = 'dropdown'; 
            
            // Jeśli to Mega Menu, dodajemy naszą specjalną klasę do szerokiego pozycjonowania
            if ( ! empty( $mega_menu_id ) ) {
                $classes[] = 'dropdown-mega';
            }
        }
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'uniwers_add_li_class', 10, 4 );

/*
 * ---------------------------------------------------------------------------
 * 2. ZAKTUALIZOWANE: DODAWANIE KLAS DO LINKÓW <a> (Z OBSŁUGĄ MEGA MENU)
 * ---------------------------------------------------------------------------
 */
function uniwers_add_a_class( $atts, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && $args->theme_location === 'primary_menu' ) {
        if ( $depth === 0 ) {
            $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' nav-link' : 'nav-link';
        } else {
            $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' dropdown-item py-2' : 'dropdown-item py-2';
        }

        // Ponownie sprawdzamy, czy przypisano tu Mega Menu
        $mega_menu_id = get_post_meta( $item->ID, '_mega_menu_card_id', true );

        if ( in_array( 'menu-item-has-children', $item->classes ) || ! empty( $mega_menu_id ) ) {
            $atts['class'] .= ' dropdown-toggle';
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-expanded'] = 'false';
        }
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'uniwers_add_a_class', 10, 4 );

/*
 * ---------------------------------------------------------------------------
 * 7. NOWE: WYŚWIETLENIE KARTY GUTENBERGA WEWNĄTRZ MENU (MEGA MENU)
 * ---------------------------------------------------------------------------
 */
function uniwers_display_mega_menu_content( $item_output, $item, $depth, $args ) {
    // Interesują nas tylko główne linki na pasku
    if ( $depth === 0 && isset( $args->theme_location ) && $args->theme_location === 'primary_menu' ) {
        
        // Sprawdzamy, czy dla tego linku w panelu zapisano jakieś Mega Menu
        $mega_menu_id = get_post_meta( $item->ID, '_mega_menu_card_id', true );
        
        if ( ! empty( $mega_menu_id ) ) {
            // Pobieramy kartę jako wpis
            $post = get_post( $mega_menu_id );
            
            if ( $post ) {
                // Generujemy i filtrujemy kod HTML ułożony przez Ciebie w Gutenbergu
                $content = apply_filters( 'the_content', $post->post_content );
                
                // Obudowujemy ten kod w okienko Bootstrapa (dropdown-menu)
                $mega_html = '<div class="dropdown-menu shadow p-4 border-0 mt-0">';
                $mega_html .= $content;
                $mega_html .= '</div>';
                
                // "Doklejamy" wygenerowaną kartę na sam koniec głównego linku w menu
                $item_output .= $mega_html;
            }
        }
    }
    return $item_output;
}
// Ten filtr pozwala nam podmienić to, co ostatecznie WordPress wyświetla na ekranie
add_filter( 'walker_nav_menu_start_el', 'uniwers_display_mega_menu_content', 10, 4 );

/*
 * ---------------------------------------------------------------------------
 * 3. ZMIANA KLASY SUB-MENU Z WORDPRESSA NA BOOTSTRAPOWE DROPDOWN-MENU
 * ---------------------------------------------------------------------------
 */
function uniwers_submenu_css_class( $classes, $args, $depth ) {
    if ( isset( $args->theme_location ) && $args->theme_location === 'primary_menu' ) {
        // WordPress domyślnie nadaje rozwijanej liście klasę 'sub-menu'.
        // Zamieniamy ją na klasę 'dropdown-menu' wymaganą przez Bootstrapa.
        // Dodajemy też 'border-0 shadow-sm', aby usunąć brzydką ramkę i dodać miękki cień z naszych pierwszych projektów.
        $classes = array( 'dropdown-menu', 'border-0', 'shadow-sm' );
    }
    return $classes;
}
add_filter( 'nav_menu_submenu_css_class', 'uniwers_submenu_css_class', 10, 3 );

/*
 * ---------------------------------------------------------------------------
 * 4. USTAWIENIA NAGŁÓWKA (CUSTOMIZER) - ZAAWANSOWANE
 * ---------------------------------------------------------------------------
 */
function uniwers_customize_register( $wp_customize ) {
    
    // 1. Sekcja w panelu (bez zmian)
    $wp_customize->add_section( 'uniwers_header_settings', array(
        'title'       => __( 'Ustawienia Nagłówka', 'uniwers' ),
        'description' => __( 'Zarządzaj informacjami kontaktowymi w górnym pasku.', 'uniwers' ),
        'priority'    => 30,
    ) );

    // 2. Numer telefonu (bez zmian)
    $wp_customize->add_setting( 'uniwers_phone_number', array(
        'default'           => '713 073 073',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'uniwers_phone_number', array(
        'label'    => __( 'Numer telefonu', 'uniwers' ),
        'section'  => 'uniwers_header_settings',
        'type'     => 'text',
    ) );

    // 3. WYBÓR STRONY DLA PRZYCISKU (ZMIANA!)
    $wp_customize->add_setting( 'uniwers_contact_page_id', array(
        'default'           => 0, // 0 oznacza brak wybranej strony
        'sanitize_callback' => 'absint', // Upewnia się, że to liczba całkowita (ID strony)
    ) );
    $wp_customize->add_control( 'uniwers_contact_page_id', array(
        'label'       => __( 'Wybierz stronę dla przycisku Kontakt', 'uniwers' ),
        'description' => __( 'Wybierz stronę z listy utworzonych stron WordPress.', 'uniwers' ),
        'section'     => 'uniwers_header_settings',
        'type'        => 'dropdown-pages', // Magiczna komenda WordPressa - tworzy listę stron!
    ) );

    // 4. OPCJONALNA KOTWICA (DODATEK)
    $wp_customize->add_setting( 'uniwers_contact_anchor', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'uniwers_contact_anchor', array(
        'label'       => __( 'Opcjonalna Kotwica', 'uniwers' ),
        'description' => __( 'Jeśli chcesz przenieść użytkownika do konkretnej sekcji na wybranej stronie, wpisz tu jej ID (np. #sekcja-kontaktowa).', 'uniwers' ),
        'section'     => 'uniwers_header_settings',
        'type'        => 'text',
    ) );
}
add_action( 'customize_register', 'uniwers_customize_register' );

/*
 * ---------------------------------------------------------------------------
 * 5. REJESTRACJA TYPU WPISÓW "KARTY MEGA MENU"
 * ---------------------------------------------------------------------------
 * Tworzymy nowe, niewidoczne publicznie jako osobne podstrony miejsce 
 * do projektowania bloków Mega Menu za pomocą Gutenberga.
 */
function uniwers_register_mega_menu_cpt() {
    $labels = array(
        'name'                  => 'Karty Mega Menu',
        'singular_name'         => 'Karta Mega Menu',
        'menu_name'             => 'Mega Menu',
        'add_new'               => 'Dodaj nową kartę',
        'add_new_item'          => 'Dodaj nową kartę Mega Menu',
        'edit_item'             => 'Edytuj kartę',
        'all_items'             => 'Wszystkie Karty',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false, // Karty nie będą miały własnych publicznych adresów URL
        'show_ui'            => true,  // Pokazuj w panelu administratora
        'show_in_menu'       => true,  // Pokaż w lewym menu
        'show_in_rest'       => true,  // KLUCZOWE: Włącza edytor blokowy (Gutenberg)
        'supports'           => array( 'title', 'editor' ), // Chcemy tylko tytuł i edytor treści
        'menu_icon'          => 'dashicons-grid-view', // Ikonka w menu admina
    );

    register_post_type( 'mega_menu_card', $args );
}
add_action( 'init', 'uniwers_register_mega_menu_cpt' );

/*
 * ---------------------------------------------------------------------------
 * 6. DODANIE LISTY ROZWIJANEJ "MEGA MENU" DO EDYTORA MENU W PANELU WP
 * ---------------------------------------------------------------------------
 */

// Funkcja 1: Wyświetla pole wyboru w zakładce Wygląd -> Menu
function uniwers_add_mega_menu_field( $item_id, $item, $depth, $args, $id ) {
    // Mega Menu chcemy podpinać tylko pod główne linki na pasku (depth === 0).
    // Nie chcemy tej opcji w podmenu podrzędnych.
    if ( $depth !== 0 ) {
        return;
    }

    // Pobieramy z bazy danych wszystkie wpisy typu 'mega_menu_card' (czyli nasze karty)
    $mega_menus = get_posts( array(
        'post_type'   => 'mega_menu_card',
        'numberposts' => -1, // Pobierz wszystkie
        'post_status' => 'publish', // Tylko opublikowane
    ) );

    // Jeśli nie ma jeszcze żadnych kart, nie wyświetlamy pola
    if ( empty( $mega_menus ) ) {
        return;
    }

    // Sprawdzamy, czy ten konkretny link w menu ma już przypisaną jakąś kartę (pobieramy z bazy)
    $saved_mega_menu_id = get_post_meta( $item_id, '_mega_menu_card_id', true );
    ?>
    
    <!-- Kod HTML dodawany do okienka edycji linku w panelu WP -->
    <p class="field-mega-menu description description-wide">
        <label for="edit-menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>">
            <strong><?php _e( 'Podepnij Mega Menu (Karta z Gutenberga)', 'uniwers' ); ?></strong><br>
            <select id="edit-menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>" name="menu_item_mega_menu[<?php echo esc_attr( $item_id ); ?>]" style="width: 100%;">
                <option value=""><?php _e( '--- Zwykłe menu (Brak) ---', 'uniwers' ); ?></option>
                
                <?php 
                // Generujemy listę opcji na podstawie utworzonych kart 
                foreach ( $mega_menus as $menu_card ) : ?>
                    <option value="<?php echo esc_attr( $menu_card->ID ); ?>" <?php selected( $saved_mega_menu_id, $menu_card->ID ); ?>>
                        <?php echo esc_html( $menu_card->post_title ); ?>
                    </option>
                <?php endforeach; ?>
                
            </select>
        </label>
    </p>
    <?php
}
// Ten specjalny hook wstrzykuje nasz kod HTML do ustawień każdego linku w menu
add_action( 'wp_nav_menu_item_custom_fields', 'uniwers_add_mega_menu_field', 10, 5 );


// Funkcja 2: Zapisuje nasz wybór do bazy danych po kliknięciu "Zapisz menu"
function uniwers_save_mega_menu_field( $menu_id, $menu_item_db_id, $args ) {
    // Sprawdzamy, czy przesłano dane z naszego pola wyboru
    if ( isset( $_POST['menu_item_mega_menu'][ $menu_item_db_id ] ) ) {
        // Oczyszczamy dane (bezpieczeństwo)
        $mega_menu_id = sanitize_text_field( $_POST['menu_item_mega_menu'][ $menu_item_db_id ] );
        
        // Zapisujemy ID wybranej karty przypisane do tego konkretnego linku w menu
        update_post_meta( $menu_item_db_id, '_mega_menu_card_id', $mega_menu_id );
    } else {
        // Jeśli użytkownik wybrał "Brak", usuwamy informację z bazy
        delete_post_meta( $menu_item_db_id, '_mega_menu_card_id' );
    }
}
// Hook uruchamiany przy zapisywaniu menu
add_action( 'wp_update_nav_menu_item', 'uniwers_save_mega_menu_field', 10, 3 );