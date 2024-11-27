<?php



return [
    "success" => "success",
    "login" => "Login",
    "fail" => "fail",
    "no_changes" => "Nothing to change",
    "unknown_error" => "An error has occurred",
    "not_found" => "Not Found",
    "suspended" => "You have been suspended. Please contact support.",
    'not_allowed' => 'Not allowed! Only approved users can have access',
    "not_verified" => [
        "email" => "User email is not verified.",
        "kyc" => "Please complete your KYC.",
    ],
    "migration" => [
        "otp_sent" => "Otp sent successfully",
        "otp_send_failed" => "Otp could not be sent",
    ],
    "currency" => [
        "not_found" => "Could not find record for :name",
    ],
    "user_address" => [
        "same_country_error" => "Sender address must not be of the same country with the receiver address",
        "not_verify" => "Could not verify address",
    ],
    'company_services' => [
        'required' => 'You must provide a name for this service!',
    ],
    'documents' => [
        'required' => 'You must submit an file sample for this document!',
        'not_allowed' => [
            'invalid_file' => 'Not allowed! This type of file uploaded is not allowed'
        ]
    ],
    'payment_method_not_found' => 'No payment method found to complete this request',

    "user" => [
        "not_found" => "User not found",
        "not_customer" => "User is not a customer",
        "activate_action" => "activated",
        "suspend_action" => "suspended",
        "activated" => "User activated",
        "suspended" => "User suspended",
        'can_delete_self' => 'You can only delete your account!',
        "activated_message" => "Your account has been activated. You can login and continue using your account",
        "suspended_message" => "Your account has been suspended. Please contact customer support for assistance or explanation.",
        'delete_account' => 'Delete Account',
        'delete_account_email_sent' => 'Delete Account Email Sent',
        'delete_account_description' => 'Your account is about to been deleted. You will no longer access your account. Click the button to proceed',
        'has_to_be_customer' => 'User has to be a customer',
    ],

    "maintenance" => "We are currently optimizing the website to improve your experience.\n\nWe will be back shortly.",

    'account_resolved' => 'Account resolved',
    'account_not_resolved' => 'Account resolve failed',
    'commission_already_paid' => 'Not allowed, partner commission already paid out to wallet',
    'commission_success' => 'partner commission calculated successfully',
    'wallet_failed_create' => 'We could not create wallet for partner, please try again',
    'settlement' => 'Settlement for consolidation reference :reference',
    'wallet_failed_commission' => 'We could not credit earned commission to partner wallet , please try again',
    'wallet_success_commission' => 'PartnerRate credited to partner wallet successfully',
    'consolidation_settlement' => 'Payment for consolidation reference :ref has been successfully settled into your :app_name balance. You can login and request for a payout. ',
    'settlement_text' => 'View',
    'otp' => 'Please enter OTP',

];
