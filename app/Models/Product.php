<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product model
 * Represents subscription plans and products
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property string $type
 * @property bool $is_active
 * @property bool $is_new_plan
 * @property array|null $settings
 * @property int $sort_order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<Subscription> $subscriptions
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'service';

    protected $fillable = [
        'name',
        'description',
        'price',
        'type',
        'is_active',
        'is_new_plan',
        'settings',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_new_plan' => 'boolean',
        'settings' => 'array',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subscriptions for this product
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope to get only active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only subscription type products
     */
    public function scopeSubscriptions($query)
    {
        return $query->where('type', 'subscription');
    }

    /**
     * Scope to get new plans
     */
    public function scopeNewPlans($query)
    {
        return $query->where('is_new_plan', true);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get plan features from settings
     */
    public function getFeaturesAttribute(): array
    {
        $defaultFeatures = [
            [
                'title' => 'Unlimited Access to The GALILEYO Web Application',
                'description' => 'Connect via desktop or mobile browser',
                'enable' => true,
            ],
            [
                'title' => 'Access to Multiple Feeds',
                'description' => 'Follow your favorite influencers, breaking news, financial, and more',
                'value' => '10 Feeds',
            ],
            [
                'title' => 'Access to Satellite Communicators',
                'description' => 'Connect a qualified personal satellite communicator',
                'enable' => false,
            ],
            [
                'title' => 'Communicate When Cell Towers Go Down or Reception is Not Available',
                'description' => '',
                'enable' => false,
            ],
            [
                'title' => 'Global Coverage',
                'description' => '',
                'enable' => false,
            ],
            [
                'title' => '24/7 Technical Support',
                'description' => '',
                'enable' => true,
            ],
        ];

        return $this->settings['features'] ?? $defaultFeatures;
    }
}