<?php

	// Class Name : Database
	// Purpose : perform various database functions
	// Created On : 23-Sep-2016
	class Database{
		private $dblink;
		//to store connection link
		private $tables;
		//list of database tables
		private $fild_list=array();
		// defines list of table fields
		private $data_list=array();
		// defines set of value for fields
		private $extra_param='';
		// to pass additional query parameters
		public $num_rows;
		//stores affected rows from query result
		private $last_qyery;
		// stores the last executed query
		
		// Constructor
		function __construct()#
		{
			//initialise mysql connection
			global $TABLES;
			$this->tables=$TABLES;
			$this->dblink=mysqli_connect(DBHOST,DBU,DBPASS,DBNAME);
			
			if (mysqli_connect_error()) {
				die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
			}

		}

		// Function Name : add_multiple_record
		// Params : $table name of the table as string
		// Purpose : function to insert multiple records to table using single query
		// Access  : Private to class
		private function add_multiple_record($table) 
		{
			
			if(empty($this->fild_list) || empty($this->data_list))
				return false;
			$str_flds=@implode(",",$this->fild_list);
			$this->clean_input_multi();
			foreach($this->data_list as &$values)
				$values = implode("','",$values);
			$sql="insert into ".$this->tables[$table]."(".$str_flds.") values ('".implode("'),('",$this->data_list)."')";
			$this->run_query($sql);
		}

		// Function Name : get_insert_id, private to database class
		// Params :  NA
		// Access : Private to class
		// Purpose : function to get the id of last inserted record
		private function get_insert_id() 
		{
			return mysqli_insert_id($this->dblink);
		}

		// Function Name : addrecord
		// Params : $table, name of the table as string
		// Purpose : general function to add record to given table
		// Access : within the class only
		private function addrecord($table) 
		{
			if(empty($this->fild_list) || empty($this->data_list))
				return false;
			$this->clean_input();
			$str_flds=@implode(",",$this->fild_list);
			$str_data=@implode("','",$this->data_list);
			$sql="insert into ".$this->tables[$table]."(".$str_flds.") values('".$str_data."')";
			$res=$this->run_query($sql);
			
			if($res)
				return $this->get_insert_id(); 
			else 
				return false;
		}

		// Function Name : get_school_record
		// Params : NA
		// Purpose : function to retrieve school data
		// Access : within and outside of class 
		public function get_school_record() 
		{
			$res=$this->get_record('schools');
			return $res;
		}

		// Function Name : get_member_record
		// Params : $check_fld name of the field as string,$fld_value value as string to search within given field
		// Purpose : function to retrieve memeber data
		//  Access : within or outside of class 
		public function get_member_record($check_fld='',$fld_value='') 
		{	
			if(!empty($check_fld) && !empty($fld_value))
				$this->extra_param=" where $check_fld='$fld_value'";
			$res=$this->get_record('members');
			return $res;
		}

		// Function Name : get_record
		// Params : $table,$fldlist='*',$sql=''
		// Purpose : general function to get data based on table and fields or Query
		// Access : within the class 
		private function get_record($table,$fldlist='*',$sql='')  
		{	
			if(empty($sql))
				$sql="select $fldlist from ".$this->tables[$table].$this->extra_param;
			$result=$this->run_query($sql);
			
			if($this->num_rows<1)
				return false;
			$data_array=array();
			$i=0;
			while($row=mysqli_fetch_assoc($result))
			{
				$data_array[$i]=$row;
				$i++;
			}

			return $data_array;
		}

		// Function Name : add_member
		// Params : $member_data array of member data ,$schools array of school ids
		// Purpose : function to add member data
		// Access : within or outside of class 
		public function add_member($member_data,$schools) 
		{
			$this->fild_list=array_keys($member_data);
			$this->data_list=$member_data;
			$usrid=$this->addrecord('members');
			
			if($usrid)
			{
				//add selected schools with user id to the userselected_school db
				$this->fild_list=array("user_id","school_id");
				$this->data_list = array();
				foreach($schools as $scl)
				{
					$this->data_list[]=array($usrid,$scl);
				}

				
				if(is_array($schools))
					$this->add_multiple_record('user_schools');
				return $usrid;
			} 
			else 
				return false;
		}

		// Function Name : get_memberbyschool
		// Params : $school_id, school id as integer
		// Purpose : retrieve member list for selected shool
		// Access : within or outside of class 
		public function get_memberbyschool($school_id='')
		{
			$sql="select m.name, m.email,s.school_name from members_info m, schools s, users_selected_school us where us.user_id = m.id and us.school_id = s.school_id";
			
			if($school_id!='')
				$sql.= " and  us.school_id=$school_id";
			$result=$this->get_record('','',$sql);
			return $result;
		}

		// Function Name : clean_input
		// Params : NA
		// Purpose : Clean the user inputs for mysqli query
		// Access : within the class 
		private function clean_input()
		{
			if(!empty($this->data_list))
				foreach($this->data_list as $key=>$val)
					$this->data_list[$key]=mysqli_real_escape_string($this->dblink,$val);
		}

		// Function Name : clean_input_multi
		// Params : NA
		// Purpose : Clean the user inputs multi-dimensional for mysqli query
		// Access : within the class 
		private function clean_input_multi()
		{
			if(!empty($this->data_list))
				foreach($this->data_list as $values)
				{
					foreach($values as $key=>$val)
						$values[$key]=mysqli_real_escape_string($this->dblink,$val);
				}
		}

		// Function Name : run_query
		// Params : $query full query as string
		// Purpose : Runs Mysqli Queries
		// Access : within the class  
		private function run_query($query)
		{
			if(empty($query))
				return false;
			// add any pre query code here 
			$this->last_qyery=$query;
			$query_result = mysqli_query($this->dblink,$query);
			$this->num_rows=mysqli_affected_rows($this->dblink);
			//add any post query code here#
			$this->extra_param="";
			return $query_result;
		}

	}