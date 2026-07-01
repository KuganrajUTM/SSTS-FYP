<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE verification DROP FOREIGN KEY verification_admin_id_foreign');
        DB::statement('ALTER TABLE verification MODIFY COLUMN admin_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE verification ADD CONSTRAINT verification_admin_id_foreign FOREIGN KEY (admin_id) REFERENCES admin(id) ON DELETE SET NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE verification DROP FOREIGN KEY verification_admin_id_foreign');
        DB::statement('UPDATE verification SET admin_id = 1 WHERE admin_id IS NULL');
        DB::statement('ALTER TABLE verification MODIFY COLUMN admin_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE verification ADD CONSTRAINT verification_admin_id_foreign FOREIGN KEY (admin_id) REFERENCES admin(id) ON DELETE CASCADE');
    }
};
