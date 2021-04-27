<?php namespace Cds\Tilda\Components;

use Cms\Classes\ComponentBase;
use Cds\Tilda\Models\TildaPage;

class TildaPageDetail extends ComponentBase
{
	
	public $css = [];
	public $js = [];
	public $html = '';
	public $page_id = 0;
    public function componentDetails()
    {
        return [
            'name'        => 'Show inner tilda page',
            'description' => ''
        ];
    }

    public function defineProperties()
    {
        return [
	        'tildaPageId' => [
	             'title'             => 'Choose tilda page',
	             'description'       => '',
	             'default'           => false,
	             'type'              => 'dropdown',
	        ]	        
        ];
    }
    
	public function getTildaPageIdOptions()
	{
		
		$tildaPages = TildaPage::with("project")->get()->toArray();
		$options = [];
		foreach($tildaPages as $tildaPage) {
			$options[$tildaPage["id"]] = $tildaPage["project"]["title"]."/".$tildaPage["title"];
		}
	    return $options;
	}    
	
	
	public function onRun() {
		$pageId = (int)$this->property("tildaPageId");
		if ($pageId) {
			$tildaPage = TildaPage::where("id", $pageId)->where("is_active", true)->get()->first();
			if (is_object($tildaPage)) {
				$this->page_id = $tildaPage->id;
				$this->html = $tildaPage->getHtml();
				$this->css = $tildaPage->css;
				$this->js = $tildaPage->js;
				
				$this->page->title = $tildaPage->seo_title;
				$this->page->meta_title = $tildaPage->seo_title;
				$this->page->meta_description = $tildaPage->seo_description;
				
			}
		}
	}
}
