<?php namespace Cds\Tilda\Components;

use Cms\Classes\ComponentBase;
use Cds\Tilda\Models\TildaPage;

class TildaPagesDetail extends ComponentBase
{
	public $css = [];
	public $js = [];
	public $html = '';
	public $page_id = 0;
    public function componentDetails()
    {
        return [
            'name'        => 'Tilda Pages Detail for list',
            'description' => ''
        ];
    }

    public function defineProperties()
    {
        return [
	        'tildaSlugPage' => [
	             'title'             => 'Write slug parametr',
	             'description'       => '',
	             'default'           => '{{ :slug }}',
	             'type'              => 'string',
	        ]	        
        ];
    }
    
    public function onRun() {
		$pageSlug = $this->property("tildaSlugPage");
		if ($pageSlug) {
			$tildaPage = TildaPage::where("slug", $pageSlug)->where("is_active", true)->get()->first();
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
