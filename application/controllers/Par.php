<?php

class Par extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//model module
		$this->load->model('asset_model');
		$this->load->model('settings_model');
		$this->load->model('employees_model');
		$this->load->helper('date');
		//view module
		 $this->data = array(
            'title' => 'Property Acknowledgement Receipt',
			'purchasesclass' => '',
			'aprclass' => '',
			'prclass' => '',
			'poclass' => '',
			'receiveclass' => '',
			'usersclass' => '',
			'userssubclass' => '',
			'reportsclass' => '',
			'assetmanagementclass' => 'active',
			'recevingassetsclass' => '',
			'assetclass' => '',
			'propertyclass' => '',
			'parclass' => 'active',
			'supplymanagementclass' => '',
			'settingsclass' => '',
			'requisitionclass' => '',
			'equipmentclass' => '',
			'itemsclass' => '',
			'suppliersclass' => '',
			'employeesclass' => '',
			'inventoryclass' => '',
			'subnavtitle' => 'PAR',
			'typeahead' => '1',
			
			);
		//javascript module
		$this->js = array(
            'jsfile' => 'assetmanagement_par.js'
			);
	}
	
	public function index()
	{
		$data = $this->data;
		
		$js = $this->js;

		
		
		//$data['assetlist'] = $this->asset_model->getassetlist();
		//$data['article'] = $this->settings_model->articlelist();
		//$data['classification'] = $this->settings_model->classificationlist();
		$data['parlist'] = $this->asset_model->getparlist();
		$data['employeeslist'] = $this->employees_model->getemployeeslist();
		$this->load->view('inc/header_view');
		$this->load->view('assetmanagement/par_view',$data);
		$this->load->view('inc/footer_view',$js);
		
	}
	
	public function details($id)
	{
		$data = $this->data;
		$js = $this->js;
		$data['parid'] = $id;
		
/*
		$data['assetdetails'] = $this->asset_model->getassetdetails($id);
		$data['article'] = $this->settings_model->articlelist();
		$data['classification'] = $this->settings_model->classificationlist();
		$data['equipments'] = $this->asset_model->getequipment($id);
		$data['supplierlist'] = $this->asset_model->getsupplierlist();
		$data['employeeslist'] = $this->employees_model->getemployeeslist();
		//print_r($data['supplierlist']);
		//display asset
		*/
		//$data['subnavtitle'] =$data['assetdetails']['asset_particulars'];
		
		$data['pardetails'] = $this->asset_model->getpardetails($id);
		$this->load->view('inc/header_view');
		$this->load->view('assetmanagement/pardetails_view',$data);
		$this->load->view('inc/footer_view',$js);
		
	}
	
	
	public function savepar(){
		
		$pardate = $this->input->post('pardate');
		$parno = $this->input->post('parno');
		$eid = $this->input->post('eid');

		$this->asset_model->savepar($pardate,$parno,$eid);
		
		
	}
	public function deleteasset(){
		$assetid = $this->input->post('assetid');
		$this->db->delete('assets', array('assetid' => $assetid));
		
		//get all equipments with the assetid and delete equipmentsdetails
		//$this->db->delete('purchase_receiving_items', array('deliveryid' => $deliveryid));
		
	}
	public function deleteequip(){
		$equipno = $this->input->post('equipno');
		
		
		//update status receiving asset
		
		$this->asset_model->updatereceiving($equipno );
		
		//delete
		$this->db->delete('equipments', array('equipNo' => $equipno));
		$this->db->delete('equipments_details', array('equipNo' => $equipno));
		
		
		
		
		//get all equipments with the assetid and delete equipmentsdetails
		//$this->db->delete('purchase_receiving_items', array('deliveryid' => $deliveryid));
		
	}
	
	
	
	
	public function getesinglequipment($equipno){
		
		$equipment = $this->asset_model->getsingleequipment($equipno);

		echo json_encode($equipment);
	}
	
	
	public function updateproperty(){
		
		$equipno = $this->input->post('equipno');
		$propertyno = $this->input->post('propertyno');
		$particulars = $this->input->post('particulars');
		$dateacquired = $this->input->post('dateacquired');
		$unitprice = $this->input->post('unitprice');
		$accountcode = $this->input->post('accountcode');
		$barcode = $this->input->post('barcode');
		$service = $this->input->post('service');
		$supplierid = $this->input->post('supplierid');
		$inventorytag = $this->input->post('inventorytag');

		$this->asset_model->updateproperty($equipno,$propertyno,$particulars,$dateacquired,$unitprice,$accountcode,$barcode,$service,$supplierid,$inventorytag);
		
		
	}
	public function updateusertab(){
		
		$equipno = $this->input->post('equipno');
		$issuedto = $this->input->post('issuedto');
		$enduser = $this->input->post('enduser');
		

		$this->asset_model->updateusertab($equipno,$issuedto,$enduser);
		
		
	}
	public function updateremarks(){
		
		$equipno = $this->input->post('equipno');
		$whereabout = $this->input->post('whereabout');
		$remarks = $this->input->post('remarks');
		

		$this->asset_model->updateremarks($equipno,$whereabout,$remarks);
		
		
	}
	public function updatepheriperal(){
		
		$equipno = $this->input->post('equipno');
		$processor = $this->input->post('processor');
		$ram = $this->input->post('ram');
		$harddisk = $this->input->post('harddisk');
		$operatingsystem = $this->input->post('operatingsystem');
		$equipmentsn = $this->input->post('equipmentsn');
		$processorsn = $this->input->post('processorsn');
		$monitorsn = $this->input->post('monitorsn');
		$keyboardsn = $this->input->post('keyboardsn');
		$mousesn = $this->input->post('mousesn');
		

		$this->asset_model->updatepheriperal($equipno,$processor,$ram,$harddisk,$operatingsystem,$equipmentsn,$processorsn,$monitorsn,$keyboardsn,$mousesn);
		
		
	}
	

}