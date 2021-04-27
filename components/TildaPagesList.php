<?php namespace Cds\Tilda\Components;

use Cms\Classes\ComponentBase;
use Cds\Tilda\Models\TildaPage;
use Cms\Classes\Page;
class TildaPagesList extends ComponentBase
{
	
	public $tildaPages = [];
	
    public function componentDetails()
    {
        return [
            'name'        => 'Tilda Pages List',
            'description' => 'Show all is active tilda pages'
        ];
    }


    public function defineProperties()
    {
        return [
	        'detailPageCms' => [
	             'title'             => 'Choose detail page CMS',
	             'description'       => '',
	             'default'           => false,
	             'type'              => 'dropdown',
	        ]	        
        ];
    }

    
	public function getDetailPageCmsOptions()
	{
		$pages = Page::sortBy('baseFileName')->lists('title', 'baseFileName');	
		return $pages;
	}   
	
	    
    public function onRun() {
	    $detailPageRouterName = $this->property("detailPageCms");
	    
	    $this->tildaPages = TildaPage::where("is_active", true)->orderBy("created_at", "desc")->get()->each(function($model) use($detailPageRouterName) {
		    if (is_object($model->image)) {
		    	$model->attributes["image_thumb"] = $model->image->getThumb(200,200, ["mode" => "crop"]);
		    }
            $model->setUrl($detailPageRouterName, $this->controller);
	    })->toArray();
	    
    }
}
