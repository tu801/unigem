<?php
/**
 * @author tmtuan
 * created Date: 10/23/2023
 * Project: Unigem
 */

namespace Modules\Acp\Controllers\Store;


use CodeIgniter\I18n\Time;
use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Entities\Store\Shop;
use App\Enums\UploadFolderEnum;
use App\Models\Store\ProvinceModel;
use App\Models\Store\ShopModel;

class ShopController extends AcpController
{

    public function __construct()
    {
        parent::__construct();
        if ( empty($this->_model)) {
            $this->_model = model(ShopModel::class);
        }
    }

    public function index()
    {
        $this->_data['title']= lang("Shop.page_title");
        $postData = $this->request->getPost();

        if ( isset($postData) && !empty($postData) ) {
            if ( !empty($postData['sel']) ) {
                $this->_model->delete($postData['sel']);
            }

            if (isset($postData['name']) && $postData['name'] !== '') {
                $this->_model->like('name', $postData['name']);
                $this->_data['search_title'] = $postData['name'];
            }
        }

        $this->_data['data'] = $this->_model->paginate();
        $this->_data['pager'] = $this->_model->pager;
        $this->_data['countAll'] = $this->_model->countAll();
        $this->_render('\store\shop\index', $this->_data);
    }

    public function addShop()
    {
        $this->_data['title']= lang('Shop.add_title');

        if ( $this->request->getPost() ) {
            return $this->addAction();
        }

        $this->_data['provinces'] = model(ProvinceModel::class)->findAll();
        $this->_render('\store\shop\add', $this->_data);
    }

    public function addAction()
    {
        $postData = $this->request->getPost();
        if ( empty($postData) ) {
            return redirect()->route('list_shop')->with('errors', lang('Acp.invalid_request'));
        }

        // validate data
        [$rules, $errMess] = $this->_getValidateRules(null);
        if (! $this->validate($rules, $errMess))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $newShop = new Shop($postData);

        // upload image
        $image = $this->request->getFile('image');
        if ( $image->getName() ) {
            $info = [
                'file_name' => clean_url($postData['name']).'-'.time().'.'. $image->getClientExtension(),
                'sub_folder' => UploadFolderEnum::SHOP
            ];
            $imgPath = $this->upload_image($image, $this->_getDefaultUploadRule(), $info);
            if ($imgPath['success'] == false) {
                return redirect()->back()->withInput()->with('errors', $imgPath['error']);
            }

            //create thumb
            $imgThumb = [
                'file_name' => $info['file_name'],
                'original_image' => $this->config->uploadFolder . '/' . $info['sub_folder'] . "/{$info['file_name']}",
                'path' => $this->config->uploadFolder . '/' . $info['sub_folder'] . "/thumb"
            ];
            create_thumb($imgThumb);
            $newShop->image = $info['file_name'];
        }

        $id = $this->_model->insert($newShop);
        if (!$id) {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }

        // Success!
        $item = $this->_model->find($id);

        //log Action
        $logData = [
            'title'        => lang('Log.add_shop'),
            'description'  => lang('Log.add_shop_desc', [$this->user->username, $item->name]),
            'properties'   => $item->toArray(),
            'subject_id'   => $item->id,
            'subject_type' => ShopModel::class,
        ];
        $this->logAction($logData);

        if ( isset($postData['save']) ) return redirect()->route('edit_shop', [$item->shop_id])->with('message', lang('Shop.addSuccess', [$item->name]));
        else if ( isset($postData['save_exit']) ) return redirect()->route('list_shop')->with('message', lang('Shop.addSuccess', [$item->name]));
        else if ( isset($postData['save_addnew']) ) return redirect()->route('add_shop')->with('message', lang('Shop.addSuccess', [$item->name]));

    }

    public function editShop($id)
    {
        $this->_data['title'] = lang('Shop.edit_title');
        $item                 = $this->_model->find($id);
        if (!isset($item->shop_id)) {
            return redirect()->route('list_shop')->with('error', lang('Acp.invalid_request'));
        }

        // save the edit when user post form data
        if ( $this->request->getPost() ) {
            return $this->editAction($id, $item);
        }

        $this->_data['itemData'] = $item;
        $this->_render('\store\shop\edit', $this->_data);
    }

    public function editAction($id, $oldItem)
    {
        $postData = $this->request->getPost();
        if ( empty($postData) ) {
            return redirect()->route('list_shop')->with('errors', lang('Acp.invalid_request'));
        }

        // validate data
        [$rules, $errMess] = $this->_getValidateRules($oldItem);
        if (! $this->validate($rules, $errMess))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // upload image
        $image = $this->request->getFile('image');
        if ( $image->getName() ) {
            $info = [
                'file_name' => clean_url($postData['name']).'-'.time().'.'. $image->getClientExtension(),
                'sub_folder' => UploadFolderEnum::SHOP
            ];
            $imgPath = $this->upload_image($image, $this->_getDefaultUploadRule(), $info);
            if ($imgPath['success'] == false) {
                return redirect()->back()->withInput()->with('errors', $imgPath['error']);
            }

            //create thumb
            $imgThumb = [
                'file_name' => $info['file_name'],
                'original_image' => $this->config->uploadFolder . '/' . $info['sub_folder'] . "/{$info['file_name']}",
                'path' => $this->config->uploadFolder . '/' . $info['sub_folder'] . "/thumb"
            ];
            create_thumb($imgThumb);
            delete_image($oldItem->image, '/' .$info['sub_folder']);
            $postData['image'] = $info['file_name'];
        }

        $postData['updated_at'] = Time::now();
        if (! $this->_model->update( $id, $postData) ) {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
        // Success!
        $item = $this->_model->find($id);

        //log Action
        $logData = [
            'title'        => lang('Log.edit_shop'),
            'description'  => lang('Log.edit_shop_desc', [$this->user->username, $item->name]),
            'properties'   => $item->toArray(),
            'subject_id'   => $item->id,
            'subject_type' => ShopModel::class,
        ];
        $this->logAction($logData);

        if ( isset($postData['save']) ) return redirect()->route('edit_shop', [$item->shop_id])->with('message', lang('Shop.editSuccess', [$item->name]));
        else if ( isset($postData['save_exit']) ) return redirect()->route('list_shop')->with('message', lang('Shop.editSuccess', [$item->name]));
        else if ( isset($postData['save_addnew']) ) return redirect()->route('add_shop')->with('message', lang('Shop.editSuccess', [$item->name]));
    }

    private function _getValidateRules($old_item)
    {
        $rules = [
            'phone'         => 'required|valid_phone',
            'province_id'   => 'required',
            'district_id'   => 'required',
            'ward_id'       => 'required',
            'address'       => 'required',
        ];
        if ( isset($old_item) && $old_item->shop_id ) {
            $rules['name'] = "required|is_unique[shop.name,shop_id,{$old_item->shop_id}]";
        } else {
            $rules['name'] = 'required|is_unique[shop.name]';
        }

        $errMess = [
            'name' => [
                'required' => lang('Shop.name_required'),
                'is_unique' => lang('Shop.name_is_exist')
            ],
            'phone' => [
                'required' => lang('Shop.phone_required'),
            ],
            'province_id' => [
                'required' => lang('Acp.province_required'),
            ],
            'district_id' => [
                'required' => lang('Acp.district_required'),
            ],
            'ward_id' => [
                'required' => lang('Acp.ward_required'),
            ],
            'address' => [
                'required' => lang('Acp.address_required'),
            ],
        ];

        return [$rules, $errMess];
    }

    /**
     * Ajax soft delete item
     * @return mixed
     */
    public function ajxRemove() {
        $response = [];
        $postData = $this->request->getPost();
        if ( !isset($postData['id']) || empty($postData['id']) ) return $this->response->setJSON(['error' => 1, 'message' => lang('Acp.invalid_request')]);

        $item = $this->_model->find($postData['id']);
        if ( !isset($item->shop_id) || empty($item) ) {
            $response['error'] = 1;
            $response['message'] = lang('Acp.no_item');
        } else {
            if ($this->_model->delete($item->shop_id)) {
                //log Action
                if ( method_exists(__CLASS__,'logAction') ) {
                    $prop = method_exists(get_class($item), 'toArray') ? $item->toArray() : (array)$item;
                    $logData = [
                        'title' => 'Delete',
                        'description' => lang('Acp.delete_success', [$item->id]),
                        'properties' => $prop,
                        'subject_id' => $item->shop_id,
                        'subject_type' => get_class($this->_model),
                    ];
                    $this->logAction($logData);
                }
                $response['error'] = 0;
                $response['message'] = lang('Acp.delete_success', [$item->shop_id]);
            }
            else {
                $response['error'] = 1;
                $response['message'] = lang('Acp.delete_fail');
            }
        }
        return $this->response->setJson($response);
    }
}