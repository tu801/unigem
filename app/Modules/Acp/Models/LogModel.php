<?php
/**
 * @author tmtuan
 * created Date: 9/29/2021
 * project: fox_cms
 */

namespace Modules\Acp\Models;


use CodeIgniter\Model;

class LogModel extends  Model
{
    protected $table = 'log';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'module', 'title', 'description', 'properties', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'lang_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    protected $skipValidation = true;

}