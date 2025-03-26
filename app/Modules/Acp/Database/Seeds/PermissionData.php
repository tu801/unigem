<?php
/**
 * @author tmtuan
 * created Date: 10/8/2021
 * project: fox_cms
 */

namespace Modules\Acp\Database\Seeds;

use CodeIgniter\CLI\CLI;
use Modules\Acp\Models\PermissionModel;

class PermissionData extends \CodeIgniter\Database\Seeder
{
    public $permissions;

    public function __construct()
    {
        $this->permissions = [
            [ "name" => "user", "group" => "acp", "description" => "xem danh sách user", "action" => "index", ],
            [ "name" => "user", "group" => "acp", "description" => "Thêm User Mới", "action" => "add", ],
            [ "name" => "user", "group" => "acp", "description" => "Sửa User", "action" => "edit", ],
            [ "name" => "user", "group" => "acp", "description" => "Xóa User", "action" => "remove", ],
            [ "name" => "user", "group" => "acp", "description" => "Phân quyền User", "action" => "editPermission", ],
            [ "name" => "config", "group" => "acp", "description" => "Thêm Cấu Hình", "action" => "add", ],
            [ "name" => "config", "group" => "acp", "description" => "Sửa Cấu Hình", "action" => "edit", ],
            [ "name" => "config", "group" => "acp", "description" => "Xoá Cấu Hình", "action" => "delete", ],
            [ "name" => "config", "group" => "acp",  "description" => "Quản Lý Cấu Hình", "action" => "index", ],
            [ "name" => "usergroup", "group" => "acp", "description" => "Quản lý nhóm", "action" => "index", ],
            [ "name" => "usergroup", "group" => "acp", "description" => "Thêm / Sửa nhóm", "action" => "updateGroup", ],
            [ "name" => "usergroup", "group" => "acp", "description" => "Xóa nhóm", "action" => "deleteGroup", ],
            [ "name" => "usergroup", "group" => "acp", "description" => "Phân quyền nhóm", "action" => "editPermission", ],
            [ "name" => "themeoptioncontroller", "group" => "acp", "description" => "Sửa cấu hình giao diện", "action" => "index", ],
            [ "name" => "post", "group" => "acp", "description" => "Thêm Bài Viết", "action" => "add", ],
            [ "name" => "post", "group" => "acp", "description" => "Sửa Bài Viết", "action" => "edit", ],
            [ "name" => "post", "group" => "acp", "description" => "Xoá Bài Viết", "action" => "delete", ],
            [ "name" => "post", "group" => "acp",  "description" => "Quản Lý Bài Viết", "action" => "index", ],
            [ "name" => "page", "group" => "acp", "description" => "Thêm Trang", "action" => "add", ],
            [ "name" => "page", "group" => "acp", "description" => "Sửa Trang", "action" => "edit", ],
            [ "name" => "page", "group" => "acp", "description" => "Xoá Trang", "action" => "delete", ],
            [ "name" => "page", "group" => "acp",  "description" => "Quản Lý Trang", "action" => "index", ],
            [ "name" => "category", "group" => "acp", "description" => "Thêm Danh Mục", "action" => "add", ],
            [ "name" => "category", "group" => "acp", "description" => "Sửa Danh Mục", "action" => "edit", ],
            [ "name" => "category", "group" => "acp", "description" => "Xoá Danh Mục", "action" => "delete", ],
            [ "name" => "category", "group" => "acp",  "description" => "Quản Lý Danh Mục", "action" => "index", ],
        ];
    }
    public function run() {
        $_model = model(PermissionModel::class);

        CLI::write("===== Start Seed Permission =====");
        foreach ($this->permissions as $item) {
            $check = $_model->where('name', $item['name'])
                ->where('group', $item['group'])
                ->where('action', $item['action'])
                ->first();

            if ( !isset($check->id) ) {
                //insert data
                $id = $_model->insert($item);
                CLI::write("- Đã thêm thành công quyền #{$id} - {$item['description']}<br>");
            }

        }
        CLI::write("===== Seed Permission Done! =====");
    }
}