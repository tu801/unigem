<?php
/**
 * @author tmtuan
 * created Date: 13-Apr-25
 */
namespace App\Models\Traits;

trait recoverItem {
    
    /**
     * Recover an item by its ID.
     *
     * @param int $id The ID of the item to recover.
     * @return bool True on success, false on failure.
     */
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