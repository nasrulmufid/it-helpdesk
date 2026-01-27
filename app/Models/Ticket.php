<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'user_id',
        'category_id',
        'assigned_to',
        'priority',
        'status',
        'resolved_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Helper methods
    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }

    public static function generateTicketNumber()
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');
        $prefixWithDate = $prefix . $date;

        $lastTicketNumber = self::where('ticket_number', 'like', $prefixWithDate . '%')->max('ticket_number');

        $nextSequence = 1;
        if (is_string($lastTicketNumber) && Str::startsWith($lastTicketNumber, $prefixWithDate) && strlen($lastTicketNumber) >= strlen($prefixWithDate) + 4) {
            $lastSequence = (int) substr($lastTicketNumber, -4);
            $nextSequence = max(1, $lastSequence + 1);
        }

        return $prefixWithDate . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    public static function createWithUniqueTicketNumber(array $attributes, int $maxAttempts = 10): self
    {
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            if (empty($attributes['ticket_number'])) {
                $attributes['ticket_number'] = self::generateTicketNumber();
            }

            try {
                return self::create($attributes);
            } catch (QueryException $e) {
                if ($attempt === $maxAttempts || !self::isTicketNumberUniqueViolation($e)) {
                    throw $e;
                }
                unset($attributes['ticket_number']);
            }
        }

        return self::create($attributes);
    }

    private static function isTicketNumberUniqueViolation(QueryException $e): bool
    {
        $message = $e->getMessage();
        if (is_string($message) && str_contains($message, 'tickets.ticket_number')) {
            return true;
        }

        $sqlState = $e->errorInfo[0] ?? null;
        if ($sqlState === '23000') {
            return true;
        }

        return false;
    }

    public function getPriorityLabelAttribute()
    {
        $labels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Kritis',
        ];

        return $labels[$this->priority] ?? $this->priority;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'open' => 'Terbuka',
            'in_progress' => 'Dalam Proses',
            'resolved' => 'Terselesaikan',
            'closed' => 'Ditutup',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
