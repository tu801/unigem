<?php
namespace App\Controllers\Customer;

use App\Enums\UserTypeEnum;
use App\Models\Store\Customer\CustomerShipAddressModel;

class ShippingAddress extends CustomerController {

    protected $shippingAddressModel;

    public function __construct()
    {
        parent::__construct();
        $this->shippingAddressModel = model(CustomerShipAddressModel::class);
    }
    
    public function index() {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }
        $this->page_title = lang('Customer.shipping_address');

        return $this->_render('customer/shipping_address/my_shipping_address', $this->_data);
    }

    public function createNewAddress() {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        $this->page_title = lang('Customer.add_new_address');
        $this->_data['countries'] = $this->countryModel->getCountries();

        if ( $this->request->getPost() ) {
            $this->checkSpam();
            return $this->saveNewAddressAction();
        }

        return $this->_render('customer/shipping_address/add_new_address', $this->_data);
    }

    public function saveNewAddressAction() {
        $postData = $this->request->getPost();
        if (empty($postData)) {
            return redirect()->back()->with('errors', lang('Acp.invalid_request'));
        }

        // Validate and save the new address
        if (! $this->validate($this->getValidationRules())) {
            //return the errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for saving
        $postData['cus_id'] = $this->_data['customer']->id;
        if ( isset($postData['is_default']) && $postData['is_default'] == 1 ) {
            // If the address is set as default, unset the previous default address
            $this->shippingAddressModel->unsetDefaultAddressItem($this->_data['customer']->id);
        } else {
            $postData['is_default'] = 0;
        }

        if ( $postData['country_id'] != VIETNAM_COUNTRY_ID ) {
            $postData['province_id']  = 0;
            $postData['district_id']  = 0;
            $postData['ward_id']      = 0;
        }

        $result = $this->shippingAddressModel->save($postData, $this->user->id);

        if ($result) {
            return redirect()->route('my_shipping_address')->with('message', lang('Customer.address_added_successfully'));
        } else {
            return redirect()->back()->withInput()->with('errors', $this->shippingAddressModel->errors());
        }
    }

    /**
     * Show edit shipping address form
     */
    public function edit($id) {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        $this->page_title = lang('Customer.edit_shipping_address');

        $item = $this->shippingAddressModel
            ->where('id', $id)
            ->where('cus_id', $this->_data['customer']->id)
            ->first();

        if (!$item) {
            return redirect()->route('my_shipping_address')->with('error', lang('Customer.ship_address_not_found'));
        }
        if ( $item->cus_id != $this->_data['customer']->id ) {
            return redirect()->route('my_shipping_address')->with('error', lang('Customer.ship_address_not_found'));
        }

        if ( $this->request->getPost() ) {
            $this->checkSpam();
            return $this->editShippingAddressAction($item);
        }

        $this->_data['shipAddress'] = $item;
        $this->_data['countries'] = $this->countryModel->getCountries();

        return $this->_render('customer/shipping_address/edit_ship_address', $this->_data);
    }

    /**
     * Handle the submission of the edited shipping address
     * @param object $item
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function editShippingAddressAction($item) {
        $postData = $this->request->getPost();
        if (empty($postData)) {
            return redirect()->back()->with('errors', lang('Acp.invalid_request'));
        }

        // Validate and save the edited address
        if (! $this->validate($this->getValidationRules($item))) {
            //return the errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for saving
        if ( isset($postData['is_default']) && $postData['is_default'] == 1 ) {
            if ( $item->is_default == 0 ) {
                // If the address is set as default, unset the previous default address
                $this->shippingAddressModel->unsetDefaultAddressItem($this->_data['customer']->id);
            }
        }

        if ( $postData['country_id'] != VIETNAM_COUNTRY_ID ) {
            $postData['province_id']  = 0;
            $postData['district_id']  = 0;
            $postData['ward_id']      = 0;
        }
            
        $result = $this->shippingAddressModel->update($item->id, $postData);

        if ($result) {
            return redirect()->route('my_shipping_address')->with('message', lang('Customer.address_updated_successfully'));
        } else {
            return redirect()->back()->withInput()->with('errors', $this->shippingAddressModel->errors());
        }
    }

    private function getValidationRules($item = null) {
        $validRules = [
            'ship_full_name' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => lang('Customer.ship_full_name_required'),
                    'min_length' => lang('Customer.ship_full_name_min_length'),
                ]
            ],
            'ship_address' => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Customer.ship_address_required'),
                ]
            ],
        ];

        if ( isset($item) && $item !== null ) {
            // If editing an existing address, ensure the telephone is unique except for the current item
            $validRules['ship_telephone'] = [
                'rules' => 'required|min_length[8]|is_unique[customer_ship_address.ship_telephone,id,' . $item->id . ']',
                'errors' => [
                    'required' => lang('Customer.ship_telephone_required'),
                    'min_length' => lang('Customer.ship_telephone_min_length'),
                    'is_unique' => lang('Customer.ship_telephone_unique'),
                ]
            ];
        } else {
            $validRules['ship_telephone'] = [
                'rules' => 'required|min_length[8]|is_unique[customer_ship_address.ship_telephone]',
                'errors' => [
                    'required' => lang('Customer.ship_telephone_required'),
                    'min_length' => lang('Customer.ship_telephone_min_length'),
                    'is_unique' => lang('Customer.ship_telephone_unique'),
                ]
            ];
        }

        return $validRules;

    }

    /**
     * Delete a shipping address
     * @param int $id
     */
    public function delete($id) {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        $item = $this->shippingAddressModel
            ->where('id', $id)
            ->where('cus_id', $this->_data['customer']->id)
            ->first();

        if (!$item) {
            return redirect()->route('my_shipping_address')->with('error', lang('Customer.ship_address_not_found'));
        }
        if ( $item->cus_id != $this->_data['customer']->id ) {
            return redirect()->route('my_shipping_address')->with('error', lang('Customer.ship_address_not_found'));
        }

        try {
            $this->db->transBegin();
            if ( $this->shippingAddressModel->delete($id) ) {
                // log Action
                $logData = [
                    'title' => 'Delete Customer Shipping Address',
                    'description' => lang('Customer.deleteCustomerShippingAddressLog', [ $this->_data['customer']->cus_code . ' - ' . $this->_data['customer']->cus_full_name], $item->id),
                    'properties' => $item,
                    'subject_id' => $item->id,
                    'subject_type' => CustomerShipAddressModel::class,
                ];
                $this->logAction($logData);
                $this->db->transCommit();

                return redirect()->route('my_shipping_address')->with('message', lang('Customer.address_deleted_successfully'));
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('errors', lang('Customer.address_delete_error') . ': ' . $e->getMessage());
        }

    }
}