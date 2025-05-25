<?php
namespace Modules\Acp\Controllers\Store;

use App\Models\ContactModel;
use Modules\Acp\Controllers\AcpController;

class ContactController extends AcpController
{
    public function __construct()
    {
        parent::__construct();
        if ( empty($this->_model)) {
            $this->_model = model(ContactModel::class);
        }
    }

    public function index()
    {
        $this->_data['title']= lang("Shop.contact");
        $postData = $this->request->getPost();

        if ( isset($postData) && !empty($postData) ) {
            if ( !empty($postData['sel']) ) {
                $this->_model->delete($postData['sel']);
            }

            if (isset($postData['email']) && $postData['email'] !== '') {
                $this->_model->like('email', $postData['email']);
                $this->_data['search_title'] = $postData['email'];
            }
        }

        $this->_data['data'] = $this->_model->paginate();
        $this->_data['pager'] = $this->_model->pager;
        $this->_data['countAll'] = $this->_model->countAll();
        $this->_render('\store\contact\index', $this->_data);
    }

    public function editContact($id)
    {
        $this->_data['title'] = lang("Shop.contact_edit");
        $this->_data['contact'] = $this->_model->find($id);
        $this->_render('\store\contact\edit', $this->_data);
    }
}