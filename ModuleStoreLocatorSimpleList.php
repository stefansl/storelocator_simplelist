<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  CLICKPRESS Internetagentur <www.clickpress.de>
 * @author     Stefan Schulz-Lauterbach <ssl@clickpress.de>
 * @package    storelocator_simplelist
 * @license    LGPL
 * @filesource
 */


class ModuleStoreLocatorSimpleList extends ModuleStoreLocatorList {


	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_storelocator_simplelist';
	
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate() {

		if( TL_MODE == 'BE' ) {

			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### STORELOCATOR SIMPLE LIST ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}



		return parent::generate();
	}
	
	
	/**
	 * Generate module
	 */
	protected function compile() {

		$this->Template = new FrontendTemplate($this->strTemplate);

        $aCategories = array();
        $aCategories = deserialize($this->storelocator_list_categories);

		$objStores = $this->Database->prepare(" SELECT
		                                            id, name, street, email, url, phone, fax, street, postal, city, country
		                                        FROM
		                                            `tl_storelocator_stores`
		                                        WHERE
		                                            pid IN(?)
		                                        ORDER BY id")
                                    ->execute(implode(',',$aCategories));

		$entries = array();

        $i=0;
        while ($objStores->next())
        {

            // Generate Link
            $link = null;


            if( $this->jumpTo ) {


				$objLink = $this->Database->prepare("SELECT * FROM tl_page WHERE id = ?;")->execute($this->jumpTo);
                $link = $this->generateFrontendUrl(
                    $objLink->fetchAssoc()
                    ,	( !$GLOBALS['TL_CONFIG']['useAutoItem'] ? '/store/' : '/' ).$objStores->id.'-'.standardize($objStores->name . ' ' . $objStores->city)
                );


				//$link = $this->generateFrontendUrl(\PageModel::findByPk($this->jumpTo)->row());
            }

            $entries[$i] =  array(
                'id'        		=>  $objStores->id,
                'name'      		=>  $objStores->name,
                'street'    		=>  $objStores->street,
                'email'     		=>  $objStores->email,
                'url'       		=>  $objStores->url,
                'phone'     		=>  $objStores->phone,
                'fax'       		=>  $objStores->fax,
                'postal'    		=>  $objStores->postal,
                'city'      		=>  $objStores->city,
                'country'   		=>  $objStores->country,
                'opening_times'  	=>  $objStores->opening_times,
                'link'    			=>  $link
            );
            $i++;
        }



		$this->Template->entries	=	$entries;



	}

    protected function getData() {
        $objStores = $this->Database->prepare(" SELECT * FROM `tl_storelocator_stores`")->execute();
        $entries = $objStores->fetchAllAssoc();

        $json   =   json_encode($entries);

    }

}
?>