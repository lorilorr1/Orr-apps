<?php
//
//Created on Mar 17, 2007 OrGraph.php
//@version php5
//@author Suchart Bunhachirat
//@copyright Copyright &copy; 2007, orr
//สร้างกราฟโดยใช้ JpGraph
//มี Font Thai FF_TH_Kodchasal FF_TH_Krub FF_TH_Sarabun

class OrGraph extends OrGui {
  //
  //Object ของกราฟ
  //@access public
  
  public $graph =  null;

  //
  //Object Array ของเส้น หรือแท่งกราฟ
  //@access public
  
  public $plot =  array();

  //
  //กำหนดการสร้างกราฟ
  //@param integer $width
  //@param integer $height
  //@return null
  
  function __construct($title, $y_title, $x_title, $width = 300 , $height = 200 , $charset = null)
  {
 		parent :: __construct($charset );
 		 $this->graph = new Graph($width , $height , 'auto');
 		 $this->graph->SetScale( "textlin");
 		 $this->graph->SetFrame(false);
 		 $this->graph->img-> SetMargin(80,120 ,40,120);
 		 $this->graph->SetMarginColor('white');
 		 
 		 $this->graph->title->Set ($title);
 		 $this->graph->title->SetFont(FF_TH_Krub,FS_BOLD,20);
 		 // Setup the tab
		 /*$this->graph->tabtitle->Set($title);
		 $this->graph->tabtitle->SetFont(FF_TH_Krub,FS_BOLD,18);
		 $this->graph->tabtitle->SetColor('darkred','#E1E1FF');*/
 		 
 		 
 		// $this->graph->xaxis-> title->SetFont(FF_TH_Sarabun ,FS_NORMAL, 15);
 		 $this->graph->yaxis->title->Set($y_title);
 		 $this->graph->yaxis->title->SetFont(FF_TH_Kodchasal ,FS_NORMAL,15);
 		 $this->graph->ygrid->SetFill(true,'#EFEFEF@0.5','#BBCCFF@0.5');
 		 
 		 $this->graph->xaxis->SetLabelMargin(15); 
 		 $this->graph->xaxis->title->Set($x_title);
 		 $this->graph->xaxis->title->SetFont(FF_TH_Kodchasal ,FS_NORMAL,15);
 		 $this->graph->xaxis->SetFont(FF_TH_Kodchasal ,FS_NORMAL,12); 
 		 /*$graph->xaxis->SetTickLabels($a);
		$graph->xaxis->SetFont(FF_FONT2); */
 		 $this->graph->legend->SetShadow('gray@0.4',3);
		 $this->graph->legend->SetAbsPos(15,120,'right','bottom');
 		 $this->graph->legend->Pos( 0.05,0.5,"right" ,"center");
  }

  //
  //สร้างกราฟเส้น
  //@param string $id ชื่อเรียก
  //@param array $y_data ชุดข้อมูลของกราฟ
  //@return null
  
  function set_line_plot($id, $y_data, $color, $weight = 2 )
  {
 		$this->plot[$id] = new LinePlot($y_data);
 		$this->plot[$id]->SetColor($color);
 		$this->plot[$id]->SetWeight($weight);
 		$this->plot[$id]->SetLegend($id);
 		$this->plot[$id]->mark->SetType(MARK_IMG_BALL ,'lightblue');
 		$this->graph->Add($this->plot[$id]);
 		return null;
  }

  //
  //แปลง Array ข้อมูลเป็นกราฟ
  
  function set_data_array($data_array)
  {
 		$color_array = array('#00FF00' , '#FF0000' , '#0000FF' , '#FFFF00' ,  '#FF00FF' ,  '#00FFFF' , '#55FF00');
 		foreach ($data_array AS $row_id => $col_array) {
 			$col_data = array();
 			$count_array = count($col_array) - 1;
 			$row_data = $col_array[0];
 			if($row_id == 0){
 				for($i = 1 ; $i < $count_array ; $i++){
 					$x_label[] = $col_array[$i];
 				}
 			}else{
 				for($i = 1 ; $i < $count_array ; $i++){
 					$col_data[] = $col_array[$i];
 				}
 				$this->set_line_plot($row_data , $col_data ,$color_array[$row_id]);
 			}
 		}
 		$this->graph->xaxis->SetLabelAngle(45);
 		$this->graph->xaxis-> SetTickLabels( $x_label);
 		return null;
  }

  function create_image()
  {
 		$this->graph->Stroke($file_name = 'my_graph.png');
 		return null;
  }

  function get_image($file_name = 'my_graph.png')
  {
 		$graph_image = new OrImage($file_name);
 		return $graph_image->get_tag($file_name);
  }

}


?>
