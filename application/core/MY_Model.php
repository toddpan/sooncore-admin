<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Model extends CI_Model {
    // will hold the table name of the current instance
    protected $table_name = "";
    // this constructor will help us initialize our child classes
    public function __construct() {
        parent::__construct();
        //手动载入数据库操作类
        //默认数据库
        $this->load->database(DB_RESOURCE);

        //载入公用函数库
        $this->load->helper('my_publicfun');
        //日期时间库
        $this->load->helper('my_dgmdate');
    }

    public function set_table($tablename)
    {
        $this->table_name = $tablename;
    }
    /**
     *
     * @brief 查询单条[返回一维数组]/多条[返回二维数组]或当前条件的记录数量[返回数量]
     * @details
     * @param array $re_type  返回类型
     * 1返回单条记录 一维数组
     * 2 返回多条记录，二维数组
     * 3 返回当前记录总数量 ,数值
     * 4  删除  返回：array('is_success'=> 0失败1成功,'affect_num'=>‘影响的行数’)
     * 5 修改更新 返回：array('is_success'=> 0失败1成功,'affect_num'=>‘影响的行数’)
     * @param array $db_arr 特别注意，数组的键前后不要有空格
     *   array(
     *     'update_data' => array(//需要修改的数组
     *              'field' => '字段值'
     *       )
     *     'select' => array(//  选择的字段数组或'title, content, date'，多个用逗号分隔，默认为 *
     *              array{
     *                  'field' => 'fieldname',//字段名
     *                  'istrans' => TRUE (这是默认值)或者 FALSE
     *          }
     *     'select_max' => array(array( //二维数组SELECT MAX(field)"
     *                  'field' => 'field',//字段名
     *                  'alias' => 'alias'//别名
     *
     *          )  )
     *     'select_min' //同 select_max
     *     'select_avg' //同 select_max
     *     'select_sum' //同 select_max
     *
     *     'distinct' => $distinct  distinct;形式:0没有1有
     *    'limit' => $limit_arr limit(10, 20) 或limit(10); 形式:
     *      array(
     *          'limit' => 10,//返回的结果数量,可以为空 默认1000：偏移量到记录集的结束所有的记录行 -1
     *          'offset' =>20//结果偏移量
     *      )
     *     //不能用，已删除'limit' => 每页纪录数(limit) 大于0，才是使用
     *     //不能用，已删除'offset' => 结果集的偏移(offset)     *
     *     'join' =>array(//联合查询,可以有多个，二维数组
     *                 array(
     *                      'join_db' => '数据库名称',//'comments'
     *                      'join_where' => '条件',//'comments.id = blogs.id'
     *                      'join_type' => '类型',//可选项包括：left, right, outer, inner, left outer, 以及 right outer
     *
     *                  )
     *      )
     *     'where' => $where_arr  条件数组或字串或二维数组
     *           array('name !=' => $name, 'id <' => $id, 'date >' => $date);
     *           还可以手动的编写子句：如'where' => "name='Joe' AND status='boss' OR status='active'";
     *           array(
     *                  array(
     *                    'field'=>'字段名',
     *                    'value' => '字段值',
     *                    'istrans' => //TRUE 或者 用这个值 FALSE (这是默认值)不会为你那些包含反勾号的字段名或表名提供保护
     *                  )
     *              )

     *     'or_where' => $or_where_arr [同$where_arr]
     *     'where_in' => $where_in_arr 多个用两维数组：形式:array('filed'=>array('Frank', 'Todd', 'James'))
     *     'or_where_in' => $or_where_in_arr 用 OR 连接起来;多个用两维数组：形式:array('filed'=>array('Frank', 'Todd', 'James'))
     *     'where_not_in' => $where_not_in_arr  用 AND 连接起来;多个用两维数组：形式:array('filed'=>array('Frank', 'Todd', 'James'))
     *     'or_where_not_in' => $or_where_not_in_arr  用 OR 连接起来;多个用两维数组：形式:array('filed'=>array('Frank', 'Todd', 'James'))
     *     'like' => $like_arr  like;多个用两维数组：形式:
     *          array(
     *              array(
     *                'field' =>字段名,
     *                'value' =>字段值,
     *                'method' => 'none'[like '值'],'before', 'after' 以及 'both' (这是默认值)
     *               )
     *          )
     *    'or_like' => $or_like_arr  or_like;多个实例之间是用 OR 连接起来的 ,多个用两维数组：形式同like
     *    'not_like' => $not_like_arr  not_like;生成 NOT LIKE 语句;多个用两维数组：形式同like
     *    'or_not_like' => $or_not_like_arr  or_not_like;多个实例之间是用 OR 连接起来的;多个用两维数组：形式同like
     *    'group_by' => $group_by_arr  group_by;形式:array("title", "date")或单个分组时，单个字符串"title"
     *    'having' => $having_arr  having;单个字符'user_id = 45'；多个用：一维数组array('title =' => 'My Title', 'id <' => $id)；两维数组：形式:
     *          array(
     *              array(
     *                'field' =>字段名,//可以是这样的 'title =' => 'My Title', 'id <' => $id
     *                'value' =>字段值,
     *                'istrans' => TRUE (这是默认值)或者 FALSE
     *               )
     *          )
     *    'or_having' => $or_having_arr  having;多个子句之间是用 "OR" 分隔的；多个用两维数组：形式:同having
     *    'order_by' => $order_by  order_by;order_by('title desc, name asc'); 包括 asc (升序)或 desc(降序), 或 random(随机)
     *                  格式一：'"title", "desc"'  第一个参数是你想要排序的字段名。第二个参数设置结果的顺序，可用的选项包括 asc (升序)或 desc(降序), 或 random(随机)。
     *                  格式二：'title desc, name asc'
     *                  格式三： array('"title", "desc"','"title", "desc"');//格式一的多维数组
     * )
     * @param array
     * @return array 返回当前记录数组或空数组
     *
     */
    public function operateDB($re_type = 0,$db_arr){
            log_message('debug', '  ' . __FUNCTION__ . ' $re_type=' . $re_type . '   $db_arr=' . any_to_str($db_arr) . ' '); 
            $update_data_arr = isset($db_arr['update_data'])?$db_arr['update_data']:array();//需要修改的数组
            $select_arr = isset($db_arr['select'])?$db_arr['select']:array();//查询字段
            $select_max_arr = isset($db_arr['select_max'])?$db_arr['select_max']:array();//select_max
            $select_min_arr = isset($db_arr['select_min'])?$db_arr['select_min']:array();//select_min
            $select_avg_arr = isset($db_arr['select_avg'])?$db_arr['select_avg']:array();//select_avg
            $select_sum_arr = isset($db_arr['select_sum'])?$db_arr['select_sum']:array();//select_sum
            $distinct = isset($db_arr['distinct'])?$db_arr['distinct']:0;//distinct 条件
            $limit_arr = isset($db_arr['limit'])?$db_arr['limit']:array();//limit 条件
            //$limit = isset($db_arr['limit'])?$db_arr['limit']:0;//每页纪录数(limit) 大于0，才是使用
            // $offset = isset($db_arr['offset'])?$db_arr['offset']:0;//结果集的偏移(offset)
            $join_arr = isset($db_arr['join'])?$db_arr['join']:array();//联合查询,可以有多个，二维数组
            $where_arr = isset($db_arr['where'])?$db_arr['where']:array();  //where条件
            $or_where_arr = isset($db_arr['or_where'])?$db_arr['or_where']:array();//or where条件
            $where_in_arr = isset($db_arr['where_in'])?$db_arr['where_in']:array();//in where 条件
            $or_where_in_arr = isset($db_arr['or_where_in'])?$db_arr['or_where_in']:array();//or_where_in 条件
            $where_not_in_arr = isset($db_arr['where_not_in'])?$db_arr['where_not_in']:array();//or_where_in 条件
            $or_where_not_in_arr = isset($db_arr['or_where_not_in'])?$db_arr['or_where_not_in']:array();//or_where_not_in 条件
            $like_arr = isset($db_arr['like'])?$db_arr['like']:array();//like 条件
            $or_like_arr = isset($db_arr['or_like'])?$db_arr['or_like']:array();//or_like 条件
            $not_like_arr = isset($db_arr['not_like'])?$db_arr['not_like']:array();//not_like 条件
            $or_not_like_arr = isset($db_arr['or_not_like'])?$db_arr['or_not_like']:array();//or_not_like 条件
            $group_by_arr = isset($db_arr['group_by'])?$db_arr['group_by']:array();//group_by 条件
            $having_arr = isset($db_arr['having'])?$db_arr['having']:array();//having 条件
            $or_having_arr = isset($db_arr['or_having'])?$db_arr['or_having']:array();//or_having 条件
            $order_by = isset($db_arr['order_by'])?$db_arr['order_by']:'';//order_by 条件


            //查询字段
            if(!isemptyArray($select_arr)){
                    foreach($select_arr as $k => $v)
                    {
                            $field = isset($v['field'])?$v['field']:'';//字段名
                            $istrans = isset($v['istrans'])?$v['istrans']:TRUE; // TRUE (这是默认值)或者 FALSE
                            if(!empty($field)){
                                    $this->db->select($field,$istrans);
                            }
                    }
            }else{//不是数组'title, content, date'，多个用逗号分隔
                    if(!empty($select_arr)){
                            $this->db->select($select_arr);
                    }

            }

            //select_max
            if(!isemptyArray($select_max_arr)){
                    foreach($select_max_arr as $k => $v)
                    {
                            $field = isset($v['field'])?$v['field']:'';
                            $alias = isset($v['alias'])?$v['alias']:$field;
                            if(!empty($field)){
                                    $this->db->select_max($field, $alias);
                            }
                    }
            }

            //select_min
            if(!isemptyArray($select_min_arr)){
                    foreach($select_min_arr as $k => $v)
                    {
                            $field = isset($v['field'])?$v['field']:'';
                            $alias = isset($v['alias'])?$v['alias']:$field;
                            if(!empty($field)){
                                    $this->db->select_min($field, $alias);
                            }
                    }
            }

            //select_avg
            if(!isemptyArray($select_avg_arr)){
                    foreach($select_avg_arr as $k => $v)
                    {
                            $field = isset($v['field'])?$v['field']:'';
                            $alias = isset($v['alias'])?$v['alias']:$field;
                            if(!empty($field)){
                                    $this->db->select_avg($field, $alias);
                            }
                    }
            }

            //select_sum
            if(!isemptyArray($select_sum_arr)){
                    foreach($select_sum_arr as $k => $v)
                    {
                            $field = isset($v['field'])?$v['field']:'';
                            $alias = isset($v['alias'])?$v['alias']:$field;
                            if(!empty($field)){
                                    $this->db->select_sum($field, $alias);
                            }
                    }
            }

            //DISTINCT" 关键字
            if($distinct == 1){
                    $this->db->distinct();
            }

            //limit

            if(!isemptyArray($limit_arr)){
                    $in_limit = isset($limit_arr['limit'])?$limit_arr['limit']:1000;//返回的结果数量,可以为空 默认：偏移量到记录集的结束所有的记录行 -1
                    $in_offset = isset($limit_arr['offset'])?$limit_arr['offset']:0;//结果偏移量

                    if($re_type == 1 ){//   1返回单条记录 一维数组
                            if($in_limit >1 ){
                                    $in_limit = 1;
                            }
                    }

                    $this->db->limit($in_limit, $in_offset);


            }else{
                    if($re_type == 1 ){//   1返回单条记录 一维数组
                            if(empty($in_limit)){
                                    $in_limit = 1;
                            }
                            if(empty($in_offset)){
                                    $in_offset = 0;
                            }
                            $this->db->limit($in_limit, $in_offset);

                    }
            }
            //where条件
            if(!isemptyArray($where_arr)){

                    $is_two_arr = 0;//是不是二维数组0不是1是
                    foreach($where_arr as $k => $v){
                            //二维数组
                            if(is_array($v)){
                                    $is_two_arr = 1;//是不是二维数组0不是1是
                                    $field = isset($v['field'])?$v['field']:'';//字段名
                                    $value = isset($v['value'])?$v['value']:'';//字段值
                                    $istrans = isset($v['istrans'])?$v['istrans']:FALSE; //TRUE 或者 FALSE (这是默认值)不会为你那些包含反勾号的字段名或表名提供保护
                                    if(!empty($field)){
                                            $this->db->where($field, $value, $istrans);
                                    }
                            }else{
                                    break;
                            }
                    }
                    if($is_two_arr == 0){//一维数据组$array = array('name !=' => $name, 'id <' => $id, 'date >' => $date);
                            $this->db->where($where_arr);
                    }
            }else{//是字符串
                    if(!empty($where_arr)){                            
                            $this->db->where($where_arr);
                    }
            }

            //or where条件
            // if(!isemptyArray($or_where_arr)){
            //    $this->db->or_where($or_where_arr);
            // }
            if(!isemptyArray($or_where_arr)){

                    $is_two_arr = 0;//是不是二维数组0不是1是
                    foreach($or_where_arr as $k => $v){
                            //二维数组
                            if(is_array($v)){
                                    $is_two_arr = 1;//是不是二维数组0不是1是
                                    $field = isset($v['field'])?$v['field']:'';//字段名
                                    $value = isset($v['value'])?$v['value']:'';//字段值
                                    $istrans = isset($v['istrans'])?$v['istrans']:FALSE; //TRUE 或者 FALSE (这是默认值)不会为你那些包含反勾号的字段名或表名提供保护
                                    if(!empty($field)){
                                            $this->db->or_where($field, $value, $istrans);
                                    }
                            }else{
                                    break;;
                            }
                    }
                    if($is_two_arr == 0){//一维数据组$array = array('name !=' => $name, 'id <' => $id, 'date >' => $date);
                            $this->db->or_where($or_where_arr);
                    }
            }else{//是字符串
                    if(!empty($or_where_arr)){
                            $this->db->or_where($or_where_arr);
                    }
            }


            //in where 条件
            if(!isemptyArray($where_in_arr)){
                    foreach($where_in_arr as $k => $v)
                    {
                            $this->db->where_in($k,$v);
                    }
            }
            //or_where_in 条件
            if(!isemptyArray($or_where_in_arr)){
                    foreach($or_where_in_arr as $k => $v)
                    {
                            $this->db->or_where_in($k,$v);
                    }
            }
            //where_not_in 条件
            if(!isemptyArray($where_not_in_arr)){
                    foreach($where_not_in_arr as $k => $v)
                    {
                            $this->db->where_not_in($k,$v);
                    }
            }
            //or_where_not_in 条件
            if(!isemptyArray($or_where_not_in_arr)){
                    foreach($or_where_not_in_arr as $k => $v)
                    {
                            $this->db->or_where_not_in($k,$v);
                    }
            }

            //like 条件
            if(!isemptyArray($like_arr)){
                    foreach($like_arr as $k => $v)
                    {
                            $field = isset($v['field'])?$v['field']:'';
                            $value = isset($v['value'])?$v['value']:'';
                            $method = isset($v['method'])?$v['method']:'both';
                            if(!empty($field)){
                                    $this->db->like($field, $value, $method);
                            }
                    }
            }

            //or_like 条件
            if(!isemptyArray($or_like_arr)){
                    foreach($or_like_arr as $k => $v)
                    {
                            $field = isset($v['field'])?$v['field']:'';
                            $value = isset($v['value'])?$v['value']:'';
                            $method = isset($v['method'])?$v['method']:'both';
                            if(!empty($field)){
                                    $this->db->or_like($field, $value, $method);
                            }
                    }
            }

            //not_like 条件
            if(!isemptyArray($not_like_arr)){
                    foreach($not_like_arr as $k => $v)
                    {
                            $field = isset($v['field'])?$v['field']:'';
                            $value = isset($v['value'])?$v['value']:'';
                            $method = isset($v['method'])?$v['method']:'both';
                            if(!empty($field)){
                                    $this->db->not_like($field, $value, $method);
                            }
                    }
            }

            //or_not_like 条件
            if(!isemptyArray($or_not_like_arr)){
                    foreach($or_not_like_arr as $k => $v)
                    {
                            $field = isset($v['field'])?$v['field']:'';
                            $value = isset($v['value'])?$v['value']:'';
                            $method = isset($v['method'])?$v['method']:'both';
                            if(!empty($field)){
                                    $this->db->or_not_like($field, $value, $method);
                            }
                    }
            }
            //group_by 条件
            if(!isemptyArray($group_by_arr)){
                    $this->db->group_by($group_by_arr);
            }else{
                    if(!is_array($group_by_arr)){
                            if(!empty($group_by_arr)){
                                    $this->db->group_by($group_by_arr);
                            }
                    }
            }


            //having 条件
            if(!isemptyArray($having_arr)){
                    $is_two_arr = 0;//是否是二维数组0不是1是
                    foreach($having_arr as $k => $v)
                    {

                            if(is_array($v)){
                                    $is_two_arr = 1;//是否是二维数组0不是1是
                                    $field = isset($v['field'])?$v['field']:'';
                                    $value = isset($v['value'])?$v['value']:'';
                                    $istrans = isset($v['istrans'])?$v['istrans']:TRUE;
                                    if(!empty($field)){
                                            $this->db->having($field, $value, $istrans);
                                    }
                            }else{
                                    break;
                            }
                    }
                    if($is_two_arr == 0){//是否是二维数组0不是1是
                            $this->db->having($having_arr);
                    }
            }else{
                    if(!is_array($having_arr)){
                            if(!empty($having_arr)){
                                    $this->db->having($having_arr);
                            }
                    }
            }

            //单个字符'user_id = 45'；多个用：一维数组array('title =' => 'My Title', 'id <' => $id)；
            //or_having 条件
            if(!isemptyArray($or_having_arr)){
                    $is_two_arr = 0;//是否是二维数组0不是1是
                    foreach($or_having_arr as $k => $v)
                    {
                            if(is_array($v)){
                                    $field = isset($v['field'])?$v['field']:'';
                                    $value = isset($v['value'])?$v['value']:'';
                                    $istrans = isset($v['istrans'])?$v['istrans']:TRUE;
                                    if(!empty($field)){
                                            $this->db->or_having($field, $value, $istrans);
                                    }
                            }else
                            {
                                    break;
                            }
                    }
                    if($is_two_arr == 0){//是否是二维数组0不是1是
                            $this->db->or_having($or_having_arr);
                    }
            }else{
                    if(!is_array($or_having_arr)){
                            if(!empty($or_having_arr)){
                                    $this->db->or_having($or_having_arr);
                            }
                    }
            }

            //order_by 条件
            if(is_array($order_by)){
                foreach($order_by as $o_k => $o_v){
                    $this->db->order_by($o_v);
                }
            }else{
                if(!empty($order_by)){
                        $this->db->order_by($order_by);
                }
            }




            //$join_arr 联合查询,可以有多个，二维数组
            if(!isemptyArray($join_arr)){
                    foreach($join_arr as $k => $v)
                    {
                            $join_db = isset($v['join_db'])?$v['join_db']:'';
                            $join_where = isset($v['join_where'])?$v['join_where']:'';
                            $join_type = isset($v['join_type'])?$v['join_type']:' left ';
                            if(!(empty($join_db) || empty($join_where)) ){
                                    $this->db->join($join_db, $join_where,$join_type);
                            }
                    }
            }

            //需要from的
            switch ($re_type) {
                    case 1: //1返回单条记录 一维数组

                    case 2://2 返回多条记录

                    case 3://3 返回当前记录总数量 ,数值
                            $this->db->from($this->table_name);
                            break;
            }

            //操作后,返回相关值
            switch ($re_type) {
                    case 1: //1返回单条记录 一维数组
                            $query = $this->db->get();
                            $re_data = $query->row_array();
                            log_message('debug', ' ' . __FUNCTION__ . '  $re_data=' . any_to_str($re_data) . ' ');
                            return $re_data;
                            break;
                    case 2://2 返回多条记录
                            $query = $this->db->get();
                            $re_nums = $query->result_array();
                            log_message('debug', ' ' . __FUNCTION__ . '  $re_nums=' . any_to_str($re_nums) . ' ');
                            return $re_nums;
                            break;
                    case 3://3 返回当前记录总数量 ,数值
                            $query = $this->db->count_all_results();
                            log_message('debug', ' ' . __FUNCTION__ . '  $query=' . any_to_str($query) . ' ');
                            return $query;
                            break;
                    case 4://4  删除 返回：array('is_success'=> 0失败1成功,'affect_num'=>‘影响的行数’)
                            $query = $this->db->delete($this->table_name);
                            $affect_num = 0;
                            if($query == 1){//成功
                                    $affect_num = $this->db->affected_rows();//当执行写入操作（insert,update等）的查询后，显示被影响的行数。
                            }
                            $re_data = array(
               'is_success'=> $query,//0失败1成功
               'affect_num'=>$affect_num//‘影响的行数’ 
                            );
                            log_message('debug', ' ' . __FUNCTION__ . '  $re_data=' . any_to_str($re_data) . ' ');
                            return $re_data;
                            break;
                    case 5://5 修改更新 返回：array('is_success'=> 0失败1成功,'affect_num'=>‘影响的行数’)
                    	log_message('debug', ' ' . __FUNCTION__ . 'set manager SQL data' . any_to_str($update_data_arr) . ' ');
                            $query = $this->db->update($this->table_name, $update_data_arr); //1成功，0失败
                            $affect_num = 0;
                            if($query == 1){//成功
                                    $affect_num = $this->db->affected_rows();//当执行写入操作（insert,update等）的查询后，显示被影响的行数。
                            }
                            $re_data = array(
               'is_success'=> $query,//0失败1成功
               'affect_num'=>$affect_num//‘影响的行数’ 
                            );
                            log_message('debug', ' ' . __FUNCTION__ . '  $re_data=' . any_to_str($re_data) . ' ');
                            return $re_data;
                            break;
            }


    }
    /**
     * @brief 对数据表插入新的数据
     * @param $data array 需要新加的数据
     * $data = array(
     *   '字段名'=>'字段值',
     *   ...
     * )
     * @return array 返回：array('is_success'=> 0失败1成功,'affect_num'=>‘影响的行数,'insert_id' => 当前标识)
     */
    public function insert_db($data){
           log_message('debug', ' ' . __FUNCTION__ . '  $data=' . any_to_str($data) . ' '); 
            //使用AR类完成插入操作
            $query = $this->db->insert($this->table_name,$data);//1成功,0失败
            $insert_id = 0;
            $affect_num = 0;
            if($query == 1){//成功
                    $insert_id = $this->db->insert_id();//当前标识
                    $affect_num = $this->db->affected_rows();//当执行写入操作（insert,update等）的查询后，显示被影响的行数。
            }
            $re_data = array(
           'is_success'=> $query,//0失败1成功
           'affect_num'=>$affect_num,//‘影响的行数’ 
           'insert_id' => $insert_id//当前标识
            );
            log_message('debug', ' ' . __FUNCTION__ . '  $re_data=' . any_to_str($re_data) . ' ');
            return $re_data;


    }
     /**
     *
     * @brief 获得指定条件的数量及列表
     * @details 
     * @param array $re_type_arr 返回类型 1总数量，2列表信息 array(1,2);
     * @param array $in_where_arr  查询条件
        $in_where_arr = array(
            'field' => $aaa,//需要返回的字段;选择的字段数组或'title, content, date'，多个用逗号分隔，默认为 *
            'sumfield' => $aaa,//计算总数量时用到的字段;此处见意只选择一个主键，提高效率
            'sum_where_arr' => $aaa,//求数量的条件 operateDB 方法的条件形式
            'list_where_arr' => $aaa,//求列表的条件 operateDB 方法的条件形式[注意如果列表件与求数量条件相同，则为空数组],不为空，列表就有这个
        );
     * @return array  
        $re_arr = array(
            'sumnum' => $aaa,//数量
            'db_arr' => $aaa,//返回的数组列表
        );
     *
     */
    public function get_db_sumlistarr($re_type_arr = array(),$in_where_arr = array()){
        log_message('debug', ' ' . __FUNCTION__ . '  $re_type_arr=' . any_to_str($re_type_arr) . '  $in_where_arr=' . any_to_str($in_where_arr) . ''); 
        $re_arr = array(
            'sumnum' => 0,//数量
            'db_arr' => array(),//返回的数组列表
        );
        if(isemptyArray($re_type_arr)){//空数组
            return $re_arr;
        }
        $field = arr_unbound_value($in_where_arr,'field',2,'');  
        $sumfield = arr_unbound_value($in_where_arr,'sumfield',2,'');  
        $sum_where_arr = arr_unbound_value($in_where_arr,'sum_where_arr',1,array());        
        $list_more_where_arr = arr_unbound_value($in_where_arr,'list_where_arr',1,array());

        $sumnum = 0;
        $db_arr = array();
        if(isemptyArray($list_more_where_arr)){//没有
            if(deep_in_array('2', $re_type_arr)){//需要返回列表

                $data_arr = array(
                    'select' =>$field,                
                );
                $data_arr = array_merge($data_arr ,$sum_where_arr);
                $db_arr =  $this->operateDB(2,$data_arr);

                if(deep_in_array('1', $re_type_arr)){//需要返回数量
                    $sumnum = count($db_arr); 
                }

            }else{//不需要
                if(deep_in_array('1', $re_type_arr)){//需要返回数量
                    $data_arr = array(
                        'select' =>$sumfield,                
                    );
                    $data_arr = array_merge($data_arr ,$sum_where_arr);
                    $sumnum = $this->operateDB(3,$data_arr);
                }
            }
        }else{//有补充条件
            
            if(deep_in_array('1', $re_type_arr)){//需要返回数量
                $data_arr = array(
                    'select' =>$sumfield,                
                );
                $data_arr = array_merge($data_arr ,$sum_where_arr);
                $sumnum = $this->operateDB(3,$data_arr);
            }
            
            if(deep_in_array('2', $re_type_arr)){//需要返回列表
                $data_arr = array(
                    'select' =>$field,                
                );
                $data_arr = array_merge($data_arr ,$list_more_where_arr);      
                $db_arr =  $this->operateDB(2,$data_arr);
            }
        }
        $re_arr = array(
            'sumnum' => $sumnum,//数量
            'db_arr' => $db_arr,//返回的数组列表
        );  
        
        log_message('debug', ' ' . __FUNCTION__ . '  $re_arr=' . any_to_str($re_arr) . ' ');
        return $re_arr;
    }
    /**
     *
     * @brief 获得指定条件的数量[注意一定要有id字段的；才可以用]
     * @details
     * @param array $where_arr 条件数组
            array(
                'where' => array(
                       'site_id =' => $this->p_site_id,
                 ),
            )
     * @return int $sum 返回数量 
     *
     */
    public function get_sum($where_arr = array()) {
        log_message('debug', ' ' . __FUNCTION__ . '  $where_arr=' . any_to_str($where_arr) . '  '); 
        $re_num = 0;
        $in_where_arr = array(
            'field' => 'id',//需要返回的字段;选择的字段数组或'title, content, date'，多个用逗号分隔，默认为 *
            'sumfield' => 'id',//计算总数量时用到的字段;此处见意只选择一个主键，提高效率
            'sum_where_arr' => $where_arr,//求数量的条件 operateDB 方法的条件形式
            'list_where_arr' => array(),//求列表的条件 operateDB 方法的条件形式[注意如果列表件与求数量条件相同，则为空数组],不为空，列表就有这个
        );        
        $re_arr = $this->get_db_sumlistarr(array(1),$in_where_arr);
        $re_num = arr_unbound_value($re_arr,'sumnum',2,0);
        log_message('debug', ' ' . __FUNCTION__ . '  $re_num=' . any_to_str($re_num) . ' ');
        return $re_num;
    }
    /**
     * @access public
     * @abstract 根据数组,获得当前数据表信息数组         
     * @param array 如
     *     array(
     *          'org_id' => ,//企业id
     *          'site_id' => ,//站点id
     * )
     * @param string $re_field 想要返回的字段,多个用逗号分隔 ,为空返回所有
     * @return array 返回一维信息数组，没有数据则返回空数组
     */
    public function get_db_arr($in_array = array(),$re_field = ''){
        log_message('debug', ' ' . __FUNCTION__ . '  $in_array=' . any_to_str($in_array) . '  $re_field=' . any_to_str($re_field) . '  ');
        $re_array = array();
        if(isemptyArray($in_array)){//如果是空数组
            return $re_array;
        }
        $sel_data = array(  
            //'select' =>'complexity_type',
            'where' => $in_array,///array(
                //'org_id' => $org_id, 
               // 'site_id' => $site_id,                           
           // )
       );
       if(!bn_is_empty($re_field)){//有数据
           $sel_data['select'] = $re_field;
       }
       $sel_arr =  $this->operateDB(1,$sel_data); 
       if(!isemptyArray($sel_arr)){//如果不是空数组
           $re_array = $sel_arr;
       }
       log_message('debug', ' ' . __FUNCTION__ . '  $re_array=' . any_to_str($re_array) . ' ');
       return $re_array;
    }
    /**
     * @access public
     * @abstract 操作 1、有记录则更新记录，没记录则新加；  2、有记录则更新记录，没有则不新加  3、有记录则不操作，没有则新加  * 
     * @param int $operate 操作类型  1、有记录则更新记录，没记录则新加；  2、有记录则更新记录，没有则不新加 
     * @param string $select 查询字段,写上主键就可以了。'customerCode,contractId,value' 多个用逗号分隔,写上id或其它缩小字段范围
     *             选择的字段数组或'title, content, date'，多个用逗号分隔，默认为 *
     *              array{
     *                  'field' => 'fieldname',//字段名
     *                  'istrans' => TRUE (这是默认值)或者 FALSE
     *          }
     * @param array $where_arr 查询或更新条件
          'where_arr' => array(
              'customerCode' => $customerCode,
              'contractId' => $contract_id,
              'siteId' => $siteId,//站点id
               )
     *          条件数组或字串或二维数组
     *           array('name !=' => $name, 'id <' => $id, 'date >' => $date);
     *           还可以手动的编写子句：如'where' => "name='Joe' AND status='boss' OR status='active'";
     *           array(
     *                  array(
     *                    'field'=>'字段名',
     *                    'value' => '字段值',
     *                    'istrans' => //TRUE 或者 用这个值 FALSE (这是默认值)不会为你那些包含反勾号的字段名或表名提供保护
     *                  )
     *              )
     * @param array $modify_arr 更新时数组
     *   $insert_data = array(  
            'customerCode' =>$customerCode,//客户编码
            'contractId' =>$contract_id,//$requestId,//id 
            'siteId' => $siteId,//站点id
            'name' => $site_name,//客户名称
            'value' => json_encode($components_arr),//站点权限配置（Json串）
        );
     * @param array $insert_arr 插入时数组
     * @return int  -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败
     */
    public function updata_or_insert($operate = 1,$select = '',$where_arr = array(),$modify_arr = array(),$insert_arr = array()){
        log_message('debug', ' ' . __FUNCTION__ . '  $operate=' . any_to_str($operate) . '  $select=' . any_to_str($select) . '  $where_arr=' . any_to_str($where_arr) . '  $modify_arr=' . any_to_str($modify_arr) . '  $insert_arr=' . any_to_str($insert_arr) . '  ');
        $re_num = 5;//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
        //保存站点客户表uc_customer
        $sel_data = array();
        $modify_data = array();
        $insert_data = array();
        /*
        $sel_data = array(  
          'select' =>'customerCode,contractId,value',
          'where' => array(
              'customerCode' => $customerCode,
              'contractId' => $contract_id,
              'siteId' => $siteId,//站点id
               )

        );
         * 
         */
      if (!is_array($select) ){//不是数组
        if(!bn_is_empty($select)){//有数据
            $sel_data['select'] = $select;
        }
       }else{//是数组
           if(!isemptyArray($select)){//不是空数组 
               $sel_data['select'] =  $select;

           }
       } 

       if (!is_array($where_arr) ){//不是数组
        if(!bn_is_empty($where_arr)){//有数据
            $sel_data['where'] = $where_arr;
            $modify_data['where'] = $where_arr;
        }
       }else{//是数组
           if(!isemptyArray($where_arr)){//不是空数组 
               $sel_data['where'] =  $where_arr;
               $modify_data['where'] = $where_arr;
           }
       }  
       
        if(!isemptyArray($modify_arr)){//不是空数组 
            $modify_data['update_data'] = $modify_arr;
        }  
        
        if(!isemptyArray($insert_arr)){//不是空数组 
            $insert_data = $insert_arr;
        } 
        
        $sel_arr = $this->operateDB(1,$sel_data); 
        if(!isemptyArray($sel_arr)){//有记录，则修改
            if($operate == 1 || $operate == 2 ){//1、有记录则更新记录，没记录则新加；  2、有记录则更新记录，没有则不新加  3、有记录则不操作，没有则新加  
                $update_arr =  $this->operateDB(5,$modify_data);
                if(db_operate_fail($update_arr)){//失败
                    $err_msg = 'update  ' . $this->table_name . '  fail.';
                    log_message('error', $err_msg); 
                    $re_num = -2;// -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
                }else{
                    log_message('debug', 'update  ' . $this->table_name . '  success.'); 
                    $re_num = -1;// -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
                }
            }else{// 3、有记录则不操作，没有则新加  
                $re_num = -5;// -1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
            }
        }else{//没有记录，则新加            
            if($operate == 1 || $operate == 3 ){//1、有记录则更新记录，没记录则新加；  2、有记录则更新记录，没有则不新加  3、有记录则不操作，没有则新加  
                $insert_arr = $this->insert_db($insert_data);
                if(db_operate_fail($insert_arr)){//失败
                    $err_msg = 'insert  ' . $this->table_name . ' fail.';
                    log_message('error', $err_msg); 
                    $re_num = -4;//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
                }else{
                    log_message('debug', 'insert ' . $this->table_name . ' success.'); 
                    $new_insert_id = arr_unbound_value($insert_arr,'insert_id',2,-3);
                    $re_num = $new_insert_id;//-3;//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
                }
            }else{
                $re_num = -5;//-1 更新记录成功 -2更新操作失败 -3新加的记录id 新加记录成功 -4新加操作失败 -5 没有操作
            }
        }
        log_message('debug', ' ' . __FUNCTION__ . '  $re_num=' . any_to_str($re_num) . ' ');
        return $re_num;

    }
    /**
     * @access public
     * @abstract 根据SQL语句，返回数组数据[一维或二维]         
     * @param string $in_sql //传入的SQL语句,返回一维时，后面自动中LIMIT 0,1
     * @param int $type 返回类型1维数组2二维数组
     * @return array 返回数组
     */
    public function get_db_arr_by_sql($in_sql = '',$type = 2){
        log_message('debug', ' ' . __FUNCTION__ . '  $in_sql=' . any_to_str($in_sql) . '  $type=' . any_to_str($type) . '  ');
        $re_data = array();
        if(bn_is_empty($in_sql)){//没有数据
            return $re_data;
        }
        if($type == 1){//返回一维数组
            $in_sql .= ' LIMIT 0,1 ';
        }
        $query = $this->db->query($in_sql);
        if($type == 1){//返回一维数组
            $re_data = $query->row_array();
        }else{//二维数组
            $re_data = $query->result_array();
        }
        log_message('debug', ' ' . __FUNCTION__ . '  $re_data=' . any_to_str($re_data) . ' ');
        return $re_data;
    }
     /**
     *
     * @brief 获得指定条件的数量及列表
     * @details 
     * @param string $in_sql [可为空，不获取]sql语句 注意：select 字段1 from 表名 中字段需要用多少，就写多少可以提高运行速度
     * @param array $sum_sql  [可为空，不获取] 获得数量sql 语句 select count(主键) from  表名  注意：count(*) 没有 count(主键) 速度快， 更不要用 这样的格式[很慢]：select count(id) from (select 字段 from 表名) as new_table
     * @return array  
        $re_arr = array(
            'sumnum' => $aaa,//数量
            'db_arr' => $aaa,//返回的数组列表
        );
     *
     */
    public function get_db_sumlistarr_bysql($in_sql = '',$sum_sql = ''){
        log_message('debug', ' ' . __FUNCTION__ . '  $in_sql=' . any_to_str($in_sql) . '  $limit_arr=' . any_to_str($limit_arr) . '  ');
        $re_data = array(
            'sumnum' => 0,//数量
            'db_arr' => array(),//返回的数组列表
        );
        if(bn_is_empty($in_sql)){//没有数据
            return $re_data;
        }
        //获得总数量
        if($sum_sql != ''){
            $sum_query = $this->db->query($sum_sql);
            $sum_data = $sum_query->row_array();           
            $sum_num = 0 ;
            foreach($sum_data as $v){
               $sum_num = $v ; 
            }
            $re_data['sumnum'] = $sum_num;
        }

        //获得指定的记录
        if($in_sql != ''){
            $list_query = $this->db->query($in_sql);
            $list_data = $list_query->result_array();
            $re_data['db_arr'] = $list_data;
        }
        log_message('debug', ' ' . __FUNCTION__ . '  $re_data=' . any_to_str($re_data) . ' ');
        return $re_data;  
    }
    //$this->db->affected_rows();

    //public function get_all($limit = -1, $offset = 0, $orderby = '') {}
    //public function get_total_count() {}
    //public function get_total_count_where($where) {}
    //public function get_where($where = array(), $limit = 10, $offset = 0, $orderby = '') {}
    
}