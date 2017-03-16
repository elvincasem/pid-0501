<?php


class Asset_model extends CI_Model
{
	
	public function getassetlist()
	{
		$sql = $this->db->query("SELECT *,(SELECT COUNT(*) FROM equipments WHERE equipments.assetid = assets.assetid) AS equipcount FROM assets");
		return $sql->result_array();
		
		
	}

	
	public function addasset($particulars,$article,$classification)
	{
		
		$sql = "INSERT INTO assets (asset_article,asset_particulars,asset_classification) VALUES (".$this->db->escape($article).",".$this->db->escape($particulars).",".$this->db->escape($classification).")";
		$this->db->query($sql);
		
		
	}
	
		
	public function getassetdetails($id)
	{
		$sql = $this->db->query("SELECT * from assets where assetid='$id'");
		$asset_details = $sql->result_array();
		return $asset_details[0];
	}
	
	public function getequipment($id)
	{
		
		$list = $this->db->query("SELECT *,(SELECT CONCAT(fname,' ',lname)FROM employee WHERE employee.eid = equipments.issuedto) AS issuedtoname,(SELECT CONCAT(fname,' ',lname) FROM employee WHERE employee.eid = equipments.enduser) AS endusername FROM equipments LEFT JOIN suppliers ON equipments.supplierid = suppliers.supplierID LEFT JOIN equipments_receiving ON equipments.deliveryid = equipments_receiving.deliveryid WHERE assetid='$id'");
		return $list->result_array();
	}
		
		
	public function getallequipment()
	{
		$sql = $this->db->query("SELECT * FROM equipments LEFT JOIN equipments_receiving ON equipments.deliveryid = equipments_receiving.deliveryid");
		return $sql->result_array();
		
		
	}
	
	public function getsingleequipment($equipno)
	{
		$sql = $this->db->query("SELECT *,(SELECT CONCAT(fname,' ',lname)FROM employee WHERE employee.eid = equipments.issuedto) AS issuedtoname,(SELECT CONCAT(fname,' ',lname) FROM employee WHERE employee.eid = equipments.enduser) AS endusername from equipments left join equipments_details on equipments.equipNo = equipments_details.equipNo left join suppliers on equipments.supplierID = suppliers.supplierID where equipments.equipNo='$equipno'");
		$equipment_details = $sql->result_array();
		return $equipment_details[0];
	}
	
	
	public function updatereceiving($equipno)
	{
		
		$sql = $this->db->query("SELECT * FROM equipments where equipNo='$equipno'");
		$equipment_details = $sql->result_array();
		$equiprow = $equipment_details[0];
		$deliveryid = $equiprow['deliveryid'];
		$assetid = $equiprow['assetid'];

		$sql = "update equipments_receiving_items set status='1' where deliveryid='$deliveryid' AND assetid='$assetid'";
		$this->db->query($sql);
		
			
		
	}
	
	
		public function getsupplierlist()
	{
		$sql2 = $this->db->query("SELECT * from suppliers");
		return $sql2->result_array();
		
		
	}
	
	public function updateproperty($equipno,$propertyno,$particulars,$dateacquired,$unitprice,$accountcode,$barcode,$service,$supplierid,$inventorytag){
		
		$sql = "update equipments set propertyNo=".$this->db->escape($propertyno).",particulars=".$this->db->escape($particulars).",dateacquired=".$this->db->escape($dateacquired).",totalcost=".$this->db->escape($unitprice).",accountcode=".$this->db->escape($accountcode).",service=".$this->db->escape($service).",barcode=".$this->db->escape($barcode).",supplierID=".$this->db->escape($supplierid).",inventorytag=".$this->db->escape($inventorytag)." where equipNo=".$this->db->escape($equipno)."";
		$this->db->query($sql);
	}
	public function updateusertab($equipno,$issuedto,$enduser){
		
		$sql = "update equipments set issuedto=".$this->db->escape($issuedto).",enduser=".$this->db->escape($enduser)." where equipNo=".$this->db->escape($equipno)."";
		$this->db->query($sql);
		
		$text = "Issued to: $issuedto, End User: $enduser";
		$sqltext = "INSERT INTO equipments_log (tab,logdescription) VALUES ('users',".$this->db->escape($text).")";
		$this->db->query($sqltext);
	}
	
	public function updateremarks($equipno,$whereabout,$remarks){
		
		$sql = "update equipments set whereabout=".$this->db->escape($whereabout).",remarks=".$this->db->escape($remarks)." where equipNo=".$this->db->escape($equipno)."";
		$this->db->query($sql);
		
		$text = "Whereabout: $whereabout, Remarks: $remarks";
		$sqltext = "INSERT INTO equipments_log (tab,logdescription) VALUES ('remarks',".$this->db->escape($text).")";
		$this->db->query($sqltext);
	}
	public function updatepheriperal($equipno,$processor,$ram,$harddisk,$operatingsystem,$equipmentsn,$processorsn,$monitorsn,$keyboardsn,$mousesn){
		
		$sql = "update equipments_details set processor=".$this->db->escape($processor).",ram=".$this->db->escape($ram).",hd=".$this->db->escape($harddisk).",operatingsystem=".$this->db->escape($operatingsystem).",equipsn=".$this->db->escape($equipmentsn).",processorsn=".$this->db->escape($processorsn).",monitorsn=".$this->db->escape($monitorsn).",keyboardsn=".$this->db->escape($keyboardsn).",mousesn=".$this->db->escape($mousesn)." where equipNo=".$this->db->escape($equipno)."";
		$this->db->query($sql);
		
		$text = "$processor,$ram,$harddisk,$operatingsystem,$equipmentsn,$processorsn,$monitorsn,$keyboardsn,$mousesn";
		$sqltext = "INSERT INTO equipments_log (tab,logdescription) VALUES ('Pheriperal',".$this->db->escape($text).")";
		$this->db->query($sqltext);
	}
	
		public function getparlist()
	{
		$sql2 = $this->db->query("SELECT equipments_par.parid,pardate,eid,parno FROM equipments_par LEFT JOIN equipments_par_items ON equipments_par.parid = equipments_par_items.parid");
		return $sql2->result_array();
		
		
	}
	
	public function savepar($pardate,$parno,$eid)
	{
		
		$sql = "INSERT INTO equipments_par (parno,pardate,eid) VALUES (".$this->db->escape($parno).",".$this->db->escape($pardate).",".$this->db->escape($eid).")";
		$this->db->query($sql);
		
		$sqlselect = $this->db->query("SELECT MAX(parid) AS lastid FROM equipments_par");
		$lastidinserted = $sqlselect->result_array();
		//echo json_encode($lastidinserted[0]);
		$currentid = $lastidinserted[0]['lastid'];
		echo $currentid;
		
	}
	
	public function getpardetails($parid)
	{
		$sql2 = $this->db->query("SELECT * FROM equipments_par left join employee on equipments_par.eid = employee.eid");
		return $sql2->result_array();
		
		
	}
	
	
}

?>