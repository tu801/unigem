<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 10/25/2023
 */

return [
    // Thông báo lõi
    'noRuleSets'      => 'Không có bộ quy tắc nào được chỉ định trong cấu hình xác thực.',
    'ruleNotFound'    => '"{0}" không phải là một quy tắc hợp lệ.',
    'groupNotFound'   => '"{0}" không phải là một nhóm quy tắc xác thực.',
    'groupNotArray'   => 'Nhóm quy tắc "{0}" phải là một mảng.',
    'invalidTemplate' => '"{0}" không phải là mẫu xác thực hợp lệ.',

    // Thông báo quy tắc
    'alpha'                 => 'Trường {field} chỉ được chứa các ký tự chữ cái.',
    'alpha_dash'            => 'Trường {field} chỉ được chứa ký tự chữ cái, số, gạch dưới và gạch ngang.',
    'alpha_numeric'         => 'Trường {field} chỉ được chứa ký tự chữ cái và số.',
    'alpha_numeric_punct'   => 'Trường {field} chỉ được chứa ký tự chữ cái, số, khoảng trắng và các ký tự ~ ! # $ % & * - _ + = | : .',
    'alpha_numeric_space'   => 'Trường {field} chỉ được chứa ký tự chữ cái, số và khoảng trắng.',
    'alpha_space'           => 'Trường {field} chỉ được chứa ký tự chữ cái và khoảng trắng.',
    'decimal'               => 'Trường {field} phải chứa một số thập phân.',
    'differs'               => 'Trường {field} phải khác với trường {param}.',
    'equals'                => 'Trường {field} phải chính xác là: {param}.',
    'exact_length'          => 'Trường {field} phải có đúng {param} ký tự.',
    'greater_than'          => 'Trường {field} phải chứa một số lớn hơn {param}.',
    'greater_than_equal_to' => 'Trường {field} phải chứa một số lớn hơn hoặc bằng {param}.',
    'hex'                   => 'Trường {field} chỉ được chứa ký tự thập lục phân.',
    'in_list'               => 'Trường {field} phải là một trong các giá trị: {param}.',
    'integer'               => 'Trường {field} phải là một số nguyên.',
    'is_natural'            => 'Trường {field} chỉ được chứa các chữ số.',
    'is_natural_no_zero'    => 'Trường {field} chỉ được chứa các chữ số và phải lớn hơn 0.',
    'is_not_unique'         => 'Trường {field} phải chứa một giá trị đã tồn tại trong cơ sở dữ liệu.',
    'is_unique'             => 'Trường {field} phải chứa một giá trị duy nhất.',
    'less_than'             => 'Trường {field} phải chứa một số nhỏ hơn {param}.',
    'less_than_equal_to'    => 'Trường {field} phải chứa một số nhỏ hơn hoặc bằng {param}.',
    'matches'               => 'Trường {field} không khớp với trường {param}.',
    'max_length'            => 'Trường {field} không được vượt quá {param} ký tự.',
    'min_length'            => 'Trường {field} phải có ít nhất {param} ký tự.',
    'not_equals'            => 'Trường {field} không được là: {param}.',
    'not_in_list'           => 'Trường {field} không được là một trong các giá trị: {param}.',
    'numeric'               => 'Trường {field} chỉ được chứa số.',
    'regex_match'           => 'Trường {field} không đúng định dạng.',
    'required'              => 'Trường {field} là bắt buộc.',
    'required_with'         => 'Trường {field} là bắt buộc khi {param} có mặt.',
    'required_without'      => 'Trường {field} là bắt buộc khi {param} không có mặt.',
    'string'                => 'Trường {field} phải là một chuỗi hợp lệ.',
    'timezone'              => 'Trường {field} phải là một múi giờ hợp lệ.',
    'valid_base64'          => 'Trường {field} phải là một chuỗi base64 hợp lệ.',
    'valid_email'           => 'Trường {field} phải chứa một địa chỉ email hợp lệ.',
    'valid_emails'          => 'Trường {field} phải chứa tất cả các địa chỉ email hợp lệ.',
    'valid_ip'              => 'Trường {field} phải chứa một địa chỉ IP hợp lệ.',
    'valid_url'             => 'Trường {field} phải chứa một URL hợp lệ.',
    'valid_url_strict'      => 'Trường {field} phải chứa một URL hợp lệ.',
    'valid_date'            => 'Trường {field} phải chứa một ngày hợp lệ.',
    'valid_json'            => 'Trường {field} phải chứa một chuỗi json hợp lệ.',

    // Thẻ tín dụng
    'valid_cc_num' => '{field} không phải là số thẻ tín dụng hợp lệ.',

    // Tập tin
    'uploaded' => '{field} không phải là tệp tin đã tải lên hợp lệ.',
    'max_size' => '{field} là tệp tin quá lớn.',
    'is_image' => '{field} không phải là tệp hình ảnh hợp lệ đã tải lên.',
    'mime_in'  => '{field} không có kiểu mime hợp lệ.',
    'ext_in'   => '{field} không có phần mở rộng tệp hợp lệ.',
    'max_dims' => '{field} hoặc không phải là hình ảnh, hoặc quá rộng hoặc quá cao.',

    // Tùy chỉnh
    'invalid_phone_number'  => 'Số điện thoại không hợp lệ'
];