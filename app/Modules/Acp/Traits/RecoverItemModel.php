<?php

namespace Modules\Acp\Traits;

trait RecoverItemModel
{
    /**
     * Recover soft delete item
     * @param $id
     * @return bool
     */
    public function recover($id)
    {
        $sql = "UPDATE `{$this->table}` SET `deleted_at` = NULL WHERE `{$this->table}`.`id` = {$id}";
        if ($this->db->query($sql)) return true;
        else return false;
    }
}