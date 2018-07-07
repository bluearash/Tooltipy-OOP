<?php
add_filter( 'tltpy_setting_fields', function( $fields ){
    // For animation field
    $animations = array(
        "none",
        "bounce", "bounceIn", "bounceInLeft", "bounceInRight", "bounceInDown", "bounceInUp",
        "fadeIn", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUp", "fadeInUpBig",
        "flash",
        "flip", "flipInX", "flipInY",
        "lightSpeedIn",
        "pulse",				
        "rollIn",
        "rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight",
        "slideInDown", "slideInLeft", "slideInRight", "slideInUp",
        "swing", "shake", "tada",
        "wobble",
        "zoomIn", "zoomInDown", "zoomInLeft", "zoomInRight", "zoomInUp"
    );

    // for got_from_post_types field
    $got_from_post_types_arr = array();
    foreach(get_post_types() as $psttp){
        $got_from_post_types_arr[$psttp] = $psttp;
    }
    
    $general_settings = array(
        array(
            'tab' 			=> 'general',
            'section' 		=> 'general',
            
            'uid' 			=> 'match_all_occurrences',
            'type' 			=> 'checkbox',
    
            'label' 		=> __( 'Match all occurrences', 'tooltipy-lang' ),
    
            'options' 		=> array(
                'yes' 		=> __( 'All', 'tooltipy-lang' ),
            ),
        ),
        array(
            'tab' 			=> 'general',
            'section' 		=> 'general',
            
            'uid' 			=> 'hide_tooltip_title',
            'type' 			=> 'checkbox',
    
            'label' 		=> __( 'Hide tooltip title', 'tooltipy-lang' ),
    
            'options' 		=> array(
                'yes' 		=> __( 'Hide', 'tooltipy-lang' ),
            ),
        ),
        array(
            'tab' 			=> 'general',
            'section' 		=> 'general',
            
            'uid' 			=> 'tooltip_position',
            'type' 			=> 'select',
    
            'label' 		=> __( 'Tooltip position', 'tooltipy-lang' ),
    
            'options' 		=> array(
                'top' 		=> __( 'Top', 'tooltipy-lang' ),
                'bottom' 	=> __( 'Bottom', 'tooltipy-lang' ),
                'right' 	=> __( 'Right', 'tooltipy-lang' ),
                'left' 		=> __( 'Left', 'tooltipy-lang' ),
            ),
            'default' 		=> array( 'bottom' ),
        ),
        array(
            'tab' 			=> 'general',
            'section' 		=> 'general',
            
            'uid' 			=> 'tooltip_animation',
            'type' 			=> 'select',
    
            'label' 		=> __( 'Animation', 'tooltipy-lang' ),
    
            'options' 		=> $animations,
        ),
        array(
            'tab' 			=> 'general',
            'section' 		=> 'general',
            
            'uid' 			=> 'tooltip_animation_speed',
            'type' 			=> 'select',
    
            'label' 		=> __( 'Animation speed', 'tooltipy-lang' ),
    
            'options' 		=> array(
                'fast'			=> __( 'Fast', 'tooltipy-lang' ),
                'normal'		=> __( 'Normal', 'tooltipy-lang' ),
                'slow'			=> __( 'Slow', 'tooltipy-lang' ),
            ),
        ),
        array(
            'tab' 			=> 'general',
            'section' 		=> 'advanced',
            
            'uid' 			=> 'got_from_post_types',
            'type' 			=> 'multiselect',									// could be : text, password, number, textarea, select, multiselect, radio, checkbox
    
            'label' 		=> __( 'Get tooltips from', 'tooltipy-lang' ),
            'description' 	=> __( 'Select post types from which you want to get tooltips', 'tooltipy-lang' ),		// Text description below the field
    
            'options' 		=> $got_from_post_types_arr,
        ),
        array(
            'tab' 			=> 'general',
            'section' 		=> 'advanced',
            
            'uid' 			=> 'load_all_keywords',
            'type' 			=> 'checkbox',
    
            'label' 		=> __( 'Load all keywords', 'tooltipy-lang' ),
    
            'options' 		=> array(
                'yes' 		=> __( 'Use only if needed to load all keywords per page', 'tooltipy-lang' ),
            ),
        ),
        array(
            'tab' 			=> 'general',
            'section' 		=> 'advanced',
            
            'uid' 			=> 'custom_events',
            'type' 			=> 'text',
    
            'label' 		=> __( 'Events to fetch', 'tooltipy-lang' ),
            'placeholder' 	=> __( 'Events names saparated with (,)', 'tooltipy-lang' ),
        ),
        array(
            'tab' 			=> 'general',
            'section' 		=> 'advanced',
            
            'uid' 			=> 'prevent_plugins_filters',
            'type' 			=> 'checkbox',
    
            'label' 		=> __( 'Prevent other plugins filters', 'tooltipy-lang' ),
    
            'options' 		=> array(
                'yes' 		=> __( 'Prevent any 3rd party plugin to filter or change the keywords content', 'tooltipy-lang' ),
            ),
        ),
    );
    
    $fields = array_merge( $fields, $general_settings );

    return $fields;
});