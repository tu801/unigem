<?php

namespace App\Cells\Widgets;

use CodeIgniter\View\Cells\Cell;

class SocialLinksCell extends Cell
{
    protected string $view = 'social_links';

    public function render(): string {
        $social_links = get_social_links();

        if ( !empty($social_links) ) {
            return $this->view($this->view, [
                'social_links' => $social_links
            ]);
        } else {
            return '';
        }
    }
}
