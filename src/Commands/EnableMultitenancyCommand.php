<?php

namespace Zaynasheff\PipelineSales\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class EnableMultitenancyCommand extends Command
{
    protected $signature = 'pipeline-sales:enable-multitenancy';

    protected $description = 'Adds tenant field to plugin tables so multitenancy can be enabled.';

    public function handle()
    {
        // Load tenant config values
        $tenantKey = config('pipeline-sales.tenant.foreign_key');
        $tenantTable = config('pipeline-sales.tenant.table');

        // List of plugin tables that require tenant column
        $tables = [
            'pipelines',
            'stages',
            'deals',
        ];

        foreach ($tables as $table) {
            // Skip if table does not exist
            if (! Schema::hasTable($table)) {
                $this->warn("Table '{$table}' does not exist — skipping.");

                continue;
            }

            // Skip if the foreign key already exists
            if (Schema::hasColumn($table, $tenantKey)) {
                $this->info("Table '{$table}' already contains '{$tenantKey}' — skipping.");

                continue;
            }

            // Generate migration filename
            $migrationName = 'add_' . $tenantKey . "_to_{$table}_table";
            $fileName = date('Y_m_d_His') . '_' . $migrationName . '.php';

            // Path to migration file
            $path = database_path('migrations/' . $fileName);

            // Generate migration content
            $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Add tenant column to table
    public function up(): void
    {
        Schema::table('$table', function (Blueprint \$table) {
            // Add tenant foreign key (nullable for backwards compatibility)
            \$table->foreignId('$tenantKey')->nullable()->after('id')->constrained('$tenantTable')->cascadeOnDelete();
        });
    }

    // Remove tenant column if migration is rolled back
    public function down(): void
    {
        Schema::table('$table', function (Blueprint \$table) {
            \$table->dropForeign(['$tenantKey']);
            \$table->dropColumn('$tenantKey');
        });
    }
};
PHP;

            // Create migration file
            file_put_contents($path, $content);

            $this->info("Migration for '{$table}' created: {$fileName}");

            // Avoid same timestamp for next migration
            sleep(1);
        }

        $this->info('Run `php artisan migrate` to apply all changes.');
    }
}
