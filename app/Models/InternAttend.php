<?php

namespace App\Models;

use App\Enums\CheckAttendStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use LaravelEasyRepository\Traits\GenUid;

/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property CheckAttendStatus $status
 * @property string $tanggal
 * @property-read string $tanggal_iso
 * @property string|null $jam_masuk
 * @property string|null $jam_keluar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TempInternAttend> $temp_intern_attends
 * @property-read int|null $temp_intern_attends_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereJamKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereUserId($value)
 * @mixin \Eloquent
 */
class InternAttend extends Model
{
    use GenUid;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'status',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => CheckAttendStatus::class,
        ];
    }

    /**
     * Konversi dari Format tanggal Sistem ke Mudah di baca untuk Manusia. cara panggil method/property nya adalah ambil bagian tengahnya. (get dan Attribute nya, hilangkan; ex: tanggal_iso)
     *
     * @return string
     */
    public function getTanggalIsoAttribute(): string
    {
        return Carbon::parse($this->tanggal)->isoFormat('DD MMMM YYYY');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function temp_intern_attends()
    {
        return $this->hasMany(TempInternAttend::class, 'user_id', 'id');
    }
}
