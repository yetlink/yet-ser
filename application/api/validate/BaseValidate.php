<?php
/**
 * 基础验证
 */

namespace app\api\validate;

use think\Request;
use think\Validate;
use app\lib\exception\ParameterException;

/**
 * Class BaseValidate
 * 验证类的基类
 */
class BaseValidate extends Validate
{
    /**
     * 检测所有客户端发来的参数是否符合验证类规则
     * 基类定义了很多自定义验证方法
     * 这些自定义验证方法其实，也可以直接调用
     * @throws ParameterException
     * @return true
     */
    public function goCheck()
    {
        //必须设置contetn-type:application/json
        $params = Request::instance()->param();
        $params['token'] = Request::instance()->header('token');
        if (!$this->check($params)) {
            $exception = new ParameterException(
                [
                    // $this->error有一个问题，并不是一定返回数组，需要判断
                    'msg' => is_array($this->error) ? implode(
                        ';', $this->error) : $this->error,
                ]);
            throw $exception;
        }
        return true;
    }

    /**
     * @param array $arrays 通常传入request.post变量数组
     * @param boolean $strict 严格模式，检查参数key是否存在
     * @return array 按照规则key过滤后的变量数组
     * @throws ParameterException
     */
    public function getDataByRule($arrays, $strict = false)
    {
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            if ($strict) {
                $newArray[$key] = $arrays[$key];
            } else {
                $newArray[$key] = array_key_exists($key, $arrays) ? $arrays[$key] : '';
            }
        }
        return $newArray;
    }

    protected function isInteger($value, $rule='', $data='', $field='')
    {
        if (isInteger($value)) {
            return true;
        }
        return $field . '必须是整数';
    }

    protected function isTimestamp($value, $rule='', $data='', $field='') {
        if(strtotime(date('Y-m-d H:i:s', $value)) == $value) {
            return true;
        }
        return $field . '必须是时间戳';
    }

    protected function isNotEmpty($value, $rule='', $data='', $field='')
    {
        if (empty($value)) {
            return $field . '不允许为空';
        } else {
            return true;
        }
    }

    protected function canEmpty($value = '', $rule='', $data='', $field='')
    {
        return true;
    }

}