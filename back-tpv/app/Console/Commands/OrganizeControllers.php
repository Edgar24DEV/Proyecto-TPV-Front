<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OrganizeControllers extends Command
{
    protected $signature = 'controllers:organize';
    protected $description = 'Organiza los controladores en carpetas específicas por entidad';

    protected $entityMap = [
        'Category' => ['Category'],
        'Client' => ['Client'],
        'Company' => ['Company'],
        'Employee' => ['Employee'],
        'Location' => ['Location'],
        'Order' => ['Order'],
        'OrderLine' => ['OrderLine'],
        'Payment' => ['Payment'],
        'Product' => ['Product'],
        'Restaurant' => ['Restaurant'],
        'Role' => ['Role'],
        'Table' => ['Table'],
    ];

    public function handle()
    {
        $sourcePath = app_path('Infrastructure/Http/Controllers');
        $targetBase = app_path('Infrastructure/Http/Controllers');

        if (!File::exists($sourcePath)) {
            $this->error("No se encontró el directorio: $sourcePath");
            return 1;
        }

        $files = File::files($sourcePath);

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $entity = $this->getEntityFromFilename($filename);

            if (!$entity) {
                $this->warn("No se pudo identificar entidad para: $filename");
                continue;
            }

            $targetDir = "$targetBase/$entity";

            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
                $this->info("Creada carpeta: $targetDir");
            }

            $targetPath = "$targetDir/$filename";
            File::move($file->getRealPath(), $targetPath);
            $this->info("Movido: $filename -> $entity/");
        }

        $this->info("✅ Controladores organizados correctamente.");
        return 0;
    }

    private function getEntityFromFilename(string $filename): ?string
    {
        foreach ($this->entityMap as $folder => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($filename, $keyword) !== false) {
                    return $folder;
                }
            }
        }

        return null;
    }
}
