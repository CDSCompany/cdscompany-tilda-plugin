<?php namespace Cds\Tilda\Models;

use Model;
use October\Rain\Filesystem\Zip;
/**
 * TildaPage Model
 */
class TildaPage extends Model
{
    use \October\Rain\Database\Traits\Validation;
	
	public $unZipDir = "tildaarchives";
	
    /**
     * @var string The database table used by the model.
     */
    public $table = 'cds_tilda_tilda_pages';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
	    "images",
	    "js",
	    "css"
    ];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'published_tilda_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [
	    "project" => [
            TildaProject::class,
            'otherKey' => 'project_id',
            'key' => 'id'		    
	    ]
    ];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
	    'image'  => 'System\Models\File',
	    'archive'  => 'System\Models\File',
    ];
    public $attachMany = [];
    
    public function getHtml() {
	    
	    $replace_array = [];
	    foreach($this->images as $image) {
		    if ($this->type == 2) {
		    	$this->html = str_replace("images/".$image["to"], $image["from"], $this->html);
		    } else {
			    $this->html = str_replace($image["to"], $image["from"], $this->html);		    
		    }
	    }
	    
	    return $this->html;
    }
    
    public function getTypeOptions() {
	    return [
		    1 => "Active page",
		    2 => "Archive page"
	    ];
    }
    
    public function getTypeTitle() {
	    $values = $this->getTypeOptions();
	    if (isset($values[$this->type])) {
		    return $values[$this->type];
	    } else {
		    return '';
	    }
    }

    public function setUrl($pageName, $controller)
    {
        $params = [
            'id'   => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }
    
    public function beforeSave() {
	    if ($this->type == 2 && is_object($this->archive)) {
		    $extractPath = $this->unZipDir."/".$this->archive->id.'/';
		    $resultExctract = Zip::extract($this->archive->getLocalPath(), storage_path($extractPath));
		    if ($resultExctract) {
			    $ext_path = explode("_", $this->archive->file_name);
			    $inDir = $ext_path[0];
			    
				$extractedPath = $extractPath.$inDir.'/';
				//js
				$arJS = [];
				$pathSuffix = "js/";
				$files = scandir(storage_path($extractedPath.$pathSuffix));
				foreach($files as $file) {
					if ($file!="." && $file!="..") {
						$arJS[] = [
							"from" => "/storage/".$extractedPath.$pathSuffix.$file,
							"to"   => $file	
						];
					}
				}
				$this->js = $arJS;
				//css
				$arCSS = [];
				$arLastCSS = [];
				$pathSuffix = "css/";
				$files = scandir(storage_path($extractedPath.$pathSuffix));
				foreach($files as $file) {
					if ($file!="." && $file!="..") {
						if (strpos($file, "blocks")!==false) {
							$arLastCSS[] = [
								"from" => "/storage/".$extractedPath.$pathSuffix.$file,
								"to"   => $file	
							];							
						} else {
							$arCSS[] = [
								"from" => "/storage/".$extractedPath.$pathSuffix.$file,
								"to"   => $file	
							];
						}
					}
				}
				$arCSS = array_merge($arCSS, $arLastCSS);
				$this->css = $arCSS;
				//images
				$arImages = [];
				$pathSuffix = "images/";
				$files = scandir(storage_path($extractedPath.$pathSuffix));
				foreach($files as $file) {
					if ($file!="." && $file!="..") {
						$arImages[] = [
							"from" => "/storage/".$extractedPath.$pathSuffix.$file,
							"to"   => $file	
						];
					}
				}
				$this->images = $arImages;
				
				
				//file pages
				$pathSuffix = "files/";
				$files = scandir(storage_path($extractedPath.$pathSuffix));
				$countPage = 1;
				foreach($files as $file) {
					if ($file!="." && $file!="..") {
						if ($countPage == 1) {
							if (empty($this->filename)) {
								$this->filename = $file;
							}
							if (empty($this->published_tilda_at)) {
								$this->published_tilda_at = date("Y-m-d H:i:s");
							}
							$this->html = file_get_contents(storage_path($extractedPath.$pathSuffix.$file));
						}
						$countPage++;
					}
				}
		    }
	    }
    }
        
}
