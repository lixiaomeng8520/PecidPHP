<?php
/**
 *	包括框架内所用到的函数
 *	@author lxm
 */


/**
 * 递归方式的对变量中的特殊字符进行转义
 *
 * @access  public
 * @param   mix     $value
 * @return  mix
 */
function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}

/**
 * 根据PHP各种类型变量生成唯一标识号
 */
function to_guid_string($mix) {
    if(is_object($mix) && function_exists('spl_object_hash')) {
        return spl_object_hash($mix);
    }elseif(is_resource($mix)){
        $mix = get_resource_type($mix).strval($mix);
    }else{
        $mix = serialize($mix);
    }
    return md5($mix);
}

/**
 *    导入一个类
 *
 *    @author    Garbin
 *    @return    void
 */
function import($class)
{
    require_once(CORE_PATH.'/include/'.$class.'.php');
}

/**
 *  设置和获取配置信息
 *  @author lxm
 */
function C($k = null, $v = null)
{
    static $_config = array();
    if($k === null) return $_config;

    if(is_string($k))
    {
        if($v === null)
        {
            return $_config[$k];
        }
        else
        {
            $_config[$k] = $v;
        }
    }

    if(is_array($k))
    {
        return $_config = array_merge($_config, array_change_key_case($k));
    }

    return null;
}
?>