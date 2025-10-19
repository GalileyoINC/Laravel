<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User\User as UserModel;

/**
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
 * @property string|null $zip
 * @property string|null $anet_customer_profile_id
 * @property string|null $anet_customer_shipping_address_id
 * @property int $bonus_point
 * @property string|null $image
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\AdminMember> $admin_members
 * @property-read int|null $admin_members_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\Invite> $affiliate_invites
 * @property-read int|null $affiliate_invites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\Affiliate> $affiliates
 * @property-read int|null $affiliates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Bookmark> $bookmarks
 * @property-read int|null $bookmarks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Subscription\BpSubscription> $bp_subscriptions
 * @property-read int|null $bp_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Content\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Communication\Contact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Finance\ContractLine> $contract_lines
 * @property-read int|null $contract_lines_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Communication\ConversationMessage> $conversation_messages
 * @property-read int|null $conversation_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Communication\Conversation> $conversations
 * @property-read int|null $conversations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Finance\CreditCard> $creditCards
 * @property-read int|null $credit_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Finance\CreditCard> $credit_cards
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Device\Device> $devices
 * @property-read int|null $devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Subscription\FollowerList> $follower_lists
 * @property-read int|null $follower_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Subscription\Follower> $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Subscription\InfluencerAssistant> $influencer_assistants
 * @property-read int|null $influencer_assistants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\Affiliate> $invite_affiliates
 * @property-read int|null $invite_affiliates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\Invite> $invites
 * @property-read int|null $invites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Finance\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Analytics\LogAuthorize> $log_authorizes
 * @property-read int|null $log_authorizes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Analytics\LoginStatistic> $login_statistics
 * @property-read int|null $login_statistics_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\MemberRequest> $member_requests
 * @property-read int|null $member_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\MemberTemplate> $member_templates
 * @property-read int|null $member_templates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Finance\MoneyTransaction> $money_transactions
 * @property-read int|null $money_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Device\PhoneNumber> $phoneNumbers
 * @property-read int|null $phone_numbers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Device\PhoneNumber> $phone_numbers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Finance\Service> $services
 * @property-read int|null $services_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Communication\SmsPoolPhoneNumber> $sms_pool_phone_numbers
 * @property-read int|null $sms_pool_phone_numbers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Communication\SmsPoolReaction> $sms_pool_reactions
 * @property-read int|null $sms_pool_reactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Communication\SmsPool> $sms_pools
 * @property-read int|null $sms_pools_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Communication\SmsShedule> $sms_shedules
 * @property-read int|null $sms_shedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\SpsAddUserRequest> $sps_add_user_requests
 * @property-read int|null $sps_add_user_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Finance\SpsContract> $sps_contracts
 * @property-read int|null $sps_contracts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Subscription\SubscriptionWizard> $subscription_wizards
 * @property-read int|null $subscription_wizards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Subscription\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read UserModel|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notification\UserFollowerAlert> $user_follower_alerts
 * @property-read int|null $user_follower_alerts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\UserFriend> $user_friends
 * @property-read int|null $user_friends_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\UserPlanShedule> $user_plan_shedules
 * @property-read int|null $user_plan_shedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\UserPlan> $user_plans
 * @property-read int|null $user_plans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User\UserPointHistory> $user_point_histories
 * @property-read int|null $user_point_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, UserModel> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
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
class User extends UserModel
{
    // This is just an alias to maintain backward compatibility
    // with existing tokens that reference App\Models\User
}
