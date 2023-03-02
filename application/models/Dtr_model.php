<?php
    class Dtr_model extends Crud {

        public function __construct(){
            parent::__construct('dtr', 'id');
        }

        public function get_all_dtr_desc(){
            $this->db->select('*')
                ->from('users')
                ->join('dtr','users.id = dtr.user_id')
                ->order_by("dtr.date_created", "desc");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_all_my_dtr_desc($user_id){
            $this->db->select('*')
                ->from('dtr')
                ->where('user_id='.$user_id)
                ->order_by("date desc, date_created desc");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_same_date($user_id,$date){
            $where = ['user_id' => $user_id, "date" => $date, "time_out !=" => NULL];
            $this->db->select('*')
                ->from('dtr')
                ->where($where)
                ->order_by("date", "desc");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_hours($user_id,$date){
            $where = ['user_id' => $user_id, "date" => $date];
            $this->db->select('*')
                ->from('dtr')
                ->where($where);
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_all_active_dtr_desc(){
            $this->db->select('*')
                ->from('users')
                ->join('dtr','users.id = dtr.user_id')
                ->where('dtr.time_out ='.NULL)
                ->order_by("dtr.date_created", "desc");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function outstanding_dtr_desc(){
            $where = ["dtr.time_out !=" => NULL, "dtr.paid" => "no"];
            $this->db->select('*')
                ->from('users')
                ->join('dtr','users.id = dtr.user_id')
                ->where($where)
                ->group_by("dtr.user_id, dtr.date")
                ->order_by("dtr.date_created", "desc");
            $query = $this->db->get();
            return $query->result_array();
        }
        
        public function outstanding_dtr_group_desc(){
            $where = ["dtr.time_out !=" => NULL, "dtr.paid" => "no"];
            $this->db->select('*')
                ->from('users')
                ->join('dtr','users.id = dtr.user_id')
                ->where($where)
                ->group_by("dtr.user_id")
                ->order_by("dtr.date_created", "asc");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function check_if_paid($user_id,$date){
            $where = ['user_id' => $user_id, "date" => $date, "paid" => "yes"];
            $this->db->select('*')
                ->from('dtr')
                ->where($where);
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_all_by_group(){
            $this->db->select('*')
                ->from('dtr')
                ->group_by("date, user_id");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_all_by_group_desc(){
            $this->db->select('*')
                ->from('dtr')
                ->group_by("date, user_id")
                ->order_by("date desc");
            $query = $this->db->get();
            return $query->result_array();
        }


    }
?>