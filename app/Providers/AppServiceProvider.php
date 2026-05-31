<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('database.default') === 'sqlite') {
            try {
                $dbPath = config('database.connections.sqlite.database');
                if ($dbPath && !file_exists($dbPath)) {
                    $dir = dirname($dbPath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    
                    // Copy the default database from the git repo if it exists, otherwise touch the file
                    $defaultDb = database_path('database.sqlite');
                    if (file_exists($defaultDb) && $dbPath !== $defaultDb) {
                        copy($defaultDb, $dbPath);
                    } else {
                        touch($dbPath);
                    }
                }
            } catch (\Throwable $e) {
                // Log the exception but do not prevent the application from booting
                \Illuminate\Support\Facades\Log::error("SQLite Database Auto-Initialization failed: " . $e->getMessage());
            }
        }
    }
}
