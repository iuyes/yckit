<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='list'){
	$this->check_access('link_list');
	$sql="SELECT * FROM ".DB_PREFIX."link ORDER BY link_sort ASC,link_id DESC";
	$result=$this->db->result($sql);
	$array=array();
	if($result>0){
		foreach($result as $row){
			$array[$row['link_id']]['id']=$row['link_id'];
			$array[$row['link_id']]['name']=$row['link_name'];
			$array[$row['link_id']]['text']=$row['link_text'];
			$array[$row['link_id']]['url']=$row['link_url'];
			$array[$row['link_id']]['sort']=$row['link_sort'];
			$array[$row['link_id']]['status']=$row['link_status'];
		}
	}
	$this->template->in('link',$array);
	$this->template->out('link.list.php');
}
if($this->do=='link_add'||$this->do=='link_edit'){
	$array=array();
	$mode='insert';
 	if($this->do=='link_add'){
 		$this->check_access('link_add');
	 	$array['sort']=1;
		$array['status']=1;
 	}else{
 		$this->check_access('link_edit');
 		$mode='update';
	 	$link_id=empty($_GET['link_id'])?0:intval($_GET['link_id']);
		$row=$this->db->row("SELECT * FROM ".DB_PREFIX."link WHERE link_id='".$link_id."'");
		$array['id']=$row['link_id'];
		$array['name']=$row['link_name'];
		$array['url']=$row['link_url'];
		$array['text']=$row['link_text'];
		$array['sort']=$row['link_sort'];
		$array['status']=$row['link_status'];
 	}
	$this->template->in('link',$array);
	$this->template->in('mode',$mode);
	$this->template->out('link.info.php');
}
if($this->do=='link_insert'||$this->do=='link_update'){
	$this->check_access('link_list');
	$link_name=empty($_POST['link_name'])?'':trim(addslashes($_POST['link_name']));
	$link_url=empty($_POST['link_url'])?'':trim(addslashes($_POST['link_url']));
	$link_text=empty($_POST['link_text'])?'':trim(addslashes($_POST['link_text']));
	$link_sort=intval($_POST['link_sort']);
	$link_status=empty($_POST['link_status'])?0:1;
	if($link_name=='')alert('链接名称不能为空');
	if($link_url=='')alert('链接地址不能为空');
	$array=array();
	$array['link_name']=$link_name;
	$array['link_url']=$link_url;
	$array['link_text']=$link_text;
	$array['link_sort']=$link_sort;
	$array['link_status']=$link_status;
	if($this->do=='link_insert'){
		$this->check_access('link_add');
		$this->db->insert(DB_PREFIX."link",$array);
	}else{
		$this->check_access('link_edit');
		$link_id=empty($_POST['link_id'])?0:intval($_POST['link_id']);
		$this->db->update(DB_PREFIX."link",$array,"link_id=$link_id");
	}
	redirect('?action=link&do=list');
}
if($this->do=='link_delete'){
	$this->check_access('link_delete');
	$link_id=empty($_GET['link_id'])?0:intval($_GET['link_id']);
	$this->db->delete(DB_PREFIX."link","link_id=".$link_id."");
	redirect('?action=link&do=list');
}