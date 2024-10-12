<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserMigration extends Migration
{
    public function up() {
        // Create the users table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'on_create' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
            ]);

        $this->forge->addKey('id', TRUE); // Set id as primary key
        $this->forge->createTable('users'); // Create the users table
    }

    public function down() {
        // Drop the users table
        $this->forge->dropTable('users');
    }
}
