<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Power_class {
  private $power_type ;//1站点属性,2部门属性,3用户属性,4会议属性
  private $power_class_order ;//当前类型分类排序串
  private $power_order ;//当前权限排序串
  private $power_ok_class_arr ;// 当前类型权限数组  
  private $power_ok_arr ;// 当前类型下的权限数组
  
  private $power_order_arr = array(//排序数组
      'class_order' => array(//类型排序数组,没在值里的，会排在后面
          '1' => '1,2,3,4',//键为权限用在的类型,1站点属性,2部门属性,3用户属性,4会议属性 ,值为类型id,多个,号分隔，前面的在前
          '2' => '1,2,3,4',
          '3' => '1,2,3,4',
          '4' => '1,2,3,4',
      ),
      'power_order' => array(//同class_order
          '1' => '',//键为权限用在的类型,1站点属性,2部门属性,3用户属性,4会议属性 ,值为boss_name_boss_property,多个,号分隔，前面的在前
          '2' => '',
          '3' => '',
          '4' => '',
          )
  );
  
  private $power_class_arr = array(
      array(
          'id' => 1,//类型编号
          'name' => 'IM设置',//类型名称
          'enable' => 1,//是否可用0不可用1可用
          'whow_type' => array(
                '1' => 5,//类型编号,键为页面类型,值为显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
                '2' => 5,
                '3' => 5,
                '4' => 5,
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性 
          
      ),
      array(
          'id' => 2,//类型编号
          'name' => '通话设置',//类型名称
          'enable' => 1,//是否可用0不可用1可用
          'whow_type' => array(
                '1' => 5,//类型编号,键为页面类型,值为显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
                '2' => 5,
                '3' => 5,
                '4' => 5,
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性          
      ),
      array(
          'id' => 3,//类型编号
          'name' => '电话会议设置',//类型名称
          'enable' => 1,//是否可用0不可用1可用
          'whow_type' => array(
                '1' => 5,//类型编号,键为页面类型,值为显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
                '2' => 5,
                '3' => 5,
                '4' => 5,
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性          
      ),
      array(
          'id' => 4,//类型编号
          'name' => '网络会议配置',//类型名称
          'enable' => 1,//是否可用0不可用1可用
          'whow_type' => array(
                '1' => 5,//类型编号,键为页面类型,值为显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
                '2' => 5,
                '3' => 5,
                '4' => 5,
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性          
      ),
  );  
  private $power_arr = array(
      
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '可使用全时云企 IM 互传文档',//说明 passDoc=>1：不允许2：允许  UC_passDoc
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => 'passDoc',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '不允许',
                '2' => '允许'
            ),//值串
            'default_value' => 1,//默认值
            'regex' => '/^[12]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
                
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '1',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ), 
         array(
            'enable' => 0,//是否可用0不可用1可用
            'name' => '自动将联系过的联系人添加到常用联系人列表',//说明
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => '?',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => 'aaa',
                '1' => 'aa',
                '2' => 'aa'
            ),//值串
            'default_value' => 1,//默认值
            'regex' => '/^[012]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '1',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ),   
         array(
            'enable' => 0,//是否可用0不可用1可用
            'name' => '自动将联系过的讨论组添加到讨论组列表',//说明
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => '?',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => 'aaa',
                '1' => 'aaa',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '/^[012]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '1',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ),   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许用户设置接听策略',//说明 1：是2：否  UC_answerStrategy
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => 'answerStrategy',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '是',
                '2' => '否',
            ),//值串
            'default_value' => 2,//默认值
            'regex' => '/^[12]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '2',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '用户可设定接听策略到海外直线电话',//说明 1：是2：否  UC_answerStrategyOverseas
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => 'answerStrategyOverseas',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '是',
                '2' => '否',
            ),//值串
            'default_value' => 2,//默认值
            'regex' => '/^[12]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '2',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许使用云企拨打电话',//说明 1是2：否  UC_isCall
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => 'isCall',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '是',
                '2' => '否',
            ),//值串
            'default_value' => 2,//默认值
            'regex' => '/^[12]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '2',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许拨打海外电话',//说明 1：是2：否 UC_allowcallOverseas
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => 'allowcallOverseas',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '是',
                '2' => '否',
            ),//值串
            'default_value' => 2,//默认值
            'regex' => '/^[12]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '2',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 0,//是否可用0不可用1可用
            'name' => '允许召开电话会议',//说明 0不允许；1允许默认值0
            'boss_name' => 'aaa',//boss权限所属名称
            'boss_property' => 'aaa',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '不允许',
                '1' => '允许'
            ),//值串
            'default_value' => 2,//默认值
            'regex' => '/^[012]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '电话会议自动报名',//说明这个参数应该是录姓名，ParticipantNameRecordAndPlayback=>>0不录制，1录制。默认值0 summit_ParticipantNameRecordAndPlayback
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'ParticipantNameRecordAndPlayback',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '不录制',
                '1' => '录制',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '/^[01]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,
         array(
            'enable' => 0,//是否可用0不可用1可用
            'name' => '外呼屏蔽 *1 功能',//说明 外呼屏蔽 *1 功能 =>无配置项，需要在外呼时写入。
            'boss_name' => 'aaa',//boss权限所属名称
            'boss_property' => 'aaa',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '是',
                '2' => '否',
            ),//值串
            'default_value' => 2,//默认值
            'regex' => '/^[12]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '参会人加入会议，告知参会者人数',//说明参会人进入会议时，是否播报会议方数：0不播报；1播报。默认值0  summit_ValidationCount
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'ValidationCount',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '不播报',
                '1' => '播报',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '/^[01]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '第一个入会是否需要听到您是第一个到会者讯息',//说明是否向第一个加入会议的人播放是第一个参会人的提示音：0不播放；1播放。默认值0 summit_FirstCall
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'FirstCallerMsg',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '不播放',
                '1' => '播放',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '/^[01]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许使用95057接入号',//说明 
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'ConfDnisAccess',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '允许使用95057 接入号',//允许设置95057 接入号
                '2,3' => '允许使用400/800 国内接入号',//有400、和800  summit_ConfDnisAccess   2 400/  3  800
                '4,5,7' => '允许使用海外接入号',//包含香港本地接入/国际TollFree/国际Caller Pay  summit_ConfDnisAccess  7：香港本地接入 5：国际Toll free  4：国际Caller pay
            ),//值串
            'default_value' => '0',//默认值
            'regex' => '/^([\d]{1,2}|([\d]{1,2}\,)+[\d]{1,2})(\,)?$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 6,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ),   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许使用400/800 国内接入号',//说明
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'ConfDnisAccess',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '2,3' => '是',
                '' => '否',
            ),//值串
            'default_value' => '',//默认值
            'regex' => '/^([\d]{1,2}|([\d]{1,2}\,)+[\d]{1,2})(\,)?$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许使用海外接入号',//说明 
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'ConfDnisAccess',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '4,5,7' => '是',
                '' => '否',
            ),//值串
            'default_value' => '',//默认值
            'regex' => '/^([\d]{1,2}|([\d]{1,2}\,)+[\d]{1,2})(\,)?$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '主持人未入会，只要会议有人入会，会议就开始',//说明 ConfQuickStart=>>0 不开启，所有参与人听音乐；1 开启。默认值1  summit_ConfQuickStart
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'ConfQuickStart',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '不开启',
                '1' => '开启',
            ),//值串
            'default_value' => 1,//默认值
            'regex' => '/^[01]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '主持人退会，会议是否结束',//说明主持人退会，会议是否停止 =>stopwhenoneuser=>>0不自动终止，1自动终止。默认值0  tang_stopwhenoneuser
            'boss_name' => 'tang',//boss权限所属名称
            'boss_property' => 'stopwhenoneuser',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => ' 否，会议继续进行',
                '1' => '是，立即结束',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '/^[01]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 7,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ),
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '参会人加入会议语音提示',//说明 参与人进入会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1 summit_Pcode1InTone
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'Pcode1InTone',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '无提示音',
                '1' => '蜂音',
                '2' => '语音报名',
            ),//值串
            'default_value' => 1,//默认值
            'regex' => '/^[012]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 2,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '参会人退出会议语音提示',//说明 参与人退出会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1 summit_Pcode1OutTone
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'Pcode1OutTone',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '无提示音',
                '1' => '蜂音',
                '2' => '语音报名',
            ),//值串
            'default_value' => 1,//默认值
            'regex' => '/^[012]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 2,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ), 
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '主持人加入会议语音提示',//说明 主持人进入会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1 summit_Pcode2InTone
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'Pcode2InTone',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '无提示音',
                '1' => '蜂音',
                '2' => '语音报名',
            ),//值串
            'default_value' => 1,//默认值
            'regex' => '/^[012]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 2,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) , 
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '主持人退出会议语音提示',//说明 主持人退出会议的提示音：0 无提示音；1 提示蜂音；2语音报名。默认值1 summit_Pcode2OutTone
            'boss_name' => 'summit',//boss权限所属名称
            'boss_property' => 'Pcode2OutTone',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '无提示音',
                '1' => '蜂音',
                '2' => '语音报名',
            ),//值串
            'default_value' => 1,//默认值
            'regex' => '/^[012]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 2,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '3',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,       
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许召开网络会议',//说明 是否可以使用VOIP  UC_enableVoip
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => 'enableVoip',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '否',
                '1' => '是',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '/^[01]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,
         array(
            'enable' => 0,//是否可用0不可用1可用
            'name' => '允许使用硬件视频',//说明 
            'boss_name' => 'aaa',//boss权限所属名称
            'boss_property' => 'aaa',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '是',
                '2' => '否',
            ),//值串
            'default_value' => 2,//默认值
            'regex' => '/^[12]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '会议允许最大方数{value}方(只限数字，最大400方，最小2方)',//说明 会议最大方数，0-5760的数字  tang_confscale
            'boss_name' => 'tang',//boss权限所属名称
            'boss_property' => 'confscale',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '是',
                '2' => '否',
            ),//值串
            'default_value' => 2,//默认值
            'regex' => "/^([2-9])|([1-9]\d{1})|([1-3]\d{2})|(400)$/",//最小为2，最大为400
            'whow_type' => 8,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选8复选[名称]+值单选+输入框
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,
         array(
            'enable' => 0,//是否可用0不可用1可用
            'name' => '允许用户邀请站点外用户加入会议',//说明 
            'boss_name' => 'aaa',//boss权限所属名称
            'boss_property' => 'aaa',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '1' => '是',
                '2' => '否',
            ),//值串
            'default_value' => 2,//默认值
            'regex' => '/^[12]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ) ,   
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '会议结束显示会后营销页面',//说明 参会人开完会后是否弹出调研页(0:否 1:是)  UC_attendeeSurvey
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => 'attendeeSurvey',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '否',
                '1' => '是',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '/^[01]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ),
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许参会人共享文档',
            'boss_name' => '',//boss权限所属名称
            'boss_property' => '',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '',
                '1' => '',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '',
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ),
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许参会人批注',
            'boss_name' => '',//boss权限所属名称
            'boss_property' => '',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '',
                '1' => '',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '',
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ),
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许参会人保存共享数据',
            'boss_name' => '',//boss权限所属名称
            'boss_property' => '',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '',
                '1' => '',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '',
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ),
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '允许参会人切换共享文档/白板',//说明 参会人开完会后是否弹出调研页(0:否 1:是)  UC_attendeeSurvey
            'boss_name' => '',//boss权限所属名称
            'boss_property' => '',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '',
                '1' => '',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '',
            'whow_type' => 4,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         ),
         array(
            'enable' => 1,//是否可用0不可用1可用
            'name' => '用户使用 PC 客户端开会，默认语音接入方式',//说明是否允许用户使用语音(0:电话和VOIP 1:电话 2:VOIP)  UC_allowUserVoice
            'boss_name' => 'UC',//boss权限所属名称
            'boss_property' => 'allowUserVoice',//权限在BOSS中的属性名称[注意大小写]
            'value_arr' => array(
                '0' => '用户自选',
                '1' => '电话',
                '2' => 'VOIP',
            ),//值串
            'default_value' => 0,//默认值
            'regex' => '/^[012]+$/',//50个中英文数字//^[\x80-\xffA-Za-z\d]{1,50}$/
            'whow_type' => 7,//显示类型0输入框1下接框2单选框3复选框[多个]4复选框[单个]5直接显示文本6用value_arr作为复选框列,不选为空7[名称]值单选
            'type' => array(
                '1' => '',//类型编号,值为当前编号下的名称;如果为空则用boss_property
                '2' => '',
                '3' => '',
                '4' => '',
            ),//权限用在的类型,多个用,号分隔,1站点属性,2部门属性,3用户属性,4会议属性
            'class' => '4',//权限分类，多个用,号分隔
            'value' => '',//值,多个用,号分隔
         )
    );
  
    public function __construct($power_type = 1){
        //载入接口公用函数
        include_once APPPATH . 'helpers/my_httpcurl_helper.php'; 
        $this->power_type = $power_type ;
        $this->power_init();//根据权限类型，获得权限分类及权限的排序串
    }
    /**
     *
     * @brief 获得权限的信息
     * @details 
     * @return array 返回标签数组 注意原来的数组 'type' 下标,由数组转换为真实的属性txt
     *
     */
    public function get_power(){
        $re_array = array(
            'power_class' =>$this->power_ok_class_arr,
            'power_arr' => $this->power_ok_arr
        );
        return $re_array;
    }
    /**
     *
     * @brief 根据权限类型，获得权限分类及权限的排序串
     * @details 
     * @return array 返回标签数组 注意原来的数组 'type' 下标,由数组转换为真实的属性txt
     *
     */
    public function power_init(){
        $class_order_arr = arr_unbound_value($this->power_order_arr,'class_order',1,array());
        $power_order_arr = arr_unbound_value($this->power_order_arr,'power_order',1,array());
        $this->power_class_order = arr_unbound_value($class_order_arr,$this->power_type,2,'');//当前类型分类排序串
        $this->power_order = arr_unbound_value($power_order_arr,$this->power_type,2,'');//当前权限排序串
        //按顺序组织分类
        $this->power_ok_class_arr = $this->get_power_class_arr();
        //按顺序组织类型
        $this->power_ok_arr = $this->get_power_arr();
    }
    /**
     *
     * @brief 获得当前类型的权限数组[按顺序]
     * @details 
     * @return array 返回标签数组 注意原来的数组 'whow_type' 下标,由数组转换为真实的属性txt
     *
     */
    public function get_power_class_arr(){
       $re_arr = array();        
       $pre_arr = array();//在顺序串里的数组
       $back_arr = array();//没在顺序串里的数组
       $order_arr = explode(',', $this->power_class_order);
       //把顺序值改为键，值改为空
       foreach ($order_arr as $k => $v){
           $pre_arr[$v] = '';
       }
       foreach ($this->power_class_arr as $k => $v){
           if(!isemptyArray($v)){//不为空
              $is_enable = arr_unbound_value($v,'enable',2,0);
              if($is_enable == 1){//可使用
                $type_arr = arr_unbound_value($v,'whow_type',1,array());             
                if(is_array($type_arr)){//是数组
                $class_id = arr_unbound_value($v,'id',2,'');
                $new_suffix = $class_id ;
                foreach($type_arr as $t_k => $t_v){                     
                   if($t_k == $this->power_type){
                        $ns_power_type = $t_v;
                        $v['whow_type'] = $ns_power_type;
                        //下标是否在下标数组里                          
                        if(deep_in_array($new_suffix, $order_arr)){//在里面
                          $pre_arr[$new_suffix] = $v;
                        }else{//不在里面
                          $back_arr[$new_suffix] = $v;
                        }
                        break;
                   } 
                }
              }
              }
           }
       }       
       //去掉空值
       foreach($pre_arr as $k => $v){
           if(isemptyArray($v)){//空，则去除
               unset($pre_arr[$k]);
           }
       }
       $re_arr = array_merge($pre_arr,$back_arr);
       return $re_arr;
    }
    /**
     *
     * @brief 获得当前类型的权限数组[按顺序]
     * @details 
     * @return array 返回标签数组 注意原来的数组 'type' 下标,由数组转换为真实的属性txt
     *
     */
    public function get_power_arr(){
       $re_arr = array(); 
       $pre_arr = array();//在顺序串里的数组
       $back_arr = array();//没在顺序串里的数组
       $order_arr = explode(',', $this->power_order);
       //把顺序值改为键，值改为空
       foreach ($order_arr as $k => $v){
           $pre_arr[$v] = '';
       }
       foreach ($this->power_arr as $k => $v){
           if(!isemptyArray($v)){//不为空
              $is_enable = arr_unbound_value($v,'enable',2,0);
              if($is_enable == 1){//可使用
                $type_arr = arr_unbound_value($v,'type',1,array());             
                if(is_array($type_arr)){//是数组                  
                  $boss_name = arr_unbound_value($v,'boss_name',2,'');
                  $boss_property = arr_unbound_value($v,'boss_property',2,'');
                  $new_suffix = $boss_name . '_' . $boss_property;
                  foreach($type_arr as $t_k => $t_v){                     
                     if($t_k == $this->power_type){
                          $ns_power_type = $t_v;
                          if(bn_is_empty($ns_power_type)){//为空
                              $ns_power_type = $boss_property;
                          }
                          $v['type'] = $ns_power_type;
                          //下标是否在下标数组里                          
                          if(deep_in_array($new_suffix, $order_arr)){//在里面
                            $pre_arr[$new_suffix] = $v;
                          }else{//不在里面
                            $back_arr[$new_suffix] = $v;
                          }
                          break;
                     } 
                  }
              }
              }
           }
       }
       //去掉空值
       foreach($pre_arr as $k => $v){
           if(isemptyArray($v)){//空，则去除
               unset($pre_arr[$k]);
           }
       }
       $re_arr = array_merge($pre_arr,$back_arr);
       return $re_arr;
    } 
}


