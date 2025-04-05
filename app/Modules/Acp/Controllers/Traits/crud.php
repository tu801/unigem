<?php
/**
 * @author tmtuan
 * created Date: 23-May-21
 */

namespace Modules\Acp\Controllers\Traits;

trait crud {

    /**
     * Display add page
     */
    public function add() {
        $this->_data['title'] = ( isset($this->_langKey) && !empty($this->_langKey) ) ? lang("{$this->_langKey}.add_title") : lang("{$this->_data['controller']}.add_title");
        $this->_data['lang'] = $this->request->getGet('lang') ?? 'vn';

        $this->_render('\\'.$this->_data['controller'].'\add', $this->_data);
    }

    public function edit($id) {
        $this->_data['title'] = ( isset($this->_langKey) && !empty($this->_langKey) ) ? lang("{$this->_langKey}.edit_title") : lang("{$this->_data['controller']}.edit_title");
        $item = $this->_model->find($id);
        if ( !isset($item->id) ) return redirect()->back()->with('error', lang('Acp.no_item_found'));

        $this->_data['itemData'] =  $item;
        $this->_data['lang'] = $item->lang??$this->request->getGet('lang')??'vn';
        $this->_render('\\'.$this->_data['controller'].'\edit', $this->_data);
    }

}