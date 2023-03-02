<?php
    class Request_ot_model extends Crud {

        public function __construct(){
            parent::__construct('request_ot', 'id');
        }

        public function get_all_ot(){
            $this->db->select('*')
                ->from('users')
                ->join('request_ot','users.id = request_ot.user_id')
                ->order_by("request_ot.date_created", "desc");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_all_my_request_desc($user_id){
            $this->db->select('*')
                ->from('request_ot')
                ->where('user_id='.$user_id)
                ->order_by("date_created", "desc");
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_ot($user_id,$date){
            $where = ['user_id' => $user_id, "date" => $date];
            $this->db->select('*')
                ->from('request_ot')
                ->where($where)
                ->order_by("date_created", "desc");
            $query = $this->db->get();
            return $query->row_array();
        }
    }
?>