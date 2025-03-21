<?php
namespace PhalApi\Auth\Auth\Domain;

use PhalApi\Auth\Auth\Model\Rule as Model_Auth_Rule;
/**
 * 规则领域类
 *
 * @author hms
 */
class Rule
{
    private static $Model = null;

    public function  __construct()
    {
        if (self::$Model == null) {
            self::$Model = new Model_Auth_Rule();
        }
    }

    /**获取规则列表
     * @param $apiObj Api对象，方便多参数获取
     * @return array 数据对象
     * @return array 数据对象[items] 数据项
     * @return int 数据对象[count] 数据总数 用于分页
     */
    public function getList($apiObj)
    {
        $rs = array('items' => array(), 'count' => 0);
        $param = get_object_vars($apiObj);
        $rs['count'] = self::$Model->getCount($param['keyWord']);
        $rs['items'] = self::$Model->getList($param);
        return $rs;
    }

    /**添加规则
     * @param $apiObj api对象
     * @return int 成功返回0，失败返回1，标识重复返回2，
     */
    public function addRule($apiObj)
    {
        $param = get_object_vars($apiObj);
        //检查规则标识是否重复，重复返回2
        $r = self::$Model->checkRepeat($param['name']);
        if ($r)
            return 2;
        //成功返回0，失败返回2
        $r = self::$Model->addRule($param);
        return $r == true ? 0 : 1;

    }

    /**修改规则
     * @param $apiObj
     * @return int 成功返回0，失败返回1，名称重复返回2
     */
    public function editRule($apiObj)
    {
        $param = get_object_vars($apiObj);
        //检查名称重复，重复返回2
        $r = self::$Model->checkRepeat($param['name'], $param['id']);
        if ($r)
            return 2;
        $r=self::$Model->editRule($param['id'], $param);
        return $r == true ? 0 : 1;
    }

    /** 删除规则
     * @param $ids id列表 如1,2,3
     * @return int
     */
    public function delRule($ids)
    {
        $arrIds = explode(',', $ids);
        $r=self::$Model->delRule($arrIds);
        return $r == true ? 0 : 1;
    }


    /** 获取单个规则信息
     * @param $id
     * @return mixed
     */
    public function getInfo($id)
    {
        $r = self::$Model->getInfo($id);
        return $r;
    }

    public function getRulesInGroups($gids) {
        if(\PhalApi\DI()->cache===null){
            $rules=self::$Model->getRulesInGroups( $gids);
        }else{
            $rules=self::$Model->getRulesInGroupsCache($gids);
        }
        return $rules;
    }
}
