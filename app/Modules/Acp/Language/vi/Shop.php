<?php

/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 05/17/2025
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

    // Exchange Rate Page
    'exchange_rate_manager'             => 'Quản lý tỷ giá',

    'note'                              => 'Ghi chú',
    'currency_exchange_info'            => 'Toàn bộ tỷ giá đều được quy đổi sang tiền Việt Nam (VND) và được sử dụng để tính toán giá trị của các đơn hàng trong cửa hàng.',

    'currency_from'                     => 'Ngoại tệ chuyển đổi',
    'currency_to'                       => 'Tỷ giá quy đổi (VND)',
    'current_exchange_rate'             => 'Tỷ giá hiện tại',
    'last_update'                       => 'Cập nhật lần cuối',

    'save_exchange_rate'                => 'Lưu tỷ giá',

    'rate_required'                     => 'Vui lòng nhập tỷ giá quy đổi',
    'rate_decimal'                      => 'Tỷ giá quy đổi phải là một số hợp lệ',
    'add_exchange_rate_log_title'       => 'Thêm tỷ giá hối đoái từ {0} sang {1}',
    'add_exchange_rate_log_desc'        => 'Người dùng {0} đã thêm tỷ giá hối đoái mới từ {1} sang {2}.',
    'addRateSuccess'                    => 'Tỷ giá hối đoái đã được thêm thành công [{0}]',
    'editRateSuccess'                   => 'Tỷ giá hối đoái đã được cập nhật thành công [{0}]',


    // Voucher Management Page
    'voucher_page_title'                => 'Quản lý mã giảm giá',
    'add_voucher_title'                 => 'Thêm mã giảm giá',
    'edit_voucher_title'                => 'Sửa mã giảm giá',

    'voucher_code'                      => 'Mã giảm giá',
    'voucher_code_info'                 => 'Mã giảm giá phải là duy nhất và không được chứa khoảng trắng. Ví dụ: "GIAMGIA2025". Nếu không gắn mã, hệ thống sẽ tự động tạo mã giảm giá ngẫu nhiên.',
    'voucher_title'                     => 'Tiêu đề mã giảm giá',
    'voucher_description'               => 'Mô tả mã giảm giá',
    'voucher_discount'                  => 'Giảm giá',
    'voucher_time'                      => 'Thời gian sử dụng',
    'voucher_status'                    => 'Trạng thái',
    'voucher_status_0'                  => 'Chưa sử dụng',
    'voucher_status_1'                  => 'Đang sử dụng',
    'voucher_status_2'                  => 'Đã hết hạn',
    'voucher_discount_type'             => 'Loại giảm giá',
    'voucher_discount_type_percentage'  => 'Giảm giá theo phần trăm',
    'voucher_discount_type_fixed_amount' => 'Giảm giá theo số tiền cố định',
    'voucher_discount_value'            => 'Giá trị',
    'voucher_start_date'                => 'Ngày bắt đầu',
    'voucher_end_date'                  => 'Ngày kết thúc',
    'voucher_currency_list'             => 'Loại tiền tệ',

    'voucher_code_is_exist'             => 'Mã giảm giá đã tồn tại, vui lòng chọn mã khác.',
    'voucher_title_required'            => 'Vui lòng nhập tiêu đề mã giảm giá.',
    'voucher_discount_type_required'    => 'Vui lòng chọn loại giảm giá.',
    'voucher_discount_value_required'   => 'Vui lòng nhập giá trị giảm giá.',
    'voucher_code_not_found'            => 'Mã giảm giá không tồn tại hoặc đã hết hạn.',
    'voucher_code_required'             => 'Vui lòng nhập mã giảm giá.',
];