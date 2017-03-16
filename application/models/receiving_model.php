<?php


class Receiving_model extends CI_Model
{
	
	public function get()
	{
		$users = $this->db->query("SELECT deliveryid,drno,purchase_po.pono,purchase_apr.aprno, receivedate, (SELECT COUNT(*) FROM purchase_receiving_items WHERE purchase_receiving.deliveryid = purchase_receiving_items.deliveryid AND purchase_receiving_items.status=1) AS updatedcount FROM purchase_receiving LEFT JOIN purchase_apr ON purchase_receiving.aprid = purchase_apr.aprid LEFT JOIN purchase_po ON purchase_receiving.poid = purchase_po.poid");
		return $users->result_array();
		
		
	}
	public function getaprlist()
	{
		$users = $this->db->query("SELECT * from purchase_apr");
		return $users->result_array();
		
		
	}
	public function getpolist()
	{
		$users = $this->db->query("SELECT * from purchase_po");
		return $users->result_array();
		
		
	}
	
	public function getsupplierlist()
	{
		$sql2 = $this->db->query("SELECT * from suppliers");
		return $sql2->result_array();
		
		
	}
	
	public function getdeliverydetails($id)
	{
		$sql = $this->db->query("SELECT * FROM purchase_receiving left join purchase_apr on purchase_receiving.aprid = purchase_apr.aprid left join purchase_po on purchase_receiving.poid = purchase_po.poid left join suppliers on purchase_receiving.supplierid = suppliers.supplierID WHERE deliveryid='$id'");
		$drmain = $sql->result_array();
		return $drmain[0];
	}
	
	public function getpoitems($poid)
	{
		$sql = $this->db->query("SELECT * FROM purchase_po_items where poid='$poid'");
		return $sql->result_array();
		
	}
	
	public function updateunitprice($deliveryitemsid,$unitprice)
	{
				
		$sql = "update purchase_receiving_items set unitcost=".$this->db->escape($unitprice)." where deliveryitemsid=".$this->db->escape($deliveryitemsid)."";
		$this->db->query($sql);

		
	}
	
	public function getitemlist($warehouseid)
	{
		$itemlist = $this->db->query("SELECT * from items where warehouseid=$warehouseid");
		return $itemlist->result_array();
		
		
	}
	public function getitemunitlist($itemid)
	{
		$itemlist = $this->db->query("SELECT * from items where itemNo='$itemid'");
		$singlerow = $itemlist->result_array();
		return $singlerow[0];
		
		
	}
	
	public function checkconvertion($itemid)
	{
		$itemlist = $this->db->query("SELECT count(*) as conversion FROM items_buom where itemNo='$itemid'");
		$counted = $itemlist->result_array();
		return $counted[0]['conversion'];
		
		
	}
	
	public function conversionunit($itemid)
	{
		$itemlist = $this->db->query("SELECT equiv_unit,base_qty,base_unit FROM items_buom where itemNo='$itemid'");
		return $itemlist->result_array();
		
		
	}
	
	public function additem($deliveryid,$drno,$itemid,$itemqty,$itemunit,$unitcost,$warehouseid)
	{
		
		$sql = "INSERT INTO purchase_receiving_items (deliveryid,drno,itemNo,unit,qty,unitcost,warehouseid) VALUES (".$this->db->escape($deliveryid).",".$this->db->escape($drno).",".$this->db->escape($itemid).",".$this->db->escape($itemunit).",".$this->db->escape($itemqty).",".$this->db->escape($unitcost).",".$this->db->escape($warehouseid).")";
		$this->db->query($sql);
		//echo "INSERT INTO purchase_receiving_items (deliveryid,prno,itemNo,unit,qty,unitcost) VALUES (".$this->db->escape($deliveryid).",".$this->db->escape($drno).",".$this->db->escape($itemid).",".$this->db->escape($itemqty).",".$this->db->escape($itemunit).",".$this->db->escape($unitcost).")";
				
		
	}
	
	
	public function get_list_items($id)
	{
		$itemlist = $this->db->query("SELECT * from purchase_receiving_items left join items on purchase_receiving_items.itemNo = items.itemNo where deliveryid='$id'");
		return $itemlist->result_array();
		
		
	}
	
	public function adddelivery($receiveddate,$drno,$aprid,$poid,$supplierid,$invoiceno,$warehouseid)
	{
		
		$sql = "INSERT INTO purchase_receiving(receivedate,drno,aprid,poid,supplierid,invoiceno,warehouseid) VALUES (".$this->db->escape($receiveddate).",".$this->db->escape($drno).",".$this->db->escape($aprid).",".$this->db->escape($poid).",".$this->db->escape($supplierid).",".$this->db->escape($invoiceno).",".$this->db->escape($warehouseid).")";
		$this->db->query($sql);
		
		
		$sqlselect = $this->db->query("SELECT MAX(deliveryid) AS lastid FROM purchase_receiving");
		$lastidinserted = $sqlselect->result_array();
		//echo json_encode($lastidinserted[0]);
		$currentid = $lastidinserted[0]['lastid'];
		echo $currentid;
				
		
	}
	
	public function updatedelivery($receivedate,$drno,$aprid,$poid,$supplierid,$deliveryid,$status,$invoiceno,$warehouseid,$purpose)
	{
		
		$sql = "update purchase_receiving set receivedate=".$this->db->escape($receivedate).",drno=".$this->db->escape($drno).",aprid=".$this->db->escape($aprid).",poid=".$this->db->escape($poid).",supplierid=".$this->db->escape($supplierid).",status=".$this->db->escape($status).",invoiceno=".$this->db->escape($invoiceno).",warehouseid=".$this->db->escape($warehouseid).",purpose=".$this->db->escape($purpose)." where deliveryid=".$this->db->escape($deliveryid)."";
		$this->db->query($sql);
		
		
		
		$sql2 = "update purchase_receiving_items set drno=".$this->db->escape($drno)." where deliveryid=".$this->db->escape($deliveryid)."";
		$this->db->query($sql2);
		
		
		
		//check if status is receive
		
		if($status=="RECEIVED"){
			
		}
		
			//echo "update purchase_receiving set receivedate=".$this->db->escape($receivedate).",drno=".$this->db->escape($drno).",aprid=".$this->db->escape($aprid).",poid=".$this->db->escape($poid).",supplierid=".$this->db->escape($supplierid)." where deliveryid=".$this->db->escape($deliveryid)."";	
		
	}
	public function getwarehouselist()
	{
		$sql = $this->db->query("SELECT * from warehouse");
		return $sql->result_array();
		
		
	}
	
	public function updatedeliveryinventory($deliveryid)
	{
		
		
		$sql = $this->db->query("SELECT * from purchase_receiving_items where deliveryid='$deliveryid' AND status=0");
		$deliveryitems = $sql->result_array();
		
		print_r($deliveryitems);
		
		foreach ($deliveryitems as $del_items):
			$itemno = $del_items['itemNo'];
			$inv_unit = $del_items['unit'];
			$inv_qty = $del_items['qty'];
			//loop items in requisition
			//compare current unit is the same as the item base unit
			$sql2 = $this->db->query("SELECT unit,inventory_qty FROM items WHERE itemNo=$itemno");
			$unitofmeasure_base = $sql2->result_array();
			$baseunit = $unitofmeasure_base[0]['unit'];
			$inventory = $unitofmeasure_base[0]['inventory_qty'];
			//return $unitofmeasure[0];
			if($baseunit == $inv_unit){
				//no conversion of units
				//update items inventory
				$updatedvalue = $inventory + $inv_qty;
				
				
				//update item status
			}else{
				
				//get convertion equivalent
				$sql3 = $this->db->query("SELECT base_qty FROM items_buom where itemNo=$itemno and equiv_unit='$req_unit'");
				$unitofmeasure_equiv = $sql3->result_array();
				
				$equiv_qty = $unitofmeasure_equiv['base_qty'];
				
				$subtotal = $equiv_qty * $inv_qty;
				$updatedvalue = $inventory + $subtotal;

			}
			//update inventory per item
				$sql4 = $this->db->query("UPDATE items set inventory_qty=$updatedvalue where itemNo=$itemno");
				$sql5 = $this->db->query("UPDATE purchase_receiving_items set status=1 where itemNo=$itemno AND deliveryid='$deliveryid'");

			
		endforeach;
		
		
	}
	
	
}

?>