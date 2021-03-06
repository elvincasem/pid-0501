<?php

class Purchaserequest extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//model module
		$this->load->model('purchaserequest_model');
		$this->load->helper('date');
		//view module
		 $this->data = array(
            'title' => 'Purchases',
			'purchasesclass' => 'active',
			'aprclass' => '',
			'prclass' => 'active',
			'poclass' => '',
			'receiveclass' => '',
			'usersclass' => '',
			'userssubclass' => '',
			'reportsclass' => '',
			'assetmanagementclass' => '',
			'recevingassetsclass' => '',
			'assetclass' => '',
			'propertyclass' => '',
			'supplymanagementclass' => '',
			'settingsclass' => '',
			'requisitionclass' => '',
			'equipmentclass' => '',
			'itemsclass' => '',
			'suppliersclass' => '',
			'employeesclass' => '',
			'inventoryclass' => '',
			'subnavtitle' => 'Purchase Request',
			'typeahead' => '1'
			);
		//javascript module
		$this->js = array(
            'jsfile' => 'purchases_pr.js'
			);
	}
	
	public function index()
	{
		$data = $this->data;
		$js = $this->js;
		//show apr list
		$data['pr_list'] = $this->purchaserequest_model->get();
		$data['apr_list'] = $this->purchaserequest_model->getaprlist();
		//display apr
		$this->load->view('inc/header_view');
		$this->load->view('purchases/pr_view',$data);
		$this->load->view('inc/footer_view',$js);
		
	}
	
	public function details($id)
	{
		$data = $this->data;
		$js = $this->js;
		$data['prid'] = $id;
		$data['apr_list'] = $this->purchaserequest_model->getaprlist();
		$data['unitofmeasure'] = $this->purchaserequest_model->getunitofmeasure();
		$data['suppliers'] = $this->purchaserequest_model->getsuppliers();
		
		//get canvas suppliers
		$data['canvasslist'] = $this->purchaserequest_model->getcanvasslist($id);
		
		$data['prmaindetails'] = $this->purchaserequest_model->getprmaindetails($id);
		//show apr list
		$data['pr_list_items'] = $this->purchaserequest_model->get_list_items($id);

		$data['get_totalamount'] = $this->purchaserequest_model->gettotalamount($id);
		
		$data['awarded_supplier'] = $this->purchaserequest_model->getawardedsupplier($id);
		$data['suppliers_added'] = $this->purchaserequest_model->getsuppliersadded($id);
		
		$data['getrelatedpo'] = $this->purchaserequest_model->getrelatedpo($id);
		//display apr
		$this->load->view('inc/header_view');
		$this->load->view('purchases/prdetails_view',$data);
		$this->load->view('inc/footer_view',$js);
		
	}
	
	public function savepr(){
		$prdate = $this->input->post('prdate');
		$aprno = $this->input->post('aprno');
		$prno = $this->input->post('prno');
		$department = $this->input->post('department');
		$section = $this->input->post('section');
		$purpose = $this->input->post('purpose');
		$modeofprocurement = $this->input->post('modeofprocurement');
		$this->purchaserequest_model->savepr($prdate,$aprno,$prno,$department,$section,$purpose,$modeofprocurement);
	}
	
	public function saveprdetails(){
		$prid = $this->input->post('prid');
		$department = $this->input->post('department');
		$aprno = $this->input->post('aprno');
		$aprdate = $this->input->post('aprdate');
		$section = $this->input->post('section');
		$prno = $this->input->post('prno');
		$prdate = $this->input->post('prdate');
		$purpose = $this->input->post('purpose');
		$modeofprocurement = $this->input->post('modeofprocurement');
		$this->purchaserequest_model->saveprdetails($prid,$department,$aprno,$aprdate,$section,$prno,$prdate,$purpose,$modeofprocurement);
	}
	
	public function getitemlist(){
		
		$items = $this->purchaserequest_model->getitemlist();
		$tokens = array();
		foreach($items as $items_array){
			$tokens[] = $items_array['itemNo'] . "-". $items_array['description'];
		}
		echo json_encode($tokens);

	}
	
	public function savepritem(){
		$itemid = $this->input->post('itemid');
		$description = $this->input->post('description');
		$unit = $this->input->post('unit');
		$unitcost = $this->input->post('unitcost');
		$prid = $this->input->post('prid');
		$itemqty = $this->input->post('itemqty');
		$this->purchaserequest_model->savepritem($itemid,$prid,$description,$itemqty,$unit,$unitcost);
	}
	
	public function getitemdetails(){
		
		$itemid = $this->input->post('itemid');
		if($itemid==0){
			echo "0";
		}else{
			$items_details = $this->purchaserequest_model->getitemdetails($itemid);
			echo $items_details;
		}
		
				
	}
	
	public function deletepritem(){
		$pritemsid = $this->input->post('pritemsid');
		$this->db->delete('purchase_pr_items', array('pritemsid' => $pritemsid));
		$this->db->delete('purchase_pr_aoc', array('pritemsid' => $pritemsid));
		
		
	}
	public function deletepr(){
		$prid = $this->input->post('prid');
		$this->db->delete('purchase_pr', array('prid' => $prid));
		$this->db->delete('purchase_pr_items', array('prid' => $prid));
		$this->db->delete('purchase_pr_aoc', array('prid' => $prid));
		
	}
	
	public function checkprduplicate(){
		$prno = $this->input->post('prno');
		$duplicatecount = $this->purchaserequest_model->checkprdupliate($prno);
		echo $duplicatecount;
		//echo json_encode($duplicatecount);
		
	}
	
	public function updateunitprice(){
		$pritemsid = $this->input->post('pritemsid');
		$unitprice = $this->input->post('unitprice');
		//$availability = $this->input->post('availability');
		
		$this->purchaserequest_model->updateunitprice($pritemsid,$unitprice);
	}
	
	public function updatecanvassprice(){
		$aocid = $this->input->post('aocid');
		$unitprice = $this->input->post('unitprice');
		//$availability = $this->input->post('availability');
		
		$this->purchaserequest_model->updatecanvassprice($aocid,$unitprice);
	}
	
	public function addsupplier(){
		$supplierid = $this->input->post('supplierid');
		$prid = $this->input->post('prid');
		
		$pr_list_items = $this->purchaserequest_model->get_list_items($prid);
		
		foreach ($pr_list_items as $prlistitems):
			$pritemsid = $prlistitems['pritemsid'];
				$this->purchaserequest_model->addsupplier($supplierid,$prid,$pritemsid);
				//echo "<option value='".$uom['unit_name']."'>".$uom['unit_name']."</option>";
		endforeach;
		
		//$this->purchaserequest_model->addsupplier($supplierid,$prid);
	}
	
	
	public function deletesupplier(){
		$supplierid = $this->input->post('supplierid');
		
		$this->db->delete('purchase_pr_aoc', array('supplierid' => $supplierid));
		
		
		
	}
	public function awardsupplier(){
		$awardedsupplier = $this->input->post('awardedsupplier');
		$awardreason = $this->input->post('awardreason');
		$prid = $this->input->post('prid');
				
		$this->purchaserequest_model->awardsupplier($awardedsupplier,$awardreason,$prid);
	}
	
	
	public function addpo(){
		$podate = $this->input->post('podate');
		$pono = $this->input->post('pono');
		$placeofdelivery = $this->input->post('placeofdelivery');
		$dateofdelivery = $this->input->post('dateofdelivery');
		$deliveryterm = $this->input->post('deliveryterm');
		$paymentterm = $this->input->post('paymentterm');
		$prnomodal = $this->input->post('prnomodal');
		$modeofprocurementpo = $this->input->post('modeofprocurementpo');
		$supplierpo = $this->input->post('supplierpo');
		$prid = $this->input->post('prid');
	
		$lastid = $this->purchaserequest_model->savepo($podate,$prid,$pono,$placeofdelivery,$dateofdelivery,$deliveryterm,$paymentterm,$prnomodal,$modeofprocurementpo,$supplierpo);

		echo $lastid;

		//$currentpoid = $lastid;
		//$data_aocitems = $this->purchaserequest_model->copyaoctopo($currentpoid,$supplierpo,$prid);

		//$this->db->insert_batch('purchase_po_items',$data_aocitems); 
		
	}
	public function checkpono(){
		$pono = $this->input->post('pono');
		$items = $this->purchaserequest_model->checkduplicatepo($pono);
		echo $items;

	}
	
	

}