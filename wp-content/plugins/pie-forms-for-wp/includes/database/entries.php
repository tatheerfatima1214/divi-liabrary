<?php if ( ! defined( 'ABSPATH' ) ) exit;

class PIE_Database_Entries extends PIE_Abstracts_Database
{
    public function __construct()
    {
        parent::__construct(
            'pf_entries'
        );
    }

    /**
     * Function to run our initial
     */
    public function run()
    {
        $query = "CREATE TABLE IF NOT EXISTS {$this->table_name()} (
            `id` int NOT NULL AUTO_INCREMENT,
            `form_id` int,
            `user_id` bigint(20),
            `user_device` varchar(100),
            `user_ip_address` varchar(100),
            `referer` text,
            `viewed` tinyint(1),
            `starred` tinyint(1),
            `fields` longtext,
            `status` varchar(20),
            `created_at` TIMESTAMP,

            UNIQUE KEY (`id`)
        ) {$this->charset_collate( true )};";

        dbDelta( $query );
    }

}
