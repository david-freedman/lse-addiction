<?php

namespace App\Domains\Course\Models;

use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Enums\CourseType;
use App\Domains\Customer\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'old_price',
        'discount_percentage',
        'coach_id',
        'banner',
        'author_id',
        'status',
        'type',
        'starts_at',
        'label',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'starts_at' => 'datetime',
        'type' => CourseType::class,
        'status' => CourseStatus::class,
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class)
            ->using(CourseCustomer::class)
            ->withPivot(['enrolled_at', 'status']);
    }

    public function isPublished(): bool
    {
        return $this->status === CourseStatus::Published;
    }

    public function isDraft(): bool
    {
        return $this->status === CourseStatus::Draft;
    }

    public function isInProgress(): bool
    {
        return $this->status === CourseStatus::InProgress;
    }

    public function isFinished(): bool
    {
        return $this->status === CourseStatus::Finished;
    }

    public function isArchived(): bool
    {
        return $this->status === CourseStatus::Archived;
    }

    public function hasCustomer(Customer $customer): bool
    {
        return $this->customers()->where('customer_id', $customer->id)->exists();
    }

    public function scopeAvailableForPurchase($query, ?Customer $customer = null)
    {
        $query = $query->where('status', CourseStatus::Published);

        if ($customer) {
            $query->whereDoesntHave('customers', function($q) use ($customer) {
                $q->where('customer_id', $customer->id);
            });
        }

        return $query;
    }

    public function scopeFeatured($query)
    {
        return $query->where('type', 'featured');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('type', CourseType::Upcoming->value);
    }

    public function scopeRecorded($query)
    {
        return $query->where('type', CourseType::Recorded->value);
    }

    public function scopeFree($query)
    {
        return $query->where('price', 0);
    }

    public function scopeByFilter($query, ?CourseType $filter)
    {
        if ($filter === null) {
            return $query;
        }

        return match($filter) {
            CourseType::Upcoming => $query->upcoming(),
            CourseType::Recorded => $query->recorded(),
            CourseType::Free => $query->free(),
        };
    }

    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->starts_at
                ? $this->starts_at->locale('uk')->isoFormat('D MMMM')
                : null
        );
    }

    protected function hasDiscount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->old_price !== null && $this->old_price > $this->price
        );
    }

    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->has_discount
                ? $this->old_price - $this->price
                : 0
        );
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->price, 0, ',', ' ') . ' ₴'
        );
    }

    protected function formattedOldPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->old_price
                ? number_format($this->old_price, 0, ',', ' ') . ' ₴'
                : null
        );
    }

    protected function formattedDiscountAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->has_discount
                ? number_format($this->discount_amount, 0, ',', ' ') . ' ₴'
                : null
        );
    }

    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->banner
                ? (str_starts_with($this->banner, 'http://') || str_starts_with($this->banner, 'https://'))
                    ? $this->banner
                    : \Storage::disk('public')->url($this->banner)
                : null
        );
    }
}
