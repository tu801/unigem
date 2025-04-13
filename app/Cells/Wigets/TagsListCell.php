<?php
/**
 * @author tmtuan
 * created Date: 9/27/2023
 * project: thuthuatonline
 */

namespace App\Cells\Wigets;

use CodeIgniter\View\Cells\Cell;
use App\Models\Blog\TagsModel;

class TagsListCell extends Cell
{
    protected string $view = 'wg_tags';
    public array $tagsData = [];

    public function mount()
    {
        $this->tagsData = model(TagsModel::class)
            ->orderBy('RAND()')
            ->get(10)
            ->getResult();
    }

    public function postTags(): string
    {
        $tagsData = model(TagsModel::class)
            ->where('tag_type', 'post')
            ->orderBy('RAND()')
            ->get(10)
            ->getResult();

        return $this->view($this->view, ['tagsData' => $tagsData]);

    }
}