<?php
/**
 * @author tmtuan
 * created Date: 10/23/2023
 * Project: Unigem
 */

namespace Modules\Acp\Controllers\Store\Product;


use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Database;
use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Controllers\Traits\ProductImage;
use App\Entities\Store\Product;
use App\Enums\Store\Product\ProductAttachMetaEnum;
use App\Enums\Store\Product\ProductStatusEnum;
use App\Models\AttachMetaModel;
use App\Models\Blog\CategoryModel;
use Modules\Acp\Models\Store\Product\ProductContentModel;
use Modules\Acp\Models\Store\Product\ProductModel;
use Modules\Acp\Traits\deleteItem;

class ProductController extends AcpController
{
    use ProductImage, deleteItem;
    protected $_categoryModel;
    protected $_attachMetaModel;
    protected $_productContentModel;
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->_model                    = model(ProductModel::class);
        $this->_categoryModel            = model(CategoryModel::class);
        $this->_attachMetaModel          = model(AttachMetaModel::class);
        $this->_productContentModel      = model(ProductContentModel::class);
        $this->db                        = Database::connect(); //Load database connection

    }

    public function index()
    {
        $getData = $this->request->getGet();

        switch ($getData['listtype']?? '') {
            case 'deleted':
                $this->_model->onlyDeleted();
                $this->_data['listtype'] = 'deleted';
                break;
            case 'user':
                $this->_model->where("user_init", $this->user->id);
                $this->_data['listtype'] = 'user';
                break;
            default:
                $this->_data['listtype'] = 'all';
                break;
        }

        if (isset($getData['search'])) {
            if (isset($getData['pd_name']) && $getData['pd_name'] !== '') {
                $this->_model->like('pd_name', $getData['pd_name']);
                $this->_data['search_pd_name'] = $getData['pd_name'];
            }
        }
        if (isset($getData['mdelete'])) {
            if (isset($getData['sel']) && !empty($getData['sel'])) {
                $this->_model->delete($getData['sel']);
            }
        }

        if (isset($getData['category']) && $getData['category'] > 0) {
            $this->_model->where('product.cat_id', $getData['category']);
            $this->_data['select_cat'] = $getData['category'];
        }
        if (isset($getData['pd_status']) && $getData['pd_status'] !== '') {
            $this->_model->like('pd_status', $getData['pd_status']);
            $this->_data['pd_status'] = $getData['pd_status'];
        }

        //get Data
        $this->_model->select('product.*, product_content.*')
            ->join('product_content', 'product_content.product_id = product.id')
            ->where('product_content.lang_id', $this->currentLang->id)
            ->orderBy('product.id DESC');

        $this->_data['product_category'] = $this->_categoryModel->getCategories('product', $this->currentLang->id);
        $this->_data['data']             = $this->_model->paginate();
        $this->_data['pager']            = $this->_model->pager;
        $this->_data['title']            = lang("Product.product_title");
        $this->_render('\store\product\index', $this->_data);
    }

    public function addProduct()
    {
        $this->_data['product_category']     = $this->_categoryModel->getCategories('product', $this->currentLang->id);
        $this->_data['title']                = lang("Product.title_add");
        $this->_render('\store\product\add', $this->_data);
    }

    public function addProductAction()
    {
        $postData = $this->request->getPost();

        // Validate here first, since some things wrong
        $rules = array_merge([
            'pd_name' => 'required|min_length[3]|checkProductNameExist|checkProductSlugExist',
        ], $this->ruleValidate());

        $errMess = $this->messageValidate();

        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $slug                  = clean_url($postData['pd_name']);
        $postData['pd_slug']   = $slug;
        $postData['user_init'] = $this->user->id;

        if ( isset($postData['pd_status']) && $postData['pd_status'] == ProductStatusEnum::PUBLISH ) {
            $postData['publish_date'] = date('Y-m-d H:i:s');
        }

        $newProduct = new Product($postData);

        $image   = $this->request->getFile('image');
        if ($image->getName()) {
            $response = $this->uploadProductImage($postData, $image);
            if ( $response instanceof RedirectResponse) return $response;
            $newProduct->pd_image = $response;
        }

        try {
            $this->db->transBegin();

            if (!$this->_model->save($newProduct)) {
                return redirect()->back()->withInput()->with('errors', $this->_model->errors());
            }
            $id   = $this->_model->getInsertID();
            $item = $this->_model->find($id);

            // save gallery image
            if (!empty($postData['images_product'])) {
                $this->_attachMetaModel->saveAttachFiles([
                    'att_meta_type'     => ProductAttachMetaEnum::META_TYPE,
                    'att_meta_mod_name' => ProductAttachMetaEnum::MODE_NAME,
                    'att_meta_mod_id'   => $item->id,
                    'att_meta_img'      => $postData['images_product'],
                ]);
            }

            // save product content data
            $this->_productContentModel->createProductContent($id, $postData);
            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }

        //log Action
        $logData = [
            'title'        => 'Add Product',
            'description'  => "#{$this->user->username} đã thêm product #{$postData['pd_name']}",
            'properties'   => $postData,
            'subject_id'   => $item->id,
            'subject_type' => ProductModel::class,
        ];
        $this->logAction($logData);

        if (isset($postData['save'])) return redirect()->route('edit_product', [$item->id])->with('message', lang('Product.addSuccess', [$postData['pd_name']]));
        else if (isset($postData['save_exit'])) return redirect()->route('product')->with('message', lang('Product.addSuccess', [$postData['pd_name']]));
        else if (isset($postData['save_addnew'])) return redirect()->route('add_product')->with('message', lang('Product.addSuccess', [$postData['pd_name']]));
    }

    public function editProduct($id)
    {
        $item = $this->_model
        ->select('product.*, product_content.*')
        ->join('product_content', 'product_content.product_id  = product.id')
        ->where('product_content.lang_id', $this->currentLang->id)
        ->find($id);
        
        if (!isset($item->id)) {
            return redirect()->route('product')->with('error', lang('Product.no_item_found'));
        }
        
        $this->_data['product_category']     = $this->_categoryModel->getCategories('product', $this->currentLang->id);
        $this->_data['itemData']             = $item;
        $this->_data['title']                = lang("Product.title_edit");
        $this->_render('\store\product\edit', $this->_data);
    }

    public function editProductAction($id)
    {
        $item = $this->_model
                    ->select('product.*, product_content.*')
                    ->join('product_content', 'product_content.product_id  = product.id')
                    ->where('product_content.lang_id', $this->currentLang->id)
                    ->find($id);
        if (!isset($item->id)) {
            return redirect()->route('product')->with('error', lang('Product.no_item_found'));
        }

        $postData = $this->request->getPost();

        // Validate here first, since some things wrong
        $rules = array_merge([
            'pd_name' => 'required|min_length[3]|checkProductNameExist['.$item->pd_ct_id.']|checkProductSlugExist['.$item->pd_ct_id.']',
        ], $this->ruleValidate());

        $errMess =$this->messageValidate();

        //validate the input
        if (!$this->validate($rules, $errMess)) { dd($this->db->getLastQuery());
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData['pd_slug']   = clean_url($postData['pd_name']);

        if (!empty($postData['tagcloud']))  $postData['pd_tags'] = json_encode($postData['tagcloud']);

        $image   = $this->request->getFile('image');
        if ($image->getName()) {
            $response = $this->editProductImage($postData, $image, $item);
            if ( $response instanceof RedirectResponse) return $response;
        }

        if ( isset($postData['pd_status']) && $postData['pd_status'] == ProductStatusEnum::PUBLISH ) {
            if ( empty($item->publish_date) ) {
                $postData['publish_date'] = date('Y-m-d H:i:s');
            }            
        }

        // save product data 
        try {
            $this->db->transBegin();
            if (!$this->_model->update($id, $postData)) {
                return redirect()->back()->withInput()->with('errors', $this->_model->errors());
            }

            // save gallery image
            if (!empty($postData['images_product'])) {
                $imageProduct = [
                    'att_meta_type'     => ProductAttachMetaEnum::META_TYPE,
                    'att_meta_mod_name' => ProductAttachMetaEnum::MODE_NAME,
                    'att_meta_mod_id'   => $item->id,
                    'att_meta_img'      => $postData['images_product'],
                ];
                if (isset($item->images->meta->id)) {
                    $this->_attachMetaModel->updateMeta($imageProduct, $item->images->meta->id);
                } else {
                    $this->_attachMetaModel->saveAttachFiles($imageProduct);
                }
            } else {
                // when removeAttachMeta field exist and value > 0, we will delete remove that attach meta record
                if ( isset($postData['removeAttachMeta']) && $postData['removeAttachMeta'] > 0 ) {
                    $this->_attachMetaModel->deleteMeta($postData['removeAttachMeta']);
                }
            }

            // save product content data
            $this->_productContentModel
                ->where('product_id', $item->id)
                ->where('lang_id', $this->currentLang->id)
                ->update(null, $postData);

            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
        //log Action
        $logData = [
            'title'        => 'edit Product',
            'description'  => "#{$this->user->username} đã sửa product #{$postData['pd_name']}",
            'properties'   => $item->toArray(),
            'subject_id'   => $item->id,
            'subject_type' => ProductModel::class,
        ];
        $this->logAction($logData);

        if (isset($postData['save'])) return redirect()->route('edit_product', [$item->id])->with('message', lang('Product.editSuccess', [$postData['pd_name']]));
        else if (isset($postData['save_exit'])) return redirect()->route('product')->with('message', lang('Product.editSuccess', [$postData['pd_name']]));
        else if (isset($postData['save_addnew'])) return redirect()->route('add_product')->with('message', lang('Product.editSuccess', [$postData['pd_name']]));
    }

    /**
     * add product valid rules
     */
    private function ruleValidate(){
        return [
            'cat_id'         => 'required',
            'pd_sku'         => 'required',
            'pd_tags'        => 'permit_empty',
            'price'          => 'required|numeric',
            'price_discount' => 'required|numeric',
            'pd_status'      => 'required',
            'product_info'   => 'required',
        ];
    }

    private function messageValidate()
    {
        return [
            'cat_id'         => [
                'required' => lang('Product.cat_id_required'),
            ],
            'pd_name'        => [
                'required'                  => lang('Product.pd_name_required'),
                'min_length'                => lang('Product.pd_name_min_length'),
                'checkProductNameExist'     => lang('Product.pd_name_is_unique'),
                'checkProductSlugExist'     => lang('Product.pd_name_is_not_create_slug'),
            ],
            'pd_sku'         => [
                'required' => lang('Product.pd_sku_required'),
            ],
            'image'          => [
                'required' => lang('Product.pd_image_required'),
            ],
            'manufacture_id' => [
                'required' => lang('Product.manufacture_id_required'),
            ],
            'origin_price'   => [
                'required' => lang('Product.origin_price_required'),
            ],
            'price'          => [
                'required' => lang('Product.price_required'),
            ],
            'price_discount' => [
                'required' => lang('Product.price_discount_required'),
            ],
            'pd_status'      => [
                'required' => lang('Product.pd_status_required'),
            ],
            'minimum'        => [
                'required' => lang('Product.minimum_required'),
            ],
            'weight'         => [
                'required' => lang('Product.weight_required'),
            ],
        ];
    }

    public function ajaxSearchProduct()
    {
        $inputData = $this->request->getPost();
        $response = [];
        if (isset($inputData['keyword_search']) && $inputData['keyword_search'] !== '') {
            $data = $this->_model
                ->like('pd_name', $inputData['keyword_search'])
                ->where('pd_status', ProductStatusEnum::PUBLISH)
                ->findAll();

            foreach ($data as $item) {
                $item->product_meta = $item->product_meta;
            }
            $response['error'] = 0;
            $response['data'] = $data;
        } else {
            $response['error'] = 1;
            $response['message'] = lang('Vui lòng nhập Từ khóa');
        }
        return $this->response->setJson($response);
    }
}