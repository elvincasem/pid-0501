<?php


class Reports_model extends CI_Model
{

	
	/*public function getyearrecord()
	{
		$sql = $this->db->query("SELECT YEAR(requisition_date) AS reqyear FROM requisition_details GROUP BY reqyear");
		return $sql->result_array();
		
		
	}
	*/
	public function getpr($from,$to)
	{
		$sql = $this->db->query("SELECT aprdate,purchase_apr.aprno,description,purchase_receiving_items.qty,purchase_receiving_items.unitcost,(purchase_receiving_items.unitcost*purchase_receiving_items.qty) AS totalamount,receivedate FROM purchase_receiving_items LEFT JOIN purchase_receiving ON purchase_receiving_items.deliveryid = purchase_receiving.deliveryid LEFT JOIN purchase_apr ON purchase_receiving.aprid = purchase_apr.aprid 
LEFT JOIN items ON purchase_receiving_items.itemNo = items.itemNo
WHERE purchase_receiving.aprid >= '1' AND aprdate BETWEEN '$from' AND '$to'");
		return $sql->result_array();
		
		
	}
	public function getutilization($from,$to)
	{
		$sql = $this->db->query("SELECT description,CONCAT(fname,' ',lname) AS requester,requisition_items.qty,items.unitCost,(qty*unitCost) AS totalcost FROM requisition_items LEFT JOIN items ON requisition_items.itemno=items.itemNo 
LEFT JOIN requisition_details ON requisition_items.reqid = requisition_details.reqid
LEFT JOIN employee ON requisition_details.eid = employee.eid WHERE requisition_details.requisition_date BETWEEN '$from' AND '$to'");
		return $sql->result_array();
		
		
	}
	public function getissued($from,$to)
	{
		$sql = $this->db->query("SELECT requisition_details.requisition_no,requisition_items.itemno,description,requisition_items.unit,requisition_items.qty,items.unitCost,(qty*unitCost) AS totalcost FROM requisition_items LEFT JOIN items ON requisition_items.itemno=items.itemNo 
LEFT JOIN requisition_details ON requisition_items.reqid = requisition_details.reqid
LEFT JOIN employee ON requisition_details.eid = employee.eid WHERE requisition_details.requisition_date BETWEEN '$from' AND '$to'");
		return $sql->result_array();
		
		
	}
	
	
	
}

?>