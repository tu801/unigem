<?php
/**
 * @author tmtuan
 * created Date: 9/27/2023
 * project: Unigem
 */

namespace App\Cells\Widgets;


use CodeIgniter\Config\Services;
use CodeIgniter\View\Cells\Cell;
use App\Enums\Post\PostTypeEnum;
use App\Models\Blog\PostModel;

class NewPostListCell extends Cell
{
    protected string $view = 'wg_new_posts';
    public array $posts;

    public function mount()
    {
        $session   = Services::session();
        $postModel = model(PostModel::class);
        $posts     = $postModel->join('post_content', 'post_content.post_id = post.id')
            ->where('lang_id', $session->lang->id)
            ->where('post_type', PostTypeEnum::POST)
            ->where('post_status', 'publish')
            ->orderBy('publish_date', 'desc')
            ->findAll(5);

        $this->posts = $posts;
    }
}