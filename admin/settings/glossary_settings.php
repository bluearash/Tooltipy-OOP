<?php
add_filter( 'tltpy_setting_fields', function( $fields ){
    $args = array(
        'sort_order' => 'asc',
        'sort_column' => 'post_title',
        'post_type' => 'page',
    );

    $pages = get_pages( $args );

    $all_pages = array(
        '' => __( 'Select a page', 'tooltipy-lang' )
    );
    foreach ($pages as $page) {
        $all_pages[$page->ID] = $page->post_title;
    }

    $glossary_settings = array(
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'general',
            
            'uid' 			=> 'glossary_page',
            'type' 			=> 'select',

            'label' 		=> __( 'Glossary page', 'tooltipy-lang' ),
            'helper' 		=> __( 'Select the page on which the glossary will show up', 'tooltipy-lang' ),

            'options' 		=> $all_pages,
            'default' 		=> array( '' ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'general',
            
            'uid' 			=> 'glossary_tooltips_per_page',
            'type' 			=> 'number',

            'label' 		=> __( 'Tooltips per page', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'ALL', 'tooltipy-lang' ),
            'helper' 		=> __( 'Keywords Per Page (leave blank for unlimited keywords per page)', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_all',
            'type' 			=> 'text',

            'label' 		=> __( 'ALL label', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'ALL', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_previous',
            'type' 			=> 'text',

            'label' 		=> __( 'Previous label', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'Previous', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_next',
            'type' 			=> 'text',

            'label' 		=> __( 'Next label', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'Next', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_select_category',
            'type' 			=> 'text',

            'label' 		=> __( 'Select a category label', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'Select a category', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'labels',
            
            'uid' 			=> 'glossary_label_all_categories',
            'type' 			=> 'text',

            'label' 		=> __( 'All categories label', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'All categories', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'page',
            
            'uid' 			=> 'add_glossary_link',
            'type' 			=> 'checkbox',

            'label' 		=> __( 'Add glossary link', 'tooltipy-lang' ),

            'options' 		=> array(
                'yes' 		=> __( 'Add glossary link page in the tooltips footer', 'tooltipy-lang' ),
            ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'page',
            
            'uid' 			=> 'glossary_link_label',
            'type' 			=> 'text',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox

            'label' 		=> __( 'Glossary link label', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'View glossary', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'general',
            
            'uid' 			=> 'glossary_show_thumbnails',
            'type' 			=> 'checkbox',

            'label' 		=> __( 'Glossary thumbnails', 'tooltipy-lang' ),

            'options' 		=> array(
                'yes' 		=> __( 'Show thumbnails on the glossary page', 'tooltipy-lang' ),
            ),
        ),
        array(
            'tab' 			=> 'glossary',
            'section' 		=> 'general',
            
            'uid' 			=> 'glossary_link_titles',
            'type' 			=> 'checkbox',

            'label' 		=> __( 'Titles', 'tooltipy-lang' ),

            'options' 		=> array(
                'yes' 		=> __( 'Add links to titles', 'tooltipy-lang' ),
            ),
        ),
    );
    
    $fields = array_merge( $fields, $glossary_settings );

    return $fields;
});