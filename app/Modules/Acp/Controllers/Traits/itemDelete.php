<?php
/**
 * @author tmtuan
 * created Date: 15-May-21
 */

namespace Modules\Acp\Controllers\Traits;

trait itemDelete {
    /**
     * Remove an item
     */
    public function remove($idItem){
        $item = $this->_model->find($idItem);

        if ( isset($item->id) ) {
            if ( isset($item->user_id) ) {
                if (  $this->user->id == $item->user_id || $this->user->inGroup('superadmin', 'admin') ) {
                    if ($this->_model->delete($item->id)) return redirect()->back()->with('message', lang('Acp.delete_success', [$item->id]));
                    else return redirect()->back()->with('error', lang('Acp.delete_fail'));
                } else {
                    return redirect()->back()->with('error', lang('Acp.no_permission'));
                }
            } else {
                if ($this->_model->delete($item->id)) return redirect()->back()->with('message', lang('Acp.delete_success', [$item->id]));
                else return redirect()->route('post')->with('error', lang('Acp.delete_fail'));
            }

        } else return redirect()->route('dashboard')->with('error', lang('Acp.invalid_request'));
    }



}