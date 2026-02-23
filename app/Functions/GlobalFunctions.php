<?php

use App\Models\Setting;

function settingKey()
{
    $app_name = Setting::query()->where('key', 'app_name')->first()->value ?? 'StartUp';
    $facebook = Setting::query()->where('key', 'facebook_link')->first()->value ?? null;
    $twitter = Setting::query()->where('key', 'twitter_link')->first()->value ?? null;
    $instagram = Setting::query()->where('key', 'instagram_link')->first()->value ?? null;
    $linkedin = Setting::query()->where('key', 'linkedin_link')->first()->value ?? null;
    $appLogo = Setting::query()->where('key', 'app_logo')->first()?->value;
    $forgotPasswordTemplate = Setting::query()->where('key', 'forgot_password_template')->first()->value ?? null;
    $forgotPasswordTemplateMail = Setting::query()->where('key', 'forgot_password_mail_template')->first()->value ?? null;
    $accountVerificationTemplateMail = Setting::query()->where('key', 'account_verification_template')->first()->value ?? null;
    $mailConfig = Setting::query()->where('key', 'mail_config')->first()->value ?? null;
    $privacyPolicy = Setting::query()->where('key', 'privacy_policy')->first()->value ?? null;

    return [
        'app_name' => $app_name,
        'facebook_link' => $facebook,
        'twitter_link' => $twitter,
        'instagram_link' => $instagram,
        'linkedin_link' => $linkedin,
        'app_logo' => $appLogo ? asset('images/setting/' . $appLogo) : asset('default_images/logo.png'),
        'forgot_password_template' => json_decode($forgotPasswordTemplate, true),
        'forgot_password_mail_template' => json_decode($forgotPasswordTemplateMail, true),
        'account_verification_template' => json_decode($accountVerificationTemplateMail, true),
        'mail_config' => json_decode($mailConfig, true),
        'privacy_policy' => $privacyPolicy,
    ];
}

function getPaginationInfo($items, $limit)
{
    return [
        'limit' => $limit,
        'currentPage' => $items->currentPage(),
        'previousPageUrl' => $items->previousPageUrl() ? $items->previousPageUrl() . '&limit=' . $limit : null,
        'nextPageUrl' => $items->nextPageUrl() ? $items->nextPageUrl() . '&limit=' . $limit : null,
        'totalPage' => $items->lastPage(),
        'total' => $items->total(),
    ];
}
