<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 * Project: Unigem
 */

namespace App\Controllers;


use Modules\Acp\Models\LangModel;

class Language extends BaseController
{
    public function setLang($lang)
    {
        //check lang
        $langData = model(LangModel::class)
            ->where('locale', $lang)
            ->first();

        if ( !isset($langData->id) ) return redirect()->back()->with('error', 'Invalid Lang!');

        $session = session();
        $session->remove('lang');
        $session->set('lang', $langData);

        return redirect()->back();
    }
}