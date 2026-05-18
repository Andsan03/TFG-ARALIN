<?php

namespace App\Console\Commands;

use Database\Seeders\Support\SeederCatalog;
use Illuminate\Console\Command;

class SeedDemoCommand extends Command
{
    protected $signature = 'aralin:seed-demo {--fresh : Ejecutar migrate:fresh antes del seed}';

    protected $description = 'Carga datos de demostración realistas para pruebas manuales de ARALIN';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->call('migrate:fresh', ['--force' => true]);
        }

        $this->call('db:seed', ['--class' => 'Database\\Seeders\\DemoSeeder', '--force' => true]);

        $this->newLine();
        $this->components->info('Datos de demostración cargados correctamente.');
        $this->newLine();
        $this->line('  <fg=cyan>Contraseña para TODAS las cuentas:</> '.SeederCatalog::DEMO_PASSWORD);
        $this->newLine();
        $this->line('  <fg=yellow>Admin</>');
        $this->line('    • '.SeederCatalog::ADMIN_EMAIL);
        $this->newLine();
        $this->line('  <fg=yellow>Profesores (profesor01 … profesor10)</>');
        $this->line('    • '.SeederCatalog::teacherEmail(1).' … '.SeederCatalog::teacherEmail(10));
        $this->line('    • Bloqueado para pruebas: '.SeederCatalog::teacherEmail(10));
        $this->newLine();
        $this->line('  <fg=yellow>Alumnos (alumno01 … alumno30)</>');
        $this->line('    • '.SeederCatalog::studentEmail(1).' … '.SeederCatalog::studentEmail(30));
        $this->line('    • Reviews pendientes: '.SeederCatalog::studentEmail(1).' y '.SeederCatalog::studentEmail(3));
        $this->line('    • Cuenta bloqueada: '.SeederCatalog::studentEmail(30));
        $this->newLine();
        $this->line('  <fg=green>Guía rápida:</> php artisan aralin:seed-demo --help');
        $this->line('  Reinicio completo: php artisan aralin:seed-demo --fresh');

        return self::SUCCESS;
    }
}
