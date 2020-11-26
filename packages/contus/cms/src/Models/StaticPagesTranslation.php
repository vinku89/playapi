<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;
use Carbon\Carbon;
use Contus\Cms\Models\StaticPages;

class StaticPagesTranslation extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'static_pages_translation';
    protected $primaryKey = 'id';
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'static_page_id', 'language_id', 'title', 'content' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
    }

  
    /**
     * Get the formated updated date
     *
     * @return object
     */
    public function staticPages()
    {
        return  $this->belongTo(StaticPages::class, 'static_page_id');
    }
}
