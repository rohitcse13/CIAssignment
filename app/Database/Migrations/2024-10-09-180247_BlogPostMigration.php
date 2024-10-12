<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BlogPostMigration extends Migration
{
    public function up() {
        // Create the blog_posts table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => TRUE,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'content' => [
                'type' => 'TEXT',
            ],
            'author_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
            ],
            'image' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'on_create' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
            ]);

        // Set id as primary key
        $this->forge->addKey('id', TRUE);
        // Create the users table
        $this->forge->createTable('blog_posts');
        // Add foreign key after creating the table
        $this->db->query("ALTER TABLE blog_posts ADD CONSTRAINT fk_author_id FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE");
    }

    public function down() {
        // Drop foreign key constraint before dropping the table
        $this->db->query("ALTER TABLE blog_posts DROP FOREIGN KEY fk_author_id");
        // Drop the users table
        $this->forge->dropTable('blog_posts');
    }
}
