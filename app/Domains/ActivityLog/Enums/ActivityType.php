<?php

namespace App\Domains\ActivityLog\Enums;

enum ActivityType: string
{
    case CustomerRegistered = 'customer.registered';
    case CustomerLoginSuccess = 'customer.login.success';
    case CustomerLoginFailed = 'customer.login.failed';
    case CustomerLoggedOut = 'customer.logged.out';
    case CustomerVerificationSent = 'customer.verification.sent';
    case CustomerVerificationVerified = 'customer.verification.verified';
    case CustomerVerificationFailed = 'customer.verification.failed';
    case CustomerContactChanged = 'customer.contact.changed';

    case SystemError = 'system.error';
}
