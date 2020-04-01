<?php

namespace Manages\Model;



use Common\Model\ModelModel;



/**

 * 部门模型

 

 */

class GroupModel extends ModelModel

{

    /**

     * 数据库表名

     

     */

    protected $tableName = 'group';



    /**

     * 自动验证规则

     

     */

    protected $_validate = array(

        array('title', 'require', '角色名不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),

        array('title', '1,32', '角色名长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),

        array('title', '', '角色名已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),

        array('menu_auth', 'require', '权限不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),

    );



    /**

     * 自动完成规则

     

     */

    protected $_auto = array(

        array('create_time', 'time', self::MODEL_INSERT, 'function'),

        array('update_time', 'time', self::MODEL_BOTH, 'function'),

        array('status', '1', self::MODEL_INSERT),

    );



    /**

     * 检查部门功能权限

     

     */

    public function checkMenuAuth()

    {

        $current_col = CONTROLLER_NAME; // 当前菜单

        $user_col   = D('Manages/Menu')->getCol(); // 获得当前登录用户信息

        if ($user_col !== '1') {

            if(!in_array($current_col,$user_col)){

                return false;

            }

           

            return true;

        } else {

            return true; // 超级管理员无需验证

        }

        return false;

    }

}

