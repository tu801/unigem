<?php
namespace Modules\Acp\Controllers\System;

use Modules\Acp\Models\AttachModel;
use Modules\Acp\Models\User\UserModel;
use Modules\Acp\Controllers\AcpController;

class Attach extends AcpController
{
    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(AttachModel::class);
        }

    }

    //AJAX
    public function ajxListImg(){
        $response = array();
        $getData = $this->request->getGet();

        if ( empty($getData) || !isset($getData['mod_name']) ) {
            $response['code'] = 1;
            $response['text'] = "Invalid Request!!";
        } else {
            $this->_data['mod_name'] = $getData['mod_name'];
            $this->_data['mod_id'] = $getData['mod_id'];
            $this->_data['modelAttatch'] = $this->_model;
            echo $this->_render('\attach\listGallery', $this->_data); exit;
        }

        return $this->response->setJSON($response);
    }


    /**
     * Upload Image via tinyMCE
     * @return \CodeIgniter\HTTP\Response
     * @throws \ReflectionException
     */
    public function ajxTinyMceUpl(){
        if ( !isset($this->user->id) ) return $this->response->setJSON(['error' => 1, 'errMess' => "Invalid Credential"]);

        if(isset($_FILES['file'])){
            //check upload image is select?
            if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
                $image   = $this->request->getFile('file');
                $imgRules = [
                    'file' => [
                        'uploaded[file]',
                        'mime_in[file,image/jpg,image/jpeg,image/gif,image/png]',
                        'max_size[file,12048]',
                    ],
                ];
                $file_title = $image->getName();
                $tempName = str_replace($image->getClientExtension(), '', $image->getName()) ;
                $info = [
                    'file_name' => time()."-".clean_url($tempName).'.'.$image->getClientExtension(),
                    'sub_folder' => "{$this->_data['controller']}/".date('Y/m')
                ];

                //validate image before upload
                if ( ! $this->validate($imgRules) ) {
                    //return error
                    return $this->response->setJSON(['error' => 1, 'errMess' => $this->validator->getError('file')]);
                } else {
                    if (! $image->isValid()) {
                        //return error
                        return $this->response->setJSON(['error' => 1, 'errMess' => $image->getError()]);
                    } else {
                        $file_name = $info['file_name'];
                        $folderName = ( isset($info['sub_folder']) && $info['sub_folder'] !== '' ) ? $info['sub_folder'] : '' ;

                        if ( !$image->move(WRITEPATH . $this->config->uploadFolder.'/'.$folderName, $file_name, true) ) {
                            //return move file error
                            return $this->response->setJSON(['error' => 1, 'errMess' =>  $image->getError()]);
                        } else {
                            $imgPath = "{$folderName}/{$file_name}";

                            //create thumb
                            $imgThumb = [
                                'file_name' => $info['file_name'],
                                'original_image' => WRITEPATH . $this->config->uploadFolder.'/'.$info['sub_folder']."/{$info['file_name']}",
                                'path' => WRITEPATH . $this->config->uploadFolder.'/'.$info['sub_folder']."/thumb"
                            ];
                            create_thumb($imgThumb);

                            //insert data image
                            $insertData = [
                                'user_id' => $this->user->id,
                                'user_type' => UserModel::class,
                                'file_name' => $info['file_name'],
                                'file_title' => $file_title
                            ];

                            if (! $this->_model->insert($insertData) ) {
                                //insert fail
                                delete_image($info['file_name'], $info['sub_folder']);
                                return $this->response->setJSON(['error' => 1, 'errMess' => $this->_model->errors()]);
                            } else {//upload success
                                return $this->response->setJSON(['location' => base_url("uploads/{$info['sub_folder']}/{$info['file_name']}")]);
                            }
                        }

                    }

                }

            }

        } else {
            //return error
            return $this->response->setJSON(['error' => 1, 'errMess' => "Vui lòng chọn ảnh"]);
        }
    }

    //vuejs upload image 
    public function ajxGalleryUpload(){
        $response = array();
        $postData = $this->request->getPost();

        //prepare to upload
        if(!isset($_FILES['images'])){
            //get error
            $response['code'] = 1;
            $response['text'] = "Vui lòng chọn ảnh";
            return $this->response->setJSON($response);
        }

        //check upload image is select?
        if (isset($_FILES['images']['name']) && !empty($_FILES['images']['name'])) {
            $image   = $this->request->getFile('images');
            $imgRules = [
                'image' => [
                    'uploaded[images]',
                    'mime_in[images,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[images,10024]',
                ],
            ];

            if ( isset($postData['title']) && $postData['title'] !== '' ) $tempName = $postData['title'];
            else {
                $tempName = str_replace($image->getClientExtension(), '', $image->getName()) ;
            }
            $info = [
                'file_name' => time()."-".clean_url($tempName).'.'.$image->getClientExtension(),
                'sub_folder' => "{$this->_data['controller']}/".date('Y/m')
            ];

            //validate image before upload
            if ( ! $this->validate($imgRules) ) {
                //get error
                $response['code'] = 1;
                $response['text'] = $this->validator->getError('image');
            } else {
                if (! $image->isValid()) {
                    $response['code'] = 1;
                    $response['text'] = $image->getError();
                } else {
                    $folderName = ( isset($info['sub_folder']) && $info['sub_folder'] !== '' ) ? $info['sub_folder'] : '' ;

                    if ( !$image->move(WRITEPATH . $this->config->uploadFolder.'/'.$folderName, $info['file_name'], true) ) {
                        //get move file error
                        $response['code'] = 1;
                        $response['text'] = $image->getError();
                    } else {
                        //create thumb
                        $imgThumb = [
                            'file_name' => $info['file_name'],
                            'original_image' => WRITEPATH . $this->config->uploadFolder.'/'.$info['sub_folder']."/{$info['file_name']}",
                            'path' => WRITEPATH . $this->config->uploadFolder.'/'.$info['sub_folder']."/thumb"
                        ];
                        create_thumb($imgThumb);

                        //insert data image
                        $insertData = [
                            'user_id' => $this->user->id,
                            'user_type' => UserModel::class,
                            'file_name' => $info['file_name'],
                            'file_title' => ( isset($postData['title']) && $postData['title'] !== '' ) ? $postData['title'] : $info['file_name']
                        ];

                        if (! $this->_model->insert($insertData) ) { //insert fail
                            $response['code'] = 1;
                            $response['text'] = "Fail to save Image!! Please try again later!";
                            delete_image($info['file_name'], $info['sub_folder']);
                        } else {
                            $response['code'] = 0;

                            $attData = $this->_model->getWhere(['user_id' => $this->user->id, 'file_name'=> $info['file_name']])->getFirstRow();
                            $this->_model->convertValue($attData);

                            $response['imgData'] = $attData;
                            $response['text'] = "Đã upload thành công";
                        }
                    }

                }

            }

        }

        return $this->response->setJSON($response);
    }

    //get all the upload data
    public function getUploadData(){
        $response = array();
        $getData = $this->request->getGet();
        $num_rows = 50;
        $page = $getData['page']??0;

        if ( isset($getData['mod_name']) && $getData['mod_name'] !== '' ) $this->_model->where('mod_name', $getData['mod_name']);
        if ( isset($getData['user']) && $getData['user'] > 0 ) $this->_model->where('user_id', $getData['user']);

        $this->_model->orderBy('id', 'DESC');
        $uploadData = $this->_model->select(['id', 'file_name', 'file_title', 'created_at'])
                    ->orderBy('id DESC')
                    ->limit($num_rows, $page)->get()->getResult();
        $newData = [];
        if ( count($uploadData) > 0 ) {
            foreach ($uploadData as $item ) {
                $this->_model->convertValue($item);
                $newData[] = $item;
            }
            $response['page'] =$page+$num_rows;
        }

        //echo "<pre>";print_r($newData);exit;
        $response['uploadData'] = $newData;

        return $this->response->setJSON($response);
    }

    //delete image
    public function ajxDeleteItem($id){
        $response = [];

        if($id){
            $item = $this->_model->find($id);
            if ( !isset($item->id) || empty($item->id) ) {
                $response['error'] = 1;
                $response['text'] = lang('Acp.item_not_found');

            } else {
                if ( $this->user->id == $item->user_id || $this->user->per === 'root' ) {
                    $this->_model->delete($id);
                    $date=date_create($item->created_at);
                    $path = '/attach/'.date_format($date, 'Y/m/d');
                    delete_image($item->file_name, $path);

                    $response['error'] = 0;
                    $response['text'] = "Đã xóa thành công file {$item->file_name}";
                    $response['divRemove'] = "attItem{$item->id}";
                } else {
                    $response['error'] = 1;
                    $response['text'] = lang('Acp.no_permission');
                }
            }

        }else{
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        }

        return $this->response->setJSON($response);
    }

}
