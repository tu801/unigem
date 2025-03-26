<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use Modules\Acp\Models\ConfigModel;

class Pager extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Templates
     * --------------------------------------------------------------------------
     *
     * Pagination links are rendered out using views to configure their
     * appearance. This array contains aliases and the view names to
     * use when rendering the links.
     *
     * Within each view, the Pager object will be available as $pager,
     * and the desired group as $pagerGroup;
     *
     * @var array<string, string>
     */
    public array $templates = [
        'default_full'   => 'App\Views\templates\paginate',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',
        'acp_full'   	 => 'App\Modules\Acp\Views\templates\paginate',
    ];

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     */
    public int $perPage = 20;

    public function __construct()
    {
        $modelConfig = model(ConfigModel::class);
        $pagerConfig = $modelConfig
            ->where('group_id', ConfigModel::DEFAULT_GROUP)
            ->where('key', 'page_number')
            ->first();

        $this->perPage = $pagerConfig->value ?? 20;
    }
}
