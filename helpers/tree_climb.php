<?
/* Tree Climb Helper for Concrete5
 *
 * (C) Copyright 2010, Jared Quinn
 * Licensed under a Creative Commons Attribution-ShareAlike 3.0 Unported License.
 *
 */
class TreeClimbHelper {

	protected $page = null;

	protected $attributes = array();

	protected $values = array();

	protected $tree = array();

	public function __construct(Collection $cObj = null) { 
		$this->setCollection($cObj);
	}

	public function setCollection(Collection $cObj = null) {
		$this->_clearCachedValues();
		$this->page = $page ? $page : Page::getCurrentPage();
		$this->_updateTree();
	}

	public function getPageDepth()    { return count($this->tree); }
	public function getPageParent()   { return count($this->tree) > 1 ? $this->tree[1] : false; }
	public function getPageToplevel() { return count($this->tree) > 2 ? $this->tree[( count($this->tree) - 2 )] : false; }


	/* 
	 * Scan ancestors for attribute that contains a value.
	 * Result will be cached for subsequent calls.
	 */
	public function findAttribute($attribute, $topMost = null) {
		$result = null;
		$this->attributes[] = $attribute;
		foreach($this->tree as $t) { 
			if($topMost && $t->getCollectionID() == $topMost) {
				break;
			}
			if(!$result && $t->getAttribute($attribute)) {
				$result = $t->getAttribute($attribute); 
				break;
			}
		}
		return $result;
	}

	/*
	 * Scan ancestors for Areas that contain blocks
	 */
	public function findPageWithArea(Area $area, $force = false) {
		$dp = false;
		foreach($this->tree as $t) {
			$a = new Area($area->getAreaHandle());   // the area obj caches too well!
			if($a->getTotalBlocksInArea($t) > 0) { $dp = $t; break; }
		}

		if($dp && Page::getCurrentPage()->isEditMode() && !$foce) 
		   $dp = Page::getCurrentPage();

		return $dp;
	}

	/*
	 * Create our Array of Page Objects
	 */
	protected function _updateTree() {
		if(!$this->tree) {
			$this->tree = array();
			$this->tree[] = $this->page;

			while(end($this->tree)->getCollectionParentID() > 0) {
				$this->tree[] = Page::getByID( end($this->tree)->getCollectionParentID() );
			}
		}
	}

	/*
	 * Reset the helper object to initial values;
	 */
	protected function _clearCachedValues() {
		$this->page = null;
		$this->attributes = $this->values = $this->tree = array();
	}


}

