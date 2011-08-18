<?php if ( ! defined('BASEPATH')) die('No direct script access allowed');

class Answers_model extends CI_Model {

	function getAllRecords($condition=FALSE)
	{
		if ($condition != FALSE)
		{
			$this->db->where($condition);
		}

		$data = $this->db->get('answers');

		return $data;
	}

	function getSomeRecords($fields,$condition=FALSE)
	{
		foreach ($fields as $field)
		{
			$this->db->select($field);
		}
		if ($condition != FALSE)
		{
			$this->db->where($condition);
		}

		$data = $this->db->get('answers');

		return $data;
	}

    function updateSortOrder($qid,$lang)
    {
        $this->db->select('qid, code, sortorder');
        $this->db->where('qid',$qid);
        $this->db->where('language',$lang);
        $this->db->order_by("sortorder","asc");
        $data = $this->db->get('answers');

		$position=0;

        foreach($data->result_array() as $row)
        {
            $datatoupdate = array('sortorder' => $position);
            $this->db->where('qid',$row['qid']);
            $this->db->where('code',$row['code']);
            $this->db->where('sortorder',$row['sortorder']);
            $this->db->update('answers',$datatoupdate);
            $position++;
        }
    }

    function getAnswerCode($qid,$code,$lang)
    {
        $this->db->select('code, answer');
        $this->db->where('qid',$qid);
        $this->db->where('code',$code);
        $this->db->where('scale_id',0);
        $this->db->where('language',$lang);
        $data = $this->db->get('answers');

		return $data;
    }

	function oldNewInsertansTags($newsid,$oldsid)
	{
		$sql = "SELECT a.qid, a.language, a.code, a.answer from ".$this->db->dbprefix('answers')." as a INNER JOIN ".$this->db->dbprefix('questions')." as b ON a.qid=b.qid WHERE b.sid=".$newsid." AND a.answer LIKE '%{INSERTANS:".$oldsid."X%'";
    	return $this->db->query($sql);
	}

    function getCountOfCode($qid,$language)
    {
        $data = $this->db->query("SELECT count(code) AS codecount FROM ".$this->db->dbprefix('answers')." WHERE qid={$qid} AND language='{$language}'");
        return $data;
    }


	function update($data, $condition=FALSE)
	{

		if ($condition != FALSE)
		{
			$this->db->where($condition);
		}

		$this->db->update('answers', $data);

	}

    function insertRecords($data)
    {

        return $this->db->insert('answers',$data);
    }



}