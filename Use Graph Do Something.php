<?php
  //Line graph折线图
    require_once '../src/jpgraph.php';
    require_once '../src/jpgraph_line.php';

    $_ga = new Graph(800,400);
    $_type = "textint";
    $_ga->SetScale($_type);

    
    $_mysqli = new mysqli('localhost', 'root', '', 'demo');
    $_mysqli->set_charset('utf8');
    $_sql = "select * from nemo order by id asc";
    $_result = $_mysqli->query($_sql);
    $_fate = array();
    while (!!$_rows = $_result->fetch_array()){
        $_fate[] = $_rows['num'];
    }
    
    
    
    $_ga->title->SetFont(FF_CHINESE);
    $_ga->title->Set("数据分析图例");
//     $_data = array(2,5,60,12,20,30,82,12,8,10,20,43,12);
    
    $_line = new LinePlot($_fate);
    
    $_line->SetLegend('公司年表(2016)');
    
    $_ga->Add($_line);
    
    $_ga->Stroke();
    
    $_ga->Stroke('wait.png');
  

?>


<?php
  //two line双折线图
  
//引入类库
require_once "../src/jpgraph.php";
require_once "../src/jpgraph_line.php";
$data=array(0=>-21,1=>-3,2=>12,3=>19,4=>23,5=>29,6=>30,7=>22,8=>26,9=>18,10=>5,11=>-10);//第一条数据
$data2y=array(0=>3,1=>12,2=>18,3=>30,4=>28,5=>33,6=>43,7=>39,8=>36,9=>29,10=>15,11=>10);//第二条数据
//得到Graph对象
$graph=new Graph(800,400);
//设置X和Y轴样式及Y轴的最大值最小值
$graph->SetScale("textint",-30,50);
//设置右侧Y轴样式及其最大值最小值
$graph->SetY2Scale("int",-30,50);
//设置图像样式，加入阴影
$graph->SetShadow();
//设置图像边界范围
$graph->img->setMargin(40,50,50,40);
//设置标题
$graph->title->SetFont(FF_CHINESE);
$graph->title->Set("一线城市数据分析");
//得到曲线实例
$linePlot=new LinePlot($data);
//得到第二条曲线
$linePlot2y=new LinePlot($data2y);
//将曲线加入到图像中
$graph->Add($linePlot);
$graph->Add($linePlot2y);
//设置三个坐标轴名称
$graph->xaxis->title->Set("Month");
$graph->yaxis->title->Set("beijing");
$graph->y2axis->title->Set("shanghai");
//设置两条曲线的颜色
$linePlot->SetColor("#d94600");
$linePlot2y->SetColor("black");
//设置两条曲线的图例
$linePlot->SetLegend("Beijing");
$linePlot2y->SetLegend("Shanghai");
//设置图例样式
$graph->legend->setlayout(LEGEND_HOR);
$graph->legend->Pos(0.45,0.9,"center","bottom");
//将图像输出到浏览器
$graph->Stroke();

$graph->Stroke('wait.png');
?>



<?php
  //柱形图1
//引入类库
require_once "../src/jpgraph.php";
require_once "../src/jpgraph_bar.php";
//柱形图模拟数据
$data=array(0=>-21,1=>-3,2=>12,3=>19,4=>23,5=>29,6=>30,7=>22,8=>26,9=>18,10=>5,11=>-10);
//数据提取
// $_mysqli = new mysqli('localhost', 'root', '', 'demo');
// $_mysqli->set_charset('utf8');
// $_sql = "select * from nemo order by id asc";
// $_result = $_mysqli->query($_sql);
// $_fate = array();
// while (!!$_rows = $_result->fetch_array()){
//     $_fate[] = $_rows['num'];
// }
// $_fate[] = -69;
//创建背景图
$graph=new Graph(800,400);
//设置刻度样式
$graph->SetScale("textlin");
//设置边界范围
$graph->img->SetMargin(30,30,80,30);
//设置标题
$graph->title->SetFont(FF_CHINESE);
$graph->title->Set("公司2016财务分析");
//得到柱形图对象
$barPlot=new BarPlot($data);
//设置柱形图图例
$barPlot->SetLegend("公司年度盈亏");
//显示柱形图代表数据的值
$barPlot->value->show();
//将柱形图加入到背景图
$graph->Add($barPlot);
//设置柱形图填充颜色
$barPlot->setfillcolor("#003e3e");
//设置边框颜色
$barPlot->Setcolor("#8080c0");
//将柱形图输出到浏览器
$graph->Stroke();
$graph->Stroke('wait.png');
?>


<?php
  //柱形图2

  <?php // content="text/plain; charset=utf-8"
require_once ('../src/jpgraph.php');
require_once ('../src/jpgraph_bar.php');

$datay=array(12,26,9,17,31);

// Create the graph. 
// One minute timeout for the cached image
// INLINE_NO means don't stream it back to the browser.
$graph = new Graph(800,400,'auto');
$graph->SetScale("textlin");
$graph->img->SetMargin(60,30,20,40);
$graph->yaxis->SetTitleMargin(45);
$graph->yaxis->scale->SetGrace(30);
$graph->SetShadow();

// Turn the tickmarks
$graph->xaxis->SetTickSide(SIDE_DOWN);
$graph->yaxis->SetTickSide(SIDE_LEFT);

// Create a bar pot
$bplot = new BarPlot($datay);

// Create targets for the image maps. One for each column
$targ=array("bar_clsmex1.php#1","bar_clsmex1.php#2","bar_clsmex1.php#3","bar_clsmex1.php#4","bar_clsmex1.php#5","bar_clsmex1.php#6");
$alts=array("val=%d","val=%d","val=%d","val=%d","val=%d","val=%d");
$bplot->SetCSIMTargets($targ,$alts);
$bplot->SetFillColor("orange");

// Use a shadow on the bar graphs (just use the default settings)
$bplot->SetShadow();
$bplot->value->SetFormat(" $ %2.1f",70);
$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,9);
$bplot->value->SetColor("blue");
$bplot->value->Show();

$graph->Add($bplot);

$graph->title->Set("DATA Analysis");
$graph->xaxis->title->Set("Year");
$graph->yaxis->title->Set("Income");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

// Send back the HTML page which will call this script again
// to retrieve the image.
$graph->StrokeCSIM();
$graph->Stroke('wait.png');
?>

<?php
  //饼状图
  
require_once "../src/jpgraph.php";
require_once "../src/jpgraph_pie.php";
require_once "../src/jpgraph_pie3d.php";

//数据提取
$_mysqli = new mysqli('localhost', 'root', '', 'demo');
$_mysqli->set_charset('utf8');
$_sql = "select * from nemo order by id asc";
$_result = $_mysqli->query($_sql);
$_fate = array();
while (!!$_rows = $_result->fetch_array()){
    $_fate[] = $_rows['num'];
}

// $data=array(0=>3.5,1=>4.6,2=>9.1,3=>21.9,4=>42.3,5=>90.7,6=>183.5,7=>127.5,8=>61.4,9=>33.5,10=>11.5,11=>4.4);
//创建画布
$graph=new pieGraph(600,600);
//设置图像边界范围
$graph->img->SetMargin(30,30,80,30);
//设置标题
$graph->title->SetFont(FF_CHINESE);
$graph->title->Set("公司财务份额分析");
//得到3D饼图对象
$piePlot3d=new piePlot3d($_fate);
//设置图例
$piePlot3d->SetLegends(array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec",'13','14','15','16','17','18'));
//设置图例位置
$graph->legend->Pos(0.02,0.16,"right","center");
//将绘制好的3D饼图加入到画布中
$graph->Add($piePlot3d);
//输出
$graph->Stroke();
$graph->Stroke('wait.png');
?>
