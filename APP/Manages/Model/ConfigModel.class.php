<?php
namespace Manages\Model;

use Common\Model\ModelModel;

/**
 * 配置模型
 
 */
class ConfigModel extends ModelModel
{
    /**
     * 数据库表名
     
     */
    protected $tableName = 'config';

    /**
     * 自动验证规则
     
     */
    protected $_validate = array(
        array('group', 'require', '配置分组不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', 'require', '配置类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', 'require', '配置名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '1,32', '配置名称长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
        array('name', '', '配置名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('title', 'require', '配置标题必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,32', '配置标题长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
        array('title', '', '配置标题已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_BOTH),
    );

    /**
     * 获取配置列表与ThinkPHP配置合并
     * @return array 配置数组
     
     */
    public function lists($map,$field)
    {
        $map['status'] = array('eq', 1);
        $list          = $this->where($map)->field($field)->select();
        foreach ($list as $key => $val) {
            switch ($val['type']) {
                case 'array':
                    $config[$val['name']] = \Util\Str::parseAttr($val['value']);
                    break;
                case 'checkbox':
                    $config[$val['name']] = explode(',', $val['value']);
                    break;
                default:
                    $config[$val['name']] = $val['value'];
                    $config[]['tip'] = $val['tip'];
                    break;
            }
        }
        return $config;
    }
}
