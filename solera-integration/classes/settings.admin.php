<?php
class Membership_admin_settings {

    public function __construct() {
        add_action('admin_menu', array($this, 'create_settings_page'));
        add_action('admin_init', array($this, 'setup_sections'));
        add_action('admin_init', array($this, 'setup_fields'));
    }

    public function create_settings_page() {

        add_menu_page('Occasions settings', 'Occasions settings', 'manage_options', 'occasion_settings', array($this, 'settings_page_content'), 'dashicons-groups', 50);

    }

    public function settings_page_content() { ?>
        <div class="wrap">
            <h1>Occasion Settings</h1>
            <form method="POST" action="options.php">
                <?php
                settings_fields('occasion_settings');
                do_settings_sections('occasion_settings');
                submit_button();
                ?>
            </form>
        </div> <?php
    }

    public function setup_sections() {
        add_settings_section('occasion_settings_section', 'Occasions SEO', false, 'occasion_settings');
    }

    public function setup_fields() {

        // SEO fields
        add_settings_field('occasion_archive_title', 'Archive page title', array($this, 'archive_title_callback'), 'occasion_settings', 'occasion_settings_section');
        add_settings_field('occasion_archive_description', 'Archive page description', array($this, 'archive_description_callback'), 'occasion_settings', 'occasion_settings_section');
        //add_settings_field('occasion_archive_keywords', 'Archive keywords', array($this, 'keywords_callback'), 'occasion_settings', 'occasion_settings_section');
        add_settings_field('occasion_archive_h1', 'Archive H1', array($this, 'h1_callback'), 'occasion_settings', 'occasion_settings_section');
        add_settings_field('occasion_archive_text', 'Archive bottom text', array($this, 'text_callback'), 'occasion_settings', 'occasion_settings_section');
        

        register_setting('occasion_settings', 'occasion_archive_title');
        register_setting('occasion_settings', 'occasion_archive_description');
        register_setting('occasion_settings', 'occasion_archive_keywords');
        register_setting('occasion_settings', 'occasion_archive_h1');
        register_setting('occasion_settings', 'occasion_archive_text');
        

    }

    public function archive_title_callback() { 
        $value = get_option('occasion_archive_title');
        echo "<input type='text' name='occasion_archive_title' value='" . $value . "' style='width:600px;' >";
    }

    public function archive_description_callback() { 
        $value = get_option('occasion_archive_description');
        echo "<textarea name='occasion_archive_description' style='width:600px; height: 100px;'>$value</textarea>";
    }

    public function keywords_callback() { 
        $value = get_option('occasion_archive_keywords');
        echo "<input type='text' name='occasion_archive_keywords' value='" . $value . "' style='width:600px;' >";
    }

    public function h1_callback() { 
        $value = get_option('occasion_archive_h1');
        echo "<input type='text' name='occasion_archive_h1' value='" . $value . "' style='width:600px;' >";
    }

    public function text_callback() { 
        $value = get_option('occasion_archive_text');
        echo wp_editor($value, 'occasion_archive_text', array('textarea_rows' => 10));
    }
    

}

new Membership_admin_settings();
