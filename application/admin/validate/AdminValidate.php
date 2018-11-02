<?php
/**
 * Created by PhpStorm.
 * User: atomic
 * Date: 5/26/2018
 * Time: 15:47
 */
namespace app\admin\Validate;
use think\Validate;

class AdminValidate extends Validate{
    protected $rule = [
        ['username', 'require', '用户名和密码不能为空'],
        ['password', 'require', '密码不能为空'],
        ['verifycode', 'require', '验证码不能为空']
    ];
}