<?php

namespace App\Domains\ActivityLog\Enums;

enum ActivityType: string
{
    case StudentRegistered = 'student.registered';
    case StudentLoginSuccess = 'student.login.success';
    case StudentLoginFailed = 'student.login.failed';
    case StudentLoggedOut = 'student.logged.out';
    case StudentVerificationSent = 'student.verification.sent';
    case StudentVerificationVerified = 'student.verification.verified';
    case StudentVerificationFailed = 'student.verification.failed';
    case StudentContactChanged = 'student.contact.changed';
    case StudentContactDetailsAdded = 'student.contact.details.added';
    case StudentPersonalDetailsUpdated = 'student.personal.details.updated';
    case ProfileFieldsCompleted = 'profile.fields.completed';
    case ProfileFieldCreated = 'admin.profile.field.created';
    case ProfileFieldUpdated = 'admin.profile.field.updated';
    case ProfileFieldDeleted = 'admin.profile.field.deleted';

    case CourseCreated = 'course.created';
    case CourseUpdated = 'course.updated';
    case CourseDeleted = 'course.deleted';
    case CoursePurchased = 'course.purchased';
    case StudentEnrolled = 'course.student.enrolled';
    case StudentUnenrolled = 'course.student.unenrolled';

    case StudentCreatedByAdmin = 'admin.student.created';
    case StudentUpdatedByAdmin = 'admin.student.updated';
    case StudentDeletedByAdmin = 'admin.student.deleted';
    case StudentRestoredByAdmin = 'admin.student.restored';
    case StudentAssignedToCourse = 'admin.student.assigned_to_course';
    case StudentUnenrolledFromCourse = 'admin.student.unenrolled_from_course';

    case UserCreated = 'admin.user.created';
    case UserUpdated = 'admin.user.updated';
    case UserDeleted = 'admin.user.deleted';
    case UserStatusChanged = 'admin.user.status_changed';

    case TeacherCreated = 'admin.teacher.created';
    case TeacherUpdated = 'admin.teacher.updated';
    case TeacherDeleted = 'admin.teacher.deleted';

    case TransactionCreated = 'transaction.created';
    case TransactionCompleted = 'transaction.completed';
    case TransactionFailed = 'transaction.failed';

    case SystemError = 'system.error';
}
