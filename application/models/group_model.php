<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_model extends MY_Model {

    public $ucc_db;
    public $tables;
    
    public function __construct(){
            parent::__construct();
            // uccserver db object
            $this->ucc_db = $this->load->database('uccserver', true);
            // uccserver tables
            $this->tables = array(
                    'group'         => 'user_group',
                    'group_member'  => 'group_member',
            );
    }
    /**
     * Method For Get Groups
     * @param array|string $member_ids    group members list
     * @param int          $site_id       current member ower site,it is used for mycat
     * @return array
     */
    public function getGroups($member_ids, $site_id)
    {
        $chat_group_data     = array();
        $project_group_data  = array();
        if(!is_array($member_ids)){
            $member_ids = array($member_ids);
        }
        //init sql
        $sql = sprintf("SELECT g.group_logo,g.nickname as group_name, g.group_pinyin, g.name_update_flag as name_flag, g.id, g.site_id, g.is_display as group_type, m.profile_id FROM " . 
                        $this->tables['group'] . " AS g LEFT JOIN " . 
                        $this->tables['group_member'] . " AS m ON g.id = m.group_id WHERE profile_id in (%s) AND m.join_state = 0", 
                        join(',', $member_ids));
        log_message('info', "get group data from db sql is [$sql]");
        //exec sql
        $results = $this->ucc_db->query($sql);
        if (!$results){
            log_message('error', 'get group data from error, error msg is ->'.var_export($this->ucc_db->last_error, true));
            return array(false, $chat_group_data);
        }
        //set up group data
        foreach($results->result_array() as $data){
            if(intval($data['group_type'] == 1)){
                //project group
                $tmp = array();
                $tmp['group_id']    = $data['id'];
                $tmp['site_id']     = $data['site_id'];
                $tmp['user_id']     = $data['profile_id'];
                $tmp['group_logo']  = $data['group_logo'];
                $tmp['group_name']  = $data['group_name'];
                $tmp['group_pinyin']= $data['group_pinyin'];
                $tmp['name_flag']   = $data['name_flag'];
                $project_group_data[] = $tmp;
            }elseif(intval($data['group_type'] == 2)){
                //group chat
                $tmp = array();
                $tmp['group_id']    = $data['id'];
                $tmp['site_id']     = $data['site_id'];
                $tmp['user_id']     = $data['profile_id'];
                $tmp['group_logo']  = json_decode($data['group_logo'], true);
                $tmp['group_name']  = $data['group_name'];
                $tmp['group_pinyin']= $data['group_pinyin'];
                $tmp['name_flag']   = $data['name_flag'];
                $chat_group_data[]  = $tmp;
            }else{
                continue;
            }
        }
        //return 
        return array(true, $project_group_data, $chat_group_data);
    }
    
    function getGroupMember($group_ids)
    {
        $member_ids = array();
        //init sql
        $sql        = sprintf("SELECT group_id, profile_id FROM ". $this->tables['group_member'] . " WHERE join_state = 0 AND group_id in (%s) ORDER BY created DESC", 
                              join(',', $group_ids));
        log_message('info', "get group member by group id sql is [$sql]");
        $results    = $this->ucc_db->query($sql);
        foreach($results->result_array() as $item){
            $member_ids[$item['group_id']][]  = $item['profile_id'];
        }
        //
        return $member_ids;
    }
    
    function updateGroupInfo($update_options, $group_id)
    {
        $upt_sql    = sprintf("UPDATE " . $this->tables['group'] . 
                              " SET group_logo = '%s', nickname = '%s', group_pinyin = '%s', member_count = %d, modified = '%s' WHERE id = %d",
                               mysql_escape_string(json_encode($update_options['group_logo'])), mysql_escape_string($update_options['group_name']),
                               mysql_escape_string($update_options['group_pinyin']), mysql_escape_string($update_options['member_count']),
                               $this->common_sql_now(), $group_id);
        log_message('info', "update group info sql is [$upt_sql]");
        if(!$this->ucc_db->query($upt_sql)){
            return false;
        }else{
            return true;
        }
    }
    
    function deleteGroupMember($member_ids)
    {
        if (!is_array($member_ids)){
            $member_ids = array($member_ids);
        }
        //$del_sql = array();
        /*foreach($data as $item){
            $del_sql[] = sprintf("UPDATE " . $this->tables['group_member'] ." SET `join_state` = 1,`modified`='%s' WHERE profile_id = %d AND group_id = %d", 
                                 $this->common_sql_now(), $item['user_id'], $item['group_id']);
        }*/
        $del_sql = sprintf("UPDATE " . $this->tables['group_member'] ." SET `join_state` = 1,`modified`='%s' WHERE profile_id in (%s) ", 
                             $this->common_sql_now(), join(',', $member_ids));
        
        log_message('info', "delete project group member sql is ->".var_export($del_sql, true));
        //exec query
        $results = $this->ucc_db->query($del_sql);
        log_message('info', "delete project group member results is ->".var_export($results, true));
        if (!$results){
            log_message(LOG_ERR, "delete project group member error, error msg is ->".var_export($results, true));
            return false;   
        }else{
            return true;
        }
    }
    
    function common_sql_now(){ return strftime('%Y-%m-%d %H:%M:%S', time()); }
}