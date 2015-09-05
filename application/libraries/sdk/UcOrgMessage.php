<?php
/**
 * Third Part Plugins For Thrift
 * 
 * Catelog      :  Thrift Plugins
 * Create Date  :  2014-02-19
 * Author       :  zhanyi.sun <zhanyi.sun@quanshi.com>
 * 
 */
define('SDKPATH', realpath(dirname(__FILE__)));

$GEN_DIR = SDKPATH . '/gen-php';
$GLOBALS['THRIFT_ROOT'] = SDKPATH ;
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Thrift.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Base/TBase.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TBufferedTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TMemoryBuffer.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TPhpStream.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TFramedTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/TProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/TJSONProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/JSON/BaseContext.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/JSON/LookaheadReader.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/JSON/ListContext.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/JSON/PairContext.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TBufferedTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Type/TMessageType.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Factory/TStringFuncFactory.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/StringFunc/TStringFunc.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/StringFunc/Core.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Type/TType.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Exception/TException.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Exception/TTransportException.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Exception/TProtocolException.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Exception/TApplicationException.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TFramedTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift/Transport/TBufferedTransport.php';
require_once $GEN_DIR . '/uc/Types.php';
require_once $GEN_DIR . '/uc/ucService.php';

use Thrift\Transport\TTransport;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TMemoryBuffer;
use Thrift\Transport\TSocket;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TJSONProtocol;
use Thrift\Protocol\JSON\BaseContext;
use Thrift\Factory\TStringFuncFactory;
use Thrift\Factory;


class UcOrgMessage{
    //消息发送者id
    public $from_user_id;
    //消息发送者站点id
    public $from_site_id;
    //消息发送者资源id
    public $from_res_id = 0;
    //消息接受者id
    public $to_user_id;
    //消息接受者站点id
    public $to_site_id = 0;
    //消息接受者资源id
    public $to_res_id = 0;
    //消息id由客户端设置
    public $id = 0;
    //消息时间戳由客户端设置
    public $timestamp;
    //消息seq由服务器设置
    public $seq = 0;
    //回话id由服务器设置
    public $conversation = 0;
    //应用id
    public $app_id = 0;
    //thrift协议版本号
    public $version = 0x0100;
    //消息信息掩码
    public $mask = 0xff;
    
    public $CI;
    /**
     * 初始化消息消息参数
     * @param int $app_id           应用id 1 - 会议  2 - 日历 3 - 系统通知 4 - 组织管理 5 - 第三方应用消息   
     * @param int $from_user_id     消息发送者id
     * @param int $from_site_id     消息发送者站点id
     * @param int $to_user_id       消息接受者id
     * @param int $id               客户端设置的消息id
     * @param int $is_group         是否是给一个组发消息,1 - 是 0 - 否默认为0
     * @return void
     */
     function __construct() {
     	$this->CI = &get_instance();
     }
    /**
     * 组织消息发送
     * @param int $from_user_id     消息发送者用户id
     * @param int $from_site_id     消息发送者站点id
     * @param int $to_user_id       消息接受者id
     * @param int $to_site_id       消息接受者站点id
     * @param int $is_group         是否为讨论组聊天     1 - 是    0 - 否
     * @param int $msg_type         消息类型             1 - 组织变动
     * @param int $body             消息体               发送每类消息对应的消息内容按照消息协议定义的字段赋值
     * @param int $content_type     消息体类型           1 - mime  2 - 二进制
     * @param int $msg_id           消息号               1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职
     *                                                    6 - 员工权限变更    7 - 部门加入新员工  8 - 部门移动  9 - 员工被指定为生态企业员工
     *                                                    10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认
     *                                                    14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息  
     * @return array array(boolean, '')
     */
    function orgMsgSend($from_user_id, $from_site_id, $to_user_id, $to_site_id, $msg_type, $msg_id, $body, $is_group=1, $content_type=2)
    {
        //生成消息头
        $conf_header            = $this->createMsgHeader($from_user_id, $from_site_id, $to_user_id, $to_site_id, $msg_type, $is_group, $msg_id, $content_type);
        //生成消息体
        $conf_body              = $this->createMsgbody($msg_id, $body);
        //设置消息体的长度到消息头中
        //$conf_header->length    = $this->getBodyLength($conf_body);
        //$operate_id = isset($body['operator_id']) ? $body['operator_id'] : 0;
        //调用消息发送接口
        $results                = $this->sendMsg($conf_header, $conf_body);
        //返回结果给调用者
        return $results;
    }
    /**
     * 创建消息头
     * @param int $from_user_id     消息发送者用户id
     * @param int $from_site_id     消息发送者站点id
     * @param int $to_user_id       消息接受者id
     * @param int $to_site_id       消息接受者站点id
     * @param int $to_group_id      组织id
     * @param int $msg_type         消息类型    1 - 组织变动
     * @return object   \uc\UcMessageHead
     */
    function createMsgHeader($from_user_id, $from_site_id, $to_user_id, $to_site_id, $msg_type, $is_group, $msg_id, $content_type)
    {
        //创建消息头对象
        $msg_header                 = new \uc\UcMessageHead();
        $msg_header->appid          = $this->getAppId();
        $msg_header->pri            = $this->getPri($is_group, $content_type);
        $msg_header->protocolid     = $this->getProtocolId($msg_id);
        $msg_header->protocoltype   = $this->getProtocolType();
        $msg_header->id             = time();
        $msg_header->seq            = 0;
        $msg_header->conversation   = 0;
        $msg_header->timestamp      = time();
        $from                       = new \uc\JID();
        $to                         = new \uc\JID();
        $from->userID               = $from_user_id;
        $from->siteID               = $from_site_id;
        $from->resID                = 0;
        $to->userID                 = $to_user_id;
        $to->siteID                 = $to_site_id;
        $to->resID                  = 0;
        $msg_header->from           = $from;
        $msg_header->to             = $to;
        //返回消息头
        return $msg_header;
    }
    /**
     * 创建消息体
     * @param int   $msg_id           消息号          1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职
     *                                                 6 - 员工权限变更    7 - 部门加入新员工  8 - 部门移动  9 - 员工被指定为生态企业员工
     *                                                 10 - 部门删除 11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认
     *                                                 14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息  
     * @param array $body             消息体          发送每类消息对应的消息内容按照消息协议定义的字段赋值
     * @return object   \uc\UcMessageBody
     */
    function createMsgbody($msg_id, $body)
    {
        $msg_body       = new \uc\UcMessageBody();
        //初始化组织消息体
        switch (intval($msg_id)){
            case 1: //员工部门名称变更
                $dept_name_change                = new \uc\DeptUpdateContent();
                $dept_name_change->operator_id   = isset($body['operator_id'])        ? $body['operator_id'] : 0;
                $dept_name_change->dept_id       = isset($body['dept_id'])            ? $body['dept_id'] : 0;
                $dept_name_change->old_dept_name = isset($body['old_dept_name'])      ? $body['old_dept_name'] : '';
                $dept_name_change->new_dept_name = isset($body['new_dept_name'])      ? $body['new_dept_name'] : '';
                $dept_name_change->desc          = isset($body['desc'])               ? $body['desc'] : '';
                $msg_body->deptUpdate            = $dept_name_change;  
                break;
            case 2: //员工部门调动   
                $dept_transfer                   = new \uc\DeptTransferContent();
                $dept_transfer->operator_id      = isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $dept_transfer->dept_id          = isset($body['dept_id'])            ? $body['dept_id']              : 0;
                $dept_transfer->dept_name        = isset($body['dept_name'])          ? $body['dept_name']            : '';
                $dept_transfer->old_dept_name    = isset($body['old_dept_name'])      ? $body['old_dept_name']        : '';
                $dept_transfer->user_id          = isset($body['user_id'])            ? $body['user_id']              : 0;
                $dept_transfer->user_name        = isset($body['user_name'])          ? $body['user_name']            : '';
                $dept_transfer->desc             = isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->deptTransfer          = $dept_transfer;  
                break;
            case 3: //员工职位变更  
                $position_change                = new \uc\PositionUpdateContent();
                $position_change->operator_id   = isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $position_change->dept_name     = isset($body['dept_name'])          ? $body['dept_name']            : '';
                $position_change->old_position  = isset($body['old_position'])       ? $body['old_position']         : '';
                $position_change->new_position  = isset($body['new_position'])       ? $body['new_position']         : '';
                $position_change->user_id       = isset($body['user_id'])            ? $body['user_id']              : 0;
                $position_change->user_name     = isset($body['user_name'])          ? $body['user_name']            : '';
                $position_change->desc          = isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->positionUpdate       = $position_change;  
                break;
            case 4: //员工入职
                $employee_entry                 = new \uc\EmployeeEntryContent();
                $employee_entry->operator_id    = isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $employee_entry->dept_id        = isset($body['dept_id'])            ? $body['dept_id']              : 0;
                $employee_entry->dept_name      = isset($body['dept_name'])          ? $body['dept_name']            : '';
                $employee_entry->position       = isset($body['position'])           ? $body['position']             : '';
                $employee_entry->user_id        = isset($body['user_id'])            ? $body['user_id']              : 0;
                $employee_entry->user_name      = isset($body['user_name'])          ? $body['user_name']            : '';
                $employee_entry->desc           = isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->employeeEntry        = $employee_entry;  
                break;
            case 5: //员工离职
                $employee_leave                 =  new \uc\EmployeeLeaveContent();
                $employee_leave->operator_id    =  isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $employee_leave->dept_name      =  isset($body['dept_name'])          ? $body['dept_name']            : '';
                $employee_leave->user_id        =  isset($body['user_id'])            ? $body['user_id']              : 0;
                $employee_leave->user_name      =  isset($body['user_name'])          ? $body['user_name']            : '';
                $employee_leave->desc           =  isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->employeeLeave        =  $employee_leave;  
                break;
            case 6: //员工权限变更
                $employee_rights                =  new \uc\EmployeeRightsChangeContent();
                $employee_rights->operator_id   =  isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $employee_rights->dept_id       =  isset($body['dept_id'])            ? $body['dept_id']              : 0;
                $employee_rights->dept_name     =  isset($body['dept_name'])          ? $body['dept_name']            : '';
                $employee_rights->user_id       =  isset($body['user_id'])            ? $body['user_id']              : 0;
                $employee_rights->user_name     =  isset($body['user_name'])          ? $body['user_name']            : '';
                $employee_rights->desc          =  isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->employeeRights       =  $employee_rights;  
                break;
            case 7: //部门加入新员工(此类消息已经删除)
                break;
            case 8: //部门移动
                $dept_parent_change                 =  new \uc\DeptParentChangeContent();
                $dept_parent_change->operator_id    =  isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $dept_parent_change->dept_id        =  isset($body['dept_id'])            ? $body['dept_id']              : 0;
                $dept_parent_change->old_dept_name  =  isset($body['old_dept_name'])      ? $body['old_dept_name']        : '';
                $dept_parent_change->new_dept_name  =  isset($body['new_dept_name'])      ? $body['new_dept_name']        : '';
                $dept_parent_change->desc           =  isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->deprtParentChange        =  $dept_parent_change;  
                break;
            case 9: //员工被指定为生态企业员工
                $commpany_join                      =  new \uc\CompanyJoinContent();
                $commpany_join->operator_id         =  isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $commpany_join->company_id          =  isset($body['company_id'])         ? $body['company_id']           : 0;
                $commpany_join->company_name        =  isset($body['company_name'])       ? $body['company_name']         : '';
                $commpany_join->user_id             =  isset($body['user_id'])            ? $body['user_id']              : 0;
                $commpany_join->user_name           =  isset($body['user_name'])          ? $body['user_name']            : '';
                $commpany_join->desc                =  isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->deprtParentChange        =  $dept_parent_change;  
                break;
            case 10: //部门删除
                $dept_del                           =  new \uc\DeptDeleteContent();
                $dept_del->operator_id              =  isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $dept_del->dept_id                  =  isset($body['dept_id'])            ? $body['dept_id']              : 0;
                $dept_del->dept_name                =  isset($body['dept_name'])          ? $body['dept_name']            : '';
                $dept_del->parent_dept_name         =  isset($body['parent_dept_name'])   ? $body['parent_dept_name']     : '';
                $dept_del->desc                     =  isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->deptDelete               =  $dept_del;  
                break;
            case 11: //员工入职确认
            case 14: //员工入职拒绝
                $entry_confirm                      =  new \uc\EmployeeEntryConfirmContent();
                $entry_confirm->operator_id         =  isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $entry_confirm->dept_id             =  isset($body['dept_id'])            ? $body['dept_id']              : 0;
                $entry_confirm->dept_name           =  isset($body['dept_name'])          ? $body['dept_name']            : '';
                $entry_confirm->user_name           =  isset($body['user_name'])          ? $body['user_name']            : '';
                $entry_confirm->user_id             =  isset($body['user_id'])            ? $body['user_id']              : 0;
                $entry_confirm->desc                =  isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->entryConfirm             =  $entry_confirm;  
                break;
            case 12: //员工离职确认
            case 15: //员工离职拒绝
                $leave_confirm                      =  new \uc\EmployeeLeaveConfirmContent();
                $leave_confirm->operator_id         =  isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $leave_confirm->dept_id             =  isset($body['dept_id'])            ? $body['dept_id']              : 0;
                $leave_confirm->dept_name           =  isset($body['dept_name'])          ? $body['dept_name']            : '';
                $leave_confirm->user_id             =  isset($body['user_id'])            ? $body['user_id']              : 0;
                $leave_confirm->user_name           =  isset($body['user_name'])          ? $body['user_name']            : '';
                $leave_confirm->desc                =  isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->leaveConfirm             =  $leave_confirm;  
                break;
            case 13: //员工部门调动确认
            case 16: //员工部门调动拒绝
                $dept_transfer_confirm              =  new \uc\DeptTransferConfirmContent();
                $dept_transfer_confirm->operator_id =  isset($body['operator_id'])        ? $body['operator_id']          : 0;
                $dept_transfer_confirm->dept_id     =  isset($body['dept_id'])            ? $body['dept_id']              : 0;
                $dept_transfer_confirm->dept_name   =  isset($body['dept_name'])          ? $body['dept_name']            : '';
                $dept_transfer_confirm->user_id     =  isset($body['user_id'])            ? $body['user_id']              : 0;
                $dept_transfer_confirm->user_name   =  isset($body['user_name'])          ? $body['user_name']            : '';
                $dept_transfer_confirm->desc        =  isset($body['desc'])               ? $body['desc']                 : '';
                $msg_body->deptConfirm              =  $dept_transfer_confirm;  
                break;
        }
        //返回结果
        return $msg_body;
    }
    /**
     * 获取消息体长度
     * @param object $msg_body  消息体内容
     * @return int   $length    消息体长度
     */
    function getBodyLength($msg_body)
    {
        $body_buffer            = new TMemoryBuffer();
        $body_buffer_protocol   = new TBinaryProtocol($body_buffer);
        $msg_body->write($body_buffer_protocol);
        return $body_buffer->available();
    }
    /**
     * 获取应用id
     * @return int              
     */
    function getAppId()
    {
        return \uc\AppId::AppOrganization;
    }
    /**
     * 获取消息号
     * @param int $msg_id        消息号          1 - 部门名称变更    2 - 员工部门调动    3 - 职位调整  4 -员工入职 5 - 员工离职
     *                                            6 - 员工权限变更    7 - 部门加入新员工  8 - 部门移动  9 - 员工被指定为生态企业员工
     *                                            10 - 部门删除  11 - 员工入职确认 12 - 员工离职确认 13 - 员工部门调动确认
     *                                            14 - 员工入职拒绝消息 15 - 员工离职拒绝消息 16 - 员工部门调动拒绝消息                       
     * @return int  
     */
    function getProtocolId($msg_id)
    {
        $protocol_id = 0;
        //处理会议消息号
        switch (intval($msg_id)){
            case 1:
                $protocol_id = \uc\OrganizeProtoMessageId::DeptUpdate;
                break;
            case 2:
                $protocol_id = \uc\OrganizeProtoMessageId::DeptTransfer;
                break;
            case 3:
                $protocol_id = \uc\OrganizeProtoMessageId::PositionUpdate;
                break;
            case 4:
                $protocol_id = \uc\OrganizeProtoMessageId::EmployeeEntry;
                break;
            case 5:
                $protocol_id = \uc\OrganizeProtoMessageId::EmployeeLeave;
                break;
            case 6:
                $protocol_id = \uc\OrganizeProtoMessageId::EmployeeRightsUpdate;
                break;
            case 7:
                $protocol_id = \uc\OrganizeProtoMessageId::EmployeeJoin;
                break;
            case 8:
                $protocol_id = \uc\OrganizeProtoMessageId::DeptParentChange;
                break;
            case 9:
                $protocol_id = \uc\OrganizeProtoMessageId::CompanyJoin;
                break;
            case 10:
                $protocol_id = \uc\OrganizeProtoMessageId::DeptDelete;
                break;
            case 10:
                $protocol_id = \uc\OrganizeProtoMessageId::DeptDelete;
                break;
            case 11:
                $protocol_id = \uc\OrganizeProtoMessageId::EmployeeEntryConfirm;
                break;
            case 12:
                $protocol_id = \uc\OrganizeProtoMessageId::EmployeeLeaveConfirm;
                break;
            case 13:
                $protocol_id = \uc\OrganizeProtoMessageId::DeptTransferConfirm;
                break;
            case 14:
                $protocol_id = \uc\OrganizeProtoMessageId::EmployeeEntryReject;
                break;
            case 15:
                $protocol_id = \uc\OrganizeProtoMessageId::EmployeeLeaveReject;
                break;
            case 16:
                $protocol_id = \uc\OrganizeProtoMessageId::DeptTransferReject;
                break;
        }
        //返回结果
        return $protocol_id;
    }
    /**
     * 获取消息类型
     * @return int  
     */
    function getProtocolType()
    {
        //返回结果
        return \uc\OrganizeProtoMessageType::OrganizeType;
        
    }
    function getPri($is_group, $content_type)
    {
        //设置消息属性
        $this->mask << 4;
        $pri = ($this->mask & $is_group << 4) | $content_type;
        return $pri;
    }
    /**
     * 
     * @param type $uc_msg
     * @param type $app_type
     * @param int       $msg_type           消息类型   1 - 单人即时会议邀请消息 2 - 预约会议邀请消息  3 - 预约会议更新消息  
     *                                                 4 - 预约会议取消消息    5 - 预约会议转发消息  6 - 多人即时会议邀请消息  
     * @param type $conf_id
     * @param type $group_id
     * @param type $icalenldar
     * @param type $host_id
     * @param type $conf_pwd
     * @param type $share_user_id
     * @param type $is_cycle_conf
     */
    function setMsgBody($uc_msg, $app_type, $msg_type, $conf_id, $group_id, $icalenldar, $host_id, $conf_pwd, $share_user_id, $forward_user_id, $start_time, $is_cycle_conf=0)
    {
        if ($app_type == 1){
            switch (inval($msg_type)){
                case 1: //预约会议邀请消息内容
                    break;
                case 2: //预约会议接受消息内容
                case 3: //预约会议更新消息内容
                    $conf_invite_content = new \uc\IQInviteContent();
                    $conf_invite_content->confId = $conf_id;
                    $conf_invite_content->shareUserID = $share_user_id;
                    $conf_invite_content->hostId = $host_id;
                    $conf_invite_content->groupID = $group_id;
                    $conf_invite_content->icalendar = $icalenldar;
                    $conf_invite_content->isRecurrent = $is_cycle_conf ? 1 : 0;
                    $conf_invite_content->startTime   = $is_cycle_conf ? 1 : 0;
                    $uc_msg->confInvite = $conf_invite_content;
                    break;
                case 4: //预约会议取消消息内容
                    $this->protocoltype   = \uc\ConfMessageType::ConfCancelMsg;
                    break;
                case 5: //预约会议转发消息内容
                    $this->protocoltype   = \uc\ConfMessageType::ConfForwardMsg;
                    break;
                    break;
            }
        }
    }
    /**
     * 设置消息id
     * @param int       $msg_type           消息类型   1 - 单人即时会议邀请消息 2 - 预约会议邀请消息  3 - 预约会议更新消息  
     *                                                  4 - 预约会议取消消息    5 - 预约会议转发消息  6 - 多人即时会议邀请消息  
     * @param int       $app_type     应用类型   1 - 预约会议   2 - 即时会议 
     * @return void
     */
    function setMsgType($msg_type, $app_type)
    {
        //会议消息类型设置
        if ($app_type == 1){
            switch (inval($msg_type)){
                case 1: //单人即时会议邀请消息
                    $this->protocoltype   = \uc\ConfMessageType::NormalInstantConfInviteMsg;
                    break;
                case 2: //预约会议邀请消息
                    $this->protocoltype   = \uc\ConfMessageType::ConfInviteMsg;
                    break;
                case 3: //预约会议更新消息
                    $this->protocoltype   = \uc\ConfMessageType::ConfUpdateMsg;
                    break;
                case 4: //预约会议取消消息
                    $this->protocoltype   = \uc\ConfMessageType::ConfCancelMsg;
                    break;
                case 5: //预约会议转发消息
                    $this->protocoltype   = \uc\ConfMessageType::ConfForwardMsg;
                    break;
                case 6: //多人即时会议邀请消息
                    $this->protocoltype   = \uc\ConfMessageType::DiscussionInstantConfInviteMsg;
                    break;
            }
        }
    }
    /**
     * 设置消息id
     * @param int $msg_id       消息id  1 - 邀请 2 - 接受 3 - 拒绝 4 - 取消单场 5 - 取消周期
     * @param int $app_type     应用类型   1 - 预约会议   2 - 即时会议 
     * @return void
     */
    function setMsgId($msg_id, $app_type)
    {
        //会议消息id设置
        if ($app_type == 1){
            switch (inval($msg_id)){
                case 1: //预约邀请消息id设置
                    $this->protocolid   = \uc\ConfInviteMessageId::IQInvite;
                    break;
                case 2: //预约接受消息id设置
                    $this->protocolid   = \uc\ConfInviteMessageId::IQReceive;
                    break;
                case 3: //预约拒绝消息id设置
                    $this->protocolid   = \uc\ConfInviteMessageId::IQReject;
                    break;
                case 4: //预约取消单场消息id设置
                    $this->protocolid   = \uc\ConfCancelMessageId::DefaultId;
                    break;
                case 5: //预约取消取消周期消息id设置
                    $this->protocolid   = \uc\ConfCancelMessageId::RecurrentConfCancel;
                    break;
            }
        }elseif ($app_type == 2) { 
            switch (inval($msg_id)){
                case 1: //即时会议邀请消息id设置
                    $this->protocolid   = \uc\InstantConfInviteMessageId::IQInvite;
                    break;
                case 2: //即时会议接受消息id设置
                    $this->protocolid   = \uc\InstantConfInviteMessageId::IQReceive;
                    break;
                case 3: //即时会议拒绝消息id设置
                    $this->protocolid   = \uc\InstantConfInviteMessageId::IQReject;
                    break;
            }
        }
    }

    /**
     * 设置应用id
     * @param int $app_id
     * @return hex
     */
    function setAppId($app_id)
    {
        switch (intval($app_id)){
            case 1:
                $this->app_id = \uc\AppId::AppMeeting;
                break;
            case 2:
                $this->app_id = \uc\AppId::AppCalendar;
                break;
            case 3:
                $this->app_id = \uc\AppId::AppNotify;
                break;
            case 4:
                $this->app_id = \uc\AppId::AppOrganization;
                break;
            case 5:
                //pass
            default :
                return $this->app_id;
        }
        //return 
        return $this->app_id;
    }
    /**
     * 执行消息发送操作
     * @param uc\UcMessageHead  $msg_header     消息头
     * @param \uc\UcMessageBody $msg_body       消息体
     * @retun array        $results     array(boolean, $results)
     */
//     function sendMsg(uc\UcMessageHead $msg_header, \uc\UcMessageBody $msg_body)
//     {
//         //echo "send conf header is ->".var_export($msg_header, true)."send body is ->".var_export($msg_body, true);
//         try {
//             //初始化webservice连接
//             $socket     = new TSocket(UC_ORG_SEDND_IP, 9080);//new TSocket('192.168.35.155', 9080);
//             $socket->setSendTimeout(UC_ORG_SEDND_TIMEOUT);//setSendTimeout(15);
//             $transport  = new \Thrift\Transport\TFramedTransport($socket, 1024, 1024);
//             $protocol   = new TBinaryProtocol($transport);
//             $client     = new \uc\ucServiceClient($protocol);
//             $transport->open(); 
//             //调用webservice执行消息发送操作
//             $resutls    = $client->SendUcMessage($msg_header, $msg_body);
            
//             if (intval($resutls) == 0){
//                 return array(true, "conf msg send sucess.");
//             }else{
//                 return array(false, "conf msg send faild.");
//             }
//             return array(true, $resutls);
//         }catch (Exception $e){
//             return array(false, $e->getMessage());
//         }
//     }

    
// ----------------------------------------new method--------------------------------------------------------------------------------    
    /**
     * 发送消息
     * @param uc\UcMessageHead $msg_header
     * @param \uc\UcMessageBody $msg_body
     * @param int $operate_id 操作人Id
     * @return boolean
     */
    function sendMsg(uc\UcMessageHead $msg_header, \uc\UcMessageBody $msg_body){
    	log_message('info', 'message');
    	//序列化消息体
    	$body_buffer 		= new TMemoryBuffer();
    	$prsence_protocol 	= new TBinaryProtocol($body_buffer);
    	$msg_body->write($prsence_protocol);
    	$msg_header->length = $body_buffer->available();
    	
    	//序列化消息头
    	$head_buffer		= new TMemoryBuffer();
    	$header_protocol 	= new TBinaryProtocol($head_buffer);
    	$msg_header->write($header_protocol);
    	
    	//合并消息头和消息体
    	$message_buffer     = new TMemoryBuffer($head_buffer->getBuffer().$body_buffer->getBuffer());
    	$msg_list[] 		= $message_buffer->getBuffer();
    	
    	//二进制打包
    	$message 			= pack('la*', $message_buffer->available(), $msg_list[0]);
    	
    	//压缩
    	$gzip_message 		= gzcompress($message);
    	
    	//发送消息
    	$address 			= UCC_API.'message/send';
    	$data 				= array('session_id'=> $this->CI->p_session_id,'user_id'=> $this->CI->p_user_id,'data'=>$gzip_message);
    	$result_json		= $this->make_post($data,$address);
    	
    	log_message('info', 'The result of sending msg is ' . $result_json);
    	
    	$result = json_decode($result_json, true);
    	
    	if($result['code'] == 0){
    		return array(true, 'success');
    	}else{
    		return array(false, $result['msg']);
    	}
    }
    
    /**
     * 发送消息
     * @param array $data
     * @param string $adress
     * @return mixed
     */
    public function make_post($data,$adress){
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $adress);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_TIMEOUT,3);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	$response = curl_exec($ch);
    	if(curl_errno($ch)){
    		print curl_error($ch);
    	}
    	curl_close($ch);
    	return $response;
    }
}

