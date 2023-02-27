<?php

namespace Omadonex\LaravelTools\Support\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Models\HistoryEventTranslate;

class HistoryGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omx:support:history-generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all data for history tables';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!file_exists(lang_path('vendor/omx-support'))) {
            $this->error('Error: main config and lang files are not published!');

            return;
        }

        HistoryEvent::truncate();
        HistoryEventTranslate::truncate();

        Model::unguard();

        $data = [
            [
                'id' => HistoryEvent::CREATE,
                ConstCustom::DB_FIELD_PROTECTED_GENERATE => true,
                'name' => 'created',
            ],
            [
                'id' => HistoryEvent::CREATE_T,
                ConstCustom::DB_FIELD_PROTECTED_GENERATE => true,
                'name' => 'created_t',
            ],
            [
                'id' => HistoryEvent::UPDATE,
                ConstCustom::DB_FIELD_PROTECTED_GENERATE => true,
                'name' => 'updated',
            ],
            [
                'id' => HistoryEvent::UPDATE_T,
                ConstCustom::DB_FIELD_PROTECTED_GENERATE => true,
                'name' => 'updated_t',
            ],
            [
                'id' => HistoryEvent::DELETE,
                ConstCustom::DB_FIELD_PROTECTED_GENERATE => true,
                'name' => 'deleted',
            ],
            [
                'id' => HistoryEvent::DELETE_T,
                ConstCustom::DB_FIELD_PROTECTED_GENERATE => true,
                'name' => 'deleted_t',
            ],
            [
                'id' => HistoryEvent::DELETE_T_ALL,
                ConstCustom::DB_FIELD_PROTECTED_GENERATE => true,
                'name' => 'deleted_t_all',
            ],
        ];

        HistoryEvent::insert($data);

        $langPath = lang_path('vendor/omx-support');
        if (file_exists($langPath)) {
            $langKeyList = array_diff(scandir($langPath), ['.', '..']);
        } else {
            $langKeyList = [config('app.fallback_locale')];
        }

        foreach ($data as $item) {
            foreach ($langKeyList as $lang) {
                $langFile = "{$langPath}/{$lang}/historyEvent.php";
                $langData = file_exists($langFile) ? include $langFile : [];
                HistoryEventTranslate::create([
                   'model_id' => $item['id'],
                   'lang' => $lang,
                   'name' => $langData[$item['id']]['name'] ?? $item['name'],
                ]);
            }

            Model::reguard();
        }
    }
}
