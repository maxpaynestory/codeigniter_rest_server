<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class User_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->model ( 'role_model' );
	}
	
	public function get_user($id = FALSE) {
		if ($id === FALSE) {
			$query = $this->db->get ( 'user' );
			return $query->result_array ();
		}
		
		$query = $this->db->get_where ( 'user', array ('idUser' => $id ) );
		return $query->row_array ();
	}
	
	public function getUserByToken($token) {
		if (empty ( $token )) {
			return FALSE;
		}
		$query = $this->db->get_where ( 'user', array ('accessToken' => $token ) );
		$result = $query->row_array ();
		if ($query->num_rows () != 1) {
			return FALSE;
		}
		return $result;
	}
	
	public function isAdmin($user_id) {
		$adminRoleId = $this->role_model->getAdminRoleId ();
		$query = $this->db->get_where ( 'user', array ('idUser' => $user_id, 'Role_idRole' => $adminRoleId ) );
		if ($query->num_rows () > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>