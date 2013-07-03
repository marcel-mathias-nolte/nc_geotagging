<?php 

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package   NC Geotagging
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2013
 * @website   https://www.noltecomputer.com
 * @credits   Helmut Schottmüller <typolight@aurealis.de>
 * @license   <marcel.nolte@noltecomputer.de> wrote this file. As long as you retain this notice you
 *            can do whatever you want with this stuff. If we meet some day, and you think this stuff 
 *            is worth it, you can buy me a beer in return. Meanwhile you can provide a link to my
 *            homepage, if you want, or send me a postcard. Be creative! Marcel Mathias Nolte
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace NC;

/**
 * Class NcGeoPositionWidget
 *
 * Backend form widget "nc_text_wizard".
 */
class NcGeoPositionWidget extends \Widget
{
	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnAutoGPS = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'autoGPS':
				$this->blnAutoGPS = $varValue ? true : false;
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}

	/**
	 * Do not validate unit fields
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		foreach ($varInput as $k=>$v)
		{
			if ($k != 'address')
			{
				$varInput[$k] = parent::validator(trim($v));
			}
		}
		return $varInput;
	}
	
	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$objTemplate = new \BackendTemplate('be_geocode_widget');
		global $objPage;
		$arrStrip = array();

		// XHTML does not support maxlength
		if ($objPage->outputFormat == 'xhtml')
		{
			$arrStrip[] = 'maxlength';
		}
		$objTemplate->lat = isset($this->varValue['lat']) ? $this->varValue['lat'] : '';
		$objTemplate->lon = isset($this->varValue['lon']) ? $this->varValue['lon'] : '';
		$objTemplate->address = isset($this->varValue['address']) ? $this->varValue['address'] : '';
		$objTemplate->autoGPS = $this->blnAutoGPS;
		$objTemplate->hasErrors = $this->hasErrors();
		$objTemplate->arrErrors = $this->arrErrors;
		$objTemplate->strName = $this->strName;
		$objTemplate->strId = $this->strId;
		$objTemplate->strLabel = $this->strLabel;
		$objTemplate->strClass = 'geo' . (strlen($this->strClass) ? ' ' . $this->strClass : '');
		$objTemplate->strAttributes = $this->getAttributes($arrStrip);
		$objTemplate->mandatory = $this->mandatory;
		$objTemplate->wizard = $this->wizard;
		return $objTemplate->parse();
	}
	
	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generateWithError()
	{
		return $this->generate();
	}
}

?>