<?php
	include_once APPPATH . 'helpers/my_publicfun_helper.php';//公用函数 
/**
 *
 * @brief 根据表头数组以网页数据流的形式生成excel2007文件，服务器端不生成文件，只是客户端下载使用
 * @details
 * @param array $data_arr 二维数组，格式为：array(array(),...)
 * @param string $version 03 07  生成版本
 * @param array $file_arr [类型为0时，可以没有值]生成的文件名，可为空，如果文件名为空，则文件名默认按日期的形式生成[注意不为空时，名称不能有扩展名]
        $file_arr = array(
            'file_path' => $aaa,//文件路径，相对于站点目录：形式: 文件夹/../文件夹/
            'file_name' => $aaa,//文件名称,注意没有文件后缀,如aaaa
        );
 * @param int $save_type 保存类型0网页数据流生成，1生成文件并返回路径 站点目录,直接写文件名，如果是全路径：E:\www\..\文件名称
 * @return string  返回文件路径[相对于站点目录],形式 data/.../文件名[有扩展名]
 *
 */
if ( ! function_exists('create_excel'))
{

    function create_excel($data_arr = array(),$version = '03',$file_arr = '',$save_type = 0){
       $in_file_path = '';
       if($save_type == 1){
            $file_path = isset($file_arr['file_path'])?$file_arr['file_path']:'data/';// 'data/';
            $file_name = isset($file_arr['file_name'])?$file_arr['file_name']:'';//'全时aaa';
            if($file_name == ''){
                $file_name = date("Y_m_d",time());
            }
            $in_file_path = './' . $file_path . $file_name;
       }
       $re_filename = export_excel($data_arr,$version,$in_file_path,$save_type);
       return $file_path . $re_filename;  
    }
}
/**
 *
 * @brief 根据表头数组以网页数据流的形式生成excel2007文件，服务器端不生成文件，只是客户端下载使用
 * @details
 * @param array $data_arr 二维数组，格式为：array(array(),...)
 * @param string $version 03 07  生成版本
 * @param string $create_filename 生成的文件名，可为空，如果文件名为空，则文件名默认按日期的形式生成[注意不为空时，名称不能有扩展名]
 * @param int $save_type 保存类型0网页数据流生成，1生成文件并返回路径 站点目录,直接写文件名，如果是全路径：E:\www\..\文件名称
 * @return null  以网页数据流的形式生成excel2007文件,或返回文件的名称，没有路径
 *
 */
if ( ! function_exists('export_excel'))
{

    function export_excel($data_arr = array(),$version = '03',$create_filename = '',$save_type = 0){
        //ini_set("memory_limit", "1024M");
        //ini_set('max_execution_time', -1); 
        //引入需要的phpexcel资源
        include_once APPPATH . 'libraries/PHPExcel.php';
        $expand = 'xls';//03扩展名
        $create_write_text = 'Excel5';//03
        if($version == '07'){
            include_once APPPATH . 'libraries/PHPExcel/Writer/Excel2007.php';
            $expand = 'xlsx';//03扩展名
            $create_write_text = 'Excel2007';
        }else{
            include_once APPPATH . 'libraries/PHPExcel/Writer/Excel5.php';
        }

        include_once APPPATH . 'libraries/PHPExcel/IOFactory.php';//加载工厂类

       //如果文件名为空，则文件名默认按日期的形式生成
        if(bn_is_empty($create_filename))
        {
            //获得当前的日期
            $date = date("Y_m_d",time());
            $fileName = "{$date}.{$expand}";
        }else{
            $fileName = $create_filename . '.' . $expand;
        }
        $re_filename = $fileName;
        //$fileName = iconv('UTF-8', 'GBK', $fileName);//服务器不需要转换


        //创建新的PHPExcel对象
        $objPHPExcel = new PHPExcel();
//        $objProps = $objPHPExcel->getProperties();
//        //设置创建者
//        $objProps->setCreator ( 'XuLulu');
//        //设置最后修改者
//        $objProps->setLastModifiedBy("XuLulu");
//        //描述
//        $objProps->setDescription("摩比班级");
//        //设置标题
//        $objProps->setTitle ( '管理器' );
//        //设置题目
//        $objProps->setSubject("OfficeXLS Test Document, Demo");
//        //设置关键字
//        $objProps->setKeywords ( '管理器' );
//        //设置分类
//        $objProps->setCategory ( "Test");

        $row_num = 1;
        foreach($data_arr as $row_k => $row_v){
            $key = ord("A");
            foreach($row_v as $col_k => $col_v){
                $colum = chr($key);
                $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.$row_num, $col_v);
                $key += 1;
            }
            $row_num += 1;
        }
        //重命名表
        $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $create_write_text);
         if($save_type == 0){ //保存类型0网页数据流生成，1生成文件并返回路径
//            header('Pragma:public');
//            header('Expires:0');
//            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
//            header('Content-Type:application/force-download');
//            header('Content-Type:application/vnd.ms-excel');
//            header('Content-Type:application/octet-stream');
//            header('Content-Type:application/download');
//            header('Content-Disposition:attachment;filename='. $fileName );
//            header('Content-Transfer-Encoding:binary');
            //将输出重定向到一个客户端web浏览器(Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header('Cache-Control: max-age=0');            
            //if(!bn_is_empty($_GET['excel']))
           // {
              //  $objWriter->save('php://output'); //文件通过浏览器下载
           // }else
            //{
            //if(!bn_is_empty($_GET['excel'])){
            //  $objWriter->save('php://output'); //文件通过浏览器下载
            // }else{
             $objWriter->save('php://output');
         }else{
             $objWriter->save($fileName); //脚本方式运行，保存在当前目录
         }
         return get_file_name($re_filename);
       // }

       // }

     }
}
	//导入一个Excel最简单的方法是使用PHPExel的IO Factory，调用PHPExcel_IOFactory类的静态法load，它可以自动识别文档格式，包括Excel2007、Excel2003XML、OOCalcSYLK、Gnumeric、CSV。返回一个PHPExcel的实例。
if ( ! function_exists('getExcelContent'))
{
    function getExcelContent($inputFileName)
    {
        //加载工厂类
        //include_once'PHPExcel/IOFactory.php';
       include_once APPPATH . 'libraries/PHPExcel/IOFactory.php';

        //要读取的xls文件路径
        //$inputFileName = './sampleData/example1.xls';
        /** 用PHPExcel_IOFactory的load方法得到excel操作对象 **/
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        //得到当前活动表格，调用toArray方法，得到表格的二维数组
        $sheetData =$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        return $sheetData;
      }
}
	//导入一个Excel最简单的方法是使用PHPExel的IO Factory，调用PHPExcel_IOFactory类的静态法load，它可以自动识别文档格式，包括Excel2007、Excel2003XML、OOCalcSYLK、Gnumeric、CSV。返回一个PHPExcel的实例。
if ( ! function_exists('getExcelContentaaa'))
{
    function getExcelContentaaa($inputFileName)
   {
        /** 用PHPExcel_IOFactory的load方法得到excel操作对象 **/
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        //得到当前活动表格，调用toArray方法，得到表格的二维数组
        //$sheetData =$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $sheetData =$objPHPExcel->getActiveSheet();
        /*foreach($sheetData as $key => $value)
        {
            foreach($value as $key1 => $value1)
            {
                echo   '$key1=' . $value1 . '   ' ;
            }
            echo '<br/>';
        }
         * 
         */

        //仅仅获得表头的值
        $allCol=PHPExcel_Cell::columnIndexFromString($sheetData->getHighestColumn());     
        $allRow=$sheetData->getHighestRow();        
        for ($row=1;$row<=1;$row++)
        {
            for($col=0; $col<$allCol;$col++) 
            {
              $data = $sheetData->getCellByColumnAndRow($col,$row)->getValue();
              $a[] = $data;
            }
             echo "<br/>";
       }
         print_r($a);
         return $a;
         //exit;
        //以数组的形式得到excel表中所有元素的值
        //print_r($sheetData);echo '<br/>';
      }
} 
if ( ! function_exists('getAllExcelContent'))
{
    //获得CSV格式文件的全部内容，以一维数组的形式
    function getAllExcelContent($inputFileName)
    {
         $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
         $sheetData =$objPHPExcel->getActiveSheet();
         $allCol=PHPExcel_Cell::columnIndexFromString($sheetData->getHighestColumn());     
         $allRow=$sheetData->getHighestRow();        
         for ($row=1;$row<=$allRow;$row++)
         {
              for($col=0; $col<$allCol;$col++) 
              {
                  $data = $sheetData->getCellByColumnAndRow($col,$row)->getValue();
                  $a[] = $data;
               }
              echo "<br/>";
        }
          print_r($a);
          return $a;

          //exit;
         //以数组的形式得到excel表中所有元素的值
         //print_r($sheetData);echo '<br/>';
    }
} 
if ( ! function_exists('analyzeExcel'))
{
     //获得excel的表头值
    function analyzeExcel($file){        
       $PHPExcel = new PHPExcel();
      // $PHPReader = new PHPExcel_Reader_Excel5();   
      // Phpexcel默认为Excel2007
       $PHPReader = new PHPExcel_Reader_Excel2007(); 
       if(!$PHPReader->canRead($file))
      {
         $PHPReader = new PHPExcel_Reader_Excel5();
         if(!$PHPReader->canRead($file))
          {
            echo "no excel";
            return;
           }       
       } 

       $PHPExcel = $PHPReader->load($file);  
       $sheet = $PHPExcel->getActiveSheet();
       $allCol=PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
       //echo $allCol;
       $allRow=$sheet->getHighestRow();
       // echo $allRow;
         for ($row=1;$row<=1;$row++)
         {
              for($col=0; $col<$allCol;$col++) 
             {
                 $data = $sheet->getCellByColumnAndRow($col,$row)->getValue();
                 $a[] = $data;
             }
             echo "<br/>";
         }
         print_r($a);
         return $a;
       }
}   
if ( ! function_exists('analyzeAllExcel'))
{
     //获得整个表中的内容，并以一维数组的形式输出
     function analyzeAllExcel($file){           
         $PHPExcel = new PHPExcel();
         $PHPReader = new PHPExcel_Reader_Excel2007();
         if(!$PHPReader->canRead($file))
         {
           $PHPReader = new PHPExcel_Reader_Excel5();
           if(!$PHPReader->canRead($file))
           {
               echo "no excel";
               return;
           }       
         } 

          $PHPExcel = $PHPReader->load($file);  
          $sheet = $PHPExcel->getActiveSheet();
          $allCol=PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
         // echo $allCol;
          $allRow=$sheet->getHighestRow();
         // echo $allRow;
         // exit;
           for ($row=2;$row<=$allRow;$row++)
           {
                for($col=0; $col<$allCol;$col++) 
               {
                     $data = $sheet->getCellByColumnAndRow($col,$row)->getValue();
                     $a[] = $data;
               }
               echo "<br/>";
           }
           print_r($a);
           return $a;
       }
}  
