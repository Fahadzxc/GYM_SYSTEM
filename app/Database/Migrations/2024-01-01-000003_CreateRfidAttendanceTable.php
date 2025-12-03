<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Create RFID Attendance Table Migration
 * 
 * This migration creates the rfid_attendance table for storing
 * RFID scan records. It does not modify any existing tables.
 */
class CreateRfidAttendanceTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'attendance_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'member_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'Foreign key to gym_members.id',
            ],
            'scan_time' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => 'Timestamp when the RFID card was scanned',
            ],
            // ============================================
            // OPTIONAL: Add more fields here if needed
            // ============================================
            // 'location' => [
            //     'type' => 'VARCHAR',
            //     'constraint' => 100,
            //     'null' => true,
            //     'comment' => 'Location where the scan occurred',
            // ],
            // 'device_id' => [
            //     'type' => 'VARCHAR',
            //     'constraint' => 50,
            //     'null' => true,
            //     'comment' => 'ID of the RFID scanner device',
            // ],
        ]);

        // Set primary key
        $this->forge->addKey('attendance_id', true);
        
        // Add index on member_id for faster lookups
        $this->forge->addKey('member_id');
        
        // Add index on scan_time for faster date-based queries
        $this->forge->addKey('scan_time');
        
        // Add composite index for checking daily attendance
        $this->forge->addKey(['member_id', 'scan_time']);

        // Create the table
        $this->forge->createTable('rfid_attendance');

        // ============================================
        // OPTIONAL: Add foreign key constraint
        // Uncomment if you want database-level referential integrity
        // ============================================
        // $this->db->query('ALTER TABLE `rfid_attendance` 
        //                   ADD CONSTRAINT `fk_rfid_attendance_member` 
        //                   FOREIGN KEY (`member_id`) 
        //                   REFERENCES `gym_members` (`id`) 
        //                   ON DELETE CASCADE 
        //                   ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop the table if migration is rolled back
        $this->forge->dropTable('rfid_attendance');
    }
}

