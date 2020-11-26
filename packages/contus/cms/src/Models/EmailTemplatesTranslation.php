<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;
use Carbon\Carbon;
use Contus\Cms\Models\EmailTemplates;

class EmailTemplatesTranslation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_templates_translation';

    protected $primaryKey = 'id';

    protected $connection = 'mysql';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email_template_id', 'language_id', 'name', 'subject', 'content'];

    /**
     * funtion to automate operations while Saving
     */
    public function __construct()
    {
        parent::__construct();

    }

    public function emailTemplates()
    {
        return $this->belongsTo(EmailTemplates::class, 'email_template_id');
    }
}
