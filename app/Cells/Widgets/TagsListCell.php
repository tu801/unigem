<?php
/**
 * @author tmtuan
 * created Date: 9/27/2023
 * project: Unigem
 */

namespace App\Cells\Widgets;

use CodeIgniter\View\Cells\Cell;
use App\Models\Blog\TagsModel;

class TagsListCell extends Cell
{
    protected string $view = 'wg_tags';
    public array $tagsData = [];
    public $postTags;

    public function mount()
    {
        $tagModel = model(TagsModel::class);

        if (!empty($this->postTags)) {
            $tagList = [];
            foreach ($this->postTags as $tag) {
                $tagList[] = $tagModel->where('slug', $tag)->first();
            }

            $this->tagsData = $tagList;
        } else {
            $this->tagsData = $tagModel
            ->orderBy('RAND()')
            ->get(10)
            ->getResult();
        }
    }

    public function postTags(): string
    {
        $tagModel = model(TagsModel::class);
        
        if (!empty($this->postTags)) {
            $tagList = [];
            foreach ($this->postTags as $tag) {
                $tagList[] = $tagModel->where('slug', $tag)->first();
            }

            $tagsData = $tagList;
        } else {
            $tagsData = model(TagsModel::class)
                ->where('tag_type', 'post')
                ->orderBy('RAND()')
                ->get(10)
                ->getResult();
        }

        return $this->view($this->view, ['tagsData' => $tagsData]);

    }
}