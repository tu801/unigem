<?php
namespace Modules\Acp\Controllers\Store;

use App\Enums\ContactEnum;
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
        $this->_data['title']= lang("Contact.contact_list");
        $postData = $this->request->getPost();

        if ( isset($postData) && !empty($postData) ) {
            if ( !empty($postData['sel']) ) {
                $this->_model->delete($postData['sel']);
            }

            if (isset($postData['email']) && $postData['email'] !== '') {
                $this->_model->like('email', $postData['email']);
                $this->_data['search_title'] = $postData['email'];
            }

            if (isset($postData['status']) && $postData['status']!== '') {
                $this->_model->where('status', $postData['status']);
                $this->_data['filter_status'] = $postData['status'];
            }
        }

        $this->_data['data'] = $this->_model->paginate();
        $this->_data['pager'] = $this->_model->pager;
        $this->_data['countAll'] = $this->_model->countAll();
        $this->_render('\store\contact\index', $this->_data);
    }

    public function viewContact($id)
    {
        $this->_data['title'] = lang("Contact.contact");
        $contactItem = $this->_model->find($id);

        if ( !$contactItem || !isset($contactItem->id) ) {
            return redirect()->to(route_to('list_contact'))->with('error', lang('Acp.item_not_found'));
        } 

        if ( $contactItem->status == ContactEnum::STATUS_NEW ) {
            $this->_model->update($contactItem->id, ['status' => ContactEnum::STATUS_READ]);
            $contactItem->status = ContactEnum::STATUS_READ; // update status for view information
        }

        $this->_data['itemData'] = $contactItem;
        $this->_render('\store\contact\view', $this->_data);
    }

    public function editContact($id)
    {
        $contactItem = $this->_model->find($id);

        if (!$contactItem ||!isset($contactItem->id) ) {
            return redirect()->to(route_to('list_contact'))->with('error', lang('Acp.item_not_found'));
        }
        
        $postData = $this->request->getPost();
        
        if ( $postData['status'] != $contactItem->status && !in_array($postData['status'], [ContactEnum::STATUS_READ, ContactEnum::STATUS_RESOLVE])) {
            return redirect()->back()->with('error', lang('Acp.invalid_request'));
        }
        $this->_model->update($contactItem->id, $postData);

        //log Action
        $logData = [
            'title'        => lang('Log.edit_contact'),
            'description'  => lang('Log.edit_contact_desc', [$this->user->username, $contactItem->id]),
            'properties'   => $contactItem,
            'subject_id'   => $contactItem->id,
            'subject_type' => ContactModel::class,
        ];
        $this->logAction($logData);

        return redirect()->to(route_to('list_contact'))->with('success', lang('Acp.update_success'));
    }
}