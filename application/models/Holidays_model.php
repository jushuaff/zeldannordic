<?php
	class Holidays_model extends Crud {

        public function __construct(){
            parent::__construct('holidays', 'id');
        }

        public function get_all_sort_date(){
            $this->db->select('*')
                ->from('holidays')
                ->order_by("date", "asc");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_all_by_sort_date(){
            $where = ["fdc_type" => "custom"];
            $this->db->select('*')
                ->from('holidays')
                ->where($where)
                ->order_by("date", "asc");
            $query = $this->db->get();
            return $query->result_array();
        }
	}
?>