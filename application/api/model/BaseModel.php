<?php
/**
 * 基础模型类
 */

namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class BaseModel extends Model
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';
    protected $baseHidden = ['token','create_time','update_time','delete_time', 'pivot'];

    protected $baseType = [
        'create_time'  =>  'datetime',
        'update_time'  =>  'datetime',
        'delete_time'  =>  'datetime',
        'expire_time'  =>  'datetime',
    ];

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->type = array_merge($this->baseType, $this->type);
        $this->hidden = array_merge($this->baseHidden, $this->hidden);
    }

    public function closeTimestamp()
    {
        $this->autoWriteTimestamp = false;
    }

}