<?php
/**
 * Thème Berre-les-Alpes — functions.php
 * WordPress FSE Theme — Version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/* ═══════════════════════════════════════════
   1. SETUP DU THÈME
═══════════════════════════════════════════ */
function berre_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', [
		'height'      => 80,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	] );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption', 'style', 'script' ] );

	load_theme_textdomain( 'theme1-aerien', get_template_directory() . '/languages' );

	register_nav_menus( [
		'primary'   => __( 'Navigation principale', 'theme1-aerien' ),
		'footer'    => __( 'Pied de page', 'theme1-aerien' ),
		'shortcuts' => __( 'Accès rapides', 'theme1-aerien' ),
	] );
}
add_action( 'after_setup_theme', 'berre_setup' );


/* ═══════════════════════════════════════════
   2. CHARGEMENT DES ASSETS
═══════════════════════════════════════════ */
function berre_enqueue_assets() {
	// Google Fonts
	wp_enqueue_style(
		'berre-google-fonts',
		'https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Inter:wght@300;400;500;600&display=swap',
		[],
		null
	);

	// CSS principal
	wp_enqueue_style(
		'berre-custom',
		get_template_directory_uri() . '/assets/css/custom.css',
		[ 'berre-google-fonts' ],
		wp_get_theme()->get( 'Version' )
	);

	// JS principal
	wp_enqueue_script(
		'berre-main',
		get_template_directory_uri() . '/assets/js/main.js',
		[],
		wp_get_theme()->get( 'Version' ),
		true
	);

	// Passage de variables PHP → JS
	wp_localize_script( 'berre-main', 'berreData', [
		'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
		'nonce'     => wp_create_nonce( 'berre_nonce' ),
		'themeUrl'  => get_template_directory_uri(),
		'siteUrl'   => get_site_url(),
	] );
}
add_action( 'wp_enqueue_scripts', 'berre_enqueue_assets' );

// CSS éditeur Gutenberg
function berre_editor_styles() {
	add_editor_style( 'assets/css/editor.css' );
	add_editor_style( 'assets/css/custom.css' );
	add_editor_style( 'https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Inter:wght@300;400;500;600&display=swap' );
}
add_action( 'after_setup_theme', 'berre_editor_styles' );


/* ═══════════════════════════════════════════
   3. CUSTOM POST TYPES
═══════════════════════════════════════════ */

// ── 3a. ACTUALITÉS ──────────────────────────
function berre_register_cpt_actualite() {
	register_post_type( 'actualite', [
		'labels' => [
			'name'               => __( 'Actualités',           'theme1-aerien' ),
			'singular_name'      => __( 'Actualité',            'theme1-aerien' ),
			'add_new'            => __( 'Ajouter',              'theme1-aerien' ),
			'add_new_item'       => __( 'Ajouter une actualité','theme1-aerien' ),
			'edit_item'          => __( 'Modifier',             'theme1-aerien' ),
			'view_item'          => __( 'Voir',                 'theme1-aerien' ),
			'all_items'          => __( 'Toutes les actualités','theme1-aerien' ),
			'search_items'       => __( 'Rechercher',           'theme1-aerien' ),
			'not_found'          => __( 'Aucune actualité',     'theme1-aerien' ),
			'menu_name'          => __( 'Actualités',           'theme1-aerien' ),
		],
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,  // Gutenberg + REST API
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'actualites' ],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-megaphone',
		'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ],
		'taxonomies'         => [ 'categorie_actu' ],
	] );
}
add_action( 'init', 'berre_register_cpt_actualite' );

// ── 3b. AGENDA ──────────────────────────────
function berre_register_cpt_agenda() {
	register_post_type( 'agenda', [
		'labels' => [
			'name'               => __( 'Agenda',               'theme1-aerien' ),
			'singular_name'      => __( 'Événement',            'theme1-aerien' ),
			'add_new'            => __( 'Ajouter',              'theme1-aerien' ),
			'add_new_item'       => __( 'Ajouter un événement', 'theme1-aerien' ),
			'edit_item'          => __( 'Modifier',             'theme1-aerien' ),
			'view_item'          => __( 'Voir',                 'theme1-aerien' ),
			'all_items'          => __( 'Tous les événements',  'theme1-aerien' ),
			'search_items'       => __( 'Rechercher',           'theme1-aerien' ),
			'not_found'          => __( 'Aucun événement',      'theme1-aerien' ),
			'menu_name'          => __( 'Agenda',               'theme1-aerien' ),
		],
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'agenda' ],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-calendar-alt',
		'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ],
		'taxonomies'         => [ 'categorie_agenda' ],
	] );
}
add_action( 'init', 'berre_register_cpt_agenda' );

// ── 3c. SERVICES ────────────────────────────
function berre_register_cpt_service() {
	register_post_type( 'service', [
		'labels' => [
			'name'               => __( 'Services',             'theme1-aerien' ),
			'singular_name'      => __( 'Service',              'theme1-aerien' ),
			'add_new'            => __( 'Ajouter',              'theme1-aerien' ),
			'add_new_item'       => __( 'Ajouter un service',   'theme1-aerien' ),
			'edit_item'          => __( 'Modifier',             'theme1-aerien' ),
			'view_item'          => __( 'Voir',                 'theme1-aerien' ),
			'all_items'          => __( 'Tous les services',    'theme1-aerien' ),
			'not_found'          => __( 'Aucun service',        'theme1-aerien' ),
			'menu_name'          => __( 'Services',             'theme1-aerien' ),
		],
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'services' ],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 7,
		'menu_icon'          => 'dashicons-admin-tools',
		'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ],
		'taxonomies'         => [ 'categorie_service' ],
	] );
}
add_action( 'init', 'berre_register_cpt_service' );


/* ═══════════════════════════════════════════
   4. TAXONOMIES PERSONNALISÉES
═══════════════════════════════════════════ */
function berre_register_taxonomies() {

	// Catégories Actualités
	register_taxonomy( 'categorie_actu', 'actualite', [
		'labels' => [
			'name'          => __( 'Catégories', 'theme1-aerien' ),
			'singular_name' => __( 'Catégorie',  'theme1-aerien' ),
			'all_items'     => __( 'Toutes les catégories', 'theme1-aerien' ),
			'add_new_item'  => __( 'Ajouter une catégorie', 'theme1-aerien' ),
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => [ 'slug' => 'categorie-actu' ],
	] );

	// Catégories Agenda
	register_taxonomy( 'categorie_agenda', 'agenda', [
		'labels' => [
			'name'          => __( 'Catégories', 'theme1-aerien' ),
			'singular_name' => __( 'Catégorie',  'theme1-aerien' ),
			'all_items'     => __( 'Toutes les catégories', 'theme1-aerien' ),
			'add_new_item'  => __( 'Ajouter une catégorie', 'theme1-aerien' ),
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => [ 'slug' => 'categorie-agenda' ],
	] );

	// Catégories Services
	register_taxonomy( 'categorie_service', 'service', [
		'labels' => [
			'name'          => __( 'Catégories', 'theme1-aerien' ),
			'singular_name' => __( 'Catégorie',  'theme1-aerien' ),
			'all_items'     => __( 'Toutes les catégories', 'theme1-aerien' ),
			'add_new_item'  => __( 'Ajouter une catégorie', 'theme1-aerien' ),
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => [ 'slug' => 'categorie-service' ],
	] );
}
add_action( 'init', 'berre_register_taxonomies' );


/* ═══════════════════════════════════════════
   5. MÉTADONNÉES PERSONNALISÉES (REST API)
═══════════════════════════════════════════ */
function berre_register_meta_fields() {

	// Champs Agenda
	$agenda_fields = [
		'_berre_date_debut'  => 'Date de début (YYYY-MM-DD)',
		'_berre_date_fin'    => 'Date de fin (YYYY-MM-DD)',
		'_berre_heure_debut' => 'Heure de début (HH:MM)',
		'_berre_heure_fin'   => 'Heure de fin (HH:MM)',
		'_berre_lieu'        => 'Lieu',
		'_berre_inscription' => 'Inscription requise (oui/non)',
	];
	foreach ( $agenda_fields as $key => $desc ) {
		register_post_meta( 'agenda', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => 'string',
			'description'   => $desc,
			'auth_callback' => fn() => current_user_can( 'edit_posts' ),
		] );
	}

	// Champs Service
	$service_fields = [
		'_berre_lien_externe' => 'URL du service en ligne',
		'_berre_telephone'    => 'Téléphone du service',
		'_berre_email'        => 'Email du service',
		'_berre_horaires'     => 'Horaires spécifiques',
		'_berre_icone'        => "Nom de l'icone (SVG slug)",
		'_berre_couleur'      => 'Couleur accent (bleu/vert/or)',
	];
	foreach ( $service_fields as $key => $desc ) {
		register_post_meta( 'service', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => 'string',
			'description'   => $desc,
			'auth_callback' => fn() => current_user_can( 'edit_posts' ),
		] );
	}

	// Champs Actualité
	register_post_meta( 'actualite', '_berre_accroche', [
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'string',
		'description'   => "Phrase d'accroche courte",
		'auth_callback' => fn() => current_user_can( 'edit_posts' ),
	] );
}
add_action( 'init', 'berre_register_meta_fields' );


/* ═══════════════════════════════════════════
   6. COLONNES ADMIN PERSONNALISÉES
═══════════════════════════════════════════ */

// Agenda : ajouter la colonne date
function berre_agenda_columns( $cols ) {
	$new = [];
	foreach ( $cols as $key => $val ) {
		$new[ $key ] = $val;
		if ( $key === 'title' ) {
			$new['berre_date'] = __( 'Date', 'theme1-aerien' );
			$new['berre_lieu'] = __( 'Lieu', 'theme1-aerien' );
		}
	}
	return $new;
}
add_filter( 'manage_agenda_posts_columns', 'berre_agenda_columns' );

function berre_agenda_column_content( $col, $post_id ) {
	if ( $col === 'berre_date' ) {
		$d = get_post_meta( $post_id, '_berre_date_debut', true );
		echo $d ? esc_html( date_i18n( 'd/m/Y', strtotime( $d ) ) ) : '—';
	}
	if ( $col === 'berre_lieu' ) {
		echo esc_html( get_post_meta( $post_id, '_berre_lieu', true ) ?: '—' );
	}
}
add_action( 'manage_agenda_posts_custom_column', 'berre_agenda_column_content', 10, 2 );

// Tri par date agenda
add_filter( 'manage_edit-agenda_sortable_columns', function( $cols ) {
	$cols['berre_date'] = 'berre_date';
	return $cols;
} );


/* ═══════════════════════════════════════════
   7. FLUSH REWRITE RULES
═══════════════════════════════════════════ */
function berre_flush_rewrite_rules() {
	berre_register_cpt_actualite();
	berre_register_cpt_agenda();
	berre_register_cpt_service();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'berre_flush_rewrite_rules' );


/* ═══════════════════════════════════════════
   8. DONNÉES D'EXEMPLE À L'ACTIVATION
═══════════════════════════════════════════ */
function berre_insert_sample_data() {
	if ( get_option( 'berre_sample_data_inserted' ) ) return;

	// Catégories actualités
	$cats_actu = [ 'Travaux', 'Scolarité', 'Tourisme', 'Environnement', 'Services', 'Culture', 'Urbanisme' ];
	foreach ( $cats_actu as $cat ) {
		if ( ! term_exists( $cat, 'categorie_actu' ) ) {
			wp_insert_term( $cat, 'categorie_actu' );
		}
	}

	// Catégories agenda
	$cats_agenda = [ 'Institutionnel', 'Culture', 'Tourisme', 'Commerce', 'Associations', 'Sport' ];
	foreach ( $cats_agenda as $cat ) {
		if ( ! term_exists( $cat, 'categorie_agenda' ) ) {
			wp_insert_term( $cat, 'categorie_agenda' );
		}
	}

	// Catégories services
	$cats_svc = [ 'État Civil', 'Urbanisme', 'Scolarité', 'Sécurité', 'Démarches', 'Social', 'Pratique' ];
	foreach ( $cats_svc as $cat ) {
		if ( ! term_exists( $cat, 'categorie_service' ) ) {
			wp_insert_term( $cat, 'categorie_service' );
		}
	}

	// Actualités exemples
	$actualites = [
		[
			'title'   => 'Réfection de l\'avenue Paul Granet : calendrier et déviations mis à jour',
			'excerpt' => 'Les travaux de réfection de chaussée reprennent cet automne. Un plan de déviation complet est disponible en mairie.',
			'cat'     => 'Travaux',
		],
		[
			'title'   => 'Rentrée 2025 : nouvelles modalités d\'inscription à l\'école communale',
			'excerpt' => 'Les inscriptions pour la rentrée scolaire 2025 sont ouvertes. Retrouvez les nouvelles modalités sur cette page.',
			'cat'     => 'Scolarité',
		],
		[
			'title'   => 'Saison de randonnée : les sentiers balisés sont prêts',
			'excerpt' => 'Tous les sentiers balisés de la commune sont désormais ouverts et entretenus pour la saison estivale.',
			'cat'     => 'Tourisme',
		],
	];
	foreach ( $actualites as $a ) {
		$term = get_term_by( 'name', $a['cat'], 'categorie_actu' );
		$id   = wp_insert_post( [
			'post_title'   => $a['title'],
			'post_excerpt' => $a['excerpt'],
			'post_content' => '<p>' . $a['excerpt'] . '</p>',
			'post_status'  => 'publish',
			'post_type'    => 'actualite',
		] );
		if ( $id && $term ) {
			wp_set_post_terms( $id, [ $term->term_id ], 'categorie_actu' );
		}
	}

	// Événements agenda exemples
	$evenements = [
		[
			'title'  => 'Conseil Municipal – Séance publique',
			'date'   => date( 'Y-m-d', strtotime( '+10 days' ) ),
			'heure'  => '19:00',
			'lieu'   => 'Salle des délibérations – Mairie',
			'cat'    => 'Institutionnel',
		],
		[
			'title'  => 'Fête de la Musique – Concert en plein air',
			'date'   => date( 'Y-m-d', strtotime( '+21 days' ) ),
			'heure'  => '18:00',
			'lieu'   => 'Place du village',
			'cat'    => 'Culture',
		],
		[
			'title'  => 'Randonnée guidée – Col Saint-Roch',
			'date'   => date( 'Y-m-d', strtotime( '+28 days' ) ),
			'heure'  => '08:30',
			'lieu'   => 'Départ Mairie',
			'cat'    => 'Tourisme',
		],
	];
	foreach ( $evenements as $e ) {
		$term = get_term_by( 'name', $e['cat'], 'categorie_agenda' );
		$id   = wp_insert_post( [
			'post_title'   => $e['title'],
			'post_content' => '<p>' . $e['title'] . '</p>',
			'post_status'  => 'publish',
			'post_type'    => 'agenda',
		] );
		if ( $id ) {
			update_post_meta( $id, '_berre_date_debut', $e['date'] );
			update_post_meta( $id, '_berre_heure_debut', $e['heure'] );
			update_post_meta( $id, '_berre_lieu', $e['lieu'] );
			if ( $term ) wp_set_post_terms( $id, [ $term->term_id ], 'categorie_agenda' );
		}
	}

	// Services exemples
	$services = [
		[ 'title' => 'État Civil',        'cat' => 'État Civil',  'couleur' => 'bleu',  'lien' => 'https://mesdemarches06.fr' ],
		[ 'title' => 'Urbanisme & PLU',   'cat' => 'Urbanisme',   'couleur' => 'bleu',  'lien' => '' ],
		[ 'title' => 'Scolarité',         'cat' => 'Scolarité',   'couleur' => 'vert',  'lien' => '' ],
		[ 'title' => 'Démarches en ligne','cat' => 'Démarches',   'couleur' => 'or',    'lien' => 'https://mesdemarches06.fr' ],
		[ 'title' => 'Paiement en ligne', 'cat' => 'Démarches',   'couleur' => 'or',    'lien' => '' ],
		[ 'title' => 'Sécurité & Risques','cat' => 'Sécurité',    'couleur' => 'bleu',  'lien' => '' ],
	];
	foreach ( $services as $s ) {
		$term = get_term_by( 'name', $s['cat'], 'categorie_service' );
		$id   = wp_insert_post( [
			'post_title'   => $s['title'],
			'post_content' => '<p>Service municipal : ' . $s['title'] . '</p>',
			'post_status'  => 'publish',
			'post_type'    => 'service',
		] );
		if ( $id ) {
			update_post_meta( $id, '_berre_couleur', $s['couleur'] );
			update_post_meta( $id, '_berre_lien_externe', $s['lien'] );
			if ( $term ) wp_set_post_terms( $id, [ $term->term_id ], 'categorie_service' );
		}
	}

	update_option( 'berre_sample_data_inserted', true );
}
add_action( 'after_switch_theme', 'berre_insert_sample_data' );


/* ═══════════════════════════════════════════
   9. SHORTCODES UTILITAIRES
═══════════════════════════════════════════ */

// [berre_actualites nombre="3"] — liste des dernières actualités
function berre_sc_actualites( $atts ) {
	$atts = shortcode_atts( [ 'nombre' => 3, 'categorie' => '' ], $atts );
	$args = [
		'post_type'      => 'actualite',
		'posts_per_page' => intval( $atts['nombre'] ),
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	];
	if ( $atts['categorie'] ) {
		$args['tax_query'] = [ [ 'taxonomy' => 'categorie_actu', 'field' => 'slug', 'terms' => $atts['categorie'] ] ];
	}
	$q = new WP_Query( $args );
	if ( ! $q->have_posts() ) return '<p>' . __( 'Aucune actualité.', 'theme1-aerien' ) . '</p>';
	ob_start();
	echo '<div class="berre-actualites-grid">';
	while ( $q->have_posts() ) {
		$q->the_post();
		$terms    = get_the_terms( get_the_ID(), 'categorie_actu' );
		$cat_name = $terms ? esc_html( $terms[0]->name ) : '';
		echo '<article class="berre-actu-card">';
		if ( has_post_thumbnail() ) {
			echo '<div class="berre-actu-img">' . get_the_post_thumbnail( null, 'medium_large' ) . '</div>';
		}
		echo '<div class="berre-actu-body">';
		if ( $cat_name ) echo '<span class="berre-cat berre-cat--' . sanitize_html_class( strtolower( $cat_name ) ) . '">' . $cat_name . '</span>';
		echo '<h3 class="berre-actu-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
		echo '<p class="berre-actu-excerpt">' . wp_trim_words( get_the_excerpt(), 20 ) . '</p>';
		echo '<time class="berre-actu-date" datetime="' . get_the_date( 'c' ) . '">' . get_the_date( 'd M Y' ) . '</time>';
		echo '</div></article>';
	}
	echo '</div>';
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'berre_actualites', 'berre_sc_actualites' );

// [berre_agenda nombre="5"] — prochains événements
function berre_sc_agenda( $atts ) {
	$atts = shortcode_atts( [ 'nombre' => 5 ], $atts );
	$args = [
		'post_type'      => 'agenda',
		'posts_per_page' => intval( $atts['nombre'] ),
		'post_status'    => 'publish',
		'meta_key'       => '_berre_date_debut',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => [ [ 'key' => '_berre_date_debut', 'value' => date( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ] ],
	];
	$q = new WP_Query( $args );
	if ( ! $q->have_posts() ) return '<p>' . __( 'Aucun événement à venir.', 'theme1-aerien' ) . '</p>';
	ob_start();
	echo '<div class="berre-agenda-list">';
	while ( $q->have_posts() ) {
		$q->the_post();
		$date  = get_post_meta( get_the_ID(), '_berre_date_debut', true );
		$heure = get_post_meta( get_the_ID(), '_berre_heure_debut', true );
		$lieu  = get_post_meta( get_the_ID(), '_berre_lieu', true );
		$terms = get_the_terms( get_the_ID(), 'categorie_agenda' );
		$cat   = $terms ? esc_html( $terms[0]->name ) : '';
		$ts    = $date ? strtotime( $date ) : 0;
		echo '<div class="berre-agenda-item">';
		echo '<div class="berre-agenda-date">';
		if ( $ts ) {
			echo '<strong>' . date_i18n( 'd', $ts ) . '</strong>';
			echo '<span>' . date_i18n( 'M', $ts ) . '</span>';
		}
		echo '</div>';
		echo '<div class="berre-agenda-info">';
		echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
		$meta = array_filter( [ $lieu, $heure ] );
		if ( $meta ) echo '<p>' . esc_html( implode( ' · ', $meta ) ) . '</p>';
		if ( $cat ) echo '<span class="berre-cat">' . $cat . '</span>';
		echo '</div></div>';
	}
	echo '</div>';
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'berre_agenda', 'berre_sc_agenda' );

// [berre_services nombre="8"] — grille services
function berre_sc_services( $atts ) {
	$atts = shortcode_atts( [ 'nombre' => 8, 'categorie' => '' ], $atts );
	$args = [
		'post_type'      => 'service',
		'posts_per_page' => intval( $atts['nombre'] ),
		'post_status'    => 'publish',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	];
	$q = new WP_Query( $args );
	if ( ! $q->have_posts() ) return '';
	ob_start();
	echo '<div class="berre-services-grid">';
	while ( $q->have_posts() ) {
		$q->the_post();
		$lien    = get_post_meta( get_the_ID(), '_berre_lien_externe', true ) ?: get_permalink();
		$couleur = get_post_meta( get_the_ID(), '_berre_couleur', true ) ?: 'bleu';
		$terms   = get_the_terms( get_the_ID(), 'categorie_service' );
		$cat     = $terms ? esc_html( $terms[0]->name ) : '';
		echo '<a href="' . esc_url( $lien ) . '" class="berre-service-card berre-service-card--' . sanitize_html_class( $couleur ) . '">';
		if ( has_post_thumbnail() ) {
			echo '<div class="berre-service-icon">' . get_the_post_thumbnail( null, 'thumbnail' ) . '</div>';
		}
		echo '<h3>' . get_the_title() . '</h3>';
		echo '<p>' . wp_trim_words( get_the_excerpt(), 12 ) . '</p>';
		echo '<span class="berre-service-link">Accéder →</span>';
		echo '</a>';
	}
	echo '</div>';
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'berre_services', 'berre_sc_services' );


/* ═══════════════════════════════════════════
   10. WIDGET BLOCS GUTENBERG (REST)
═══════════════════════════════════════════ */
function berre_register_blocks() {
	// Bloc dynamique : liste actualités
	register_block_type( 'berre/actualites', [
		'render_callback' => function( $attrs ) {
			return berre_sc_actualites( $attrs );
		},
		'attributes' => [
			'nombre'    => [ 'type' => 'number', 'default' => 3 ],
			'categorie' => [ 'type' => 'string', 'default' => '' ],
		],
	] );

	// Bloc dynamique : agenda
	register_block_type( 'berre/agenda', [
		'render_callback' => function( $attrs ) {
			return berre_sc_agenda( $attrs );
		},
		'attributes' => [
			'nombre' => [ 'type' => 'number', 'default' => 5 ],
		],
	] );
}
add_action( 'init', 'berre_register_blocks' );


/* ═══════════════════════════════════════════
   11. SÉCURITÉ & NETTOYAGE
═══════════════════════════════════════════ */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
add_filter( 'the_generator', '__return_empty_string' );

// Désactiver XML-RPC si non nécessaire
add_filter( 'xmlrpc_enabled', '__return_false' );




/* ============================================================
   ACCÈS RAPIDES — Page d'administration (v2)
   Structure : { primary: [...], secondary: [...] }
   Option WP : berre_acces_rapides
   ============================================================ */

function berre_icons_list() {
    return [
        "document"   => ["label" => "Document / État Civil",    "svg" => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>'],
        "calendar"   => ["label" => "Calendrier / Rendez-vous", "svg" => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
        "computer"   => ["label" => "Ordinateur / Démarches",   "svg" => '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>'],
        "coin"       => ["label" => "Paiement / Finances",      "svg" => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>'],
        "hike"       => ["label" => "Randonnées / Sport",       "svg" => '<path d="M3 17l4-8 4 4 4-7 4 8"/>'],
        "people"     => ["label" => "Personnes / Élus",         "svg" => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>'],
        "building"   => ["label" => "Urbanisme / Bâtiment",     "svg" => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 12h6M9 15h4"/>'],
        "heart"      => ["label" => "Social / Santé",           "svg" => '<path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>'],
        "flag"       => ["label" => "Magazine / Communication", "svg" => '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>'],
        "school"     => ["label" => "Scolarité / Éducation",   "svg" => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>'],
        "info"       => ["label" => "Informations pratiques",   "svg" => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'],
        "phone"      => ["label" => "Contact / Téléphone",      "svg" => '<path d="M22 16.92v3a2 2 0 01-2.18 2A19.79 19.79 0 0112 18.85a19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.13.96.36 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0122 16.92z"/>'],
        "home"       => ["label" => "Mairie / Accueil",         "svg" => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
        "leaf"       => ["label" => "Environnement / Nature",   "svg" => '<path d="M17 8C8 10 5.9 16.17 3.82 19.5M9 19.5c.9-1.32 4.5-4.5 11.5-6.5"/>'],
        "star"       => ["label" => "Événement / Agenda",       "svg" => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>'],
        "truck"      => ["label" => "Transport / Déchets",      "svg" => '<rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>'],
        "shield"     => ["label" => "Sécurité / Protection",    "svg" => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
        "map"        => ["label" => "Carte / Plan",             "svg" => '<polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/>'],
    ];
}

/* ── Données par défaut ── */
function berre_acces_rapides_defaults() {
    return [
        "primary" => [
            ["label" => "État Civil",       "url" => "/etat-civil",              "icon" => "document", "color" => "bleu", "target" => "_self"],
            ["label" => "Rendez-vous",      "url" => "#",                        "icon" => "calendar", "color" => "bleu", "target" => "_self"],
            ["label" => "Mes Démarches 06", "url" => "https://mesdemarches06.fr","icon" => "computer", "color" => "bleu", "target" => "_blank"],
            ["label" => "Paiement en ligne","url" => "#",                        "icon" => "coin",     "color" => "bleu", "target" => "_blank"],
            ["label" => "Randonnées",       "url" => "/randonnees",              "icon" => "hike",     "color" => "vert", "target" => "_self"],
            ["label" => "Magazine",         "url" => "#",                        "icon" => "flag",     "color" => "or",   "target" => "_self"],
        ],
        "secondary" => [
            ["label" => "Urbanisme",        "url" => "/urbanisme",               "icon" => "building", "color" => "bleu", "target" => "_self"],
            ["label" => "Scolarité",        "url" => "/scolarite",               "icon" => "school",   "color" => "bleu", "target" => "_self"],
            ["label" => "Action sociale",   "url" => "/social",                  "icon" => "heart",    "color" => "vert", "target" => "_self"],
            ["label" => "Les élus",         "url" => "/les-elus",                "icon" => "people",   "color" => "bleu", "target" => "_self"],
            ["label" => "Environnement",    "url" => "/environnement",           "icon" => "leaf",     "color" => "vert", "target" => "_self"],
            ["label" => "Infos pratiques",  "url" => "/infos",                   "icon" => "info",     "color" => "or",   "target" => "_self"],
        ],
    ];
}

/* ── Lire les données (avec migration depuis v1) ── */
function berre_get_acces_rapides() {
    $saved = get_option( "berre_acces_rapides" );
    if ( empty( $saved ) ) return berre_acces_rapides_defaults();
    // Migration v1 → v2 : l'ancien format était un tableau plat
    if ( isset( $saved[0] ) && is_array( $saved[0] ) ) {
        return [ "primary" => $saved, "secondary" => [] ];
    }
    if ( ! isset( $saved["primary"] ) ) return berre_acces_rapides_defaults();
    return $saved;
}

/* ── Menu admin ── */
// Accès Rapides menu géré par le menu unifié berre-admin

/* ── Sauvegarde ── */
function berre_save_acces_rapides() {
    if ( ! isset( $_POST["berre_acces_rapides_nonce"] ) ) return;
    if ( ! wp_verify_nonce( $_POST["berre_acces_rapides_nonce"], "berre_save_acces_rapides" ) ) {
        wp_die( "Sécurité : nonce invalide." );
    }
    if ( ! current_user_can( "manage_options" ) ) {
        wp_die( "Permission refusée." );
    }

    $data = [ "primary" => [], "secondary" => [] ];

    foreach ( ["primary", "secondary"] as $group ) {
        $labels  = (array)( $_POST["ar_{$group}_label"]  ?? [] );
        $urls    = (array)( $_POST["ar_{$group}_url"]    ?? [] );
        $icons   = (array)( $_POST["ar_{$group}_icon"]   ?? [] );
        $colors  = (array)( $_POST["ar_{$group}_color"]  ?? [] );
        $targets = (array)( $_POST["ar_{$group}_target"] ?? [] );

        foreach ( $labels as $i => $label ) {
            $label = sanitize_text_field( $label );
            if ( empty( $label ) ) continue;
            $data[$group][] = [
                "label"  => $label,
                "url"    => esc_url_raw( $urls[$i] ?? "#" ),
                "icon"   => sanitize_key( $icons[$i] ?? "document" ),
                "color"  => in_array( $colors[$i] ?? "", ["bleu","vert","or"] ) ? $colors[$i] : "bleu",
                "target" => ( isset( $targets[$i] ) && $targets[$i] === "_blank" ) ? "_blank" : "_self",
            ];
        }
    }

    update_option( "berre_acces_rapides", $data );

    add_action( "admin_notices", function() {
        echo '<div class="notice notice-success is-dismissible"><p>✅ Accès rapides sauvegardés avec succès.</p></div>';
    });
}
add_action( "admin_init", function() {
    if ( isset( $_POST["berre_save_ar"] ) ) berre_save_acces_rapides();
});

/* ── Page d'administration ── */
function berre_admin_page() {
    $data   = berre_get_acces_rapides();
    $icons  = berre_icons_list();
    $colors = ["bleu" => "Bleu #2D6AB0", "vert" => "Vert #587526", "or" => "Or #DEA128"];
    $groups = [
        "primary"   => ["title" => "🔵 Icônes principales",     "desc" => "Affichées immédiatement sous la photo d'accueil."],
        "secondary" => ["title" => "➕ Icônes supplémentaires", "desc" => "Révélées au clic sur \"Voir plus d'accès rapides\"."],
    ];
    ?>
    <style>
    .berre-ar-section { background:#fff; border:1px solid #ddd; border-radius:6px; margin-bottom:28px; overflow:hidden; }
    .berre-ar-section-head { background:#f6f7f7; border-bottom:1px solid #ddd; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; }
    .berre-ar-section-head h2 { margin:0; font-size:15px; }
    .berre-ar-section-head p { margin:4px 0 0; color:#666; font-size:12px; }
    .berre-ar-table { width:100%; border-collapse:collapse; }
    .berre-ar-table th { background:#f9f9f9; padding:8px 10px; text-align:left; font-size:12px; color:#555; border-bottom:1px solid #eee; }
    .berre-ar-table td { padding:7px 10px; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
    .berre-ar-table tr:last-child td { border-bottom:none; }
    .berre-ar-table tr:hover td { background:#fafcff; }
    .berre-drag { cursor:grab; color:#bbb; font-size:20px; user-select:none; }
    .berre-drag:active { cursor:grabbing; }
    .berre-icon-preview { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .berre-icon-preview svg { width:16px; height:16px; fill:none; stroke:white; stroke-width:1.8; }
    .berre-table-wrap { padding:0 18px 14px; }
    .berre-add-btn { margin:8px 18px 16px; }
    .berre-icon-grid { display:flex; flex-wrap:wrap; gap:12px; padding:16px 18px; background:#f9f9f9; border-top:1px solid #eee; }
    .berre-icon-chip { text-align:center; cursor:pointer; padding:8px; border-radius:6px; border:1.5px solid transparent; transition:all .15s; }
    .berre-icon-chip:hover { border-color:#2D6AB0; background:#e8f1fb; }
    .berre-icon-chip span { display:block; font-size:10px; color:#555; margin-top:5px; max-width:70px; line-height:1.2; }
    </style>

    <div class="wrap" style="max-width:960px">
        <h1 style="margin-bottom:6px">🔗 Accès Rapides — Berre-les-Alpes</h1>
        <p style="color:#666;margin-bottom:24px">Gérez les icônes affichées sur la page d'accueil. Glissez ⠿ pour réordonner.</p>

        <form method="post" id="berre-ar-form">
            <?php wp_nonce_field( "berre_save_acces_rapides", "berre_acces_rapides_nonce" ); ?>

            <?php foreach ( $groups as $group => $meta ) : ?>
            <div class="berre-ar-section" id="section-<?php echo $group; ?>">
                <div class="berre-ar-section-head">
                    <div>
                        <h2><?php echo $meta["title"]; ?></h2>
                        <p><?php echo $meta["desc"]; ?></p>
                    </div>
                    <button type="button" class="button button-secondary berre-toggle-icons" data-group="<?php echo $group; ?>">
                        Choisir une icône ▾
                    </button>
                </div>

                <div class="berre-table-wrap">
                    <table class="berre-ar-table">
                        <thead>
                            <tr>
                                <th style="width:30px"></th>
                                <th style="width:36px">Icône</th>
                                <th style="width:160px">Libellé</th>
                                <th>URL</th>
                                <th style="width:140px">Couleur</th>
                                <th style="width:110px">Ouverture</th>
                                <th style="width:50px"></th>
                            </tr>
                        </thead>
                        <tbody class="berre-ar-rows" data-group="<?php echo $group; ?>">
                        <?php foreach ( $data[$group] as $link ) : ?>
                        <?php echo berre_admin_row( $link, $group, $icons, $colors ); ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="berre-add-btn">
                    <button type="button" class="button berre-add-row" data-group="<?php echo $group; ?>">
                        ➕ Ajouter un lien
                    </button>
                </div>

                <!-- Grille icônes (cachée par défaut) -->
                <div class="berre-icon-grid" id="icons-<?php echo $group; ?>" style="display:none">
                    <?php foreach ( $icons as $key => $ic ) : ?>
                    <div class="berre-icon-chip" onclick="addRowWithIcon('<?php echo esc_js($group); ?>','<?php echo esc_js($key); ?>')">
                        <div class="berre-icon-preview" style="background:#2D6AB0;margin:auto">
                            <svg viewBox="0 0 24 24"><?php echo $ic["svg"]; ?></svg>
                        </div>
                        <span><?php echo esc_html( $ic["label"] ); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <div style="display:flex;gap:12px;align-items:center;margin-top:4px">
                <input type="submit" name="berre_save_ar" class="button button-primary button-large" value="💾 Enregistrer tout">
                <span style="color:#666;font-size:12px">Les modifications s'appliquent immédiatement sur le site.</span>
            </div>
        </form>
    </div>

    <script>
    const ICONS = <?php echo json_encode(array_map(fn($k,$v) => ['value'=>$k,'label'=>$v['label'],'svg'=>$v['svg']], array_keys($icons), $icons)); ?>;
    const COLORS = <?php echo json_encode(array_map(fn($k,$v) => ['value'=>$k,'label'=>$v], array_keys($colors), $colors)); ?>;

    function buildRow(group, iconPreset='document', label='', url='') {
        const selIcon  = ICONS.map(o  => `<option value="${o.value}"${o.value===iconPreset?' selected':''}>${o.label}</option>`).join('');
        const selColor = COLORS.map(o => `<option value="${o.value}">${o.label}</option>`).join('');
        const preview  = ICONS.find(o=>o.value===iconPreset)?.svg || '';
        return `<tr class="berre-ar-row" draggable="true">
            <td><span class="berre-drag">⠿</span></td>
            <td><div class="berre-icon-preview berre-icon-preview-cell" style="background:#2D6AB0"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">${preview}</svg></div></td>
            <td><input type="text" name="ar_${group}_label[]" value="${label}" class="regular-text" style="width:100%" placeholder="Libellé" required></td>
            <td><div class="berre-url-picker" data-uid="new-${Date.now()}"><div class="berre-url-type-toggle"><label class="berre-url-toggle-label active"><input type="radio" name="ar_${group}_url_type[]" value="internal" checked class="berre-url-radio"> 🏠 Interne</label><label class="berre-url-toggle-label"><input type="radio" name="ar_${group}_url_type[]" value="external" class="berre-url-radio"> 🌐 Externe</label></div><div class="berre-url-internal"><select name="ar_${group}_url_internal[]" class="berre-url-select"><?php echo implode('', array_map(fn($o) => '<option value="'.esc_attr($o["value"]).'">'.esc_html($o["label"]).'</option>', berre_get_page_options())); ?></select></div><div class="berre-url-external" style="display:none"><input type="url" name="ar_${group}_url_external[]" placeholder="https://..." class="berre-url-input"></div><input type="hidden" name="ar_${group}_url[]" value="/"></div></td>
            <td><select name="ar_${group}_color[]" style="width:100%">${selColor}</select></td>
            <td><select name="ar_${group}_target[]" style="width:100%"><option value="_self">Même page</option><option value="_blank">Nouvel onglet ↗</option></select></td>
            <td><button type="button" class="button berre-del-row" style="color:#c00;padding:2px 8px">✕</button></td>
            <td style="display:none"><select name="ar_${group}_icon[]">${selIcon}</select></td>
        </tr>`;
    }

    function addRowWithIcon(group, icon) {
        const tbody = document.querySelector(`.berre-ar-rows[data-group="${group}"]`);
        tbody.insertAdjacentHTML('beforeend', buildRow(group, icon));
        initRow(tbody.lastElementChild);
        // Mettre à jour la couleur de l'icône preview selon la couleur sélectionnée
        updatePreviewColor(tbody.lastElementChild);
    }

    function initRow(row) {
        // Supprimer
        row.querySelector('.berre-del-row')?.addEventListener('click', function() {
            if (confirm('Supprimer ce lien ?')) row.remove();
        });
        // Sync icône sélectionnée → preview
        const iconSel = row.querySelector('select[name*="_icon"]');
        const preview = row.querySelector('.berre-icon-preview svg');
        if (iconSel && preview) {
            iconSel.addEventListener('change', function() {
                const ic = ICONS.find(o=>o.value===this.value);
                if (ic) preview.innerHTML = ic.svg;
            });
        }
        // Couleur → preview bg
        const colorSel = row.querySelector('select[name*="_color"]');
        const previewDiv = row.querySelector('.berre-icon-preview-cell');
        const colorMap = {bleu:'#2D6AB0',vert:'#587526',or:'#DEA128'};
        if (colorSel && previewDiv) {
            colorSel.addEventListener('change', function() {
                previewDiv.style.background = colorMap[this.value] || '#2D6AB0';
            });
        }
    }

    function updatePreviewColor(row) {
        const sel = row.querySelector('select[name*="_color"]');
        const div = row.querySelector('.berre-icon-preview-cell');
        const colorMap = {bleu:'#2D6AB0',vert:'#587526',or:'#DEA128'};
        if (sel && div) div.style.background = colorMap[sel.value] || '#2D6AB0';
    }

    // Bouton ajouter
    document.querySelectorAll('.berre-add-row').forEach(btn => {
        btn.addEventListener('click', function() {
            addRowWithIcon(this.dataset.group, 'document');
        });
    });

    // Toggle grille icônes
    document.querySelectorAll('.berre-toggle-icons').forEach(btn => {
        btn.addEventListener('click', function() {
            const grid = document.getElementById('icons-' + this.dataset.group);
            const open = grid.style.display !== 'none';
            grid.style.display = open ? 'none' : 'flex';
            this.textContent = open ? 'Choisir une icône ▾' : 'Masquer les icônes ▴';
        });
    });

    // Init rows existants
    document.querySelectorAll('.berre-ar-row').forEach(row => {
        initRow(row);
        // Sync preview bg depuis la couleur existante
        const sel = row.querySelector('select[name*="_color"]');
        const div = row.querySelector('.berre-icon-preview-cell');
        const colorMap = {bleu:'#2D6AB0',vert:'#587526',or:'#DEA128'};
        if (sel && div) div.style.background = colorMap[sel.value] || '#2D6AB0';
    });

    // Drag & drop par tableau séparé
    document.querySelectorAll('.berre-ar-rows').forEach(tbody => {
        let dragging = null;
        tbody.addEventListener('dragstart', e => {
            dragging = e.target.closest('tr');
            if (dragging) { setTimeout(()=>dragging.style.opacity='.4',0); }
        });
        tbody.addEventListener('dragend', e => {
            if (dragging) dragging.style.opacity = '';
            dragging = null;
        });
        tbody.addEventListener('dragover', e => {
            e.preventDefault();
            const row = e.target.closest('tr');
            if (row && row !== dragging && tbody.contains(row)) {
                const rect = row.getBoundingClientRect();
                if (e.clientY < rect.top + rect.height/2) tbody.insertBefore(dragging, row);
                else tbody.insertBefore(dragging, row.nextSibling);
            }
        });
    });
    </script>
    <?php
    do_action( 'berre_after_acces_rapides_form' );
}

/* ── Génère une ligne de tableau (réutilisable) ── */
function berre_admin_row( $link, $group, $icons, $colors ) {
    $icon_key = $link['icon'] ?? 'document';
    $color    = $link['color'] ?? 'bleu';
    $color_hex = ['bleu'=>'#2D6AB0','vert'=>'#587526','or'=>'#DEA128'][$color] ?? '#2D6AB0';
    $svg      = $icons[$icon_key]['svg'] ?? '';
    ob_start(); ?>
    <tr class="berre-ar-row" draggable="true">
        <td><span class="berre-drag">⠿</span></td>
        <td>
            <div class="berre-icon-preview berre-icon-preview-cell" style="background:<?php echo esc_attr($color_hex); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><?php echo $svg; ?></svg>
            </div>
        </td>
        <td><input type="text" name="ar_<?php echo $group; ?>_label[]" value="<?php echo esc_attr($link['label']); ?>" class="regular-text" style="width:100%" required></td>
        <td><?php echo berre_url_picker_html('ar_' . $group . '_url', $link['url'] ?? '#', $i); ?></td>
        <td>
            <select name="ar_<?php echo $group; ?>_color[]" style="width:100%">
                <?php foreach ($colors as $val => $lbl) : ?>
                <option value="<?php echo esc_attr($val); ?>" <?php selected($color,$val); ?>><?php echo esc_html($lbl); ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <select name="ar_<?php echo $group; ?>_target[]" style="width:100%">
                <option value="_self" <?php selected(($link['target']??'_self'),'_self'); ?>>Même page</option>
                <option value="_blank" <?php selected(($link['target']??'_self'),'_blank'); ?>>Nouvel onglet ↗</option>
            </select>
        </td>
        <td><button type="button" class="button berre-del-row" style="color:#c00;padding:2px 8px">✕</button></td>
        <!-- Icône (champ caché — synchro JS) -->
        <td style="display:none">
            <select name="ar_<?php echo $group; ?>_icon[]">
                <?php foreach ($icons as $k => $ic) : ?>
                <option value="<?php echo esc_attr($k); ?>" <?php selected($icon_key,$k); ?>><?php echo esc_html($ic['label']); ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <?php return ob_get_clean();
}

/* ── Shortcode [berre_acces_rapides] ──────────────────────
   Structure HTML avec classes NOUVELLES (berre-ar__*)
   pour éviter tout conflit avec l'ancien cache WordPress.
   Chaque lien = 1 seul <a> contenant cercle + libellé.
   ───────────────────────────────────────────────────── */
function berre_render_acces_rapides( $atts = [] ) {
    $data  = berre_get_acces_rapides();
    $icons = berre_icons_list();
    $html  = '<div class="berre-ar" data-berre-ar="1">';
    $html .= berre_ar_grid( $data['primary'] ?? [], $icons, false );
    if ( ! empty( $data['secondary'] ) ) {
        $html .= berre_ar_grid( $data['secondary'], $icons, true );
    }
    $html .= '</div>';
    return $html;
}
add_shortcode( "berre_acces_rapides", "berre_render_acces_rapides" );

function berre_ar_grid( $links, $icons, $secondary = false ) {
    if ( empty( $links ) ) return '';
    $cls   = $secondary ? 'berre-ar__grid berre-ar__grid--secondary' : 'berre-ar__grid berre-ar__grid--primary';
    $style = $secondary ? ' style="display:none"' : '';
    $html  = '<div class="' . $cls . '"' . $style . '>';
    foreach ( $links as $link ) {
        $icon_key = $link['icon'] ?? 'document';
        $color    = $link['color'] ?? 'bleu';
        $target   = ( ($link['target'] ?? '_self') === '_blank' ) ? ' target="_blank" rel="noopener noreferrer"' : '';
        $svg      = $icons[$icon_key]['svg'] ?? $icons['document']['svg'];
        $html .= '<a href="' . esc_url( $link['url'] ?? '#' ) . '"' . $target . ' class="berre-ar__item">';
        $html .= '<span class="berre-ar__circle berre-ar__circle--' . esc_attr( $color ) . '">';
        $html .= '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white" stroke-width="1.7">' . $svg . '</svg>';
        $html .= '</span>';
        $html .= '<span class="berre-ar__label">' . esc_html( $link['label'] ?? '' ) . '</span>';
        $html .= '</a>';
    }
    $html .= '</div>';
    return $html;
}

function berre_icons_html( $links, $icons, $class = '' ) {
    return berre_ar_grid( $links, $icons, strpos( $class, 'secondary' ) !== false );
}


/* ============================================================
   RÉINITIALISATION DES TEMPLATES FSE
   - Automatique : à chaque changement de version
   - Manuel : bouton dans l'admin
   ============================================================ */

function berre_delete_fse_templates() {
    global $wpdb;

    // Supprimer TOUS les templates/template-parts FSE via SQL direct
    $ids = $wpdb->get_col(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_type IN ('wp_template','wp_template_part')
         AND post_status != 'trash'"
    );

    $count = 0;
    foreach ( (array)$ids as $id ) {
        if ( wp_delete_post( intval($id), true ) ) $count++;
    }

    // Vider tous les caches
    wp_cache_flush();
    if ( function_exists( 'opcache_reset' ) ) opcache_reset();

    return $count;
}

// Auto-reset à chaque changement de version
function berre_reset_templates_on_version_change() {
    $theme_version  = wp_get_theme()->get( "Version" );
    $stored_version = get_option( "berre_fse_template_version", "" );
    if ( $stored_version === $theme_version ) return;
    berre_delete_fse_templates();
    update_option( "berre_fse_template_version", $theme_version );
}
add_action( "init", "berre_reset_templates_on_version_change", 1 );

// Traitement du reset (GET ou POST)
add_action( "admin_init", function() {
    if ( ! isset( $_REQUEST["berre_reset_templates"] ) ) return;
    if ( ! current_user_can( "manage_options" ) ) return;
    if ( ! wp_verify_nonce( $_REQUEST["berre_reset_nonce"] ?? "", "berre_reset_templates" ) ) {
        wp_die( "Sécurité invalide." );
    }
    $n = berre_delete_fse_templates();
    delete_option( "berre_fse_template_version" );
    set_transient( "berre_reset_ok", $n, 30 );
    wp_redirect( admin_url( "admin.php?page=berre-outils" ) );
    exit;
} );

// Notification après reset
add_action( "admin_notices", function() {
    $n = get_transient( "berre_reset_ok" );
    if ( $n === false ) return;
    delete_transient( "berre_reset_ok" );
    echo "<div class='notice notice-success is-dismissible'><p>✅ <strong>" . intval($n) . " modèle(s) FSE supprimé(s).</strong> WordPress utilise maintenant les fichiers du thème à jour.</p></div>";
} );

// Page Outils dans le menu admin
// Submenu : Outils Thème (géré par le menu unifié berre-admin)

function berre_outils_page() {
    $reset_url = wp_nonce_url(
        admin_url( "admin.php?page=berre-outils&berre_reset_templates=1" ),
        "berre_reset_templates",
        "berre_reset_nonce"
    );
    $version = wp_get_theme()->get( "Version" );
    $stored  = get_option( "berre_fse_template_version", "inconnue" );
    ?>
    <div class="wrap">
        <h1>🛠 Outils — Thème Berre-les-Alpes</h1>

        <div style="background:#fff;border:1px solid #ddd;border-radius:6px;padding:24px;max-width:700px;margin-top:20px">
            <h2 style="margin:0 0 8px;font-size:16px">🔄 Réinitialiser les modèles FSE</h2>
            <p style="color:#555;margin:0 0 16px">Si le site n'affiche pas les dernières modifications après une mise à jour du thème, cliquez ici pour forcer WordPress à utiliser les fichiers du thème plutôt que la version en base de données.</p>

            <table style="margin-bottom:16px;font-size:13px">
                <tr><td style="color:#888;padding-right:16px">Version thème installée :</td><td><strong><?php echo esc_html($version); ?></strong></td></tr>
                <tr><td style="color:#888;padding-right:16px">Version cache FSE :</td><td><strong><?php echo esc_html($stored); ?></strong></td></tr>
                <tr><td style="color:#888;padding-right:16px">Statut :</td><td><?php echo $stored === $version ? '<span style="color:#46b450">✓ À jour</span>' : '<span style="color:#dc3232">⚠ Cache obsolète — réinitialisation recommandée</span>'; ?></td></tr>
            </table>

            <a href="<?php echo esc_url($reset_url); ?>"
               class="button button-primary button-large"
               onclick="return confirm('Supprimer les modèles en cache et forcer l'utilisation des fichiers du thème ?')">
                🔄 Réinitialiser les modèles maintenant
            </a>
            <p style="color:#888;font-size:11px;margin-top:8px">Cette opération est sûre et ne supprime aucun contenu. Les modèles sont recréés automatiquement depuis les fichiers du thème.</p>
        </div>

        <div style="background:#fff;border:1px solid #ddd;border-radius:6px;padding:24px;max-width:700px;margin-top:16px">
            <h2 style="margin:0 0 8px;font-size:16px">📋 Informations système</h2>
            <table style="font-size:13px;width:100%">
                <tr><td style="color:#888;padding:4px 16px 4px 0;width:220px">Thème</td><td><?php echo esc_html(wp_get_theme()->get("Name")); ?> v<?php echo esc_html($version); ?></td></tr>
                <tr><td style="color:#888;padding:4px 16px 4px 0">WordPress</td><td><?php global $wp_version; echo esc_html($wp_version); ?></td></tr>
                <tr><td style="color:#888;padding:4px 16px 4px 0">PHP</td><td><?php echo PHP_VERSION; ?></td></tr>
                <tr><td style="color:#888;padding:4px 16px 4px 0">Dossier thème</td><td><code><?php echo esc_html(get_template_directory()); ?></code></td></tr>
            </table>
        </div>
    </div>
    <?php
}


/* ============================================================
   EXPORT / IMPORT — Accès Rapides
   ============================================================ */

/* ── Export JSON ── */
function berre_export_acces_rapides() {
    if ( ! isset( $_GET['berre_export_ar'] ) ) return;
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Permission refusée.' );
    if ( ! wp_verify_nonce( $_GET['berre_export_nonce'] ?? '', 'berre_export_ar' ) ) wp_die( 'Nonce invalide.' );

    $data = berre_get_acces_rapides();
    $json = json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    $filename = 'berre-acces-rapides-' . date( 'Y-m-d' ) . '.json';

    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Content-Length: ' . strlen( $json ) );
    header( 'Cache-Control: no-cache' );
    echo $json;
    exit;
}
add_action( 'admin_init', 'berre_export_acces_rapides' );

/* ── Import JSON ── */
function berre_import_acces_rapides() {
    if ( ! isset( $_POST['berre_import_ar'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['berre_import_nonce'] ?? '', 'berre_import_ar' ) ) {
        wp_die( 'Nonce invalide.' );
    }
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Permission refusée.' );

    $file = $_FILES['berre_import_file'] ?? null;
    if ( ! $file || $file['error'] !== UPLOAD_ERR_OK ) {
        set_transient( 'berre_import_error', 'Erreur lors de l\'upload du fichier.', 30 );
        return;
    }

    $content = file_get_contents( $file['tmp_name'] );
    $data    = json_decode( $content, true );

    if ( json_last_error() !== JSON_ERROR_NONE ) {
        set_transient( 'berre_import_error', 'Fichier JSON invalide : ' . json_last_error_msg(), 30 );
        return;
    }

    // Accepter l'ancien format (tableau plat) ou le nouveau (primary/secondary)
    if ( isset( $data[0] ) ) {
        $data = [ 'primary' => $data, 'secondary' => [] ];
    }

    if ( ! isset( $data['primary'] ) ) {
        set_transient( 'berre_import_error', 'Format non reconnu. Le fichier doit contenir les clés "primary" et "secondary".', 30 );
        return;
    }

    // Nettoyer chaque lien
    foreach ( [ 'primary', 'secondary' ] as $group ) {
        $data[$group] = array_map( function( $link ) {
            return [
                'label'  => sanitize_text_field( $link['label']  ?? '' ),
                'url'    => esc_url_raw( $link['url']    ?? '#' ),
                'icon'   => sanitize_key( $link['icon']   ?? 'document' ),
                'color'  => in_array( $link['color'] ?? '', ['bleu','vert','or'] ) ? $link['color'] : 'bleu',
                'target' => ( ($link['target'] ?? '_self') === '_blank' ) ? '_blank' : '_self',
            ];
        }, $data[$group] ?? [] );
    }

    update_option( 'berre_acces_rapides', $data );
    set_transient( 'berre_import_success', count( $data['primary'] ) + count( $data['secondary'] ) . ' lien(s) importé(s) avec succès.', 30 );
}
add_action( 'admin_init', 'berre_import_acces_rapides' );

/* ── Section Export/Import dans la page admin ──
   On ajoute un filtre pour afficher la section dans berre_admin_page()
   en utilisant un hook personnalisé */
add_action( 'berre_after_acces_rapides_form', function() {
    $export_url = wp_nonce_url(
        add_query_arg( 'berre_export_ar', '1', admin_url( 'admin.php?page=berre-acces-rapides' ) ),
        'berre_export_ar',
        'berre_export_nonce'
    );
    $import_err     = get_transient( 'berre_import_error' );
    $import_success = get_transient( 'berre_import_success' );
    if ( $import_err )     delete_transient( 'berre_import_error' );
    if ( $import_success ) delete_transient( 'berre_import_success' );
    ?>
    <div style="margin-top:32px;padding:20px 24px;background:#fff;border:1px solid #ddd;border-radius:6px;max-width:960px">
        <h2 style="margin:0 0 6px;font-size:15px">📦 Export / Import</h2>
        <p style="color:#666;font-size:12px;margin:0 0 18px">Sauvegardez vos liens dans un fichier JSON pour les migrer ou les archiver.</p>

        <?php if ( $import_err ) : ?>
        <div class="notice notice-error inline" style="margin:0 0 14px"><p>❌ <?php echo esc_html( $import_err ); ?></p></div>
        <?php endif; ?>
        <?php if ( $import_success ) : ?>
        <div class="notice notice-success inline" style="margin:0 0 14px"><p>✅ <?php echo esc_html( $import_success ); ?></p></div>
        <?php endif; ?>

        <div style="display:flex;gap:32px;flex-wrap:wrap;align-items:flex-start">

            <!-- Export -->
            <div style="flex:1;min-width:200px">
                <h3 style="font-size:13px;margin:0 0 8px">⬇ Exporter</h3>
                <p style="font-size:12px;color:#666;margin:0 0 10px">Télécharge un fichier <code>.json</code> contenant tous vos liens principaux et supplémentaires.</p>
                <a href="<?php echo esc_url( $export_url ); ?>" class="button button-secondary">
                    ⬇ Télécharger acces-rapides.json
                </a>
            </div>

            <!-- Import -->
            <div style="flex:1;min-width:200px">
                <h3 style="font-size:13px;margin:0 0 8px">⬆ Importer</h3>
                <p style="font-size:12px;color:#666;margin:0 0 10px">Importe un fichier <code>.json</code> exporté depuis ce thème. <strong>Remplace les liens actuels.</strong></p>
                <form method="post" enctype="multipart/form-data" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                    <?php wp_nonce_field( 'berre_import_ar', 'berre_import_nonce' ); ?>
                    <input type="file" name="berre_import_file" accept=".json,application/json"
                           style="font-size:12px;border:1px solid #ddd;padding:5px 8px;border-radius:4px;background:#fafafa">
                    <button type="submit" name="berre_import_ar" value="1" class="button button-primary"
                            onclick="return confirm('Remplacer tous les liens actuels par ceux du fichier ?')">
                        ⬆ Importer
                    </button>
                </form>
            </div>

        </div>
    </div>
    <?php
} );


/* ============================================================
   ÉDITEUR DE PAGE — Contenu dynamique de l'accueil
   Option : berre_page_content
   Admin  : Tableau de bord → Éditeur de page
   ============================================================ */

function berre_page_content_defaults() {
    return [
        'hero' => [
            'image_url' => '',
            'image_id'  => 0,
            'btn1_text' => 'Vos démarches en ligne',
            'btn1_url'  => 'https://mesdemarches06.fr',
            'btn2_text' => 'Découvrir la commune',
            'btn2_url'  => '/decouvrir',
        ],
        'commune' => [
            'eyebrow'     => 'Découvrir la commune',
            'title'       => 'Un village d\'exception entre mer et montagne',
            'description' => 'Perché à 682 m à 25 km de Nice, Berre-les-Alpes offre un panorama unique sur la Méditerranée et les Alpes-Maritimes. Village médiéval, sentiers balisés et art de vivre provençal.',
            'stat1_val'   => '1 234',  'stat1_lbl' => 'Habitants',
            'stat2_val'   => '682 m',  'stat2_lbl' => 'Altitude',
            'stat3_val'   => '25 km',  'stat3_lbl' => 'De Nice',
            'stat4_val'   => '9,58 km²', 'stat4_lbl' => 'Superficie',
            'btn_text'    => 'Explorer nos sentiers →',
            'btn_url'     => '/decouvrir',
        ],
        'services' => [
            ['title'=>'État Civil',       'desc'=>'Actes, naissances, mariages, décès.', 'url'=>'/etat-civil',  'icon'=>'document', 'color'=>'bleu'],
            ['title'=>'Urbanisme',        'desc'=>'Permis de construire, PLU.',          'url'=>'/urbanisme',   'icon'=>'building', 'color'=>'bleu'],
            ['title'=>'Scolarité',        'desc'=>'Inscriptions, cantine, garderie.',    'url'=>'/scolarite',   'icon'=>'school',   'color'=>'vert'],
            ['title'=>'Démarches en ligne','desc'=>'Mes Démarches 06 — 24h/24.',        'url'=>'https://mesdemarches06.fr','icon'=>'computer','color'=>'or'],
            ['title'=>'Sécurité & Risques','desc'=>'Gendarmerie, plan communal.',       'url'=>'/securite',    'icon'=>'shield',   'color'=>'bleu'],
            ['title'=>'Qualité de vie',   'desc'=>'Environnement, tri sélectif.',        'url'=>'/qualite-vie', 'icon'=>'leaf',     'color'=>'vert'],
            ['title'=>'Finances publiques','desc'=>'Budget, marchés publics.',           'url'=>'/finances',    'icon'=>'coin',     'color'=>'or'],
            ['title'=>'Infos pratiques',  'desc'=>'ANAH, SPANC, juridique.',             'url'=>'/infos',       'icon'=>'info',     'color'=>'bleu'],
        ],
        'contact' => [
            'address'       => 'Place de la Mairie',
            'city'          => '06390 Berre-les-Alpes',
            'dept'          => 'Alpes-Maritimes (06)',
            'phone'         => '04 93 91 80 07',
            'fax'           => '04 93 91 85 44',
            'email'         => 'mairie@berrelesalpes.fr',
            'telealerte_url'=> 'https://www.acces-gedicom.com/Subscriptions/index.jsp?CustId=582',
            'hours'         => [
                ['day'=>'Lundi',           'h'=>'9h–12h / 14h–17h30', 'off'=>false],
                ['day'=>'Mardi',           'h'=>'9h–12h / 14h–17h30', 'off'=>false],
                ['day'=>'Mercredi',        'h'=>'Fermé',               'off'=>true],
                ['day'=>'Jeudi',           'h'=>'9h–12h / 14h–17h30', 'off'=>false],
                ['day'=>'Vendredi',        'h'=>'9h–12h / 14h–17h30', 'off'=>false],
                ['day'=>'Sam – Dim',       'h'=>'Fermé',               'off'=>true],
            ],
        ],
        'newsletter' => [
            'title' => 'La newsletter de Berre-les-Alpes',
            'desc'  => 'Chaque semaine, toute l\'actualité communale dans votre boîte mail.',
        ],
        'footer' => [
            'description'   => 'La commune de Berre-les-Alpes, village perché à 682 m d\'altitude en Alpes-Maritimes.',
            'facebook_url'  => 'https://www.facebook.com',
            'youtube_url'   => 'https://www.youtube.com',
        ],
    ];
}

function berre_get_page_content() {
    $saved = get_option( 'berre_page_content' );
    $defaults = berre_page_content_defaults();
    if ( empty( $saved ) ) return $defaults;
    // Fusion récursive pour ne pas perdre les nouvelles clés
    return array_replace_recursive( $defaults, $saved );
}

/* ── Menu admin ── */
// Éditeur de page géré par le menu unifié berre-admin

/* ── Sauvegarde ── */
add_action( 'admin_init', function() {
    if ( ! isset( $_POST['berre_save_page_content'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['berre_page_nonce'] ?? '', 'berre_save_page_content' ) ) wp_die('Nonce invalide.');
    if ( ! current_user_can( 'manage_options' ) ) wp_die('Permission refusée.');

    $d = berre_page_content_defaults();

    // Hero
    $d['hero']['image_url']  = esc_url_raw( $_POST['hero_image_url'] ?? '' );
    $d['hero']['image_id']   = intval( $_POST['hero_image_id'] ?? 0 );
    $d['hero']['btn1_text']  = sanitize_text_field( $_POST['hero_btn1_text'] ?? '' );
    $d['hero']['btn1_url']   = esc_url_raw( $_POST['hero_btn1_url'] ?? '' );
    $d['hero']['btn2_text']  = sanitize_text_field( $_POST['hero_btn2_text'] ?? '' );
    $d['hero']['btn2_url']   = esc_url_raw( $_POST['hero_btn2_url'] ?? '' );

    // Commune
    foreach (['eyebrow','title','description','stat1_val','stat1_lbl','stat2_val','stat2_lbl','stat3_val','stat3_lbl','stat4_val','stat4_lbl','btn_text','btn_url'] as $k) {
        $d['commune'][$k] = sanitize_text_field( $_POST['commune_'.$k] ?? '' );
    }
    $d['commune']['description'] = sanitize_textarea_field( $_POST['commune_description'] ?? '' );

    // Services
    $d['services'] = [];
    $s_titles  = (array)($_POST['svc_title']  ?? []);
    $s_descs   = (array)($_POST['svc_desc']   ?? []);
    $s_urls    = (array)($_POST['svc_url']    ?? []);
    $s_icons   = (array)($_POST['svc_icon']   ?? []);
    $s_colors  = (array)($_POST['svc_color']  ?? []);
    foreach ($s_titles as $i => $t) {
        if (empty(trim($t))) continue;
        $d['services'][] = [
            'title' => sanitize_text_field($t),
            'desc'  => sanitize_text_field($s_descs[$i] ?? ''),
            'url'   => esc_url_raw($s_urls[$i] ?? '#'),
            'icon'  => sanitize_key($s_icons[$i] ?? 'document'),
            'color' => in_array($s_colors[$i]??'',['bleu','vert','or']) ? $s_colors[$i] : 'bleu',
        ];
    }

    // Contact
    foreach (['address','city','dept','phone','fax','email','telealerte_url'] as $k) {
        $fn = ($k === 'telealerte_url') ? 'esc_url_raw' : 'sanitize_text_field';
        $d['contact'][$k] = $fn($_POST['contact_'.$k] ?? '');
    }
    $d['contact']['hours'] = [];
    $h_days = (array)($_POST['hour_day'] ?? []);
    $h_hrs  = (array)($_POST['hour_h']   ?? []);
    $h_offs = (array)($_POST['hour_off'] ?? []);
    foreach ($h_days as $i => $day) {
        if (empty(trim($day))) continue;
        $d['contact']['hours'][] = [
            'day' => sanitize_text_field($day),
            'h'   => sanitize_text_field($h_hrs[$i] ?? ''),
            'off' => isset($h_offs[$i]),
        ];
    }

    // Newsletter
    $d['newsletter']['title'] = sanitize_text_field($_POST['nl_title'] ?? '');
    $d['newsletter']['desc']  = sanitize_textarea_field($_POST['nl_desc'] ?? '');

    // Footer
    $d['footer']['description'] = sanitize_textarea_field($_POST['footer_description'] ?? '');
    $d['footer']['facebook_url']= esc_url_raw($_POST['footer_facebook_url'] ?? '');
    $d['footer']['youtube_url'] = esc_url_raw($_POST['footer_youtube_url'] ?? '');

    update_option('berre_page_content', $d);
    set_transient('berre_page_saved', true, 10);
});

/* ── Page admin ── */
function berre_page_editor_page() {
    $c    = berre_get_page_content();
    $ic   = berre_icons_list();
    $saved = get_transient('berre_page_saved');
    if ($saved) delete_transient('berre_page_saved');
    $colors_map = ['bleu'=>'#2D6AB0','vert'=>'#587526','or'=>'#DEA128'];
    ?>
    <!DOCTYPE html>
    <style>
    #berre-editor-wrap { display:flex; height:calc(100vh - 32px); overflow:hidden; font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; }
    #berre-editor-left { width:420px; min-width:320px; flex-shrink:0; overflow-y:auto; background:#fff; border-right:1px solid #ddd; display:flex; flex-direction:column; }
    #berre-editor-right { flex:1; overflow:hidden; background:#f0f2f5; position:relative; }
    #berre-preview-frame { width:100%; height:100%; border:none; }
    /* Tabs */
    .berre-tabs { display:flex; overflow-x:auto; border-bottom:2px solid #e0e6ef; background:#f9f9f9; flex-shrink:0; }
    .berre-tab { padding:12px 16px; font-size:12px; font-weight:600; cursor:pointer; white-space:nowrap; border-bottom:2px solid transparent; margin-bottom:-2px; color:#666; transition:all .15s; }
    .berre-tab.active { border-bottom-color:#2D6AB0; color:#2D6AB0; background:#fff; }
    .berre-tab:hover { color:#2D6AB0; }
    /* Header editor */
    .berre-editor-header { padding:14px 18px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; flex-shrink:0; background:#fff; }
    .berre-editor-header h1 { margin:0; font-size:15px; }
    /* Panels */
    .berre-panel { display:none; padding:18px; }
    .berre-panel.active { display:block; }
    /* Fields */
    .berre-field { margin-bottom:16px; }
    .berre-field label { display:block; font-size:12px; font-weight:600; color:#333; margin-bottom:5px; }
    .berre-field input[type=text], .berre-field input[type=url], .berre-field textarea, .berre-field select { width:100%; padding:7px 10px; border:1px solid #ddd; border-radius:4px; font-size:13px; font-family:inherit; }
    .berre-field textarea { resize:vertical; min-height:80px; }
    .berre-field input:focus, .berre-field textarea:focus, .berre-field select:focus { border-color:#2D6AB0; outline:none; box-shadow:0 0 0 2px rgba(45,106,176,.15); }
    .berre-field .hint { font-size:11px; color:#888; margin-top:3px; }
    .berre-section-title { font-size:11px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#999; margin:20px 0 10px; padding-bottom:6px; border-bottom:1px solid #f0f0f0; }
    .berre-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    /* Stats */
    .berre-stat-row { display:grid; grid-template-columns:1fr 1fr; gap:8px; background:#f9f9f9; border:1px solid #eee; border-radius:5px; padding:10px; margin-bottom:8px; }
    /* Services */
    .berre-svc-row { background:#f9f9f9; border:1px solid #e8e8e8; border-radius:5px; padding:10px; margin-bottom:8px; position:relative; }
    .berre-svc-row .berre-del { position:absolute; top:8px; right:8px; background:none; border:none; color:#cc0000; cursor:pointer; font-size:16px; }
    /* Hours */
    .berre-hour-row { display:grid; grid-template-columns:100px 1fr auto; gap:8px; align-items:center; margin-bottom:6px; }
    .berre-hour-row input[type=text] { padding:5px 8px; }
    /* Preview */
    #berre-editor-right .preview-label { position:absolute; top:10px; left:50%; transform:translateX(-50%); background:rgba(0,0,0,.6); color:#fff; font-size:11px; padding:4px 12px; border-radius:10px; pointer-events:none; z-index:10; }
    /* Image picker */
    .berre-img-preview { width:100%; max-height:120px; object-fit:cover; border-radius:5px; margin-bottom:8px; display:none; }
    .berre-img-preview.has-img { display:block; }
    </style>

    <div id="berre-editor-wrap">

    <!-- COLONNE GAUCHE : Formulaire -->
    <div id="berre-editor-left">
        <div class="berre-editor-header">
            <h1>✏️ Éditeur de page</h1>
            <?php if ($saved): ?>
            <span style="color:#46b450;font-size:12px;font-weight:600">✓ Sauvegardé</span>
            <?php endif; ?>
        </div>

        <div class="berre-tabs">
            <div class="berre-tab active" data-panel="hero">🖼 Hero</div>
            <div class="berre-tab" data-panel="commune">🏘 Commune</div>
            <div class="berre-tab" data-panel="services">⚙️ Services</div>
            <div class="berre-tab" data-panel="contact">📍 Contact</div>
            <div class="berre-tab" data-panel="newsletter">📧 Newsletter</div>
            <div class="berre-tab" data-panel="footer">🔗 Footer</div>
        </div>

        <form method="post" id="berre-page-form" style="flex:1;overflow-y:auto">
            <?php wp_nonce_field('berre_save_page_content','berre_page_nonce'); ?>

            <!-- ══ HERO ══ -->
            <div class="berre-panel active" id="panel-hero">
                <p class="berre-section-title">Photo de fond</p>
                <div class="berre-field">
                    <label>Image</label>
                    <?php if (!empty($c['hero']['image_url'])): ?>
                    <img src="<?php echo esc_url($c['hero']['image_url']); ?>" class="berre-img-preview has-img" id="hero-img-preview">
                    <?php else: ?>
                    <img src="" class="berre-img-preview" id="hero-img-preview">
                    <?php endif; ?>
                    <input type="hidden" name="hero_image_id"  id="hero_image_id"  value="<?php echo intval($c['hero']['image_id']); ?>">
                    <input type="hidden" name="hero_image_url" id="hero_image_url" value="<?php echo esc_attr($c['hero']['image_url']); ?>">
                    <button type="button" id="berre-pick-hero-img" class="button button-secondary" style="width:100%">
                        📷 Choisir une photo depuis la médiathèque
                    </button>
                    <?php if (!empty($c['hero']['image_url'])): ?>
                    <button type="button" id="berre-clear-hero-img" class="button" style="width:100%;margin-top:4px;color:#c00">✕ Supprimer la photo</button>
                    <?php endif; ?>
                    <p class="hint">Format recommandé : 1600×900px minimum, paysage.</p>
                </div>
                <p class="berre-section-title">Boutons d'appel à l'action</p>
                <div class="berre-field"><label>Bouton principal — texte</label><input type="text" name="hero_btn1_text" value="<?php echo esc_attr($c['hero']['btn1_text']); ?>" data-preview="hero-btn1-text"></div>
                <div class="berre-field"><label>Bouton principal — URL</label><input type="url" name="hero_btn1_url" value="<?php echo esc_attr($c['hero']['btn1_url']); ?>"></div>
                <div class="berre-field"><label>Bouton secondaire — texte</label><input type="text" name="hero_btn2_text" value="<?php echo esc_attr($c['hero']['btn2_text']); ?>" data-preview="hero-btn2-text"></div>
                <div class="berre-field"><label>Bouton secondaire — URL</label><input type="url" name="hero_btn2_url" value="<?php echo esc_attr($c['hero']['btn2_url']); ?>"></div>
            </div>

            <!-- ══ COMMUNE ══ -->
            <div class="berre-panel" id="panel-commune">
                <div class="berre-field"><label>Surtitre</label><input type="text" name="commune_eyebrow" value="<?php echo esc_attr($c['commune']['eyebrow']); ?>" data-preview="commune-eyebrow"></div>
                <div class="berre-field"><label>Titre principal</label><input type="text" name="commune_title" value="<?php echo esc_attr($c['commune']['title']); ?>" data-preview="commune-title"></div>
                <div class="berre-field"><label>Description</label><textarea name="commune_description" data-preview="commune-desc"><?php echo esc_textarea($c['commune']['description']); ?></textarea></div>
                <p class="berre-section-title">Chiffres clés</p>
                <?php foreach ([1,2,3,4] as $n): ?>
                <div class="berre-stat-row">
                    <div class="berre-field" style="margin:0"><label>Valeur <?php echo $n; ?></label><input type="text" name="commune_stat<?php echo $n; ?>_val" value="<?php echo esc_attr($c['commune']["stat{$n}_val"]); ?>" data-preview="stat<?php echo $n; ?>-val"></div>
                    <div class="berre-field" style="margin:0"><label>Libellé <?php echo $n; ?></label><input type="text" name="commune_stat<?php echo $n; ?>_lbl" value="<?php echo esc_attr($c['commune']["stat{$n}_lbl"]); ?>" data-preview="stat<?php echo $n; ?>-lbl"></div>
                </div>
                <?php endforeach; ?>
                <p class="berre-section-title">Bouton</p>
                <div class="berre-row">
                    <div class="berre-field"><label>Texte</label><input type="text" name="commune_btn_text" value="<?php echo esc_attr($c['commune']['btn_text']); ?>" data-preview="commune-btn"></div>
                    <div class="berre-field"><label>URL</label><input type="url" name="commune_btn_url" value="<?php echo esc_attr($c['commune']['btn_url']); ?>"></div>
                </div>
            </div>

            <!-- ══ SERVICES ══ -->
            <div class="berre-panel" id="panel-services">
                <p style="font-size:12px;color:#666;margin-bottom:12px">Glissez ⠿ pour réordonner. Maximum 8 services affichés.</p>
                <div id="berre-services-list">
                <?php foreach ($c['services'] as $i => $svc): $ch = $colors_map[$svc['color']] ?? '#2D6AB0'; ?>
                <div class="berre-svc-row" draggable="true">
                    <button type="button" class="berre-del" onclick="this.closest('.berre-svc-row').remove()">✕</button>
                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
                        <span style="cursor:grab;color:#bbb;font-size:18px">⠿</span>
                        <div style="width:32px;height:32px;border-radius:50%;background:<?php echo $ch; ?>;flex-shrink:0;display:flex;align-items:center;justify-content:center">
                            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" width="15" height="15"><?php echo $ic[$svc['icon']]['svg'] ?? ''; ?></svg>
                        </div>
                        <strong style="font-size:13px"><?php echo esc_html($svc['title']); ?></strong>
                    </div>
                    <div class="berre-row">
                        <div class="berre-field" style="margin:0"><label>Titre</label><input type="text" name="svc_title[]" value="<?php echo esc_attr($svc['title']); ?>"></div>
                        <div class="berre-field" style="margin:0"><label>Couleur</label>
                            <select name="svc_color[]">
                                <option value="bleu" <?php selected($svc['color'],'bleu'); ?>>Bleu</option>
                                <option value="vert" <?php selected($svc['color'],'vert'); ?>>Vert</option>
                                <option value="or"   <?php selected($svc['color'],'or'); ?>>Or</option>
                            </select>
                        </div>
                    </div>
                    <div class="berre-field" style="margin:4px 0"><label>Description courte</label><input type="text" name="svc_desc[]" value="<?php echo esc_attr($svc['desc']); ?>"></div>
                    <div class="berre-row">
                        <div class="berre-field" style="margin:0"><label>URL</label><input type="url" name="svc_url[]" value="<?php echo esc_attr($svc['url']); ?>"></div>
                        <div class="berre-field" style="margin:0"><label>Icône</label>
                            <select name="svc_icon[]">
                                <?php foreach ($ic as $k=>$v): ?>
                                <option value="<?php echo $k; ?>" <?php selected($svc['icon'],$k); ?>><?php echo esc_html($v['label']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                </div>
                <button type="button" id="berre-add-svc" class="button" style="width:100%;margin-top:6px">➕ Ajouter un service</button>
            </div>

            <!-- ══ CONTACT ══ -->
            <div class="berre-panel" id="panel-contact">
                <p class="berre-section-title">Coordonnées</p>
                <div class="berre-field"><label>Adresse</label><input type="text" name="contact_address" value="<?php echo esc_attr($c['contact']['address']); ?>" data-preview="contact-address"></div>
                <div class="berre-row">
                    <div class="berre-field"><label>Code postal + Ville</label><input type="text" name="contact_city" value="<?php echo esc_attr($c['contact']['city']); ?>" data-preview="contact-city"></div>
                    <div class="berre-field"><label>Département</label><input type="text" name="contact_dept" value="<?php echo esc_attr($c['contact']['dept']); ?>"></div>
                </div>
                <div class="berre-row">
                    <div class="berre-field"><label>Téléphone</label><input type="text" name="contact_phone" value="<?php echo esc_attr($c['contact']['phone']); ?>" data-preview="contact-phone"></div>
                    <div class="berre-field"><label>Fax</label><input type="text" name="contact_fax" value="<?php echo esc_attr($c['contact']['fax']); ?>"></div>
                </div>
                <div class="berre-field"><label>Email</label><input type="text" name="contact_email" value="<?php echo esc_attr($c['contact']['email']); ?>" data-preview="contact-email"></div>
                <div class="berre-field"><label>URL TéléAlerte SMIAGE</label><input type="url" name="contact_telealerte_url" value="<?php echo esc_attr($c['contact']['telealerte_url']); ?>"></div>
                <p class="berre-section-title">Horaires d'ouverture</p>
                <div id="berre-hours-list">
                <?php foreach ($c['contact']['hours'] as $h): ?>
                <div class="berre-hour-row">
                    <input type="text" name="hour_day[]" value="<?php echo esc_attr($h['day']); ?>" placeholder="Jour">
                    <input type="text" name="hour_h[]"   value="<?php echo esc_attr($h['h']); ?>"   placeholder="Horaires">
                    <label style="white-space:nowrap;font-size:12px"><input type="checkbox" name="hour_off[]" value="1" <?php checked($h['off'],true); ?>> Fermé</label>
                </div>
                <?php endforeach; ?>
                </div>
                <button type="button" id="berre-add-hour" class="button" style="margin-top:4px">➕ Ajouter un jour</button>
            </div>

            <!-- ══ NEWSLETTER ══ -->
            <div class="berre-panel" id="panel-newsletter">
                <div class="berre-field"><label>Titre</label><input type="text" name="nl_title" value="<?php echo esc_attr($c['newsletter']['title']); ?>" data-preview="nl-title"></div>
                <div class="berre-field"><label>Description</label><textarea name="nl_desc" data-preview="nl-desc"><?php echo esc_textarea($c['newsletter']['desc']); ?></textarea></div>
                <div style="background:#587526;border-radius:8px;padding:16px;color:#fff;margin-top:12px">
                    <div id="nl-preview-title" style="font-size:15px;font-weight:800;margin-bottom:4px"><?php echo esc_html($c['newsletter']['title']); ?></div>
                    <div id="nl-preview-desc" style="font-size:12px;opacity:.7"><?php echo esc_html($c['newsletter']['desc']); ?></div>
                </div>
            </div>

            <!-- ══ FOOTER ══ -->
            <div class="berre-panel" id="panel-footer">
                <div class="berre-field"><label>Description</label><textarea name="footer_description" data-preview="footer-desc"><?php echo esc_textarea($c['footer']['description']); ?></textarea></div>
                <p class="berre-section-title">Réseaux sociaux</p>
                <div class="berre-field"><label>Facebook — URL</label><input type="url" name="footer_facebook_url" value="<?php echo esc_attr($c['footer']['facebook_url']); ?>" placeholder="https://www.facebook.com/..."></div>
                <div class="berre-field"><label>YouTube — URL</label><input type="url" name="footer_youtube_url" value="<?php echo esc_attr($c['footer']['youtube_url']); ?>" placeholder="https://www.youtube.com/..."></div>
            </div>

            <!-- Bouton save fixe en bas -->
            <div style="position:sticky;bottom:0;background:#fff;border-top:1px solid #ddd;padding:12px 18px;display:flex;gap:8px;align-items:center">
                <input type="submit" name="berre_save_page_content" class="button button-primary button-large" value="💾 Enregistrer">
                <span style="font-size:11px;color:#888">Les modifications s'appliquent immédiatement sur le site.</span>
            </div>
        </form>
    </div>

    <!-- COLONNE DROITE : Aperçu -->
    <div id="berre-editor-right">
        <div class="preview-label">Aperçu en direct</div>
        <iframe id="berre-preview-frame"
            src="<?php echo home_url('/?berre_editor_preview=1'); ?>"
            title="Aperçu de la page d'accueil">
        </iframe>
    </div>

    </div><!-- #berre-editor-wrap -->

    <script>
    (function($) {
        // Tabs
        document.querySelectorAll('.berre-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.berre-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.berre-panel').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('panel-' + this.dataset.panel).classList.add('active');
            });
        });

        // Preview live — mise à jour de l'iframe via postMessage
        var previewFrame = document.getElementById('berre-preview-frame');
        function sendToPreview(key, value) {
            if (previewFrame && previewFrame.contentWindow) {
                previewFrame.contentWindow.postMessage({ type:'berre_preview_update', key:key, value:value }, '*');
            }
        }

        // Écouter tous les champs avec data-preview
        document.querySelectorAll('[data-preview]').forEach(function(el) {
            ['input','keyup','change'].forEach(function(evt) {
                el.addEventListener(evt, function() {
                    sendToPreview(this.dataset.preview, this.value);
                    // Mini aperçu newsletter en inline
                    if (this.dataset.preview === 'nl-title') document.getElementById('nl-preview-title').textContent = this.value;
                    if (this.dataset.preview === 'nl-desc')  document.getElementById('nl-preview-desc').textContent  = this.value;
                });
            });
        });

        // Recharger l'aperçu lors de la sauvegarde
        document.getElementById('berre-page-form').addEventListener('submit', function() {
            setTimeout(function() {
                previewFrame.src = previewFrame.src;
            }, 800);
        });

        // Media picker — Hero image
        document.getElementById('berre-pick-hero-img')?.addEventListener('click', function() {
            if (typeof wp === 'undefined' || !wp.media) {
                alert('Le module médias WordPress n\'est pas disponible.');
                return;
            }
            var frame = wp.media({ title:'Choisir la photo hero', button:{ text:'Utiliser cette photo' }, multiple:false });
            frame.on('select', function() {
                var att = frame.state().get('selection').first().toJSON();
                document.getElementById('hero_image_id').value  = att.id;
                document.getElementById('hero_image_url').value = att.url;
                var prev = document.getElementById('hero-img-preview');
                prev.src = att.url;
                prev.classList.add('has-img');
                sendToPreview('hero-image', att.url);
            });
            frame.open();
        });

        document.getElementById('berre-clear-hero-img')?.addEventListener('click', function() {
            document.getElementById('hero_image_id').value  = '';
            document.getElementById('hero_image_url').value = '';
            var prev = document.getElementById('hero-img-preview');
            prev.src = '';
            prev.classList.remove('has-img');
            sendToPreview('hero-image', '');
        });

        // Drag & drop services
        (function() {
            var list = document.getElementById('berre-services-list');
            if (!list) return;
            var dragging = null;
            list.addEventListener('dragstart', function(e) {
                dragging = e.target.closest('.berre-svc-row');
                if (dragging) setTimeout(() => dragging.style.opacity = '.4', 0);
            });
            list.addEventListener('dragend', function() {
                if (dragging) dragging.style.opacity = '';
                dragging = null;
            });
            list.addEventListener('dragover', function(e) {
                e.preventDefault();
                var row = e.target.closest('.berre-svc-row');
                if (row && row !== dragging) {
                    var r = row.getBoundingClientRect();
                    list.insertBefore(dragging, e.clientY < r.top + r.height/2 ? row : row.nextSibling);
                }
            });
        })();

        // Ajouter un service
        document.getElementById('berre-add-svc')?.addEventListener('click', function() {
            var html = `<div class="berre-svc-row" draggable="true">
                <button type="button" class="berre-del" onclick="this.closest('.berre-svc-row').remove()">✕</button>
                <div class="berre-row">
                    <div class="berre-field" style="margin:0"><label>Titre</label><input type="text" name="svc_title[]" placeholder="Titre du service"></div>
                    <div class="berre-field" style="margin:0"><label>Couleur</label><select name="svc_color[]"><option value="bleu">Bleu</option><option value="vert">Vert</option><option value="or">Or</option></select></div>
                </div>
                <div class="berre-field" style="margin:4px 0"><label>Description</label><input type="text" name="svc_desc[]" placeholder="Courte description"></div>
                <div class="berre-row">
                    <div class="berre-field" style="margin:0"><label>URL</label><div class="berre-url-picker" style="margin-top:4px"><div class="berre-url-type-toggle"><label class="berre-url-toggle-label active"><input type="radio" name="svc_url_type[]" value="internal" checked class="berre-url-radio"> 🏠 Interne</label><label class="berre-url-toggle-label"><input type="radio" name="svc_url_type[]" value="external" class="berre-url-radio"> 🌐 Externe</label></div><div class="berre-url-internal"><select name="svc_url_internal[]" class="berre-url-select"><?php echo implode('', array_map(fn($o)=>'<option value="'.esc_attr($o["value"]).'">'.esc_html($o["label"]).'</option>', berre_get_page_options())); ?></select></div><div class="berre-url-external" style="display:none"><input type="url" name="svc_url_external[]" placeholder="https://..." class="berre-url-input"></div><input type="hidden" name="svc_url[]" value="/"></div></div>
                    <div class="berre-field" style="margin:0"><label>Icône</label><select name="svc_icon[]"><?php foreach($ic as $k=>$v): echo '<option value="'.esc_attr($k).'">'.esc_html($v['label']).'</option>'; endforeach; ?></select></div>
                </div>
            </div>`;
            document.getElementById('berre-services-list').insertAdjacentHTML('beforeend', html);
        });

        // Ajouter un horaire
        document.getElementById('berre-add-hour')?.addEventListener('click', function() {
            document.getElementById('berre-hours-list').insertAdjacentHTML('beforeend',
                `<div class="berre-hour-row">
                    <input type="text" name="hour_day[]" placeholder="Jour">
                    <input type="text" name="hour_h[]"   placeholder="Horaires">
                    <label style="white-space:nowrap;font-size:12px"><input type="checkbox" name="hour_off[]" value="1"> Fermé</label>
                </div>`
            );
        });

    })(jQuery);
    </script>
    <?php
    // Charger le script media de WP pour le sélecteur d'images
    wp_enqueue_media();
    do_action( 'berre_outils_extra' );
}
/* ── Mode aperçu ── */
add_action( 'template_redirect', function() {
    if ( ! isset( $_GET['berre_editor_preview'] ) ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;
    // Afficher la page normalement avec un bandeau et un listener postMessage
    add_action( 'wp_footer', function() {
        ?>
        <script>
        window.addEventListener('message', function(e) {
            var d = e.data;
            if (!d || d.type !== 'berre_preview_update') return;
            var k = d.key, v = d.value;
            var el;
            if (k === 'hero-image') {
                var cover = document.querySelector('.berre-hero');
                if (cover) cover.style.backgroundImage = v ? 'url('+v+')' : '';
            }
            // Textes dynamiques
            var map = {
                'hero-btn1-text':   '.berre-hero .berre-hb1',
                'hero-btn2-text':   '.berre-hero .berre-hb2',
                'commune-eyebrow':  '.berre-commune-eyebrow',
                'commune-title':    '.berre-section--commune h2',
                'commune-desc':     '.berre-commune-desc',
                'commune-btn':      '.berre-commune-btn .wp-block-button__link, .berre-commune-btn a',
                'stat1-val':        '.berre-cf:nth-child(1) strong',
                'stat1-lbl':        '.berre-cf:nth-child(1) span',
                'stat2-val':        '.berre-cf:nth-child(2) strong',
                'stat2-lbl':        '.berre-cf:nth-child(2) span',
                'stat3-val':        '.berre-cf:nth-child(3) strong',
                'stat3-lbl':        '.berre-cf:nth-child(3) span',
                'stat4-val':        '.berre-cf:nth-child(4) strong',
                'stat4-lbl':        '.berre-cf:nth-child(4) span',
                'contact-address':  '.berre-adresse',
                'contact-city':     '.berre-adresse',
                'contact-phone':    'a[href^="tel:"]',
                'contact-email':    'a[href^="mailto:"]',
                'nl-title':         '.berre-nl-title',
                'nl-desc':          '.berre-nl-desc',
                'footer-desc':      '.berre-footer__desc',
            };
            if (map[k]) {
                document.querySelectorAll(map[k]).forEach(function(el) {
                    el.textContent = v;
                });
            }
        });
        </script>
        <?php
    });
});


/* ── Shortcodes frontend ── */

// [berre_hero_buttons] — boutons du hero
add_shortcode('berre_hero_buttons', function() {
    $c = berre_get_page_content();
    $h = $c['hero'];
    $out = '<div class="berre-hero-btns">';
    if (!empty($h['btn1_text'])) $out .= '<a href="'.esc_url($h['btn1_url']).'" class="berre-hb1">'.esc_html($h['btn1_text']).'</a>';
    if (!empty($h['btn2_text'])) $out .= '<a href="'.esc_url($h['btn2_url']).'" class="berre-hb2">'.esc_html($h['btn2_text']).'</a>';
    $out .= '</div>';
    return $out;
});

// [berre_commune_content] — section commune complète

// [berre_services_grid] — grille services

// [berre_contact_content] — section contact
add_shortcode('berre_contact_content', function() {
    $ct = berre_get_page_content()['contact'];
    ob_start(); ?>
    <div class="berre-contact-banner">
        <h2 class="berre-contact-banner__title">La Mairie à votre service</h2>
        <div class="berre-contact-banner__info">
            <a href="tel:<?php echo esc_attr(preg_replace('/\s/','',$ct['phone'])); ?>" class="berre-contact-banner__item"><?php echo esc_html($ct['phone']); ?></a>
            <span class="berre-contact-banner__sep"></span>
            <a href="mailto:<?php echo esc_attr($ct['email']); ?>" class="berre-contact-banner__item"><?php echo esc_html($ct['email']); ?></a>
            <span class="berre-contact-banner__sep"></span>
            <a href="/contact" class="berre-contact-banner__btn">Nous écrire →</a>
        </div>
    </div>
    <div class="berre-contact-grid wp-block-columns">
        <div class="wp-block-column">
            <p class="berre-contact-sublbl">Horaires d'ouverture</p>
            <div class="berre-horaires-table">
                <?php foreach ($ct['hours'] as $h): ?>
                <div class="berre-hor-row<?php echo $h['off'] ? ' berre-hor-row--off' : ''; ?>">
                    <span><?php echo esc_html($h['day']); ?></span>
                    <span><?php echo esc_html($h['h']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="wp-block-column">
            <p class="berre-contact-sublbl">Adresse</p>
            <p class="berre-adresse"><?php echo esc_html($ct['address']); ?><br><?php echo esc_html($ct['city']); ?><br><?php echo esc_html($ct['dept']); ?><?php if (!empty($ct['fax'])): ?><br><br>Fax : <?php echo esc_html($ct['fax']); endif; ?></p>
            <?php if (!empty($ct['telealerte_url'])): ?>
            <p class="berre-telealerte"><a href="<?php echo esc_url($ct['telealerte_url']); ?>" target="_blank" rel="noopener">🔔 S'inscrire au TéléAlerte SMIAGE →</a></p>
            <?php endif; ?>
        </div>
        <div class="wp-block-column">
            <p class="berre-contact-sublbl">Démarches rapides</p>
            <div class="berre-quick-links">
                <a href="https://mesdemarches06.fr" target="_blank" rel="noopener" class="berre-quick-link"><div class="berre-ql-icon berre-ql-icon--bleu"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="#2D6AB0" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg></div><div><strong>Mes Démarches 06</strong><span>En ligne 24h/24</span></div><span class="berre-ql-arr">›</span></a>
                <a href="#" class="berre-quick-link"><div class="berre-ql-icon berre-ql-icon--or"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="#DEA128" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div><div><strong>Paiement en ligne</strong><span>Cantine, garderie, loyer</span></div><span class="berre-ql-arr">›</span></a>
                <a href="#" class="berre-quick-link"><div class="berre-ql-icon berre-ql-icon--vert"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="#587526" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><div><strong>Les élus</strong><span>Conseil municipal</span></div><span class="berre-ql-arr">›</span></a>
            </div>
        </div>
    </div>
    <?php return ob_get_clean();
});

// [berre_newsletter_content] — section newsletter
add_shortcode('berre_newsletter_content', function() {
    $nl = berre_get_page_content()['newsletter'];
    ob_start(); ?>
    <div class="wp-block-columns are-vertically-aligned-center berre-nl-cols">
        <div class="wp-block-column">
            <h3 class="wp-block-heading berre-nl-title"><?php echo esc_html($nl['title']); ?></h3>
            <p class="berre-nl-desc"><?php echo esc_html($nl['desc']); ?></p>
        </div>
        <div class="wp-block-column">
            <form class="berre-nl-form" onsubmit="return false">
                <input type="email" placeholder="Votre adresse e-mail" class="berre-nl-input" required aria-label="Adresse e-mail">
                <button type="submit" class="berre-nl-btn">S'inscrire</button>
            </form>
        </div>
    </div>
    <?php return ob_get_clean();
});



/* ============================================================
   MÉDIA HERO — Page d'administration
   Gère la photo ou vidéo de fond de la page d'accueil
   Option WP : berre_hero_media
   ============================================================ */

/* ── Valeurs par défaut ── */
function berre_hero_media_defaults() {
    return [
        'type'        => 'photo',      // 'photo' | 'video_local' | 'video_youtube'
        'photo_id'    => 0,
        'photo_url'   => '',
        'video_id'    => 0,
        'video_url'   => '',
        'youtube_url' => '',
        'overlay'     => 20,           // opacité overlay sombre 0-80
        'height'      => 480,          // hauteur en px
    ];
}

function berre_get_hero_media() {
    $saved = get_option( 'berre_hero_media' );
    return empty($saved) ? berre_hero_media_defaults() : array_merge(berre_hero_media_defaults(), $saved);
}

/* ── Menu admin ── */
// Submenu : Photo / Vidéo (géré par le menu unifié berre-admin)

/* ── Sauvegarde ── */
add_action( 'admin_init', function() {
    if ( ! isset($_POST['berre_save_hero_media']) ) return;
    if ( ! wp_verify_nonce($_POST['berre_hero_nonce'] ?? '', 'berre_save_hero_media') ) wp_die('Sécurité invalide.');
    if ( ! current_user_can('manage_options') ) wp_die('Permission refusée.');

    $type = in_array($_POST['hero_type'] ?? '', ['photo','video_local','video_youtube']) ? $_POST['hero_type'] : 'photo';

    update_option('berre_hero_media', [
        'type'        => $type,
        'photo_id'    => intval($_POST['hero_photo_id'] ?? 0),
        'photo_url'   => esc_url_raw($_POST['hero_photo_url'] ?? ''),
        'video_id'    => intval($_POST['hero_video_id'] ?? 0),
        'video_url'   => esc_url_raw($_POST['hero_video_url'] ?? ''),
        'youtube_url' => esc_url_raw($_POST['hero_youtube_url'] ?? ''),
        'overlay'     => max(0, min(80, intval($_POST['hero_overlay'] ?? 20))),
        'height'      => max(300, min(900, intval($_POST['hero_height'] ?? 480))),
    ]);

    set_transient('berre_hero_saved', true, 10);
} );

/* ── Page admin ── */
function berre_hero_media_page() {
    $m = berre_get_hero_media();
    $saved = get_transient('berre_hero_saved');
    if ($saved) delete_transient('berre_hero_saved');
    ?>
    <style>
    .berre-hero-wrap { max-width:900px; margin-top:20px; }
    .berre-hero-card { background:#fff;border:1px solid #ddd;border-radius:8px;overflow:hidden;margin-bottom:20px; }
    .berre-hero-card-head { background:#f6f7f7;border-bottom:1px solid #eee;padding:14px 20px; }
    .berre-hero-card-head h2 { margin:0;font-size:14px; }
    .berre-hero-card-body { padding:20px; }
    .berre-type-tabs { display:flex;gap:0;border:1.5px solid #ddd;border-radius:6px;overflow:hidden;max-width:450px;margin-bottom:20px; }
    .berre-type-tab { flex:1;padding:10px;text-align:center;cursor:pointer;font-size:13px;font-weight:600;color:#555;background:#f9f9f9;border:none;border-right:1px solid #ddd;transition:all .15s; }
    .berre-type-tab:last-child { border-right:none; }
    .berre-type-tab.active { background:#2D6AB0;color:#fff; }
    .berre-panel { display:none; }
    .berre-panel.active { display:block; }
    .berre-field { margin-bottom:14px; }
    .berre-field label { display:block;font-size:12px;font-weight:600;color:#333;margin-bottom:5px; }
    .berre-field input[type=text],.berre-field input[type=url],.berre-field input[type=number] { width:100%;max-width:500px;padding:7px 10px;border:1px solid #ddd;border-radius:4px;font-size:13px; }
    .berre-preview-box { border-radius:8px;overflow:hidden;position:relative;background:#1a1a2e;margin-top:16px; }
    .berre-preview-box img,.berre-preview-box video { width:100%;height:260px;object-fit:cover;display:block; }
    .berre-preview-box .overlay { position:absolute;inset:0;background:rgba(0,0,0,0);pointer-events:none; }
    .berre-preview-empty { height:260px;display:flex;align-items:center;justify-content:center;color:#888;flex-direction:column;gap:8px; }
    .berre-preview-empty svg { opacity:.3; }
    .berre-slider-row { display:flex;align-items:center;gap:12px; }
    .berre-slider-row input[type=range] { flex:1;max-width:300px; }
    .berre-slider-val { font-size:12px;color:#666;min-width:32px; }
    </style>

    <div class="wrap berre-hero-wrap">
        <h1>🎬 Photo / Vidéo — Page d'accueil</h1>
        <?php if ($saved): ?><div class="notice notice-success is-dismissible"><p>✅ Sauvegardé avec succès.</p></div><?php endif; ?>

        <form method="post" id="berre-hero-form">
            <?php wp_nonce_field('berre_save_hero_media','berre_hero_nonce'); ?>

            <!-- TYPE -->
            <div class="berre-hero-card">
                <div class="berre-hero-card-head"><h2>Type de fond</h2></div>
                <div class="berre-hero-card-body">
                    <div class="berre-type-tabs">
                        <button type="button" class="berre-type-tab <?php echo $m['type']==='photo'?'active':''; ?>" data-type="photo">📷 Photo</button>
                        <button type="button" class="berre-type-tab <?php echo $m['type']==='video_local'?'active':''; ?>" data-type="video_local">🎬 Vidéo locale</button>
                        <button type="button" class="berre-type-tab <?php echo $m['type']==='video_youtube'?'active':''; ?>" data-type="video_youtube">▶ YouTube</button>
                    </div>
                    <input type="hidden" name="hero_type" id="hero_type" value="<?php echo esc_attr($m['type']); ?>">

                    <!-- PHOTO -->
                    <div class="berre-panel <?php echo $m['type']==='photo'?'active':''; ?>" id="panel-photo">
                        <div class="berre-field">
                            <label>Photo de fond</label>
                            <input type="hidden" name="hero_photo_id" id="hero_photo_id" value="<?php echo intval($m['photo_id']); ?>">
                            <input type="hidden" name="hero_photo_url" id="hero_photo_url" value="<?php echo esc_attr($m['photo_url']); ?>">
                            <?php if (!empty($m['photo_url'])): ?>
                                <div class="berre-preview-box"><img src="<?php echo esc_url($m['photo_url']); ?>" id="photo-preview" alt="Aperçu"></div>
                            <?php else: ?>
                                <div class="berre-preview-box"><div class="berre-preview-empty" id="photo-preview-empty"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><span>Aucune photo sélectionnée</span></div></div>
                            <?php endif; ?>
                            <p style="margin-top:10px;display:flex;gap:8px">
                                <button type="button" id="pick-photo" class="button button-primary">📷 Choisir dans la médiathèque</button>
                                <button type="button" id="clear-photo" class="button" style="color:#c00" <?php echo empty($m['photo_url'])?'disabled':''; ?>>✕ Supprimer</button>
                            </p>
                            <p style="color:#888;font-size:12px">Format recommandé : 1600×900px minimum, JPEG ou WebP.</p>
                        </div>
                    </div>

                    <!-- VIDÉO LOCALE -->
                    <div class="berre-panel <?php echo $m['type']==='video_local'?'active':''; ?>" id="panel-video_local">
                        <div class="berre-field">
                            <label>Fichier vidéo (MP4 recommandé)</label>
                            <input type="hidden" name="hero_video_id" id="hero_video_id" value="<?php echo intval($m['video_id']); ?>">
                            <input type="hidden" name="hero_video_url" id="hero_video_url" value="<?php echo esc_attr($m['video_url']); ?>">
                            <?php if (!empty($m['video_url'])): ?>
                                <div class="berre-preview-box"><video id="video-preview" src="<?php echo esc_url($m['video_url']); ?>" muted autoplay loop playsinline></video></div>
                            <?php else: ?>
                                <div class="berre-preview-box"><div class="berre-preview-empty" id="video-preview-empty"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg><span>Aucune vidéo sélectionnée</span></div></div>
                            <?php endif; ?>
                            <p style="margin-top:10px;display:flex;gap:8px">
                                <button type="button" id="pick-video" class="button button-primary">🎬 Choisir dans la médiathèque</button>
                                <button type="button" id="clear-video" class="button" style="color:#c00" <?php echo empty($m['video_url'])?'disabled':''; ?>>✕ Supprimer</button>
                            </p>
                            <p style="color:#888;font-size:12px">MP4/WebM. La vidéo sera muette, en boucle, sans contrôles.</p>
                        </div>
                    </div>

                    <!-- YOUTUBE -->
                    <div class="berre-panel <?php echo $m['type']==='video_youtube'?'active':''; ?>" id="panel-video_youtube">
                        <div class="berre-field">
                            <label>URL YouTube</label>
                            <input type="url" name="hero_youtube_url" id="hero_youtube_url"
                                value="<?php echo esc_attr($m['youtube_url']); ?>"
                                placeholder="https://www.youtube.com/watch?v=...">
                            <p style="color:#888;font-size:12px;margin-top:4px">La vidéo sera en lecture automatique, muette et en boucle.</p>
                            <?php if (!empty($m['youtube_url'])): 
                                preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $m['youtube_url'], $yt);
                                $ytid = $yt[1] ?? '';
                            ?>
                            <?php if ($ytid): ?>
                            <div class="berre-preview-box" style="margin-top:12px">
                                <img src="https://img.youtube.com/vi/<?php echo esc_attr($ytid); ?>/maxresdefault.jpg" style="width:100%;height:260px;object-fit:cover">
                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center">
                                    <div style="width:60px;height:60px;background:rgba(255,0,0,.85);border-radius:50%;display:flex;align-items:center;justify-content:center">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="white"><polygon points="5,3 19,12 5,21"/></svg>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OPTIONS -->
            <div class="berre-hero-card">
                <div class="berre-hero-card-head"><h2>Options d'affichage</h2></div>
                <div class="berre-hero-card-body">
                    <div class="berre-field">
                        <label>Hauteur du hero (px)</label>
                        <div class="berre-slider-row">
                            <input type="range" name="hero_height" id="hero_height" min="300" max="900" step="10" value="<?php echo intval($m['height']); ?>">
                            <span class="berre-slider-val" id="height-val"><?php echo intval($m['height']); ?>px</span>
                        </div>
                    </div>
                    <div class="berre-field">
                        <label>Assombrissement de l'image/vidéo (%)</label>
                        <div class="berre-slider-row">
                            <input type="range" name="hero_overlay" id="hero_overlay" min="0" max="80" step="5" value="<?php echo intval($m['overlay']); ?>">
                            <span class="berre-slider-val" id="overlay-val"><?php echo intval($m['overlay']); ?>%</span>
                        </div>
                    </div>
                </div>
            </div>

            <input type="submit" name="berre_save_hero_media" class="button button-primary button-large" value="💾 Enregistrer">
        </form>
    </div>

    <script>
    // Onglets type
    document.querySelectorAll('.berre-type-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.berre-type-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.berre-panel').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('panel-' + this.dataset.type).classList.add('active');
            document.getElementById('hero_type').value = this.dataset.type;
        });
    });

    // Sliders
    document.getElementById('hero_height').addEventListener('input', function() {
        document.getElementById('height-val').textContent = this.value + 'px';
    });
    document.getElementById('hero_overlay').addEventListener('input', function() {
        document.getElementById('overlay-val').textContent = this.value + '%';
    });

    // Media pickers
    function makePicker(btnId, clearId, hiddenIdField, hiddenUrlField, previewId, emptyId, type) {
        var btn = document.getElementById(btnId);
        var clr = document.getElementById(clearId);
        if (!btn) return;

        btn.addEventListener('click', function() {
            if (typeof wp === 'undefined' || !wp.media) { alert('Module médias non disponible.'); return; }
            var frame = wp.media({
                title: type === 'video' ? 'Choisir la vidéo hero' : 'Choisir la photo hero',
                button: { text: 'Utiliser' },
                library: { type: type === 'video' ? 'video' : 'image' },
                multiple: false
            });
            frame.on('select', function() {
                var att = frame.state().get('selection').first().toJSON();
                document.getElementById(hiddenIdField).value = att.id;
                document.getElementById(hiddenUrlField).value = att.url;
                // Mettre à jour la prévisualisation
                var prev = document.getElementById(previewId);
                var empty = document.getElementById(emptyId);
                if (empty) empty.style.display = 'none';
                if (prev) {
                    prev.src = att.url;
                    prev.style.display = 'block';
                } else {
                    var box = btn.closest('.berre-field').querySelector('.berre-preview-box');
                    if (box) {
                        var tag = type === 'video' ? '<video muted autoplay loop playsinline style="width:100%;height:260px;object-fit:cover">' : '<img style="width:100%;height:260px;object-fit:cover">';
                        box.innerHTML = (type === 'video'
                            ? '<video id="' + previewId + '" src="' + att.url + '" muted autoplay loop playsinline style="width:100%;height:260px;object-fit:cover"></video>'
                            : '<img id="' + previewId + '" src="' + att.url + '" style="width:100%;height:260px;object-fit:cover">');
                    }
                }
                if (clr) clr.disabled = false;
            });
            frame.open();
        });

        if (clr) clr.addEventListener('click', function() {
            document.getElementById(hiddenIdField).value = '';
            document.getElementById(hiddenUrlField).value = '';
            var box = btn.closest('.berre-field').querySelector('.berre-preview-box');
            if (box) {
                var icon = type === 'video'
                    ? '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>'
                    : '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';
                box.innerHTML = '<div class="berre-preview-empty" style="height:260px;display:flex;align-items:center;justify-content:center;color:#888;flex-direction:column;gap:8px"><div style="opacity:.3">' + icon + '</div><span>Aucun fichier sélectionné</span></div>';
            }
            this.disabled = true;
        });
    }

    makePicker('pick-photo', 'clear-photo', 'hero_photo_id', 'hero_photo_url', 'photo-preview', 'photo-preview-empty', 'image');
    makePicker('pick-video', 'clear-video', 'hero_video_id', 'hero_video_url', 'video-preview', 'video-preview-empty', 'video');
    </script>
    <?php
    wp_enqueue_media();
}

/* ── Shortcode [berre_hero] — rendu frontend ── */
add_shortcode('berre_hero', function() {
    $m = berre_get_hero_media();
    $h = intval($m['height']);
    $o = intval($m['overlay']);
    $overlay_style = $o > 0 ? "position:absolute;inset:0;background:rgba(0,0,0," . ($o/100) . ");pointer-events:none;" : '';

    ob_start();
    echo '<div class="berre-hero" style="min-height:' . $h . 'px;position:relative;overflow:hidden;">';

    if ($m['type'] === 'photo' && !empty($m['photo_url'])) {
        echo '<img src="' . esc_url($m['photo_url']) . '" alt="Photo de la commune" '
           . 'style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;" loading="eager">';

    } elseif ($m['type'] === 'video_local' && !empty($m['video_url'])) {
        echo '<video autoplay muted loop playsinline '
           . 'style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">'
           . '<source src="' . esc_url($m['video_url']) . '" type="video/mp4">'
           . '</video>';

    } elseif ($m['type'] === 'video_youtube' && !empty($m['youtube_url'])) {
        preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $m['youtube_url'], $yt);
        $ytid = $yt[1] ?? '';
        if ($ytid) {
            echo '<div style="position:absolute;inset:0;width:100%;height:100%;overflow:hidden;pointer-events:none;">';
            echo '<iframe src="https://www.youtube-nocookie.com/embed/' . esc_attr($ytid)
               . '?autoplay=1&mute=1&loop=1&playlist=' . esc_attr($ytid)
               . '&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3" '
               . 'style="position:absolute;top:50%;left:50%;width:177.78%;height:100%;min-height:56.25vw;transform:translate(-50%,-50%);" '
               . 'frameborder="0" allow="autoplay;encrypted-media" allowfullscreen></iframe>';
            echo '</div>';
        }

    } else {
        // Placeholder si rien n'est configuré
        echo '<div style="position:absolute;inset:0;background:linear-gradient(135deg,#102142,#2D6AB0);display:flex;align-items:center;justify-content:center;">';
        echo '<p style="color:rgba(255,255,255,.4);font-size:14px;">📷 Ajoutez une photo ou vidéo dans <strong>Photo / Vidéo</strong></p>';
        echo '</div>';
    }

    if ($overlay_style) echo '<div style="' . $overlay_style . '"></div>';
    echo '</div>';
    return ob_get_clean();
});


/* ============================================================
   AGENDA — Champs personnalisés (date, heure, lieu)
   + API REST pour le calendrier
   ============================================================ */

/* ── Enregistrer les meta fields ── */
add_action( 'init', function() {
    register_post_meta( 'agenda', 'berre_event_date_start', [
        'single' => true, 'type' => 'string', 'show_in_rest' => true,
        'auth_callback' => function() { return current_user_can('edit_posts'); }
    ]);
    register_post_meta( 'agenda', 'berre_event_date_end', [
        'single' => true, 'type' => 'string', 'show_in_rest' => true,
        'auth_callback' => function() { return current_user_can('edit_posts'); }
    ]);
    register_post_meta( 'agenda', 'berre_event_time', [
        'single' => true, 'type' => 'string', 'show_in_rest' => true,
        'auth_callback' => function() { return current_user_can('edit_posts'); }
    ]);
    register_post_meta( 'agenda', 'berre_event_location', [
        'single' => true, 'type' => 'string', 'show_in_rest' => true,
        'auth_callback' => function() { return current_user_can('edit_posts'); }
    ]);
} );

/* ── Metabox dans l'éditeur ── */
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'berre_event_details',
        '📅 Détails de l\'événement',
        'berre_event_metabox_html',
        'agenda', 'side', 'high'
    );
} );

function berre_event_metabox_html( $post ) {
    wp_nonce_field( 'berre_event_meta', 'berre_event_nonce' );
    $start    = get_post_meta( $post->ID, 'berre_event_date_start', true );
    $end      = get_post_meta( $post->ID, 'berre_event_date_end',   true );
    $time     = get_post_meta( $post->ID, 'berre_event_time',       true );
    $location = get_post_meta( $post->ID, 'berre_event_location',   true );
    ?>
    <p>
        <label style="font-weight:600;font-size:12px">Date de début *</label><br>
        <input type="date" name="berre_event_date_start" value="<?php echo esc_attr($start); ?>" style="width:100%">
    </p>
    <p>
        <label style="font-weight:600;font-size:12px">Date de fin (si multi-jours)</label><br>
        <input type="date" name="berre_event_date_end" value="<?php echo esc_attr($end); ?>" style="width:100%">
    </p>
    <p>
        <label style="font-weight:600;font-size:12px">Heure (ex: 14h00)</label><br>
        <input type="text" name="berre_event_time" value="<?php echo esc_attr($time); ?>" placeholder="10h00 – 18h00" style="width:100%">
    </p>
    <p>
        <label style="font-weight:600;font-size:12px">Lieu</label><br>
        <input type="text" name="berre_event_location" value="<?php echo esc_attr($location); ?>" placeholder="Salle des fêtes, Place de la Mairie…" style="width:100%">
    </p>
    <?php
}

add_action( 'save_post_agenda', function( $post_id ) {
    if ( ! isset($_POST['berre_event_nonce']) || ! wp_verify_nonce($_POST['berre_event_nonce'], 'berre_event_meta') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    $fields = ['berre_event_date_start','berre_event_date_end','berre_event_time','berre_event_location'];
    foreach ($fields as $field) {
        if ( isset($_POST[$field]) ) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
} );

/* ── Endpoint REST pour le calendrier ── */
add_action( 'rest_api_init', function() {
    register_rest_route( 'berre/v1', '/agenda', [
        'methods'             => 'GET',
        'callback'            => 'berre_rest_agenda_events',
        'permission_callback' => '__return_true',
        'args' => [
            'month' => [ 'required' => false, 'sanitize_callback' => 'sanitize_text_field' ],
        ],
    ]);
} );

function berre_rest_agenda_events( WP_REST_Request $request ) {
    // Retourner tous les événements publiés — le JS filtre par mois
    $posts = get_posts( [
        'post_type'      => 'agenda',
        'post_status'    => 'publish',
        'posts_per_page' => 300,
        'orderby'        => 'date',
        'order'          => 'ASC',
    ] );

    $events = [];
    foreach ( $posts as $post ) {
        $start    = get_post_meta( $post->ID, 'berre_event_date_start', true );
        if ( ! $start ) $start = get_the_date( 'Y-m-d', $post );
        $end      = get_post_meta( $post->ID, 'berre_event_date_end', true );
        if ( ! $end ) $end = $start;
        $time     = get_post_meta( $post->ID, 'berre_event_time', true );
        $location = get_post_meta( $post->ID, 'berre_event_location', true );

        $thumb_id  = get_post_thumbnail_id( $post->ID );
        $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : '';

        $cats = wp_get_object_terms( $post->ID, 'categorie_agenda', [ 'fields' => 'names' ] );
        $cat_colors = [];
        $cat_terms  = wp_get_object_terms( $post->ID, 'categorie_agenda' );
        foreach ( $cat_terms as $ct ) {
            $c = get_term_meta( $ct->term_id, 'berre_cat_agenda_color', true );
            if ( $c ) $cat_colors[] = $c;
        }

        $events[] = [
            'id'        => $post->ID,
            'title'     => $post->post_title,
            'dateStart' => $start,
            'dateEnd'   => $end,
            'time'      => $time,
            'location'  => $location,
            'img'       => $thumb_url,
            'url'       => get_permalink( $post->ID ),
            'cats'      => is_array( $cats ) ? $cats : [],
            'color'     => ! empty( $cat_colors ) ? $cat_colors[0] : '',
        ];
    }

    return rest_ensure_response( $events );
}

/* ── Shortcode [berre_calendrier_agenda] ── */
add_shortcode( 'berre_calendrier_agenda', function() {
    // Récupérer TOUS les événements et les embarquer en JSON dans la page
    // (évite tout problème d'API/nonce/CORS)
    $posts = get_posts( [
        'post_type'      => 'agenda',
        'post_status'    => 'publish',
        'posts_per_page' => 300,
        'orderby'        => 'date',
        'order'          => 'ASC',
    ] );

    $events = [];
    foreach ( $posts as $post ) {
        $start    = get_post_meta( $post->ID, 'berre_event_date_start', true );
        if ( ! $start ) $start = get_the_date( 'Y-m-d', $post );
        $end_d    = get_post_meta( $post->ID, 'berre_event_date_end', true );
        if ( ! $end_d ) $end_d = $start;
        $time     = get_post_meta( $post->ID, 'berre_event_time',       true );
        $location = get_post_meta( $post->ID, 'berre_event_location',   true );
        $thumb    = get_post_thumbnail_id( $post->ID );
        $thumb_url= $thumb ? wp_get_attachment_image_url( $thumb, 'medium' ) : '';
        $cats     = wp_get_object_terms( $post->ID, 'categorie_agenda', [ 'fields' => 'names' ] );
        $events[] = [
            'id'        => $post->ID,
            'title'     => $post->post_title,
            'dateStart' => $start,
            'dateEnd'   => $end_d,
            'time'      => $time,
            'location'  => $location,
            'img'       => $thumb_url,
            'url'       => get_permalink( $post->ID ),
            'cats'      => is_array($cats) ? $cats : [],
        ];
    }

    $events_json = wp_json_encode( $events ) ?: '[]';
    ob_start(); ?>
    <div class="berre-cal" id="berre-cal">

      <div class="berre-cal__header">
        <div class="berre-cal__nav-left">
          <button class="berre-cal__btn" id="berre-cal-prev" aria-label="Mois précédent">‹</button>
          <span class="berre-cal__month-label" id="berre-cal-label"></span>
          <button class="berre-cal__btn" id="berre-cal-next" aria-label="Mois suivant">›</button>
        </div>
        <button class="berre-cal__today-btn" id="berre-cal-today">Aujourd'hui</button>
      </div>

      <div class="berre-cal__weekdays">
        <div>lun.</div><div>mar.</div><div>mer.</div>
        <div>jeu.</div><div>ven.</div><div>sam.</div><div>dim.</div>
      </div>

      <div class="berre-cal__grid" id="berre-cal-grid">
        <div class="berre-cal__loading">Chargement…</div>
      </div>

      <div class="berre-cal__popup" id="berre-cal-popup" style="display:none" role="dialog">
        <button class="berre-cal__popup-close" id="berre-cal-popup-close">×</button>
        <div class="berre-cal__popup-img-wrap" id="berre-cal-popup-img-wrap"></div>
        <div class="berre-cal__popup-body">
          <div class="berre-cal__popup-cat" id="berre-cal-popup-cat"></div>
          <h3 class="berre-cal__popup-title" id="berre-cal-popup-title"></h3>
          <div class="berre-cal__popup-meta" id="berre-cal-popup-meta"></div>
          <div class="berre-cal__popup-share">
            <a class="berre-cal__popup-share-icon" id="berre-cal-share-fb" href="#" target="_blank">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
            </a>
            <a class="berre-cal__popup-share-icon" id="berre-cal-share-tw" href="#" target="_blank">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
          </div>
          <a class="berre-cal__popup-cta" id="berre-cal-popup-cta" href="#">En savoir plus</a>
        </div>
      </div>
      <div class="berre-cal__overlay" id="berre-cal-overlay" style="display:none"></div>

    </div>

    <script>
    (function(){
      var ALL_EVENTS = <?php echo $events_json; ?>;
      var today  = new Date();
      var curYear  = today.getFullYear();
      var curMonth = today.getMonth();
      var MONTHS_FR = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

      function pad(n){ return n < 10 ? '0' + n : '' + n; }
      function escHtml(s){ var d=document.createElement('div');d.textContent=s||'';return d.innerHTML; }
      function truncate(s,n){ return (s||'').length>n ? s.slice(0,n)+'…' : s||''; }
      function formatDate(str) {
        if (!str) return '';
        var d = new Date(str + 'T00:00:00');
        var days=['dim.','lun.','mar.','mer.','jeu.','ven.','sam.'];
        var months=['janv.','févr.','mars','avr.','mai','juin','juil.','août','sept.','oct.','nov.','déc.'];
        return days[d.getDay()] + ' ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
      }

      function buildIndex(events, year, month) {
        var idx = {};
        events.forEach(function(ev){
          var start = new Date((ev.dateStart||'').replace(/-/g,'/'));
          var end   = ev.dateEnd ? new Date((ev.dateEnd||'').replace(/-/g,'/')) : start;
          if (isNaN(start.getTime())) return;
          var d = new Date(start);
          while (d <= end) {
            if (d.getFullYear() === year && d.getMonth() === month) {
              var key = pad(d.getDate());
              if (!idx[key]) idx[key] = [];
              idx[key].push(ev);
            }
            d.setDate(d.getDate() + 1);
          }
        });
        return idx;
      }

      function renderCalendar(year, month) {
        var label  = document.getElementById('berre-cal-label');
        var grid   = document.getElementById('berre-cal-grid');
        if (!label || !grid) return;
        label.textContent = MONTHS_FR[month] + ' ' + year;
        var idx = buildIndex(ALL_EVENTS, year, month);
        var firstDay = new Date(year, month, 1);
        var lastDay  = new Date(year, month + 1, 0);
        var startDow = (firstDay.getDay() + 6) % 7;
        var html = '';
        var prevLast = new Date(year, month, 0).getDate();
        for (var i = startDow - 1; i >= 0; i--) {
          html += '<div class="berre-cal__day berre-cal__day--other"><span class="berre-cal__day-num">' + (prevLast - i) + '</span></div>';
        }
        for (var d = 1; d <= lastDay.getDate(); d++) {
          var isToday = (d === today.getDate() && month === today.getMonth() && year === today.getFullYear());
          var dayEvs  = idx[pad(d)] || [];
          var cls     = 'berre-cal__day' + (isToday ? ' berre-cal__day--today' : '');
          html += '<div class="' + cls + '">';
          html += '<span class="berre-cal__day-num">' + d + '</span>';
          dayEvs.slice(0, 2).forEach(function(ev){
            html += '<button class="berre-cal__event" data-id="' + ev.id + '">' + escHtml(truncate(ev.title, 18)) + '</button>';
          });
          if (dayEvs.length > 2) {
            html += '<button class="berre-cal__more" data-id="' + dayEvs[2].id + '">+ ' + (dayEvs.length - 2) + ' autre' + (dayEvs.length > 3 ? 's' : '') + '</button>';
          }
          html += '</div>';
        }
        var endDow = (lastDay.getDay() + 6) % 7;
        var fillCount = endDow < 6 ? (6 - endDow) : 0;
        for (var n = 1; n <= fillCount; n++) {
          html += '<div class="berre-cal__day berre-cal__day--other"><span class="berre-cal__day-num">' + n + '</span></div>';
        }
        grid.innerHTML = html;
        grid.querySelectorAll('.berre-cal__event, .berre-cal__more').forEach(function(btn){
          btn.addEventListener('click', function(e){
            e.stopPropagation();
            var id = parseInt(this.dataset.id);
            var ev = ALL_EVENTS.find(function(x){ return x.id === id; });
            if (ev) openPopup(ev, this);
          });
        });
      }

      function openPopup(ev, anchor) {
        var imgWrap  = document.getElementById('berre-cal-popup-img-wrap');
        var catEl    = document.getElementById('berre-cal-popup-cat');
        var titleEl  = document.getElementById('berre-cal-popup-title');
        var metaEl   = document.getElementById('berre-cal-popup-meta');
        var ctaEl    = document.getElementById('berre-cal-popup-cta');
        var popup    = document.getElementById('berre-cal-popup');
        var overlay  = document.getElementById('berre-cal-overlay');
        var cal      = document.getElementById('berre-cal');
        if (!popup) return;
        imgWrap.innerHTML = ev.img ? '<img src="' + ev.img + '" alt="">' : '';
        catEl.textContent = ev.cats && ev.cats.length ? ev.cats.join(', ') : '';
        titleEl.textContent = ev.title || '';
        var dateStr = formatDate(ev.dateStart);
        if (ev.dateEnd && ev.dateEnd !== ev.dateStart) dateStr += ' – ' + formatDate(ev.dateEnd);
        if (ev.time) dateStr += ', ' + ev.time;
        var meta = '<div class="berre-cal__popup-meta-row"><span>' + dateStr + '</span></div>';
        if (ev.location) meta += '<div class="berre-cal__popup-meta-row"><span>' + escHtml(ev.location) + '</span></div>';
        metaEl.innerHTML = meta;
        ctaEl.href = ev.url || '#';
        document.getElementById('berre-cal-share-fb').href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(ev.url||'');
        document.getElementById('berre-cal-share-tw').href = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(ev.url||'');
        popup.style.display = 'block';
        overlay.style.display = 'block';
        var rect = anchor.getBoundingClientRect();
        var calRect = cal.getBoundingClientRect();
        var top  = rect.bottom - calRect.top + 4;
        var left = rect.left - calRect.left;
        if (left + 280 > calRect.width) left = calRect.width - 285;
        if (left < 0) left = 0;
        popup.style.top  = top + 'px';
        popup.style.left = left + 'px';
      }

      function closePopup() {
        var popup   = document.getElementById('berre-cal-popup');
        var overlay = document.getElementById('berre-cal-overlay');
        if (popup)   popup.style.display   = 'none';
        if (overlay) overlay.style.display = 'none';
      }

      function navigate(year, month) {
        curYear  = year;
        curMonth = month;
        closePopup();
        renderCalendar(year, month);
      }

      function init() {
        var prev   = document.getElementById('berre-cal-prev');
        var next   = document.getElementById('berre-cal-next');
        var todayB = document.getElementById('berre-cal-today');
        var closeB = document.getElementById('berre-cal-popup-close');
        var overlay= document.getElementById('berre-cal-overlay');
        if (!prev) return;
        prev.addEventListener('click', function(){
          var d = new Date(curYear, curMonth - 1, 1);
          navigate(d.getFullYear(), d.getMonth());
        });
        next.addEventListener('click', function(){
          var d = new Date(curYear, curMonth + 1, 1);
          navigate(d.getFullYear(), d.getMonth());
        });
        todayB.addEventListener('click', function(){
          navigate(today.getFullYear(), today.getMonth());
        });
        if (closeB)  closeB.addEventListener('click', closePopup);
        if (overlay) overlay.addEventListener('click', closePopup);
        document.addEventListener('keydown', function(e){ if(e.key==='Escape') closePopup(); });
        navigate(curYear, curMonth);
      }

      function safeInit() {
        try { init(); }
        catch(err) {
          var g = document.getElementById('berre-cal-grid');
          if (g) g.innerHTML = '<div style="color:#c00;padding:16px;font-size:12px">Erreur calendrier: ' + err.message + '</div>';
        }
      }
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', safeInit);
      } else {
        safeInit();
      }
    })();
    </script>
    <?php
    return ob_get_clean();
} );



/* ============================================================
   SERVICES MUNICIPAUX — Page d'administration dédiée
   Option WP : berre_services
   ============================================================ */

function berre_services_defaults() {
    return [
        ['title'=>'État Civil',        'desc'=>'Actes, naissances, mariages, décès.',    'url'=>'/etat-civil',   'icon'=>'document', 'color'=>'bleu'],
        ['title'=>'Urbanisme',         'desc'=>'Permis de construire, PLU.',             'url'=>'/urbanisme',    'icon'=>'building', 'color'=>'bleu'],
        ['title'=>'Scolarité',         'desc'=>'Inscriptions, cantine, garderie.',       'url'=>'/scolarite',    'icon'=>'school',   'color'=>'vert'],
        ['title'=>'Démarches en ligne','desc'=>'Mes Démarches 06 — 24h/24.',            'url'=>'https://mesdemarches06.fr','icon'=>'computer','color'=>'or'],
        ['title'=>'Sécurité & Risques','desc'=>'Gendarmerie, plan communal.',           'url'=>'/securite',     'icon'=>'shield',   'color'=>'bleu'],
        ['title'=>'Qualité de vie',    'desc'=>'Environnement, tri sélectif.',           'url'=>'/qualite-vie',  'icon'=>'leaf',     'color'=>'vert'],
        ['title'=>'Finances publiques','desc'=>'Budget, marchés publics.',              'url'=>'/finances',     'icon'=>'coin',     'color'=>'or'],
        ['title'=>'Infos pratiques',   'desc'=>'ANAH, SPANC, juridique.',               'url'=>'/infos',        'icon'=>'info',     'color'=>'bleu'],
    ];
}

function berre_get_services() {
    $saved = get_option( 'berre_services' );
    if ( empty($saved) || ! is_array($saved) ) return berre_services_defaults();
    return $saved;
}

/* ── Menu admin ── */
// Submenu : Services municipaux (géré par le menu unifié berre-admin)

/* ── Sauvegarde ── */
add_action( 'admin_init', function() {
    if ( ! isset($_POST['berre_save_services']) ) return;
    if ( ! wp_verify_nonce($_POST['berre_services_nonce'] ?? '', 'berre_save_services') ) wp_die('Sécurité invalide.');
    if ( ! current_user_can('manage_options') ) wp_die('Permission refusée.');

    $services = [];
    $titles = (array)($_POST['svc_title']  ?? []);
    $descs  = (array)($_POST['svc_desc']   ?? []);
    $urls   = (array)($_POST['svc_url']    ?? []);
    $icons  = (array)($_POST['svc_icon']   ?? []);
    $colors = (array)($_POST['svc_color']  ?? []);

    foreach ($titles as $i => $title) {
        $title = sanitize_text_field($title);
        if (empty($title)) continue;
        $services[] = [
            'title' => $title,
            'desc'  => sanitize_text_field($descs[$i]  ?? ''),
            'url'   => esc_url_raw($urls[$i]   ?? '#'),
            'icon'  => sanitize_key($icons[$i]  ?? 'document'),
            'color' => in_array($colors[$i] ?? '', ['bleu','vert','or']) ? $colors[$i] : 'bleu',
        ];
    }

    update_option('berre_services', $services);
    set_transient('berre_services_saved', true, 10);
} );

/* ── Page admin ── */
function berre_services_admin_page() {
    $services = berre_get_services();
    $icons    = berre_icons_list();
    $colors   = ['bleu' => '#2D6AB0', 'vert' => '#587526', 'or' => '#DEA128'];
    $saved    = get_transient('berre_services_saved');
    if ($saved) delete_transient('berre_services_saved');
    ?>
    <style>
    .berre-svc-admin-wrap { max-width:860px;margin-top:20px; }
    .berre-svc-row { background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:14px 16px;margin-bottom:10px;display:grid;grid-template-columns:28px 42px 1fr 1fr 1fr 130px 110px 44px;gap:10px;align-items:center; }
    .berre-svc-row:hover { border-color:#bbb; }
    .berre-drag-handle { cursor:grab;color:#ccc;font-size:20px;text-align:center;user-select:none; }
    .berre-drag-handle:active { cursor:grabbing; }
    .berre-svc-icon-preview { width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
    .berre-svc-icon-preview svg { fill:none;stroke:white;stroke-width:1.8; }
    .berre-svc-row input[type=text],.berre-svc-row input[type=url] { width:100%;padding:6px 9px;border:1px solid #ddd;border-radius:4px;font-size:12.5px; }
    .berre-svc-row select { width:100%;padding:5px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px; }
    .berre-svc-row label { font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#999;display:block;margin-bottom:3px; }
    .berre-svc-add-btn { margin-top:14px; }
    </style>

    <div class="wrap berre-svc-admin-wrap">
        <h1>⚙️ Services municipaux</h1>
        <p style="color:#666;margin-bottom:16px">Gérez les services affichés sur la page d'accueil. Glissez ⠿ pour réordonner. Maximum recommandé : 8.</p>

        <?php if ($saved): ?>
        <div class="notice notice-success is-dismissible"><p>✅ Services sauvegardés avec succès.</p></div>
        <?php endif; ?>

        <form method="post" id="berre-svc-form">
            <?php wp_nonce_field('berre_save_services','berre_services_nonce'); ?>

            <!-- En-têtes -->
            <div style="display:grid;grid-template-columns:28px 42px 1fr 1fr 1fr 130px 110px 44px;gap:10px;padding:0 16px;margin-bottom:4px">
                <div></div><div></div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#999;letter-spacing:.08em">Titre</div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#999;letter-spacing:.08em">Description</div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#999;letter-spacing:.08em">URL</div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#999;letter-spacing:.08em">Icône</div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#999;letter-spacing:.08em">Couleur</div>
                <div></div>
            </div>

            <div id="berre-svc-list">
            <?php foreach ($services as $i => $svc) :
                $hex = $colors[$svc['color']] ?? '#2D6AB0';
                $svg = $icons[$svc['icon']]['svg'] ?? '';
            ?>
            <div class="berre-svc-row" draggable="true">
                <span class="berre-drag-handle">⠿</span>
                <div class="berre-svc-icon-preview" style="background:<?php echo esc_attr($hex); ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18"><?php echo $svg; ?></svg>
                </div>
                <input type="text"  name="svc_title[]"  value="<?php echo esc_attr($svc['title']); ?>" placeholder="Titre" required>
                <input type="text"  name="svc_desc[]"   value="<?php echo esc_attr($svc['desc']); ?>"  placeholder="Description courte">
                <?php echo berre_url_picker_html('svc_url', $svc['url'] ?? '#', $i); ?>
                <select name="svc_icon[]" class="berre-svc-icon-sel" onchange="updatePreview(this)">
                    <?php foreach ($icons as $k => $v) : ?>
                    <option value="<?php echo esc_attr($k); ?>" <?php selected($svc['icon'],$k); ?>>
                        <?php echo esc_html($v['label']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <select name="svc_color[]" class="berre-svc-color-sel" onchange="updatePreview(this)">
                    <option value="bleu" <?php selected($svc['color'],'bleu'); ?>>🔵 Bleu</option>
                    <option value="vert" <?php selected($svc['color'],'vert'); ?>>🟢 Vert</option>
                    <option value="or"   <?php selected($svc['color'],'or');   ?>>🟡 Or</option>
                </select>
                <button type="button" class="button berre-del-svc" onclick="if(confirm('Supprimer ?'))this.closest('.berre-svc-row').remove()" style="color:#c00">✕</button>
            </div>
            <?php endforeach; ?>
            </div>

            <div class="berre-svc-add-btn" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <button type="button" id="berre-add-svc" class="button button-secondary">➕ Ajouter un service</button>
                <input type="submit" name="berre_save_services" class="button button-primary button-large" value="💾 Enregistrer">
                <span style="color:#888;font-size:12px">Les modifications s'appliquent immédiatement sur le site.</span>
            </div>
        </form>

        <!-- Aperçu des icônes -->
        <div style="margin-top:28px;padding:20px;background:#fff;border:1px solid #eee;border-radius:8px">
            <h2 style="font-size:14px;margin:0 0 12px">Aperçu des icônes disponibles</h2>
            <div style="display:flex;flex-wrap:wrap;gap:12px">
                <?php foreach ($icons as $key => $ic) : ?>
                <div style="text-align:center;cursor:pointer;padding:6px;border-radius:6px;border:1px solid transparent;width:80px" title="<?php echo esc_attr($ic['label']); ?>">
                    <div style="width:40px;height:40px;background:#2D6AB0;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 5px">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="white" stroke-width="1.8"><?php echo $ic['svg']; ?></svg>
                    </div>
                    <div style="font-size:9.5px;color:#666;line-height:1.2"><?php echo esc_html($ic['label']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
    var ICONS_SVG = <?php echo json_encode(array_map(fn($k,$v)=>['key'=>$k,'svg'=>$v['svg']], array_keys($icons), $icons)); ?>;
    var COLORS    = {bleu:'#2D6AB0',vert:'#587526',or:'#DEA128'};

    function updatePreview(el) {
        var row     = el.closest('.berre-svc-row');
        var iconSel = row.querySelector('.berre-svc-icon-sel');
        var colSel  = row.querySelector('.berre-svc-color-sel');
        var preview = row.querySelector('.berre-svc-icon-preview');
        var ic = ICONS_SVG.find(i => i.key === iconSel.value);
        var color = COLORS[colSel.value] || '#2D6AB0';
        preview.style.background = color;
        preview.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="white" stroke-width="1.8">' + (ic ? ic.svg : '') + '</svg>';
    }

    function buildRow() {
        var iconOpts  = ICONS_SVG.map(i => `<option value="${i.key}">${i.key}</option>`).join('');
        var row = document.createElement('div');
        row.className = 'berre-svc-row';
        row.draggable = true;
        row.innerHTML = `
            <span class="berre-drag-handle">⠿</span>
            <div class="berre-svc-icon-preview" style="background:#2D6AB0">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="white" stroke-width="1.8"></svg>
            </div>
            <input type="text" name="svc_title[]" placeholder="Titre" required>
            <input type="text" name="svc_desc[]"  placeholder="Description courte">
            <input type="url"  name="svc_url[]"   placeholder="/mon-service">
            <select name="svc_icon[]"  class="berre-svc-icon-sel"  onchange="updatePreview(this)">${ICONS_SVG.map(i=>`<option value="${i.key}">${i.key}</option>`).join('')}</select>
            <select name="svc_color[]" class="berre-svc-color-sel" onchange="updatePreview(this)">
                <option value="bleu">🔵 Bleu</option>
                <option value="vert">🟢 Vert</option>
                <option value="or">🟡 Or</option>
            </select>
            <button type="button" class="button berre-del-svc" onclick="if(confirm('Supprimer ?'))this.closest('.berre-svc-row').remove()" style="color:#c00">✕</button>
        `;
        return row;
    }

    document.getElementById('berre-add-svc').addEventListener('click', function() {
        document.getElementById('berre-svc-list').appendChild(buildRow());
    });

    // Drag & drop
    (function() {
        var list = document.getElementById('berre-svc-list');
        var dragging = null;
        list.addEventListener('dragstart', function(e) {
            dragging = e.target.closest('.berre-svc-row');
            if (dragging) setTimeout(()=>dragging.style.opacity='.4',0);
        });
        list.addEventListener('dragend', function() {
            if (dragging) dragging.style.opacity='';
            dragging=null;
        });
        list.addEventListener('dragover', function(e) {
            e.preventDefault();
            var row = e.target.closest('.berre-svc-row');
            if (row && row !== dragging) {
                var r = row.getBoundingClientRect();
                list.insertBefore(dragging, e.clientY < r.top + r.height/2 ? row : row.nextSibling);
            }
        });
    })();
    </script>
    <?php
}

/* ── Mise à jour du shortcode [berre_services_grid] ──
   Lit maintenant depuis berre_services (nouvelle option dédiée)
   avec fallback sur berre_page_content pour la rétrocompatibilité ── */
remove_shortcode('berre_services_grid');
add_shortcode('berre_services_grid', function() {
    // Priorité : nouvelle option dédiée
    $services = get_option('berre_services');
    if (empty($services)) {
        // Fallback : ancienne option page_content
        $page_content = berre_get_page_content();
        $services = $page_content['services'] ?? [];
    }
    if (empty($services)) $services = berre_services_defaults();

    $ic  = berre_icons_list();
    $out = '<div class="berre-services-grid">';
    foreach (array_slice($services, 0, 8) as $s) {
        $svg = $ic[$s['icon']]['svg'] ?? $ic['document']['svg'];
        $out .= sprintf(
            '<div class="berre-service-card berre-service-card--%s">'
            . '<div class="berre-service-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">%s</svg></div>'
            . '<h3>%s</h3><p>%s</p>'
            . '<a href="%s" class="berre-service-link">Accéder →</a>'
            . '</div>',
            esc_attr($s['color']), $svg,
            esc_html($s['title']), esc_html($s['desc']),
            esc_url($s['url'] ?? '#')
        );
    }
    $out .= '</div>';
    return $out;
});


/* ============================================================
   MENU UNIFIÉ BERRE-LES-ALPES
   Tous les outils du thème sous un seul menu
   ============================================================ */

add_action( 'admin_menu', function() {

    // ── Menu parent ──
    add_menu_page(
        'Berre-les-Alpes',
        'Berre-les-Alpes',
        'manage_options',
        'berre-admin',
        'berre_dashboard_admin',
        'dashicons-admin-site-alt3',
        56
    );

    // ── Sous-menus ──
    add_submenu_page( 'berre-admin', 'Tableau de bord',    'Tableau de bord',    'manage_options', 'berre-admin',         'berre_dashboard_admin' );
    add_submenu_page( 'berre-admin', 'Accès Rapides',      'Accès Rapides',      'manage_options', 'berre-acces-rapides', 'berre_admin_page' );
    add_submenu_page( 'berre-admin', 'Services municipaux','Services municipaux','manage_options', 'berre-services',      'berre_services_admin_page' );
    add_submenu_page( 'berre-admin', 'Éditeur de page',    'Éditeur de page',    'manage_options', 'berre-page-editor',   'berre_page_editor_page' );
    add_submenu_page( 'berre-admin', 'Photo / Vidéo',      '🎬 Photo / Vidéo',   'manage_options', 'berre-hero-media',    'berre_hero_media_page' );
    add_submenu_page( 'berre-admin', 'Outils Thème',       '🛠 Outils',          'manage_options', 'berre-outils',        'berre_outils_page' );
} );

/* ── Tableau de bord du thème ── */
function berre_dashboard_admin() {
    $version = wp_get_theme()->get('Version');
    $pages = [
        ['slug'=>'berre-acces-rapides', 'icon'=>'🔗', 'title'=>'Accès Rapides',       'desc'=>'Gérez les icônes d\'accès rapide (primaires et secondaires).'],
        ['slug'=>'berre-services',      'icon'=>'⚙️', 'title'=>'Services municipaux', 'desc'=>'Modifiez la grille des services sur la page d\'accueil.'],
        ['slug'=>'berre-page-editor',   'icon'=>'✏️', 'title'=>'Éditeur de page',     'desc'=>'Hero, Commune, Contact, Newsletter, Footer — avec aperçu temps réel.'],
        ['slug'=>'berre-hero-media',    'icon'=>'🎬', 'title'=>'Photo / Vidéo',       'desc'=>'Choisissez la photo ou vidéo de fond de la page d\'accueil.'],
        ['slug'=>'berre-outils',        'icon'=>'🛠', 'title'=>'Outils Thème',        'desc'=>'Réinitialiser les templates FSE, vérifier les mises à jour.'],
    ];
    ?>
    <div class="wrap" style="max-width:900px">
        <div style="display:flex;align-items:center;gap:16px;margin:20px 0 28px">
            <div style="width:52px;height:52px;background:<?php echo esc_attr('#2D6AB0'); ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px">🏛</div>
            <div>
                <h1 style="margin:0;font-size:1.6rem">Berre-les-Alpes</h1>
                <p style="margin:2px 0 0;color:#888;font-size:12px">Thème FSE v<?php echo esc_html($version); ?></p>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
        <?php foreach ($pages as $p) : ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $p['slug'])); ?>"
               style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:18px 20px;text-decoration:none;display:block;transition:box-shadow .15s"
               onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.1)'"
               onmouseout="this.style.boxShadow=''">
                <div style="font-size:24px;margin-bottom:8px"><?php echo $p['icon']; ?></div>
                <div style="font-size:14px;font-weight:700;color:#111;margin-bottom:4px"><?php echo esc_html($p['title']); ?></div>
                <div style="font-size:12px;color:#777;line-height:1.4"><?php echo esc_html($p['desc']); ?></div>
            </a>
        <?php endforeach; ?>
        </div>

        <div style="margin-top:28px;background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:18px 20px">
            <h3 style="margin:0 0 10px;font-size:13px">Raccourcis</h3>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <a href="<?php echo admin_url('post-new.php?post_type=actualite'); ?>" class="button">➕ Nouvelle actualité</a>
                <a href="<?php echo admin_url('post-new.php?post_type=agenda'); ?>" class="button">➕ Nouvel événement</a>
                <a href="<?php echo admin_url('edit.php?post_type=actualite'); ?>" class="button">📋 Toutes les actualités</a>
                <a href="<?php echo admin_url('edit.php?post_type=agenda'); ?>" class="button">📋 Tous les événements</a>
                <a href="<?php echo home_url('/'); ?>" target="_blank" class="button">🌐 Voir le site</a>
            </div>
        </div>
    </div>
    <?php
}

/* ============================================================
   UTILITAIRE — Sélecteur URL (interne / externe)
   Utilisé dans Accès Rapides et Services
   ============================================================ */

function berre_get_page_options() {
    $options = [
        ['value' => '/',            'label' => 'Accueil'],
        ['value' => '/actualites',  'label' => 'Actualités'],
        ['value' => '/agenda',      'label' => 'Agenda'],
        ['value' => '/services',    'label' => 'Services'],
        ['value' => '/contact',     'label' => 'Contact'],
    ];
    $pages = get_pages(['post_status' => 'publish', 'sort_column' => 'post_title']);
    foreach ($pages as $page) {
        $path = wp_make_link_relative(get_permalink($page->ID));
        $options[] = ['value' => $path, 'label' => $page->post_title];
    }
    return $options;
}

function berre_url_picker_html( $name, $current_url = '#', $index = 0 ) {
    $page_options = berre_get_page_options();
    $is_external = ( substr($current_url, 0, 4) === 'http' || substr($current_url, 0, 2) === '//' );
    // Vérifier si l'URL interne est dans la liste
    $found_internal = false;
    foreach ($page_options as $opt) {
        if ($opt['value'] === $current_url) { $found_internal = true; break; }
    }
    $type = ($is_external || (!$found_internal && $current_url !== '#' && $current_url !== '')) ? 'external' : 'internal';
    $uid  = 'berre-url-' . $name . '-' . $index . '-' . wp_rand(1000, 9999);
    ob_start();
    ?>
    <div class="berre-url-picker" data-uid="<?php echo esc_attr($uid); ?>">
        <!-- Toggle type -->
        <div class="berre-url-type-toggle">
            <label class="berre-url-toggle-label <?php echo $type==='internal'?'active':''; ?>">
                <input type="radio" name="<?php echo esc_attr($name); ?>_type[]" value="internal"
                       <?php checked($type,'internal'); ?> class="berre-url-radio"
                       onchange="berreUrlToggle('<?php echo esc_js($uid); ?>')">
                🏠 Page interne
            </label>
            <label class="berre-url-toggle-label <?php echo $type==='external'?'active':''; ?>">
                <input type="radio" name="<?php echo esc_attr($name); ?>_type[]" value="external"
                       <?php checked($type,'external'); ?> class="berre-url-radio"
                       onchange="berreUrlToggle('<?php echo esc_js($uid); ?>')">
                🌐 URL externe
            </label>
        </div>

        <!-- Sélecteur interne -->
        <div class="berre-url-internal" id="<?php echo esc_attr($uid); ?>-internal"
             style="<?php echo $type==='external'?'display:none':''; ?>">
            <select name="<?php echo esc_attr($name); ?>_internal[]"
                    class="berre-url-select"
                    onchange="berreUrlSyncInternal('<?php echo esc_js($uid); ?>')">
                <?php foreach ($page_options as $opt) : ?>
                <option value="<?php echo esc_attr($opt['value']); ?>"
                    <?php selected(($type==='internal' ? $current_url : ''), $opt['value']); ?>>
                    <?php echo esc_html($opt['label']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Saisie externe -->
        <div class="berre-url-external" id="<?php echo esc_attr($uid); ?>-external"
             style="<?php echo $type==='internal'?'display:none':''; ?>">
            <input type="url" name="<?php echo esc_attr($name); ?>_external[]"
                   value="<?php echo esc_attr($type==='external' ? $current_url : ''); ?>"
                   placeholder="https://..."
                   class="berre-url-input"
                   oninput="berreUrlSyncExternal('<?php echo esc_js($uid); ?>')">
        </div>

        <!-- Champ caché — URL finale -->
        <input type="hidden" name="<?php echo esc_attr($name); ?>[]"
               id="<?php echo esc_attr($uid); ?>-value"
               value="<?php echo esc_attr($current_url); ?>">
    </div>
    <?php
    return ob_get_clean();
}

/* CSS + JS pour le sélecteur URL — injecté une seule fois */
add_action( 'admin_head', function() {
    static $printed = false;
    if ($printed) return;
    $printed = true;
    ?>
    <style>
    .berre-url-picker { display:flex; flex-direction:column; gap:6px; width:100%; }
    .berre-url-type-toggle { display:flex; gap:0; border:1px solid #ddd; border-radius:5px; overflow:hidden; }
    .berre-url-toggle-label {
        flex:1; text-align:center; padding:5px 8px; font-size:11.5px; font-weight:600;
        cursor:pointer; transition:all .15s; color:#666; background:#f9f9f9;
        display:flex; align-items:center; justify-content:center; gap:4px;
    }
    .berre-url-toggle-label input[type=radio] { display:none; }
    .berre-url-toggle-label.active, .berre-url-toggle-label:has(input:checked) {
        background:#2D6AB0; color:#fff;
    }
    .berre-url-select, .berre-url-input {
        width:100% !important; padding:5px 8px !important;
        border:1px solid #ddd !important; border-radius:4px !important;
        font-size:12px !important;
    }
    .berre-url-input:focus, .berre-url-select:focus {
        border-color:#2D6AB0 !important; outline:none !important;
        box-shadow:0 0 0 2px rgba(45,106,176,.12) !important;
    }
    </style>
    <script>
    function berreUrlToggle(uid) {
        var intDiv  = document.getElementById(uid + '-internal');
        var extDiv  = document.getElementById(uid + '-external');
        var radios  = document.querySelectorAll('[data-uid="' + uid + '"] .berre-url-radio');
        var type    = 'internal';
        radios.forEach(function(r){ if(r.checked) type = r.value; });
        intDiv.style.display = type==='internal' ? '' : 'none';
        extDiv.style.display = type==='external' ? '' : 'none';
        // Sync labels
        document.querySelectorAll('[data-uid="' + uid + '"] .berre-url-toggle-label').forEach(function(l){
            l.classList.toggle('active', l.querySelector('input').value === type);
        });
        if (type==='internal') berreUrlSyncInternal(uid);
        else berreUrlSyncExternal(uid);
    }
    function berreUrlSyncInternal(uid) {
        var sel = document.querySelector('[data-uid="' + uid + '"] .berre-url-select');
        var hid = document.getElementById(uid + '-value');
        if (sel && hid) hid.value = sel.value;
    }
    function berreUrlSyncExternal(uid) {
        var inp = document.querySelector('[data-uid="' + uid + '"] .berre-url-input');
        var hid = document.getElementById(uid + '-value');
        if (inp && hid) hid.value = inp.value;
    }
    </script>
    <?php
} );


/* ============================================================
   LA COMMUNE — Page d'administration dédiée
   Option WP : berre_commune_data
   ============================================================ */

function berre_commune_defaults() {
    return [
        'eyebrow'    => 'Découvrir la Commune',
        'title'      => 'Un village d\'exception entre mer et montagne',
        'desc'       => 'Perché à 682 m à 25 km de Nice, Berre-les-Alpes offre un panorama unique sur la Méditerranée et les Alpes-Maritimes. Village médiéval, sentiers balisés et art de vivre provençal.',
        'stats'      => [
            ['val'=>'1 234',    'lbl'=>'Habitants',  'active'=>true],
            ['val'=>'682 m',    'lbl'=>'Altitude',    'active'=>true],
            ['val'=>'25 km',    'lbl'=>'de Nice',     'active'=>true],
            ['val'=>'9,58 km²', 'lbl'=>'Superficie',  'active'=>true],
        ],
        'btn_active' => true,
        'btn_text'   => 'Explorer nos sentiers →',
        'btn_url'    => '/',
    ];
}

function berre_get_commune_data() {
    $saved = get_option( 'berre_commune_data' );
    if ( ! empty($saved) && is_array($saved) ) {
        $d = array_merge( berre_commune_defaults(), $saved );
        // Migration ancien format (stat1_val…stat4_val) vers nouveau format (stats[])
        if ( empty($d['stats']) && isset($d['stat1_val']) ) {
            $d['stats'] = [];
            for ( $i = 1; $i <= 4; $i++ ) {
                if ( ! empty($d["stat{$i}_val"]) ) {
                    $d['stats'][] = ['val'=>$d["stat{$i}_val"],'lbl'=>$d["stat{$i}_lbl"],'active'=>true];
                }
            }
        }
        return $d;
    }
    $pc = get_option('berre_page_content');
    if ( ! empty($pc['commune']) ) return array_merge( berre_commune_defaults(), $pc['commune'] );
    return berre_commune_defaults();
}

/* ── Sous-menu dans Berre-les-Alpes ── */
add_action( 'admin_menu', function() {
    add_submenu_page( 'berre-admin', 'La Commune', '🏘 La Commune', 'manage_options', 'berre-commune', 'berre_commune_admin_page' );
}, 20 );

/* ── Sauvegarde ── */
add_action( 'admin_init', function() {
    if ( ! isset($_POST['berre_save_commune']) ) return;
    if ( ! wp_verify_nonce($_POST['berre_commune_nonce'] ?? '', 'berre_save_commune') ) wp_die('Sécurité invalide.');
    if ( ! current_user_can('manage_options') ) return;

    // Statistiques
    $stats = [];
    $stat_vals    = (array)($_POST['stat_val']    ?? []);
    $stat_lbls    = (array)($_POST['stat_lbl']    ?? []);
    $stat_actives = (array)($_POST['stat_active'] ?? []);
    foreach ( $stat_vals as $i => $val ) {
        $val = sanitize_text_field($val);
        if ( $val === '' ) continue;
        $stats[] = [
            'val'    => $val,
            'lbl'    => sanitize_text_field($stat_lbls[$i] ?? ''),
            'active' => isset($stat_actives[$i]) && $stat_actives[$i] === '1',
        ];
    }

    update_option( 'berre_commune_data', [
        'eyebrow'    => sanitize_text_field($_POST['commune_eyebrow'] ?? ''),
        'title'      => sanitize_text_field($_POST['commune_title']   ?? ''),
        'desc'       => sanitize_textarea_field($_POST['commune_desc'] ?? ''),
        'stats'      => $stats,
        'btn_active' => isset($_POST['commune_btn_active']) && $_POST['commune_btn_active'] === '1',
        'btn_text'   => sanitize_text_field($_POST['commune_btn_text'] ?? ''),
        'btn_url'    => esc_url_raw($_POST['commune_btn_url'][0] ?? '/'),
    ]);
    set_transient( 'berre_commune_saved', true, 10 );
} );

/* ── Page admin ── */
function berre_commune_admin_page() {
    $d     = berre_get_commune_data();
    $stats = $d['stats'] ?? [];
    $saved = get_transient('berre_commune_saved');
    if ($saved) delete_transient('berre_commune_saved');
    $btn_active = $d['btn_active'] ?? true;
    ?>
    <style>
    .berre-commune-wrap { max-width:820px; margin-top:20px; }
    .berre-c-card { background:#fff; border:1px solid #ddd; border-radius:8px; overflow:hidden; margin-bottom:18px; }
    .berre-c-head { background:#f6f7f7; border-bottom:1px solid #eee; padding:11px 18px; display:flex; align-items:center; justify-content:space-between; }
    .berre-c-head h2 { margin:0; font-size:13px; }
    .berre-c-body { padding:18px 20px; }
    .berre-c-field { margin-bottom:13px; }
    .berre-c-field label { display:block; font-size:11px; font-weight:700; color:#555; margin-bottom:4px; text-transform:uppercase; letter-spacing:.07em; }
    .berre-c-field input[type=text], .berre-c-field textarea { width:100%; padding:7px 9px; border:1px solid #ddd; border-radius:4px; font-size:13.5px; font-family:inherit; box-sizing:border-box; }
    .berre-c-field textarea { height:90px; resize:vertical; }
    /* Tableau stats */
    .berre-stats-table { width:100%; border-collapse:collapse; font-size:13px; }
    .berre-stats-table th { text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#888; padding:6px 8px; border-bottom:2px solid #eee; }
    .berre-stats-table td { padding:6px 6px; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
    .berre-stats-table tr:last-child td { border-bottom:none; }
    .berre-stat-row input[type=text] { width:100%; padding:5px 8px; border:1px solid #ddd; border-radius:4px; font-size:13px; font-family:inherit; }
    .berre-stat-row input:focus { border-color:#2D6AB0; outline:none; }
    .berre-drag-h { cursor:grab; color:#ccc; font-size:18px; padding:0 6px; user-select:none; }
    .berre-toggle-sw { position:relative; display:inline-block; width:36px; height:20px; }
    .berre-toggle-sw input { opacity:0; width:0; height:0; }
    .berre-toggle-sw .slider { position:absolute; cursor:pointer; inset:0; background:#ccc; border-radius:20px; transition:.2s; }
    .berre-toggle-sw .slider:before { position:absolute; content:""; height:14px; width:14px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.2s; }
    .berre-toggle-sw input:checked + .slider { background:#2D6AB0; }
    .berre-toggle-sw input:checked + .slider:before { transform:translateX(16px); }
    .berre-stat-row.disabled { opacity:.45; }
    .berre-del-btn { background:none; border:none; color:#bbb; cursor:pointer; font-size:16px; padding:2px 6px; border-radius:4px; }
    .berre-del-btn:hover { color:#c00; background:#fff0f0; }
    /* Aperçu */
    .berre-prev-commune { background:#2D6AB0; border-radius:8px; padding:24px 28px 20px; margin-bottom:18px; }
    .berre-prev-commune__eyebrow { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.14em; color:rgba(255,255,255,.55); margin:0 0 8px; }
    .berre-prev-commune__title { font-size:1.45rem; font-weight:800; color:#fff; margin:0 0 10px; line-height:1.3; }
    .berre-prev-commune__desc { font-size:12.5px; color:rgba(255,255,255,.75); line-height:1.65; margin:0 0 14px; }
    .berre-prev-commune__stats { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:14px; }
    .berre-prev-commune__stat { background:rgba(255,255,255,.14); border-radius:6px; padding:10px 14px; min-width:80px; }
    .berre-prev-commune__stat strong { display:block; font-size:1.1rem; font-weight:800; color:#fff; }
    .berre-prev-commune__stat span { font-size:9.5px; text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.6); }
    .berre-prev-commune__btn { display:inline-block; background:#DEA128; color:#111; font-size:12.5px; font-weight:700; padding:9px 20px; border-radius:22px; text-decoration:none; }
    </style>

    <div class="wrap berre-commune-wrap">
        <h1>🏘 La Commune</h1>
        <?php if ($saved): ?><div class="notice notice-success is-dismissible"><p>✅ Sauvegardé.</p></div><?php endif; ?>

        <!-- APERÇU TEMPS RÉEL -->
        <div class="berre-prev-commune">
            <p class="berre-prev-commune__eyebrow" id="pv-eyebrow"><?php echo esc_html($d['eyebrow']); ?></p>
            <h2 class="berre-prev-commune__title"  id="pv-title"><?php echo esc_html($d['title']); ?></h2>
            <p class="berre-prev-commune__desc"    id="pv-desc"><?php echo nl2br(esc_html($d['desc'])); ?></p>
            <div class="berre-prev-commune__stats" id="pv-stats"></div>
            <?php if ($btn_active): ?>
            <a class="berre-prev-commune__btn" id="pv-btn" style=""><?php echo esc_html($d['btn_text']); ?></a>
            <?php else: ?>
            <a class="berre-prev-commune__btn" id="pv-btn" style="display:none"><?php echo esc_html($d['btn_text']); ?></a>
            <?php endif; ?>
        </div>

        <form method="post" id="berre-commune-form">
            <?php wp_nonce_field('berre_save_commune','berre_commune_nonce'); ?>

            <!-- TEXTES -->
            <div class="berre-c-card">
                <div class="berre-c-head"><h2>Textes</h2></div>
                <div class="berre-c-body">
                    <div class="berre-c-field">
                        <label>Surtitre</label>
                        <input type="text" name="commune_eyebrow" value="<?php echo esc_attr($d['eyebrow']); ?>"
                               oninput="document.getElementById('pv-eyebrow').textContent=this.value">
                    </div>
                    <div class="berre-c-field">
                        <label>Titre principal</label>
                        <input type="text" name="commune_title" value="<?php echo esc_attr($d['title']); ?>"
                               oninput="document.getElementById('pv-title').textContent=this.value">
                    </div>
                    <div class="berre-c-field">
                        <label>Description</label>
                        <textarea name="commune_desc" oninput="document.getElementById('pv-desc').textContent=this.value"><?php echo esc_textarea($d['desc']); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- STATISTIQUES -->
            <div class="berre-c-card">
                <div class="berre-c-head">
                    <h2>Statistiques</h2>
                    <button type="button" id="berre-add-stat" class="button button-small">➕ Ajouter</button>
                </div>
                <div class="berre-c-body" style="padding:0">
                    <table class="berre-stats-table">
                        <thead>
                            <tr>
                                <th style="width:28px"></th>
                                <th style="width:44px;text-align:center">Actif</th>
                                <th>Chiffre / Valeur</th>
                                <th>Libellé</th>
                                <th style="width:36px"></th>
                            </tr>
                        </thead>
                        <tbody id="berre-stats-body">
                        <?php foreach ($stats as $i => $st): ?>
                        <tr class="berre-stat-row<?php echo $st['active'] ? '' : ' disabled'; ?>" draggable="true">
                            <td><span class="berre-drag-h">⠿</span></td>
                            <td style="text-align:center">
                                <label class="berre-toggle-sw">
                                    <input type="hidden"   name="stat_active[<?php echo $i; ?>]" value="0">
                                    <input type="checkbox" name="stat_active[<?php echo $i; ?>]" value="1"
                                           <?php checked($st['active']); ?>
                                           onchange="this.closest('tr').classList.toggle('disabled',!this.checked);refreshPreviewStats()">
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td><input type="text" name="stat_val[<?php echo $i; ?>]" value="<?php echo esc_attr($st['val']); ?>"
                                       placeholder="1 234" oninput="refreshPreviewStats()"></td>
                            <td><input type="text" name="stat_lbl[<?php echo $i; ?>]" value="<?php echo esc_attr($st['lbl']); ?>"
                                       placeholder="Habitants" oninput="refreshPreviewStats()"></td>
                            <td><button type="button" class="berre-del-btn"
                                        onclick="this.closest('tr').remove();refreshPreviewStats()">✕</button></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- BOUTON -->
            <div class="berre-c-card">
                <div class="berre-c-head">
                    <h2>Bouton</h2>
                    <label class="berre-toggle-sw" title="Afficher/masquer le bouton">
                        <input type="hidden"   name="commune_btn_active" value="0">
                        <input type="checkbox" name="commune_btn_active" value="1"
                               <?php checked($btn_active); ?>
                               id="commune-btn-toggle"
                               onchange="
                                 var btn=document.getElementById('pv-btn');
                                 btn.style.display=this.checked?'':'none';
                                 document.getElementById('commune-btn-fields').style.opacity=this.checked?'1':'.4';
                               ">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="berre-c-body" id="commune-btn-fields" style="opacity:<?php echo $btn_active?'1':'.4'; ?>">
                    <div class="berre-c-field">
                        <label>Texte du bouton</label>
                        <input type="text" name="commune_btn_text" value="<?php echo esc_attr($d['btn_text']); ?>"
                               oninput="document.getElementById('pv-btn').textContent=this.value">
                    </div>
                    <div class="berre-c-field">
                        <label>Lien</label>
                        <?php echo berre_url_picker_html('commune_btn_url', $d['btn_url'] ?? '/', 0); ?>
                    </div>
                </div>
            </div>

            <input type="submit" name="berre_save_commune" class="button button-primary button-large" value="💾 Enregistrer">
        </form>
    </div>

    <script>
    var statIndex = <?php echo count($stats); ?>;

    // Aperçu stats temps réel
    function refreshPreviewStats() {
        var rows = document.querySelectorAll('#berre-stats-body .berre-stat-row:not(.disabled)');
        // en fait vérifier le checkbox actif
        var container = document.getElementById('pv-stats');
        container.innerHTML = '';
        document.querySelectorAll('#berre-stats-body .berre-stat-row').forEach(function(tr) {
            var cb = tr.querySelector('input[type=checkbox]');
            if (!cb || !cb.checked) return;
            var val = tr.querySelector('input[name^="stat_val"]')?.value || '';
            var lbl = tr.querySelector('input[name^="stat_lbl"]')?.value || '';
            if (!val) return;
            var div = document.createElement('div');
            div.className = 'berre-prev-commune__stat';
            div.innerHTML = '<strong>' + val + '</strong><span>' + lbl + '</span>';
            container.appendChild(div);
        });
    }
    refreshPreviewStats();

    // Ajouter une stat
    document.getElementById('berre-add-stat').addEventListener('click', function() {
        var i = statIndex++;
        var tr = document.createElement('tr');
        tr.className = 'berre-stat-row';
        tr.draggable = true;
        tr.innerHTML = '<td><span class="berre-drag-h">⠿</span></td>' +
            '<td style="text-align:center"><label class="berre-toggle-sw">' +
            '<input type="hidden" name="stat_active[' + i + ']" value="0">' +
            '<input type="checkbox" name="stat_active[' + i + ']" value="1" checked ' +
            'onchange="this.closest(\'tr\').classList.toggle(\'disabled\',!this.checked);refreshPreviewStats()">' +
            '<span class="slider"></span></label></td>' +
            '<td><input type="text" name="stat_val[' + i + ']" placeholder="Valeur" oninput="refreshPreviewStats()"></td>' +
            '<td><input type="text" name="stat_lbl[' + i + ']" placeholder="Libellé" oninput="refreshPreviewStats()"></td>' +
            '<td><button type="button" class="berre-del-btn" onclick="this.closest(\'tr\').remove();refreshPreviewStats()">✕</button></td>';
        document.getElementById('berre-stats-body').appendChild(tr);
        tr.querySelector('input[type=text]').focus();
    });

    // Drag & drop sur le tableau
    (function() {
        var tbody = document.getElementById('berre-stats-body');
        var dragging = null;
        tbody.addEventListener('dragstart', function(e) {
            dragging = e.target.closest('tr');
            if (dragging) setTimeout(function(){ dragging.style.opacity='.4'; }, 0);
        });
        tbody.addEventListener('dragend', function() {
            if (dragging) { dragging.style.opacity=''; dragging=null; }
        });
        tbody.addEventListener('dragover', function(e) {
            e.preventDefault();
            var row = e.target.closest('tr');
            if (row && row !== dragging) {
                var r = row.getBoundingClientRect();
                tbody.insertBefore(dragging, e.clientY < r.top + r.height/2 ? row : row.nextSibling);
            }
        });
        tbody.addEventListener('drop', function(e) { e.preventDefault(); refreshPreviewStats(); });
    })();

    // Renuméroter les champs avant soumission
    document.getElementById('berre-commune-form').addEventListener('submit', function() {
        document.querySelectorAll('#berre-stats-body .berre-stat-row').forEach(function(tr, i) {
            tr.querySelectorAll('input').forEach(function(inp) {
                inp.name = inp.name.replace(/\[\d+\]/, '[' + i + ']');
            });
        });
    });
    </script>
    <?php
}

add_shortcode('berre_commune_content', function() {
    $c = berre_get_commune_data();
    $stats = $c['stats'] ?? [];
    $btn_active = $c['btn_active'] ?? true;
    ob_start(); ?>
    <p class="berre-commune-eyebrow"><?php echo esc_html($c['eyebrow']); ?></p>
    <h2 class="berre-commune-title"><?php echo esc_html($c['title']); ?></h2>
    <p class="berre-commune-desc"><?php echo nl2br(esc_html($c['desc'] ?: ($c['description'] ?? ''))); ?></p>
    <?php if (!empty($stats)): ?>
    <div class="berre-commune-facts">
        <?php foreach ($stats as $st):
            if (!($st['active'] ?? true)) continue; ?>
        <div class="berre-cf">
            <strong><?php echo esc_html($st['val']); ?></strong>
            <span><?php echo esc_html($st['lbl']); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if ($btn_active && !empty($c['btn_text'])): ?>
    <a class="berre-commune-btn" href="<?php echo esc_url($c['btn_url'] ?? '/'); ?>">
        <?php echo esc_html($c['btn_text']); ?>
    </a>
    <?php endif; ?>
    <?php
    return ob_get_clean();
});



/* ============================================================
   COULEURS PAR CATÉGORIE D'ACTUALITÉ
   Term meta : berre_cat_color
   ============================================================ */

/* ── Champ couleur dans l'édition de catégorie ── */
add_action( 'categorie_actu_edit_form_fields', function( $term ) {
    $color = get_term_meta( $term->term_id, 'berre_cat_color', true ) ?: '#2D6AB0';
    ?>
    <tr class="form-field">
        <th scope="row"><label for="berre_cat_color">🎨 Couleur de la catégorie</label></th>
        <td>
            <input type="color" name="berre_cat_color" id="berre_cat_color"
                   value="<?php echo esc_attr($color); ?>" style="width:60px;height:32px;cursor:pointer;border:1px solid #ddd;border-radius:4px;padding:2px">
            <button type="button" onclick="document.getElementById('berre_cat_color').value='#2D6AB0'"
                    style="margin-left:8px;font-size:12px;cursor:pointer">⟳ Réinitialiser</button>
            <p class="description">Couleur du badge dans les cards d'actualité et sur la page article.</p>
        </td>
    </tr>
    <?php
} );

/* Formulaire d'AJOUT de catégorie (nouvelle) */
add_action( 'categorie_actu_add_form_fields', function() {
    ?>
    <div class="form-field">
        <label for="berre_cat_color">🎨 Couleur de la catégorie</label>
        <input type="color" name="berre_cat_color" id="berre_cat_color"
               value="#2D6AB0" style="width:60px;height:32px;cursor:pointer;border:1px solid #ddd;border-radius:4px;padding:2px">
        <p>Couleur du badge dans les cards d'actualité.</p>
    </div>
    <?php
} );

/* ── Sauvegarde ── */
add_action( 'edited_categorie_actu', function( $term_id ) {
    if ( isset($_POST['berre_cat_color']) ) {
        update_term_meta( $term_id, 'berre_cat_color', sanitize_hex_color($_POST['berre_cat_color']) );
    }
} );
add_action( 'created_categorie_actu', function( $term_id ) {
    if ( isset($_POST['berre_cat_color']) ) {
        update_term_meta( $term_id, 'berre_cat_color', sanitize_hex_color($_POST['berre_cat_color']) );
    }
} );

/* ── Couleurs catégories — CSS haute spécificité + render_block ── */

/* Cache des couleurs par terme */
function berre_get_cat_colors() {
    static $map = null;
    if ( $map !== null ) return $map;
    $map   = [];
    $terms = get_terms( ['taxonomy' => 'categorie_actu', 'hide_empty' => false] );
    if ( is_wp_error($terms) ) return $map;
    foreach ( $terms as $t ) {
        $c = get_term_meta( $t->term_id, 'berre_cat_color', true );
        if ( $c ) $map[ $t->term_id ] = [ 'color' => $c, 'slug' => $t->slug, 'term' => $t ];
    }
    return $map;
}

/* CSS injecté dans <head> avec URLs exactes → sélecteur [href="..."] infaillible */
add_action( 'wp_head', function() {
    $colors = berre_get_cat_colors();
    if ( empty($colors) ) return;

    $css = '<style id="berre-cat-colors">';
    foreach ( $colors as $tid => $info ) {
        $color = $info['color'];
        $term  = $info['term'];
        $link  = get_term_link( $term );
        if ( is_wp_error($link) ) continue;

        // URL relative pour matcher quelle que soit la configuration
        $path  = wp_make_link_relative( $link );
        $rgb   = sscanf( $color, '#%02x%02x%02x' );
        $light = sprintf( 'rgba(%d,%d,%d,0.13)', $rgb[0], $rgb[1], $rgb[2] );

        // CSS avec URL exactes (guillemets simples PHP pour éviter l'échappement)
        $q = '"';  // guillemet double comme variable pour l'insérer dans les sélecteurs CSS
        foreach ( array('.berre-actu-card__cat', '.berre-home-actu-card__cat') as $sel ) {
            $css .= $sel . ' a[href=' . $q . $path . $q . '],' . "\n";
            $css .= $sel . ' a[href=' . $q . $link . $q . ']';
            $css .= ' { color:' . $color . ' !important }' . "\n";
        }
        $css .= '.berre-article-cats a[href=' . $q . $path . $q . '],' . "\n";
        $css .= '.berre-article-cats a[href=' . $q . $link . $q . ']';
        $css .= ' { color:' . $color . ' !important; background:' . $light . ' !important }' . "\n";
        $css .= '.berre-article-tags a[href=' . $q . $path . $q . '],' . "\n";
        $css .= '.berre-article-tags a[href=' . $q . $link . $q . ']';
        $css .= ' { color:' . $color . ' !important; border-color:' . $color . ' !important }' . "\n";
        $css .= '.berre-article-tags a[href=' . $q . $path . $q . ']:hover,' . "\n";
        $css .= '.berre-article-tags a[href=' . $q . $link . $q . ']:hover';
        $css .= ' { background:' . $color . ' !important; color:#fff !important }' . "\n";
        $css .= '.berre-filter[href=' . $q . $path . $q . '].berre-filter--active,' . "\n";
        $css .= '.berre-filter[href=' . $q . $link . $q . '].berre-filter--active';
        $css .= ' { color:' . $color . ' !important; border-bottom-color:' . $color . ' !important }' . "\n";
    }  // fin foreach $colors
    $css .= '</style>';
    echo $css;
} );

/* render_block en couche secondaire — modifie le HTML directement si disponible */
add_filter( 'render_block', function( $html, $block ) {
    if ( ( $block['blockName'] ?? '' ) !== 'core/post-terms' ) return $html;
    if ( ( $block['attrs']['term'] ?? '' ) !== 'categorie_actu' )  return $html;
    if ( empty($html) ) return $html;

    $post_id = get_the_ID();
    if ( ! $post_id ) return $html;

    $terms = get_the_terms( $post_id, 'categorie_actu' );
    if ( ! $terms || is_wp_error($terms) ) return $html;

    $colors = berre_get_cat_colors();
    if ( empty($colors) ) return $html;

    $slug_map = [];
    foreach ( $colors as $tid => $info ) {
        $slug_map[ $info['slug'] ] = $info['color'];
        $slug_map[ $info['slug'] . '-2' ] = $info['color']; // variantes de slug WordPress
    }

    $class = $block['attrs']['className'] ?? '';
    $is_article_badge = strpos($class, 'berre-article-cats') !== false
                     || strpos($class, 'berre-article-tags') !== false;

    foreach ( $terms as $term ) {
        $color = $slug_map[ $term->slug ] ?? null;
        if ( ! $color ) continue;
        $rgb   = sscanf( $color, '#%02x%02x%02x' );
        $light = sprintf( 'rgba(%d,%d,%d,0.13)', $rgb[0], $rgb[1], $rgb[2] );
        $slug  = preg_quote( $term->slug, '/' );
        $style = $is_article_badge
            ? 'color:' . $color . ';background:' . $light . ';border-color:' . $color
            : 'color:' . $color;
        // Ajouter le style sur le lien contenant le slug
        $html = preg_replace(
            '#(<a\b[^>]*\bhref=[^>]*' . $slug . '[^>]*)>#i',
            '<a$1 style="' . esc_attr( $style ) . '">',
            $html
        );
    }
    return $html;
}, 10, 2 );

/* ── Colonne "Couleur" dans la liste des catégories ── */
add_filter( 'manage_edit-categorie_actu_columns', function( $cols ) {
    $cols['berre_color'] = 'Couleur';
    return $cols;
} );
add_filter( 'manage_categorie_actu_custom_column', function( $out, $col, $term_id ) {
    if ( $col !== 'berre_color' ) return $out;
    $color = get_term_meta( $term_id, 'berre_cat_color', true ) ?: '#2D6AB0';
    return '<span style="display:inline-block;width:20px;height:20px;border-radius:50%;background:'
         . esc_attr($color) . ';border:1px solid rgba(0,0,0,.1);vertical-align:middle"></span> '
         . '<code style="font-size:11px">' . esc_html($color) . '</code>';
}, 10, 3 );

/* ── Supprimer le texte "Menu" du bouton hamburger en PHP ── */
add_filter( 'render_block', function( $html, $block ) {
    if ( ( $block['blockName'] ?? '' ) !== 'core/navigation' ) return $html;
    $html = preg_replace(
        '#(<button[^>]*wp-block-navigation__responsive-container-open[^>]*>)(\s*<svg[^>]*>.*?</svg>)\s*(?:Menu|Ouvrir|Open|Naviguer|Fermer)?\s*(</button>)#si',
        '$1$2$3',
        $html
    );
    return $html;
}, 5, 2 );



/* ============================================================
   ACTUALITÉS — Date de dépublication automatique
   ============================================================ */

/* ── Metabox dans l'éditeur ── */
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'berre_expiry_date',
        '📅 Dépublication automatique',
        'berre_expiry_metabox_html',
        'actualite', 'side', 'default'
    );
} );

function berre_expiry_metabox_html( $post ) {
    wp_nonce_field( 'berre_expiry_save', 'berre_expiry_nonce' );
    $date = get_post_meta( $post->ID, 'berre_expiry_date', true );
    ?>
    <p style="font-size:12px;color:#666;margin-bottom:8px">
        L'article sera automatiquement dépublié à la date choisie.<br>
        Laissez vide pour ne pas dépublier.
    </p>
    <input type="date" name="berre_expiry_date" value="<?php echo esc_attr($date); ?>"
           style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;font-size:13px">
    <?php if ($date): ?>
    <p style="font-size:11px;color:<?php echo $date < date('Y-m-d') ? '#c00' : '#587526'; ?>;margin-top:6px">
        <?php echo $date < date('Y-m-d') ? '⚠️ Date passée — cet article devrait être dépublié.' : '✓ Actif jusqu\'au ' . date('d/m/Y', strtotime($date)); ?>
    </p>
    <?php endif; ?>
    <?php
}

add_action( 'save_post_actualite', function( $post_id ) {
    if ( ! isset($_POST['berre_expiry_nonce']) || ! wp_verify_nonce($_POST['berre_expiry_nonce'], 'berre_expiry_save') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;
    $date = sanitize_text_field( $_POST['berre_expiry_date'] ?? '' );
    if ( $date ) {
        update_post_meta( $post_id, 'berre_expiry_date', $date );
    } else {
        delete_post_meta( $post_id, 'berre_expiry_date' );
    }
} );

/* ── Tâche CRON quotidienne — dépublier les articles expirés ── */
add_action( 'init', function() {
    if ( ! wp_next_scheduled('berre_check_expiry') ) {
        wp_schedule_event( time(), 'daily', 'berre_check_expiry' );
    }
} );

add_action( 'berre_check_expiry', function() {
    $today = date('Y-m-d');
    $posts = get_posts( [
        'post_type'      => 'actualite',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [ [
            'key'     => 'berre_expiry_date',
            'value'   => $today,
            'compare' => '<',
            'type'    => 'DATE',
        ] ],
    ] );
    foreach ( $posts as $post ) {
        wp_update_post( [ 'ID' => $post->ID, 'post_status' => 'draft' ] );
    }
} );

/* ── Exclure les articles expirés côté frontend ── */
add_action( 'pre_get_posts', function( $query ) {
    if ( is_admin() ) return;
    if ( $query->get('post_type') !== 'actualite' && ! in_array('actualite', (array)$query->get('post_type')) ) return;
    $today = date('Y-m-d');
    $existing = $query->get('meta_query') ?: [];
    $existing[] = [
        'relation' => 'OR',
        [ 'key' => 'berre_expiry_date', 'compare' => 'NOT EXISTS' ],
        [ 'key' => 'berre_expiry_date', 'value' => '', 'compare' => '=' ],
        [ 'key' => 'berre_expiry_date', 'value' => $today, 'compare' => '>=', 'type' => 'DATE' ],
    ];
    $query->set( 'meta_query', $existing );
} );

/* ── Afficher la date d'expiration dans la liste des articles ── */
add_filter( 'manage_actualite_posts_columns', function( $cols ) {
    $cols['berre_expiry'] = '📅 Expiration';
    return $cols;
} );
add_action( 'manage_actualite_posts_custom_column', function( $col, $post_id ) {
    if ( $col !== 'berre_expiry' ) return;
    $date = get_post_meta( $post_id, 'berre_expiry_date', true );
    if ( ! $date ) { echo '<span style="color:#ccc">—</span>'; return; }
    $past = $date < date('Y-m-d');
    echo '<span style="color:' . ($past ? '#c00' : '#333') . ';font-size:12px">'
       . ($past ? '⚠️ ' : '') . date('d/m/Y', strtotime($date)) . '</span>';
}, 10, 2 );

/* ============================================================
   FOOTER — Informations contact (horaires, adresse, bouton)
   Option WP : berre_mairie_info
   ============================================================ */

function berre_mairie_info_defaults() {
    return [
        'hours'    => "Lundi – Vendredi : 9h00 – 12h00\nLundi & Mercredi : 14h00 – 17h00\nFermé le week-end et jours fériés",
        'address'  => "Place de la Mairie\n06390 Berre-les-Alpes",
        'phone'    => '04 93 91 80 07',
        'email'    => 'mairie@berrelesalpes.fr',
        'btn_text' => 'Nous écrire',
    ];
}

function berre_get_mairie_info() {
    $saved = get_option('berre_mairie_info');
    return empty($saved) ? berre_mairie_info_defaults() : array_merge(berre_mairie_info_defaults(), $saved);
}

/* Sous-menu admin */
add_action( 'admin_menu', function() {
    add_submenu_page( 'berre-admin', 'Infos Mairie', '🏛 Infos Mairie', 'manage_options', 'berre-mairie-info', 'berre_mairie_info_page' );
}, 22 );

add_action( 'admin_init', function() {
    if ( ! isset($_POST['berre_save_mairie_info']) ) return;
    if ( ! wp_verify_nonce($_POST['berre_mairie_nonce'] ?? '', 'berre_save_mairie_info') ) return;
    if ( ! current_user_can('manage_options') ) return;
    update_option( 'berre_mairie_info', [
        'hours'   => sanitize_textarea_field( $_POST['mairie_hours']   ?? '' ),
        'address' => sanitize_textarea_field( $_POST['mairie_address'] ?? '' ),
        'phone'   => sanitize_text_field(     $_POST['mairie_phone']   ?? '' ),
        'email'   => sanitize_email(          $_POST['mairie_email']   ?? '' ),
        'btn_text'=> sanitize_text_field(     $_POST['mairie_btn_text']?? '' ),
    ]);
    set_transient('berre_mairie_saved', true, 10);
} );

function berre_mairie_info_page() {
    $d = berre_get_mairie_info();
    $saved = get_transient('berre_mairie_saved');
    if ($saved) delete_transient('berre_mairie_saved');
    ?>
    <div class="wrap" style="max-width:680px;margin-top:20px">
        <h1>🏛 Infos Mairie — Footer</h1>
        <p style="color:#666">Ces informations s'affichent en bas de toutes les pages (footer).</p>
        <?php if ($saved): ?><div class="notice notice-success is-dismissible"><p>✅ Sauvegardé.</p></div><?php endif; ?>
        <form method="post" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:24px;margin-top:16px">
            <?php wp_nonce_field('berre_save_mairie_info','berre_mairie_nonce'); ?>
            <?php foreach([
                ['mairie_hours',   'hours',   '🕐 Horaires d\'ouverture', 'textarea', "Lundi 9h–12h…"],
                ['mairie_address', 'address', '📍 Adresse',               'textarea', "Place de la Mairie\n06390 Berre-les-Alpes"],
                ['mairie_phone',   'phone',   '📞 Téléphone',             'text',     '04 93 91 80 07'],
                ['mairie_email',   'email',   '✉️ Email',                  'email',    'mairie@berrelesalpes.fr'],
                ['mairie_btn_text','btn_text','🔵 Texte du bouton',       'text',     'Nous écrire'],
            ] as [$name, $key, $label, $type, $ph]): ?>
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:12px;font-weight:700;color:#444;margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em"><?php echo $label; ?></label>
                <?php if ($type === 'textarea'): ?>
                <textarea name="<?php echo $name; ?>" placeholder="<?php echo esc_attr($ph); ?>"
                          style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;height:80px;font-family:inherit;font-size:13px;resize:vertical"><?php echo esc_textarea($d[$key]); ?></textarea>
                <?php else: ?>
                <input type="<?php echo $type; ?>" name="<?php echo $name; ?>" value="<?php echo esc_attr($d[$key]); ?>"
                       placeholder="<?php echo esc_attr($ph); ?>"
                       style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px;font-size:13px">
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <input type="submit" name="berre_save_mairie_info" class="button button-primary" value="💾 Enregistrer">
        </form>
    </div>
    <?php
}

/* Shortcode [berre_footer_contact] */
add_shortcode( 'berre_footer_contact', function() {
    $d = berre_get_mairie_info();
    ob_start(); ?>
    <div class="berre-footer-contact">
        <?php if (!empty($d['hours'])): ?>
        <div class="berre-footer-contact__block">
            <strong class="berre-footer-contact__label">Horaires d'ouverture</strong>
            <p class="berre-footer-contact__hours"><?php echo nl2br(esc_html($d['hours'])); ?></p>
        </div>
        <?php endif; ?>
        <?php if (!empty($d['address'])): ?>
        <div class="berre-footer-contact__block">
            <strong class="berre-footer-contact__label">Adresse</strong>
            <p class="berre-footer-contact__address"><?php echo nl2br(esc_html($d['address'])); ?></p>
        </div>
        <?php endif; ?>
        <?php if (!empty($d['phone'])): ?>
        <a href="tel:<?php echo esc_attr(preg_replace('/\s/','',$d['phone'])); ?>" class="berre-footer-contact__phone">
            📞 <?php echo esc_html($d['phone']); ?>
        </a>
        <?php endif; ?>
        <?php if (!empty($d['email'])): ?>
        <a href="mailto:<?php echo esc_attr($d['email']); ?>" class="berre-footer-contact__btn">
            ✉️ <?php echo esc_html($d['btn_text'] ?: 'Nous écrire'); ?>
        </a>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
} );


/* ============================================================
   FOOTER — Colonnes de navigation (droite)
   Option WP : berre_footer_nav
   ============================================================ */

function berre_footer_nav_defaults() {
    return [
        [
            'title' => 'Vivre à Berre',
            'links' => [
                ['label' => 'La Commune',     'url' => '/commune',    'ext' => false],
                ['label' => 'Agenda',         'url' => '/agenda',     'ext' => false],
                ['label' => 'Tourisme',       'url' => '/tourisme',   'ext' => false],
                ['label' => 'Associations',   'url' => '/associations','ext' => false],
            ],
        ],
        [
            'title' => 'Votre Mairie',
            'links' => [
                ['label' => 'Actualités',       'url' => '/actualites', 'ext' => false],
                ['label' => 'Services',         'url' => '/services',   'ext' => false],
                ['label' => 'État Civil',       'url' => '/etat-civil', 'ext' => false],
                ['label' => 'Urbanisme',        'url' => '/urbanisme',  'ext' => false],
            ],
        ],
        [
            'title' => 'Pratique',
            'links' => [
                ['label' => 'Contact',           'url' => '/contact',          'ext' => false],
                ['label' => 'Mes Démarches 06', 'url' => 'https://mesdemarches06.fr', 'ext' => true],
                ['label' => 'Service-Public.fr', 'url' => 'https://www.service-public.fr', 'ext' => true],
                ['label' => 'Mentions légales',  'url' => '/mentions-legales', 'ext' => false],
            ],
        ],
    ];
}

function berre_get_footer_nav() {
    $saved = get_option('berre_footer_nav');
    return (is_array($saved) && !empty($saved)) ? $saved : berre_footer_nav_defaults();
}

/* ── Sous-menu admin ── */
add_action( 'admin_menu', function() {
    add_submenu_page( 'berre-admin', 'Footer — Liens', '🔗 Footer Liens', 'manage_options', 'berre-footer-nav', 'berre_footer_nav_page' );
}, 23 );

/* ── Sauvegarde ── */
add_action( 'admin_init', function() {
    if ( ! isset($_POST['berre_save_footer_nav']) ) return;
    if ( ! wp_verify_nonce($_POST['berre_footer_nav_nonce'] ?? '', 'berre_save_footer_nav') ) return;
    if ( ! current_user_can('manage_options') ) return;

    $columns = [];
    $col_titles = (array)($_POST['col_title'] ?? []);
    foreach ( $col_titles as $ci => $title ) {
        $title = sanitize_text_field($title);
        if ( $title === '' ) continue;
        $links = [];
        $labels  = (array)($_POST["link_label_{$ci}"]  ?? []);
        $urls    = (array)($_POST["link_url_{$ci}"]    ?? []);
        $targets = (array)($_POST["link_target_{$ci}"] ?? []);
        foreach ( $labels as $li => $label ) {
            $label = sanitize_text_field($label);
            if ( $label === '' ) continue;
            $links[] = [
                'label' => $label,
                'url'   => esc_url_raw($urls[$li] ?? '#'),
                'ext'   => isset($targets[$li]) && $targets[$li] === '1',
            ];
        }
        $columns[] = ['title' => $title, 'links' => $links];
    }

    // Liens légaux
    $legal = [];
    $ll = (array)($_POST['legal_label'] ?? []);
    $lu = (array)($_POST['legal_url']   ?? []);
    foreach ($ll as $i => $label) {
        $label = sanitize_text_field($label);
        if ($label) $legal[] = ['label'=>$label,'url'=>esc_url_raw($lu[$i]??'#')];
    }

    update_option('berre_footer_nav',   $columns);
    update_option('berre_footer_legal', $legal);
    set_transient('berre_footer_saved', true, 10);
} );

/* ── Page admin ── */
function berre_footer_nav_page() {
    $columns = berre_get_footer_nav();
    $legal   = get_option('berre_footer_legal', [
        ['label'=>'Mentions légales','url'=>'/mentions-legales'],
        ['label'=>'Politique de confidentialité','url'=>'/confidentialite'],
    ]);
    $saved = get_transient('berre_footer_saved');
    if ($saved) delete_transient('berre_footer_saved');
    ?>
    <style>
    .berre-fn-wrap { max-width:960px;margin-top:20px; }
    .berre-fn-cols { display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px; }
    .berre-fn-col { background:#fff;border:1px solid #ddd;border-radius:8px;overflow:hidden; }
    .berre-fn-col-head { background:#f6f7f7;border-bottom:1px solid #eee;padding:10px 14px;display:flex;align-items:center;gap:8px; }
    .berre-fn-col-head input { flex:1;border:none;background:transparent;font-size:13px;font-weight:700;color:#333; }
    .berre-fn-col-body { padding:12px 14px; }
    .berre-fn-link-row { display:flex;align-items:center;gap:6px;margin-bottom:6px;padding:5px 6px;background:#f9f9f9;border-radius:4px;border:1px solid #eee; }
    .berre-fn-link-row input[type=text],.berre-fn-link-row input[type=url] { flex:1;border:1px solid #ddd;border-radius:3px;padding:4px 7px;font-size:12px; }
    .berre-drag-h { cursor:grab;color:#ccc;font-size:16px; }
    .berre-del-btn { background:none;border:none;color:#bbb;cursor:pointer;font-size:14px;padding:2px 5px;border-radius:3px; }
    .berre-del-btn:hover { color:#c00;background:#fff0f0; }
    .berre-fn-add { font-size:12px;margin-top:6px;width:100%; }
    </style>

    <div class="wrap berre-fn-wrap">
        <h1>🔗 Footer — Colonnes de navigation</h1>
        <p style="color:#666">Gérez les 3 colonnes de liens du footer. Vous pouvez ajouter/supprimer des liens et les réordonner.</p>
        <?php if ($saved): ?><div class="notice notice-success is-dismissible"><p>✅ Sauvegardé.</p></div><?php endif; ?>

        <form method="post" id="berre-fn-form">
            <?php wp_nonce_field('berre_save_footer_nav','berre_footer_nav_nonce'); ?>

            <!-- Colonnes -->
            <div class="berre-fn-cols" id="berre-fn-cols">
            <?php foreach ($columns as $ci => $col): ?>
            <div class="berre-fn-col" data-col="<?php echo $ci; ?>">
                <div class="berre-fn-col-head">
                    <span class="berre-drag-h">⠿</span>
                    <input type="text" name="col_title[<?php echo $ci; ?>]"
                           value="<?php echo esc_attr($col['title']); ?>"
                           placeholder="Titre de la colonne">
                </div>
                <div class="berre-fn-col-body">
                    <div class="berre-fn-links" id="berre-fn-links-<?php echo $ci; ?>">
                    <?php foreach ($col['links'] as $li => $link): ?>
                    <div class="berre-fn-link-row" draggable="true">
                        <span class="berre-drag-h" style="font-size:13px">⠿</span>
                        <input type="text" name="link_label_<?php echo $ci; ?>[]"
                               value="<?php echo esc_attr($link['label']); ?>" placeholder="Libellé">
                        <input type="url"  name="link_url_<?php echo $ci; ?>[]"
                               value="<?php echo esc_attr($link['url']); ?>"  placeholder="URL ou /page">
                        <label title="Ouvrir dans un nouvel onglet" style="font-size:10px;white-space:nowrap;color:#888;display:flex;align-items:center;gap:2px">
                            <input type="checkbox" name="link_target_<?php echo $ci; ?>[]" value="1"
                                   <?php checked(!empty($link['ext'])); ?>>↗
                        </label>
                        <button type="button" class="berre-del-btn" onclick="this.closest('.berre-fn-link-row').remove()">✕</button>
                    </div>
                    <?php endforeach; ?>
                    </div>
                    <button type="button" class="button berre-fn-add"
                            onclick="addLink(this, <?php echo $ci; ?>)">+ Ajouter un lien</button>
                </div>
            </div>
            <?php endforeach; ?>
            </div>

            <!-- Liens légaux (bas de footer) -->
            <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:16px 18px;margin-bottom:18px">
                <h3 style="font-size:13px;margin:0 0 12px">Liens légaux (bas du footer)</h3>
                <div id="berre-fn-legal">
                <?php foreach ($legal as $l): ?>
                <div class="berre-fn-link-row" style="max-width:600px">
                    <input type="text" name="legal_label[]" value="<?php echo esc_attr($l['label']); ?>" placeholder="Libellé">
                    <input type="url"  name="legal_url[]"   value="<?php echo esc_attr($l['url']); ?>"   placeholder="/page">
                    <button type="button" class="berre-del-btn" onclick="this.closest('.berre-fn-link-row').remove()">✕</button>
                </div>
                <?php endforeach; ?>
                </div>
                <button type="button" class="button berre-fn-add" style="max-width:600px;margin-top:8px"
                        onclick="addLegal()">+ Ajouter un lien légal</button>
            </div>

            <input type="submit" name="berre_save_footer_nav" class="button button-primary button-large" value="💾 Enregistrer">
        </form>
    </div>

    <script>
    function addLink(btn, ci) {
        var list = document.getElementById('berre-fn-links-' + ci);
        var row = document.createElement('div');
        row.className = 'berre-fn-link-row';
        row.draggable = true;
        row.innerHTML =
            '<span class="berre-drag-h" style="font-size:13px">⠿</span>' +
            '<input type="text" name="link_label_' + ci + '[]" placeholder="Libellé">' +
            '<input type="url" name="link_url_' + ci + '[]" placeholder="URL ou /page">' +
            '<label title="Nouvel onglet" style="font-size:10px;white-space:nowrap;color:#888;display:flex;align-items:center;gap:2px">' +
            '<input type="checkbox" name="link_target_' + ci + '[]" value="1">↗</label>' +
            '<button type="button" class="berre-del-btn" onclick="this.closest(\'.berre-fn-link-row\').remove()">✕</button>';
        list.appendChild(row);
        row.querySelector('input[type=text]').focus();
    }
    function addLegal() {
        var list = document.getElementById('berre-fn-legal');
        var row = document.createElement('div');
        row.className = 'berre-fn-link-row';
        row.style.maxWidth = '600px';
        row.innerHTML =
            '<input type="text" name="legal_label[]" placeholder="Libellé">' +
            '<input type="url" name="legal_url[]" placeholder="/page">' +
            '<button type="button" class="berre-del-btn" onclick="this.closest(\'.berre-fn-link-row\').remove()">✕</button>';
        list.appendChild(row);
        row.querySelector('input').focus();
    }
    // Drag & drop intra-colonne
    document.querySelectorAll('.berre-fn-links').forEach(function(list) {
        var dr = null;
        list.addEventListener('dragstart', function(e){
            dr = e.target.closest('.berre-fn-link-row');
            if(dr) setTimeout(function(){dr.style.opacity='.4';},0);
        });
        list.addEventListener('dragend', function(){if(dr){dr.style.opacity='';dr=null;}});
        list.addEventListener('dragover', function(e){
            e.preventDefault();
            var row = e.target.closest('.berre-fn-link-row');
            if(row && row !== dr){
                var r = row.getBoundingClientRect();
                list.insertBefore(dr, e.clientY < r.top+r.height/2 ? row : row.nextSibling);
            }
        });
    });
    </script>
    <?php
}

/* ── Shortcodes de rendu ── */
add_shortcode( 'berre_footer_nav', function() {
    $columns = berre_get_footer_nav();
    $out = '<div class="berre-footer-nav-cols">';
    foreach ( $columns as $col ) {
        $out .= '<div class="berre-footer__col">';
        $out .= '<h4 class="berre-footer__col-title">' . esc_html($col['title']) . '</h4>';
        $out .= '<nav class="berre-footer__nav"><ul>';
        foreach ( $col['links'] as $link ) {
            $target = !empty($link['ext']) ? ' target="_blank" rel="noopener"' : '';
            $out .= '<li><a href="' . esc_url($link['url']) . '"' . $target . '>'
                  . esc_html($link['label']) . '</a></li>';
        }
        $out .= '</ul></nav></div>';
    }
    $out .= '</div>';
    return $out;
} );

add_shortcode( 'berre_footer_legal', function() {
    $legal = get_option('berre_footer_legal', []);
    if ( empty($legal) ) return '';
    $out = '<nav class="berre-footer__legal"><ul style="display:flex;gap:16px;list-style:none;padding:0;margin:0">';
    foreach ( $legal as $l ) {
        $out .= '<li><a href="' . esc_url($l['url']) . '" style="color:rgba(255,255,255,.5);font-size:12px;text-decoration:none">'
              . esc_html($l['label']) . '</a></li>';
    }
    $out .= '</ul></nav>';
    return $out;
} );


/* ============================================================
   FOOTER — Réseaux sociaux administrables
   Option WP : berre_footer_social
   ============================================================ */

function berre_footer_social_defaults() {
    return [
        ['service' => 'facebook',  'url' => 'https://www.facebook.com', 'label' => 'Facebook'],
        ['service' => 'youtube',   'url' => 'https://www.youtube.com',  'label' => 'YouTube'],
    ];
}

function berre_get_footer_social() {
    $saved = get_option('berre_footer_social');
    return (is_array($saved) && !empty($saved)) ? $saved : berre_footer_social_defaults();
}

/* Icônes SVG par réseau */
function berre_social_icon( $service ) {
    $icons = [
        'facebook'  => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>',
        'youtube'   => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58a2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
        'twitter'   => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'linkedin'  => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>',
    ];
    return $icons[$service] ?? '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>';
}

/* ── Sous-menu admin ── */
add_action( 'admin_menu', function() {
    add_submenu_page( 'berre-admin', 'Réseaux sociaux', '📣 Réseaux sociaux', 'manage_options', 'berre-footer-social', 'berre_footer_social_page' );
}, 24 );

/* ── Sauvegarde ── */
add_action( 'admin_init', function() {
    if ( ! isset($_POST['berre_save_social']) ) return;
    if ( ! wp_verify_nonce($_POST['berre_social_nonce'] ?? '', 'berre_save_social') ) return;
    if ( ! current_user_can('manage_options') ) return;

    $networks = [];
    $services = (array)($_POST['social_service'] ?? []);
    $urls     = (array)($_POST['social_url']     ?? []);
    $labels   = (array)($_POST['social_label']   ?? []);
    foreach ( $services as $i => $service ) {
        $service = sanitize_key($service);
        $url     = esc_url_raw($urls[$i] ?? '');
        if ( $service && $url ) {
            $networks[] = ['service'=>$service,'url'=>$url,'label'=>sanitize_text_field($labels[$i]??$service)];
        }
    }
    update_option('berre_footer_social', $networks);
    set_transient('berre_social_saved', true, 10);
} );

/* ── Page admin ── */
function berre_footer_social_page() {
    $networks = berre_get_footer_social();
    $saved    = get_transient('berre_social_saved');
    if ($saved) delete_transient('berre_social_saved');
    $services_list = ['facebook','instagram','twitter','youtube','linkedin'];
    ?>
    <style>
    .berre-soc-row{display:flex;align-items:center;gap:10px;margin-bottom:8px;background:#fff;border:1px solid #e0e0e0;border-radius:6px;padding:10px 14px}
    .berre-soc-row select,.berre-soc-row input[type=url]{padding:6px 9px;border:1px solid #ddd;border-radius:4px;font-size:13px}
    .berre-soc-row select{width:150px}
    .berre-soc-row input[type=url]{flex:1}
    .berre-soc-preview{width:36px;height:36px;border-radius:50%;background:rgba(45,106,176,.15);display:flex;align-items:center;justify-content:center;color:var(--b,#2D6AB0);flex-shrink:0}
    </style>
    <div class="wrap" style="max-width:680px;margin-top:20px">
        <h1>📣 Réseaux sociaux</h1>
        <p style="color:#666">Ces liens s'affichent en haut à droite du footer.</p>
        <?php if ($saved): ?><div class="notice notice-success is-dismissible"><p>✅ Sauvegardé.</p></div><?php endif; ?>
        <form method="post">
            <?php wp_nonce_field('berre_save_social','berre_social_nonce'); ?>
            <div id="berre-soc-list">
            <?php foreach ($networks as $n): ?>
            <div class="berre-soc-row" draggable="true">
                <span class="berre-drag-h" style="cursor:grab;color:#ccc;font-size:18px">⠿</span>
                <div class="berre-soc-preview" id="soc-prev-<?php echo esc_attr($n['service']); ?>">
                    <?php echo berre_social_icon($n['service']); ?>
                </div>
                <select name="social_service[]" onchange="updateSocIcon(this)">
                    <?php foreach ($services_list as $s): ?>
                    <option value="<?php echo $s; ?>" <?php selected($n['service'],$s); ?>><?php echo ucfirst($s); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="url" name="social_url[]" value="<?php echo esc_attr($n['url']); ?>" placeholder="https://...">
                <input type="hidden" name="social_label[]" value="<?php echo esc_attr($n['label']); ?>">
                <button type="button" class="button berre-del-btn" onclick="this.closest('.berre-soc-row').remove()" style="color:#c00">✕</button>
            </div>
            <?php endforeach; ?>
            </div>
            <div style="display:flex;gap:10px;margin-top:12px;align-items:center">
                <button type="button" id="add-soc" class="button">➕ Ajouter un réseau</button>
                <input type="submit" name="berre_save_social" class="button button-primary" value="💾 Enregistrer">
            </div>
        </form>
    </div>
    <script>
    var SOC_ICONS = <?php echo json_encode(array_combine($services_list, array_map('berre_social_icon', $services_list))); ?>;
    function updateSocIcon(sel) {
        var prev = sel.closest('.berre-soc-row').querySelector('.berre-soc-preview');
        if (prev) prev.innerHTML = SOC_ICONS[sel.value] || '';
    }
    document.getElementById('add-soc').addEventListener('click', function() {
        var row = document.createElement('div');
        row.className = 'berre-soc-row';
        row.draggable = true;
        var opts = <?php echo json_encode(array_map(fn($s)=>'<option value="'.$s.'">'.ucfirst($s).'</option>', $services_list)); ?>.join('');
        row.innerHTML = '<span class="berre-drag-h" style="cursor:grab;color:#ccc;font-size:18px">⠿</span>' +
            '<div class="berre-soc-preview">' + SOC_ICONS['facebook'] + '</div>' +
            '<select name="social_service[]" onchange="updateSocIcon(this)">' + opts + '</select>' +
            '<input type="url" name="social_url[]" placeholder="https://...">' +
            '<input type="hidden" name="social_label[]" value="">' +
            '<button type="button" class="button berre-del-btn" onclick="this.closest(\'.berre-soc-row\').remove()" style="color:#c00">✕</button>';
        document.getElementById('berre-soc-list').appendChild(row);
    });
    </script>
    <?php
}

/* ── Shortcode [berre_footer_social] ── */
add_shortcode( 'berre_footer_social', function() {
    $networks = berre_get_footer_social();
    if ( empty($networks) ) return '';
    $out = '<div class="berre-footer-social">';
    foreach ( $networks as $n ) {
        $out .= '<a href="' . esc_url($n['url']) . '" class="berre-footer-social__link berre-footer-social__link--' . esc_attr($n['service']) . '"'
              . ' target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($n['label']) . '">'
              . berre_social_icon($n['service'])
              . '</a>';
    }
    $out .= '</div>';
    return $out;
} );

/* ── Shortcode [berre_footer_contact] : horaires, adresse, puis ligne tel + réseaux + bouton ── */
remove_shortcode( 'berre_footer_contact' );
add_shortcode( 'berre_footer_contact', function() {
    $d       = berre_get_mairie_info();
    $socials = berre_get_footer_social();
    ob_start(); ?>
    <div class="berre-footer-contact">
        <?php if (!empty($d['hours'])): ?>
        <div class="berre-footer-contact__block">
            <strong class="berre-footer-contact__label">Horaires d'ouverture</strong>
            <p class="berre-footer-contact__hours"><?php echo nl2br(esc_html($d['hours'])); ?></p>
        </div>
        <?php endif; ?>
        <?php if (!empty($d['address'])): ?>
        <div class="berre-footer-contact__block">
            <strong class="berre-footer-contact__label">Adresse</strong>
            <p class="berre-footer-contact__address"><?php echo nl2br(esc_html($d['address'])); ?></p>
        </div>
        <?php endif; ?>
        <!-- Ligne : téléphone + bouton (gauche) | spacer | réseaux sociaux (droite) -->
        <div class="berre-footer-contact__row">
            <?php if (!empty($d['phone'])): ?>
            <a href="tel:<?php echo esc_attr(preg_replace('/\s/','',$d['phone'])); ?>" class="berre-footer-contact__phone">
                📞 <?php echo esc_html($d['phone']); ?>
            </a>
            <?php endif; ?>
            <?php if (!empty($d['email'])): ?>
            <a href="mailto:<?php echo esc_attr($d['email']); ?>" class="berre-footer-contact__btn">
                ✉️ <?php echo esc_html($d['btn_text'] ?: 'Nous écrire'); ?>
            </a>
            <?php endif; ?>
            <div class="berre-footer-contact__row-spacer"></div>
            <?php if (!empty($socials)): ?>
            <div class="berre-footer-social berre-footer-social--inline">
                <?php foreach ($socials as $n):
                    $svc = esc_attr($n['service'] ?? '');
                ?>
                <a href="<?php echo esc_url($n['url']); ?>" class="berre-footer-social__link berre-footer-social__link--<?php echo $svc; ?>"
                   target="_blank" rel="noopener" aria-label="<?php echo esc_attr($n['label'] ?? $svc); ?>">
                    <?php echo berre_social_icon($svc); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
} );


/* ============================================================
   COULEURS PAR CATÉGORIE D'AGENDA — même système qu'actualités
   Term meta : berre_cat_agenda_color
   ============================================================ */

add_action( 'categorie_agenda_edit_form_fields', function( $term ) {
    $color = get_term_meta( $term->term_id, 'berre_cat_agenda_color', true ) ?: '#DEA128';
    ?>
    <tr class="form-field">
        <th scope="row"><label for="berre_cat_agenda_color">🎨 Couleur de la catégorie</label></th>
        <td>
            <input type="color" name="berre_cat_agenda_color" id="berre_cat_agenda_color"
                   value="<?php echo esc_attr($color); ?>"
                   style="width:60px;height:32px;cursor:pointer;border:1px solid #ddd;border-radius:4px;padding:2px">
            <p class="description">Couleur affichée dans le calendrier et les pages agenda.</p>
        </td>
    </tr>
    <?php
} );

add_action( 'categorie_agenda_add_form_fields', function() {
    ?>
    <div class="form-field">
        <label for="berre_cat_agenda_color">🎨 Couleur</label>
        <input type="color" name="berre_cat_agenda_color" id="berre_cat_agenda_color" value="#DEA128"
               style="width:60px;height:32px;cursor:pointer;border:1px solid #ddd;border-radius:4px;padding:2px">
    </div>
    <?php
} );

add_action( 'edited_categorie_agenda', function( $term_id ) {
    if ( isset($_POST['berre_cat_agenda_color']) )
        update_term_meta( $term_id, 'berre_cat_agenda_color', sanitize_hex_color($_POST['berre_cat_agenda_color']) );
} );
add_action( 'created_categorie_agenda', function( $term_id ) {
    if ( isset($_POST['berre_cat_agenda_color']) )
        update_term_meta( $term_id, 'berre_cat_agenda_color', sanitize_hex_color($_POST['berre_cat_agenda_color']) );
} );

/* CSS agenda catégories injecté dans <head> */
add_action( 'wp_head', function() {
    $terms = get_terms( ['taxonomy' => 'categorie_agenda', 'hide_empty' => false] );
    if ( is_wp_error($terms) || empty($terms) ) return;
    $css = '<style id="berre-agenda-cat-colors">';
    foreach ( $terms as $term ) {
        $color = get_term_meta( $term->term_id, 'berre_cat_agenda_color', true );
        if ( ! $color ) continue;
        $slug  = sanitize_html_class( $term->slug );
        $link  = wp_make_link_relative( get_term_link( $term ) );
        $rgb   = sscanf( $color, '#%02x%02x%02x' );
        $light = sprintf( 'rgba(%d,%d,%d,0.13)', $rgb[0], $rgb[1], $rgb[2] );
        // Badge agenda dans les pages
        $css .= ".berre-agenda-event-cat a[href=\"{$link}\"] { color:{$color}!important }";
        $css .= ".berre-event-card__cat a[href=\"{$link}\"] { color:{$color}!important }";
    }
    $css .= '</style>';
    echo $css;
} );

/* Colonne couleur dans la liste des catégories agenda */
add_filter( 'manage_edit-categorie_agenda_columns', function( $cols ) {
    $cols['berre_color'] = 'Couleur';
    return $cols;
} );
add_filter( 'manage_categorie_agenda_custom_column', function( $out, $col, $term_id ) {
    if ( $col !== 'berre_color' ) return $out;
    $color = get_term_meta( $term_id, 'berre_cat_agenda_color', true ) ?: '#DEA128';
    return '<span style="display:inline-block;width:20px;height:20px;border-radius:50%;background:' . esc_attr($color) . ';border:1px solid rgba(0,0,0,.1);vertical-align:middle"></span> <code style="font-size:11px">' . esc_html($color) . '</code>';
}, 10, 3 );
