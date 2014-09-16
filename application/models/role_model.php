<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Role_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	/**
	 * Get role from DB by its name
	 * 
	 * @param String $roleName
	 */
	public function getRoleByName($roleName) {
		if (empty ( $roleName )) {
			return FALSE;
		}
		
		$query = $this->db->get_where ( 'role', array ('name' => $roleName ) );
		return $query->row_array ();
	}
	
	/**
	 * Get Admin role ID from DB
	 * 
	 * @return Integer ID of Admin's Role
	 */
	public function getAdminRoleId() {
		$adminRole = $this->getRoleByName ( 'admin' );
		return $adminRole ['idRole'];
	}
}
?>