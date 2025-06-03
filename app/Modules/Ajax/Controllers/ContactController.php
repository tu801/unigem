<?php

namespace Modules\Ajax\Controllers;

use App\Enums\ContactEnum;

class ContactController extends AjaxBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->_model = model(\App\Models\ContactModel::class);
    }

    public function addSubscribeEmail()
    {
        $this->_checkSpam();

        $email = $this->request->getPost('subscribeEmail');

        // Check if email is provided
        if (empty($email)) {
            return $this->response->setJSON(['code' => 403, 'status' => 'error', 'message' => lang('Contact.contact_email_required')]);
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['code' => 403, 'status' => 'error', 'message' => lang('Contact.contact_email_invalid')]);
        }
        // Check if the email already exists in the database
        $existingContact = $this->_model->where('email', $email)
            ->where('contact_type', ContactEnum::SUBSCRIBE_CONTACT_TYPE)
            ->first();

        if ($existingContact) {
            // If the email already exists, return a message indicating that
            return $this->response->setJSON(['code' => 403, 'status' => 'error', 'message' => lang('Contact.subscribeEmailExists')]);
        }

        $newData = [
            'fullname' => '',
            'phone' => '',
            'email' => $email,
            'contact_type' => ContactEnum::SUBSCRIBE_CONTACT_TYPE,
            'status' => ContactEnum::STATUS_NEW,
        ];

        $this->_model->insert($newData);
        // Check if the insert was successful
        if (!$this->_model->insertID()) {
            return $this->response->setJSON(['code' => 500, 'status' => 'error', 'message' => lang('Common.internalServerError')]);
        }

        return $this->response->setJSON(['code' => 200, 'status' => 'success', 'message' => lang('Contact.subscribeSuccess')]);
    }
}
