<?php
namespace Manage\Model;
use Think\Model\ViewModel;
class ManageruserViewModel extends ViewModel {   
	public $viewFields = array(
		'manager'=>array('*','_type'=>'LEFT'), 			
		'role'=>array('id'=>'groupid','groupname','groupdesc','priv','isban','_on'=>'role.id=manager.privgid'),
	
	); 
}
?>