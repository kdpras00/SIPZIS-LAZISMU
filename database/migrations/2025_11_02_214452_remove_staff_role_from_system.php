<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Convert all staff users to admin
        DB::table('users')
            ->where('role', 'staff')
            ->update(['role' => 'admin']);

        // Step 2: Drop existing triggers
        DB::unprepared("DROP TRIGGER IF EXISTS chk_received_by_role_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS chk_received_by_role_update");

        // Step 3: Modify enum column (MySQL/MariaDB doesn't support direct enum modification)
        // We need to alter the column type
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'muzakki') NOT NULL DEFAULT 'muzakki'");

        // Step 4: Recreate triggers with only 'admin' check
        DB::unprepared("
            CREATE TRIGGER chk_received_by_role_insert
            BEFORE INSERT ON zakat_payments
            FOR EACH ROW
            BEGIN
                IF NEW.received_by IS NOT NULL THEN
                    IF NOT EXISTS (
                        SELECT 1 FROM users 
                        WHERE users.id = NEW.received_by 
                        AND users.role = 'admin'
                    ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'received_by must reference a user with admin role';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER chk_received_by_role_update
            BEFORE UPDATE ON zakat_payments
            FOR EACH ROW
            BEGIN
                IF NEW.received_by IS NOT NULL THEN
                    IF NOT EXISTS (
                        SELECT 1 FROM users 
                        WHERE users.id = NEW.received_by 
                        AND users.role = 'admin'
                    ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'received_by must reference a user with admin role';
                    END IF;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared("DROP TRIGGER IF EXISTS chk_received_by_role_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS chk_received_by_role_update");

        // Restore enum with staff
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'muzakki') NOT NULL DEFAULT 'muzakki'");

        // Recreate triggers with staff
        DB::unprepared("
            CREATE TRIGGER chk_received_by_role_insert
            BEFORE INSERT ON zakat_payments
            FOR EACH ROW
            BEGIN
                IF NEW.received_by IS NOT NULL THEN
                    IF NOT EXISTS (
                        SELECT 1 FROM users 
                        WHERE users.id = NEW.received_by 
                        AND users.role IN ('admin', 'staff')
                    ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'received_by must reference a user with admin or staff role';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER chk_received_by_role_update
            BEFORE UPDATE ON zakat_payments
            FOR EACH ROW
            BEGIN
                IF NEW.received_by IS NOT NULL THEN
                    IF NOT EXISTS (
                        SELECT 1 FROM users 
                        WHERE users.id = NEW.received_by 
                        AND users.role IN ('admin', 'staff')
                    ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'received_by must reference a user with admin or staff role';
                    END IF;
                END IF;
            END
        ");
    }
};
