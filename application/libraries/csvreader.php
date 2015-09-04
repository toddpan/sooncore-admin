<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
/** 
* CSVReader Class 
* 
* $Id: csvreader.php 147 2007-07-09 23:12:45Z Pierre-Jean $ 
* 
* Allows to retrieve a CSV file content as a two dimensional array. 
* The first text line shall contains the column names. 
* 
* @author        Pierre-Jean Turpeau 
* @link        http://www.codeigniter.com/wiki/CSVReader 
*/  
class CSVReader {  
  
    var $fields;        /** columns names retrieved after parsing 头列名数组，  如array(Id,Name,Category,Price)
*/  
    var $separator = ',';    /** separator used to explode each line */  
  
    /** 
     * Parse a text containing CSV formatted data. 
     * 
     * @access    public csv内容的字串，转换为记录数组
     * @param    string $p_Text csv内容的字串 ,每列用\n分隔
     * @return    array 记录数组
     */  
    function parse_text($p_Text) {  
        $lines = explode("\n", $p_Text);  
        return $this->parse_lines($lines);  
    }  
  
    /** 
     * Parse a file containing CSV formatted data. 
     * 
     * @access    public 根据csv文件路径返回记录数组
     * @param    string $p_Filepath csv文件路径
     * @return    array 返回记录数组
     */  
    function parse_file($p_Filepath) {

    	//返回行的数组
        $lines = file($p_Filepath);  //file 把整个文件读入一个数组中，这里需要改进
        return $this->parse_lines($lines);  
    }  
    /** 
     * Parse an array of text lines containing CSV formatted data. 
     * 
     * @access    public 根据csv内容，解析成头对应内容的数组
     * @param    array  原csv内容数组
     * @return    array  失败/没有记录返回false,成功/有记录返回记录数组
     */  
    function parse_lines($p_CSVLines) {  
    	
        $content = FALSE;  
        foreach( $p_CSVLines as $line_num => $line ) {  
            //每一行有记录
        	if( $line != '' ) { // skip empty lines  
        		
        		//按符号分成数组
                $elements = explode($this->separator, $line);  
                
                //获得标头
                if( !is_array($content) ) { // the first line contains fields names  
                    $this->fields = $elements;  
                    $content = array();  
                } else {  //获得每一行内容
                    $item = array();  
                    foreach( $this->fields as $id => $field ) {  
                        if( isset($elements[$id]) ) {  
                            $item[$field] = $elements[$id];  
                        }  
                    }  
                    $content[] = $item;  
                }  
            }  
        }  
        return $content;  
    }  
}  