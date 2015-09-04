<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//支持AJAX的PHP分页类
class Minupage
{
    //初始化对象时，可以给初始的值
    var $page_name = "p";//page标签，用来控制url页。比如说xxx.php?PB_page=2中的PB_page
//    var $format_left = ' ';//页码文字前代码 如:<div>1</div> 中的 <div>
//    var $format_right = '';//页码文字后代码 如:<div>1</div> 中的 </div>
    var $is_ajax = false;//是否支持AJAX分页模式
    var $ajax_action_name = '';//AJAX动作名
    var $ajax_action_obj = '';//ajax局部刷新的对象
    var $pageno  = 1;//当前页
    var $url = "";//url地址头 这样的中url地址: /ucadmin/test/get_url?id=5
    var $is_rewrite = 0;//是否伪静态0$url用/ucadmin/test/get_url?id=5 1用伪静态方式 /ucadmin/test/{page标签}/参数1/参数2
    var $offset = 0;//设置偏移量 每页显示的数量
    
   //方法中会计算出来的值
    var $totalpage = 0;//总页数
    
    //调用分页方法时，可以重新改变的属性
    var $next_page = '>';//下一页
    var $pre_page = '<';//上一页
    var $first_page = 'First';//首页
    var $last_page = 'Last';//尾页
//    var $pre_bar = '<<';//上一分页条
//   var $next_bar = '>>';//下一分页条
    var $pagebarnum = 10;//分页中间显示的页的数量。



    /**
    * constructor构造函数
    * @param array $array 数据数组串
        $array = array(
            'total' => $aa,//总数量
            'perpage' => $aa,//每页显示数量
            'pageno' => $aaa,//当前页数
            'url' => $aa,//url地址 这样的中url地址: /ucadmin/test/get_url?id=5 is_rewrite 为0时可以为空；为1时必须有值
            'is_rewrite' => 0;//是否伪静态0$url用/ucadmin/test/get_url?id=5 1用伪静态方式 /ucadmin/test/{page标签}/参数1/参数2
            'page_name' => $aa,//page标签
            'ajax' => $aaa,// ajax名称
            'ajax_obj' => $aaa,//ajax局部刷新的对象 格式 #div 或 .class
            
        );
    * @param array $array['total'],$array['perpage'],$array['pageno'],$array['url'],$array['ajax']...
    */
    function minupage($array = array())
    {

        if(is_array($array)){//如果是数组
             if(!array_key_exists('total',$array)){
                $this->error(__FUNCTION__,'need a param of total');
             }
             $total = intval($array['total']);
             $perpage = (array_key_exists('perpage',$array))?intval($array['perpage']):10;
             $pageno = (array_key_exists('pageno',$array))?intval($array['pageno']):'';
             $url = (array_key_exists('url',$array))?$array['url']:'';
             $is_rewrite = (array_key_exists('is_rewrite',$array))?$array['is_rewrite']:'';
             $ajax_obj = (array_key_exists('ajax_obj',$array))?$array['ajax_obj']:'';
             
        }else{//是总数量
             $total = $array;
             $perpage = 10;
             $pageno = '';
             $url = '';
             $is_rewrite = 0;
             $ajax_obj = '';
        }
        if((!is_int($total))||($total<0)){
            $this->error(__FUNCTION__,$total.' is not a positive integer!');
        }
        if((!is_int($perpage))||($perpage<=0)){
            $this->error(__FUNCTION__,$perpage.' is not a positive integer!');
        }
        if(!empty($array['page_name'])){
            $this->set('page_name',$array['page_name']);//设置pagename  $this->page_name
        }
        $this->_set_pageno($pageno);//设置当前页  $this->pageno
        $this->_set_rewrite($is_rewrite);//设置rewrite是否伪静态0$url用/ucadmin/test/get_url?id=5 1用伪静态方式 /ucadmin/test/{page标签}/参数1/参数2
        $this->_set_url($url);//设置链接地址 $this->url
        $this->totalpage = ceil($total/$perpage); //总页数
        $this->offset = ($this->pageno-1)*$perpage; //设置偏移量
        if(!empty($array['ajax'])){            
            $this->open_ajax($array['ajax'],$ajax_obj);//打开AJAX模式         $this->is_ajax = true; $this->ajax_action_name = $action;
        }
    }
    /**
    * 设定类中指定变量名的值，如果改变量不属于这个类，将throw一个exception
    * @param string $var
    * @param string $value
    */
    function set($var,$value)
    {
        if(in_array($var,get_object_vars($this)))
             $this->$var = $value;
        else {
           $this->error(__FUNCTION__,$var . " does not belong to PB_Page!");
        }
    }
    /**
    * 通过属性{}，替换成对应的属性值
    * @param string $text
    */
    function replace_pop($text = '')
    {
        $pop_arr = get_object_vars($this);
        foreach($pop_arr as $k => $v){
           $text = str_replace('{' . $k . '}', $v, $text);
        }
        return $text;
    }
    /**
    * 打开倒AJAX模式
    * @param string $action 默认ajax触发的动作。
    * @param string $ajax_obj ajax局部刷新的对象
    */
    function open_ajax($action = '',$ajax_obj = '')
    {
        $this->is_ajax = true;
        $this->ajax_action_name = $action;
        $this->ajax_action_obj = $ajax_obj;//ajax局部刷新的对象
    }
    /**
    * 获取显示"下一页"的代码
    * @param string $next_tag_open 打开标签 如：<div> 
    * @param string $next_tag_close 关闭标签 如：</div>
    * @param string $next_common_style 通常页的样式 样式名称
    * @param string $next_cur_style 是当前页的样式 样式名称
    * @return string
    */
    function next_fun_page($next_tag_open = '',$next_tag_close = '',$next_common_style = '',$next_cur_style = '')
    {
        if($this->pageno < $this->totalpage){//小于总页数
           $style = $next_common_style;
           return $next_tag_open . $this->_get_link($this->_get_url($this->pageno+1),$this->next_page,$style). $next_tag_close;
        }else{//最后一页
            $style = (empty($next_cur_style))?'':'class="' . $next_cur_style . '"';
            return $next_tag_open . '<a ' . $style . ' href="javascript:;">' . $this->next_page . '</a>' . $next_tag_close;
        }
        
        
    }

    /**
    * 获取显示“上一页”的代码
    * @param string $prev_tag_open 打开标签 如：<div>
    * @param string $prev_tag_close 关闭标签 如：</div>
    * @param string $prev_common_style 通常页的样式 样式名称
    * @param string $prev_cur_style 是当前页的样式 样式名称
    * @return string
    */
    function pre_fun_page($prev_tag_open = '',$prev_tag_close = '',$prev_common_style = '',$prev_cur_style = '')
    {
        if($this->pageno > 1){//当前页大于1
           $style = $prev_common_style;
           return $prev_tag_open . $this->_get_link($this->_get_url($this->pageno-1),$this->pre_page,$style) . $prev_tag_close;
        }else{//是第一页的样式
           $style = (empty($prev_cur_style))?'':'class="' . $prev_cur_style . '"';
           return $prev_tag_open . '<a ' . $style . ' href="javascript:;">' . $this->pre_page . '</a>' . $prev_tag_close;
        }
    }

    /**
    * 获取显示“首页”的代码
    * @param string $first_tag_open 打开标签
    * @param string $first_tag_close 关闭标签
    * @param string $first_common_style 通常页的样式 样式名称
    * @param string $first_cur_style 是当前页的样式 样式名称
    * @return string
    */
    function first_fun_page($first_tag_open = '', $first_tag_close = '',$first_common_style = '',$first_cur_style='')
    {
        
        if($this->pageno == 1){//第一页时
            $style = (empty($first_cur_style))?'':'class="' . $first_cur_style . '"';
            return $first_tag_open . '<a ' . $style . ' href="javascript:;">' . $this->first_page . '</a>' . $first_tag_close ;
        }else{
            //$style = (empty($first_common_style))?'':'class="' . $first_common_style . '"';
            $style = $first_common_style;
            return $first_tag_open . $this->_get_link($this->_get_url(1),$this->first_page,$style) . $first_tag_close ;
        }
        
    }

    /**
    * 获取显示“尾页”的代码
    * @param string $last_tag_open 打开标签 如：<div>
    * @param string $last_tag_close 关闭标签 如：</div>
    * @param string $last_common_style 通常页的样式 样式名称
    * @param string $last_cur_style 是当前页的样式 样式名称
    * @return string
    */
    function last_fun_page($last_tag_open = '',$last_tag_close = '',$last_common_style = '',$last_cur_style = '')
    {
        if($this->pageno == $this->totalpage){//最后一页
             $style = (empty($last_cur_style))?'':'class="' . $last_cur_style . '"';
              return $last_tag_open . '<a ' . $style . ' href="javascript:;">' . $this->last_page . '</a>' . $last_tag_close;
        }else{
             $style = $last_common_style;
             return $last_tag_open . $this->_get_link($this->_get_url($this->totalpage),$this->last_page,$style) . $last_tag_close;
        }
        
    }
  /**
    * 获得页码代码
    * @param string $cur_tag_open 打开标签[最外层] 如：<b>
    * @param string $cur_tag_close 关闭标签[最外层] 如：</b>
    * @param string $cur_tagin_open 打开标签[最里层] 如：<b>
    * @param string $cur_tagin_close 关闭标签[最里层] 如：</b>
    * @param string $num_tag_open 打开标签[最外层] 如：<div>
    * @param string $num_tag_close 关闭标签[最外层] 如：</div>
    * @param string $num_tagin_open 打开标签[最里层] 如：<div>
    * @param string $num_tagin_close 关闭标签[最里层] 如：</div>
    * @param string $num_common_style 通常页的样式 样式名称
    * @param string $num_cur_style 是当前页的样式 样式名称
    * @return string
    */
    function nowbar($cur_tag_open = '',$cur_tag_close = '',$cur_tagin_open = '',$cur_tagin_close = '',$num_tag_open = '',$num_tag_close = '',$num_tagin_open = '',$num_tagin_close = '',$num_common_style = '',$num_cur_style = '')
    {
        $plus = ceil($this->pagebarnum / 2);//一半
        //echo $this->pagebarnum;die;
        if($this->pagebarnum - $plus + $this->pageno > $this->totalpage){//当前页加上别一半，是否还大于总页数[已经到最后几页了]
            $plus = $this->pagebarnum-$this->totalpage+$this->pageno;//关多少页能尽量保证每页都能写满全页，除非总页数都不够
        }
        $begin = $this->pageno - $plus + 1;//开始页数量
        $begin = ($begin >= 1)?$begin:1;//防止小于1
        $return = '';
        for($i = $begin;$i < $begin + $this->pagebarnum;$i++)
        {
        	
           if($i <= $this->totalpage){//小于总页数
                if($i != $this->pageno){//不是当前页
                    //$return .= $this->_get_text($this->_get_link($this->_get_url($i),$i,$style));
                    $style = $num_common_style;
                    $return .= $num_tag_open . $this->_get_link($this->_get_url($i),$num_tagin_open . $i . $num_tagin_close,$style) . $num_tag_close;
                    
                }else{//当前页
                    $style = (empty($num_cur_style))?'':'class="' . $num_cur_style . '"';
                    //$return .= $this->_get_text('<a class="' . $pageno_style . '">' . $i . '</a>');
                    $return .= $cur_tag_open . '<a ' . $style . ' href="javascript:;">' . $cur_tagin_open . $i . $cur_tagin_close . '</a>' . $cur_tag_close;

                }
            }else{
                 break;
           }
           $return .= "\n";
        }

        unset($begin);
       
        return $return;
    }
    /**
    * 获取显示跳转按钮的代码
    * @param string $url url地址 /ucadmin/test/get_url?id=aa&page=
    * @param int $type $url为空时的类型0自动为属性url值，1就为空
    * @param string $sel_tag_open 打开标签[最外层] 如：<div>
    * @param string $sel_tag_close 关闭标签[最外层] 如：</div>
    * @param string $sel_tagin_open 打开标签[最里层] 如：<div>
    * @param string $sel_tagin_close 关闭标签[最里层] 如：</div>
    * @param string $sel_common_style 通常页的样式 样式名称
    * 
    * @return string
    */
    function select($url = '',$type = 0,$sel_tag_open = '',$sel_tag_close = '',$sel_tagin_open = '',$sel_tagin_close = '',$sel_common_style = '')
    {
        $style = (empty($sel_common_style))?'':'class="' . $sel_common_style . '"';
        $return = '<select onchange="' . $this->_get_goto_link($url,$type) . '" ' . $style . '>';
        for($i = 1;$i <= $this->totalpage;$i++)
        {
           if($i == $this->pageno){//当前页
              //$return .= '<option value=' . $url . $i . ' selected>' . $i . '</option>';
              $return .= '<option value=' . $i  .' selected>' . $sel_tagin_open . $i . $sel_tagin_close . '</option>';
           }else{//非当前页
              //$return .= '<option value=' . $url . $i . '>' . $i . '</option>';
              $return .= '<option value=' . $i . '>' . $sel_tagin_open . $i . $sel_tagin_close . '</option>';
           }
        }
        unset($i);
        $return .= '</select>';
        
        return $sel_tag_open . $return . $sel_tag_close;
    }
    /**
    * 获取显示输入框的代码
    * @param string $url url地址 /ucadmin/test/get_url?id=aa&page=
    * @param int $type $url为空时的类型0自动为属性url值，1就为空
    * @param string $input_tag_open 打开标签[最外层] 如：<div>
    * @param string $input_tag_close 关闭标签[最外层] 如：</div>
    * @param string $input_common_style 通常页的样式 样式名称
    * @return string
    */
    function inputtxt($url = '' , $type = 0,$input_tag_open = '',$input_tag_close = '',$input_common_style = '')
    {
       $style = (empty($input_common_style))?'':'class="' . $input_common_style . '"';
       $keyup_txt= "javascript:this.value=this.value.replace(/[^\d]/g,'');if(this.value != ''){if(this.value < 1){this.value = 1;}if(this.value > " . $this->totalpage . "){this.value = " . $this->totalpage . ";}}";
       $return = '<input type="text" onchange="if(this.value != \'\'){' . $this->_get_goto_link($url,$type) . '}"  value="' . $this->pageno . '" onkeyup="' . $keyup_txt .'" onafterpaste="' . $keyup_txt .'" ' . $style . ' />';
       return $input_tag_open . $return . $input_tag_close;
    }
    
 /**
  * 获取显示文本框跳转的代码
  *
  * @return string
  */
// function input() {
//    $return = '&nbsp;&nbsp;跳至<input type="text" id="pageno" size="2" value=""  onkeyup="if(this.value > ' . $this->totalpage . '){ this.value = ' . $this->totalpage . '};if(this.value < 1){this.value=' . $this->pageno . ';}if(event.keyCode==13){document.getElementById(\'pageGo\').click();}" />页';
//    $return .= '<input type="button" id ="pageGo" value="Go" onclick="if(document.getElementById(\'pageno\').value < 1){document.getElementById(\'pageno\').value=' . $this->pageno . ';};window.location.href=( \'' . $this->url . '\'+document.getElementById(\'pageno\').value );"/>';
//    return $return;
// }


    /**
    * 获取mysql 语句中limit需要的值
    * @return string
    */
    function offset()
    {
        return $this->offset;
    }

    /**
    * 控制分页显示风格（你可以增加相应的风格）
    * @param int $mode
    * 风格 1 首页 上一页 1 2 3 ..  下一页 第 下拉框 页
    *     2 首页 上一页 [第1页] 下一页 尾页 第 下拉框 页
    *     3 首页 上一页 下一页 尾页
    *     4 上一页 1 2 3 ..  下一页
    *     5
    *     6  << 1 2 3 ..  >>
    * @param $url 下拉选框时，页数前缀地址/ucadmin/test/get_url?id=5
    * @return string
    */
    function show($mode = 1,$url = '')
    {
        //print_r(get_object_vars($this));
        switch ($mode)
        {
           case '1':
            $this->next_page = '下一页';
            $this->pre_page = '上一页';
            $this->first_page = '首页';
            $this->last_page = '尾页';
            return $this->first_fun_page() . $this->pre_fun_page() . $this->nowbar() . $this->next_fun_page() . $this->last_fun_page() . '第' . $this->select($url,0) . '页' . $this->inputtxt($url,0);
            break;
           case '2':
            $this->next_page = '下一页';
            $this->pre_page = '上一页';
            $this->first_page = '首页';
            $this->last_page = '尾页';
            return $this->first_fun_page() . $this->pre_fun_page() . '[第'. $this->pageno . ' 页]' . $this->next_fun_page() . $this->last_fun_page() . '第 ' . $this->select($url,0) . '页';
            break;
           case '3':
            $this->next_page = '下一页';
            $this->pre_page = '上一页';
            $this->first_page = '首页';
            $this->last_page = '尾页';
            return $this->first_fun_page() . $this->pre_fun_page() . $this->next_fun_page() . $this->last_fun_page();
            break;
           case '4':
            $this->next_page = '下一页';
            $this->pre_page = '上一页';
            $this->first_page = '首页';
            $this->last_page = '尾页';
            return $this->pre_fun_page() . $this->nowbar() . $this->next_fun_page();
            break;
           case '5':
            return $this->pre_bar() . $this->pre_fun_page() . $this->nowbar() . $this->next_fun_page() . $this->next_bar();
            break;
           case '6':
            $this->next_page = '>>';
            $this->pre_page = '<<';
            Return $this->pre_fun_page() . $this->nowbar() . $this->next_fun_page();
            break;	
        }

    }
  /**
    * 控制分页显示风格（你可以增加相应的风格;可以在内容中加{属性名}，会自动替换成对应的属性值
    * @param array  $param_arr 参数数组
        $param_arr = array(
            //添加封装标签
            //整个分页周围围绕一些标签
            'full_tag_open' => '',//左侧 如：<p>
            'full_tag_close' => '',//右侧 如：</p>
            //自定义起始链接[首页]
            'first_link' => '',//你不希望显示，可以把它的值设为 FALSE 如：<< 首页
            'first_tag_open' => '',//打开标签 如：<div>
            'first_tag_close' => '',//关闭标签 如：</div>
            'first_common_style' => '',//通常页的样式 样式名称
            'first_cur_style' => '',//是当前页的样式 样式名称
            //自定义结束链接[尾页]
            'last_link' => '',//如果你不希望显示，可以把它的值设为 FALSE  如：>> 尾页
            'last_tag_open' => '',//打开标签 如：<div>
            'last_tag_close' => '',//关闭标签 如：</div>
            'last_common_style' => '',//通常页的样式 样式名称
            'last_cur_style' => '',//是当前页的样式 样式名称
            //自定义“下一页”链接[下一页]        
            'next_link' => '',//不希望显示，可以把它的值设为 FALSE  如：&gt; 下一页
            'next_tag_open' => '',//打开标签 如：<div>
            'next_tag_close' => '',//关闭标签 如：</div>
            'next_common_style' => '',//通常页的样式 样式名称
            'next_cur_style' => '',//是当前页的样式 样式名称
            //自定义“上一页”链接[上一页]
            'prev_link' => '',//不希望显示，可以把它的值设为 FALSE 如：&lt; 上一页
            'prev_tag_open' => '',//打开标签 如：<div>
            'prev_tag_close' => '',//关闭标签 如：</div>
            'prev_common_style' => '',//通常页的样式 样式名称
            'prev_cur_style' => '',//是当前页的样式 样式名称
            
            //数字
            'display_pages' => '',//FALSE不显示“数字”链接 ；TRUE显示
            //自定义“当前页”链接
            'cur_tag_open' => '',//打开标签[最外层] 如：<b>
            'cur_tag_close' => '',//关闭标签[最外层] 如：</b>
            'cur_tagin_open' => '',//打开标签[最里层] 如：<b>
            'cur_tagin_close' => '',//关闭标签[最里层] 如：</b>
                      
            //自定义“数字”链接
            'num_tag_open' => '',//打开标签[最外层] 如：<div>
            'num_tag_close' => '',//关闭标签[最外层] 如：</div>
            'num_tagin_open' => '',//打开标签[最里层] 如：<div>
            'num_tagin_close' => '',//关闭标签[最里层] 如：</div>
            
            //css样式
            'num_common_style' => '',//通常页的样式 样式名称
            'num_cur_style' => '',//是当前页的样式 样式名称
            
            //下拉框
            'sel_pages' => '',//FALSE不显示“下拉框”链接 ；TRUE显示
            'sel_tag_open' => '',//打开标签[最外层] 如：<div>
            'sel_tag_close' => '',//关闭标签[最外层] 如：</div>
            'sel_tagin_open' => '',//打开标签[最里层] 如：<div>
            'sel_tagin_close' => '',//关闭标签[最里层] 如：</div>
            'sel_common_style' => '',//通常页的样式 样式名称
            
            //输入框
            'input_pages' => '',//FALSE不显示“下拉框”链接 ；TRUE显示
            'input_tag_open' => '',//打开标签[最外层] 如：<div>
            'input_tag_close' => '',//关闭标签[最外层] 如：</div>
            'input_common_style' => '',//通常页的样式 样式名称
        );
    * @param array $whow_arr 显示的顺序数组[通过调整下面模块顺序达到调整顺序的作用，按数组下标从上到下显示]
        $whow_arr = array(
            'first' => $aaa ,//首页
            'last' => $aaa ,//尾页
            'next' => $aaa ,//下一页
            'prev' => $aaa ,//上一页
            'num' => $aaa ,//中间数字
            'sel' => $aaa ,//下拉框
            'input' => $aaa ,//输入框
        );
    * @return string
    */
    function new_show($param_arr = '',$whow_arr = array())
    {

        
        $re_link_txt = '';
        $html_arr = array();
        
        //添加封装标签
        //整个分页周围围绕一些标签
        $full_tag_open = isset($param_arr['full_tag_open'])?$param_arr['full_tag_open']:'';//左侧 如：<p>
        $full_tag_close = isset($param_arr['full_tag_close'])?$param_arr['full_tag_close']:'';//右侧 如：</p>
        
        //$re_link_txt .= $full_tag_open;
        
       // $re_link_txt .= $full_tag_close;
        //自定义起始链接[首页]
        $first_link = isset($param_arr['first_link'])?$param_arr['first_link']:FALSE;//你不希望显示，可以把它的值设为 FALSE 如：<< 首页
        $first_tag_open = isset($param_arr['first_tag_open'])?$param_arr['first_tag_open']:'';//打开标签 如：<div>
        $first_tag_close = isset($param_arr['first_tag_close'])?$param_arr['first_tag_close']:'';//关闭标签 如：</div>
        $first_common_style = isset($param_arr['first_common_style'])?$param_arr['first_common_style']:'';//通常页的样式 样式名称
        $first_cur_style = isset($param_arr['first_cur_style'])?$param_arr['first_cur_style']:'';//是当前页的样式 样式名称

        $first_html = '';
        if($first_link != FALSE){
            $this->first_page = $first_link;//首页
            $first_html = $this->first_fun_page($first_tag_open,$first_tag_close,$first_common_style,$first_cur_style);
        }

        $html_arr['first'] = $first_html;
        
        //自定义结束链接[尾页]
        $last_link = isset($param_arr['last_link'])?$param_arr['last_link']:FALSE;//如果你不希望显示，可以把它的值设为 FALSE  如：>> 尾页
        $last_tag_open = isset($param_arr['last_tag_open'])?$param_arr['last_tag_open']:'';//打开标签 如：<div>
        $last_tag_close = isset($param_arr['last_tag_close'])?$param_arr['last_tag_close']:'';//关闭标签 如：</div>
        $last_common_style = isset($param_arr['last_common_style'])?$param_arr['last_common_style']:'';//通常页的样式 样式名称
        $last_cur_style = isset($param_arr['last_cur_style'])?$param_arr['last_cur_style']:'';//是当前页的样式 样式名称
        
        $last_html = '';
        if($last_link != FALSE){
            $this->last_page = $last_link;//尾页
            $last_html = $this->last_fun_page($last_tag_open,$last_tag_close,$last_common_style,$last_cur_style);
        }
        $html_arr['last'] = $last_html;

        //自定义“下一页”链接[下一页]        
        $next_link = isset($param_arr['next_link'])?$param_arr['next_link']:FALSE;//不希望显示，可以把它的值设为 FALSE  如：&gt; 下一页
        $next_tag_open = isset($param_arr['next_tag_open'])?$param_arr['next_tag_open']:'';//打开标签 如：<div>
        $next_tag_close = isset($param_arr['next_tag_close'])?$param_arr['next_tag_close']:'';//关闭标签 如：</div>
        $next_common_style = isset($param_arr['next_common_style'])?$param_arr['next_common_style']:'';//通常页的样式 样式名称
        $next_cur_style = isset($param_arr['next_cur_style'])?$param_arr['next_cur_style']:'';//是当前页的样式 样式名称
        
        $next_html = '';
        if($next_link != FALSE){
            $this->next_page = $next_link;//下一页
            $next_html = $this->next_fun_page($next_tag_open,$next_tag_close,$next_common_style,$next_cur_style);
        }
        $html_arr['next'] = $next_html;

        
        //自定义“上一页”链接[上一页]
        $prev_link = isset($param_arr['prev_link'])?$param_arr['prev_link']:FALSE;//不希望显示，可以把它的值设为 FALSE 如：&lt; 上一页
        $prev_tag_open = isset($param_arr['prev_tag_open'])?$param_arr['prev_tag_open']:'';//打开标签 如：<div>
        $prev_tag_close = isset($param_arr['prev_tag_close'])?$param_arr['prev_tag_close']:'';//关闭标签 如：</div>
        $prev_common_style = isset($param_arr['prev_common_style'])?$param_arr['prev_common_style']:'';//通常页的样式 样式名称
        $prev_cur_style = isset($param_arr['prev_cur_style'])?$param_arr['prev_cur_style']:'';//是当前页的样式 样式名称

        $prev_html = '';
        if($prev_link != FALSE){
            $this->pre_page = $prev_link;//上一页
            $prev_html = $this->pre_fun_page($prev_tag_open,$prev_tag_close,$prev_common_style,$prev_cur_style);
        }
        $html_arr['prev'] = $prev_html;

        
        //数字
        $display_pages = isset($param_arr['display_pages'])?$param_arr['display_pages']:FALSE;//FALSE不显示“数字”链接 ；TRUE显示
        //自定义“当前页”链接
        $cur_tag_open = isset($param_arr['cur_tag_open'])?$param_arr['cur_tag_open']:'';//打开标签[最外层] 如：<b>
        $cur_tag_close = isset($param_arr['cur_tag_close'])?$param_arr['cur_tag_close']:'';//关闭标签[最外层] 如：</b>
        $cur_tagin_open = isset($param_arr['cur_tagin_open'])?$param_arr['cur_tagin_open']:'';//打开标签[最里层] 如：<b>
        $cur_tagin_close = isset($param_arr['cur_tagin_close'])?$param_arr['cur_tagin_close']:'';//关闭标签[最里层] 如：</b>

        //自定义“数字”链接
        $num_tag_open = isset($param_arr['num_tag_open'])?$param_arr['num_tag_open']:'';//打开标签[最外层] 如：<div>
        $num_tag_close = isset($param_arr['num_tag_close'])?$param_arr['num_tag_close']:'';//关闭标签[最外层] 如：</div>
        $num_tagin_open = isset($param_arr['num_tagin_open'])?$param_arr['num_tagin_open']:'';//打开标签[最里层] 如：<div>
        $num_tagin_close = isset($param_arr['num_tagin_close'])?$param_arr['num_tagin_close']:'';//关闭标签[最里层] 如：</div>

        //css样式
        $num_common_style = isset($param_arr['num_common_style'])?$param_arr['num_common_style']:'';//通常页的样式 样式名称
        $num_cur_style = isset($param_arr['num_cur_style'])?$param_arr['num_cur_style']:'';//是当前页的样式 样式名称
        
        $num_html = '';
        if($display_pages == TRUE){
            $num_html = $this->nowbar($cur_tag_open,$cur_tag_close,$cur_tagin_open,$cur_tagin_close,$num_tag_open,$num_tag_close,$num_tagin_open,$num_tagin_close,$num_common_style,$num_cur_style);
        }
        $html_arr['num'] = $num_html;

        
        //下拉框
        $sel_pages = isset($param_arr['sel_pages'])?$param_arr['sel_pages']:FALSE;//FALSE不显示“下拉框”链接 ；TRUE显示
        $sel_tag_open = isset($param_arr['sel_tag_open'])?$param_arr['sel_tag_open']:'';//打开标签[最外层] 如：<div>
        $sel_tag_close = isset($param_arr['sel_tag_close'])?$param_arr['sel_tag_close']:'';//关闭标签[最外层] 如：</div>
        $sel_tagin_open = isset($param_arr['sel_tagin_open'])?$param_arr['sel_tagin_open']:'';//打开标签[最里层] 如：<div>
        $sel_tagin_close = isset($param_arr['sel_tagin_close'])?$param_arr['sel_tagin_close']:'';//关闭标签[最里层] 如：</div>
        $sel_common_style = isset($param_arr['sel_common_style'])?$param_arr['sel_common_style']:'';//通常页的样式 样式名称
       
        $sel_html = '';
        if($sel_pages == TRUE){
           $sel_html = $this->select('',0,$sel_tag_open,$sel_tag_close,$sel_tagin_open,$sel_tagin_close,$sel_common_style); 
        }
        $html_arr['sel'] = $sel_html;
        

        
        //输入框
        $input_pages = isset($param_arr['input_pages'])?$param_arr['input_pages']:FALSE;//FALSE不显示“下拉框”链接 ；TRUE显示
        $input_tag_open = isset($param_arr['input_tag_open'])?$param_arr['input_tag_open']:'';//打开标签[最外层] 如：<div>
        $input_tag_close = isset($param_arr['input_tag_close'])?$param_arr['input_tag_close']:'';//关闭标签[最外层] 如：</div>
        $input_common_style = isset($param_arr['input_common_style'])?$param_arr['input_common_style']:'';//通常页的样式 样式名称
        
        $input_html = '';
        if($input_pages == TRUE){
            $input_html = $this->inputtxt('',0,$input_tag_open,$input_tag_close,$input_common_style);
        }
        $html_arr['input'] = $input_html;

        
        //$text_nr = isset($param_arr['text_nr'])?$param_arr['text_nr']:FALSE;//需要替换属性的内容
        //$text_html = $this->replace_pop($text_nr);
        $re_link_txt .= $full_tag_open;

        foreach($whow_arr as $v){

            //获得当前值
            $show_text = isset($html_arr[$v])?$html_arr[$v]:'';
            $re_link_txt .= $show_text;
        }
        $re_link_txt .= $full_tag_close;

        $re_link_txt = $this->replace_pop($re_link_txt);//替换内容
         return $re_link_txt;
    }
    
    /*----private function (私有方法)-----*/
    /**
    * 设置rewrite
    * @param: int $is_rewrite  是否伪静态0$url用/ucadmin/test/get_url?id=5 1用伪静态方式 /ucadmin/test/{page标签}/参数1/参数2
    * @return boolean
    */
    function _set_rewrite($is_rewrite = 0)
    {
        $this->is_rewrite = $is_rewrite;
    } 
    
    /**
    * 设置url头地址 url /ucadmin/test/get_url?id=5&page_name= ；注意 最后没有参数值
    * @param: String $url  这样的中url地址: /ucadmin/test/get_url?id=5 ;注意：不能包含页的代码 如page = ''
    * @return boolean
    */
    function _set_url($url = "")
    {
        if($this->is_rewrite == 1){
            $this->url = $url;
        }else{
            if(!empty($url)){//不为空
               //手动设置
               $this->url = $url . ((stristr($url,'?'))?'&':'?') . $this->page_name . "=";
            }else{
                //自动获取
               if(empty($_SERVER['QUERY_STRING'])){//网址参数 id=5
                    //不存在QUERY_STRING时 /ucadmin/test/get_url?id=5
                    $this->url = $_SERVER['REQUEST_URI'] . "?" . $this->page_name . "=";
               }else{
                   //存在参数
                if(stristr($_SERVER['QUERY_STRING'],$this->page_name.'=')){
                    //地址存在页面参数 
                    $this->url = str_replace($this->page_name . '=' . $this->pageno,'',$_SERVER['REQUEST_URI']);
                    $last = $this->url[strlen($this->url)-1];//最后一个字符
                    if($last == '?' || $last == '&' ){//最后面是?或&
                        $this->url .= $this->page_name . "=";
                    }else{
                        $this->url .= '&' . $this->page_name . "=";
                    }
                }else{
                    //不存在页面参数 /ucadmin/test/get_url?id=5
                    $this->url = $_SERVER['REQUEST_URI'] . '&' . $this->page_name . '=';
                }//end if   
               }//end if
            }//end if
        }
    }

    /**
    * 设置当前页面
    * @param int $pageno 当前页
    */
    function _set_pageno($pageno)
    {
        if(empty($pageno)){
           //系统获取  
           if(isset($_GET[$this->page_name])){
            $this->pageno = intval($_GET[$this->page_name]);
           }
        }else{
           //手动设置
           $this->pageno = intval($pageno);
        }
    }

    /**
    * 为指定的页面返回地址值;为地址加上页码
    * @param int $pageno 需要组织的页码
    * @return string $url 返回url /ucadmin/test/get_url?id=5
    */
    function _get_url($pageno = 1)
    {
        $re_url = '';
        if($this->is_rewrite == 1){
            $re_url =  str_replace('{' . $this->page_name . '}',$pageno,$this->url);
        }else{
            $re_url = $this->url . $pageno;
        }
        return $re_url;
    }

    /**
    * 获取分页显示文字，比如说默认情况下_get_text('<a href="">1</a>')将返回[<a href="">1</a>]
    * @param String $str
    * @return string $url
    */
//    function _get_text($str)
//    {
//        return $this->format_left . $str . $this->format_right;
 //   }


    /**
     * 
     * @brief 获取链接地址,链接形式的用此方法
     * @details 
     * @param string $url url地址 链接的url地址 完整的url 
     * @param string $text 显示的文字 页码
     * @param string $style class 样式名称
     * 
     * @return string
     */
    function _get_link($url,$text,$style = ''){
        $style = (empty($style))?'':'class="' . $style . '"';
        if($this->is_ajax){
           //如果是使用AJAX模式
           return '<a ' . $style . ' href="javascript:;' . $this->ajax_action_name . '(\'' . $this->ajax_action_obj . '\',\'' . $url . '\')">' . $text . '</a>';
        }else{
           return '<a ' . $style . ' href="javascript:;"' . $url . '">' . $text . '</a>';
        }
    }
    /**
     * 
     * @brief 获取链接地址,链接形式的用此方法 ；返回 url + 当前页码的全地址
     * @details 
     * @param string $url url地址 /ucadmin/test/get_url?id=aa&page=
     * @param int $type $url为空时的类型0自动为属性url值，1就为空
     * @return string 
     */
    function _get_goto_link($url = '' , $type = 0){
        if($url == ''){
            if($type == 0 || $this->is_rewrite == 1){
               $url = $this->url;
            }
        }
        if($this->is_rewrite == 1){
            $ns_url = str_replace('{' . $this->page_name . '}','\'+ this.value +\'', '\'' . $url . '\'' );
        }else{
            $ns_url = '\'' . $url . '\' + this.value';
        }
        if($this->is_ajax){
           //如果是使用AJAX模式
           return  $this->ajax_action_name . '(\'' . $this->ajax_action_obj . '\',' . $ns_url . ');';
        }else{
           return 'location.href = ' . $ns_url . '';
        }
    }
    /**出错处理方式*/
    function error($function,$errormsg)
    {
         die('Error in file <b>'.__FILE__.'</b> ,Function <b>'.$function.'()</b> :'.$errormsg);
    }
}


