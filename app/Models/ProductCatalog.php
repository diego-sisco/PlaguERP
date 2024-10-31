<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCatalog extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'product_catalog';

    protected $fillable = [
        'id',
        'economic_id',
        'biocide_id',
        'purpose_id',
        'linebusiness_id',
        'presentation_id',
        'toxicity_categ_id',
        'files_id',
        'image_path',
        'name',
        'business_name',
        'bar_code',
        'description',
        'execution_indications',
        'manufacturer',
        'metric_id',
        'register_number',
        'validity_date',
        'active_ingredient',
        'per_active_ingredient',
        'dosage',
        'safety_period',
        'residual_effect',
        'is_obsolete',
        'is_toxic',
    ];

    public function lineBusiness() {
        return $this->belongsTo(LineBusiness::class,'linebusiness_id');
    }

    public function purpose() { 
        return $this->belongsTo(Purpose::class,'purpose_id');
    }

    public function economicData() {
        return $this->belongsTo(EconomicDataProduct::class, 'economic_data_id');
    }

    public function applicationMethod() {
        return $this->belongsTo(ApplicationMethod::class, 'application_method_id');
    }

    public function applicationMethods() {
        return $this->hasManyThrough(
            ApplicationMethod::class,        // El modelo intermedio (Method)
            Dosage::class,        // El modelo final al que deseas acceder (Dosage)
            'prod_id', // Clave foránea en la tabla intermedia (Method) que apunta al modelo actual (ApplicationMethod)
            'id',          // Clave foránea en la tabla final (Dosage) que apunta al modelo intermedio (Method)
            'id',                 // Clave local en el modelo actual (ApplicationMethod)
            'methd_id'                  // Clave local en el modelo intermedio (Method)
        );
    }

    public function hasAppMethod($appMethodId) {
        return $this->applicationMethods->contains('id', $appMethodId);
    }
    
    public function presentation() {
        return $this->belongsTo(Presentation::class, 'presentation_id');
    }

    public function toxicityCategory() {
        return $this->belongsTo(ToxicityCategories::class, 'toxicity_categ_id');
    }
    public function orderProduct(){
        return $this->hasOne(OrderProduct::class, 'product_id');
    }
    public function pests() {
        return $this->hasManyThrough(
            PestCatalog::class,
            ProductPest::class,
            'product_id',
            'id',
            'id',
            'pest_id'
        );
    }

    public function hasPest($pestID)
    {
        return $this->pests->contains('id', $pestID);
    }

    public function files () {
        return $this->belongsTo(ProductFile::class,'files_id');
    }

    public function lot() {
        return $this->belongsTo(Lot::class, 'id', 'product_id');
    }

}
