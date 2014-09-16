<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class User_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->model ( 'role_model' );
	}
	/**
	 * Gets a user with the provided ID, of list of users if no ID is provided.
	 * 
	 * @param Integer $id
	 */
	public function get_user($id = FALSE) {
		if ($id === FALSE) {
			$query = $this->db->get ( 'user' );
			return $query->result_array ();
		}
		
		$query = $this->db->get_where ( 'user', array ('idUser' => $id ) );
		return $query->row_array ();
	}
	
	/**
	 * Gets user by token (API KEY)
	 * 
	 * @param String $token
	 * @return Users with the provided token or FALSE if the provided token is invalid or is empty
	 */
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
	
	/**
	 * Checks if the user with the provided ID is admin
	 * 
	 * @param Integer $user_id
	 * @return true if the user is admin, false otherwise.
	 */
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