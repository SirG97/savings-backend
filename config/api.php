<?php

return [
    'paginate' => [
        'user' => [
            'pageSize' => 10,
        ],
        'branch' => [
            'pageSize' => 10,
        ],
        'wallet' => [
            'pageSize' => 10,
        ],
        'notification' => [
            'pageSize' => 10
        ],
        'activity_log' => [
            'pageSize' => 15
        ],
        'contact_us' => [
            'pageSize' => 15
        ],
        'transaction' => [
            'pageSize' => 15
        ],
    ],

    'redirect' => [
        'verifyUserEmail' => env('APP_FRONTEND_CUSTOMER_URL') . '/login'
    ],
    /**
     * Password configurations
     */
    'password' => [
        /**
         * int|boolean - Check all or a specified number of previous passwords
         */
        'check_all' => true,
        /**
         * int - Number of previous passwords to check
         */
        'number' => 4,

        /**
         * Password notification configurations
         */
        'notify' => [
            /**
             * boolean - Send a notification whenever password is changed
             */
            'change' => true,
        ]
    ],

    /**
     * Registration configurations
     */
    'registration' => [
        /**
         * boolean - Login after registration
         */
        'autologin' => true,

        /**
         * Registration notification configurations
         */
        'notify' => [
            /**
             * boolean - Send a welcome notification after registration
             */
            'welcome' => true,
            /**
             * boolean - Send a verification email after registration
             */
            'verify' => true,
        ]
    ],

    /**
     * Login configurations
     */
    'login' => [

        /**
         * Login notification configurations
         */
        'notify' => [
            /**
             * boolean - Send a notification whenever there
             * has been a login
             */
            'user' => true,
        ],
        /**
         * int - Number of times a user is allowed to authenticate
         * when trying to login
         */
        'maxAttempts' => 3,
        /**
         * int - Number of times a user is allowed to authenticate
         * when trying to login
         */
        'delayMinutes' => 1,
    ],

    /**
     * Cookie configurations
     */
    'cookie' => [
        /**
         * int - Uses minutes.
         */
        'minutes' => 5,

        /**
         * string - Cookie name.
         */
        'name' => 'user_uuid'
    ],

    /**
     * Notification URL
     */
    'notification_url' => [
        /**
         * string - Action urls for registration notifications.
         */
        'registration' => [
            /**
             * string - Action urls for
             * App\Notifications\WelcomeUser
             * notifications.
             */
            'welcome_user' => env('APP_FRONTEND_CUSTOMER_URL')
        ],
        /**
         * string - Action urls for login notifications.
         */
        'login' => [
            /**
             * string - Action urls for
             * App\Notifications\UserLogin
             * notifications.
             */
            'user_login' => env('APP_FRONTEND_CUSTOMER_URL') . '/onboarding/forgot-password'
        ],
        /**
         * string - Action urls for change password notifications.
         */
        'password' => [
            /**
             * string - Action urls for
             * App\Notifications\PasswordChange
             * notifications.
             */
            'password_change' => env('APP_FRONTEND_CUSTOMER_URL') . '/onboarding/forgot-password'
        ],
    ],
];
