<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_class {
   private $ok_notice_arr = array() ;//当前需要的消息数组
   private $ok_class_arr = array() ;//类型数组
   private $class_arr = array(
       array(
        'id' => 1 ,//id
        'name' => '任务',//名称
        'isopen' => 1,//是否开启
       ),
       array(
        'id' => 2 ,//id
        'name' => '消息',//名称
        'isopen' => 1,//是否开启
       ),
       array(
        'id' => 3 ,//id
        'name' => '通知',//名称
        'isopen' => 1,//是否开启
       ),
   );
   
   private $notice_arr = array(//类型数组
         array(
            'id' => 1,//id
            'name' => '员工管理员管理',//
            'isopen' => 1,//是否开启
            'class_arr' => array('2'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '添加员工管理员',
                    'display_content' => '{display_name}已被总管理员指派为员工管理员',
                    'format' =>array(
                        'display_name' => '员工名称',//员工姓名
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                    
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '删除员工管理员',
                    'display_content' => '{display_name}已被总管理员移除员工管理员角色',
                    'format' =>array(
                        'display_name' => '员工名称',//员工姓名
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'name' => '变更员工管理员管理范围',
                    'display_content' => '{display_name}的管理范围已经变更',
                    'format' =>array(
                        'display_name' => '员工名称',//员工姓名
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
            ),
        ), 
         array(
            'id' => 2,//id
            'name' => 'Cost Center 管理',//
            'isopen' => 1,//是否开启
            'class_arr' => array('2'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '新增 Cost Center',
                    'display_content' => '总管理员新增 Cost Center：{cost_center_name}',
                    'format' =>array(
                        'cost_center_name' => 'Cost Center 名称',//Cost Center 名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '删除 Cost Center',
                    'display_content' => '总管理员删除 Cost Center：{cost_center_name}',
                    'format' =>array(
                        'cost_center_name' => 'Cost Center 名称',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'name' => '修改 Cost Center',
                    'display_content' => '总管理员修改 Cost Center：{cost_center_name}',
                    'format' =>array(
                        'cost_center_name' => 'Cost Center 名称',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
            ),
        ), 
         array(
            'id' => 3,//id
            'name' => '组织与员工管理',//
            'isopen' => 1,//是否开启
            'class_arr' => array('2'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '新增部门',
                    'display_content' => '总管理员在{parent_org_names}新增部门：{org_name}',
                    'format' =>array(
                        'parent_org_names' => '[公司名称]/[一级部门]/[二级部门]/[三级部门]',//[公司名称]/[一级部门]/[二级部门]/[三级部门]
                        'org_name' =>'四级部门名称',//[四级部门名称]
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '删除部门',
                    'display_content' => '总管理员删除{parent_org_names}之下的部门：{org_name}',
                    'format' =>array(
                        'parent_org_names' => '[公司名称]/[一级部门]/[二级部门]/[三级部门]',//[公司名称]/[一级部门]/[二级部门]/[三级部门]
                        'org_name' =>'四级部门名称',//[四级部门名称]
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'name' => '新增员工标签',
                    'display_content' => '总管理员新增员工标签：{tag_name}，请立即更新',
                    'format' =>array(
                        'tag_name' => '员工标签',//员工标签
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 4,
                    'isopen' => 1,//是否开启
                    'name' => '删除员工标签',
                    'display_content' => '总管理员删除员工标签：{tag_name}',
                    'format' =>array(
                        'tag_name' => '员工标签',//员工标签
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 5,
                    'isopen' => 1,//是否开启
                    'name' => '变更员工标签填写规则',
                    'display_content' => '总管理员修改{tag_name}的填写规则',
                    'format' =>array(
                        'tag_name' => '标签名称',//标签名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
            ),
        ), 
         array(
            'id' => 4,//id
            'name' => '应用管理',//
            'isopen' => 1,//是否开启
            'class_arr' => array('2'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '新加应用',
                    'display_content' => '总管理员添加了新的应用',
                    'format' =>array(
                        //'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3','5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '删除应用',
                    'display_content' => '总管理员删除了新的应用',
                    'format' =>array(
                        //'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3','5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
            ),
        ), 
         array(
            'id' => 5,//id
            'name' => '安全管理',//
            'isopen' => 1,//是否开启
            'class_arr' => array('2'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '添加敏感词',
                    'display_content' => '总管理员添加了敏感词：',
                    'format' =>array(
                        //'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3','5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '违背敏感词的用户信息',
                    'display_content' => '',
                    'format' =>array(
                        //'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3','5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'name' => '新建豁免人员',
                    'display_content' => '{display_name}已经被加入豁免名单',
                    'format' =>array(
                        'display_name' => '豁免人员员工名称',//豁免人员员工名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3','5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 4,
                    'isopen' => 1,//是否开启
                    'name' => '移除豁免人员',
                    'display_content' => '{display_name}已经豁免名单当中移除',
                    'format' =>array(
                        'display_name' => '豁免人员员工名称',//豁免人员员工名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3','5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 5,
                    'isopen' => 1,//是否开启
                    'name' => '解散群',
                    'display_content' => '',
                    'format' =>array(
                        //'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3','5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
            ),
        ), 
         array(
            'id' => 6,//id
            'name' => '员工管理请求消息',//
            'isopen' => 1,//是否开启
            'class_arr' => array('1'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '调岗员工请求消息',
                    'display_content' => '{org_manager_name}申请{display_name}调岗至[部门名称]',
                    'format' =>array(
                        'org_manager_name' => '部门管理者名称',//部门管理者名称
                        'display_name' => '员工名称',//员工名称
                        'org_name' => '部门名称'
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '新增员工请求消息',
                    'display_content' => '{org_manager_name}申请新增员工：{display_name}',
                    'format' =>array(
                        'org_manager_name' => '部门管理者名称',//部门管理者名称
                        'display_name' => '员工名称',//员工名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'name' => '删除员工请求消息',
                    'display_content' => '{org_manager_name}申请删除员工：{display_name}',
                    'format' =>array(
                        'org_manager_name' => '部门管理者名称',//部门管理者名称
                        'display_name' => '员工名称',//员工名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
            ),
        ), 
         array(
            'id' => 7,//id
            'name' => '敏感词通知',//
            'isopen' => 1,//是否开启
            'class_arr' => array('2'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '违反敏感词规则通知',
                    'display_content' => '{display_name}内容违反敏感词：{content}',
                    'format' =>array(
                        'display_name' => '员工名称',//员工名称
                        'content' => '敏感词内容',//敏感词内容
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('3'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
            ),
        ), 
         array(
            'id' => 8,//id
            'name' => '合作伙伴管理',//
            'isopen' => 1,//是否开启
            'class_arr' => array('2'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '开通组织具有合作伙伴权限',
                    'display_content' => '{org_name}的合作伙伴权限已被开通',
                    'format' =>array(
                        'org_name' => '部门名称',//部门名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '关闭组织合作伙伴权限',
                    'display_content' => '{org_name}的合作伙伴权限已被关闭',
                    'format' =>array(
                        'org_name' => '部门名称',//部门名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'name' => '与某企业关闭合作伙伴关系',
                    'display_content' => '{qiye_name}与我司的合作伙伴关系已经结束',
                    'format' =>array(
                        'qiye_name' => '企业名称',//企业名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 4,
                    'isopen' => 1,//是否开启
                    'name' => '与某企业建立合作伙伴关系',
                    'display_content' => '{qiye_name}与我司的合作伙伴关系已经开始',
                    'format' =>array(
                        'qiye_name' => '企业名称',//企业名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
            ),
        ), 
         array(
            'id' => 9,//id
            'name' => '试用',//
            'isopen' => 1,//是否开启
            'class_arr' => array('3'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '开始试用',
                    'display_content' => '您的全时工作圈已经开始试用',
                    'format' =>array(
                       // 'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('1'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '试用即将结束',
                    'display_content' => '您的全时工作圈试用即将到期，欢迎联系您的销售',
                    'format' =>array(
                       // 'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('1'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'name' => '试用到期',
                    'display_content' => '您的全时工作圈试用期已经结束',
                    'format' =>array(
                       // 'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('1'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),


            ),
        ), 
         array(
            'id' => 10,//id
            'name' => '正式用户',//
            'isopen' => 1,//是否开启
            'class_arr' => array('3'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '开始使用',
                    'display_content' => '您的全时工作圈已经开始启用',
                    'format' =>array(
                        //'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('1'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '使用到期',
                    'display_content' => '您的全时工作圈使用已经到期，请立即续约',
                    'format' =>array(
                       // 'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('1'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 3,
                    'isopen' => 1,//是否开启
                    'name' => '后台升级维护通知',
                    'display_content' => '全时工作圈即将升级通知信息',
                    'format' =>array(
                        //'aaaa' => 'aaa',//aaa
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('1'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),


            ),
        ), 
         array(
            'id' => 11,//id
            'name' => '营销活动',//
            'isopen' => 1,//是否开启
            'class_arr' => array('3'),//所属类型
            'item' => array(
                array(
                    'id' => 1,
                    'isopen' => 1,//是否开启
                    'name' => '研讨会信息',
                    'display_content' => '全时工作圈研讨会：{meet_name}信息',
                    'format' =>array(
                        'meet_name' => '研讨会名称',//研讨会名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('1','3','5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
                array(
                    'id' => 2,
                    'isopen' => 1,//是否开启
                    'name' => '功能简介信息',
                    'display_content' => '全时工作圈{action_name}简介',
                    'format' =>array(
                        'action_name' => '功能名称',//功能名称
                    ),
                    'from_admin_id' => array(), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员                    
                    'to_admin_id' => array('1','3','5'), //发送者1系统管理员2组织管理员3员工管理员4帐号管理员5生态管理员6渠道管理员
                ),
            ),
        ), 
  );
    /**
     *
     * @brief 构造函数
     * @details 
     * @param array $type_arr  1任务2消息3通知 
     * @return array 
     *
     */
    public function __construct($type_arr = array()) {
       // echo 'aaaa';
        //载入接口公用函数
        include_once APPPATH . 'helpers/my_httpcurl_helper.php'; 
         if(isemptyArray($type_arr)){//如果是空数组
             $type_arr = array('1','2','3');
         }
        $this->ok_notice_arr = $this -> set_notice_arr($type_arr);
    }
    /**
     *
     * @brief 根据类型，获得相关的数组
     * @details 
     * @param array $type_arr  1任务2消息3通知 
     * @return array 相关的数组
     *
     */
    public function set_notice_arr($type_arr = array()){
         if(isemptyArray($type_arr)){//如果是空数组
             $type_arr = array('1','2','3');
         } 
         $new_class_arr =array();
         $new_type_arr =array();
         //去掉没有打开的类型
         foreach($this->class_arr as $k => $v){
             $id = arr_unbound_value($v,'id',2,'');
             if(!bn_is_empty($id)){//有数据
                $isopen = arr_unbound_value($v,'isopen',2,'');
                if($isopen == 1){
                    if(deep_in_array($id, $type_arr)){//在里面
                        $new_class_arr[] = $v;
                        $new_type_arr[] = $id;
                    } 
                }
             }
         }
         $this->ok_class_arr = $new_class_arr;
         $ok_notice_arr = array() ;//当前需要的消息数组
         if(isemptyArray($new_class_arr)){//如果是空数组
             return $ok_notice_arr;
         } 
         foreach($this->notice_arr as $k => $v){             
             $isopen = arr_unbound_value($v,'isopen',2,'');
             if($isopen == 1){
                 $class_arr = arr_unbound_value($v,'class_arr',1,array()); 
                 $is_seled = 0;//是否是需要的
                 foreach($class_arr as $cls_k =>$cls_v){
                     if(deep_in_array($cls_v, $new_type_arr)){//在里面
                         $ok_notice_arr[$k] = $v;
                         
                     }
                 }
                 
             }
         }
         return $ok_notice_arr;
    }
    /**
     *
     * @brief 获得当前类型
     * @details 
     * @return array $notice_arr当前需要的消息数组
     *
     */
    public function get_notice_arr(){        
        return $this->ok_notice_arr;
    }
    /**
     *
     * @brief 获得类型数组
     * @details 
     * @return array $class_arr ;//类型数组
     *
     */
    public function get_class_arr(){        
        
        return $this->ok_class_arr;
    }
    /**
     *
     * @brief 替换操作后的数组信息
     * @details 
     * @param array $classid_arr  类型id数组[逐层] 
     * @param array $format_arr 格式数组[可以为空]
     * @param array $in_notice_arr  消息数组[可以为空]
     * @return array  
     *
     */
    public function replaced_arr($classid_arr = array(),$format_arr = array(),$in_notice_arr = array()){        
        $re_arr = array();
        if(isemptyArray($classid_arr)){//如果是空数组
            return $re_arr;
        }
        if(isemptyArray($in_notice_arr)){//如果是空数组
            $in_notice_arr = $this->ok_notice_arr;
        }
        if(isemptyArray($in_notice_arr)){//如果是空数组
            return $re_arr;
        }
        //根据id数据[逐层],获得当前的串
        $item_arr = $this->get_item_arr($classid_arr,$in_notice_arr);
        if(isemptyArray($item_arr)){//如果是空数组
            return $re_arr;
        }
        $display_content = arr_unbound_value($item_arr,'display_content',2,'');
        $isopen = arr_unbound_value($item_arr,'isopen',2,0);
        $big_isopen = arr_unbound_value($item_arr,'big_isopen',2,0);
        $ok_str = '';
        if($isopen == 1 && $big_isopen == 1){
            $ok_str = $display_content;
            foreach($format_arr as $k => $v){
                //替换
                $ok_str = str_replace('{' . $k . '}',$v,$ok_str);
            }
        }
        $item_arr['ok_content'] = $ok_str;
        $re_arr = $item_arr;
        return $re_arr;
        
    }
    /**
     *
     * @brief //根据id数据[逐层],获得当前的串
     * @details 
     * @param array $classid_arr  类型id数组[逐层] 
     * @param array $in_notice_arr  消息数组[可以为空]
     * @return array 数组
     *
     */
    public function get_item_arr($classid_arr = array(),$in_notice_arr = array()){        
        $re_arr = array();
        if(isemptyArray($classid_arr)){//如果是空数组
            return $re_arr;
        }
        if(isemptyArray($in_notice_arr)){//如果是空数组
            $in_notice_arr = $this->ok_notice_arr;
        }
        if(isemptyArray($in_notice_arr)){//如果是空数组
            return $re_arr;
        }
        $big_num = arr_unbound_value($classid_arr,0,2,'');
        $small_num = arr_unbound_value($classid_arr,1,2,'');
        foreach($in_notice_arr as $k => $v){
            if(!bn_is_empty($big_num)){
                $big_id = arr_unbound_value($v,'id',2,'');                        
                if($big_id == $big_num){
                    $item_arr = arr_unbound_value($v,'item',1,array());
                    foreach($item_arr as $i_k => $i_v){                       
                       if(!bn_is_empty($small_num)){
                           $sml_id = arr_unbound_value($i_v,'id',2,'');
                           if($small_num == $sml_id){
                               $big_name = arr_unbound_value($v,'name',2,'');
                               $big_isopen = arr_unbound_value($v,'isopen',2,'');
                               $bag_cls_arr = arr_unbound_value($v,'class_arr',1,array());
                               $re_arr = $i_v;
                               $re_arr['big_id'] = $big_id;
                               $re_arr['big_name'] = $big_name;
                               $re_arr['big_isopen'] = $big_isopen;
                               $re_arr['bag_cls_arr'] = $bag_cls_arr;
                               break;
                           }
                       }
                    }
                    break;
                }
            }                    
        }
        return $re_arr;
    }
}


