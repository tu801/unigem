<?php

/**
 * @author tmtuan
 * created Date: 10/24/2021
 * project: foxcms
 */

namespace App\Models;


use CodeIgniter\Model;

class LangModel extends Model
{
    protected $table = 'language';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_init',
        'user_type',
        'name',
        'locale',
        'lang_code',
        'flag',
        'order',
        'is_default',
        'is_rtl',
        'currency_code',
        'currency_symbol'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $skipValidation = true;

    /**
     * List all language data
     * @return array|\CodeIgniter\Cache\CacheInterface|mixed
     */
    public function listLang()
    {
        if (!$langData = cache('_languages')) {
            $langData = $this
                ->orderBy('order', 'ASC')
                ->findAll();

            foreach ($langData as $item) {
                $flagIcon = base_url("themes/flag/{$item->flag}");
                $item->icon = $flagIcon;
            }
            cache()->save('_languages', $langData, config('Cache')->ttl);
        }

        return $langData;
    }

    /**
     * get default language item
     */
    public function getPrivLang()
    {
        $defaultLang = cache()->get('default_lang');
        if (empty($defaultLang)) {
            $defaultLang = $this->where('is_default', 1)->first();
            cache()->save('default_lang', $defaultLang, config('Cache')->ttl);
        }

        return $defaultLang;
    }

    /**
     * insert or update language
     * @param $input
     */
    public function insertOrUpdate($input)
    {
        $chkLang = $this->where(['locale' => $input['locale'], 'lang_code' => $input['lang_code']])->first();
        if (!isset($chkLang->id)) {
            $this->insert($input);
        } else {
            $this->update($chkLang->id, $input);
        }
    }
}
