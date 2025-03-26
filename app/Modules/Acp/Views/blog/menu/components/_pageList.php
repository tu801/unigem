<?php
/**
 * @author tmtuan
 * created Date: 10/15/2021
 * project: basic_cms
 */
?>

<div class="card card-primary card-outline collapsed-card" id="listPageApp" data-sltMenu="<?=$menuItem->id?>">
    <div class="card-header">
        <h3 class="card-title">Trang</h3>
        <div class="card-tools">
            <button type="button" class="btn bg-primary btn-xs mr-1" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn bg-primary btn-xs" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Nhập tiêu đề trang" v-model="title" @keyup="onKeyUp()">
                    <div class="input-group-append">
                      <button class="input-group-text" type="button" @click.prevent="fetchPages">
                        <i class="fa fa-search"></i>
                      </button>
                    </div>
                </div>
            </div>
        </div>
        <table class="table no-margin">
            <tbody>
                <tr v-for="item in pageList" :key="item.id" >
                    <td width="90%">{{ item.title }}</td>
                    <td width="10%" v-html="rdAddBtn(item)"></td>
                </tr>
            </tbody>
        </table>

    </div>

</div>
