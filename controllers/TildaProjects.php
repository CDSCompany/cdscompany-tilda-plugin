<?php namespace Cds\Tilda\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Cds\Tilda\Models\Setting;
use Cds\Tilda\Models\TildaProject;
use Cds\Tilda\Models\TildaPage;
use Cds\Tilda\Classes\Tilda\Api as TildaApi;
use Redirect;

/**
 * Tilda Projects Back-end Controller
 */
class TildaProjects extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    /**
     * @var string Configuration file for the `FormController` behavior.
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string Configuration file for the `ListController` behavior.
     */
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Cds.Tilda', 'tilda', 'tildaprojects');
    }
    
    public function onSyncTildaProjects() {		
		$api = new TildaApi(Setting::get("public_key"), Setting::get("secret_key"));
	    $arProjects = $api->getProjectsList();
	    
	    foreach($arProjects as $arProject) {
			$obTildaProject = TildaProject::where("tilda_id", $arProject["id"])->get()->first();
			if (is_object($obTildaProject)) {
				$obTildaProject->title = $arProject["title"];
				$obTildaProject->description = $arProject["descr"];
				$obTildaProject->save();
			} else {
				$obTildaProject = new TildaProject;
				$obTildaProject->title = $arProject["title"];
				$obTildaProject->description = $arProject["descr"];
				$obTildaProject->tilda_id = $arProject["id"];
				$obTildaProject->save();				
			}
			
			$arPages = $api->getPagesList($arProject["id"]);
			foreach($arPages as $arPage) {
				
				$arPageData = $api->getPageExport($arPage["id"]);
				
				$obTildaPage = TildaPage::where("tilda_id", $arPage["id"])->get()->first();
				if (is_object($obTildaPage)) {
					$obTildaPage->title = $arPage["title"];
					$obTildaPage->description = $arPage["descr"];
					$obTildaPage->filename = $arPage["filename"];
					$obTildaPage->published_tilda_at = $arPage["published"];
					$obTildaPage->html = $arPageData["html"];
					$obTildaPage->images = $arPageData["images"];
					$obTildaPage->js = $arPageData["js"];
					$obTildaPage->css = $arPageData["css"];
					$obTildaPage->slug = str_slug($arPage["title"], "-");
					
					$obTildaPage->save();
					
				} else {
					$obTildaPage = new TildaPage;
					$obTildaPage->title = $arPage["title"];
					$obTildaPage->description = $arPage["descr"];
					$obTildaPage->filename = $arPage["filename"];
					$obTildaPage->published_tilda_at = $arPage["published"];
					$obTildaPage->tilda_id = $arPage["id"];
					$obTildaPage->project_id = $obTildaProject->id;
					$obTildaPage->html = $arPageData["html"];
					$obTildaPage->images = $arPageData["images"];
					$obTildaPage->js = $arPageData["js"];
					$obTildaPage->css = $arPageData["css"];
					$obTildaPage->slug = str_slug($arPage["title"], "-");
					$obTildaPage->type = 1;
					$obTildaPage->is_active = false;
					
					$obTildaPage->save();
				}
				
				
			}
	    }
		return Redirect::refresh();
    }
}
