<?php

namespace App\Controllers;

use App\Enums\ContactEnum;
use App\Enums\Post\PostPositionEnum;
use App\Enums\Post\PostStatusEnum;
use App\Enums\Store\Product\ProductStatusEnum;
use App\Libraries\SeoMeta\SeoMetaCell;
use App\Models\Blog\PostModel;
use App\Models\ContactModel;
use App\Models\Store\Product\ProductModel;

class Home extends BaseController
{
    protected $_productModel;

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
        if(isset($og_img_data->full_image)) {
            SeoMetaCell::add('og_image', base_url($og_img_data->full_image));
        }
    }

    public function index(): string
    {
        $productList = model(ProductModel::class)
                    ->select('product.*, pdc.pd_name, pdc.pd_slug, pdc.price, pdc.price_discount ')
                    ->join('product_content AS pdc', 'pdc.product_id = product.id')
                    ->where('pdc.lang_id', $this->currentLang->id)
                    ->orderBy('product.id DESC')
                    ->where('pd_status', ProductStatusEnum::PUBLISH)
                    ->findAll(8);

        $postList = model(PostModel::class)
                    ->select('post.*, pc.title, pc.slug, pc.image')
                    ->join('post_content AS pc', 'pc.post_id = post.id')
                    ->where('pc.lang_id', $this->currentLang->id)
                    ->orderBy('post.id DESC')
                    ->where('post.post_status', PostStatusEnum::PUBLISH)
                    ->where('post.post_position', PostPositionEnum::TOP)
                    ->findAll(8);

        $this->_data['productList'] = $productList;
        $this->_data['postList'] = $postList;
        return $this->_render('home/index', $this->_data);
    }

    public function contactUs() {
        $this->page_title = lang('Home.contact_us');
        $postData = $this->request->getPost();
        $contactModel = model(ContactModel::class);

        if(!empty($postData)) {
            $throttler = service('throttler');

            if ($throttler->check($this->request->getIPAddress(), 2, MINUTE) === false) {
                return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
            }

            $rules   = [
                'fullname'     => 'required',
                'email'        => 'required|valid_email',
                'subject'      => 'required',
                'message'      => 'required',
            ];
            $errMess = [
                'fullname'     => [
                    'required'            => lang('Home.contact_fullname_required'),
                ],
                'email'        => [
                    'required'    => lang('Home.contact_email_required'),
                    'valid_email' => lang('Validation.valid_email'),
                ],
                'subject'     => [
                    'required'   => lang('Home.contact_subject_required'),
                ],
                'message' => [
                    'required' => lang('Home.contact_message_required'),
                ],
            ];
            if (!$this->validate($rules, $errMess)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // check if user is submit form in 15 minutes
            $formCheck = $contactModel
                    ->where('contact_type', ContactEnum::FORM_CONTACT_TYPE)
                    ->where('ip_address', $this->request->getIPAddress())
                    ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-15 minutes')))
                    ->first();
            if(!empty($formCheck)) {
                return redirect()->back()->withInput()->with('errors', [lang('Home.form_submit_same_ip_error')]);
            }

            $postData['contact_type'] = ContactEnum::FORM_CONTACT_TYPE;
            $postData['ip_address'] = $this->request->getIPAddress();
            $postData['phone'] = '';
            $contactModel->save($postData);
            
            return redirect()->back()->with('message', lang('Home.form_submit_success'));
        }

        return $this->_render('home/contact', $this->_data);
    }

    public function error_404(){
        return $this->_render('errors/404', $this->_data);
    }
}
