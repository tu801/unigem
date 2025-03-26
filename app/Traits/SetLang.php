<?php
namespace App\Traits;

use CodeIgniter\Config\Services;
use Modules\Acp\Models\LangModel;

/**
 * Author: tmtuan
 * Created date: 8/10/2023
 * Project: thuthuatonline
 **/

trait SetLang {
    /**
     * setup language
     */
    public function _setLang()
    {
        $session = Services::session();
        $language = Services::language();

        if ($session->lang) {
            $language->setLocale($session->lang->locale);
            $this->_data['curLang'] = $session->lang;
        }
        else {
            //get default lang
            $defLang = model(LangModel::class)
                ->select('id, name, locale, flag, is_default')
                ->where('is_default', 1)->first();

            $lang = $defLang->locale ?? config('App')->defaultLocale;
            $language->setLocale($lang);
            $session->set('lang', $defLang);
            $this->_data['curLang'] = $defLang;
        }
    }

    /**
     * check if there are more than 1 language in the system then enable the multi-lang option
     */
    public function checkMultilang()
    {
        $count = model(LangModel::class)->countAll();

        $this->_data['multiLang'] = $count > 1 ? true : false;
    }
}