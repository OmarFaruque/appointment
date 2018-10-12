<?php
class appointment_category{
 
   /* Object properties */
    public $id;
	public $location_id;
    public $category_title;
	public $position;
	public $business_owner_id;

	 /**
     * create appointment category table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_categories';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `business_owner_id` int(11) NOT NULL,
			  `location_id` int(11) NOT NULL,
			  `category_title` varchar(100) NOT NULL,
			  `position` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	
	dbDelta($sql);     
			}
	} 
	 function readAll($reqpage=''){
		global $wpdb;
		if($reqpage=='Export' && $this->location_id=='All' && $this->location_id!='0'){
		$return = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_categories where business_owner_id=".$this->business_owner_id." ORDER BY position ASC");	
		}else{
		$return = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_categories where location_id='".$this->location_id."' and business_owner_id=".$this->business_owner_id." ORDER BY position ASC");	
		}
		
		return $return;
	}

    
	/**
	* create category
	* @return $return true - on sucess, false - on faliure
	*/
	  function create(){
	 
		  global $wpdb;
		$return = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_categories (id,business_owner_id,location_id,category_title,position) values( '','".$this->business_owner_id."','".$this->location_id."','".$this->category_title."',0)");
		//echo "INSERT INTO ".$wpdb->prefix."apt_categories (id,location_id,category_title,`order`) values( '','".$this->location_id."','".$this->category_title."',0)";
		if($return){
			return true;
		}else{
			return false;
		}
	 
	}
	
	/* Sort Category Position */
	function sort_category_position(){
		global $wpdb;
			 $stmt = $wpdb->query("UPDATE ".$wpdb->prefix."apt_categories set position='".$this->position."' where id='".$this->id."'");
			return $result;	
	}
	
	
	
    /**
	* read category
	* @return $return true - on success, false - on failure
	*/
	function read(){
		global $wpdb;
		$return = $wpdb->get_results("SELECT
						id, category_title
					FROM
						".$wpdb->prefix."apt_categories 
					ORDER BY
						category_title");  
	 
		
			return $return;
	}
	
	
	/**
	* read one category
	*/
	function readOne(){
	 
		global $wpdb;
		$return = $wpdb->get_results("SELECT * FROM	".$wpdb->prefix."apt_categories WHERE id =".$this->id);
	 
		foreach($return as $row){
		$this->category_title = $row->category_title;
		$this->location_id = $row->location_id;
		}
	}
	
	/**
	* read category name by category ID
	* @return $return true - on success, false - on failure
	*/
	function readName(){
			 
		global $wpdb;
		if($this->id!='all'){
			$return = $wpdb->get_results("SELECT category_title FROM  ".$wpdb->prefix."apt_categories  WHERE id =".$this->id." limit 0,1");
				foreach($return as $row){
				$this->category_title = $row->category_title;
				}
		}	
	}
	
	/**
	* Update Category
	* @return true - on success, false- on falure
	*/	
	function update(){
		 global $wpdb;
		 $return = $wpdb->query("UPDATE
					 ".$wpdb->prefix."apt_categories 
				SET
					category_title = '".$this->category_title."'
				WHERE
					id =".$this->id);
	 
		if($return){
			return true;
		} else {
			return false;
		}
	}

	/**
	* Count all category
	* @return $num - number of records
	*/	
	public function countAll(){
		global $wpdb;
		$return = $wpdb->query("SELECT id FROM  ".$wpdb->prefix."apt_categories where location_id='".$this->location_id."'");
	 
		$num = sizeof($return);
	 
		return $num;
	}

	/**
	*Delete Category
	*@return true - sucess, false- faliure
	*/
	function delete(){
		 global $wpdb;
		 $return = $wpdb->query("DELETE FROM  ".$wpdb->prefix."apt_categories  WHERE id =".$this->id);
		
		$result=$return;
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	function delete_cate(){
		 global $wpdb;
		 $return = $wpdb->query("DELETE FROM  ".$wpdb->prefix."apt_categories  WHERE id =".$this->id);
		
		$result=$return;
		if($result){
			return true;
		}else{
			return false;
		}
	}
}
?>