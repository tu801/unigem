<?php
/**
 * @author tmtuan
 * created Date: 04/11/2025
 */

namespace App\Traits;

use App\Models\LangModel;
use App\Models\LogModel;

trait SystemLog
{
    public function logAction(array $data)
    {
        $properties = ( isset($data['properties']) && !empty($data['properties'])) ? json_encode($data['properties']) : null;

        if ( !isset($data['lang_id']) || empty($data['lang_id']) || $data['lang_id'] == 0 ) {
            $privLang = model(LangModel::class)->getPrivLang();
            $data['lang_id'] = $privLang->id;
        }

        $logData = [
            'module' => 'acp',
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'properties' => $properties,
            'subject_id' => $data['subject_id'] ?? null,
            'subject_type' => $data['subject_type'] ?? null,
            'causer_id' => $this->user->id,
            'causer_type' => $this->user->model_class,
            'lang_id' => $data['lang_id']
        ];
        $_logModel = model(LogModel::class);
        $_logModel->insert($logData);
    }
}