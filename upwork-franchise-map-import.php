
<div class="wrap">
    <h1><?php esc_html_e( 'Franchise Map Import', 'upwork-franchise-map' ) ?></h1>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'upwork_franchise_group' );
            do_settings_sections( 'upwork-franchise-map-import' );
            submit_button();
        ?>
    </form>
</div>