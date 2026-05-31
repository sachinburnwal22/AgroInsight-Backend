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
            $dbPath = config('database.connections.sqlite.database');
            $fallbackDb = database_path('database.sqlite');

            if ($dbPath && $dbPath !== $fallbackDb) {
                $dir = dirname($dbPath);
                
                try {
                    // Try to create the directory if it doesn't exist
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    
                    // Verify if the directory is writable
                    if (is_writable($dir)) {
                        if (!file_exists($dbPath)) {
                            if (file_exists($fallbackDb)) {
                                copy($fallbackDb, $dbPath);
                            } else {
                                touch($dbPath);
                            }
                        }
                    } else {
                        // Directory is not writable, fallback to ephemeral database
                        throw new \Exception("Directory {$dir} is not writable.");
                    }
                } catch (\Throwable $e) {
                    // Log the warning but do not prevent the application from booting
                    \Illuminate\Support\Facades\Log::warning("SQLite persistent database directory not writable, falling back to ephemeral database. Error: " . $e->getMessage());
                    
                    // Force Laravel to use the ephemeral database inside the repository
                    config(['database.connections.sqlite.database' => $fallbackDb]);
                }
            }
        }
    }
}
