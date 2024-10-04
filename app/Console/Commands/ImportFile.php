<?php

namespace App\Console\Commands;

use App\Models\Platform;
use App\Models\Status;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ImportFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:file {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import your file data into your SQL Database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $fileName = $this->argument('file');
        $file = storage_path('app/' . $fileName);
        $fileContent = File::get($file);
        $data = json_decode($fileContent, true);

        if (isset($data['platforms'])) {
            $this->migrateToDbWithIcon($data['platforms'], new Platform());
        }

        if (isset($data['tags'])) {
            $this->migrateToDb($data['tags'], new Tag());
        }

        if (isset($data['statuses'])) {
            $this->migrateStatusToDb($data['statuses'], new Status());
        }

        $this->info('Success, you imported your file data into your SQL Database');
    }

    private function migrateToDb(array $data, Model $model): void
    {
        foreach ($data as $value) {
            $model->create(['name' => $value['name']]);
        }
    }

    private function migrateToDbWithIcon(array $data, Model $model): void
    {
        foreach ($data as $value) {
            $model->create(['name' => $value['name'], 'icon' => $value['icon']]);
        }
    }

    private function migrateStatusToDb(array $data, Model $model): void
    {
        foreach ($data as $value) {
            $model->create(['name' => $value['name'], 'icon' => $value['icon'], 'color' => $value['color']]);
        }
    }
}
