<?php

/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: fox_cms
 */

namespace App\Models\Blog;

use CodeIgniter\Model;
use App\Entities\MenuItem;

class MenuItemsModel extends Model
{

    protected $table = 'menu_items';
    protected $primaryKey = 'id';

    protected $returnType = MenuItem::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [ 'user_init', 'user_type', 'menu_id', 'parent_id', 'related_id', 'type', 'title', 'url',
        'icon_font', 'css_class', 'target', 'order', 'updated_at',];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $skipValidation = true;


    public function addMenuItem($input)
    {
        $bd = $this->db->table($this->table);
        if (!empty($input)) {
            $bd->insert($input);
            return true;
        }
        return false;
    }

    /**
     * check if a category is added to menu or not
     * @param $cat_id
     * @param $menu_id
     * @return bool
     */
    public function checkCatExistInMenu( $cat_id, $menu_id ) {
        $check = $this->where('related_id', $cat_id)
            ->where('menu_id', $menu_id)
            ->where('type', MenuItem::CAT_TYPE)
            ->first();
        if ( isset($check->id) ) return true;
        else return false;
    }

    public function checkPageExistInMenu( $page_id, $menu_id ) {
        $check = $this->where('related_id', $page_id)
            ->where('menu_id', $menu_id)
            ->where('type', MenuItem::PAGE_TYPE)
            ->first();
        if ( isset($check->id) ) return true;
        else return false;
    }
}
