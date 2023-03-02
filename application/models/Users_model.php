<?php
    class Users_model extends Crud {

        public function __construct(){
            parent::__construct('users', 'id');
        }

        public function get_all_desc_by_id($archive){
            $this->db->select('*')
                ->from('users')
                ->where('archive='.$archive)
                ->order_by("date_created", "desc");
            $query = $this->db->get();
            return $query->result_array();
        }
    }
?>