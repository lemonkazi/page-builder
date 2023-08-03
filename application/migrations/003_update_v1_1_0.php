<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_v1_1_0 extends CI_Migration {

    public function up()
    {
        // Add new fonts section to "app_settings" table
        $this->db->query("INSERT INTO `apps_settings` (`id`, `name`, `value`, `default_value`, `description`, `required`, `created_at`, `modified_at`) VALUES
            (13, 'custom_fonts', '', '', '<h4>Google Fonts</h4>\r\n<p>\r\nTo allow usage of Google Fonts, you will need to provide a JSON string defining which fonts to load. This string should use the following format:\r\n</p>\r\n<pre>[\r\n   {\r\n       \"nice_name\": \"Roboto\",\r\n      \"css_name\": \"\'Roboto\', sans-serif\",\r\n       \"api_entry\": \"Roboto\"\r\n   },\r\n  {\r\n       \"nice_name\": \"Oswald\",\r\n      \"css_name\": \"\'Oswald\', sans-serif\",\r\n       \"api_entry\": \"Oswald\"\r\n   }\r\n]</pre>\r\n<p>\r\nFor more information, please refer to our knowledge base.\r\n</p>', 0, '0000-00-00 00:00:00', NULL);
            ");

        // Add fonts column to the "pages" table
        $pages_fields = array(
            'google_fonts' => array(
                'type' => 'LONGTEXT',
                'null' => TRUE,
                'default' => '',
                'after' => 'pagethumb'
            )
        );
        $this->dbforge->add_column('pages', $pages_fields);

        // Create template_categories table
        $this->dbforge->add_field(array(
            'templates_categories_id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'category_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            )
        ));
        $this->dbforge->add_key('templates_categories_id', TRUE);
        $this->dbforge->create_table('template_categories');

        // Create template_to_category table
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'pages_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'category_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('pages_id');
        $this->dbforge->add_key('category_id');
        $this->dbforge->create_table('template_to_category');

        // Create settings table
        $this->dbforge->add_field(array(
            'settings_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'value' => array(
                'type' => 'INT',
                'type' => 'LONGTEXT',
            )
        ));
        $this->dbforge->add_key('settings_id', TRUE);
        $this->dbforge->create_table('settings');

        // Add `blocks` column to the "packages" table
        $packages_fields = array(
            'blocks' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'default' => '',
                'after' => 'templates'
            )
        );
        $this->dbforge->add_column('packages', $packages_fields);
    }

    public function down()
    {
        // Delete the custom fonts from "app_settings"
        $this->db->query("DELETE FROM `apps_settings` WHERE id = 13");

        // Drop column google_fonts from pages table
        $this->dbforge->drop_column('pages', 'google_fonts');

        // Delete the template_categories table
        $this->dbforge->drop_table('template_categories');

        // Delete the template_to_category table
        $this->dbforge->drop_table('template_to_category');

        // Delete the settings table
        $this->dbforge->drop_table('settings');

        // Drop column blocks from packages table
        $this->dbforge->drop_column('packages', 'blocks');
    }
}