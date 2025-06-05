<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Libraries\SeoMeta\SeoMetaCell;

class Register extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        //SEOData config
        SeoMetaCell::setCanonical();
        SeoMetaCell::setOgType();
        SeoMetaCell::add('meta_desc', get_theme_config('general_seo_description'));
        SeoMetaCell::add('meta_keywords', get_theme_config('general_seo_keyword'));
        SeoMetaCell::add('og_title', get_theme_config('general_site_title'));
        SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
        SeoMetaCell::add('og_description', get_theme_config('general_seo_description'));
        SeoMetaCell::add('og_url', base_url());
        $og_img_data = get_theme_config('general_seo_open_graph_image');
        if (isset($og_img_data->full_image)) {
            SeoMetaCell::add('og_image', base_url($og_img_data->full_image));
        }
    }

    public function register()
    {
        $authenticator = auth('session')->getAuthenticator();

        if ($authenticator->loggedIn()) {
            return redirect()->route('cus_logout');
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('Home.cus_register'), route_to('cus_register'));
        $this->page_title = lang('Home.cus_register');

        return $this->_render('customer/auth/register', $this->_data);
    }

    public function registerSubmit()
    {
        $postData = $this->request->getPost();

        $validRules = [
            'cus_full_name' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => lang('Customer.cus_full_name_required'),
                    'min_length' => lang('Customer.cus_full_name_min_length'),
                ]
            ],
            'cus_phone' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => lang('Customer.cus_phone_required'),
                    'min_length' => lang('Customer.cus_phone_min_length'),
                ]
            ],
            'cus_email' => [
                'rules' => 'required|valid_email|is_unique[customer.cus_email]',
                'errors' => [
                    'required' => lang('Customer.cus_email_required'),
                    'valid_email' => lang('Customer.cus_email_valid_email'),
                    'is_unique' => lang('Customer.cus_email_unique'),
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => lang('Customer.password_required'),
                    'min_length' => lang('Customer.password_min_length'),
                ]
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => lang('Customer.password_confirm_required'),
                    'matches' => lang('Customer.password_confirm_matches_password'),
                ]
            ],
        ];

        //validate the input
        if (! $this->validate($validRules)) {
            //return the errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        dd($postData);
    }
}