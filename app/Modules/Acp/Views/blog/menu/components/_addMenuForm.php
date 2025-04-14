<?php
/**
 * @author tmtuan
 * created Date: 8/19/2021
 * project: foxstore
 */
?>
<script type="text/x-template" id="vaddMenu-template">
    <div>
        <transition name="modal">
            <!-- Add Menu Modal -->
            <div class="modal fade" id="addMenuModal" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addMenuModalLabel">Thêm Menu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form action="<?=route_to('ajx_add_menu')?>" id="addMenuFrm" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label >Tên Menu</label>
                                <input type="text" class="form-control" id="inpName" v-model="name" placeholder="Nhập tên Menu">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="lang_id" value="<?=$currentLang->id??0?>" >
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" @click.prevent="onSubmit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</script>