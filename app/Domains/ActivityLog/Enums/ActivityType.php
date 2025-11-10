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
    case CustomerContactDetailsAdded = 'customer.contact.details.added';
    case CustomerPersonalDetailsUpdated = 'customer.personal.details.updated';
    case ProfileFieldsCompleted = 'profile.fields.completed';
    case ProfileFieldCreated = 'admin.profile.field.created';
    case ProfileFieldUpdated = 'admin.profile.field.updated';
    case ProfileFieldDeleted = 'admin.profile.field.deleted';

    case CourseCreated = 'course.created';
    case CourseUpdated = 'course.updated';
    case CourseDeleted = 'course.deleted';
    case CustomerEnrolled = 'course.customer.enrolled';
    case CustomerUnenrolled = 'course.customer.unenrolled';

    case SystemError = 'system.error';
}
