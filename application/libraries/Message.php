<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class Message
 * @brief Message 消息类。
 * @file Message.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class Message{    
    //管理员类型
    protected $managerType = array(
        "1" => array('id'=> '1','name'=> '总管理员'),
        "2" => array('id'=> '2','name'=> '员工管理员'),
        "3" => array('id'=> '3','name'=> '合作伙伴管理员')
    );
    
    //消息类型(来源)
    protected $messageType = array(
        "1" => array('id'=> '1','name' => '任务','alias'=> '客户端传到后台'),
        "2" => array('id'=> '2','name' => '消息','alias'=> '总管理员对其他管理员（员工管理员、合作伙伴管理员）发布'),
        "3" => array('id'=> '3','name' => '通知','alias'=> '全时推送')        
    );
    //消息来源类型
    protected $sourceType = array(
        "1" => array('MSGTypeId'=> '1','id'=> '11','name'=> '员工管理请求消息'),
        "2" => array('MSGTypeId'=> '2','id'=> '21','name'=> '员工管理员管理'),
        "3" => array('MSGTypeId'=> '2','id'=> '22','name'=> 'Cost Center 管理'),
        "4" => array('MSGTypeId'=> '2','id'=> '23','name'=> '组织与员工管理'),
        "5" => array('MSGTypeId'=> '2','id'=> '24','name'=> '应用管理'),
        "6" => array('MSGTypeId'=> '2','id'=> '25','name'=> '安全管理'),
        "7" => array('MSGTypeId'=> '2','id'=> '26','name'=> '敏感词通知'),
        "8" => array('MSGTypeId'=> '2','id'=> '27','name'=> '合作伙伴管理'),
        "9" => array('MSGTypeId'=> '3','id'=> '31','name'=> '试用'),
        "10" => array('MSGTypeId'=> '3','id'=> '32','name'=> '正式用户'),
        "11" => array('MSGTypeId'=> '3','id'=> '33','name'=> '营销活动')
    );
    //消息
    protected $messageArr = array(
        "1" => array(
            'MSGTypeId'=> '1',
            'sourTypeId'=> '11',
            'id'=> '1101',
            'name'=> '调岗员工请求消息',
            'show' => '[部门管理者名称]申请[员工名称]调岗至[部门名称]',
            'managerTypeIds'=> '2',
            'operate' => '补充调岗后信息（职称空白；可选是否为部门管理者），确认后完成调岗'
            ),
        "2" => array(
            'MSGTypeId'=> '1',
            'sourTypeId'=> '11',
            'id'=> '1102',
            'name'=> '新增员工请求消息',
            'show' => '[部门管理者名称]申请新增员工：[员工名称]',
            'managerTypeIds'=> '2',
            'operate' => '补充员工信息，确认提交，完成创建员工'
            ),
        "3" => array(
            'MSGTypeId'=> '1',
            'sourTypeId'=> '11',
            'id'=> '1103',
            'name'=> '删除员工请求消息',
            'show' => '[部门管理者名称]申请删除员工：[员工名称]',
            'managerTypeIds'=> '2',
            'operate' => '查看员工信息，确认后删除'
            ),
        "4" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '21',
            'id'=> '2101',
            'name'=> '添加员工管理员',
            'show' => '[员工名称]已被总管理员指派为员工管理员',
            'managerTypeIds'=> '2',
            'operate' => '无“查看详情”；[员工名称]可点击，查看员工信息'
            ),
        "5" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '21',
            'id'=> '2102',
            'name'=> '删除员工管理员',
            'show' => '[员工名称]已被总管理员移除员工管理员角色',
            'managerTypeIds'=> '2',
            'operate' => '无“查看详情”；[员工名称]可点击，查看员工信息'
            ),
        "6" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '21',
            'id'=> '2102',
            'name'=> '变更员工管理员管理范围',
            'show' => '[员工名称]的管理范围已经变更',
            'managerTypeIds'=> '2',
            'operate' => '无“查看详情”；[员工名称]可点击，查看员工信息'
            ),
        "7" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '22',
            'id'=> '2201',
            'name'=> '新增 Cost Center',
            'show' => '总管理员新增 Cost Center：[Cost Center 名称]',
            'managerTypeIds'=> '2',
            'operate' => '显示 Cost Center 列表，光标停留在此 Cost Center'
            ),
        "8" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '22',
            'id'=> '2202',
            'name'=> '删除 Cost Center',
            'show' => '总管理员删除 Cost Center：[Cost Center 名称]',
            'managerTypeIds'=> '2',
            'operate' => '显示 Cost Center 列表，光标停留在前一个 Cost Center'
            ),
        "9" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '22',
            'id'=> '2203',
            'name'=> '修改 Cost Center',
            'show' => '总管理员修改 Cost Center：[Cost Center 名称]',
            'managerTypeIds'=> '2',
            'operate' => '显示 Cost Center 列表，光标停留在此 Cost Center '
            ),
        "10" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '23',
            'id'=> '2301',
            'name'=> '新增部门',
            'show' => '总管理员在[公司名称]/[一级部门]/[二级部门]/[三级部门]新增部门：[部门名称]',
            'managerTypeIds'=> '2',
            'operate' => '显示部门'
            ),
        "11" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '23',
            'id'=> '2302',
            'name'=> '删除部门',
            'show' => '总管理员删除[公司名称]/[一级部门]/[二级部门]/[三级部门]之下的部门：[四级部门名称]',
            'managerTypeIds'=> '2',
            'operate' => '显示上一级部门'
            ),
        "12" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '23',
            'id'=> '2303',
            'name'=> '新增员工标签',
            'show' => '总管理员新增员工标签：[员工标签]，请立即更新',
            'managerTypeIds'=> '2',
            'operate' => '显示员工列表'
            ),
        "13" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '23',
            'id'=> '2304',
            'name'=> '删除员工标签',
            'show' => '总管理员删除员工标签：[员工标签]',
            'managerTypeIds'=> '2',
            'operate' => '无操作（无“查看详情”）'
            ),
        "14" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '23',
            'id'=> '2305',
            'name'=> '变更员工标签填写规则',
            'show' => '总管理员修改 [标签名称]的填写规则',
            'managerTypeIds'=> '2',
            'operate' => '显示员工列表'
            ),
        "15" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '24',
            'id'=> '2401',
            'name'=> '新加应用',
            'show' => '总管理员添加了新的应用',
            'managerTypeIds'=> '2,3',
            'operate' => '显示应用列表'
            ),
        "16" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '24',
            'id'=> '2402',
            'name'=> '删除应用',
            'show' => '总管理员删除了新的应用',
            'managerTypeIds'=> '2,3',
            'operate' => '显示应用列表'
            ),
        "17" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '25',
            'id'=> '2501',
            'name'=> '添加敏感词',
            'show' => '总管理员添加了敏感词：',
            'managerTypeIds'=> '2,3',
            'operate' => '显示敏感词列表'
            ),
        "18" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '25',
            'id'=> '2502',
            'name'=> '添加敏感词',
            'show' => '总管理员添加了敏感词：[敏感词]',
            'managerTypeIds'=> '2,3',
            'operate' => '显示敏感词列表'
            ),
        "19" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '25',
            'id'=> '2503',
            'name'=> '新建豁免人员',
            'show' => '[豁免人员员工名称]已经被加入豁免名单',
            'managerTypeIds'=> '2,3',
            'operate' => '显示豁免名单'
            ),
        "20" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '25',
            'id'=> '2504',
            'name'=> '移除豁免人员',
            'show' => '[豁免人员员工名称]已经豁免名单当中移除',
            'managerTypeIds'=> '2,3',
            'operate' => '显示豁免名单'
            ),
        "21" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '25',
            'id'=> '2505',
            'name'=> '解散群',
            'show' => '总管理员解散了[群]',
            'managerTypeIds'=> '2,3',
            'operate' => '显示『群』列表'
            ),
        
        "22" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '26',
            'id'=> '2601',
            'name'=> '违反敏感词规则通知',
            'show' => '[员工名称]内容违反敏感词：[敏感词内容]',
            'managerTypeIds'=> '2',
            'operate' => '跳转显示聊天信息内容页面（第一个敏感词出现处，敏感词高亮显示）'
            ),
        
        "23" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '27',
            'id'=> '2701',
            'name'=> '开通组织具有合作伙伴权限',
            'show' => '[部门名称]的合作伙伴权限已被开通',
            'managerTypeIds'=> '3',
            'operate' => '查看[部门名称]信息（权限）'
            ),
        
        "24" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '27',
            'id'=> '2702',
            'name'=> '关闭组织合作伙伴权限',
            'show' => '[部门名称]的合作伙伴权限已被关闭',
            'managerTypeIds'=> '3',
            'operate' => '查看[部门名称]信息（权限）'
            ),
        
        "25" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '27',
            'id'=> '2703',
            'name'=> '与某企业关闭合作伙伴关系',
            'show' => '[企业名称]与我司的合作伙伴关系已经结束',
            'managerTypeIds'=> '3',
            'operate' => '查看[部门名称]信息（权限）'
            ),
        
        "26" => array(
            'MSGTypeId'=> '2',
            'sourTypeId'=> '27',
            'id'=> '2704',
            'name'=> '与某企业建立合作伙伴关系',
            'show' => '[企业名称]与我司的合作伙伴关系已经开始',
            'managerTypeIds'=> '3',
            'operate' => '查看[部门名称]信息（权限）'
            ),
        "27" => array(
            'MSGTypeId'=> '3',
            'sourTypeId'=> '31',
            'id'=> '3101',
            'name'=> '开始试用',
            'show' => '您的全时蜜蜂已经开始试用',
            'managerTypeIds'=> '1',
            'operate' => '无操作'
            ),
        "28" => array(
            'MSGTypeId'=> '3',
            'sourTypeId'=> '31',
            'id'=> '3102',
            'name'=> '试用即将结束',
            'show' => '您的全时蜜蜂试用即将到期，欢迎联系您的销售',
            'managerTypeIds'=> '1',
            'operate' => '无操作'
            ),
        "29" => array(
            'MSGTypeId'=> '3',
            'sourTypeId'=> '31',
            'id'=> '3103',
            'name'=> '试用到期',
            'show' => '您的全时蜜蜂试用期已经结束',
            'managerTypeIds'=> '1',
            'operate' => '无操作'
            ),
        
        "30" => array(
            'MSGTypeId'=> '3',
            'sourTypeId'=> '32',
            'id'=> '3201',
            'name'=> '开始使用',
            'show' => '您的全时蜜蜂已经开始启用',
            'managerTypeIds'=> '1',
            'operate' => '无操作'
            ),
        
        "31" => array(
            'MSGTypeId'=> '3',
            'sourTypeId'=> '32',
            'id'=> '3202',
            'name'=> '使用到期',
            'show' => '您的全时蜜蜂使用已经到期，请立即续约',
            'managerTypeIds'=> '1',
            'operate' => '无操作'
            ),
        
        "32" => array(
            'MSGTypeId'=> '3',
            'sourTypeId'=> '32',
            'id'=> '3203',
            'name'=> '后台升级维护通知',
            'show' => '全时蜜蜂即将升级通知信息',
            'managerTypeIds'=> '1',
            'operate' => '无操作'
            ),
        
        "33" => array(
            'MSGTypeId'=> '3',
            'sourTypeId'=> '33',
            'id'=> '3301',
            'name'=> '研讨会信息',
            'show' => '全时蜜蜂研讨会：[研讨会名称]信息',
            'managerTypeIds'=> '1,2,3',
            'operate' => '无操作'
            ),
        
        "34" => array(
            'MSGTypeId'=> '3',
            'sourTypeId'=> '33',
            'id'=> '3302',
            'name'=> '功能简介信息',
            'show' => '全时蜜蜂[功能名称]简介',
            'managerTypeIds'=> '1,2,3',
            'operate' => '无操作'
            )
    );    
 
}
