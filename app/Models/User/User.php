<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $phone_profile
 * @property string|null $country
 * @property string|null $state
 * @property bool|null $is_influencer
 * @property string|null $zip
 * @property string|null $anet_customer_profile_id
 * @property string|null $anet_customer_shipping_address_id
 * @property int $bonus_point
 * @property string|null $image
 * @property string|null $timezone
 * @property string|null $verification_token
 * @property bool $is_valid_email
 * @property string|null $refer_custom
 * @property Carbon|null $pay_at
 * @property int|null $refer_type
 * @property string|null $name_as_referral
 * @property string|null $affiliate_token
 * @property bool $is_receive_subscribe
 * @property bool $is_receive_list
 * @property int|null $pay_day
 * @property bool $is_test
 * @property string|null $admin_token
 * @property bool $is_assistant
 * @property Carbon|null $cancel_at
 * @property int|null $tour
 * @property int|null $id_sps
 * @property array|null $sps_data
 * @property bool|null $is_sps_active
 * @property Carbon|null $sps_terminated_at
 * @property int|null $general_visibility
 * @property int|null $phone_visibility
 * @property int|null $address_visibility
 * @property float|null $last_price
 * @property float|null $credit
 * @property bool|null $is_bad_email
 * @property string|null $promocode
 * @property int|null $test_message_qnt
 * @property Carbon|null $test_message_at
 * @property string|null $city
 * @property int|null $id_inviter
 * @property int|null $source
 * @property string|null $about
 * @property string|null $header_image
 * @property string|null $name_search
 * @property User|null $user
 * @property Collection|Address[] $addresses
 * @property Collection|AdminMember[] $admin_members
 * @property Collection|Affiliate[] $affiliates
 * @property Collection|AffiliateInvite[] $affiliate_invites
 * @property Collection|BpSubscription[] $bp_subscriptions
 * @property Collection|Comment[] $comments
 * @property Collection|Contact[] $contacts
 * @property Collection|ContractLine[] $contract_lines
 * @property Collection|ConversationMessage[] $conversation_messages
 * @property Collection|Conversation[] $conversations
 * @property Collection|CreditCard[] $credit_cards
 * @property Collection|Device[] $devices
 * @property Collection|Follower[] $followers
 * @property Collection|FollowerList[] $follower_lists
 * @property Collection|InfluencerAssistant[] $influencer_assistants
 * @property Collection|Invite[] $invites
 * @property Collection|InviteAffiliate[] $invite_affiliates
 * @property Collection|Invoice[] $invoices
 * @property Collection|LogAuthorize[] $log_authorizes
 * @property Collection|LoginStatistic[] $login_statistics
 * @property Collection|MemberRequest[] $member_requests
 * @property Collection|MemberTemplate[] $member_templates
 * @property Collection|MoneyTransaction[] $money_transactions
 * @property Collection|PhoneNumber[] $phone_numbers
 * @property Collection|SmsPool[] $sms_pools
 * @property Collection|SmsPoolPhoneNumber[] $sms_pool_phone_numbers
 * @property Collection|SmsPoolReaction[] $sms_pool_reactions
 * @property Collection|SmsShedule[] $sms_shedules
 * @property Collection|SpsAddUserRequest[] $sps_add_user_requests
 * @property Collection|SpsContract[] $sps_contracts
 * @property Collection|Subscription[] $subscriptions
 * @property Collection|SubscriptionWizard[] $subscription_wizards
 * @property Collection|User[] $users
 * @property Collection|UserFollowerAlert[] $user_follower_alerts
 * @property Collection|UserFriend[] $user_friends
 * @property Collection|UserPlan[] $user_plans
 * @property Collection|UserPlanShedule[] $user_plan_shedules
 * @property Collection|UserPointHistory[] $user_point_histories
 * @property Collection|Service[] $services
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'user';

    protected $casts = [
        'role' => 'int',
        'status' => 'int',
        'is_influencer' => 'bool',
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

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_inviter');
    }

    public function addresses()
    {
        return $this->hasMany(App\Models\User\App\Models\User\Address::class, 'id_user');
    }

    public function admin_members()
    {
        return $this->hasMany(App\Models\User\App\Models\User\AdminMember::class, 'id_member');
    }

    public function affiliates()
    {
        return $this->hasMany(App\Models\User\App\Models\User\Affiliate::class, 'id_user_parent');
    }

    public function affiliate_invites()
    {
        return $this->hasMany(App\Models\User\AffiliateApp\Models\User\App\Models\User\Invite::class, 'id_user');
    }

    public function bp_subscriptions()
    {
        return $this->hasMany(App\Models\Subscription\BpApp\Models\Subscription\App\Models\Subscription\Subscription::class, 'id_user');
    }

    public function comments()
    {
        return $this->hasMany(App\Models\Content\App\Models\Content\Comment::class, 'id_user');
    }

    public function contacts()
    {
        return $this->hasMany(App\Models\Communication\App\Models\Communication\Contact::class, 'id_user');
    }

    public function contract_lines()
    {
        return $this->hasMany(App\Models\Finance\App\Models\Finance\ContractLine::class, 'id_user');
    }

    public function conversation_messages()
    {
        return $this->hasMany(App\Models\Communication\App\Models\Communication\ConversationMessage::class, 'id_user');
    }

    public function conversations()
    {
        return $this->belongsToMany(App\Models\Communication\App\Models\Communication\Conversation::class, 'conversation_user', 'id_user', 'id_conversation');
    }

    public function credit_cards()
    {
        return $this->hasMany(App\Models\Finance\App\Models\Finance\CreditCard::class, 'id_user');
    }

    public function devices()
    {
        return $this->hasMany(App\Models\Device\App\Models\Device\Device::class, 'id_user');
    }

    public function followers()
    {
        return $this->hasMany(App\Models\Subscription\App\Models\Subscription\Follower::class, 'id_user_leader');
    }

    public function follower_lists()
    {
        return $this->hasMany(App\Models\Subscription\App\Models\Subscription\FollowerList::class, 'id_user');
    }

    public function influencer_assistants()
    {
        return $this->hasMany(App\Models\Subscription\App\Models\Subscription\InfluencerAssistant::class, 'id_influencer');
    }

    public function invites()
    {
        return $this->hasMany(App\Models\User\App\Models\User\Invite::class, 'id_user');
    }

    public function invite_affiliates()
    {
        return $this->hasMany(InviteApp\Models\User\App\Models\User\Affiliate::class, 'id_inviter');
    }

    public function invoices()
    {
        return $this->hasMany(App\Models\Finance\App\Models\Finance\Invoice::class, 'id_user');
    }

    public function log_authorizes()
    {
        return $this->hasMany(App\Models\Analytics\App\Models\Analytics\LogAuthorize::class, 'id_user');
    }

    public function login_statistics()
    {
        return $this->hasMany(App\Models\Analytics\App\Models\Analytics\LoginStatistic::class, 'id_user');
    }

    public function member_requests()
    {
        return $this->hasMany(App\Models\User\App\Models\User\MemberRequest::class, 'id_user_from');
    }

    public function member_templates()
    {
        return $this->hasMany(App\Models\User\App\Models\User\MemberTemplate::class, 'id_admin');
    }

    public function money_transactions()
    {
        return $this->hasMany(App\Models\Finance\App\Models\Finance\MoneyTransaction::class, 'id_user');
    }

    public function phone_numbers()
    {
        return $this->hasMany(App\Models\Device\App\Models\Device\PhoneNumber::class, 'id_user');
    }

    public function sms_pools()
    {
        return $this->hasMany(App\Models\Communication\App\Models\Communication\SmsPool::class, 'id_user');
    }

    public function sms_pool_phone_numbers()
    {
        return $this->hasMany(SmsPoolApp\Models\Device\App\Models\Device\PhoneNumber::class, 'id_user');
    }

    public function sms_pool_reactions()
    {
        return $this->hasMany(App\Models\Communication\App\Models\Communication\SmsPoolReaction::class, 'id_user');
    }

    public function sms_shedules()
    {
        return $this->hasMany(App\Models\Communication\App\Models\Communication\SmsShedule::class, 'id_user');
    }

    public function sps_add_user_requests()
    {
        return $this->hasMany(App\Models\User\App\Models\User\SpsAddUserRequest::class, 'id_user');
    }

    public function sps_contracts()
    {
        return $this->hasMany(App\Models\Finance\App\Models\Finance\SpsContract::class, 'id_user');
    }

    public function subscriptions()
    {
        return $this->belongsToMany(App\Models\Subscription\App\Models\Subscription\Subscription::class, 'user_subscription_address', 'id_user', 'id_subscription')
            ->withPivot('id', 'zip');
    }

    public function subscription_wizards()
    {
        return $this->hasMany(App\Models\Subscription\App\Models\Subscription\SubscriptionWizard::class, 'id_user');
    }

    public function users()
    {
        return $this->hasMany(App\Models\User\User::class, 'id_inviter');
    }

    public function user_follower_alerts()
    {
        return $this->hasMany(App\Models\Notification\App\Models\Notification\UserFollowerAlert::class, 'id_user');
    }

    public function user_friends()
    {
        return $this->hasMany(App\Models\User\App\Models\User\UserFriend::class, 'id_user');
    }

    public function user_plans()
    {
        return $this->hasMany(App\Models\User\App\Models\User\UserPlan::class, 'id_user');
    }

    public function user_plan_shedules()
    {
        return $this->hasMany(App\Models\User\App\Models\User\UserPlanShedule::class, 'id_user');
    }

    public function user_point_histories()
    {
        return $this->hasMany(App\Models\User\App\Models\User\UserPointHistory::class, 'id_user');
    }

    public function services()
    {
        return $this->belongsToMany(App\Models\Finance\App\Models\Finance\Service::class, 'user_service', 'id_user', 'id_service');
    }
}
