<?php

/**
 * @author tmtuan
 * created Date: 10/09/2021
 * project: fox_cms
 */

namespace App\Models\Blog;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'lang_id',
        'user_init',
        'user_type',
        'name',
        'status',
        'location',
        'slug',
        'id',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $skipValidation = true;

    /**
     * insert new item if exist otherwise edit the item data
     * @param $input
     */
    public function updateItem($input)
    {
        $bd = $this->db->table($this->table);
        $item = $bd->where('name', $input['name'])->orWhere('slug', $input['slug'])->get()->getFirstRow();
        if (!empty($item) || isset($item->id)) {
            unset($input['id']);
            $bd->update($input, "id = {$item->id}");
            return true;
        } else {
            $bd->insert($input);
            return true;
        }
        return false;
    }

    /*
     * get Child menu and return html list
     */
    public function getChild($parent, $menu)
    {
        $bd = $this->db->table($this->table);
        $html = '';
        $config = config('Acp');
        $adminSlug = env('app.adminSlug') ?? 'acp';
        $_menuItems = \model(MenuItemsModel::class);
        $childs = $_menuItems->where(['menu_id ' => $menu->id, 'parent_id' => $parent->id])
            ->orderBy('order ASC')
            ->get()->getResult();
        if (!empty($childs)) {
            $html .= '<ol class="dd-list">';
            foreach ($childs as $item) {
                $delItemUrl = base_url($adminSlug . "/menu/remove/{$item->id}?key={$menu->slug}");

                $html .= '<li class="dd-item" data-id="' . $item->id . '">';
                $html .= '<div class="dd-handle"><h3 class="title" id="menuTitle_' . $item->id . '">' . $item->title . '</h3>';
                $html .= '<div class="float-right">
                            <a class="btn btn-primary btn-sm" data-toggle="collapse" href="#editForm_' . $item->id . '" role="button" aria-expanded="false"
                               aria-controls="editForm_<?=$row->id?>">
<i class="fa fa-edit"></i>
</a>
<a class="btn btn-danger btn-sm acpRmItem" href="' . $delItemUrl . '">
    <i class="fas fa-trash"></i>
</a>
</div>';
                $html .= '</div>';
                $html .= '<div class="card collapse card-danger card-outline" id="editForm_' . $item->id . '">';
                if ($item->type == 'category') :
                    $html .= view($config->view . '\blog\menu\components\_editCategoryCard', ['row' => $item]);
                else :
                    $html .= view($config->view . '\blog\menu\components\_editMenuCard', ['row' => $item]);
                endif;
                $html .= '</div>';
                $html .= $this->getChild($item, $menu);
                $html .= '</li>';
            }
            $html .= '</ol>';
        }
        return $html;
    }
}
