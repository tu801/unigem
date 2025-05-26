<?php
/**
 * @author tmtuan
 * created Date: 13-Apr-25
 */
namespace App\Models\Traits;

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