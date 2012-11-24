<?php   
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); 

/* Load the Tree Climb Helper and climb the page tree to return the closest ancestor
 * that contains the attribute 'collection_view_type'
 */
$tc = Loader::helper('tree_climb');
$viewtype = $tc->findAttribute('collection_view_type');

/* Load the "Area" from the closest related ancestor and display the contents
 * from that page.  Useful for automatically handling sidebars without
 * duplicating.  Area is only editable on the source page.
 */
?>
<div id="sidebar" class="area-wrapper">
		<?php $a = new Area('Sidebar'); $a->display($tc->findPageWithArea($a)); ?>
</div>
<?

$this->inc('elements/footer.php'); 
