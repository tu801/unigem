<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 10/24/2023
 */

return [
    'page_title'            => 'Quản lý cửa hàng',
    'add_title'             => 'Thêm cửa hàng',
    'edit_title'            => 'Sửa thông tin cửa hàng',

    'image'                 => 'Ảnh đại diện',
    'name'                  => 'Tên cửa hàng',
    'phone'                 => 'Số điện thoại',
    'address'               => 'Địa chỉ',

    'status_active'         => 'Đang hoạt động',
    'status_in_active'      => 'Không hoạt động',

    // error message
    'name_required'         => 'Vui lòng điền tên cửa hàng',
    'name_is_exist'         => 'Tên cửa hàng đã tồn tại',
    'phone_required'        => 'Vui lòng điền số điện thoại',

    // success message
    'addSuccess'            => 'Đã tạo thành công cửa hàng #{0}',
    'editSuccess'           => 'Đã sửa thành công thông tin của cửa hàng #{0}',

    /******************************************************
     * shipping_config config page
     ******************************************************/
    'shipping_config_title'                 => 'Cấu hình phí giao hàng',
    'province_default_shipping_fee'         => 'Phí giao hàng mặc định theo tỉnh thành',
    'province_default_shipping_fee_helper'  => 'Nếu tỉnh thành nào không được thiết lập phí giao hàng thì sẽ lấy phí giao hàng này để tạm tính',
    'shipping_by_province'                  => 'Phí giao hàng theo tỉnh thành',
    'shipping_config_edit'                  => 'Sửa cấu hình phí giao hàng theo tỉnh thành',
    'weight_default_shipping_fee'           => 'Phí giao hàng theo cân nặng',
    'weight_default_shipping_fee_helper'    => 'Phí giao hàng tính theo số kg của sản phẩm',

    'config_id'             => 'ID',
    'edit_shipping_config'  => 'Sửa phí giao hàng',
    'shipping_config_fee'   => 'Phí giao hàng',

    'shipping_fee_required' => 'Vui lòng điền phí giao hàng theo tỉnh thành',
    'weight_fee_required'   => 'Vui lòng điền phí giao hàng theo cân nặng',
    'province_config_exist' => 'Cấu hình cho Tỉnh thành này đã tồn tại!',
    'no_shipping_fee'       => 'Chưa có cấu hình, vui lòng thêm cấu hình',

    'shipping_fee_save_success' => 'Đã lưu thành công phí giao hàng',
    'log_shipping_config_desc'  => '#{0} đã cấu hình phí giao hàng',
];