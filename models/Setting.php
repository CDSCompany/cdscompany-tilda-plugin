<?php namespace Cds\Tilda\Models;

use Model;

/**
 * Setting Model
 */
class Setting extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $settingsCode = 'cds_tilda_settings';
    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsFields = 'fields.yaml';

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
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];


}
