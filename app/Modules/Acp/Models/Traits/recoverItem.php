<?php
/**
 * @author tmtuan
 * created Date: 14-Apr-21
 */
namespace Modules\Acp\Models\Traits;

trait recoverItem {
    public function recover($id) {
        $tblPrefix = $this->db->getPrefix();
        $tableName = $tblPrefix.$this->table;
        if ( $this->useSoftDeletes ) {
            $query = $this->db->query("UPDATE {$tableName} SET deleted_at = NULL");
            if($query) return true;
            else return false;
        } return false;
    }
}