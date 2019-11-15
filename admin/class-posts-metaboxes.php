<?php
namespace Tooltipy;

class Posts_Metaboxes{
    public function __construct() {
        add_action( 'do_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 3 );
        
        // Filter metabox fields before save if needed
        $this->filter_metabox_fields();

        add_action('save_post', array( $this, 'save_metabox_fields' ) );

        // Regenerate matched tooltips when restoring a post revision
        add_action( 'wp_restore_post_revision', array( $this, 'regenerate_matched_tooltips'), 10 );
    }

    function is_related_posttype( $post_id ){
        $related_posttypes = Tooltipy::get_related_post_types();
        $current_post = get_post( $post_id );

        if( in_array( $current_post->post_type, $related_posttypes ) ){
            return true;
        }else{
            return false;
        }
    }

    function regenerate_matched_tooltips( $post_id ){
        if( !$this->is_related_posttype( $post_id ) ){
            return false;
        }

        $current_post = get_post( $post_id );

        $new_value = $this->filter_matched_tooltips( null, $current_post->post_content );
        update_post_meta( $post_id, 'tltpy_matched_tooltips', $new_value);
    }

    // Filter metabox fields before save if needed
    public function filter_metabox_fields(){
        // Filter fields here
        add_filter('tltpy_posts_metabox_field_before_save_' . 'tltpy_matched_tooltips', array($this, 'filter_matched_tooltips'), 10, 2 );
        add_filter('tltpy_posts_metabox_field_before_save_' . 'tltpy_exclude_tooltips', array($this, 'filter_exclude_tooltips'), 10, 2 );
    }

    function filter_exclude_tooltips( $old_value, $post_vars ){
        $new_value = '';

        $arr_value = explode( ',', $old_value);
        $arr_value = array_map( 'trim', $arr_value );
        $arr_value = array_map( 'strtolower', $arr_value );

        foreach ($arr_value as $key => $value) {
            if( empty($value) ){
                unset( $arr_value[$key] );
            }
        }
        $new_value = implode( ', ', $arr_value );

        return $new_value;
    }

    function filter_matched_tooltips( $old_value, $data ){
        $content = $data;

        if( is_array( $data ) ){
            $content = $data['post_content'];
        }        

        $tooltips = Tooltipy::get_tooltips();

        $matched_tooltips = array();
        foreach($tooltips as $tltp){
            $synonyms = array( $tltp->post_title );
            $syn_meta = get_post_meta( $tltp->ID, 'tltpy_synonyms', true );

            if( $syn_meta ){
                $synonyms = array_merge( $synonyms, explode( '|', $syn_meta ) );
            }

            // Quote regular expression characters
            $synonyms = array_map( 'preg_quote', $synonyms );

            $pattern = '/'. implode( '|', $synonyms ) .'/i';

            preg_match( $pattern, $content, $matches);

            if( is_array($matches) && count($matches) == 1 && empty($matches[0]) ){
                $matches = array();
            }

            if( !empty($matches) ){
                $tltp_vector = array(
                    'tooltip_id'    => $tltp->ID,
                    'tooltip_title' => $tltp->post_title
                );
                array_push($matched_tooltips, $tltp_vector );
            }
        }

        return $matched_tooltips;
    }

    function save_metabox_fields( $post_id ){
        // Not for Tooltipy post type
        if( !empty($_POST['post_type']) && $_POST['post_type'] == Tooltipy::get_plugin_name() ){
            return false;
        }

        // editpost : to prevent bulk edit problems
        if( !empty($_POST['action']) && $_POST['action'] == 'editpost' ){

            $metabox_fields = $this->get_metabox_fields();
            foreach ( $metabox_fields as $field) {
                $this->save_metabox_field( $post_id, $field['meta_field_id']);
            }
        }
    }

    function save_metabox_field( $post_id, $meta_field_id, $sanitize_function = 'sanitize_text_field' ){
        $value = call_user_func( $sanitize_function, $_POST[$meta_field_id] );

        // Filter hook before saving meta field
        $value = apply_filters( 'tltpy_posts_metabox_field_before_save_' . $meta_field_id, $value, $_POST);

        update_post_meta( $post_id, $meta_field_id, $value);
    }

    function add_meta_boxes( $post_type, $context, $post ){
        // For all posts except Tooltipy
        if( Tooltipy::get_plugin_name() == $post_type ){
            return false;
        }

        //for post types except my_keywords
        $all_post_types = Tooltipy::get_related_post_types();
        foreach($all_post_types as $screen) {
            add_meta_box(
                'tltpy_posts_metabox',
                __('Related tooltips settings','tooltipy-lang'),
                array( $this, 'metabox_render' ) ,
                $screen,
                'side',
                'high'
            );
        }
    }

    static function get_metabox_fields(){
        $tooltip_fields = array(
            array(
                'meta_field_id' => 'exclude_me',
                'callback'      => array( __CLASS__, 'exclude_me_field' )
            ),
            array(
                'meta_field_id' => 'matched_tooltips',
                'callback'      => array( __CLASS__, 'matched_tooltips_field' )
            ),
            array(
                'meta_field_id' => 'exclude_tooltips',
                'callback'      => array( __CLASS__, 'exclude_tooltips_field' )
            ),
        );
        
        // Filter hook
        $tooltip_fields = apply_filters( 'tltpy_posts_metabox_fields', $tooltip_fields);
        
        // Add metadata prefix
        foreach( $tooltip_fields as $key => $field ){
			$tooltip_fields[$key]['meta_field_id'] = 'tltpy_' . $field['meta_field_id'];
        }
        return $tooltip_fields;
    }

    function metabox_render(){
        $metabox_fields = $this->get_metabox_fields();

        foreach ($metabox_fields as $field) {
            call_user_func( $field['callback'], $field['meta_field_id'] );
        }
    }
    
    function exclude_me_field( $meta_field_id ){
        global $post_type;
        $post_type_label = $post_type;
        $currentPostType = get_post_type_object(get_post_type());

        if ($currentPostType) {
            $post_type_label = esc_html($currentPostType->labels->singular_name);
        }

        $is_checked = get_post_meta( get_the_id(), $meta_field_id ,true) ? 'checked' : '';
        ?>
        <p>
            <h4><?php _e('Exclude this post from being matched', 'tooltipy-lang'); ?></h4>
            <Label><?php echo(__('Exclude this ','tooltipy-lang') . '<b>' . strtolower($post_type_label) . '</b>' ); ?>
                <input type="checkbox" 
                    name="<? echo( $meta_field_id ); ?>" 
                    <?php echo ( $is_checked ); ?> 
                />
            </label>
            <div style="color:red;">NOT WORKING PROPERLY YET</div>
        </p>
        <?php
    }
    
    function matched_tooltips_field($meta_field_id){
        $matched_tooltips = get_post_meta( get_the_id(), $meta_field_id, true );
        ?>
        <h4><?php _e('Tooltips in this post', 'tooltipy-lang'); ?></h4>
        <?php
        if( empty($matched_tooltips) ){
            ?>
            <p style="color:red;"><?php _e('No tooltips matched yet', 'tooltipy-lang'); ?></p>
            <?php
            return false;
        }
        ?>
        <ul style="padding: 0px 10px;">
            <?php
            foreach ($matched_tooltips as $tltp) {
              ?>
              <li style="color:green;"><?php echo($tltp['tooltip_title']); ?></li>
              <?php  
            }
            ?>
        </ul>
        <?php
    }

    function exclude_tooltips_field($meta_field_id){
        $excluded_tooltips = get_post_meta( get_the_id(), $meta_field_id, true );
        ?>
        <h4><?php _e('Tooltips to exclude', 'tooltipy-lang'); ?></h4>
        <input
            type="text"
            name="<?php echo($meta_field_id); ?>"
            placeholder="tooltip..."
            value="<?php echo( $excluded_tooltips ); ?>"
        >
        <?php
    }
}