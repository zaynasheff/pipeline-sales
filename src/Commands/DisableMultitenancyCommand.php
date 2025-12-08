<?php

namespace Zaynasheff\PipelineSales\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DisableMultitenancyCommand extends Command
{
    protected $signature = 'pipeline-sales:disable-multitenancy';

    protected $description = 'Removes tenant fields from plugin tables so multitenancy can be disabled.';

    public function handle()
    {
        $tenantKey = config('pipeline-sales.tenant.foreign_key');
        $tenantTable = config('pipeline-sales.tenant.table'); // for reference only

        $tables = [
            'pipelines',
            'stages',
            'deals',
        ];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                $this->warn("Table '{$table}' does not exist — skipping.");
                continue;
            }

            if (! Schema::hasColumn($table, $tenantKey)) {
                $this->info("Table '{$table}' has no '{$tenantKey}' — skipping.");
                continue;
            }

            // migration name
            $migrationName = "remove_{$tenantKey}_from_{$table}_table";
            $fileName = date('Y_m_d_His') . '_' . $migrationName . '.php';
            $path = database_path('migrations/' . $fileName);

            // migration content
            $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('$table', function (Blueprint \$table) {
            try {
                \$table->dropForeign(['$tenantKey']);
            } catch (\\Throwable \$e) {
                // SQLite fallback — ignore
            }

            if (Schema::hasColumn('$table', '$tenantKey')) {
                \$table->dropColumn('$tenantKey');
            }
        });
    }

    public function down(): void
    {
        Schema::table('$table', function (Blueprint \$table) {
            // Restore tenant FK (nullable)
            \$table->foreignId('$tenantKey')
                  ->nullable()
                  ->after('id')
                  ->constrained('$tenantTable')
                  ->cascadeOnDelete();
        });
    }
};
PHP;

            file_put_contents($path, $content);

            $this->info("Migration for '{$table}' created: {$fileName}");

            sleep(1); // avoid timestamp collisions
        }

        $this->info('Run `php artisan migrate` to apply all changes.');
    }
}
