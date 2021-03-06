<?php
/**
 * Admin Tools - Help Admins In thier Task .
 * 
 * @package blesta
 * @subpackage blesta.plugins.Cloud_Backup
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
class AdminNotes extends AdminUtilsController {

	/**
	 * Pre Action
	 */
	public function preAction() {
		parent::preAction();
		
        // Require login
        $this->requireLogin(); 
		Language::loadLang("notes", null, PLUGINDIR . "admin_utils" . DS . "language" . DS);
		
		// Restore structure view location of the admin portal
		$this->structure->setDefaultView(APPDIR);
		$this->structure->setView(null, $this->orig_structure_view);		
		
		$this->uses(array("admin_utils.UtilNotes")); // Call Notes Model Inside admin_utils 
		
		$this->total_notes = $this->UtilNotes->getNoteListCount() ;
		$this->Tabs = $this->getTabs($current = "notes") ;
		
		$this->NavigationLinks = '
				<div class="links_row">
					<a class="btn_right sticky" href="'. $this->Html->safe($this->base_uri . "plugin/admin_utils/admin_notes/sticky/") .'"><span>'. Language::_("AdminToolsPlugin.notes.sticky", true , $this->total_notes['total_sticky'] ) .' </span></a>
					<a class="btn_right normal" href="'. $this->Html->safe($this->base_uri . "plugin/admin_utils/admin_notes/unsticky/") .'"><span>'. Language::_("AdminToolsPlugin.notes.unsticky" , true , $this->total_notes['total_unsticky'] ) .'</span></a>
					<a class="btn_right notes"  href="'. $this->Html->safe($this->base_uri . "plugin/admin_utils/admin_notes/") .'"><span>'. Language::_("AdminToolsPlugin.notes.total_notes", true , $this->total_notes['total_notes'] ) .'</span></a>
				</div>';
				
		$language = Language::_("AdminToolsPlugin.notes." . Loader::fromCamelCase($this->action ? "page_title.".  $this->action : "page_title") , true);
		$this->structure->set("page_title", $language);					
	} 
	
	
	/**
	 * Returns the view to be rendered when managing this plugin
	 */
    public function index() {	

		$this->set("tabs", $this->Tabs);		
		$this->set("navigationlinks", $this->NavigationLinks);	
		$this->set("notes", $this->UtilNotes->GetNotes());
    }

	/**
	 * Delete Note
	 */
    public function delete() {	
	
			$this->UtilNotes->deleteNote($this->get[0]) ;
			
			if (($errors = $this->UtilNotes->errors()))
				$this->setMessage("error", $errors, false, null, false);
			else
				$this->flashMessage("message", Language::_("AdminToolsPlugin.notes.delete.!success", true), null, false);
		
		$this->redirect($this->base_uri . "plugin/admin_utils/admin_notes/"  );	
    }
	
	/**
	 * Returns Sticky Notes
	 */
    public function sticky() {	

		$this->set("tabs", $this->Tabs);		
		$this->set("navigationlinks", $this->NavigationLinks);	
		$this->set("notes", $this->UtilNotes->getAllStickyNotes());
		$this->set("total_notes", $this->total_notes );	
    }	


	/**
	 * Returns UnSticky Notes
	 */
    public function unsticky() {

	
		$this->set("tabs", $this->Tabs);		
		$this->set("navigationlinks", $this->NavigationLinks);	
		$this->set("notes", $this->UtilNotes->getAllUnStickyNotes());
		$this->set("total_notes", $this->total_notes );	
    }	

	/**
	 * Sets the given note as unstickied
	 *
	 */
    public function unsticknote() {

			$this->UtilNotes->unstickNote($this->get[0]) ;
			
			if (($errors = $this->UtilNotes->errors()))
				$this->setMessage("error", $errors, false, null, false);
			else
				$this->flashMessage("message", Language::_("AdminToolsPlugin.notes.unstick.!success", true), null, false);
		
		$this->redirect( $_SERVER['HTTP_REFERER'] );	
    }	

	/**
	 * Sets the given note as stickied
	 */
    public function sticknote() {

			$this->UtilNotes->stickNote($this->get[0]) ;
			
			if (($errors = $this->UtilNotes->errors()))
				$this->setMessage("error", $errors, false, null, false);
			else
				$this->flashMessage("message", Language::_("AdminToolsPlugin.notes.stick.!success", true), null, false);
		
		$this->redirect( $_SERVER['HTTP_REFERER'] );	
    }		
}

?>