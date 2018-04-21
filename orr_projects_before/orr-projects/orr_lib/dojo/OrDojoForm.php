<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrDojoForm
 *
 * @author orr
 */
class OrDojoForm extends OrForm{
    function  __construct($id, $name = "") {
        parent::__construct($id, $name);
    }

    public function get_tag() {
        $id='id="'.$this->OP_[id]->get().'"';
	$name='name="'.$this->OP_[name]->get().'"';
	$action='action="'.$this->OP_[action]->get().'"';
	$method='method="'.$this->OP_[method]->get().'"';
	$body=$this->OP_[body]->get();
        $dojo_property = 'dojoType="dijit.form.Form" encType="multipart/form-data" ';
	$tag = array("<div $dojo_property $action $id $method $name>\n");
        $tag[] ="<script type=\"dojo/method\" event=\"onSubmit\">\n";
        $tag[] = "if (this.validate()) {\n";
        //$tag[] = "return confirm('Form is valid, press OK to submit');\n";
        $tag[] = "} else {\n";
        $tag[] = "return confirm(' เอ๊ะ! คุณมั่นใจว่าตรวจสอบ และไม่ลืมใส่ข้อมูลที่จำเป็นแล้วนะ?');\n";
        //$tag[] = "return false;\n";
        $tag[] = "}\n";
        $tag[] = "return true;\n";
        $tag[] = "</script>\n";
	foreach($body as $val)
	{
		$tag[]=$val."\n";
	}
	$tag[]="</div>";
	return $tag;
    }
}
?>
