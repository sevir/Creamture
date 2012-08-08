<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	
	function __construct()
    {
        parent::__construct(); 
		$this->load->database();
		$this->load->library('unit_test');
		$this->load->model("test_model");
	}
	
	function index(){
		/* CREATE */
		$id=$this->test_model->insert(array(
			"name"=>"Test",
			"number"=>123456,
			"date"=>date("Y-m-d")
		));
		$this->unit->run($id, "is_numeric", "Insert record id=$id");
		
		$id2=$this->test_model->save(array(
			"name"=>"Test2",
			"number"=>654321,
			"date"=>date("Y-m-d")
		));
		$this->unit->run($id2, "is_numeric", "Save record id=$id2");
		
		/* READ */
		$result=$this->test_model->find($id);
		$this->unit->run(count($result), 1, "Find record with id=$id",json_encode($result));
		
		$result=$this->test_model->find(array($id,$id2));
		$this->unit->run(count($result), 2, "Find record with id in $id,$id2",json_encode($result));
		
		$result=$this->test_model->find(9999);
		$this->unit->run(count($result), 0, "Not find record with id=9999");
				
		$result=$this->test_model->find_by_name("Test");
		$this->unit->run(count($result), 1, "Find record with name=Test",json_encode($result));
		
		$result=$this->test_model->find_by_name("Tset");
		$this->unit->run(count($result), 0, "Not find record with name=Tset");
		
		$result=$this->test_model->find_where(array("date"=>date("Y-m-d")));
		$this->unit->run(count($result), 2, "Find record with date=".date("Y-m-d"),json_encode($result));	

		$result=$this->test_model->find_where(array("number"=>12));
		$this->unit->run(count($result), 0, "Not find record with number=12");		
		
		$result=$this->test_model->find_all();
		$this->unit->run(count($result), 2, "Find all record",json_encode($result));		
		
		/* UPDATE */
		$result=$this->test_model->update($id,array(
			"name"=>"TestTest"
		));		
		$this->unit->run(TRUE, TRUE, "Update record with id=$id",json_encode($this->test_model->find($id)));

		$result=$this->test_model->update_by_name("TestTest",array(
			"name"=>"Test"
		));		
		$this->unit->run(TRUE, TRUE, "Update record with name=TestTest",json_encode($this->test_model->find_by_name("Test")));	

		
		$result=$this->test_model->update_where(array("date"=>date("Y-m-d")),array(
			"name"=>"TestAll"
		));		
		$this->unit->run(TRUE, TRUE, "Update record with date=".date("Y-m-d"),json_encode($this->test_model->find_by_name("TestAll")));	
		
		$result=$this->test_model->save(array(
			"id"=>$id,
			"name"=>"TestSave"
		));		
		$this->unit->run(TRUE, TRUE, "Save/Update record",json_encode($this->test_model->find($id)));					
		
		/* DELETE */
		$result=$this->test_model->delete($id);
		$this->unit->run($result, TRUE, "Delete record with id=$id",json_encode($this->test_model->find_all()));
		
		$result=$this->test_model->delete_by_name("TestAll");
		$this->unit->run($result, TRUE, "Delete record with name=TestAll",json_encode($this->test_model->find_all()));			
		
		/*RESULT*/
		echo $this->unit->report();
	}			
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */