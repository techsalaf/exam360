<?php

namespace App\Models;

use App\Models\Addons\Ielts\IeltsTest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\SystemSetting;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'email',
        'avatar',
        'preferences',
        'password',
        'is_banned',
        'country_code',
        'mobile',
        'phone',            
        'custom_fields',    
        'email_verified_at',
        'mobile_verified_at',
        'country',
        'city',
        'state',
        'zip',
        'address',
        'plan_id',
        'plan_expires_at',
        'plan_type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'mobile_verified_at' => 'datetime',
        'password'           => 'hashed',
        'is_banned'          => 'boolean',
        'preferences'        => 'array',
        'custom_fields'      => 'array',    // Added so Laravel handles JSON automatically
        'plan_expires_at'    => 'datetime',
    ];

    protected $appends = [
        'is_subscribed',
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            if (!$user->roles()->exists()) {
                $defaultRoleId = SystemSetting::where('key', 'default_signup_role')->value('value');
                if ($defaultRoleId) {
                    $role = Role::find($defaultRoleId);
                } else {
                    $role = Role::where('name', 'Student')->first();
                }

                if ($role && $role->name !== 'Super Admin') {
                    $user->assignRole($role);
                }
            }
        });
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_user')
            ->withPivot('status', 'created_at', 'payment_method', 'transaction_id', 'price')
            ->withTimestamps();
    }

    public function ieltsTests()
    {
        return $this->belongsToMany(
            IeltsTest::class,
            'ielts_test_user'
        )->withPivot([
            'status',
            'transaction_id',
            'price'
        ])->withTimestamps();
    }

    public function examSessions()
    {
        return $this->hasMany(StudentExamSession::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function currentSubscription(): HasOne
    {
        return $this->hasOne(Payment::class)
            ->where('type', 'subscription')
            ->whereIn('status', ['success', 'approved', 'paid'])
            ->where('end_date', '>', now())
            ->latest('id');
    }

    public function canAccessExam(Exam $exam): bool
    {
        if (!$exam->is_paid) {
            return true;
        }

        $purchase = \DB::table('exam_user')
            ->where('user_id', $this->id)
            ->where('exam_id', $exam->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if ($purchase) {
            return true;
        }

        if ($this->is_subscribed && $this->plan) {
            if ($exam->plan_id == $this->plan_id) {
                return true;
            }

            return $this->plan->categories()->where('categories.id', $exam->category_id)->exists();
        }

        return false;
    }

    public function getIsSubscribedAttribute(): bool
    {
        if ($this->plan_id && $this->plan_expires_at && $this->plan_expires_at->isFuture()) {
            return true;
        }

        return $this->currentSubscription()->exists();
    }
}