<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Database\Factories\UserFactory as RootUserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @property int $id
 * @property string|null $email
 * @property string $auth_key
 * @property string|null $password_hash
 * @property string|null $password_reset_token
 * @property int $role
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $phone_profile
 * @property string|null $country
 * @property string|null $state
 * @property bool|null $is_influencer
 * @property \Illuminate\Support\Carbon|null $influencer_verified_at
 * @property string|null $zip
 * @property string|null $anet_customer_profile_id
 * @property string|null $anet_customer_shipping_address_id
 * @property int $bonus_point
 * @property string|null $avatar
 * @property string|null $timezone
 * @property string|null $verification_token
 * @property bool $is_valid_email
 * @property string|null $refer_custom
 * @property \Illuminate\Support\Carbon|null $pay_at
 * @property int|null $refer_type
 * @property string|null $name_as_referral
 * @property string|null $affiliate_token
 * @property bool $is_receive_subscribe
 * @property bool $is_receive_list
 * @property int|null $pay_day
 * @property bool $is_test
 * @property string|null $admin_token
 * @property bool $is_assistant
 * @property \Illuminate\Support\Carbon|null $cancel_at
 * @property int|null $tour
 * @property int|null $id_sps
 * @property array<array-key, mixed>|null $sps_data
 * @property bool|null $is_sps_active
 * @property \Illuminate\Support\Carbon|null $sps_terminated_at
 * @property int|null $general_visibility
 * @property int|null $phone_visibility
 * @property int|null $address_visibility
 * @property float|null $last_price
 * @property float|null $credit
 * @property bool|null $is_bad_email
 * @property string|null $promocode
 * @property int|null $test_message_qnt
 * @property \Illuminate\Support\Carbon|null $test_message_at
 * @property string|null $city
 * @property int|null $id_inviter
 * @property int|null $source
 * @property string|null $about
 * @property string|null $header_image
 * @property string|null $name_search
 * @property-read Collection<int, Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read Collection<int, AdminMember> $admin_members
 * @property-read int|null $admin_members_count
 * @property-read Collection<int, Invite> $affiliate_invites
 * @property-read int|null $affiliate_invites_count
 * @property-read Collection<int, Affiliate> $affiliates
 * @property-read int|null $affiliates_count
 * @property-read Collection<int, \App\Models\Bookmark> $bookmarks
 * @property-read int|null $bookmarks_count
 * @property-read Collection<int, \App\Models\Subscription\BpSubscription> $bp_subscriptions
 * @property-read int|null $bp_subscriptions_count
 * @property-read Collection<int, \App\Models\Content\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read Collection<int, \App\Models\Communication\Contact> $contacts
 * @property-read int|null $contacts_count
 * @property-read Collection<int, \App\Models\Finance\ContractLine> $contractLines
 * @property-read int|null $contract_lines_count
 * @property-read Collection<int, \App\Models\Finance\ContractLine> $contract_lines
 * @property-read Collection<int, \App\Models\Communication\ConversationMessage> $conversation_messages
 * @property-read int|null $conversation_messages_count
 * @property-read Collection<int, \App\Models\Communication\Conversation> $conversations
 * @property-read int|null $conversations_count
 * @property-read Collection<int, \App\Models\Finance\CreditCard> $creditCards
 * @property-read int|null $credit_cards_count
 * @property-read Collection<int, \App\Models\Finance\CreditCard> $credit_cards
 * @property-read Collection<int, \App\Models\Device\Device> $devices
 * @property-read int|null $devices_count
 * @property-read Collection<int, \App\Models\Subscription\FollowerList> $follower_lists
 * @property-read int|null $follower_lists_count
 * @property-read Collection<int, \App\Models\Subscription\Follower> $followers
 * @property-read int|null $followers_count
 * @property-read Collection<int, \App\Models\Subscription\InfluencerAssistant> $influencer_assistants
 * @property-read int|null $influencer_assistants_count
 * @property-read Collection<int, Affiliate> $invite_affiliates
 * @property-read int|null $invite_affiliates_count
 * @property-read Collection<int, Invite> $invites
 * @property-read int|null $invites_count
 * @property-read Collection<int, \App\Models\Finance\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read Collection<int, \App\Models\Analytics\LogAuthorize> $log_authorizes
 * @property-read int|null $log_authorizes_count
 * @property-read Collection<int, \App\Models\Analytics\LoginStatistic> $login_statistics
 * @property-read int|null $login_statistics_count
 * @property-read Collection<int, MemberRequest> $member_requests
 * @property-read int|null $member_requests_count
 * @property-read Collection<int, MemberTemplate> $member_templates
 * @property-read int|null $member_templates_count
 * @property-read Collection<int, \App\Models\Finance\MoneyTransaction> $money_transactions
 * @property-read int|null $money_transactions_count
 * @property-read Collection<int, \App\Models\Device\PhoneNumber> $phoneNumbers
 * @property-read int|null $phone_numbers_count
 * @property-read Collection<int, \App\Models\Device\PhoneNumber> $phone_numbers
 * @property-read Collection<int, \App\Models\Finance\Service> $services
 * @property-read int|null $services_count
 * @property-read Collection<int, \App\Models\Communication\SmsPoolPhoneNumber> $sms_pool_phone_numbers
 * @property-read int|null $sms_pool_phone_numbers_count
 * @property-read Collection<int, \App\Models\Communication\SmsPoolReaction> $sms_pool_reactions
 * @property-read int|null $sms_pool_reactions_count
 * @property-read Collection<int, \App\Models\Communication\SmsPool> $sms_pools
 * @property-read int|null $sms_pools_count
 * @property-read Collection<int, \App\Models\Communication\SmsShedule> $sms_shedules
 * @property-read int|null $sms_shedules_count
 * @property-read Collection<int, SpsAddUserRequest> $sps_add_user_requests
 * @property-read int|null $sps_add_user_requests_count
 * @property-read Collection<int, \App\Models\Finance\SpsContract> $sps_contracts
 * @property-read int|null $sps_contracts_count
 * @property-read Collection<int, \App\Models\Subscription\SubscriptionWizard> $subscription_wizards
 * @property-read int|null $subscription_wizards_count
 * @property-read Collection<int, \App\Models\Subscription\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read User|null $user
 * @property-read Collection<int, \App\Models\Notification\UserFollowerAlert> $user_follower_alerts
 * @property-read int|null $user_follower_alerts_count
 * @property-read Collection<int, UserFriend> $user_friends
 * @property-read int|null $user_friends_count
 * @property-read Collection<int, UserPlanShedule> $user_plan_shedules
 * @property-read int|null $user_plan_shedules_count
 * @property-read Collection<int, UserPlan> $user_plans
 * @property-read int|null $user_plans_count
 * @property-read Collection<int, UserPointHistory> $user_point_histories
 * @property-read int|null $user_point_histories_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\User\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddressVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAdminToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAffiliateToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAnetCustomerProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAnetCustomerShippingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAuthKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBonusPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCancelAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGeneralVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereHeaderImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdInviter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdSps($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsAssistant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsBadEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsInfluencer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsReceiveList($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsReceiveSubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsSpsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsValidEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNameAsReferral($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNameSearch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordResetToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePayAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePayDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePromocode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereReferCustom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereReferType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSpsData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSpsTerminatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTestMessageAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTestMessageQnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTour($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereZip($value)
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /** @phpstan-ignore-line */
    public const STATUS_ACTIVE = 1;

    public const STATUS_CANCEL = 2;

    public const STATUS_DELETED = 3;

    public const STATUS_TO_VERIFY = 4;

    protected $table = 'user';

    protected $casts = [
        'role' => 'int',
        'status' => 'int',
        'is_influencer' => 'bool',
        'influencer_verified_at' => 'datetime',
        'bonus_point' => 'int',
        'is_valid_email' => 'bool',
        'pay_at' => 'datetime',
        'refer_type' => 'int',
        'is_receive_subscribe' => 'bool',
        'is_receive_list' => 'bool',
        'pay_day' => 'int',
        'is_test' => 'bool',
        'is_assistant' => 'bool',
        'cancel_at' => 'datetime',
        'tour' => 'int',
        'id_sps' => 'int',
        'sps_data' => 'json',
        'is_sps_active' => 'bool',
        'sps_terminated_at' => 'datetime',
        'general_visibility' => 'int',
        'phone_visibility' => 'int',
        'address_visibility' => 'int',
        'last_price' => 'float',
        'credit' => 'float',
        'is_bad_email' => 'bool',
        'test_message_qnt' => 'int',
        'test_message_at' => 'datetime',
        'id_inviter' => 'int',
        'source' => 'int',
    ];

    protected $hidden = [
        'password_reset_token',
        'verification_token',
        'affiliate_token',
        'admin_token',
    ];

    protected $fillable = [
        'email',
        'auth_key',
        'password_hash',
        'password_reset_token',
        'role',
        'status',
        'first_name',
        'last_name',
        'phone_profile',
        'country',
        'state',
        'is_influencer',
        'influencer_verified_at',
        'zip',
        'anet_customer_profile_id',
        'anet_customer_shipping_address_id',
        'bonus_point',
        'image',
        'timezone',
        'verification_token',
        'is_valid_email',
        'refer_custom',
        'pay_at',
        'refer_type',
        'name_as_referral',
        'affiliate_token',
        'is_receive_subscribe',
        'is_receive_list',
        'pay_day',
        'is_test',
        'admin_token',
        'is_assistant',
        'cancel_at',
        'tour',
        'id_sps',
        'sps_data',
        'is_sps_active',
        'sps_terminated_at',
        'general_visibility',
        'phone_visibility',
        'address_visibility',
        'last_price',
        'credit',
        'is_bad_email',
        'promocode',
        'test_message_qnt',
        'test_message_at',
        'city',
        'id_inviter',
        'source',
        'about',
        'header_image',
        'name_search',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(self::class, 'id_inviter');
    }

    /**
     * @return HasMany<\App\Models\Bookmark, $this>
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(\App\Models\Bookmark::class, 'user_id');
    }

    /**
     * @return HasMany<Address, $this>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'id_user');
    }

    /**
     * @return HasMany<AdminMember, $this>
     */
    public function admin_members(): HasMany
    {
        return $this->hasMany(AdminMember::class, 'id_member');
    }

    /**
     * @return HasMany<Affiliate, $this>
     */
    public function affiliates(): HasMany
    {
        return $this->hasMany(Affiliate::class, 'id_user_parent');
    }

    /**
     * @return HasMany<Invite, $this>
     */
    public function affiliate_invites(): HasMany
    {
        return $this->hasMany(Invite::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Subscription\BpSubscription, $this>
     */
    public function bp_subscriptions(): HasMany
    {
        return $this->hasMany(\App\Models\Subscription\BpSubscription::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Content\Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(\App\Models\Content\Comment::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Communication\Contact, $this>
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(\App\Models\Communication\Contact::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Finance\ContractLine, $this>
     */
    public function contract_lines(): HasMany
    {
        return $this->hasMany(\App\Models\Finance\ContractLine::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Finance\ContractLine, $this>
     */
    public function contractLines(): HasMany
    {
        return $this->contract_lines();
    }

    /**
     * @return HasMany<\App\Models\Communication\ConversationMessage, $this>
     */
    public function conversation_messages(): HasMany
    {
        return $this->hasMany(\App\Models\Communication\ConversationMessage::class, 'id_user');
    }

    /**
     * @return BelongsToMany<\App\Models\Communication\Conversation, $this>
     */
    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Communication\Conversation::class, 'conversation_user', 'id_user', 'id_conversation');
    }

    /**
     * @return HasMany<\App\Models\Finance\CreditCard, $this>
     */
    public function credit_cards(): HasMany
    {
        return $this->hasMany(\App\Models\Finance\CreditCard::class, 'id_user');
    }


    /**
     * @return HasMany<\App\Models\Device\Device, $this>
     */
    public function devices(): HasMany
    {
        return $this->hasMany(\App\Models\Device\Device::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Subscription\Follower, $this>
     */
    public function followers(): HasMany
    {
        return $this->hasMany(\App\Models\Subscription\Follower::class, 'id_user_leader');
    }

    /**
     * @return HasMany<\App\Models\Subscription\FollowerList, $this>
     */
    public function follower_lists(): HasMany
    {
        return $this->hasMany(\App\Models\Subscription\FollowerList::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Subscription\InfluencerAssistant, $this>
     */
    public function influencer_assistants(): HasMany
    {
        return $this->hasMany(\App\Models\Subscription\InfluencerAssistant::class, 'id_influencer');
    }

    /**
     * @return HasMany<Invite, $this>
     */
    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class, 'id_user');
    }

    /**
     * @return HasMany<Affiliate, $this>
     */
    public function invite_affiliates(): HasMany
    {
        return $this->hasMany(Affiliate::class, 'id_inviter');
    }

    /**
     * @return HasMany<\App\Models\Finance\Invoice, $this>
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(\App\Models\Finance\Invoice::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Analytics\LogAuthorize, $this>
     */
    public function log_authorizes(): HasMany
    {
        return $this->hasMany(\App\Models\Analytics\LogAuthorize::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Analytics\LoginStatistic, $this>
     */
    public function login_statistics(): HasMany
    {
        return $this->hasMany(\App\Models\Analytics\LoginStatistic::class, 'id_user');
    }

    /**
     * @return HasMany<MemberRequest, $this>
     */
    public function member_requests(): HasMany
    {
        return $this->hasMany(MemberRequest::class, 'id_user_from');
    }

    /**
     * @return HasMany<MemberTemplate, $this>
     */
    public function member_templates(): HasMany
    {
        return $this->hasMany(MemberTemplate::class, 'id_admin');
    }

    /**
     * @return HasMany<\App\Models\Finance\MoneyTransaction, $this>
     */
    public function money_transactions(): HasMany
    {
        return $this->hasMany(\App\Models\Finance\MoneyTransaction::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Device\PhoneNumber, $this>
     */
    public function phone_numbers(): HasMany
    {
        return $this->hasMany(\App\Models\Device\PhoneNumber::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Device\PhoneNumber, $this>
     */
    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(\App\Models\Device\PhoneNumber::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Communication\SmsPool, $this>
     */
    public function sms_pools(): HasMany
    {
        return $this->hasMany(\App\Models\Communication\SmsPool::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Communication\SmsPoolPhoneNumber, $this>
     */
    public function sms_pool_phone_numbers(): HasMany
    {
        return $this->hasMany(\App\Models\Communication\SmsPoolPhoneNumber::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Communication\SmsPoolReaction, $this>
     */
    public function sms_pool_reactions(): HasMany
    {
        return $this->hasMany(\App\Models\Communication\SmsPoolReaction::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Communication\SmsShedule, $this>
     */
    public function sms_shedules(): HasMany
    {
        return $this->hasMany(\App\Models\Communication\SmsShedule::class, 'id_user');
    }

    /**
     * @return HasMany<SpsAddUserRequest, $this>
     */
    public function sps_add_user_requests(): HasMany
    {
        return $this->hasMany(SpsAddUserRequest::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Finance\SpsContract, $this>
     */
    public function sps_contracts(): HasMany
    {
        return $this->hasMany(\App\Models\Finance\SpsContract::class, 'id_user');
    }

    /**
     * @return BelongsToMany<\App\Models\Subscription\Subscription, $this>
     */
    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Subscription\Subscription::class, 'user_subscription_address', 'id_user', 'id_subscription')
            ->withPivot('id', 'zip');
    }

    /**
     * @return HasMany<\App\Models\Subscription\SubscriptionWizard, $this>
     */
    public function subscription_wizards(): HasMany
    {
        return $this->hasMany(\App\Models\Subscription\SubscriptionWizard::class, 'id_user');
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(self::class, 'id_inviter');
    }

    /**
     * @return HasMany<\App\Models\Notification\UserFollowerAlert, $this>
     */
    public function user_follower_alerts(): HasMany
    {
        return $this->hasMany(\App\Models\Notification\UserFollowerAlert::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\CreditCard, $this>
     */
    public function creditCards(): HasMany
    {
        return $this->hasMany(\App\Models\CreditCard::class, 'user_id', 'id');
    }

    /**
     * @return HasMany<\App\Models\UserSubscription, $this>
     */
    public function paymentSubscriptions(): HasMany
    {
        return $this->hasMany(\App\Models\UserSubscription::class, 'user_id', 'id');
    }

    /**
     * @return HasMany<\App\Models\PaymentHistory, $this>
     */
    public function paymentHistories(): HasMany
    {
        return $this->hasMany(\App\Models\PaymentHistory::class, 'user_id', 'id');
    }

    /**
     * @return HasMany<UserFriend, $this>
     */
    public function user_friends(): HasMany
    {
        return $this->hasMany(UserFriend::class, 'id_user');
    }

    /**
     * @return HasMany<UserPlan, $this>
     */
    public function user_plans(): HasMany
    {
        return $this->hasMany(UserPlan::class, 'id_user');
    }

    /**
     * @return HasMany<UserPlanShedule, $this>
     */
    public function user_plan_shedules(): HasMany
    {
        return $this->hasMany(UserPlanShedule::class, 'id_user');
    }

    /**
     * @return HasMany<UserPointHistory, $this>
     */
    public function user_point_histories(): HasMany
    {
        return $this->hasMany(UserPointHistory::class, 'id_user');
    }

    /**
     * @return BelongsToMany<\App\Models\Finance\Service, $this>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Finance\Service::class, 'user_service', 'id_user', 'id_service');
    }

    // Role helpers to align with views that check staff-like methods on Auth::user()
    public function isSuper(): bool
    {
        return false;
    }

    public function isAdmin(): bool
    {
        return false;
    }

    // Settings read-only flag (used in settings view)
    public function showSettingsRO(): bool
    {
        return true;
    }

    // Permission to view settings (used in SettingsController@index)
    public function showSettings(): bool
    {
        return true;
    }

    protected static function newFactory(): RootUserFactory
    {
        return RootUserFactory::new();
    }
}
