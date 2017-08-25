<?php
namespace app\demo\controller;
use PHPExcel_IOFactory;
use PHPExcel;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function asd()
    {
//         $objPHPExcel = new \PHPExcel();
//         $objSheet = $objPHPExcel->getActiveSheet();
//         $objSheet ->setTitle("demo");//可以给sheet设置名称为"demo"
//         $objSheet->setCellValue("A1","姓名")->setCellValue("B1","分数");
//         $objSheet->setCellValue("A2","张三")->setCellValue("B2","100");
//         $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');//生成一个Excel2007文件
//         $objWriter->save('E:/test.xlsx');//保存文件
        
        
        
//         $path = 'E:/';
//         $name = 'word_record'.time();
//         $objPHPExcel = new \PHPExcel();
//         for ($i=1; $i < 3; $i++) {
//             if ($i>1) {
//                 $objPHPExcel->createSheet();
//             }
        
//             $objPHPExcel->setActiveSheetIndex($i-1);//把当前创建的sheet设置为活动sheet
//             $objSheet = $objPHPExcel->getActiveSheet();//获得当前活动Sheet
//             $objSheet->setTitle("user".$i);
        
//             if($i==1){
//                 $db_admin = db('work_record')->where('type','1')->select();
//             }else{
//                 $db_admin = db('work_record')->where('type','2')->select();
//             }
//             $objSheet->setCellValue('A1','id')->setCellValue('B1','user')->setCellValue('C1','name')->setCellValue('D1','password')
//             ->setCellValue('E1','imgpath')->setCellValue('F1','tel')->setCellValue('G1','sex')->setCellValue('H1','age');
//             $j = 2;
//             foreach ($db_admin as $key => $value) {
//                 $objSheet->setCellValue('A'.$j,$value['id'])->setCellValue('B'.$j,$value['id'])->setCellValue('C'.$j,$value['name'])
//                 ->setCellValue('D'.$j,$value['fontname'])
//                 ->setCellValue('E'.$j,$value['name'])->setCellValue('F'.$j,$value['function'])->setCellValue('G'.$j,$value['updatetime'])
//                 ->setCellValue('H'.$j,$value['type']);
//                 $j++;
//             }
//         }
//         $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//         $objWriter->save($path.$name.'.xlsx');
        
     
        $filename = 'think_admin'.time().'.xls';
        $objPHPExcel = new \PHPExcel();
        for ($i=1; $i < 3; $i++) {
            if ($i>1) {
                $objPHPExcel->createSheet();
            }
            $objPHPExcel->setActiveSheetIndex($i-1);//把当前创建的sheet设置为活动sheet
            $objSheet = $objPHPExcel->getActiveSheet();//获得当前活动Sheet
            $objSheet->setTitle("user".$i);
            if($i==1){
                $db_admin = db('work_record')->where('type',1)->select();
            }else{
                $db_admin = db('work_record')->where('type',3)->select();
            }
            $objSheet->setCellValue('A1','id')->setCellValue('B1','name')->setCellValue('C1','fontname')->setCellValue('D1','type')
            ->setCellValue('E1','setother')->setCellValue('F1','function')->setCellValue('G1','updatetime')->setCellValue('H1','successtime');
            $j = 2;
            foreach ($db_admin as $key => $value) {
                $objSheet->setCellValue('A'.$j,$value['id'])->setCellValue('B'.$j,$value['name'])->setCellValue('C'.$j,$value['fontname'])->setCellValue('D'.$j,$value['type'])
                ->setCellValue('E'.$j,$value['setother'])->setCellValue('F'.$j,$value['function'])->setCellValue('G'.$j,$value['updatetime'])->setCellValue('H'.$j,$value['successtime']);
                $j++;
            }
        }
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');//告诉浏览器将要输出excel03文件
        header('Content-Disposition: attachment;filename="'.$filename.'"');//告诉浏览器将输出文件的名称(文件下载)
        header('Cache-Control: max-age=0');//禁止缓存
        $objWriter->save("php://output");
        
        
        
        
        
    }

    
    
}
