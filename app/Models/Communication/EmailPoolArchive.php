<?php

declare(strict_types=1);

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * EmailPoolArchive model - alias for SmsPool
 * This model represents archived SMS pool entries
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_staff
 * @property int|null $id_subscription
 * @property int|null $id_follower_list
 * @property int|null $purpose
 * @property int $status
 * @property string $body
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $sms_provider
 * @property int|null $id_assistant
 * @property string|null $short_body
 * @property string|null $url
 * @property bool $is_ban
 * @property-read \App\Models\User\User|null $assistant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EmailPoolAttachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bookmark> $bookmarks
 * @property-read int|null $bookmarks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Content\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Subscription\FollowerList|null $followerList
 * @property-read int|null $comment_quantity
 * @property-read int|null $emergency_level
 * @property-read string|null $image
 * @property-read bool|null $is_bookmarked
 * @property-read bool|null $is_liked
 * @property-read bool|null $is_owner
 * @property-read bool|null $is_subscribed
 * @property-read string|null $location
 * @property-read float|null $percent
 * @property-read float|null $price
 * @property-read string|null $subtitle
 * @property-read string|null $title
 * @property-read int|null $type
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SmsPoolPhoto> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Device\PhoneNumber> $phone_numbers
 * @property-read int|null $phone_numbers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SmsPoolPhoto> $photos
 * @property-read int|null $photos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Content\Reaction> $reactions
 * @property-read int|null $reactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SmsPoolPhoto> $sms_pool_photos
 * @property-read int|null $sms_pool_photos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SmsShedule> $sms_shedules
 * @property-read int|null $sms_shedules_count
 * @property-read \App\Models\System\Staff|null $staff
 * @property-read \App\Models\Subscription\Subscription|null $subscription
 * @property-read \App\Models\User\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\UserPointHistory> $user_point_histories
 * @property-read int|null $user_point_histories_count
 *
 * @method static \Database\Factories\SmsPoolFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereIdAssistant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereIdFollowerList($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereIdStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereIdSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereIsBan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereShortBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereSmsProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolArchive whereUrl($value)
 *
 * @mixin \Eloquent
 */
class EmailPoolArchive extends SmsPool
{
    /**
     * Get available purposes for dropdowns
     */
    public static function getPurposes(): array
    {
        return parent::getPurposes();
    }

    /**
     * Get available statuses for dropdowns
     */
    public static function getStatuses(): array
    {
        return parent::getStatuses();
    }

    /**
     * Get the attachments for this email pool archive
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(EmailPoolAttachment::class, 'id_email_pool');
    }
}
