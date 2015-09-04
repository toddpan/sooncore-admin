<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/chartopinyin.php');
include_once APPPATH . 'libraries/sdk/UcOrgMessage.php';
class ClearGroup{
    public $CI;
    
    public function __construct(){
        $this->CI = & get_instance();
        //load model
        $this->CI->load->model('group_model');
        $this->CI->load->library('UmsLib', '', 'ums');
        $this->CI->msglib   =  new UcOrgMessage();
    }
    /**
     * Method For Clear Group Member
     * @param int|array $user_ids
     * @param int       $site_id
     * @return boolean
     */
    function clearGroupMember($user_ids, $site_id)
    {
        //get group data from db
        list($flag, $project_group_data, $chat_group_data) = $this->CI->group_model->getGroups($user_ids, $site_id);
        if (!$flag){
            log_message('error', "get group by reuqest user ids ->".var_export($user_ids,true)." error.");
            return false;
        }
        //update chat group group logo, group name and group pinyin
        if (!empty($chat_group_data)){
            $group_change_info = $this->deleteChatGrupMember($chat_group_data);
        }
        //send msg to client nofity the users is quit
        $send_data  = $this->CI->msglib->createGrpMsg($project_group_data, $chat_group_data, $group_change_info);
        $flag = false;
        if (!empty($send_data)){
            list($flag, $results) = $this->CI->msglib->sendGrpMsg($send_data);
        }
        //clear project group and chat group data from db
        if(!empty($user_ids) && $flag){
            $this->CI->group_model->deleteGroupMember($user_ids);
        }
        //return 
        return true;
    }
    
    function deleteChatGrupMember($chat_group_data)
    {
        $group_change_info  = array();
        log_message('info', "receiver delete chat group member data is ->".var_export($chat_group_data, true));
        $group_ids      = array();
        $group_logo     = array();
        $chat_members   = array();
        foreach($chat_group_data as $data){
            $group_ids[]                    = $data['group_id'];
            $group_logo[$data['group_id']]  = $data['group_logo'];
            $chat_members[$data['group_id']]= $data['user_id'];
        }
        log_message('info', "delete chat group id data is ->".var_export($group_ids, true));
        log_message('info', "delete chat group logo data is ->".var_export($group_logo, true));
        log_message('info', "delete chat group member data is ->".var_export($chat_members, true));
        //get group members
        $group_members = $this->CI->group_model->getGroupMember($group_ids);
        log_message('info', "delete chat group valid member data is ->".var_export($group_members, true));
        //create new group logo and name
        foreach($group_members as $gid => $members){
            $update_group_info = array();
            log_message('info', "group member count is [".count($members)."], member list is ".var_export($members, true));
            $update_group_info['member_count']   =  count($members) - 1;
            $logo_members   = isset($group_logo[$gid]) ? $group_logo[$gid]      : array();
            //离职的人在讨论组的logo中我们需要重新计算这个讨论组的头像和拼音
            $del_user_id    = isset($chat_members[$gid]) ? $chat_members[$gid]  : 0;
            if(in_array($del_user_id, $logo_members)){
                //delete member from valid member ids
                $search_idx     = array_search($del_user_id, $logo_members);
                if ($search_idx !== false || !is_null($search_idx)){
                    unset($logo_members[$search_idx]);
                }
                //delete member from group logo
                $group_logo_idx     = array_search($del_user_id, $members);
                if ($group_logo_idx !== false || !is_null($group_logo_idx)){
                    unset($members[$group_logo_idx]);
                }
                $diff_value     =  array_diff($members, $logo_members);
                $update_group_info['group_logo']     =  array_slice(array_merge($logo_members, $diff_value), 0, 4);
                $update_group_info['group_name']     =  $this->createGroupName($update_group_info['group_logo']);
                $update_group_info['group_pinyin']   =  pinyin::utf8_to($update_group_info['group_name'], true);
                
                log_message('info', "update current group [$gid] info. update info is ->".var_export($update_group_info, true));
                $this->CI->group_model->updateGroupInfo($update_group_info, $gid);
            }
            //set group change info 
            if (!empty($update_group_info)){
                $group_change_info[$gid]    = $update_group_info;
            }
        }
        //return 
        return $group_change_info;
    }
    /**
     * 根据用户信息计算讨论组名称
     */
    function createGroupName($user_ids)
    {
        $user_name_list     =   array();
        $user_info  = $this->CI->ums->getUserByIds($user_ids);
        if (!$user_info){
            log_message(LOG_ERR, "get multi user form ums error, error msg is ->".var_export($user_info, true));
            return join('、', $user_name_list);
        }
        //TODO:这里因为不知道ums返回多个用户信息的时候是按照什么顺序返回的所以这里我必须按照user id的顺序
        //组装用户的名称,这里后期必须做优化性能太低
        foreach ($user_ids as $user_id){
            foreach($user_info as $data){
                if ($data['id'] == $user_id){
                    $user_name_list[]   = $data['displayName'];
                    break;
                }
            }
        }
        //返回讨论组名称
        return join('、', $user_name_list);
    }

}