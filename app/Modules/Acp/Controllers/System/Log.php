<?php

/**
 * @author brianhas289
 */

namespace Modules\Acp\Controllers\System;

use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Models\LogModel;
use Modules\Auth\Models\LoginModel;

class Log extends AcpController
{
    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(LogModel::class);
        }
        if (empty($this->_loginModel)) {
            $this->_loginModel = model(LoginModel::class);
        }
    }

    public function index()
    {
        if (!$this->user->is_root) {
            return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));
        }
        $this->_data['title'] = lang("Nhật ký");
        $this->_render('\system\log\index', $this->_data);
    }

    public function ajaxLstSysAct()
    {
        $results = array();
        $getData = $this->request->getGet();
        if (isset($getData['mod']) && $getData['mod'] != '') {
            $this->_model->where('module', $getData['mod']);
        }
        if (isset($getData['keyword']) && $getData['keyword'] != '') {
            $this->_model->like('title', $getData['keyword']);
        }

        // pagination
        $pagePer = (int)$this->config->sys['default_page_number'];
        $nxtRow = 0;
        if (isset($getData['page']) && $getData['page'] != '') {
            $nxtRow = ((int)$getData['page'] - 1);
        }
        $this->_model->orderBy('id DESC');
        $results['data'] = $this->_model->get($pagePer, $nxtRow * $pagePer)->getResult();
        $allPages = $this->_model->countAll();
        $results['pages'] = (int)($allPages / $pagePer);
        $results['pagePer'] = $pagePer;
        $results['curPage'] = $nxtRow + 1;
        $results['error'] = 0;

        return $this->response->setJSON($results);
    }

    public function ajaxModSysAct()
    {
        $results = array();
        $results['sysModule'] = $this->config->sysModule;
        $results['error'] = 0;
        return $this->response->setJSON($results);
    }

    public function ajaxLstSysLogin()
    {
        $results = array();
        $getData = $this->request->getGet();
        // if (isset($getData['mod']) && $getData['mod'] != '') {
        //     $this->_loginModel->where('module', $getData['mod']);
        // }
        if (isset($getData['keyword']) && $getData['keyword'] != '') {
            $this->_loginModel->like('email', $getData['keyword']);
        }

        // pagination
        $pagePer = (int)$this->config->sys['default_page_number'];
        $nxtRow = 0;
        if (isset($getData['page']) && $getData['page'] != '') {
            $nxtRow = ((int)$getData['page'] - 1);
        }
        $this->_loginModel->orderBy('id DESC');
        $results['data'] = $this->_loginModel->get($pagePer, $nxtRow * $pagePer)->getResult();
        $allPages = $this->_loginModel->countAll();
        $results['pages'] = (int)($allPages / $pagePer);
        $results['pagePer'] = $pagePer;
        $results['curPage'] = $nxtRow + 1;
        $results['error'] = 0;

        return $this->response->setJSON($results);
    }


    public function detail($logId)
    {
        if (!$this->user->is_root) {
            return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));
        }

        $this->_data['title'] = lang("Chi tiết Nhật ký hệ thống");
        $this->_data['sysAct'] = $this->_model->where('id', $logId)->first();
        $this->_render('\system\log\detail', $this->_data);
    }
}
