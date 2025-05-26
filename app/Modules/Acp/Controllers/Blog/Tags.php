<?php
/**
 * @author tmtuan
 * created Date: 28-Jul-21
 */

namespace Modules\Acp\Controllers\Blog;

use Modules\Acp\Controllers\AcpController;
use App\Models\Blog\TagsModel;

class Tags extends AcpController
{
    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(TagsModel::class);
        }

    }

    //AJAX
    public function ajxAddTags(){
        $response = array();
        $inputData = $this->request->getPost();

        if (!isset($inputData['title']) || $inputData['title'] == '') {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            $slug = clean_url($inputData['title']);

            //validate the data
            $rules = [
                'title'       	=> "required|min_length[2]|is_unique[tags.title]",
            ];

            $errMess = [
                'title' => [
                    'required' => "Vui lòng điền tên tag",
                    'min_length' => "Tên tag phải có tối thiểu 3 ký tự",
                    'is_unique' => 'Tên tag này đã tồn tại'
                ],
            ];
            if (! $this->validate($rules, $errMess)) {
                $response['error'] = 1;
                $response['text'] = $this->validator->getError('title');
            } else {
                $tagsData = $this->_model->getWhere(['slug' => $slug])->getResultArray();
                if ( count($tagsData) > 0 ) {
                    $response['error'] = 1;
                    $response['text'] = lang('Acp.tags_exits');
                } else {
                    $inputData['slug'] = $slug;
                    $inputData['user_init'] = $this->user->id;

                    if (! $this->_model->insert($inputData) ) {
                        $response['error'] = 1;
                        $response['text'] = $this->_model->errors();
                    } else {
                        $response['error'] = 0;
                        $response['tagsdata'] = $inputData;
                        $response['text'] = lang('Acp.add_tag_success');
                    }

                }
            }

        }

        echo(json_encode($response));
        exit();
    }

    public function getData(){
        $response = array();
        $getData = $this->request->getGet();

        if ( isset($getData['tag']) && $getData['tag'] !== '' ) {
            $this->_model->like('title', $getData['tag']);
        }

        $tags = $this->_model->findAll();
        $response['tagList'] = $tags;
        $response['total'] = count($tags);

        print_r(json_encode($response));
        exit();
    }

    /**
     * List all tag by post ID
     * @param $postID
     */
    public function getPostTagById($postID){
        $response = array();

        if ( !$postID ) {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            $langID = $this->currentLang->id;
            $tags = $this->_model->getTagByPost($postID, $langID);
            $tags = json_decode($tags->tags);
            $tagList = [];
            if ( isset($tags) && count($tags) > 0 ) {
                foreach ($tags as $item){
                    $tagData = $this->_model->getWhere(['slug'=>$item])->getFirstRow('object');
                    $tagList[] = $tagData;
                }
            }
            //echo '<pre>'; print_r($tagList);exit;
            $response['tagList'] = $tagList;
            $response['error'] = 0;
        }


        print_r(json_encode($response));
        exit();
    }

    public function getTags($modId, $modName)
    {
        $response = array();
        $langID = $this->currentLang->id;
        $tagList = [];

        switch ($modName) {
            case 'post':
                $tags = $this->_model->getTagByPost($modId, $langID);
                $tags = json_decode($tags->tags);
                if ( isset($tags) && count($tags) > 0 ) {
                    foreach ($tags as $item){
                        $tagData = $this->_model->getWhere(['slug'=>$item])->getFirstRow('object');
                        $tagList[] = $tagData;
                    }
                }
                break;
            case 'product':
//                exit('asd');
                $tags = $this->_model->getTagByProduct($modId, $langID);
                $tags = json_decode($tags->pd_tags);
                if ( isset($tags) && count($tags) > 0 ) {
                    foreach ($tags as $item){
                        $tagData = $this->_model->getWhere(['slug'=>$item])->getFirstRow('object');
                        $tagList[] = $tagData;
                    }
                }
                break;
        }
        $response['tagList'] = $tagList;
        $response['error'] = 0;

        return $this->response->setJSON($response);
    }
}