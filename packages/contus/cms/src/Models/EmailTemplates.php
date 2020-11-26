<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;

class EmailTemplates extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'subject', 'content', 'is_active'];

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving()
    {
        $this->setDynamicSlug('name');
    }

    public function getFormattedCreatedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('M d Y');
    }

    public function EmailTemplatesTranslation()
    {
        return $this->hasMany(EmailTemplatesTranslation::class, 'email_template_id');
    }

    public function getNameAttribute($value)
    {
        $trans = $this->EmailTemplatesTranslation()->where('language_id', $this->fetchLanugageId())->first();
        if (!empty($trans)) {
            return $trans->name;
        }
        return $value;
    }

    public function getSubjectAttribute($value)
    {
        $trans = $this->EmailTemplatesTranslation()->where('language_id', $this->fetchLanugageId())->first();
        if (!empty($trans)) {
            return $trans->subject;
        }
        return $value;
    }

    public function getContentAttribute($value)
    {
        $trans = $this->EmailTemplatesTranslation()->where('language_id', $this->fetchLanugageId())->first();
        if (!empty($trans)) {
            return $trans->content;
        }
        return $value;
    }
}
