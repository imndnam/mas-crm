<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once dirname(__FILE__) . '/Viewer.php';

class Ncrm_PDF_PagerViewer extends Ncrm_PDF_Viewer {
	
	protected $model;

	function setModel($m) {
		$this->model = $m;
	}

	function totalHeight($parent) {
		return 10;
	}

	function initDisplay($parent) {
	}
	
	function display($parent) {
		$pdf = $parent->getPDF();
		$contentFrame = $parent->getContentFrame();

		$displayFormat = '-%s-';
		if($this->model) {
			$displayFormat = $this->model->get('format', $displayFormat);
		}
		$contentHeight = $pdf->GetStringHeight($displayFormat, $contentFrame->w/2.0);
		$pdf->MultiCell($contentFrame->w/2.0, $contentHeight, sprintf($displayFormat, $pdf->getPage()), 0, 'L', 0, 1,
			$contentFrame->x+$contentFrame->w/2.0, $parent->getTotalHeight());
	}
}

?>