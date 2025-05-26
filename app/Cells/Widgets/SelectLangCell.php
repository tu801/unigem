<?php

namespace App\Cells\Widgets;

use App\Models\LangModel;
use CodeIgniter\View\Cells\Cell;

class SelectLangCell extends Cell
{
    protected string $view = 'select_lang';
    protected $langList;

    public function render(): string {
        $langList = model(LangModel::class)->listLang();
        $lang = session()->lang;

        return $this->view($this->view, [
            'langList' => $langList,
            'currentLang' => $lang,
        ]);
    }
}
