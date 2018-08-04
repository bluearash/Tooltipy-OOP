<?php
    $synonyms = get_post_meta( get_the_ID(), 'tltpy_synonyms', true );
    $synonyms_arr = explode( '|', $synonyms );
    $synonyms_arr = array_map( 'trim', $synonyms_arr );
    $synonyms_arr = array_map( 'strtolower', $synonyms_arr );
?>
<?php if( count( $synonyms_arr ) ): ?>
    <div class="tooltipy-synonyms">
        <h2>Synonyms</h2>
            <ul>
                <?php foreach( $synonyms_arr as $synonym ): ?>
                    <li><?php echo $synonym; ?></li>
                <?php endforeach; ?>
            </ul>
    </div>
<?php endif; ?>