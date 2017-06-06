<?php

class Fishpig_Wordpress_Block_Tag_Cloud extends Mage_Core_Block_Template
{
	/**
	 * Retrieve a collection of tags
	 *
	 * @return Fishpig_Wordpress_Model_Mysql4_Post_Tag_Collection
	 */
	public function getTags()
	{
		if (!$this->hasTags()) {
			$this->setTags(false);

			$tags = Mage::getResourceModel('wordpress/post_tag_collection')
				->addTagCloudFilter();

			$tags->load();

			if (count($tags) > 0) {
				$max = 0;
				
				foreach($tags as $tag) {
					$max = $tag->getCount() > $max ? $tag->getCount() : $max;
				}

				$this->setMaximumPopularity($max);
				$this->setTags($tags);
			}
		}

		return $this->getData('tags');
	}
	
	public function getFontSize(Fishpig_Wordpress_Model_Post_Tag $tag)
	{
		$percentage = ($tag->getCount() * 100) / $this->getMaximumPopularity();
		
		foreach(array(25 => 90, 50 => 100, 75 => 120, 90 => 140, 100 => 150) as $percentageLimit => $default) {
			if ($percentage <= $percentageLimit) {
				return $this->_getConfigFontSize($percentage, $default);
			}
		}
		
		return $this->_getConfigFontSize(100, 150);
	}
	
	protected function _getConfigFontSize($percent, $default)
	{
		$key = 'wordpress_blog/tag_cloud/font_size_below_' . $percent;
		
		return Mage::getStoreConfig($key) ? Mage::getStoreConfig($key) : $default;
	}
}
