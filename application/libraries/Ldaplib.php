<?php if (!defined('BASEPATH'))	exit('No direct script access allowed');

/**
 * @class LdapLib
 * @brief LDAP 类库，主要负责对LDAP接口类的列表获取、保存、修改等操作。
 * @file LdapLib.php
 * @author zouyan <yan.zou@quanshi.com>
 * @copyright Copyright (c) UC
 * @version: v1.0
 */
class LdapLib  {
    /**
     *
     * @brief 根据当前站点ID、企业（生态企业），集团分散式分公司获得LDAP列表信息--?需要接口2.7.1： 
     * @details 
     * -# 根据当前站点ID，通过接口获得LDAP列表信息。
     * @param int $site_id  当前站点ID
     * @param int $org_id  当前组织ID
     * @return array $datearr 获得LDAP列表信息
     *
     */
      public function getLDAP($site_id,$org_id){
          $datearr=array();
          //通过接口获得
          return $datearr;
      }
    /**
     *
     * @brief 对单个LDAP进行开通操作[ajax加载]--?需要接口2.7.2： 
     * @details 
     * -# 获得JS post 过来的 选中的LDAP标识 LDAPId
     *    对LDAPId进行效验
     * -# 执行开通操作
     * @param string $site_id  当前站点id 
     * @return 返回状态，0失败 1成功
     *
     */
    public function openLDAP($site_id,$LDAPId) {
        // $this->form_validation->set_rules('LDAPId','开通LDAP','required');
         $LDAPId=$this->input->post('LDAPId', TRUE);


         $this->LDAPLib->setLDAPOpen($LDAPId);

    }  
    /**
     *
     * @brief 对单个LDAP进行关闭操作[ajax加载]--?需要接口2.7.3： 
     * @details 
     * 
     * -# 获得JS post 过来的 选中的LDAP标识 LDAPId
     *    对LDAPId进行效验
     * -# 执行开通操作
     * @param string $site_id  当前站点id 
     * @return 返回状态，0失败 1成功
     *
     */
    public function closeLDAP($site_id,$LDAPId) {
        // $this->form_validation->set_rules('LDAPId','关闭LDAP','required');
         $LDAPId=$this->input->post('LDAPId', TRUE);


         $this->LDAPLib->setLDAPClose($LDAPId);

    }  
      /**
     *
     * @brief 对选择的LDAP进行删除操作--?需要接口2.7.4： 
     * @details 
     * -# 对选择的LDAP进行删除操作
     * @param string $site_id  当前站点id 
     * @param string $LDAPIds  需要删除的LDAP
     * @return array $datearr 返回删除LDAP状态，0失败 1成功
     *
     */
      public function delLDAPbyLDAPIds($site_id,$LDAPIds){
          $datearr=array();
          //通过接口获
          return $datearr;
      }
      /**
     *
     * @brief 根据当前的LDAP标识，获得LDAP详情--?需要接口2.7.5： 
     * @details 
     * @param string $site_id  当前站点id 
     * @param string $LDAPId  需要获取的LDAP
     * @return array $datearr 返回LDAP信息
     *
     */
      public function getLDAPInfo($site_id,$LDAPId){
          $datearr=array();
          //通过接口获
          return $datearr;
      }
   /**
     *
     * @brief 向BOSS新加LDAP服务器配置--?需要接口2.1.1： 
     * @details 
     * @param int $data  需要保存LDAP信息
     * @return ?array ?$datearr ? 返回是否保存成功
     *
     */
      public function saveLdap($data){         
          //保存
          // return 0;
      } 
   /**
     *
     * @brief 向BOSS修改指定LDAP标识的LDAP服务器配置--?需要接口2.1.2： 
     * @details 
     * -# 返回状态 0失败该 1成功
     * @param array $data  需要保存LDAP信息
     * @param int $LDAPId 需要保存LDAP标识 
     * @return ?array ?$datearr ? 返回是否保存成功
     *
     */
      public function modifyLdap($data,$LDAPId){         
          //保存
          // return 0;
      } 
}
